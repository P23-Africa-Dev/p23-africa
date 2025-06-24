@php
    $start = \Carbon\Carbon::parse($event->date_time)->format('Ymd\THis\Z');
    $end = \Carbon\Carbon::parse($event->date_time)->addHour()->format('Ymd\THis\Z');
    $title = urlencode($event->title);
    $details = urlencode($event->description ?? 'Event Reminder');
    $location = urlencode($event->location ?? '');
    $link = $event->is_public ? $event->event_link : url('/private-event/' . $booking->unique_code);
@endphp



<p>Hi {{ $booking->name }},</p>

<p>This is a {{ $type == '24hr' ? '24-hour' : '15-minute' }} reminder for your upcoming event:</p>

<ul>
    <li><strong>Event Title:</strong> {{ $event->title }}</li>
    <li><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($event->date_time)->format('l, F jS Y h:i A') }}</li>
    <li><strong>Location:</strong> {{ $event->location ?? 'N/A' }}</li>

    <br>

    <li><strong>Event Link:</strong>
        <a href="{{ $event->is_public ? $event->event_link : url('/private-event/' . $booking->unique_code) }}">
            {{ $event->is_public ? $event->event_link : url('/private-event/' . $booking->unique_code) }}
        </a>
    </li>
</ul>


<p>
    <strong>Add to Calendar:</strong><br>
    <a href="https://www.google.com/calendar/render?action=TEMPLATE&text={{ $title }}&dates={{ $start }}/{{ $end }}&details={{ $details }}&location={{ $location }}&sprop={{ $link }}"
        target="_blank">
        📆 Add to Google Calendar
    </a>
</p>

<p>See you there!</p>

<p>Best regards,<br>Your Team</p>
