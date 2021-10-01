<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use App\Models\Country;
use App\Models\UserInfo;
use App\Enums\MaturityType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterPrimaryMail;
use App\Enums\MembershipStatus;
use App\Models\MembershipPending;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/membership';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function showRegistrationForm()
    {
        $countries = Country::all();
        return view('user.auth.register', compact(
            'countries'
        ));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rule = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], //'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'],
            'phone' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'before:' . date('Y-m-d')],
            'gender' => ['required'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country_id' => ['required'],
            'region' => ['required', 'string', 'max:255'],
        ];
        
        return Validator::make($data, $rule,
            [
                'password.regex' => 'Password must be should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['relationship'] = 'Primary';
        $data['is_adult'] = 1;
        $data['member_number'] = 'X-' . mt_rand(10000000, 99999999);
        $user = User::create($data);
        
        $data['user_id'] = $user->id;
        UserInfo::create($data);

        MembershipPending::create([
            'owner_id' => $user->id,
            'user_id' => $user->id
        ]);

        Mail::to($data['email'])->send(new RegisterPrimaryMail($data));
        
        return $user;
    }
}
