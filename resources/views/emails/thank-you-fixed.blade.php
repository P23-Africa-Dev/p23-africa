<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - P23 Africa</title>
    <style>
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
            background: #193E47;
            box-shadow: 0px 4px 4px 0px #0000004D;
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

        .greeting {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 8px 0 0 0;
        }

        .content {
            padding: 40px 30px;
        }

        .content h3 {
            color: #2c5f5f;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 20px 0;
            line-height: 1.4;
        }

        .content p {
            color: #555555;
            font-size: 15px;
            line-height: 1.7;
            margin: 0 0 20px 0;
        }

        .highlight-box {
            background-color: #f8fafa;
            border-left: 4px solid #2c5f5f;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .highlight-box p {
            margin: 0;
            font-size: 14px;
            color: #2c5f5f;
            font-weight: 500;
        }

        .services-list {
            background-color: #f8fafa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .services-list h4 {
            color: #2c5f5f;
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 12px 0;
        }

        .services-list ul {
            margin: 0;
            padding-left: 20px;
        }

        .services-list li {
            color: #555555;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .budget-info {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .budget-info p {
            margin: 0;
            color: #f57c00;
            font-weight: 600;
            font-size: 15px;
        }

        .cta-section {
            background-color: #2c5f5f;
            color: white;
            padding: 30px;
            text-align: center;
            margin: 30px 0 0 0;
        }

        .cta-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        .cta-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 0 0 20px 0;
        }

        .contact-button {
            background-color: #ffc107;
            color: #2c5f5f;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .footer {
            padding: 25px 30px;
            text-align: center;
            background-color: #f8fafa;
        }

        .social-icons {
            margin-bottom: 15px;
        }

        .social-icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: 0 8px;
            border-radius: 50%;
            text-decoration: none;
            text-align: center;
            line-height: 40px;
            font-size: 18px;
            color: white;
            font-weight: bold;
        }

        .social-instagram {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }

        .social-tiktok {
            background-color: #000000;
        }

        .social-linkedin {
            background-color: #0077b5;
        }

        .social-website {
            background-color: #2c5f5f;
        }

        .copyright {
            font-size: 12px;
            color: #000000;
            margin: 0;
        }

        .signature {
            color: #2c5f5f;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0 0 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <div class="email-icon">
                <!-- Using a simple email icon with Unicode character as fallback -->
                <img src="https://www.p23africa.com/images/email-header-icon.png" alt="P23 Africa Email Icon"
                    width="115" height="98" style="display: block; max-width: 100%; height: auto;"
                    onerror="this.style.display='none'; this.parentNode.innerHTML='✉️';" />
            </div>
            <h1 class="greeting">Hello {{ $data['name'] }},</h1>
            <p class="subtitle">Thank you for reaching out to P23 Africa.</p>
        </div>

        <div class="content">
            <h3>We acknowledge receipt of your inquiry regarding business consultation for your organization.</h3>

            <p>Our team is currently reviewing your request to ensure we understand your specific needs. <strong>Within
                    the next 24-48 hours</strong>, one of our representatives will reach out with tailored next steps
                and a few discovery questions to help us prepare for the call.</p>

            @if (!empty($data['services']) && is_array($data['services']) && count($data['services']) > 0)
                <div class="services-list">
                    <h4>Services you inquired about:</h4>
                    <ul>
                        @foreach ($data['services'] as $service)
                            <li>{{ $service }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (!empty($data['budget_range']))
                <div class="budget-info">
                    <p><strong>Your Budget Range:</strong> ${{ number_format($data['budget_range']) }}</p>
                </div>
            @endif

            <p>Should you have any relevant company profile, reports, or background information to share, kindly send
                them to <a href="mailto:contact@p23-africa.com"
                    style="color: #2c5f5f; text-decoration: none;">contact@p23-africa.com</a> ahead of the call to help
                us prepare appropriately.</p>

            <div class="highlight-box">
                <p>We appreciate your interest in working with P23 Africa and look forward to helping your business grow
                    strategically.</p>
            </div>

            <p class="signature">Best regards,<br><strong>P23</strong></p>
        </div>

        <div class="cta-section">
            <h2 class="cta-title">Where Top CEOs Connect and Grow</h2>
            <p class="cta-subtitle">A yearly, invite-driven in-person event for leading CEOs to network and share
                experiences through short, 15 connections.</p>
            <a href="#" class="contact-button">Register Now</a>
        </div>

        <div class="footer">
            <div class="social-icons">
                <!-- Using text-based icons for maximum email client compatibility -->
                <a href="https://instagram.com/p23africa/" class="social-icon social-instagram">IG</a>
                <a href="https://tiktok.com/@p23africa?_t=ZN-8zOFQMxpTU6&_r=1" class="social-icon social-tiktok">TT</a>
                <a href="https://linkedin.com/company/p23-africa/" class="social-icon social-linkedin">in</a>
                <a href="https://p23africa.com/" class="social-icon social-website">🌐</a>
            </div>
            <p class="copyright"><b>Copyright</b> © 2025, <b>P23 Africa</b></p>
        </div>
    </div>
</body>

</html>
