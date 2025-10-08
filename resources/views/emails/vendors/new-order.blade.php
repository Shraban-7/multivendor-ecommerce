<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
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

                    <!-- Alert Badge -->
                    <tr>
                        <td style="padding:32px 40px 0;text-align:center;">
                            <div style="display:inline-block;padding:8px 20px;background-color:#E8F5E9;border-radius:20px;">
                                <span style="color:#2E7D32;font-size:14px;font-weight:600;">🔔 New Order</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:24px 40px 32px;">
                            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">You Have a New Order!</h2>
                            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;text-align:center;">A customer has placed an order from your store.</p>

                            <!-- Order Summary -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;border:2px solid #FF6B35;border-radius:8px;overflow:hidden;">
                                <tr>
                                    <td style="padding:20px;background-color:#FFF4F0;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Order Number</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#2C3E50;font-size:15px;">#{{order_id}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Order Date</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;">{{order_date}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Customer</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;">{{customer_name}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;border-top:1px solid #FFE0D3;padding-top:12px;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Your Earnings</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;border-top:1px solid #FFE0D3;padding-top:12px;">
                                                    <strong style="color:#FF6B35;font-size:18px;">{{vendor_earnings}}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Items -->
                            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:18px;font-weight:600;">Items to Ship</h3>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td width="60" style="padding-right:12px;" valign="top">
                                        <img src="{{product_image}}" alt="{{product_name}}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;display:block;">
                                    </td>
                                    <td valign="top">
                                        <p style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">{{product_name}}</p
                                            <p style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">{{product_name}}</p>
                                        <p style="margin:0 0 4px;color:#5A6C7D;font-size:13px;">SKU: {{product_sku}}</p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">Qty: {{quantity}}</p>
                                    </td>
                                    <td align="right" valign="top" width="80">
                                        <strong style="color:#2C3E50;font-size:15px;">{{item_price}}</strong>
                                    </td>
                                </tr>
                            </table>

                            <!-- Shipping Address -->
                            <h3 style="margin:0 0 12px;color:#2C3E50;font-size:16px;font-weight:600;">Ship To</h3>
                            <div style="padding:16px;background-color:#FFF4F0;border-radius:6px;border-left:4px solid #FF6B35;margin-bottom:32px;">
                                <p style="margin:0;color:#2C3E50;font-size:14px;line-height:1.6;">
                                    <strong>{{customer_name}}</strong><br>
                                    {{address_line_1}}<br>
                                    {{address_line_2}}<br>
                                    {{city}}, {{state}} {{zip}}<br>
                                    {{country}}<br>
                                    Phone: {{customer_phone}}
                                </p>
                            </div>

                            <!-- Action Required -->
                            <div style="padding:16px;background-color:#FFF3E0;border-radius:6px;margin-bottom:24px;">
                                <p style="margin:0 0 8px;color:#2C3E50;font-size:14px;font-weight:600;">⚡ Action Required</p>
                                <p style="margin:0;color:#5A6C7D;font-size:13px;line-height:1.6;">Please process this order within 24 hours to maintain your seller rating.</p>
                            </div>

                            <!-- CTA Buttons -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <a href="{{process_order_url}}" style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Process Order</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="{{print_packing_slip_url}}" style="display:inline-block;padding:14px 32px;background-color:#F7F9FC;color:#FF6B35;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;border:2px solid #FF6B35;">Print Packing Slip</a>
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