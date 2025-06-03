@php
    $firstName = explode(' ', $seat->name)[0];
@endphp

<p>Hi {{ $firstName }},</p>

<p>This is a quick reminder that the event you registered for is happening in just few minutes!</p>

<p>
    🖥️ <strong>Event:</strong> {{ $event->title }}<br>
    📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('jS F, Y') }}<br>
    🕒 <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}<br>
</p>

<p><strong>Join Link:</strong> <a href="{{ $link }}">{{ $link }}</a></p>

<p>We look forward to having you there!</p>
<p>— P23 Africa Team</p>
