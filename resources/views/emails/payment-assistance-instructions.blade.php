<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alternative payment option</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <p>Hi {{ $application->name }},</p>

    <p>Thanks for letting us know you need another way to complete payment for your P23 Africa consultation.</p>

    <p><strong>Amount due:</strong> {{ strtoupper($currency) }} {{ number_format($amount, 2) }}</p>
    <p><strong>Booking reference:</strong> {{ $application->booking_token }}</p>

    <p><strong>Payment instructions from our team:</strong></p>
    <div style="white-space: pre-wrap; background: #f7f7f7; padding: 12px; border-radius: 8px;">{{ $instructions }}</div>

    <p>Once you’ve completed payment, reply to this email or keep this booking reference handy so we can confirm and schedule your session.</p>

    <p>Best regards,<br>P23 Africa</p>
</body>
</html>
