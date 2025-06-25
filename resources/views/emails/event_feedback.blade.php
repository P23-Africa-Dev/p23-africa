<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #ffffff; padding: 20px; color: #000000;">

    <div style="max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; padding: 30px; background-color: #f2f2f2;">
        <h2 style="text-align: center;">Thank You for Attending "{{ $event->title }}"</h2>
        <p>
            We hope you enjoyed the event. We'd love your feedback to help us improve future events.
        </p>

        <div style="text-align: center; margin: 20px 0;">
            <p style="margin-bottom: 10px; font-size: 18px;">How would you rate this event?</p>
            @for ($i = 1; $i <= 5; $i++)
                <a href="{{ route('feedback.form', ['event_id' => $event->id, 'email' => $booking->email, 'rating' => $i]) }}"
                   style="text-decoration: none; font-size: 32px; margin: 0 5px;">
                    &#9733;
                </a>
            @endfor
        </div>

        <p>
            Thanks again,<br>
            <strong>P23 Africa</strong>
        </p>
    </div>

</body>
</html>
