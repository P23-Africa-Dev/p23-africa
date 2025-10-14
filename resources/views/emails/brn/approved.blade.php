<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRN Application Approved</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Montserrat', Arial, sans-serif; background-color: #f8fafc; line-height: 1.6;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: #193E47; padding: 40px 30px; text-align: center;">
                            <h1
                                style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 700; font-family: 'Montserrat', Arial, sans-serif;">
                                🎉 Application Approved!
                            </h1>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <!-- Greeting -->
                            <div
                                style="background: #f0fdf4; padding: 25px; border-radius: 10px; border-left: 4px solid #10b981; margin-bottom: 30px;">
                                <p
                                    style="margin: 0 0 15px 0; font-size: 18px; color: #1f2937; font-weight: 500; font-family: 'Montserrat', Arial, sans-serif;">
                                    Dear <strong>{{ $submission->name }}</strong>,
                                </p>

                                <p
                                    style="margin: 0 0 15px 0; font-size: 16px; color: #4b5563; font-family: 'Montserrat', Arial, sans-serif;">
                                    We are delighted to inform you that your <strong> Registration for NOEL </strong>
                                    application has been thoroughly reviewed and <span
                                        style="color: #10b981; font-weight: 600;">successfully approved</span>.
                                </p>

                                <p
                                    style="margin: 0; font-size: 16px; color: #4b5563; font-family: 'Montserrat', Arial, sans-serif;">
                                    You are now one step closer to joining our exclusive network of verified business
                                    professionals. Welcome to the P23 Africa community!
                                </p>
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $registrationUrl }}"
                                    style="display: inline-block; background: #193E47; color: #ffffff; text-decoration: none; padding: 18px 35px; border-radius: 8px; font-size: 18px; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif; box-shadow: 0 4px 6px rgba(25, 62, 71, 0.3); transition: transform 0.2s;">
                                    🚀 Complete Your Registration
                                </a>
                            </div>

                            <!-- Next Steps -->
                            <div style="background: #f1f5f9; padding: 25px; border-radius: 10px; margin-bottom: 30px;">
                                <h2
                                    style="margin: 0 0 20px 0; font-size: 20px; color: #1e293b; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif;">
                                    📋 Next Steps:
                                </h2>
                                <ol
                                    style="margin: 0; padding-left: 20px; color: #475569; font-family: 'Montserrat', Arial, sans-serif;">
                                    <li style="margin-bottom: 12px; font-size: 15px; line-height: 1.6;">
                                        Click the button above to access your registration portal
                                    </li>
                                    <li style="margin-bottom: 12px; font-size: 15px; line-height: 1.6;">
                                        Complete your profile with additional business details
                                    </li>
                                    <li style="margin-bottom: 0; font-size: 15px; line-height: 1.6;">
                                        Set up your account preferences
                                    </li>
                                </ol>
                            </div>

                            <!-- Important Notice -->
                            <div
                                style="background: #fef3c7; border: 1px solid #f59e0b; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px;">
                                <p
                                    style="margin: 0; font-size: 15px; color: #92400e; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif;">
                                    ⏰ <strong>Important:</strong> Please complete your registration within 7 days to
                                    maintain your approved status.
                                </p>
                            </div>

                            <!-- Alternative Link -->
                            <div
                                style="background: #ffffff; border: 1px solid #d1d5db; border-radius: 8px; padding: 25px; margin-bottom: 30px;">
                                <h3
                                    style="margin: 0 0 15px 0; font-size: 16px; color: #6b7280; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif;">
                                    Alternative Access Link:
                                </h3>
                                <p
                                    style="margin: 0 0 15px 0; font-size: 14px; color: #4b5563; font-family: 'Montserrat', Arial, sans-serif;">
                                    If the button above doesn't work, you can copy this link and paste it into your
                                    browser:
                                </p>

                                <!-- Clickable link for easy selection -->
                                <div
                                    style="background: #f9fafb; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb; margin-bottom: 15px;">
                                    <a href="{{ $registrationUrl }}"
                                        style="color: #193E47; text-decoration: none; font-size: 14px; font-family: 'Courier New', monospace; word-break: break-all; display: block; line-height: 1.5;">
                                        {{ $registrationUrl }}
                                    </a>
                                </div>

                                <!-- Instructions -->
                                <!--<div-->
                                <!--    style="background: #eff6ff; padding: 15px; border-radius: 6px; border-left: 4px solid #193E47;">-->
                                <!--    <p-->
                                <!--        style="margin: 0; font-size: 13px; color: #1f2937; font-family: 'Montserrat', Arial, sans-serif; line-height: 1.4;">-->
                                <!--        <strong>📱 On Mobile:</strong> Tap and hold the link above, then select-->
                                <!--        "Copy"<br>-->
                                <!--        <strong>💻 On Desktop:</strong> Triple-click the link above to select all, then-->
                                <!--        Ctrl+C (Windows) or Cmd+C (Mac)-->
                                <!--    </p>-->
                                <!--</div>-->
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background: #193E47; padding: 30px; text-align: center;">
                            <p
                                style="margin: 0 0 5px 0; font-size: 16px; color: #9ca3af; font-family: 'Montserrat', Arial, sans-serif;">
                                Best regards,
                            </p>
                            <p
                                style="margin: 0 0 10px 0; font-size: 18px; color: #ffffff; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif;">
                                P23 Africa Team
                            </p>
                            <p
                                style="margin: 0; font-size: 14px; color: #9ca3af; font-family: 'Montserrat', Arial, sans-serif;">
                                Empowering African Businesses • Building Tomorrow's Network
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
