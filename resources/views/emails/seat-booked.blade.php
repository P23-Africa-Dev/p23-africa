
@php
    // $firstName = explode(' ', $booking->name)[0];
     $firstName = explode(' ', $booking->name)[0] ?? 'Guest';
    // $event = $booking->event ?? $event;
    $event = $booking->event ?? $event ?? null;
@endphp

@if ($event)
<p>Hi {{ $firstName }},</p>

<p>Thank you for registering! You’re officially confirmed for our upcoming virtual event:</p>

<p>
    🖥️ <strong>Event:</strong> {{ $event->title }} <br>
    📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('jS F, Y') }} <br>
    🕒 <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }} (Your Local Time) <br>
    🔗 <strong>Access Link:</strong> <a href="{{ $link }}">{{ $link }}</a>
</p>

<p><em>(P.S. Save this link—it’s your entry to the event!)</em></p>

<hr>

<h4>What You’ll Need:</h4>
<ul>
    <li>A stable internet connection</li>
    <li>Headphones or speakers</li>
</ul>

<h4>What to Expect:</h4>
<p>A 60-minute interactive workshop focused on building your remote leadership skills.</p>

<hr>

<h4>Quick Tips:</h4>
<ul>
    <li>Join a few minutes early to get settled</li>
    <li>Keep your microphone muted unless speaking</li>
    <li>Bring your questions—we love interaction!</li>
</ul>

<p>If you have any issues joining or questions beforehand, reach out to us at <a
        href="mailto:support@p23africa.com">support@p23africa.com</a>.</p>

<p>Looking forward to seeing you online!</p>

@else
    <p>Event or access details could not be retrieved. Please contact support 
        <a href="mailto:support@p23africa.com">support@p23africa.com</a> or try booking event again!</p>
@endif