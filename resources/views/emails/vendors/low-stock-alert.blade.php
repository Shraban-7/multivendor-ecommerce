<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
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
                    
                    <!-- Warning Icon -->
                    <tr>
                        <td style="padding:32px 40px 0;text-align:center;">
                            <div style="display:inline-block;width:64px;height:64px;background-color:#FFF3E0;border-radius:50%;line-height:64px;">
                                <span style="font-size:32px;">⚠️</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:24px 40px 32px;">
                            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">Low Stock Alert</h2>
                            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;text-align:center;">Some of your products are running low on inventory.</p>
                            
                            <!-- Alert Message -->
                            <div style="padding:16px;background-color:#FFF3E0;border-left:4px solid #FF9800;border-radius:6px;margin-bottom:24px;">
                                <p style="margin:0;color:#5A6C7D;font-size:14px;line-height:1.6;">Update your inventory soon to avoid missing out on sales. Products with zero stock are automatically hidden from customers.</p>
                            </div>
                            
                            <!-- Low Stock Products -->
                            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:18px;font-weight:600;">Products Needing Attention</h3>
                            
                            <!-- Product 1 -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 12px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td width="60" style="padding-right:12px;" valign="top">
                                        <img src="{{product_image_1}}" alt="{{product_name_1}}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;display:block;">
                                    </td>
                                    <td valign="top">
                                        <p style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">{{product_name_1}}</p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">SKU: {{product_sku_1}}</p>
                                    </td>
                                    <td align="right" valign="top" width="100">
                                        <div style="display:inline-block;padding:6px 12px;background-color:#FFEBEE;border-radius:4px;">
                                            <strong style="color:#C62828;font-size:14px;">{{stock_1}} left</strong>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Product 2 -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 12px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td width="60" style="padding-right:12px;" valign="top">
                                        <img src="{{product_image_2}}" alt="{{product_name_2}}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;display:block;">
                                    </td>
                                    <td valign="top">
                                        <p style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">{{product_name_2}}</p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">SKU: {{product_sku_2}}</p>
                                    </td>
                                    <td align="right" valign="top" width="100">
                                        <div style="display:inline-block;padding:6px 12px;background-color:#FFEBEE;border-radius:4px;">
                                            <strong style="color:#C62828;font-size:14px;">{{stock_2}} left</strong>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- More products indicator -->
                            <p style="margin:0 0 32px;color:#5A6C7D;font-size:13px;text-align:center;">+ {{additional_count}} more products</p>
                            
                            <!-- CTA Buttons -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <a href="{{update_inventory_url}}" style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Update Inventory</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="{{view_all_products_url}}" style="display:inline-block;padding:14px 32px;background-color:#F7F9FC;color:#FF6B35;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;border:2px solid #FF6B35;">View All Products</a>
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