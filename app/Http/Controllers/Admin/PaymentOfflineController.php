<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Enums\MembershipStatus;

use App\Models\Membership;
use App\Models\MembershipPending;
use App\Models\Payment as PaymentModel;

use App\Mail\PendingCompleteMail;
use App\User;

class PaymentOfflineController extends Controller
{
    public function index()
    {
        $plans = MembershipPending::where('status', MembershipStatus::PENDING)->get();
        
        return view('admin.pendings.list', compact(
            'plans'
        ));
    }

    public function approve($id) {
        $pendingPlan = MembershipPending::find($id);
        if ($pendingPlan) {

            $membership = Membership::where('owner_id', $pendingPlan->owner_id)
                ->where('user_id', $pendingPlan->user_id)
                ->first();
            
            if ($membership) {
                $membership->plan_type_id = $pendingPlan->plan_type_id;
                $membership->expires_in = $pendingPlan->expires_in;
                $membership->total_price = $pendingPlan->total_price;
                $membership->status = MembershipStatus::PURCHASE;

                $membership->save();
            } else {
                $memNumber = $this->genMembershipNumber($pendingPlan->owner_id,  $pendingPlan->user_id);

                $membership = Membership::create(
                    [
                        'owner_id' => $pendingPlan->owner_id,
                        'user_id' => $pendingPlan->user_id,
                        'membership_number' => $memNumber['membership_number'],
                        'plan_type_id' => $pendingPlan->plan_type_id,
                        'expires_in' => $pendingPlan->expires_in,
                        'total_price' => $pendingPlan->total_price,
                        'status' => MembershipStatus::PURCHASE,
                        'order' => $memNumber['member_count'],
                        'created_by' => auth()->user()->email
                    ]
                );
            }

            $owner = $membership->owner;

            $user = $membership->user;
            $user->membership_id = $membership->id;
            $user->save();

            $pendingPlan->delete();

            PaymentModel::create([
                'owner_id' => $membership->owner_id,
                'user_id' => $membership->user_id,
                'owner_name' => $owner->userInfo->first_name . ' ' . $owner->userInfo->last_name,
                'user_name' => $user->userInfo->first_name . ' ' . $user->userInfo->last_name,
                'relationship' => $user->relationship,
                'plan' => $membership->planType->name,
                'price' => $membership->total_price,
                'expires_in' => $membership->expires_in,
                'payment_type' => 'Cache',
                'status' => MembershipStatus::PURCHASE,
                'created_by' => auth()->user()->email,
                'created_by_type' => 'ADMIN'
            ]);

            Mail::to($membership->owner->email)
                ->send(new PendingCompleteMail($membership->owner->userInfo->first_name, $membership));

            if ($membership->user->email) {
                Mail::to($membership->user->email)
                    ->send(new PendingCompleteMail($membership->user->userInfo->first_name, $membership));
            }
        }

        return redirect()->back();
    }

    public function delete($id)
    {
        $plan = MembershipPending::find($id);
        if ($plan) {
            $plan->delete();
        }
        
        return redirect()->route("admin.pendings.index");
    }

    public function genMembershipNumber($ownerId, $userId) {
        $owner = User::find($ownerId);
        $membershipNumber = $owner->member_number;

        $memberCount = Membership::where('owner_id', $ownerId)->max('order');
        if ($ownerId == $userId) {  // when primary member
            $memberCount = 1;
        } else {
            if ($memberCount == 0) {
                $memberCount = 1;
            }

            $memberCount++;
        }

        $membershipNumber = $membershipNumber . '-' . ($memberCount);

        return [
            'membership_number' => $membershipNumber,
            'member_count' => $memberCount
        ];
    }
}
