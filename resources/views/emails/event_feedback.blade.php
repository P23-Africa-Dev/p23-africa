@component('mail::message')
# Thank You for Attending "{{ $event->title }}"

We hope you enjoyed the event. We'd love your feedback to help us improve future events.

@component('mail::button', ['url' => route('feedback.form', ['event_id' => $event->id, 'email' => $booking->email])])
Give Your Feedback
@endcomponent

Thanks again,<br>
{{ __('P23 Africa') }}
@endcomponent
