<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment assistance requested</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2>Payment assistance requested</h2>
    <p>A consultation client could not complete Stripe payment and asked for another option.</p>

    <ul>
        <li><strong>Name:</strong> {{ $application->name }}</li>
        <li><strong>Email:</strong> {{ $application->email }}</li>
        <li><strong>Phone:</strong> {{ $application->phone ?: 'Not provided' }}</li>
        <li><strong>Company:</strong> {{ $application->company ?: 'Not provided' }}</li>
        <li><strong>Booking:</strong> {{ $application->booking_token }}</li>
        <li><strong>Amount due:</strong> {{ strtoupper($currency) }} {{ number_format($amount, 2) }}</li>
        <li><strong>Requested at:</strong> {{ optional($application->payment_assistance_requested_at)->format('d M Y H:i') }}</li>
    </ul>

    @if ($application->payment_assistance_note)
        <p><strong>Client note:</strong><br>{{ $application->payment_assistance_note }}</p>
    @endif

    <p><a href="{{ $adminUrl }}">Open consultation in admin dashboard</a></p>
</body>
</html>
