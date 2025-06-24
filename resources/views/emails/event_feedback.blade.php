@component('mail::message')
# Thank You for Attending "{{ $event->title }}"

We hope you enjoyed the event. We'd love your feedback to help us improve future events.

@component('mail::form', ['url' => route('feedback.submit')])
    <input type="hidden" name="event_id" value="{{ $event->id }}">
    <input type="hidden" name="email" value="{{ $booking->email }}">

    <p>How would you rate this event?</p>
    <label><input type="radio" name="rating" value="Excellent"> Excellent</label><br>
    <label><input type="radio" name="rating" value="Good"> Good</label><br>
    <label><input type="radio" name="rating" value="Fair"> Fair</label><br>
    <label><input type="radio" name="rating" value="Poor"> Poor</label><br>

    <br>
    <p>Any additional comments?</p>
    <textarea name="comments" rows="4" style="width:100%"></textarea>

    <br><br>
    <button type="submit">Submit Feedback</button>
@endcomponent

Thanks again,<br>
{{ config('app.name') }}
@endcomponent
