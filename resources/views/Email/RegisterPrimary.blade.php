@component('mail::message')
<style>
    button {
        margin: 0 auto;
        padding: 15px 25px;
        border-radius: 20px;
        background-color: #00b2b2;
        font-size: 20px;
        color: white;
        border: 0px;
    }
</style>
Dear {{ $userInfo['first_name'] }}

You are receiving this email because you signed up for membership at wastina.com using the email address {{ $userInfo['first_name'] }}.

If you did not sign up for membership, please ignore this email and nothing else will happen.			
			
If you signed up for membership, please click the link below to validate your email.

<div style="text-align: center">
    <a href="{{ route('user.auth.login') }}"><button type="button" class="btn btn-primary">Validate Email</button></a>
</div>

Warm Regards,

Wastina Administration
@endcomponent
