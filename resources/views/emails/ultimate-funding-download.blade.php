<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P23 Africa Ultimate Funding & Investor Directory Download</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .email-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        .ultimate-funding-header {
            background: linear-gradient(135deg, #0d4036 0%, #52ccb4 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
            position: relative;
        }

        .ultimate-funding-header::before {
            content: "💎";
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
        }

        .ultimate-funding-header h1 {
            color: white !important;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .ultimate-funding-header p {
            color: rgba(255, 255, 255, 0.9) !important;
            margin: 10px 0 0 0;
            font-size: 16px;
        }

        .premium-badge {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #0d4036;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(255, 215, 0, 0.3);
        }

        .highlight-box {
            background: #f8f9fa;
            border-left: 4px solid #52ccb4;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .premium-features {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 25px 0;
            border: 2px solid #52ccb4;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 35px;
            font-size: 16px;
        }

        .feature-list li:before {
            content: "💰";
            position: absolute;
            left: 0;
            font-size: 18px;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .download-section {
            text-align: center;
            background: linear-gradient(135deg, #0d4036 0%, #52ccb4 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
        }

        .download-title {
            color: white !important;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .download-description {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
        }

        .button-premium {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: #0d4036;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .button-secondary {
            background-color: #6c757d;
            color: white;
        }

        .payment-confirmation {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #52ccb4;
        }

        .footer-info {
            background: #0d4036;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
        }

        .footer-info p {
            color: rgba(255, 255, 255, 0.9) !important;
            margin: 5px 0;
        }

        .company-info {
            font-weight: 600;
            color: #52ccb4 !important;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #0d4036;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="ultimate-funding-header">
            <div class="premium-badge">🌟 PREMIUM ACCESS</div>
            <h1>💎 Your Ultimate Funding & Investor Directory is Ready!</h1>
            <p>Exclusive access to Africa's most comprehensive funding database</p>
        </div>

        <h2>Hello {{ $download->name }},</h2>

        @if ($download->payment_completed)
            <div class="payment-confirmation">
                <h3 style="color: #155724; margin-top: 0;">✅ Payment Confirmed!</h3>
                <p><strong>Thank you for your purchase!</strong> Your payment of
                    ${{ number_format($download->amount_paid, 2) }} has been successfully processed.</p>
                <p><small>Payment Reference: {{ $download->payment_reference }}</small></p>
            </div>

            <p>Welcome to the <b>Ultimate Funding & Investor Directory</b>! You now have exclusive access to Africa's
                most comprehensive database of active investors, grant programs, and funding opportunities specifically
                curated for African entrepreneurs.</p>
        @else
            <p>Thank you for your interest in the <b>Ultimate Funding & Investor Directory</b>! We've received your
                information and you can complete your purchase at any time to gain instant access to Africa's most
                comprehensive funding database.</p>
        @endif

        <div class="premium-features">
            <h3 style="color: #0d4036; margin-top: 0;">💎 What's Inside Your Premium Directory:</h3>
            <ul class="feature-list">
                <li><strong>500+ Active Investors</strong>: Verified investors actively funding African startups</li>
                <li><strong>40+ Countries Covered</strong>: Comprehensive coverage across the African continent</li>
                <li><strong>Global Grant Programs</strong>: Access to international funding opportunities</li>
                <li><strong>Investment Focus Areas</strong>: Detailed information on investor preferences</li>
                <li><strong>Contact Information</strong>: Direct access to decision makers</li>
                <li><strong>Funding Ranges</strong>: Clear investment thresholds and amounts</li>
                <li><strong>Application Processes</strong>: Step-by-step guidance for each opportunity</li>
                <li><strong>Success Stories</strong>: Real examples of funded African startups</li>
            </ul>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-number">500+</div>
                <div class="stat-label">Verified Investors</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">40+</div>
                <div class="stat-label">African Countries</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">$2B+</div>
                <div class="stat-label">Available Funding</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">100+</div>
                <div class="stat-label">Grant Programs</div>
            </div>
        </div>

        @if ($download->payment_completed)
            <div class="download-section">
                <h2 class="download-title">📥 Download Your Premium Directory Now</h2>
                <p class="download-description">
                    Click the button below to instantly download your PDF copy of the Ultimate Funding & Investor
                    Directory.
                    Start connecting with investors today!
                </p>

                <a href="{{ asset('playbook/Ultimate-Funding-Investor-Directory.pdf') }}" class="button button-premium">
                    💎 Download Premium Directory PDF
                </a>

                <p style="font-size: 14px; color: rgba(255, 255, 255, 0.8); margin-top: 15px;">
                    <em>File Size: ~5.2MB | Format: PDF | Lifetime Access</em>
                </p>
            </div>

            <div class="highlight-box">
                <h3 style="color: #0d4036; margin-top: 0;">🎯 Your Next Steps:</h3>
                <ol style="margin: 0; padding-left: 20px;">
                    <li>Download the premium directory using the button above</li>
                    <li>Review the investor categories and focus areas</li>
                    <li>Identify investors that match your startup profile</li>
                    <li>Use the provided templates to craft your pitch</li>
                    <li>Start reaching out to potential investors and partners</li>
                </ol>
            </div>
        @else
            <div class="highlight-box">
                <h3 style="color: #0d4036; margin-top: 0;">💳 Complete Your Purchase</h3>
                <p>To access your Ultimate Funding & Investor Directory, please complete your payment of
                    <strong>$50.00</strong>.</p>
                <p>Once payment is confirmed, you'll receive immediate access to the complete directory with all premium
                    features.</p>
            </div>
        @endif

        <h2>🚀 Why This Directory is Different</h2>

        <p>Unlike free resources, this directory provides <strong>verified, up-to-date information</strong> with direct
            contact details and recent funding activity. Every investor and program has been personally researched and
            validated by our team.</p>

        <div style="text-align: center;">
            <a href="https://p23africa.com" class="button button-premium">
                🌍 Visit P23 Africa
            </a>

            <a href="https://p23africa.com/contact" class="button button-secondary">
                💬 Get Support
            </a>
        </div>

        <div class="footer-info">
            @if ($download->payment_completed)
                <p>Payment Confirmed: {{ $download->payment_date->format('F j, Y \a\t g:i A') }}</p>
                <p>Amount Paid: ${{ number_format($download->amount_paid, 2) }}</p>
            @else
                <p>Account Created: {{ $download->created_at->format('F j, Y \a\t g:i A') }}</p>
            @endif
            <p>Sent to: {{ $download->email }}</p>
        </div>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p><strong>Transform Your Funding Journey</strong></p>

        <p>This premium directory gives you the competitive edge you need to secure funding in Africa's growing startup
            ecosystem.
            Access the same investor networks used by successful African unicorns and high-growth companies.</p>

        <p>Best regards,<br>
            <strong>The P23 Africa Team</strong> 💎
        </p>

        <p style="font-size: 12px; color: #999;">
            <em>This directory was requested by {{ $download->email }}. If you didn't request this download, please
                safely ignore
                this email. For support, contact us at contact@p23africa.com</em>
        </p>
    </div>
</body>

</html>
