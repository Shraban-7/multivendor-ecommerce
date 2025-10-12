<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body
    style="margin:0;padding:0;font-family:'Inter','Segoe UI',Roboto,sans-serif;background-color:#F7F9FC;-webkit-font-smoothing:antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color:#F7F9FC;">
        <tr>
            <td style="padding:20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    style="margin:0 auto;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td
                            style="background:linear-gradient(135deg,#FF6B35 0%,#E85A2A 100%);padding:32px 40px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;">
                                {{ config('app.name') }}</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px 40px 32px;">
                            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;">Order Cancelled
                            </h2>
                            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;line-height:1.6;">Your order
                                #{{ $order_id }} has been cancelled as requested.</p>

                            <!-- Cancellation Info -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin:0 0 24px;padding:20px;background-color:#FFF3E0;border-left:4px solid #FF9800;border-radius:6px;">
                                <tr>
                                    <td>
                                        <p style="margin:0 0 8px;color:#2C3E50;font-size:14px;font-weight:600;">
                                            Cancellation Details</p>
                                        <p style="margin:0 0 4px;color:#5A6C7D;font-size:13px;">Cancelled on:
                                            {{ $cancellation_date }}</p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">Reason:
                                            {{ $cancellation_reason }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Refund Info -->
                            <div style="padding:20px;background-color:#E8F5E9;border-radius:6px;margin-bottom:24px;">
                                <h3 style="margin:0 0 12px;color:#2C3E50;font-size:16px;font-weight:600;">Refund
                                    Information</h3>
                                <p style="margin:0 0 8px;color:#5A6C7D;font-size:14px;">Amount to be refunded: <strong
                                        style="color:#2E7D32;">{{ $refund_amount }}</strong></p>
                                <p style="margin:0;color:#5A6C7D;font-size:13px;line-height:1.6;">The refund will be
                                    processed within {{ $refund_days }} business days and will appear in your original
                                    payment method.</p>
                            </div>

                            <!-- Order Details -->
                            <h3 style="margin:0 0 12px;color:#2C3E50;font-size:16px;font-weight:600;">Cancelled Items
                            </h3>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin:0 0 32px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                                <tr>
                                    <td>
                                        <p style="margin:0 0 4px;color:#2C3E50;font-size:14px;font-weight:500;">
                                            {{ $product_name }}</p>
                                        <p style="margin:0;color:#5A6C7D;font-size:13px;">Qty: {{ $quantity }} ×
                                            {{ $price }}</p>
                                    </td>
                                    <td align="right">
                                        <strong style="color:#2C3E50;font-size:14px;">{{ $item_total }}</strong>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <p style="margin:0 0 20px;color:#5A6C7D;font-size:14px;text-align:center;">We're sorry to
                                see this order cancelled. If you have any questions or concerns, please don't hesitate
                                to reach out.</p>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $shop_url }}"
                                            style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Continue
                                            Shopping</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 40px;background-color:#F7F9FC;border-top:1px solid #E0E6ED;">
                            <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;line-height:1.5;text-align:center;">
                                Questions? Contact us at <a href="mailto:{{ config('mail.support_address') }}"
                                    style="color:#FF6B35;text-decoration:none;">{{ config('mail.support_address') }}</a></p>
                            <p style="margin:0;color:#8A9BA8;font-size:12px;text-align:center;">© {{ date('Y') }}
                                {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
