<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P23 Africa Funding Readiness Guidebook Download</title>
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

        .sales-book-header {
            background: linear-gradient(135deg, #0d4036 0%, #52ccb4 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .sales-book-header h1 {
            color: white !important;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .sales-book-header p {
            color: rgba(255, 255, 255, 0.9) !important;
            margin: 10px 0 0 0;
            font-size: 16px;
        }

        .highlight-box {
            background: #f8f9fa;
            border-left: 4px solid #52ccb4;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .guide-features {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 25px 0;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 25px;
        }

        .feature-list li:before {
            content: "✓";
            color: #52ccb4;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .download-section {
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            border: 2px solid #52ccb4;
        }

        .download-title {
            color: #0d4036 !important;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .download-description {
            color: #666 !important;
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

        .button-success {
            background-color: #52ccb4;
            color: white;
        }

        .button-secondary {
            background-color: #6c757d;
            color: white;
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
    </style>
</head>

<body>
    <div class="email-container">
        <div class="sales-book-header">
            <h1>💰 Your Funding Readiness Guidebook is Ready!</h1>
            <p>Master your business story, understand your numbers, and pitch with confidence</p>
        </div>

        <h2>Hello {{ $download->name }},</h2>

        <p>Thank you for your interest in the <b>Funding Readiness Guidebook</b>! We're excited to share this
            comprehensive guide
            that has been specifically designed for African entrepreneurs seeking funding or sustainable growth.</p>

        <div class="guide-features">
            <h3 style="color: #0d4036; margin-top: 0;">📚 What's Inside Your Guidebook:</h3>
            <ul class="feature-list">
                <li><strong>Master Your Business Story</strong>: Craft compelling narratives that resonate with
                    investors</li>
                <li><strong>Understand Your Numbers</strong>: Financial planning and analysis frameworks</li>
                <li><strong>Pitch with Confidence</strong>: Presentation strategies that get results</li>
                <li><strong>Ready-to-Use Templates</strong>: Pitch deck, funding plan, and assessment tools</li>
                <li><strong>Investor Insights</strong>: What investors really look for in African ventures</li>
            </ul>
        </div>

        <div class="download-section">
            <h2 class="download-title">📥 Download Your Guidebook Now</h2>
            <p class="download-description">
                Click the button below to instantly download your PDF copy of the Funding Readiness Guidebook.
                Save it to your device and start your funding journey today!
            </p>

            <a href="{{ asset('playbook/Funding-Readiness-Guidebook.pdf') }}" class="button button-success">
                📄 Download Guidebook PDF
            </a>

            <p style="font-size: 14px; color: #666; margin-top: 15px;">
                <em>File Size: ~2.8MB | Format: PDF | Compatible with all devices</em>
            </p>
        </div>

        <div class="highlight-box">
            <h3 style="color: #0d4036; margin-top: 0;">🎯 Your Next Steps:</h3>
            <ol style="margin: 0; padding-left: 20px;">
                <li>Download the guidebook using the button above</li>
                <li>Start with the Business Story Framework (Chapter 2)</li>
                <li>Complete the financial assessment tools</li>
                <li>Use the templates to build your pitch deck</li>
                <li>Practice your pitch with our confidence-building tips</li>
            </ol>
        </div>

        <h2>🚀 Ready to Take It Further?</h2>

        <p>Once you've mastered the fundamentals in this guide, explore our <strong>Premium Funding Directory</strong>
            which includes verified investors actively funding African startups across 40+ countries.</p>

        <div style="text-align: center;">
            <a href="https://p23africa.com" class="button button-success">
                🌍 Visit P23 Africa
            </a>

            <a href="https://p23africa.com/contact" class="button button-secondary">
                💬 Get Support
            </a>
        </div>

        <div class="footer-info">
            <p>Downloaded on: {{ $download->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p>Sent to: {{ $download->email }}</p>
        </div>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p><strong>Funding is Limited and Competitive in Africa</strong></p>

        <p>This guide helps you master your business story, understand your numbers, and pitch with confidence —
            so you can attract the right investors or grow sustainably without them. Don't just read it, implement it!
        </p>

        <p>Best regards,<br>
            <strong>The P23 Africa Team</strong> 💰
        </p>

        <p style="font-size: 12px; color: #999;">
            <em>This guidebook was requested by {{ $download->email }}. If you didn't request this download, please
                safely ignore
                this email. For support, contact us at contact@p23africa.com</em>
        </p>
    </div>
</body>

</html>
