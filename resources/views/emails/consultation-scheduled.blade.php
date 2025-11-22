<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Scheduled</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', 'Helvetica', sans-serif;
            background-color: #f4f4f4;
            color: #333333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }

        .intro-text {
            font-size: 16px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 30px;
        }

        .details-card {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .details-card h2 {
            color: #667eea;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 12px;
            font-size: 15px;
        }

        .detail-label {
            font-weight: 600;
            color: #333333;
            min-width: 120px;
        }

        .detail-value {
            color: #555555;
        }

        .meeting-link {
            background-color: #667eea;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
            font-weight: 600;
            font-size: 16px;
        }

        .meeting-link:hover {
            background-color: #5568d3;
        }

        .calendar-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .calendar-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }

        .important-note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .important-note p {
            margin: 5px 0;
            font-size: 14px;
            color: #856404;
        }

        .footer {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Consultation Confirmed</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Dear {{ $scheduledSession->name }},</p>

            <p class="intro-text">
                Thank you for scheduling a consultation with P23 Africa. We're excited to connect with you and discuss
                how we can support your goals. Your consultation has been successfully scheduled and confirmed.
            </p>

            <!-- Consultation Details -->
            <div class="details-card">
                <h2>📅 Consultation Details</h2>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->format('l, F j, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->format('g:i A') }}
                        (EAT)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">60 minutes</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type:</span>
                    <span class="detail-value">{{ ucfirst($scheduledSession->consultation_type) }}</span>
                </div>
            </div>

            @if ($scheduledSession->google_meet_link)
                <!-- Google Meet Link -->
                <div style="text-align: center;">
                    <a href="{{ $scheduledSession->google_meet_link }}" class="meeting-link" target="_blank">
                        🎥 Join Google Meet
                    </a>
                </div>
                <p style="text-align: center; font-size: 14px; color: #666666; margin-top: 10px;">
                    Meeting Link: <a href="{{ $scheduledSession->google_meet_link }}"
                        style="color: #667eea;">{{ $scheduledSession->google_meet_link }}</a>
                </p>
            @endif

            <!-- Calendar Integration -->
            @if ($scheduledSession->calendar_html_link)
                <div class="calendar-section">
                    <p style="margin-bottom: 10px; color: #555555;">📆 Add to your calendar:</p>
                    <a href="{{ $scheduledSession->calendar_html_link }}" class="calendar-link" target="_blank">
                        View in Google Calendar
                    </a>
                </div>
            @endif

            <div class="divider"></div>

            <!-- Your Information -->
            <div class="details-card">
                <h2>👤 Your Information</h2>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $scheduledSession->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $scheduledSession->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $scheduledSession->phone }}</span>
                </div>
                @if ($scheduledSession->company)
                    <div class="detail-row">
                        <span class="detail-label">Company:</span>
                        <span class="detail-value">{{ $scheduledSession->company }}</span>
                    </div>
                @endif
            </div>

            @if ($scheduledSession->message)
                <!-- Your Message -->
                <div class="details-card">
                    <h2>💬 Your Message</h2>
                    <p style="margin: 0; color: #555555; line-height: 1.6;">{{ $scheduledSession->message }}</p>
                </div>
            @endif

            <!-- Important Notes -->
            <div class="important-note">
                <p><strong>⏰ Please Note:</strong></p>
                <p>• Join the meeting 5 minutes before the scheduled time</p>
                <p>• Ensure you have a stable internet connection</p>
                <p>• Test your camera and microphone beforehand</p>
                <p>• If you need to reschedule, please contact us at least 24 hours in advance</p>
            </div>

            <div class="divider"></div>

            <p class="intro-text">
                If you have any questions or need to make changes to your consultation, please don't hesitate to reach
                out to us at <a href="mailto:contact@p23africa.com" style="color: #667eea;">contact@p23africa.com</a>.
            </p>

            <p class="intro-text">
                We look forward to speaking with you!
            </p>

            <p style="margin-top: 30px; font-size: 16px;">
                Best regards,<br>
                <strong>The P23 Africa Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>P23 Africa</strong></p>
            <p>Email: <a href="mailto:contact@p23africa.com">contact@p23africa.com</a></p>
            <p>Website: <a href="https://p23africa.com">www.p23africa.com</a></p>
            <p style="margin-top: 20px; font-size: 12px; color: #95a5a6;">
                © {{ date('Y') }} P23 Africa. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
