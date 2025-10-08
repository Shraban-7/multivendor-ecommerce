<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Product Review</title>
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
                            <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;">{{platform_name}}</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 40px 32px;">
                            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;">New Review Received ⭐</h2>
                            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;line-height:1.6;">A customer has left a review for one of your products.</p>

                            <!-- Product Info -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td width="80" style="padding-right:16px;" valign="top">
                                        <img src="{{product_image}}" alt="{{product_name}}" style="width:80px;height:80px;object-fit:cover;border-radius:6px;display:block;">
                                    </td>
                                    <td valign="top">
                                        <p style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">{{product_name}}</p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">SKU: {{product_sku}}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Rating -->
                            <div style="padding:20px;background-color:#FFF4F0;border-radius:6px;margin-bottom:24px;">
                                <div style="text-align:center;margin-bottom:12px;">
                                    <span style="color:#FF6B35;font-size:32px;">{{star_rating}}</span>
                                </div>
                                <p style="margin:0;color:#2C3E50;font-size:14px;text-align:center;font-weight:600;">{{rating_number}} out of 5 stars</p>
                            </div>

                            <!-- Review Content -->
                            <div style="padding:20px;background-color:#F7F9FC;border-left:4px solid #FF6B35;border-radius:6px;margin-bottom:16px;">
                                <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;">Review by <strong style="color:#2C3E50;">{{customer_name}}</strong> on {{review_date}}</p>
                                <p style="margin:0;color:#2C3E50;font-size:14px;line-height:1.6;">"{{review_text}}"</p>
                            </div>

                            <!-- CTA -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{respond_to_review_url}}" style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Respond to Review</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;background-color:#F7F9FC;border-top:1px solid #E0E6ED;">
                            <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;line-height:1.5;text-align:center;">Need help? Contact seller support at <a href="mailto:{{seller_support_email}}" style="color:#FF6B35;text-decoration:none;">{{seller_support_email}}</a></p>
                            <p style="margin:0;color:#8A9BA8;font-size:12px;text-align:center;">© {{year}} {{platform_name}}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>