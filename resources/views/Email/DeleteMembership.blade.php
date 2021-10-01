@component('mail::message')
Dear {{ $name }}
			
According to your request, we have canceled membership for {{ $membership_number }} effective {{ $expires_in }}.

If you have any questions, please contact us at support@wastina.com.

Warm Regards,

Wastina Administration
        
@endcomponent
