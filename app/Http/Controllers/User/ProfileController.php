<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Models\Country;
use App\Models\UserInfo;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegistrationRequest;

class ProfileController extends Controller
{
    public function index() {
        return view('user.profile.index');
    }

    public function edit() {
        $countries = Country::all();
        return view('user.profile.edit', compact(
            'countries'
        ));
    }

    public function update(UserRegistrationRequest $request) {
        $user = Auth::user();
        $data = $request->all();

        if ($request->input('password')) {
            $user->password = bcrypt($data['password']);
            $user->save();
        }

        UserInfo::where('user_id', auth()->id())->first()->update($data);
        
        return redirect()->route('user.profile.index');
    }

    public function payments() {
        $payments = Membership::where('owner_id', auth()->id())
            ->with('planType')
            ->with('user')
            ->get();

        return view('user.payments.list', compact(
            'payments'
        ));
    }
}
