<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enums\MembershipStatus;
use App\Models\Slide;
use App\Models\Membership;
use App\Models\MembershipPending;
use App\Models\MemberPlanType;
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

// mail
use App\Mail\SendPaymentMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOfflinePaymentMail;
use App\Enums\MaturityType;
use App\Enums\PlanType;

class PaymentController extends Controller
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
        return view('user.payments.payment');
    }

    public function paypal() {
        $totalPrice = $this->getTotalPricePlans();

        if ($totalPrice > 0) {
            return $this->paypal_payment($totalPrice);
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

        $totalPrice = $this->getTotalPricePlans();

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
                    $this->updateMembership('Stripe');
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
        $redirect_urls->setReturnUrl(route('user.payments.paypalSuccess')) /** Specify return URL **/
            ->setCancelUrl(route('user.membership.plans'));

 
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
            $this->updateMembership('Paypal');
            
            return redirect()->route('user.members.index');
        }
 
        session()->flash('payment_error', 'Error occured while payment');
        return recirect()->back();
    }

    public function cache() {
        $this->updateMembership('Cache');
        return redirect()->route('user.pendings.index');
    }

    public function updateMembership($paymentType) {
        $user = auth()->user();
        $memberPlans = MembershipPending::where('owner_id', $user->id)
            ->where('status', MembershipStatus::DRAFT)
            ->where('plan_type_id', '<>', PlanType::NOPLAN)
            ->get();

        $mPlanArray = [];

        $memberCount = Membership::where('owner_id', $user->id)->max('order');

        foreach ($memberPlans as $mp) {

            $subTotalPrice = 0;
            if ($mp->user->is_adult == MaturityType::ADULT) {
                $subTotalPrice = $mp->planType->adult_price * 12;
            } else {
                $subTotalPrice = $mp->planType->child_price * 12;
            }

            if ($paymentType == 'Cache') {
                $mp->status = MembershipStatus::PENDING;
                $mp->expires_in = now()->addMonths(12);
                $mp->total_price = $subTotalPrice;
                $mp->save();

                $mPlanArray[] = $mp->id;
            } else {
                $membership = Membership::where('owner_id', $user->id)
                    ->where('user_id', $mp->user_id)
                    ->first();
                
                if ($membership) {
                    $membership->plan_type_id = $mp->plan_type_id;
                    $membership->expires_in = now()->addMonths(12);
                    $membership->total_price =  $subTotalPrice;
                    $membership->status = MembershipStatus::PURCHASE;

                    $membership->save();
                } else {
                    if ($user->id == $mp->user_id) {
                        $lastMemberOrder = 1;
                    } else {
                        if ($memberCount == 0) {
                            $lastMemberOrder = 2;
                            $memberCount = 1;
                        } else {
                            $lastMemberOrder = $memberCount + 1;
                        }
                    }

                    $membershipNumber = $user->member_number . '-' . $lastMemberOrder;

                    $membership = Membership::create(
                        [
                            'owner_id' => $user->id,
                            'user_id' => $mp->user_id,
                            'membership_number' => $membershipNumber,
                            'plan_type_id' => $mp->plan_type_id,
                            'expires_in' => now()->addMonths(12),
                            'total_price' => $subTotalPrice,
                            'status' => MembershipStatus::PURCHASE,
                            'order' => $lastMemberOrder
                        ]
                    );

                    $memberCount++;
                }

                $membershipUser = $membership->user;
                $membershipUser->membership_id = $membership->id;
                $membershipUser->save();

                $mPlanArray[] = $membership->id;

                $mp->delete();

                PaymentModel::create([
                    'owner_id' => $membership->owner_id,
                    'user_id' => $membership->user_id,
                    'owner_name' => $user->userInfo->first_name . ' ' . $user->userInfo->last_name,
                    'user_name' => $membershipUser->userInfo->first_name . ' ' . $membershipUser->userInfo->last_name,
                    'relationship' => $membershipUser->relationship,
                    'plan' => $membership->planType->name,
                    'price' => $subTotalPrice,
                    'expires_in' => $membership->expires_in,
                    'payment_type' => $paymentType,
                    'status' => MembershipStatus::PURCHASE,
                    'created_by' => auth()->user()->email,
                    'created_by_type' => 'USER'
                ]);
            }
        }
        
        $this->sendPaymentEmail($mPlanArray, $paymentType);
    }

    public function sendPaymentEmail($idArray, $paymentType) {
        
        $data = [];
        $index = 0;
        $memberPlans = [];

        if ($paymentType == 'Cache') {
            $memberPlans = MembershipPending::whereIn('id', $idArray)->get();
        } else {
            $memberPlans = Membership::whereIn('id', $idArray)->get();
        }
       
        foreach ($memberPlans as $mp) {
            $user = $mp->user;
            $data[$index]['email'] = $user->email;
            $data[$index]['name'] = $user->userInfo->first_name . ' ' . $user->userInfo->last_name;
            $data[$index]['birthday'] = $user->userInfo->birthday;
            $data[$index]['relationship'] = $user->relationship;
            $data[$index]['plan'] = $mp->planType->name;
            $data[$index]['price'] = $mp->total_price;
            $data[$index]['start_date'] = now()->format('Y-m-d');
            $data[$index]['expires_in'] = $mp->expires_in;

            $index++;
        }

        $ownerUser = auth()->user();
        $ownerEmail = $ownerUser->email;
        $ownerName = $ownerUser->userInfo->first_name;

        if ($paymentType != 'Cache') {
            Mail::to($ownerEmail)->send(new SendPaymentMail($ownerName, $data));
            foreach ($data as $item) {
                if ($item['email']) {
                    Mail::to($item['email'])->send(new SendPaymentMail($item['name'], $data));
                }
            }
        } else {
            Mail::to($ownerEmail)->send(new SendOfflinePaymentMail($ownerName, $data));
            foreach ($data as $item) {
                if ($item['email']) {
                    Mail::to($item['email'])->send(new SendOfflinePaymentMail($item['name'], $data));
                }
            }
        }
    }

    public function history() {
        $payments = PaymentModel::where('owner_id', auth()->id())->get();
        return view('user.payments.history', compact('payments'));
    }

    public function getTotalPricePlans() {
        $members = MembershipPending::getMemberPlans(MembershipStatus::DRAFT, auth()->id());
        $totalPrice = 0;
        foreach( $members as $member) {
            $totalPrice += $member->plan_price;
        }

        $totalPrice = $totalPrice * 12;

        return $totalPrice;
    }

    public function terms() {
        return view('user.payments.terms');
    }
}
