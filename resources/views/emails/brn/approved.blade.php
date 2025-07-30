@component('mail::message')
# BRN Application Approved 🎉

Hi {{ $submission->full_name }},

We’re excited to let you know that your BRN application has been **approved**. Welcome aboard!

@component('mail::button', ['url' => 'https://yourplatform.com/brn-dashboard'])
Access BRN Platform
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
