<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOEL Registration Received</title>
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
                                📩 Submission Received
                            </h1>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <!-- Greeting -->
                            <div
                                style="background: #eff6ff; padding: 25px; border-radius: 10px; border-left: 4px solid #193E47; margin-bottom: 30px;">
                                <p
                                    style="margin: 0 0 15px 0; font-size: 18px; color: #1f2937; font-weight: 500; font-family: 'Montserrat', Arial, sans-serif;">
                                    Dear <strong>{{ $submission->full_name }}</strong>,
                                </p>

                                <p
                                    style="margin: 0 0 15px 0; font-size: 16px; color: #4b5563; font-family: 'Montserrat', Arial, sans-serif;">
                                    This email confirms receipt of your <strong>NOEL registration</strong> submission.
                                </p>

                                <p
                                    style="margin: 0; font-size: 16px; color: #4b5563; font-family: 'Montserrat', Arial, sans-serif;">
                                    Your application is currently undergoing our standard review and verification
                                    process. This step helps us maintain the quality and integrity of NOEL.
                                </p>
                            </div>

                            <!-- What's Next -->
                            <div style="background: #f1f5f9; padding: 25px; border-radius: 10px; margin-bottom: 30px;">
                                <h2
                                    style="margin: 0 0 20px 0; font-size: 20px; color: #1e293b; font-weight: 600; font-family: 'Montserrat', Arial, sans-serif;">
                                    📋 What Happens Next?
                                </h2>
                                <ol
                                    style="margin: 0; padding-left: 20px; color: #475569; font-family: 'Montserrat', Arial, sans-serif;">
                                    <li style="margin-bottom: 12px; font-size: 15px; line-height: 1.6;">
                                        Our team will carefully review your application
                                    </li>
                                    <li style="margin-bottom: 12px; font-size: 15px; line-height: 1.6;">
                                        We verify the information provided to ensure quality membership
                                    </li>
                                    <li style="margin-bottom: 0; font-size: 15px; line-height: 1.6;">
                                        You will receive an email notification once the review is complete
                                    </li>
                                </ol>
                            </div>

                            <!-- Important Notice -->
                            <div
                                style="background: #f0fdf4; border: 1px solid #10b981; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px;">
                                <p
                                    style="margin: 0; font-size: 15px; color: #065f46; font-weight: 500; font-family: 'Montserrat', Arial, sans-serif;">
                                    ✅ <strong>No additional information is required from you at this time.</strong>
                                </p>
                            </div>

                            <!-- Thank You Message -->
                            <div
                                style="background: #ffffff; border: 1px solid #d1d5db; border-radius: 8px; padding: 25px; text-align: center;">
                                <p
                                    style="margin: 0; font-size: 16px; color: #4b5563; font-family: 'Montserrat', Arial, sans-serif;">
                                    Thank you for your interest in <strong style="color: #193E47;">P23 Africa</strong>.
                                </p>
                                <p
                                    style="margin: 15px 0 0 0; font-size: 14px; color: #6b7280; font-family: 'Montserrat', Arial, sans-serif;">
                                    We look forward to potentially welcoming you to our exclusive network of verified
                                    business professionals.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background: #193E47; padding: 30px; text-align: center;">
                            <p
                                style="margin: 0 0 5px 0; font-size: 16px; color: #9ca3af; font-family: 'Montserrat', Arial, sans-serif;">
                                Kind regards,
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
