@extends('emails.layouts.base')
@section('content')

<tr>
    <td style="padding: 40px 30px;">
        <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;">
            Hello, {{ $customer_name }}!</h2>

        <p style="margin: 0 0 16px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
            Use the code below to verifiy your email:
        </p>

        <!-- Reset Code Box -->
        <table role="presentation" style="margin: 32px 0; width: 100%;">
            <tr>
                <td align="center">
                    <div style="background-color: #f7fafc; border: 2px dashed #FF6B35; border-radius: 8px; padding: 24px; display: inline-block;">
                        <p style="margin: 0 0 8px 0; color: #718096; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                            Your Verification Code
                        </p>
                        <p style="margin: 0; color: #FF6B35; font-size: 36px; font-weight: 700; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                            {{ $code }}
                        </p>
                    </div>
                </td>
            </tr>
        </table>

        <p style="margin: 0 0 16px 0; color: #4a5568; font-size: 16px; line-height: 1.6;">
            This code will expire in <strong style="color: #FF6B35;">15 minutes</strong>.
        </p>
    </td>
</tr>

@endsection