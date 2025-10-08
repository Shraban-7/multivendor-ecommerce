<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Released</title>
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
                    
                    <!-- Success Icon -->
                    <tr>
                        <td style="padding:32px 40px 0;text-align:center;">
                            <div style="display:inline-block;width:80px;height:80px;background-color:#E8F5E9;border-radius:50%;line-height:80px;">
                                <span style="font-size:40px;">💸</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding:24px 40px 32px;">
                            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">Payment Released!</h2>
                            <p style="margin:0 0 32px;color:#5A6C7D;font-size:15px;text-align:center;">Your earnings have been released and are on their way.</p>
                            
                            <!-- Payment Amount -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;padding:32px;background:linear-gradient(135deg,#E8F5E9 0%,#C8E6C9 100%);border-radius:8px;">
                                <tr>
                                    <td style="text-align:center;">
                                        <p style="margin:0 0 8px;color:#2E7D32;font-size:14px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Payout Amount</p>
                                        <p style="margin:0;color:#1B5E20;font-size:36px;font-weight:700;">{{payout_amount}}</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Payment Details -->
                            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:18px;font-weight:600;">Payment Details</h3>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 24px;padding:20px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td>
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Payout ID</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;font-family:monospace;">{{payout_id}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Release Date</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;">{{release_date}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Payment Method</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;">{{payment_method}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Account Ending</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#2C3E50;font-size:14px;">****{{account_last_4}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;">
                                                    <span style="color:#5A6C7D;font-size:13px;">Est. Arrival</span>
                                                </td>
                                                <td align="right" style="padding:6px 0;">
                                                    <strong style="color:#FF6B35;font-size:14px;">{{estimated_arrival}}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Earnings Breakdown -->
                            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:18px;font-weight:600;">Earnings Breakdown</h3>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 32px;padding:20px;background-color:#FFF4F0;border-radius:6px;">
                                <tr>
                                    <td style="padding:6px 0;">
                                        <span style="color:#5A6C7D;font-size:14px;">Total Sales</span>
                                    </td>
                                    <td align="right" style="padding:6px 0;">
                                        <span style="color:#2C3E50;font-size:14px;">{{total_sales}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;">
                                        <span style="color:#5A6C7D;font-size:14px;">Platform Fee ({{platform_fee_percent}}%)</span>
                                    </td>
                                    <td align="right" style="padding:6px 0;">
                                        <span style="color:#2C3E50;font-size:14px;">-{{platform_fee}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;">
                                        <span style="color:#5A6C7D;font-size:14px;">Processing Fee</span>
                                    </td>
                                    <td align="right" style="padding:6px 0;">
                                        <span style="color:#2C3E50;font-size:14px;">-{{processing_fee}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;">
                                        <span style="color:#5A6C7D;font-size:14px;">Refunds</span>
                                    </td>
                                    <td align="right" style="padding:6px 0;">
                                        <span style="color:#2C3E50;font-size:14px;">-{{refunds}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0 0;border-top:2px solid #FFE0D3;">
                                        <strong style="color:#2C3E50;font-size:16px;">Net Earnings</strong>
                                    </td>
                                    <td align="right" style="padding:12px 0 0;border-top:2px solid #FFE0D3;">
                                        <strong style="color:#FF6B35;font-size:18px;">{{payout_amount}}</strong>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Orders Included -->
                            <div style="padding:16px;background-color:#F7F9FC;border-radius:6px;margin-bottom:24px;">
                                <p style="margin:0 0 8px;color:#2C3E50;font-size:14px;font-weight:600;">Orders Included</p>
                                <p style="margin:0;color:#5A6C7D;font-size:13px;">This payout includes {{order_count}} orders from {{period_start}} to {{period_end}}.</p>
                            </div>
                            
                            <!-- CTA Buttons -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom:12px;">
                                        <a href="{{payout_details_url}}" style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">View Payout Details</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <a href="{{download_statement_url}}" style="display:inline-block;padding:14px 32px;background-color:#F7F9FC;color:#FF6B35;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;border:2px solid #FF6B35;">Download Statement</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;background-color:#F7F9FC;border-top:1px solid #E0E6ED;">
                            <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;line-height:1.5;text-align:center;">Questions about your payout? Contact us at <a href="mailto:{{seller_support_email}}" style="color:#FF6B35;text-decoration:none;">{{seller_support_email}}</a></p>
                            <p style="margin:0;color:#8A9BA8;font-size:12px;text-align:center;">© {{year}} {{platform_name}}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>