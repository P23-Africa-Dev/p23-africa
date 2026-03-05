<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome to {{ config('app.name') }}</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        /* ── Reset ────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            display: block;
        }

        a {
            color: inherit;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #eef2f7;
            color: #1e293b;
        }

        /* ── Wrapper ──────────────────────────────── */
        .email-wrapper {
            width: 100%;
            background-color: #eef2f7;
            padding: 40px 16px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 32px rgba(0, 0, 0, .09);
        }

        /* ── Header ───────────────────────────────── */
        .header {
            background: linear-gradient(135deg, #0a3d32 0%, #1d6154 50%, #1a9e6e 100%);
            padding: 48px 40px 36px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-pattern {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, .07) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(34, 197, 139, .15) 0%, transparent 50%);
            pointer-events: none;
        }

        .header-logo {
            display: inline-block;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 14px;
            padding: 12px 20px;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }

        .header-logo span {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            letter-spacing: .3px;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(34, 197, 139, .18);
            border: 1px solid rgba(34, 197, 139, .4);
            border-radius: 999px;
            padding: 4px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #a7f3d0;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 8px;
            letter-spacing: -.3px;
            line-height: 1.25;
            position: relative;
            z-index: 1;
        }

        .header h1 .name {
            color: #6ee7b7;
        }

        .header p {
            font-size: 15px;
            color: rgba(255, 255, 255, .75);
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* ── Divider bar ──────────────────────────── */
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, #1d6154, #22c58b, #1d6154);
        }

        /* ── Body content ─────────────────────────── */
        .body-content {
            padding: 40px;
        }

        .greeting {
            font-size: 16px;
            color: #334155;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .greeting strong {
            color: #1d6154;
        }

        /* ── Credentials card ─────────────────────── */
        .credentials-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .credentials-header {
            background: linear-gradient(135deg, #f0fdf9, #e6f7f3);
            border-bottom: 1px solid #d1fae5;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .credentials-header .icon-wrap {
            width: 36px;
            height: 36px;
            background: #1d6154;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .credentials-header .icon-wrap svg {
            width: 18px;
            height: 18px;
            fill: #fff;
        }

        .credentials-header h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0f4c38;
            margin: 0;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .credential-row {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .credential-row:last-child {
            border-bottom: none;
        }

        .credential-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            width: 140px;
            flex-shrink: 0;
        }

        .credential-value {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 5px 12px;
            word-break: break-all;
        }

        /* ── Security notice ──────────────────────── */
        .security-notice {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 28px;
        }

        .security-notice .notice-icon {
            font-size: 20px;
            line-height: 1;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .security-notice p {
            font-size: 13.5px;
            color: #78350f;
            line-height: 1.6;
            margin: 0;
        }

        .security-notice p strong {
            color: #92400e;
        }

        /* ── Steps ────────────────────────────────── */
        .steps-section {
            margin-bottom: 32px;
        }

        .steps-section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #475569;
            letter-spacing: .5px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .step-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .step-row:last-child {
            border-bottom: none;
        }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1d6154, #22c58b);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .step-text {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.55;
            padding-top: 3px;
        }

        /* ── CTA button ───────────────────────────── */
        .cta-wrap {
            text-align: center;
            margin-bottom: 32px;
        }

        .cta-button {
            display: inline-block;
            padding: 14px 40px;
            background: linear-gradient(135deg, #1d6154 0%, #22c58b 100%);
            color: #fff !important;
            text-decoration: none !important;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .3px;
            box-shadow: 0 4px 18px rgba(29, 97, 84, .3);
        }

        /* ── Footer ───────────────────────────────── */
        .email-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 28px 40px;
            text-align: center;
        }

        .footer-brand {
            font-size: 15px;
            font-weight: 700;
            color: #1d6154;
            margin-bottom: 6px;
        }

        .footer-tagline {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        .footer-note {
            font-size: 11.5px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-note a {
            color: #1d6154;
            text-decoration: none;
        }

        /* ── Responsive ───────────────────────────── */
        @media only screen and (max-width: 600px) {
            .body-content {
                padding: 28px 20px;
            }

            .header {
                padding: 36px 20px 28px;
            }

            .header h1 {
                font-size: 22px;
            }

            .credential-row {
                flex-direction: column;
                gap: 6px;
            }

            .credential-label {
                width: auto;
            }

            .email-footer {
                padding: 24px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">

            {{-- ── HEADER ──────────────────────────────────── --}}
            <div class="header">
                <div class="header-pattern"></div>
                <div class="header-logo">
                    <span>🌍 {{ config('app.name') }}</span>
                </div>
                <div style="display:block;"></div>
                <div class="header-badge">✦ New Team Member</div>
                <h1>Welcome, <span class="name">{{ $user->name }}</span>!</h1>
                <p>Your account has been created successfully.</p>
            </div>

            <div class="accent-bar"></div>

            {{-- ── BODY ─────────────────────────────────────── --}}
            <div class="body-content">

                <p class="greeting">
                    Hi <strong>{{ explode(' ', $user->name)[0] }}</strong>, we're delighted to have you on board!
                    Your staff account on <strong>{{ config('app.name') }}</strong> is ready.
                    Below are your login credentials — please keep them safe and confidential.
                </p>

                {{-- Credentials card --}}
                <div class="credentials-card">
                    <div class="credentials-header">
                        <div class="icon-wrap">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                            </svg>
                        </div>
                        <h3>Your Login Credentials</h3>
                    </div>

                    <div class="credential-row">
                        <span class="credential-label">Login Email</span>
                        <span class="credential-value">{{ $user->email }}</span>
                    </div>
                    <div class="credential-row">
                        <span class="credential-label">Temporary Password</span>
                        <span class="credential-value">{{ $password }}</span>
                    </div>
                </div>

                {{-- Security notice --}}
                <div class="security-notice">
                    <span class="notice-icon">⚠️</span>
                    <p>
                        <strong>Security Notice:</strong> This is a temporary password.
                        Please change it immediately after your first login.
                        Never share your credentials with anyone.
                    </p>
                </div>

                {{-- Next steps --}}
                <div class="steps-section">
                    <h3>Getting Started</h3>
                    <div class="step-row">
                        <div class="step-num">1</div>
                        <div class="step-text">Click <strong>Login Now</strong> below and enter your email and temporary
                            password.</div>
                    </div>
                    <div class="step-row">
                        <div class="step-num">2</div>
                        <div class="step-text">Go to <strong>Profile &amp; Settings</strong> and set a new secure
                            password.</div>
                    </div>
                    <div class="step-row">
                        <div class="step-num">3</div>
                        <div class="step-text">Complete your profile — add your photo and contact details.</div>
                    </div>
                    <div class="step-row">
                        <div class="step-num">4</div>
                        <div class="step-text">Explore your dashboard and familiarise yourself with your assigned tasks.
                        </div>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="cta-wrap">
                    <a href="{{ route('login') }}" class="cta-button">Login Now &rarr;</a>
                </div>

            </div>{{-- /body-content --}}

            {{-- ── FOOTER ────────────────────────────────────── --}}
            <div class="email-footer">
                <div class="footer-brand">{{ config('app.name') }}</div>
                <div class="footer-tagline">Powering Africa's Tomorrow</div>
                <p class="footer-note">
                    This email was sent to {{ $user->email }} because a staff account was created for you.<br>
                    If you believe this was a mistake, please contact <a
                        href="mailto:support@p23africa.org">support@p23africa.org</a>.
                </p>
            </div>

        </div>{{-- /email-container --}}
    </div>{{-- /email-wrapper --}}
</body>

</html>
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
