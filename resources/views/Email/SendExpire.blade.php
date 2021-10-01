@component('mail::message')
Dear {{ $name }}

You are receiving this email because you started a signup process for membership at wastina.com using the email address {{ $email }}. 

This membership {{ $expireSentence }}
        
If you have any questions, please contact us at support@wastina.com.
        
Warm Regards,

Wastina Administration
        
@endcomponent
