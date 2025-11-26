<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Scheduled - P23 Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=GT+Walsheim+Trial+Condensed:wght@300;400;500;600;700&display=swap');

        @font-face {
            font-family: "GT Walsheim Con";
            font-weight: 400;
            font-style: normal;
            font-display: swap;
            src: url("https://www.p23africa.com/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedRegular.woff2") format("woff2"),
                url("https://www.p23africa.com/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedRegular.woff") format("woff");
        }

        @font-face {
            font-family: "GT Walsheim Con";
            font-weight: 700;
            font-style: normal;
            font-display: swap;
            src: url("https://www.p23africa.com/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedBold.woff2") format("woff2"),
                url("https://www.p23africa.com/fonts/gt-walsheim-pro/GTWalsheimPro-CondensedBold.woff") format("woff");
        }

        body {
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            font-family: "GT Walsheim Con", -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: url('https://p23africa.com/images/thank-you-header-email.png') no-repeat center center;
            background-size: cover;
            margin-top: 20px;
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .email-icon {
            width: 115px;
            height: 98px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .email-icon img {
            width: 115px;
            height: 98px;
            fill: white;
        }

        .greeting {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 10px;
            opacity: 0.9;
            margin: 8px 0 0 0;
        }

        .content {
            padding: 40px 30px;
            font-size: 16px;
        }

        .content h3 {
            color: #0D4036;
            font-size: 16px;
            font-weight: 500;
            margin: 0 0 20px 0;
            line-height: 1.4;
        }

        .content p {
            color: #0D4036;
            font-size: 16px;
            line-height: 1.7;
            margin: 0 0 20px 0;
        }

        .content p:last-child {
            margin-bottom: 0;
        }

        .details-card {
            background-color: #f8fafa;
            border-left: 4px solid #2c5f5f;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .details-card h2 {
            color: #2c5f5f;
            font-size: 16px;
            font-weight: 600;
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
            color: #0D4036;
            min-width: 120px;
        }

        .detail-value {
            color: #0D4036;
        }

        .meeting-link {
            background-color: #FCDD0A;
            color: #193E47 !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            display: inline-block;
            margin: 20px 0;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .meeting-link:hover {
            background-color: #e6c600;
        }

        .calendar-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8fafa;
            border-radius: 8px;
        }

        .calendar-link {
            color: #2c5f5f;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }

        .important-note {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .important-note p {
            margin: 5px 0;
            font-size: 14px;
            color: #f57c00;
            font-weight: 600;
        }

        .highlight-box {
            margin: 25px 0;
            color: #0D4036;
            font-weight: 700;
            font-size: 16px;
        }

        .signature {
            color: #2c5f5f;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0 0 0;
        }

        .signature .img {
            position: relative;
            width: 105px;
            height: 50px;
            left: -14px;
        }

        .signature img {
            z-index: 1000 !important;
            margin-top: -15px;
        }

        .footer {
            padding: 25px 30px;
            text-align: center;
            background-color: #f8fafa;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            display: inline-block;
            padding: 10px;
            margin: 0 3px;
            border-radius: 50%;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .social-icons a:hover {
            transform: translateY(-2px);
        }

        .social-icons svg {
            width: 26px;
            height: 26px;
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .copyright {
            font-size: 12px;
            color: #000000;
            margin: 0;
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
            <div class="email-icon">
                <img src="https://p23africa.com/img/calendar-icon.png" alt="Calendar" width="115" height="98">
            </div>
            <h1 class="greeting">Hello {{ $scheduledSession->name }},</h1>
            <p class="subtitle">Your consultation with <br>P23 Africa is confirmed.</p>
        </div>

        <!-- Content -->
        <div class="content px-md-5">
            <h3>We're excited to connect with you and discuss how we can support your business goals.</h3>

            <p>
                Thank you for scheduling a consultation with P23 Africa. Your consultation has been successfully
                scheduled and confirmed. Below are your consultation details and important information for our upcoming
                session.
            </p>

            <!-- Consultation Details -->
            <div class="details-card">
                <h2>📅 Consultation Details</h2>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value"> </span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->format('l, F j, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->format('g:i A') }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">90 minutes</span>
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

            <p>
                If you have any questions or need to make changes to your consultation, please don't hesitate to reach
                out to us at <a href="mailto:contact@p23africa.com"
                    style="color: #D65F5F; text-decoration: none;"><i>contact@p23africa.com</i></a>.
            </p>

            <div class="highlight-box">
                <p>We look forward to speaking with you and helping your business grow strategically.</p>
            </div>

            <div class="signature">
                <img src="https://p23africa.com/images/signature.png" class="img" alt="">
                <br>
                <img src="https://p23africa.com/images/Logo2.png" alt="Logo" style="width: 31px; height: 15px;"><br>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-icons">
                <a href="https://instagram.com/p23africa/" class="social-instagram">
                    <img src="https://www.p23africa.com/images/instagram.png" alt="">
                </a>
                <a href="https://tiktok.com/@p23africa?_t=ZN-8zOFQMxpTU6&_r=1" class="social-twitter">
                    <img src="https://www.p23africa.com/images/tiktok.png" alt="">
                </a>
                <a href="https://linkedin.com/company/p23-africa/" class="social-linkedin">
                    <img src="https://www.p23africa.com/images/linkedin.png" alt="">
                </a>
                <a href="https://p23africa.com/" class="social-pinterest">
                    <img src="https://www.p23africa.com/images/web.png" alt="">
                </a>
            </div>
            <p class="copyright"><b>Copyright</b> © {{ date('Y') }}, <b>P23 Africa</b></p>
        </div>
    </div>
</body>

</html>
