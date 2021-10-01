<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Enums\MembershipStatus;

use App\User;
use App\Models\Membership;
use App\Models\MemberPlanType;
use App\Enums\MaturityType;
use App\Models\Payment as PaymentModel;

// paypal
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

// stripe
use Cartalyst\Stripe\Stripe;
use App\Mail\SendPaymentMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfflinePaymentMail;
use App\Models\MembershipPending;

class MemberController extends Controller
{
    public $api_context;

    public function __construct() {
        $paypal_conf = config('paypal');
    
        $this->api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );

        $this->api_context->setConfig($paypal_conf['settings']);
    }

    public function index() {
        
        $user = auth()->user();
        $ownerId = $user->id;

        if (auth()->user()->relationship != 'Primary') {
            $ownerId = $user->primary_id;
        }

        $members = Membership::getOwnPlans(
            [MembershipStatus::PURCHASE, MembershipStatus::EXPIRED],
            $ownerId
        );

        return view('user.members.list', compact(
            'members'
        ));
    }

    public function showUpgrade($id, $updateType) {
        $planTypes = MemberPlanType::all();

        $membership = Membership::find($id);
        
        if ($updateType == 'new' && $membership->status != MembershipStatus::EXPIRED) {
            return redirect()->route('user.members.index');
        }

        if ($updateType == 'upgrade' && $membership->primary_update > 0) {
            return redirect()->route('user.members.index');
        }

        return view('user.members.upgrade', compact(
            'membership',
            'planTypes',
            'updateType'
        ));
    }

    public function upgrade(Request $request) {
        session()->forget('upgrade_data');
        session()->put('upgrade_data', [
            'membership_id' => $request->input('membership_id'),
            'plan_type' => $request->input('plan_type'),
            'update_type' => $request->input('update_type')
        ]);

        return redirect()->route('user.members.upgrade.showterms');
    }

    public function showTerms() {
        $membership = Membership::find(session('upgrade_data.membership_id'));
        $targetPlan = MemberPlanType::find(session('upgrade_data.plan_type'));

        $startDay = Carbon::parse($membership->created_at);
        $diffMonth = now()->diffInMonths($startDay);
        $restMonth = 12 - $diffMonth;

        $amount = 0;
        if ($membership->user->is_adult) {
            $amount = ($targetPlan->adult_price - $membership->planType->adult_price) * $restMonth;
        } else {
            $amount = ($targetPlan->child_price - $membership->planType->child_price) * $restMonth;
        }

        return view('user.members.showterms', compact(
            'membership',
            'targetPlan',
            'restMonth',
            'amount'
        ));
    }

    public function showPayment() {
        return view('user.members.payment');
    }

    public function paypal(Request $request) {
        if (session()->get('upgrade_data')) {
            $totalPrice = $this->getPriceToPay(session('upgrade_data.membership_id'), 
                session('upgrade_data.plan_type'),
                session('upgrade_data.update_type')
            );

            if ($totalPrice > 0) {
                return $this->paypal_payment($totalPrice);
            }
        }
        
        return redirect()->back();
    }

    public function stripe(Request $request) {
        $this->validate($request, [
            'stripe_name' => 'required',
            'stripe_number' => 'required|digits:16',
            'stripe_month' => 'required|integer',
            'stripe_year' => 'required|integer',
            'stripe_cvc' => 'required|digits:3',
        ]);

        $totalPrice = $this->getPriceToPay(session('upgrade_data.membership_id'), 
            session('upgrade_data.plan_type'),
            session('upgrade_data.update_type')
        );

        if ($totalPrice > 0) {
            try{
                $stripe = Stripe::make(config('stripe.secret'));
                $token = $stripe->tokens()->create([
                    'card' => [
                        'name' => $request->input('stripe_name'),
                        'number' => $request->input('stripe_number'),
                        'exp_month' => $request->input('stripe_month'),
                        'exp_year' => $request->input('stripe_year'),
                        'cvc' => $request->input('stripe_cvc'),
                    ],
                ]);
    
                if (!isset($token['id'])) {
                    session()->flash('payment_error', 'Error occured while payment');
                    return redirect()->back()->withInput();
                }
        
                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => $totalPrice,
                    'description' => 'You purchased for your member.',
                ]);
                
                if ($charge['status'] == 'succeeded') {
                    $this->updateMembership('Stripe', $totalPrice);
                } else {
                    session()->flash('payment_error', 'Error occured while payment');
                    return redirect()->back()->withInput();
                }
            } catch ( \Exception $e ) {
                session()->flash('payment_error', $e->getMessage());
                return redirect()->back()->withInput();
            } catch( \Cartalyst\Stripe\Exception\CardErrorException $e ) {
                session()->flash('payment_error', $e->getMessage());
                return redirect()->back()->withInput();
            } catch( \Cartalyst\Stripe\Exception\MissingParameterException $e ) {
                session()->flash('payment_error', $e->getMessage());
                return redirect()->back()->withInput();
            }
        }

        return redirect()->route('user.members.index');
    }

    public function paypal_payment($totalPrice) {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
 
        $item_1 = new Item();
 
        $item_1->setName('membership for member') /** item name **/
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($totalPrice); /** unit price **/
 
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
 
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($totalPrice);
 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('You purchased for your member');
 
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('user.members.upgrade.paypalsuccess')) /** Specify return URL **/
            ->setCancelUrl(route('user.members.index'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        
        try {
            $payment->create($this->api_context);
        } catch (\Exception $e) {
            session()->flash('payment_error', 'Sorry. Error occured while payment!');
            return redirect()->back();
        }
 
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
 
        /** add payment ID to session **/
        session()->flash('paypal_payment_id', $payment->getId());
 
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return redirect()->away($redirect_url);
        }
 
        session()->flash('payment_error', 'Error occured while payment');
        return recirect()->back();
    }

    public function paypalSuccess(Request $request) {
        $payment_id = session('paypal_payment_id');
        session()->forget('paypal_payment_id');

        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            return redirect()->route('user.membership.index');
        }

        $payment = Payment::get($payment_id, $this->api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->api_context);

        if ($result->getState() == 'approved') {
            $amount = $result->getTransactions()[0]->getAmount()->total;
            $this->updateMembership('Paypal', $amount);
            
            return redirect()->route('user.members.index');
        }
 
        session()->flash('payment_error', 'Error occured while payment');
        return recirect()->back();
    }

    public function cache() {
        $totalPrice = $this->getPriceToPay(session('upgrade_data.membership_id'), 
            session('upgrade_data.plan_type'),
            session('upgrade_data.update_type')
        );

        $this->updateMembership('Cache', $totalPrice);

        return redirect()->route('user.pendings.index');
    }

    public function updateMembership($paymentType, $totalPrice) {
        $membershipId = session('upgrade_data.membership_id');
        $planType = session('upgrade_data.plan_type');
        $updateType = session('upgrade_data.update_type');
        $purchasePrice = $totalPrice;
        session()->forget('upgrade_data');

        if ($membershipId && $planType) {
            $membership = Membership::find($membershipId);
            if ($membership) {
                $expiresIn = $membership->expires_in;
                if ($updateType == 'new') {
                    $expiresIn = now()->addMonths(12);
                } else {
                    //when update plus rest price to old price
                    // $totalPrice += $membership->total_price;
                }

                if ($paymentType == 'Cache') {
                    $msPending = MembershipPending::updateOrCreate(
                        [
                            'user_id' => $membership->user_id,
                        ],
                        [
                            'owner_id' => auth()->id(),
                            'plan_type_id' => $planType,
                            'expires_in' => $expiresIn,
                            'total_price' => $totalPrice,
                            'status' => MembershipStatus::PENDING
                        ]
                    );

                    $membershipId = $msPending->id;
                } else {
                    $membership->plan_type_id = $planType;
                    $membership->total_price = $totalPrice;
                    $membership->expires_in = $expiresIn;
                    if ($updateType == 'new') {
                        $membership->status = MembershipStatus::PURCHASE;
                    } else {
                        $membership->primary_update++;
                    }

                    $membership->save();

                    $owner = $membership->owner;
                    $user = $membership->owner;

                    PaymentModel::create([
                        'owner_id' => $membership->owner_id,
                        'user_id' => $membership->user_id,
                        'owner_name' => $owner->userInfo->first_name . ' ' . $owner->userInfo->last_name,
                        'user_name' => $user->userInfo->first_name . ' ' . $user->userInfo->last_name,
                        'relationship' => $user->relationship,
                        'plan' => $membership->planType->name,
                        'price' => $purchasePrice,
                        'expires_in' => now()->addMonths(12),
                        'payment_type' => $paymentType,
                        'status' => MembershipStatus::PURCHASE,
                        'created_by' => auth()->user()->email,
                        'created_by_type' => 'USER'
                    ]);
                }

                $this->sendPaymentEmail($paymentType, $membershipId);
            }
        }
    }

    public function sendPaymentEmail($paymentType, $membershipId) {
        
        $data = [];

        if ($paymentType == 'Cache') {
            $membership = MembershipPending::where('id', $membershipId)->first();
        } else {
            $membership = Membership::where('id', $membershipId)->first();
        }

        $user = $membership->user;
        $data[0]['name'] = $user->userInfo->first_name . ' ' . $user->userInfo->last_name;
        $data[0]['relationship'] = $user->relationship;
        $data[0]['plan'] = $membership->planType->name;
        $data[0]['birthday'] = $user->userInfo->birthday;
        $data[0]['start_date'] = $membership->created_at;
        $data[0]['expires_in'] = $membership->expires_in;
        $data[0]['price'] = $membership->total_price;
        $data[0]['email'] = $user->email;

        if ($paymentType != 'Cache') {
            $data[0]['membership_number'] = $membership->membership_number;
        }
            
        $ownerEmail = auth()->user()->email;

        if ($paymentType == 'Cache') {
            Mail::to($ownerEmail)->send(new SendOfflinePaymentMail($ownerEmail, $data));
        } else {
            Mail::to($ownerEmail)->send(new SendPaymentMail($ownerEmail, $data));
        }
    }

    public function getPriceToPay($membershipId, $planType, $updateType) {

        $membership = Membership::find($membershipId);
        $targetMembership = MemberPlanType::find($planType);

        $restPrice = 0;

        if ($updateType == 'new') {

            $birthday = Carbon::parse($membership->user->userInfo->birthday);
            $age = now()->diffInYears($birthday);

            $user = $membership->user;
            if ($age > 18) {
                $user->is_adult = MaturityType::ADULT;
            } else {
                $user->is_adult = MaturityType::CHILD;
            }
            $user->save();

            if ($user->is_adult == MaturityType::ADULT) {
                $restPrice = $targetMembership->adult_price * 12;
            } else {
                $restPrice = $targetMembership->child_price * 12;
            }
        } else {
            $startDay = Carbon::parse($membership->created_at);
            $diffMonth = now()->diffInMonths($startDay);
            $restMonth = 12 - $diffMonth;
    
            if ($membership->user->is_adult == MaturityType::ADULT) {
                $restPrice = ($targetMembership->adult_price - $membership->planType->adult_price) * $restMonth;
            } else {
                $restPrice = ($targetMembership->child_price - $membership->planType->child_price) * $restMonth;
            }
        }
        
        return $restPrice;
    }

    public function test() {
        \QrCode::format('png')
            ->size(100)            
            ->generate('ItSolutionStuff.com', public_path('files/qrcode.png'));
    }
}
