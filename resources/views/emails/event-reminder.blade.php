@php
    $firstName = explode(' ', $seat->name)[0];

    $start = \Carbon\Carbon::parse($event->date_time)->format('Ymd\THis\Z');
    $end = \Carbon\Carbon::parse($event->date_time)->addHour()->format('Ymd\THis\Z');
    $title = urlencode($event->title);
    $details = urlencode($event->description ?? 'Event Reminder');
    $location = urlencode($event->location ?? '');
    // $link = $event->is_public ? $event->event_link : url('/private-event/' . $booking->unique_code);
@endphp

<p>Hi {{ $firstName }},</p>

<p>This is a quick reminder that the event you registered for is happening in just few minutes!</p>

<p>
    🖥️ <strong>Event:</strong> {{ $event->title }}<br>
    📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('jS F, Y') }}<br>
    🕒 <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}<br>
</p>

<p><strong>Join Link:</strong> <a href="{{ $link }}">{{ $link }}</a></p>
<br>
<p>
    <strong>Add to Calendar:</strong><br>
    <a href="https://www.google.com/calendar/render?action=TEMPLATE&text={{ $title }}&dates={{ $start }}/{{ $end }}&details={{ $details }}&location={{ $location }}&sprop={{ $link }}"
        target="_blank">
        📆 Add to Google Calendar
    </a>
</p>

<p>We look forward to having you there!</p>
<p>— P23 Africa Team</p>
