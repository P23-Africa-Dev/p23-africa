<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Consultation Scheduled</title>
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
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 26px;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            background-color: #e74c3c;
            color: #ffffff;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .content {
            padding: 40px 30px;
        }

        .alert-box {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .alert-box p {
            margin: 0;
            font-size: 15px;
            color: #e65100;
            font-weight: 500;
        }

        .section-title {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .info-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }

        .info-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #555555;
            font-size: 15px;
        }

        .message-box {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .message-box h3 {
            color: #2e7d32;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .message-box p {
            margin: 0;
            color: #555555;
            line-height: 1.6;
            font-size: 14px;
        }

        .quick-actions {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            text-align: center;
        }

        .quick-actions h3 {
            color: #1976d2;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .action-link {
            display: inline-block;
            background-color: #667eea;
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            font-weight: 600;
            font-size: 14px;
        }

        .action-link:hover {
            background-color: #5568d3;
        }

        .stats-box {
            display: flex;
            justify-content: space-around;
            margin: 25px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }

        .stat-label {
            font-size: 12px;
            color: #666666;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .footer {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 25px;
            text-align: center;
            font-size: 13px;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 25px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔔 New Consultation Scheduled</h1>
            <div class="badge">Admin Notification</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="alert-box">
                <p>⚡ A new consultation has been scheduled on your calendar. Please review the details below.</p>
            </div>

            <!-- Consultation Timing -->
            <h2 class="section-title">📅 Consultation Schedule</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Date & Time</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->format('l, F j, Y') }} at
                        {{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->format('g:i A') }} (EAT)
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Consultation Type</div>
                    <div class="info-value">{{ ucfirst($scheduledSession->consultation_type) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Duration</div>
                    <div class="info-value">60 minutes</div>
                </div>
            </div>

            <!-- Client Information -->
            <h2 class="section-title">👤 Client Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $scheduledSession->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">
                        <a href="mailto:{{ $scheduledSession->email }}" style="color: #667eea; text-decoration: none;">
                            {{ $scheduledSession->email }}
                        </a>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">
                        <a href="tel:{{ $scheduledSession->phone }}" style="color: #667eea; text-decoration: none;">
                            {{ $scheduledSession->phone }}
                        </a>
                    </div>
                </div>
                @if ($scheduledSession->company)
                    <div class="info-item">
                        <div class="info-label">Company/Organization</div>
                        <div class="info-value">{{ $scheduledSession->company }}</div>
                    </div>
                @endif
            </div>

            <!-- Client Message -->
            @if ($scheduledSession->message)
                <div class="message-box">
                    <h3>💬 Client Message</h3>
                    <p>{{ $scheduledSession->message }}</p>
                </div>
            @endif

            <div class="divider"></div>

            <!-- Meeting Details -->
            @if ($scheduledSession->google_meet_link)
                <h2 class="section-title">🎥 Meeting Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Google Meet Link</div>
                        <div class="info-value">
                            <a href="{{ $scheduledSession->google_meet_link }}"
                                style="color: #667eea; word-break: break-all;">
                                {{ $scheduledSession->google_meet_link }}
                            </a>
                        </div>
                    </div>
                    @if ($scheduledSession->calendar_html_link)
                        <div class="info-item">
                            <div class="info-label">Google Calendar Event</div>
                            <div class="info-value">
                                <a href="{{ $scheduledSession->calendar_html_link }}" style="color: #667eea;">
                                    View in Google Calendar →
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>⚡ Quick Actions</h3>
                @if ($scheduledSession->google_meet_link)
                    <a href="{{ $scheduledSession->google_meet_link }}" class="action-link" target="_blank">
                        Join Meeting
                    </a>
                @endif
                @if ($scheduledSession->calendar_html_link)
                    <a href="{{ $scheduledSession->calendar_html_link }}" class="action-link" target="_blank">
                        View Calendar
                    </a>
                @endif
                <a href="mailto:{{ $scheduledSession->email }}" class="action-link">
                    Email Client
                </a>
            </div>

            <!-- Stats -->
            <div class="stats-box">
                <div class="stat-item">
                    <div class="stat-value">
                        {{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->diffInDays(\Carbon\Carbon::now()) }}
                    </div>
                    <div class="stat-label">Days Until</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        {{ \Carbon\Carbon::parse($scheduledSession->consultation_date)->diffInHours(\Carbon\Carbon::now()) }}
                    </div>
                    <div class="stat-label">Hours Until</div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Submission Details -->
            <h2 class="section-title">📊 Submission Details</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Submitted On</div>
                    <div class="info-value">{{ $scheduledSession->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Session ID</div>
                    <div class="info-value">#{{ $scheduledSession->id }}</div>
                </div>
            </div>

            <p
                style="margin-top: 30px; font-size: 14px; color: #666666; padding: 15px; background-color: #f8f9fa; border-radius: 6px;">
                <strong>📌 Reminder:</strong> This consultation has been automatically added to your Google Calendar.
                The client has received a confirmation email with all the meeting details.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>P23 Africa - Admin Notification System</strong></p>
            <p style="margin-top: 15px; font-size: 12px; color: #95a5a6;">
                This is an automated notification. Please do not reply to this email.
            </p>
            <p style="font-size: 12px; color: #95a5a6;">
                © {{ date('Y') }} P23 Africa. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
