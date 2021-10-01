@extends('user.auth.layout', ['formType' => 'login-wrap'])

@section('content')
<div class="sigin-form-title text-center">Sign In</div>
<div class="login-form">
    <!-- signin form -->
    <form method="post" action="{{ route('user.auth.login') }}">
        @csrf

        @if ($errors->has("social_duplication"))
            <div class="alert alert-danger">
                <ul>
                    <li>{!! $errors->first("social_duplication") !!}</li>
                </ul>
            </div>
            <br />
        @endif

        <div class="group">
            <label for="email" class="label">Email</label>
            <input id="email" name="email" type="text" class="input{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                value="{{ old('email') }}" required>
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
            <input id="check" name="remember" type="checkbox" class="check" {{ old('remember') ? 'checked' : '' }}>
            <label for="check">
                <span class="icon"></span> Keep me Signed in</label>
        </div>
        <div class="group">
            <input type="submit" class="button" value="Sign In">
            {{-- <br />
            <a class="btn btn-block btn-social btn-google" href="{{ route('user.auth.sociallogin', ['google']) }}">
                <span class="fa fa-google"></span> Sign in with Google
            </a> --}}
        </div>
        <div class="hr"></div>
        <div>
            <a href="{{ route('user.auth.forgot-password.showLinkRequestForm') }}">Forgot Password?</a>
            <a class="float-rt" href="{{ route('user.auth.showSignup') }}">Sign Up</a>
        </div>
    </form>
    <!--./ signin form -->
</div>
@endsection
