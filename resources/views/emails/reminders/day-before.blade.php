<p>Hi {{ $firstName }},</p>

<p>Just a quick heads-up — {{ $eventName }} is happening tomorrow!</p>

<p>
    📅 <strong>Date:</strong> {{ $eventDate }} <br>
    🕒 <strong>Time:</strong> {{ $eventTime }} <br>
    📍 <strong>Location:</strong> {{ $link }}
</p>

<p>We can’t wait to welcome you to our interactive event.</p>

<p>Here is your access link: <br>
🔗 <a href="{{ $link }}">{{ $link }}</a>
</p>

<p>If you have any questions or updates, feel free to reply to this email.</p>

<p>See you tomorrow!</p>
