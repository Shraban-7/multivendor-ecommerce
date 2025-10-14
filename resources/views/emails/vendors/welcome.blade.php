@extends('emails.layouts.base')

@section('content')
    <tr>
        <td style="padding:40px 40px 32px;">
            <h2 style="margin:0 0 16px;color:#2C3E50;font-size:24px;font-weight:600;">Welcome, {{ $vendor_name }}! 🎉
            </h2>
            <p style="margin:0 0 24px;color:#5A6C7D;font-size:16px;line-height:1.6;">Congratulations! Your vendor account
                has been approved. You're now part of our growing marketplace community.</p>

            <!-- Quick Stats -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin:0 0 32px;">
                <tr>
                    <td width="50%" style="padding:20px;background-color:#FFF4F0;border-radius:6px;" valign="top">
                        <h3 style="margin:0 0 8px;color:#FF6B35;font-size:32px;font-weight:700;">{{ $commission_rate }}%
                        </h3>
                        <p style="margin:0;color:#5A6C7D;font-size:13px;">Commission Rate</p>
                    </td>
                    <td width="16"></td>
                    <td width="50%" style="padding:20px;background-color:#FFF4F0;border-radius:6px;" valign="top">
                        <h3 style="margin:0 0 8px;color:#FF6B35;font-size:32px;font-weight:700;">{{ $payout_cycle }}</h3>
                        <p style="margin:0;color:#5A6C7D;font-size:13px;">Payout Cycle</p>
                    </td>
                </tr>
            </table>

            <!-- Getting Started -->
            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:18px;font-weight:600;">Getting Started</h3>

            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin:0 0 32px;">
                <tr>
                    <td style="padding:16px 0;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="40" valign="top">
                                    <div
                                        style="width:32px;height:32px;background-color:#FF6B35;border-radius:50%;color:#ffffff;text-align:center;line-height:32px;font-weight:700;">
                                        1</div>
                                </td>
                                <td valign="top">
                                    <h4 style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">Complete Your
                                        Store Profile</h4>
                                    <p style="margin:0;color:#5A6C7D;font-size:14px;">Add your business details, logo, and
                                        description.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 0;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="40" valign="top">
                                    <div
                                        style="width:32px;height:32px;background-color:#FF6B35;border-radius:50%;color:#ffffff;text-align:center;line-height:32px;font-weight:700;">
                                        2</div>
                                </td>
                                <td valign="top">
                                    <h4 style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">Add Your First
                                        Products</h4>
                                    <p style="margin:0;color:#5A6C7D;font-size:14px;">Upload product images, descriptions,
                                        and pricing.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 0;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="40" valign="top">
                                    <div
                                        style="width:32px;height:32px;background-color:#FF6B35;border-radius:50%;color:#ffffff;text-align:center;line-height:32px;font-weight:700;">
                                        3</div>
                                </td>
                                <td valign="top">
                                    <h4 style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">Set Up Payment
                                        Details</h4>
                                    <p style="margin:0;color:#5A6C7D;font-size:14px;">Configure your bank account for
                                        payouts.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- CTA Buttons -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td align="center" style="padding-bottom:12px;">
                        <a href="{{ $vendor_dashboard_url }}"
                            style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Go
                            to Dashboard</a>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <a href="{{ $seller_guide_url }}"
                            style="display:inline-block;padding:14px 32px;background-color:#F7F9FC;color:#FF6B35;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;border:2px solid #FF6B35;">View
                            Seller Guide</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
