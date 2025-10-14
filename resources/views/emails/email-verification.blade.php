@extends('emails.layouts.base')
@section('content')

<tr>
    <td style="padding: 30px 20px; font-family: Arial, sans-serif; background-color: #f4f7fb;">
        <h2 style="margin: 0 0 12px; color: #2C3E50; font-size: 22px; font-weight: 600; text-align: center;">
            Hello, {{ $receipent_name }}!
        </h2>

        <p style="margin: 0 0 16px; color: #4a5568; font-size: 15px; line-height: 1.5; text-align: center;">
            To verify your email address, enter the code below:
        </p>

        <table role="presentation" style="margin: 20px 0; width: 100%; text-align: center;">
            <tr>
                <td>
                    <div style="background-color: #ffffff; border: 2px solid #FF6B35; border-radius: 8px; padding: 24px 20px; display: inline-block; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <p style="margin: 0 0 8px 0; color: #718096; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                            Your Verification Code
                        </p>
                        <p style="margin: 0; color: #FF6B35; font-size: 40px; font-weight: 700; letter-spacing: 4px; font-family: 'Courier New', monospace;">
                            {{ $verification_code }}
                        </p>
                    </div>
                </td>
            </tr>
        </table>

        <p style="margin: 0 0 12px; color: #4a5568; font-size: 15px; line-height: 1.5; text-align: center;">
            This code will expire in <strong style="color: #FF6B35;">{{ $expiry_minutes }} minutes</strong>.
        </p>
    </td>
</tr>

@endsection