@extends('user.auth.layout', ['formType' => 'login-wrap forgot-wrap'])

@section('content')
<div class="sigin-form-title text-center">Forgot Password</div>
<div class="login-form">
    <!-- signin form -->
    <form method="post" action="{{ route('user.auth.forgot-password.sendResetLinkEmail') }}">
        @csrf

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
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
            <input type="submit" class="button" value="Send Reset Request">
        </div>
        <div class="hr"></div>
        <div>
            <a href="{{ route('user.auth.showLogin') }}">Sign In</a>
            <a class="float-rt" href="{{ route('user.auth.showSignup') }}">Sign Up</a>
        </div>
    </form>
    <!--./ signin form -->
</div>
@endsection
