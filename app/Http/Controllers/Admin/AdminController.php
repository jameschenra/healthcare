<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Enums\MaturityType;
use App\Enums\MembershipStatus;
use App\Enums\UserType;

use App\User;
use App\Models\Membership;
use App\Models\Payment as PaymentModel;


class AdminController extends Controller
{
    public function home()
    {
        $user = auth()->user();

        $query = Membership::leftJoin('users as u', 'memberships.user_id', 'u.id');

        if ($user->role_id != UserType::SUPERADMIN) {
            $query = $query->where('created_by', $user->email);
        }
        
        $childCount = $query->where('u.is_adult', MaturityType::CHILD)
            ->where('memberships.status', MembershipStatus::PURCHASE)
            ->count();
        
        $query = Membership::leftJoin('users as u', 'memberships.user_id', 'u.id');
        if ($user->role_id != UserType::SUPERADMIN) {
            $query = $query->where('created_by', $user->email);
        }
        $adultCount = $query->where('u.is_adult', MaturityType::ADULT)
            ->where('memberships.status', MembershipStatus::PURCHASE)
            ->count();

        $totalPrice = 0;
        $lastPrice = 0;

        if ($user->role_id == UserType::SUPERADMIN) {
            $totalPrice = PaymentModel::sum('price');

            $lastPrice = PaymentModel::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('price');
        } else {
            $totalPrice = PaymentModel::where('created_by', $user->email)
                ->sum('price');

            $lastPrice = PaymentModel::where('created_by', $user->email)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('price');
        }

        // $memberships = Membership::orderBy('created_at')->get();

        $query = Membership::leftJoin('users as u', 'memberships.user_id', 'u.id');
        if ($user->role_id != UserType::SUPERADMIN) {
            $query->where('created_by', $user->email);
        }

        $membershipAdults = $query->where('u.is_adult', MaturityType::ADULT)
            ->whereRaw('Year(memberships.created_at) = Year(CURRENT_DATE)')
            ->select('memberships.*', 'u.is_adult')
            ->get()->groupBy(function($d) {
                return Carbon::parse($d->created_at)->format('m');
            })->toArray();

        $query = Membership::leftJoin('users as u', 'memberships.user_id', 'u.id');
        if ($user->role_id != UserType::SUPERADMIN) {
            $query->where('created_by', $user->email);
        }
        
        $membershipChilds = $query->where('u.is_adult', MaturityType::CHILD)
            ->whereRaw('Year(memberships.created_at) = Year(CURRENT_DATE)')
            ->select('memberships.*', 'u.is_adult')
            ->get()->groupBy(function($d) {
                return Carbon::parse($d->created_at)->format('m');
            })->toArray();

        $mAdultMembers = [];
        $mChildMembers = [];

        for ($i=1; $i<=12; $i++) {
            $monthKey = sprintf('%02d', $i);
            if (array_key_exists($monthKey, $membershipAdults)) {
                $mAdultMembers[$i-1] = count($membershipAdults[$monthKey]);
            } else {
                $mAdultMembers[$i-1] = 0;
            }

            if (array_key_exists($monthKey, $membershipChilds)) {
                $mChildMembers[$i-1] = count($membershipChilds[$monthKey]);
            } else {
                $mChildMembers[$i-1] = 0;
            }
        }

        $userRevenues = [];
        $userPayments = [];
        if ($user->role_id == UserType::SUPERADMIN) {
            $userPayments = PaymentModel::whereRaw('Year(created_at) = Year(CURRENT_DATE)')
                ->where('created_by_type', 'USER')
                ->get()->groupBy(function($d) {
                    return Carbon::parse($d->created_at)->format('m');
                })->toArray();
        }
        
        $adminRevenues = [];
        $query = PaymentModel::whereRaw('Year(created_at) = Year(CURRENT_DATE)');
        if ($user->role_id != UserType::SUPERADMIN) {
            $query->where('created_by', $user->email);
        }
        $adminPayments = $query->where('created_by_type', 'ADMIN')
            ->get()->groupBy(function($d) {
                return Carbon::parse($d->created_at)->format('m');
            })->toArray();

        for ($i=1; $i<=12; $i++) {
            $monthKey = sprintf('%02d', $i);

            if ($user->role_id == UserType::SUPERADMIN) {
                $userRevenues[$i-1] = 0;
                if (array_key_exists($monthKey, $userPayments)) {
                    foreach($userPayments[$monthKey] as $item) {
                        $userRevenues[$i-1] += $item['price'];
                    }
                }
            }
            
            $adminRevenues[$i-1] = 0;
            if (array_key_exists($monthKey, $adminPayments)) {
                foreach($adminPayments[$monthKey] as $item) {
                    $adminRevenues[$i-1] += $item['price'];
                }
            }
        }
        
        return view("admin.home",[
            'childCount' => $childCount,
            'adultCount' => $adultCount,
            'totalPrice' => $totalPrice,
            'lastPrice' => $lastPrice,
            'mAdultMembers' => $mAdultMembers,
            'mChildMembers' => $mChildMembers,
            'userRevenues' => $userRevenues,
            'adminRevenues' => $adminRevenues
        ]);
    }
}
