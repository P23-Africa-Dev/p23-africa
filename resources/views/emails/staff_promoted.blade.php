@component('mail::message')
# Congratulations!

You have been promoted to an **Admin** on {{ config('app.name') }}.

You can now log in to your dashboard and manage other staff and resources.

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
