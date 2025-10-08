<?php
    $platformName = config('app.name');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $platformName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body style="margin:0;padding:0;font-family:'Inter','Segoe UI',Roboto,sans-serif;background-color:#F7F9FC;-webkit-font-smoothing:antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#F7F9FC;">
        <tr>
            <td style="padding:20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin:0 auto;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#FF6B35 0%,#E85A2A 100%);padding:32px 40px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;">{{ $platformName }}</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 40px 32px;">
                            <h2 style="margin:0 0 16px;color:#2C3E50;font-size:24px;font-weight:600;">Welcome, {{customer_name}}! 👋</h2>
                            <p style="margin:0 0 24px;color:#5A6C7D;font-size:16px;line-height:1.6;">We're thrilled to have you join our community of shoppers. Get ready to discover amazing products from vendors around the world.</p>

                            <!-- Benefits -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 32px;">
                                <tr>
                                    <td style="padding:16px;background-color:#FFF4F0;border-radius:6px;margin-bottom:12px;">
                                        <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:500;">✓ Access to thousands of products</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px;background-color:#FFF4F0;border-radius:6px;margin-bottom:12px;">
                                        <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:500;">✓ Secure checkout & payment options</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px;background-color:#FFF4F0;border-radius:6px;">
                                        <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:500;">✓ Track orders in real-time</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{shop_url}}" style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Start Shopping</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;background-color:#F7F9FC;border-top:1px solid #E0E6ED;">
                            <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;line-height:1.5;text-align:center;">Need help? Contact us at <a href="mailto:{{support_email}}" style="color:#FF6B35;text-decoration:none;">{{support_email}}</a></p>
                            <p style="margin:0;color:#8A9BA8;font-size:12px;text-align:center;">© {{year}} {{ $platformName }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>