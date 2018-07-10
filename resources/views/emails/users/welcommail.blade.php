@component('mail::message')

Hi {{$firstname}},

Thanks for signing up to keep in touch with Weil Organisation. From now on, you will get regular updates. 

Login Credentials are as below:

Email:{{$email}}<br>
Password: {{$password}}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
