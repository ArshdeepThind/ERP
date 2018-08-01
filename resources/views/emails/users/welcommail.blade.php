@component('mail::message')

Hi {{$firstname}},

Thanks for joing ERP organization. From now on, you will get regular updates. 

Login Credentials are as below:

Email:{{$email}}<br>
Password: {{$password}}<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
