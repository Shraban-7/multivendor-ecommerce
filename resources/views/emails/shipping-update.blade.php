<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Has Shipped!</title>
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
                    
                    <!-- Shipping Icon -->
                    <tr>
                        <td style="padding:32px 40px 0;text-align:center;">
                            <div style="display:inline-block;width:64px;height:64px;background-color:#FFF4F0;border-radius:50%;line-height:64px;">
                                <span style="font-size:32px;">📦</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:24px 40px 32px;">
                            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">Your Order is On the Way!</h2>
                            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;text-align:center;">Great news, {{customer_name}}! Your package has been shipped.</p>
                            
                            <!-- Tracking Info -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;padding:20px;background:linear-gradient(135deg,#FFF4F0 0%,#FFE8DC 100%);border-radius:8px;">
                                <tr>
                                    <td style="text-align:center;">
                                        <p style="margin:0 0 8px;color:#5A6C7D;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;">Tracking Number</p>
                                        <p style="margin:0 0 16px;color:#2C3E50;font-size:20px;font-weight:600;font-family:monospace;letter-spacing:1px;">{{tracking_number}}</p>
                                        <p style="margin:0 0 4px;color:#5A6C7D;font-size:13px;">Carrier: <strong style="color:#2C3E50;">{{carrier_name}}</strong></p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">Est. Delivery: <strong style="color:#FF6B35;">{{estimated_delivery}}</strong></p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Order Info -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td>
                                        <p style="margin:0 0 8px;color:#5A6C7D;font-size:13px;">Order Number</p>
                                        <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:600;">#{{order_id}}</p>
                                    </td>
                                    <td align="right">
                                        <p style="margin:0 0 8px;color:#5A6C7D;font-size:13px;">Items</p>
                                        <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:600;">{{item_count}} items</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Shipping Address -->
                            <h3 style="margin:0 0 12px;color:#2C3E50;font-size:16px;font-weight:600;">Delivering To</h3>
                            <div style="padding:16px;background-color:#F7F9FC;border-radius:6px;margin-bottom:32px;">
                                <p style="margin:0;color:#2C3E50;font-size:14px;line-height:1.6;">
                                    {{customer_name}}<br>
                                    {{address_line_1}}<br>
                                    {{city}}, {{state}} {{zip}}
                                </p>
                            </div>
                            
                            <!-- CTA Buttons -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <a href="{{tracking_url}}" style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Track Package</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="{{order_details_url}}" style="display:inline-block;padding:14px 32px;background-color:#F7F9FC;color:#FF6B35;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;border:2px solid #FF6B35;">View Order Details</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;background-color:#F7F9FC;border-top:1px solid #E0E6ED;">
                            <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;line-height:1.5;text-align:center;">Need help? Contact us at <a href="mailto:{{support_email}}" style="color:#FF6B35;text-decoration:none;">{{support_email}}</a></p>
                            <p style="margin:0;color:#8A9BA8;font-size:12px;text-align:center;">© {{year}} {{platform_name}}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>