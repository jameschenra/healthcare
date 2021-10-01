@component('mail::message')
Membership Paid

Hi,

This is an email to inform you that you paid following memberships.
<br /><br />
@foreach($memberships as $membership)
    <strong>"{{$membership['relationship']}}" - 
        "{{array_key_exists('email', $membership)? $membership['email'] : ''}}" - 
        "{{$membership['membership_number']}}"" - paid ${{ $membership['price']}} - will be expires in {{ $membership['expires_in'] }}</strong>
    <br />
@endforeach

Thanks
@endcomponent
