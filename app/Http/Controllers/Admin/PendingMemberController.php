<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MembershipRequest;

use App\Enums\MaturityType;
use App\Enums\PlanType;
use App\Enums\UserType;
use App\Enums\MembershipStatus;

use App\Mail\DeleteMembershipMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPaymentMail;

use App\User;
use App\Models\Country;
use App\Models\UserInfo;
use App\Models\Membership;
use App\Models\MemberPlanType;
use App\Models\MembershipPending;

class PendingMemberController extends Controller
{
    public function index()
    {
        /* $users = User::leftJoin('users as uo', 'users.primary_id', 'uo.id')
            ->leftJoin('user_infos as ui', 'users.id', 'ui.user_id')
            ->leftJoin('memberships as ms', 'users.membership_id', 'ms.id')
            ->leftJoin('member_plan_types as mpt', 'ms.plan_type_id', 'mpt.id')
            ->select(
                'users.*',
                DB::raw('IF(uo.email != "", uo.email, users.email) as owner_email'),
                'ui.first_name',
                'ui.last_name',
                'mpt.name as plan_name',
                'ms.membership_number',
                'ms.status'
            )->get(); */
        
        $users = MembershipPending::leftJoin('users as u', 'membership_pendings.user_id', 'u.id')
            ->leftJoin('user_infos as ui', 'u.id', 'ui.user_id')
            ->leftJoin('users as up', 'u.primary_id', 'up.id')
            ->leftJoin('member_plan_types as mpt', 'membership_pendings.plan_type_id', 'mpt.id')
            ->select(
                'u.*',
                DB::raw('IF(up.email != "", up.email, u.email) as owner_email'),
                'ui.first_name',
                'ui.last_name',
                'mpt.name as plan_name',
                'membership_pendings.status'
            )->get();

        return view("admin.users.list", [
            "users" => $users
        ]);
    }

    public function create()
    {
        $countries = Country::all();
        $primaryMembers = User::where('relationship', 'Primary')->get();
        $memberPlanTypes = MemberPlanType::all();

        return view("admin.users.add", compact(
            'countries',
            'primaryMembers',
            'memberPlanTypes'
        ));
    }

    public function store(MembershipRequest $request)
    {
        $data = $request->all();
        if ($request->input('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        if ($data['relationship'] == 'Son' || $data['relationship'] == 'Daughter') {
            $data['is_adult'] = MaturityType::CHILD;
        } else {
            $data['is_adult'] = MaturityType::ADULT;
        }

        if ($data['relationship'] == 'Primary') {
            $data['member_number'] = 'X-' . mt_rand(10000000, 99999999);
        }

        $user = User::Create($data);
        $data['user_id'] = $user->id;

        $userInfo = UserInfo::create($data);

        if ($data['plan_type'] != 0) {

            $ownerId = null;
            if ($data['relationship'] == 'Primary') {
                $ownerId = $data['user_id'];                
            } else {
                $ownerId = $data['primary_id'];
            }

            $membershipPending = MembershipPending::create(
                [
                    'owner_id' => $ownerId,
                    'user_id' => $data['user_id'],
                    'plan_type_id' => $data['plan_type'],
                    'status' => MembershipStatus::DRAFT
                ]
            );

            session([
                'register_user_info' => [
                    'register_type' => 'NEW',
                    'user' => $user,
                    'plan_type' => $data['plan_type']
                ]
            ]);
    
            return redirect()->route("admin.payment.checkout");
        }

        return redirect()->route("admin.users");
    }

    public function edit($id)
    {
        $user = User::find($id);

        if ($user) {
            $countries = Country::all();
            $primaryMembers = User::where('relationship', 'Primary')->get();
            $memberPlanTypes = MemberPlanType::all();

            return view("admin.users.edit", [
                'user' => $user,
                'countries' => $countries,
                'primaryMembers' => $primaryMembers,
                'memberPlanTypes' => $memberPlanTypes
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function update($id, MembershipRequest $request)
    {
        $user = User::find($id);
        
        if ($user) {
            $data = $request->all();

            if ($request->input("password")){
                $data['password'] = bcrypt($request->input("password"));
            } else {
                unset($data['password']);
            }

            $relationship = $data['relationship'];
            $isAdult = 1;
            if ($relationship == 'Son' || $relationship == 'Daughter') {
                $isAdult = 0;
            }
            $user->is_adult = $isAdult;

            $user->update($data);
    
            $userInfo = UserInfo::where('user_id', $id)->first();
            if ($userInfo) {
                $userInfo->update($data);

                $planType = $request->input('plan_type');

                // if membership upgraded 3 times, admin restrict to update
                $membership = Membership::find($user->membership_id);
                if ($membership) {
                    if($membership->plan_type_id < $planType) {
                        if (auth()->user()->role_id == UserType::ADMIN
                            && $membership->admin_update >= 3) {
                            return redirect()->back()->withErrors(['This membership has already been update 3 times.']);
                        }
                    }
                }

                // when upgrade plan redirect to checkout page
                if (($membership && $membership->plan_type_id < $planType) || 
                    (!$membership && $planType != PlanType::NOPLAN)) {
                    session([
                        'register_user_info' => [
                            'register_type' => 'UPGRADE',
                            'user' => $user,
                            'plan_type' => $planType
                        ]
                    ]);

                    return redirect()->route('admin.payment.checkout');
                }
            }
        }
        
        return redirect()->route("admin.users");
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if($user) {
            $members = null;

            if ($user->relationship == 'Primary') {
                $members = $user->members;
            }

            $userName = $user->userInfo->first_name;
            $membershipNumber = 'No Planned';
            $expiresIn = '';
            if ($user->membership) {
                $membershipNumber = $user->membership->membership_number;
                $expiresIn = $user->membership->expires_in;
            }

            $user->delete();

            if ($user->email) {
                Mail::to($user->email)
                    ->send(new DeleteMembershipMail($userName, $membershipNumber, $expiresIn));
            }

            /* if ($members) {
                foreach ($members as $member) {
                    if ($member->email) {
                        Mail::to($member->email)
                            ->send(new DeleteMembershipMail($member->userInfo->first_name, $member->membership));
                    }
                }
            } */
        }
        
        return redirect()->route("admin.users");
    }

    public function genMembershipNumber($ownerId, $relationship) {
        $owner = User::find($ownerId);
        $membershipNumber = $owner->member_number;

        $memberCount = Membership::where('owner_id', $ownerId)->max('order');
        if ($relationship == 'Primary') {
            $memberCount = 1;
        } else {
            if ($memberCount == 0) {
                $memberCount = 1;
            }

            $memberCount++;
        }

        $membershipNumber = $membershipNumber . '-' . $memberCount;

        return [
            'membership_number' => $membershipNumber,
            'member_count' => $memberCount
        ];
    }


}
