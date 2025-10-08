<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation #{{order_id}}</title>
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
                    
                    <!-- Success Badge -->
                    <tr>
                        <td style="padding:32px 40px 0;text-align:center;">
                            <div style="display:inline-block;padding:8px 20px;background-color:#E8F5E9;border-radius:20px;">
                                <span style="color:#2E7D32;font-size:14px;font-weight:600;">✓ Order Confirmed</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:24px 40px 32px;">
                            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">Thank you, {{customer_name}}!</h2>
                            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;text-align:center;">Your order has been confirmed and is being processed.</p>
                            
                            <!-- Order Info Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;border:2px solid #FF6B35;border-radius:8px;overflow:hidden;">
                                <tr>
                                    <td style="padding:16px 20px;background-color:#FFF4F0;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding:4px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Order Number</span>
                                                </td>
                                                <td align="right" style="padding:4px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;">#{{order_id}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Order Date</span>
                                                </td>
                                                <td align="right" style="padding:4px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;">{{order_date}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Total Amount</span>
                                                </td>
                                                <td align="right" style="padding:4px 0;">
                                                    <strong style="color:#FF6B35;font-size:16px;">{{total_amount}}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Order Items -->
                            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:18px;font-weight:600;">Order Details</h3>
                            
                            <!-- Item 1 -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 12px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td width="80" style="padding-right:16px;" valign="top">
                                        <img src="{{product_image_1}}" alt="{{product_name_1}}" style="width:80px;height:80px;object-fit:cover;border-radius:6px;display:block;">
                                    </td>
                                    <td valign="top">
                                        <p style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">{{product_name_1}}</p>
                                        <p style="margin:0 0 8px;color:#5A6C7D;font-size:13px;">Vendor: {{vendor_name_1}}</p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">Qty: {{quantity_1}} × {{price_1}}</p>
                                    </td>
                                    <td align="right" valign="top" width="80">
                                        <strong style="color:#2C3E50;font-size:15px;">{{item_total_1}}</strong>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Repeat for more items as needed -->
                            
                            <!-- Price Summary -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:24px 0 32px;padding-top:16px;border-top:1px solid #E0E6ED;">
                                <tr>
                                    <td style="padding:6px 0;">
                                        <span style="color:#5A6C7D;font-size:14px;">Subtotal</span>
                                    </td>
                                    <td align="right" style="padding:6px 0;">
                                        <span style="color:#2C3E50;font-size:14px;">{{subtotal}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;">
                                        <span style="color:#5A6C7D;font-size:14px;">Shipping</span>
                                    </td>
                                    <td align="right" style="padding:6px 0;">
                                        <span style="color:#2C3E50;font-size:14px;">{{shipping_cost}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;">
                                        <span style="color:#5A6C7D;font-size:14px;">Tax</span>
                                    </td>
                                    <td align="right" style="padding:6px 0;">
                                        <span style="color:#2C3E50;font-size:14px;">{{tax}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0 0;border-top:2px solid #E0E6ED;">
                                        <strong style="color:#2C3E50;font-size:16px;">Total</strong>
                                    </td>
                                    <td align="right" style="padding:12px 0 0;border-top:2px solid #E0E6ED;">
                                        <strong style="color:#FF6B35;font-size:18px;">{{total_amount}}</strong>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Shipping Address -->
                            <h3 style="margin:0 0 12px;color:#2C3E50;font-size:16px;font-weight:600;">Shipping Address</h3>
                            <div style="padding:16px;background-color:#F7F9FC;border-radius:6px;margin-bottom:24px;">
                                <p style="margin:0;color:#2C3E50;font-size:14px;line-height:1.6;">
                                    {{customer_name}}<br>
                                    {{address_line_1}}<br>
                                    {{address_line_2}}<br>
                                    {{city}}, {{state}} {{zip}}<br>
                                    {{country}}
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{track_order_url}}" style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Track Your Order</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;background-color:#F7F9FC;border-top:1px solid #E0E6ED;">
                            <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;line-height:1.5;text-align:center;">Questions about your order? Contact us at <a href="mailto:{{support_email}}" style="color:#FF6B35;text-decoration:none;">{{support_email}}</a></p>
                            <p style="margin:0;color:#8A9BA8;font-size:12px;text-align:center;">© {{year}} {{platform_name}}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>