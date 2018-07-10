@component('mail::message')
# Introduction

Hey {{$firstname}},

We were notified to send you instructions to reset your password.

In order to reset your password, please click on the link below and follow the steps.



@component('mail::button', ['url' => $url])
RESET PASSWORD
@endcomponent

If this was sent on accident, no worries, just forget about it.<br>

We recommend that you change your password immediately for extra protection.<br>

This link will expire in 24 hours.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
