@extends('emails.layouts.base')

@section('content')
    <!-- Success Icon -->
    <tr>
        <td style="padding:32px 40px 0;text-align:center;">
            <div
                style="display:inline-block;width:64px;height:64px;background-color:#E8F5E9;border-radius:50%;line-height:64px;">
                <span style="font-size:32px;">💰</span>
            </div>
        </td>
    </tr>

    <tr>
        <td style="padding:24px 40px 32px;">
            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">
                Refund Processed</h2>
            <p style="margin:0 0 32px;color:#5A6C7D;font-size:15px;text-align:center;">Your refund has
                been successfully processed.</p>

            <!-- Refund Details -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin:0 0 24px;padding:24px;background:linear-gradient(135deg,#E8F5E9 0%,#C8E6C9 100%);border-radius:8px;">
                <tr>
                    <td style="text-align:center;">
                        <p
                            style="margin:0 0 8px;color:#2E7D32;font-size:13px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">
                            Refund Amount</p>
                        <p style="margin:0;color:#1B5E20;font-size:32px;font-weight:700;">
                            {{ $refund_amount }}</p>
                    </td>
                </tr>
            </table>

            <!-- Refund Info -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin:0 0 24px;padding:20px;background-color:#F7F9FC;border-radius:6px;">
                <tr>
                    <td>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding:6px 0;">
                                    <span style="color:#5A6C7D;font-size:13px;">Order Number</span>
                                </td>
                                <td align="right" style="padding:6px 0;">
                                    <strong style="color:#2C3E50;font-size:14px;">#{{ $order_id }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;">
                                    <span style="color:#5A6C7D;font-size:13px;">Refund Date</span>
                                </td>
                                <td align="right" style="padding:6px 0;">
                                    <strong style="color:#2C3E50;font-size:14px;">{{ $refund_date }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;">
                                    <span style="color:#5A6C7D;font-size:13px;">Payment Method</span>
                                </td>
                                <td align="right" style="padding:6px 0;">
                                    <strong style="color:#2C3E50;font-size:14px;">{{ $payment_method }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;">
                                    <span style="color:#5A6C7D;font-size:13px;">Transaction ID</span>
                                </td>
                                <td align="right" style="padding:6px 0;">
                                    <strong
                                        style="color:#2C3E50;font-size:12px;font-family:monospace;">{{ $transaction_id }}</strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Timeline -->
            <div style="padding:20px;background-color:#FFF4F0;border-radius:6px;margin-bottom:32px;">
                <h3 style="margin:0 0 12px;color:#2C3E50;font-size:15px;font-weight:600;">What's Next?
                </h3>
                <p style="margin:0;color:#5A6C7D;font-size:13px;line-height:1.6;">The refund will appear
                    in your account within {{ $refund_processing_days }} business days, depending on
                    your bank or card issuer's processing time.</p>
            </div>

            <!-- CTA -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td align="center">
                        <a href="{{ $account_url }}"
                            style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">View
                            Account</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
