@component('mail::message')
# BRN Application Approved 🎉

Congratulations, {{ $submission->name }},

We’re excited to let you know that your BRN application has been **approved**. Welcome aboard!

@component('mail::button', ['url' => $registrationUrl])
Complete Registration
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
