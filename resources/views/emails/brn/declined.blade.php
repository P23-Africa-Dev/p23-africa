@component('mail::message')
# BRN Application Declined

Hello {{ $submission->full_name }},

Thank you for applying to join the BRN platform. Unfortunately, based on the provided information, we cannot approve your application at this time.

You may reapply later as your business grows.

Thanks,  
{{ config('app.name') }}
@endcomponent
