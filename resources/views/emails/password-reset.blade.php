<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>

<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f7;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f4f4f7;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; width: 100%; border-collapse: collapse; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #040293 0%, #0503c7 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; letter-spacing: -0.5px;">
                                Password Reset Request
                            </h1>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px 0; color: #040293; font-size: 24px; font-weight: 600;">
                                Hello, {{ $user->name }}
                            </h2>

                            <p style="margin: 0 0 16px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                                We received a request to reset your password. Use the code below to reset your password:
                            </p>

                            <!-- Reset Code Box -->
                            <table role="presentation" style="margin: 32px 0; width: 100%;">
                                <tr>
                                    <td align="center">
                                        <div style="background-color: #f7fafc; border: 2px dashed #040293; border-radius: 8px; padding: 24px; display: inline-block;">
                                            <p style="margin: 0 0 8px 0; color: #718096; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Your Reset Code
                                            </p>
                                            <p style="margin: 0; color: #040293; font-size: 36px; font-weight: 700; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                                                {{ $code }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 16px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
                                This code will expire in <strong style="color: #040293;">15 minutes</strong>.
                            </p>

                            <p style="margin: 0 0 24px 0; color: #718096; font-size: 14px; line-height: 1.6;">
                                If you didn't request a password reset, please ignore this email. Your password will remain unchanged.
                            </p>

                            <p style="margin: 24px 0 0 0; color: #718096; font-size: 14px; line-height: 1.5;">
                                Best regards,<br>
                                <strong style="color: #040293;">The {{ config('app.name') }} Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f7fafc; padding: 24px 30px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; color: #a0aec0; font-size: 13px; line-height: 1.5; text-align: center;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                            <p style="margin: 8px 0 0 0; color: #a0aec0; font-size: 13px; line-height: 1.5; text-align: center;">
                                This is an automated security email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>