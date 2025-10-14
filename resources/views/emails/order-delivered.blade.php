@extends('emails.layouts.base')

@section('content')
    <!-- Success Icon -->
    <tr>
        <td style="padding:32px 40px 0;text-align:center;">
            <div
                style="display:inline-block;width:80px;height:80px;background-color:#E8F5E9;border-radius:50%;line-height:80px;">
                <span style="font-size:40px;">✓</span>
            </div>
        </td>
    </tr>

    <tr>
        <td style="padding:24px 40px 32px;">
            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">
                Delivered Successfully!</h2>
            <p style="margin:0 0 8px;color:#5A6C7D;font-size:15px;text-align:center;">Your order was
                delivered on {{ $delivery_date }}</p>
            <p style="margin:0 0 32px;color:#8A9BA8;font-size:13px;text-align:center;">Order
                #{{ $order_id }}</p>

            <!-- Review Request -->
            <div
                style="padding:24px;background:linear-gradient(135deg,#FFF4F0 0%,#FFE8DC 100%);border-radius:8px;margin-bottom:32px;text-align:center;">
                <p style="margin:0 0 4px;color:#2C3E50;font-size:18px;font-weight:600;">How was your
                    experience?</p>
                <p style="margin:0 0 20px;color:#5A6C7D;font-size:14px;">Your feedback helps us and our
                    vendors improve.</p>
                <a href="{{ $review_url }}"
                    style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Leave
                    a Review</a>
            </div>

            <!-- Order Summary -->
            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:16px;font-weight:600;">Order Summary</h3>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin:0 0 24px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                <tr>
                    <td style="padding:4px 0;">
                        <span style="color:#5A6C7D;font-size:14px;">{{ $item_count }} items</span>
                    </td>
                    <td align="right" style="padding:4px 0;">
                        <strong style="color:#2C3E50;font-size:14px;">{{ $total_amount }}</strong>
                    </td>
                </tr>
            </table>

            <!-- CTA Buttons -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td align="center" style="padding-bottom:12px;">
                        <a href="{{ $order_details_url }}"
                            style="display:inline-block;padding:14px 32px;background-color:#F7F9FC;color:#FF6B35;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;border:2px solid #FF6B35;">View
                            Order</a>
                    </td>
                </tr>
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
@endsection
