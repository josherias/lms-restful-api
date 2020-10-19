@component('mail::message')
# Hello {{$user->name}}

You changed your email, so we need to verify it ,Click the button below to verify your account.

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent