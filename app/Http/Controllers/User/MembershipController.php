<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Common\Utils;
use App\Enums\PlanType;
use App\Enums\MembershipStatus;
use App\Http\Requests\MemberRequest;

use App\User;
use App\Models\MembershipPending;
use App\Models\Country;
use App\Models\UserInfo;
use App\Models\Membership;
use App\Models\MemberPlanType;


class MembershipController extends Controller
{
    public function index() {
        $planTypes = MemberPlanType::all();

        $membership = Membership::where('user_id', auth()->id())
            ->where('status', MembershipStatus::PURCHASE)
            ->first();

        return view('user.membership.index', compact(
            'membership',
            'planTypes'
        ));
    }

    public function terms($planTypeId) {
        $plan = MemberPlanType::find($planTypeId);
        return view('user.membership.terms', compact(
            'planTypeId',
            'plan'
        ));
    }

    public function signUp($planTypeId, Request $request) {
        
        $membership = MembershipPending::updateOrCreate([
            'user_id' => auth()->id()
        ],[
            'owner_id' => auth()->id(),
            'status' => MembershipStatus::DRAFT,
            'plan_type_id' => $planTypeId,
        ]);

        return redirect()->route('user.membership.plans');
    }

    public function planSummary() {
        $members = MembershipPending::getMemberPlans(MembershipStatus::DRAFT, auth()->id());
        
        return view('user.membership.plans', compact(
            'members'
        ));
    }

    public function create() {
        $memberPlanTypes = MemberPlanType::all();
        $countries = Country::all();

        return view('user.membership.add', compact(
            'memberPlanTypes',
            'countries'
        ));
    }

    public function store(MemberRequest $request) {

        $isAdult = false;
        if (Utils::checkAdult($request->input('birthday'))) {
            $isAdult = true;
        }

        $data = [
            'primary_id' => auth()->id(),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'is_adult' => $isAdult,
            'relationship' => $request->input('relationship')
        ];

        $user = User::create($data);

        $data = $request->all();

        if (!$isAdult) {
            unset($data['phone']);
            unset($data['email']);
            unset($data['password']);
        }
        
        $data['user_id'] = $user->id;
        $userInfo = UserInfo::create($data);

        MembershipPending::updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'owner_id' => auth()->id(),
                'plan_type_id' => $request->input('plan_type'),
                'status' => MembershipStatus::DRAFT
            ]
        );

        if ($request->input('submit_type') == 'add') {
            return redirect()->route('user.membership.create');
        } else {
            return redirect()->route('user.membership.plans');
        }
        
    }

    public function edit($pendingId) {
        $member = MembershipPending::find($pendingId);
        if ($member) {
            $memberPlanTypes = MemberPlanType::all();
            $countries = Country::all();
    
            return view('user.membership.edit', compact(
                'member',
                'memberPlanTypes',
                'countries'
            ));
        } else {
            return redirect()->back();
        }
    }

    public function update(MemberRequest $request) {

        $isAdult = false;
        if (Utils::checkAdult($request->input('birthday'))) {
            $isAdult = true;
        }

        $data = [
            'email' => $request->input('email'),
            'is_adult' => $isAdult,
            'relationship' => $request->input('relationship')
        ];

        if ($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        $member = User::find($request->input('user_id'));
        $member->update($data);

        $data = $request->all();
        
        $userInfo = $member->userInfo;
        $userInfo->update($data);

        $pendingId = $request->input('pending_id');
        $memberPending = MembershipPending::find($pendingId);
        $memberPending->plan_type_id = $data['plan_type'];
        $memberPending->save();

        return redirect()->route('user.membership.plans');
    }

    public function delete($pendingId) {
        $pendingItem = MembershipPending::find($pendingId);

        if ($pendingItem) {
            $user = $pendingItem->user;
            if ($user->id == auth()->id()) {
                $pendingItem->delete();
            } else {
                $user->delete();
            }
        }

        return redirect()->route('user.membership.plans');
    }

    public function pendings() {
        $members = MembershipPending::getMemberPlans(MembershipStatus::PENDING, auth()->id());
        
        return view('user.pendings.index', compact(
            'members'
        ));
    }

    public function pendings_edit($id) {
        $member = Member::getMember($id, MembershipStatus::PENDING, auth()->id());
        $countries = Country::all();

        return view('user.pendings.edit', compact(
            'member',
            'countries'
        ));
    }

    public function pendings_delete($id) {
        if ($id == auth()->id()) {
            $membershipId = auth()->user()->userInfo->membership_id;
            $membership = Membership::find($membershipId);
            if ($membership) {
                $membership->delete();
            }
        } else {
            $member = Member::find($id);
            if( $member) {
                $member->delete();
            }
        }

        return redirect()->route('user.pendings.index');
    }
}
