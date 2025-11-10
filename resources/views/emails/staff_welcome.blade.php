<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Team</title>
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
            text-align: center !important;
            color: white !important;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,80 0,100"/></svg>');
            background-size: cover;
        }

        .welcome-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
        }

        .welcome-text {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            line-height: 1.7;
        }

        .highlight {
            color: #1d6154;
            font-weight: 600;
        }

        .credentials-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #22c58b;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            position: relative;
        }

        .credentials-section::before {
            content: "🔐";
            position: absolute;
            top: -15px;
            left: 25px;
            background: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 18px;
        }

        .credentials-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .credential-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
            padding: 12px 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .credential-label {
            font-weight: 600;
            color: #5a6c7d;
            font-size: 14px;
        }

        .credential-value {
            font-family: 'Courier New', monospace;
            background: #f1f3f4;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            color: #2c3e50;
            border: 1px solid #dee2e6;
        }

        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 25px 0;
            font-size: 14px;
            color: #856404;
        }

        .security-icon {
            margin-right: 8px;
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

        .next-steps {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }

        .next-steps h3 {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
        }

        .steps-list {
            list-style: none;
            counter-reset: step-counter;
        }

        .steps-list li {
            padding: 10px 0;
            font-size: 14px;
            color: #5a6c7d;
            position: relative;
            padding-left: 40px;
            counter-increment: step-counter;
        }

        .steps-list li:before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 10px;
            background: #22c58b;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
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

            .welcome-text {
                font-size: 16px;
            }

            .credential-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .credential-value {
                width: 100%;
                text-align: center;
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
            <div class="welcome-icon">🎉</div>
            <h1>Welcome {{ $user->name }}!</h1>
            <p>You're now part of our amazing team</p>
        </div>

        <hr class="divider">

        <div class="content">
            <p class="welcome-text">
                Your staff account has been successfully created on
                <span class="highlight">{{ config('app.name') }}</span>.
                We're excited to have you join our team!
            </p>

            <div class="credentials-section">
                <h3 class="credentials-title">Your Login Credentials</h3>

                <div class="credential-row">
                    <span class="credential-label">Login Email:</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>

                <div class="credential-row">
                    <span class="credential-label">Temporary Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="security-notice">
                <strong>🛡️ Security Notice:</strong>
                Please change your password immediately after your first login for security purposes.
                Keep your credentials confidential and never share them with others.
            </div>

            <div class="next-steps">
                <h3>Getting Started - Next Steps:</h3>
                <ol class="steps-list">
                    <li>Click the "Login Now" button below</li>
                    <li>Use your email and temporary password to log in</li>
                    <li>Change your password to something secure</li>
                    <li>Complete your profile information</li>
                    <li>Explore your dashboard and available features</li>
                </ol>
            </div>

            <a href="{{ route('login') }}" class="cta-button">
                Login Now
            </a>
        </div>

        <div class="footer">
            <p class="footer-text">
                Welcome aboard!<br>
                If you have any questions, don't hesitate to reach out.<br>
                <span class="company-name">{{ config('app.name') }} Team</span>
            </p>
        </div>
    </div>
</body>

</html>
