@component('mail::message')
# Hello {{$user->name}}

Thank you for creating an account with us. Click button below to verify your account

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent