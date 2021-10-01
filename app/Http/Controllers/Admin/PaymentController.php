<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Enums\MembershipStatus;

// stripe
use Cartalyst\Stripe\Stripe;

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

// models
use App\Models\MemberPlanType;
use App\Models\Membership;
use App\Models\Payment as PaymentModel;

use App\Mail\SendPaymentMail;
use App\Mail\SendOfflinePaymentMail;
use App\Models\MembershipPending;
use App\Enums\MaturityType;
use Carbon\Carbon;
use App\Enums\PlanType;
use App\User;

class PaymentController extends Controller
{
    public function pendingList($id) {
        $pendingMembers = MembershipPending::where('owner_id', $id)
            ->where('plan_type_id', '<>', PlanType::NOPLAN)
            ->where('status', MembershipStatus::DRAFT)
            ->get();
        
        return view('admin.payment.pendinglist', compact(
            'id',
            'pendingMembers'
        ));
    }

    public function pendingCheckout(Request $request) {
        session([
            'checkout_info' => [
                'type' => 'PENDING',
                'id' => $request->input('owner_id')
            ]
        ]);

        return redirect()->route('admin.payment.checkout');
    }

    public function upgradeInfo() {
        $userId = session('checkout_info.id');
        $targetPlanId = session('checkout_info.play_type_id');
        $user = User::find($userId);

        $membership = $user->membership;
        $targetPlan = MemberPlanType::find($targetPlanId);

        $startDay = Carbon::parse($membership->created_at);
        $diffMonth = now()->diffInMonths($startDay);
        $restMonth = 12 - $diffMonth;

        $amount = 0;
        if ($user->is_adult) {
            $amount = ($targetPlan->adult_price - $membership->planType->adult_price) * $restMonth;
        } else {
            $amount = ($targetPlan->child_price - $membership->planType->child_price) * $restMonth;
        }

        return view('admin.payment.upgradeinfo', compact(
            'membership',
            'targetPlan',
            'restMonth',
            'amount'
        ));
    }

    public function checkout() {
        if (session('checkout_info')) {
            return view('admin.payment.checkout');
        } else {
            return view('admin.users');
        }
    }

    public function stripe(Request $request) {
        $this->validate($request, [
            'stripe_name' => 'required',
            'stripe_number' => 'required|digits:16',
            'stripe_month' => 'required|integer',
            'stripe_year' => 'required|integer',
            'stripe_cvc' => 'required|digits:3',
        ]);

        $registerType = session('checkout_info.type');
        
        // when pending owner id, else user id to upgrade
        $userId = session('checkout_info.id');

        $planTypeId = null;
        if ($registerType == 'UPGRADE') {
            $planTypeId = session('checkout_info.play_type_id');
        }

        $totalPrice = $this->getPriceToPay($registerType, $userId, $planTypeId);

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
                    $this->updateMembership($registerType, $userId, $planTypeId, 'Stripe', $totalPrice);

                    session()->forget('checkout_info');
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

        return redirect()->route('admin.users');
    }

    public function paypal() {
        $registerType = session('checkout_info.type');
        $userId = session('checkout_info.id');
        $planTypeId = session('checkout_info.play_type_id');

        $totalPrice = $this->getPriceToPay($registerType, $userId, $planTypeId);
        
        if ($totalPrice > 0) {
            return $this->paypal_payment($totalPrice);
        }
        
        return redirect()->route('admin.users');
    }

    public function paypal_payment($totalPrice) {

        $api_context = $this->createPaypalContext();

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
        $redirect_urls->setReturnUrl(route('admin.payment.paypalSuccess')) /** Specify return URL **/
            ->setCancelUrl(route('admin.users'));

 
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        
        try {
            $payment->create($api_context);
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

    public function createPaypalContext() {
        $paypal_conf = config('paypal');
    
        $api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );

        $api_context->setConfig($paypal_conf['settings']);

        return $api_context;
    }

    public function paypalSuccess(Request $request) {

        $registerType = session('checkout_info.type');
        $userId = session('checkout_info.id');
        $planTypeId = session('checkout_info.play_type_id');

        $api_context = $this->createPaypalContext();

        $payment_id = session('paypal_payment_id');
        session()->forget('paypal_payment_id');

        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            return redirect()->route('admin.users');
        }

        $payment = Payment::get($payment_id, $api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $api_context);

        if ($result->getState() == 'approved') {
            $this->updateMembership($registerType, $userId, $planTypeId, 'Paypal', $result->transactions[0]->amount->total);
            session()->forget('register_user_info');

            return redirect()->route('admin.users');
        }
 
        session()->flash('payment_error', 'Error occured while payment');
        return recirect()->back();
    }

    public function cache() {
        $registerType = session('checkout_info.type');
        $userId = session('checkout_info.id');
        $planTypeId = session('checkout_info.play_type_id');

        $totalPrice = $this->getPriceToPay($registerType, $userId, $planTypeId);

        $this->updateMembership($registerType, $userId, $planTypeId, 'Cache', $totalPrice);
        session()->forget('register_user_info');

        return redirect()->route('admin.users');
    }

    public function updateMembership($registerType, $userId, $planTypeId, $paymentType, $totalPrice = 0) {
        
        $user = User::find($userId);
        
        if ($registerType == 'PENDING') {
            $memberPlans = MembershipPending::where('owner_id', $user->id)
                ->where('status', MembershipStatus::DRAFT)
                ->where('plan_type_id', '<>', PlanType::NOPLAN)
                ->get();

            $mPlanArray = [];

            $memberCount = Membership::where('owner_id', $userId)->max('order');

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
                                'order' => $lastMemberOrder,
                                'created_by' => auth()->user()->email
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

            $this->sendPaymentEmail($mPlanArray, $user, $paymentType);

        } else {
            $ownerUser = $user->owner;

            // if user is primary user
            if (!$ownerUser) {
                $ownerUser = $user;
            }
    
            $memberPlan = MembershipPending::where('user_id', $user->id)->first();
    
            $memberCount = Membership::where('owner_id', $ownerUser->id)->max('order');
    
            $mPlanArray = [];
    
            if ($paymentType == 'Cache') {
                if (!$memberPlan) {
                    $memberPlan = MembershipPending::create([
                        'owner_id' => $ownerUser->id,
                        'user_id' => $user->id,
                        'plan_type_id' => $planTypeId,
                        'expires_in' => now()->addMonths(12),
                        'total_price' => $totalPrice,
                        'status' => MembershipStatus::PENDING
                    ]);
                } else {
                    $memberPlan->status = MembershipStatus::PENDING;
                    $memberPlan->expires_in = now()->addMonths(12);
                    $memberPlan->plan_type_id = $planTypeId;
                    $memberPlan->total_price = $totalPrice;

                    $memberPlan->save();
                }
                
                $mPlanArray[] = $memberPlan->id;
            } else {
                $membership = Membership::where('owner_id', $ownerUser->id)
                    ->where('user_id', $user->id)
                    ->first();
                
                if ($membership) {
                    $membership->plan_type_id = $planTypeId;
                    // $membership->expires_in = now()->addMonths(12);
                    $membership->total_price =  $totalPrice;
                    $membership->status = MembershipStatus::PURCHASE;
                    $membership->created_by = auth()->user()->email;
                    $membership->admin_update++;
    
                    $membership->save();
                } else {
                    if ($user->relationship == 'Primary') {
                        $lastMemberOrder = 1;
                    } else {
                        if ($memberCount == 0) {
                            $lastMemberOrder = 2;
                            $memberCount = 1;
                        } else {
                            $lastMemberOrder = $memberCount + 1;
                        }
                    }
    
                    $membershipNumber = $ownerUser->member_number . '-' . $lastMemberOrder;
    
                    $membership = Membership::create(
                        [
                            'owner_id' => $ownerUser->id,
                            'user_id' => $user->id,
                            'membership_number' => $membershipNumber,
                            'plan_type_id' => $planTypeId,
                            'expires_in' => now()->addMonths(12),
                            'total_price' => $totalPrice,
                            'status' => MembershipStatus::PURCHASE,
                            'order' => $lastMemberOrder,
                            'created_by' => auth()->user()->email
                        ]
                    );
    
                    $memberCount++;
    
                    $user->membership_id = $membership->id;
                    $user->save();
                }
    
                $mPlanArray[] = $membership->id;
    
                if ($memberPlan) {
                    $memberPlan->delete();
                }
                
                PaymentModel::create([
                    'owner_id' => $membership->owner_id,
                    'user_id' => $membership->user_id,
                    'owner_name' => $user->userInfo->first_name . ' ' . $user->userInfo->last_name,
                    'user_name' => $user->userInfo->first_name . ' ' . $user->userInfo->last_name,
                    'relationship' => $user->relationship,
                    'plan' => $membership->planType->name,
                    'price' => $totalPrice,
                    'expires_in' => $membership->expires_in,
                    'payment_type' => $paymentType,
                    'status' => MembershipStatus::PURCHASE,
                    'created_by' => auth()->user()->email,
                    'created_by_type' => 'ADMIN'
                ]);
            }
            
            $this->sendPaymentEmail($mPlanArray, $ownerUser, $paymentType);
        }
        
    }


    // userID: owner id when 'pending', user id when upgrade
    public function getPriceToPay($registerType, $userId, $planType = null) {

        if ($registerType == 'PENDING') {

            $members = MembershipPending::getMemberPlans(MembershipStatus::DRAFT, $userId);
            $totalPrice = 0;
            foreach( $members as $member) {
                $totalPrice += $member->plan_price;
            }

            $totalPrice = $totalPrice * 12;

            return $totalPrice;

        } else {
            $targetMembership = MemberPlanType::find($planType);

            $user = User::find($userId);
            $membership = $user->membership;

            $restPrice = 0;
            
            if ($membership) {
                $startDay = Carbon::parse($membership->created_at);
                $diffMonth = now()->diffInMonths($startDay);
                $restMonth = 12 - $diffMonth;
        
                if ($membership->user->is_adult == MaturityType::ADULT) {
                    $restPrice = ($targetMembership->adult_price - $membership->planType->adult_price) * $restMonth;
                } else {
                    $restPrice = ($targetMembership->child_price - $membership->planType->child_price) * $restMonth;
                }
            } else {
                if ($user->is_adult == MaturityType::ADULT) {
                    $restPrice = $targetMembership->adult_price * 12;
                } else {
                    $restPrice = $targetMembership->child_price * 12;
                }
            }
        }
        
        return $restPrice;
    }

    public function sendPaymentEmail($idArray, $ownerUser, $paymentType) {
        
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
}
