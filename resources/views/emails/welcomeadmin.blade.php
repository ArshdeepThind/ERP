@component('mail::message')

Hello {{ ucwords($name)}},

Your password is : {{$password}}

Kindly please change password after you logged in!!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
