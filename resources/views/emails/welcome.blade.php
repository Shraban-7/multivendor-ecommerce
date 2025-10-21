@extends('emails.layouts.base')

@section('content')
<tr>
    <td style="padding:40px 40px 32px;">
        <h2 style="margin:0 0 16px;color:#2C3E50;font-size:24px;font-weight:600;">Welcome, {{ $receipent_name }}! 👋</h2>
        <p style="margin:0 0 24px;color:#5A6C7D;font-size:16px;line-height:1.6;">
            We're thrilled to have you join our community of shoppers. Get ready to discover amazing products from vendors around the world.
        </p>

        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 32px;">
            <tr>
                <td style="padding:16px;background-color:#FFF4F0;border-radius:6px;margin-bottom:12px;">
                    <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:500;">✓ Access to thousands of products
                    </p>
                </td>
            </tr>
            <tr>
                <td style="height:12px;line-height:12px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="padding:16px;background-color:#FFF4F0;border-radius:6px;margin-bottom:12px;">
                    <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:500;">✓ Secure checkout & payment
                        options</p>
                </td>
            </tr>
            <tr>
                <td style="height:12px;line-height:12px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="padding:16px;background-color:#FFF4F0;border-radius:6px;">
                    <p style="margin:0;color:#2C3E50;font-size:15px;font-weight:500;">✓ Track orders in real-time</p>
                </td>
            </tr>
        </table>

        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td align="center">
                    <a href="{{ config('app.url') }}"
                        style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">
                        Start Shopping
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection