@component('mail::message')
# Welcome {{ $user->name }}

Your staff account has been created.

**Login Email:** {{ $user->email }}  
**Temporary Password:** {{ $password }}

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent



Thanks,<br>
{{ config('app.name') }}
@endcomponent
