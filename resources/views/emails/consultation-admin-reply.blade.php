<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response from P23 Africa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #0d4036, #2c5f5f);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #0d4036;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .admin-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .admin-info h3 {
            margin-top: 0;
            color: #0d4036;
        }

        .info-row {
            margin: 8px 0;
        }

        .label {
            font-weight: bold;
            color: #666;
        }

        .footer {
            background: #f8f9fa;
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
        }

        .timestamp {
            color: #888;
            font-size: 14px;
            margin-top: 10px;
        }

        .reply-cta {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }

        .reply-button {
            background: #0d4036;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>💬 Response from P23 Africa</h1>
            <p>We've replied to your help request</p>
        </div>

        <div class="content">
            <p>Hi {{ $application->name }},</p>

            <p>Thank you for reaching out to us during your consultation booking process. We've received your message
                and here's our response:</p>

            <div class="message-box">
                <h3>📩 Our Response:</h3>
                <p style="font-size: 16px; line-height: 1.6;">{{ $chatMessage->message }}</p>
                <div class="timestamp">
                    Sent on {{ $chatMessage->sent_at->format('F j, Y \a\t g:i A') }}
                </div>
            </div>

            <div class="admin-info">
                <h3>👤 From: {{ $chatMessage->sender_name }}</h3>
                <p style="margin: 0; color: #666;">P23 Africa Support Team</p>
            </div>

            <div class="reply-cta">
                <p><strong>📧 Need to respond or have more questions?</strong></p>
                <p>Simply reply to this email or continue using the "Call For Help" feature on our website during your
                    booking process.</p>

                @if ($application->status !== 'paid')
                    <a href="{{ route('consultation.payment-selection') }}" class="reply-button">
                        Continue Booking Process
                    </a>
                @elseif($application->scheduledSessions->count() === 0)
                    <a href="{{ route('consultation.schedule-consultation') }}" class="reply-button">
                        Schedule Your Session
                    </a>
                @endif
            </div>

            <div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p><strong>🗓️ Your Consultation Status:</strong></p>
                <p style="margin: 5px 0;">
                    <strong>Application:</strong>
                    @if ($application->status === 'paid')
                        ✅ Payment Complete
                    @else
                        ⏳ Pending Payment
                    @endif
                </p>
                @if ($application->scheduledSessions->count() > 0)
                    <p style="margin: 5px 0;">
                        <strong>Session:</strong> ✅ Scheduled for
                        {{ $application->scheduledSessions->first()->consultation_date->format('F j, Y') }}
                    </p>
                @endif
            </div>

            <p>We're here to help make your consultation booking process as smooth as possible. Don't hesitate to reach
                out if you need any further assistance.</p>

            <p>Best regards,<br>
                The P23 Africa Team</p>
        </div>

        <div class="footer">
            <p>This email was sent in response to your help request on P23 Africa.</p>
            <p>📧 <a href="mailto:contact@p23africa.com">contact@p23africa.com</a> | 🌐 <a
                    href="https://p23africa.com">p23africa.com</a></p>
            <p>&copy; {{ date('Y') }} P23 Africa. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
