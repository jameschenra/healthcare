@extends('user.auth.layout', ['formType' => 'login-wrap signup-wrap'])

@section('content')
<div class="sigin-form-title text-center">Sign Up</div>
<div class="login-form">
    @if (session('payment_error'))
        <div class="alert alert-danger">
            <ul>
                <li>{{ session('payment_error') }}</li>
            </ul>
        </div>
    @endif
    <!-- signin form -->
    <form method="post" action="{{ route('user.auth.signup') }}" autocomplete="off" id="signup-form">
        @csrf

        <!-- name field start -->
        <div class="row">
            <div class="col-md-6">
                <div class="group">
                    <label for="first_name" class="label">First Name</label>
                    <input id="first_name" name="first_name" type="text" class="input{{ $errors->has('first_name') ? ' is-invalid' : '' }}" 
                        value="{{ old('first_name') }}" required />
            
                    @if ($errors->has('first_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="group">
                    <label for="last_name" class="label">Last Name</label>
                    <input id="last_name" name="last_name" type="text" class="input{{ $errors->has('last_name') ? ' is-invalid' : '' }}" 
                        value="{{ old('last_name') }}" required />
            
                    @if ($errors->has('last_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <!--./ name field end -->

        <!-- address field start -->
        <div class="row">
            <div class="col-md-6">
                <div class="group">
                    <label for="address" class="label">Address</label>
                    <input id="address" name="address" type="text" class="input{{ $errors->has('address') ? ' is-invalid' : '' }}"
                        value="{{ old('address') }}" required>
                    
                    @if ($errors->has('address'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('address') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="group">
                    <label for="city" class="label">City</label>
                    <input id="city" name="city" type="text" class="input{{ $errors->has('city') ? ' is-invalid' : '' }}"
                        value="{{ old('city') }}" required>
                    
                    @if ($errors->has('city'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('city') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <!--./ address field end -->
    
        <!-- country field start -->
        <div class="row">
            <div class="col-md-6">
                <div class="group">
                    <label for="country" class="label">Country</label>
                    <select id="country_id" name="country_id" class="input{{ $errors->has('country_id') ? ' is-invalid' : '' }}" required>
                        <option></option>
                        @foreach($countries as $country)    
                            <option value="{{ $country->id }}"{{ old('country_id') == $country->id ? ' selected' : ''}}>
                                {{ $country->country_name }}
                            </option>
                        @endforeach
                    </select>
                    
                    @if ($errors->has('country_id'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('country_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="group">
                    <label for="region" class="label">Region</label>
                    <input id="region" name="region" type="text" class="input{{ $errors->has('region') ? ' is-invalid' : '' }}"
                        value="{{ old('region') }}" required>
                    
                    @if ($errors->has('region'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('region') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <!--./ country field end -->

        <!-- email field start -->
        <div class="row">
            <div class="col-md-6">
                <div class="group">
                    <label for="email" class="label">Email Address</label>
                    <input id="email" name="email" type="email" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        value="{{ old('email') }}" required>
                    
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="group">
                    <label for="phone" class="label">Phone</label>
                    <input id="phone" name="phone" type="text" class="input{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                        value="{{ old('phone') }}" required>
                    
                    @if ($errors->has('phone'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <!--./ email field end -->

        <!-- gender field start -->
        <div class="row">
            <div class="col-md-6">
                <div class="group">
                    <label for="gender" class="label">Gender</label>
                    <select id="gender" name="gender" class="input{{ $errors->has('gender') ? ' is-invalid' : '' }}" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    
                    @if ($errors->has('gender'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('gender') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="group">
                    <label for="birthday" class="label">Birthday</label>
                    <input id="birthday" name="birthday" type="date" class="input{{ $errors->has('birthday') ? ' is-invalid' : '' }}"
                        value="{{ old('birthday') }}" required>
                    
                    @if ($errors->has('birthday'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('birthday') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <!--./ gender field start -->

        <!-- password field start -->
        <div class="row">
            <div class="col-md-6">
                <div class="group">
                    <label for="password" class="label">Password</label>
                    <input id="password" name="password" type="password" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}"
                        autocomplete="new-password" required />
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="group">
                    <label for="password-confirmation" class="label">Re-Enter Password</label>
                    <input id="password-confirmation" name="password_confirmation" type="password" class="input" required />
                </div>
            </div>
        </div>
        <!--./ password field end -->

        <br />
        <div class="group">
            <input type="submit" class="button" value="Sign Up">
        </div>
        <div class="hr"></div>
        <div class="foot-lnk">
            <a href="{{ route('user.auth.showLogin') }}">Already Member?</a>
        </div>
    </form>
    <!--./ signin form -->
</div>
@endsection

@section('js')
<script>
    $(function(){
        var date = new Date();
        var minDate = new Date();
        $('#birthday').attr('max', (date.getFullYear() - 18) + '-12-31');
    });

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
</script>
    
@endsection