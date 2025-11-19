<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - P23 Africa</title>
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
            /* background: #193E47;
            box-shadow: 0px 4px 4px 0px #0000004D;
            box-shadow: 0px 8px 12px 6px #00000026; */
            background: url('https://p23africa.com/images/thank-you-header-email.png') no-repeat center center;
            background-size: cover;
            /* display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; */
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

        .highlight-box {
            margin: 25px 0;
            color: #0D4036;
            font-weight: 700;
            font-size: 16px;
        }

        .highlight-box .signature {
            margin: 0;
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
            /* background-color: #2c5f5f; */
            background: linear-gradient(72.69deg, #1A3E47 3.43%, #23444C 38.4%, #2F626E 60.06%);
            color: white;
            padding: 30px;
            /* text-align: center; */
            margin: 30px 0 0 0;
            position: relative;
        }

        .cta-title {
            font-size: 27px;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        .cta-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 0 0 20px 0;
            width: 308px;
        }

        .cta-description {
            font-size: 13px;
            opacity: 0.8;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        .contact-button {
            background-color: #FCDD0A;
            color: #193E47;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 11px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            position: absolute;
            bottom: 50px;
        }

        @media (max-width: 772px) {
            .contact-button {
                position: relative;
                bottom: 0px;
            }

        }


        .footer {
            padding: 25px 30px;
            text-align: center;
            background-color: #f8fafa;
        }

        .social-icons {
            /* margin-bottom: 15px; */
            margin-top: 15px;
        }

        .social-icons a {
            display: inline-block;
            padding: 10px;
            margin: 0 3px;
            border-radius: 50%;
            text-decoration: none;
            transition: transform 0.3s ease;
            /* display: flex;
            align-items: center;
            text-align: center; */
        }

        .social-icons a:hover {
            transform: translateY(-2px);
        }

        /* .social-instagram {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }

        .social-twitter {
            background-color: #1da1f2;
        }

        .social-linkedin {
            background-color: #0077b5;
        }

        .social-pinterest {
            background-color: #bd081c;
        } */

        .social-icons svg {
            width: 26px;
            height: 26px;
            /* fill: white; */
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
                <img src="https://www.p23africa.com/images/email-envelope.png" alt="P23 Africa Email Icon" />
            </div> 
            <h1 class="greeting">Hello {{ $data['name'] }},</h1>
            <!--<h1 class="greeting">Hello Touka Fernande,</h1>-->
            <p class="subtitle">Thank you for reaching out to <br>P23 Africa.</p>
        </div>

        <div class="content px-md-5">
            <h3>We acknowledge receipt of your inquiry regarding business consultation for your organization.</h3>

            <p>Our team is currently reviewing your request to ensure we understand your business needs thoroughly.
                Within the <strong>next 24-48 hours</strong>, one of our representatives will reach out with tailored
                next steps and a few discovery questions to help us align our approach with your objectives.</p>

            {{-- @if (!empty($data['services']) && is_array($data['services']) && count($data['services']) > 0)
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
            @endif --}}

            <p>Should you have any relevant company profile, reports, or background information to share, kindly send
                them to <a href="mailto:contact@p23africa.com"
                    style="color: #D65F5F; text-decoration: none;"><i>contact@p23africa.com</i></a> ahead of the call to
                help
                us prepare adequately.</p>

            <div class="highlight-box">
                <p>We appreciate your interest in working with P23 Africa and look forward to helping your business grow
                    strategically.</p>
            </div>

            <div class="signature">
                <img src="https://p23africa.com/images/signature.png" class="img" alt="">
                <br>
                <img src="https://p23africa.com/images/Logo2.png" alt="Logo" style="width: 31px; height: 15px;"><br>
            </div>
        </div>

        <div class="cta-section row px-md-5">
            <div class="col-md-8">
                {{-- <h2 class="cta-title">Where Top CEOs <span style="color: #1DEAA5;">Connect and Grow</span></h2> --}}
                <h2 class="cta-title">Join NOEL</h2>
                               <p class="cta-subtitle">A private, trusted platform for vetted CEOs across Africa to exchange referrals, strategic deals, and build partnerships through direct 1:1 connections.</p>
            </div>
            <div class="col-md-4">
                <a href="https://p23africa.com/noel54/interest-form" class="contact-button">Register Now</a>
            </div>
        </div>

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
            <p class="copyright"><b>Copyright</b> © 2025, <b>P23 Africa</b></p>
        </div>
    </div>
</body>
</html>
