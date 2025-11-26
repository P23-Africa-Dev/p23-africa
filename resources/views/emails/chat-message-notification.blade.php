<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Help Request - P23 Africa</title>
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

        .user-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .user-info h3 {
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🆘 New Help Request</h1>
            <p>A consultation user needs assistance</p>
        </div>

        <div class="content">
            <div class="user-info">
                <h3>👤 User Information</h3>
                <div class="info-row">
                    <span class="label">Name:</span> {{ $application->name }}
                </div>
                <div class="info-row">
                    <span class="label">Email:</span> {{ $application->email }}
                </div>
                <div class="info-row">
                    <span class="label">Phone:</span> {{ $application->phone ?? 'Not provided' }}
                </div>
                <div class="info-row">
                    <span class="label">Company:</span> {{ $application->company ?? 'Not provided' }}
                </div>
                <div class="info-row">
                    <span class="label">Industry:</span> {{ $application->industry ?? 'Not provided' }}
                </div>
                <div class="info-row">
                    <span class="label">Application Status:</span>
                    <span
                        style="
                        padding: 4px 8px; 
                        border-radius: 4px; 
                        background: {{ $application->status === 'paid' ? '#d4edda' : '#fff3cd' }}; 
                        color: {{ $application->status === 'paid' ? '#155724' : '#856404' }}; 
                        font-size: 12px;
                    ">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>

            <div class="message-box">
                <h3>💬 Message:</h3>
                <p style="font-size: 16px; line-height: 1.6;">{{ $chatMessage->message }}</p>
                <div class="timestamp">
                    Sent on {{ $chatMessage->sent_at->format('F j, Y \a\t g:i A') }}
                </div>
            </div>

            <div style="background: #fff8e1; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <p><strong>⚠️ Action Required:</strong></p>
                <p>Please respond to this user as soon as possible. You can view their full consultation profile and
                    respond in the admin dashboard.</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/admin/consultations/' . $application->id) }}"
                    style="
                       background: #0d4036; 
                       color: white; 
                       padding: 12px 30px; 
                       text-decoration: none; 
                       border-radius: 5px; 
                       display: inline-block;
                       font-weight: bold;
                   ">
                    View in Admin Dashboard
                </a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from P23 Africa consultation system.</p>
            <p>&copy; {{ date('Y') }} P23 Africa. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
