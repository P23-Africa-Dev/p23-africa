<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P23 Africa Founders Playbook Download</title>
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

        .playbook-header {
            background: linear-gradient(135deg, #193e47 0%, #1deaa5 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .playbook-header h1 {
            color: white !important;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .playbook-header p {
            color: rgba(255, 255, 255, 0.9) !important;
            margin: 10px 0 0 0;
            font-size: 16px;
        }

        .highlight-box {
            background: #f8f9fa;
            border-left: 4px solid #1deaa5;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .playbook-features {
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
            color: #1deaa5;
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
            border: 2px solid #1deaa5;
        }

        .download-title {
            color: #193e47 !important;
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
            background-color: #1deaa5;
            color: white;
        }

        .button-secondary {
            background-color: #6c757d;
            color: white;
        }

        .footer-info {
            background: #193e47;
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
            color: #1deaa5 !important;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="playbook-header">
            <h1>🚀 Your P23 Africa Founders Playbook is Ready!</h1>
            <p>Your comprehensive guide to entrepreneurial success across Africa</p>
        </div>

        <h2>Hello {{ $download->name }},</h2>

        <p>Thank you for your interest in the <b>P23 Africa Founders Playbook</b>! We're thrilled to share this game-changing resource
        that has helped countless entrepreneurs across the continent scale their businesses and achieve remarkable success.</p>

        {{-- <div class="playbook-features">
            <h3 style="color: #193e47; margin-top: 0;">📚 What's Inside Your Playbook:</h3>
            <ul class="feature-list">
                <li><strong>Strategic Market Insights</strong>: Navigate African markets with confidence</li>
                <li><strong>Proven Growth Frameworks</strong>: Scale sustainably across diverse economies</li>
                <li><strong>Actionable Implementation Steps</strong>: Turn insights into immediate results</li>
                <li><strong>Resource Directory</strong>: Access essential tools and connections</li>
            </ul>
        </div> --}}

        <div class="download-section">
            <h2 class="download-title">📥 Download Your Playbook Now</h2>
            <p class="download-description">
                Click the button below to instantly download your PDF copy of the P23 Africa Founders Playbook.
                Save it to your device and start implementing these proven strategies today!
            </p>

            <a href="{{ asset('playbook/P23 Africa\'s Founders Playbook.pdf') }}" class="button button-success">
                📄 Download Playbook PDF
            </a>

            <p style="font-size: 14px; color: #666; margin-top: 15px;">
                <em>File Size: ~2.5MB | Format: PDF | Compatible with all devices</em>
            </p>
        </div>

        {{-- <div class="highlight-box">
            <h3 style="color: #193e47; margin-top: 0;">🎯 Quick Start Guide:</h3>
            <ol style="margin: 0; padding-left: 20px;">
                <li>Download the playbook using the button above</li>
                <li>Start with Chapter 1: "Understanding African Markets"</li>
                <li>Complete the assessment tools in Section 3</li>
                <li>Implement one strategy from Chapter 5 this week</li>
                <li>Join our community for ongoing support</li>
            </ol>
        </div> --}}

        <h2>🤝 Stay Connected</h2>

        <p>We'd love to hear about your entrepreneurial journey and how this playbook accelerates your success. Share your wins,
        challenges, and feedback with our community!</p>

        <div style="text-align: center;">
            <a href="https://p23africa.com" class="button button-success">
                🌍 Visit P23 Africa
            </a>

            <a href="https://p23africa.com/contact" class="button button-secondary">
                💬 Get Support
            </a>
        </div>

        {{-- <div class="footer-info">
            <p><strong class="company-info">{{ $download->company }}</strong></p>
            <p>Downloaded on: {{ $download->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p>Sent to: {{ $download->email }}</p>
        </div> --}}

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p><strong>Ready to Transform Your Business?</strong></p>

        <p>This playbook contains the exact strategies used by successful African entrepreneurs to build thriving businesses. Don't
        just read it, implement it! Your success story starts today.</p>

        <p>Best regards,<br>
        <strong>The P23 Africa Team</strong> 🚀</p>

        <p style="font-size: 12px; color: #999;">
            <em>This playbook was requested by {{ $download->email }}. If you didn't request this download, please safely ignore
            this email. For support, contact us at contact@p23africa.com</em>
        </p>
    </div>
</body>
</html>
