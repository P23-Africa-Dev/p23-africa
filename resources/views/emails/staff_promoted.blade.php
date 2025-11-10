<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotion Congratulations</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #1d6154 0%, #22c58b 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center !important;
            color: white !important;
        }

        .crown-icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }

        .content {
            padding: 40px 30px;
        }

        .congratulations-text {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
            line-height: 1.7;
        }

        .highlight {
            color: #1d6154;
            font-weight: 600;
        }

        .benefits-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }

        .benefits-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
        }

        .benefits-list li {
            padding: 8px 0;
            font-size: 14px;
            color: #5a6c7d;
            position: relative;
            padding-left: 25px;
        }

        .benefits-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #22c58b;
            font-weight: bold;
            font-size: 16px;
        }

        .cta-button {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 15px 30px;
            background: linear-gradient(135deg, #1d6154 0%, #22c58b 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(29, 97, 84, 0.3);
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 97, 84, 0.4);
        }

        .footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 30px;
            text-align: center;
        }

        .footer-text {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .company-name {
            font-weight: 600;
            color: #22c58b;
        }

        .divider {
            height: 2px;
            background: linear-gradient(135deg, #1d6154 0%, #22c58b 100%);
            margin: 0;
            border: none;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }

            .congratulations-text {
                font-size: 16px;
            }

            .cta-button {
                width: 180px;
                padding: 12px 25px;
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="crown-icon">👑</div>
            <h1>Congratulations!</h1>
        </div>

        <hr class="divider">

        <div class="content">
            <p class="congratulations-text">
                You have been <span class="highlight">promoted to Admin</span> on
                <span class="highlight">{{ config('app.name') }}</span>!
            </p>

            <div class="benefits-section">
                <h3 class="benefits-title">Your New Admin Privileges:</h3>
                <ul class="benefits-list">
                    <li>Full access to the admin dashboard</li>
                    <li>Manage staff members and user accounts</li>
                    <li>Control system resources and settings</li>
                    <li>Access advanced reporting and analytics</li>
                    <li>Configure platform permissions and roles</li>
                </ul>
            </div>

            <p style="text-align: center; color: #5a6c7d; margin: 25px 0;">
                Ready to explore your new administrative powers? Click below to access your dashboard.
            </p>

            <a href="{{ route('login') }}" class="cta-button">
                Access Dashboard
            </a>
        </div>

        <div class="footer">
            <p class="footer-text">
                Welcome to the admin team!<br>
                Best regards,<br>
                <span class="company-name">{{ config('app.name') }} Team</span>
            </p>
        </div>
    </div>
</body>

</html>
