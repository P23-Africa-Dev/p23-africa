@php
    $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . $event->event_time);
    $start = $eventDateTime->format('Ymd\THis\Z');
    $end = $eventDateTime->copy()->addHour()->format('Ymd\THis\Z');
    $title = urlencode($event->title);
    $details = urlencode(strip_tags($event->description ?? 'Event Reminder'));
    $location = urlencode($event->venue ?? '');
@endphp

<p>Hi {{ $firstName }},</p>

<p>This is a reminder that the event you registered for is coming up!</p>

<p>
    🖥️ <strong>Event:</strong> {{ $event->title }}<br>
    📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('jS F, Y') }}<br>
    🕒 <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }} (GMT+1)<br>
    📍 <strong>Venue:</strong> {{ $event->venue ?? 'Online' }}<br>
</p>

@if ($link)
    <p><strong>Join Link:</strong> <a href="{{ $link }}">{{ $link }}</a></p>
@endif

<br>
<p>
    <strong>Add to Calendar:</strong><br>
    <a href="https://www.google.com/calendar/render?action=TEMPLATE&text={{ $title }}&dates={{ $start }}/{{ $end }}&details={{ $details }}&location={{ $location }}"
        target="_blank">
        📆 Add to Google Calendar
    </a>
</p>

<p>We look forward to having you there!</p>
<p>— P23 Africa Team</p>
