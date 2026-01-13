<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $leadEmail->subject }}</title>
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

        .sender-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .sender-info h3 {
            margin-top: 0;
            color: #0d4036;
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

        .reply-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Message from P23 Africa</h1>
        </div>

        <div class="content">
            <p>Hi {{ $lead->full_name }},</p>

            <div class="message-box">
                {!! nl2br(e($leadEmail->body)) !!}
            </div>

            <div class="sender-info">
                <h3>👤 From: {{ $sender->name }}</h3>
                <p style="margin: 0; color: #666;">{{ $sender->email }}</p>
                <p style="margin: 5px 0 0 0; color: #666;">P23 Africa Team</p>
            </div>

            <div class="reply-notice">
                <p><strong>📧 Want to reply?</strong></p>
                <p>Simply reply directly to this email and we'll receive your message.</p>
            </div>

            <p>Best regards,<br>
                {{ $sender->name }}<br>
                P23 Africa</p>
        </div>

        <div class="footer">
            <p>This email was sent from P23 Africa CRM.</p>
            <p>📧 <a href="mailto:{{ $sender->email }}">{{ $sender->email }}</a> | 🌐 <a
                    href="https://p23africa.com">p23africa.com</a></p>
            <p>&copy; {{ date('Y') }} P23 Africa. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
