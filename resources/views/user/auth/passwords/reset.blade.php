@extends('user.auth.layout', ['formType' => 'login-wrap'])

@section('content')
<div class="sigin-form-title text-center">Reset Password</div>
<div class="login-form">
    <!-- signin form -->
    <form method="post" action="{{ route('user.auth.forgot-password.reset') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="group">
            <label for="email" class="label">Email</label>
            <input id="email" name="email" type="text" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                value="{{ $email ?? old('email') }}" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="group">
            <label for="pass" class="label">Password</label>
            <input id="pass" name="password" type="password" class="input{{ $errors->has('password') ? ' is-invalid' : '' }}" data-type="password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="group">
            <label for="password-confirmation" class="label">Re-Enter Password</label>
            <input id="password-confirmation" name="password_confirmation" type="password" class="input" data-type="password" required>
        </div>

        <div class="group">
            <input type="submit" class="button" value="Reset Password">
        </div>
        <div class="hr"></div>
        <div class="row">
            <a href="{{ route('user.auth.showLogin') }}">Sign In</a>
            <a class="float-rt" href="{{ route('user.auth.showSignup') }}">Sign Up</a>
        </div>
    </form>
    <!--./ signin form -->
</div>
@endsection
