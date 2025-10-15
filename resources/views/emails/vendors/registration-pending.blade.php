@extends('emails.layouts.base')

@section('content')
<tr>
    <td style="padding:40px 40px 32px;">
        <h2 style="margin:0 0 16px;color:#2C3E50;font-size:20px;font-weight:600;">Thank you for registering, {{ $receipent_name }}! 👋</h2>

        <p style="margin:0 0 24px;color:#5A6C7D;font-size:16px;line-height:1.6;">
            We’ve received your vendor application and it's currently under review by our team.
        </p>

        <p style="margin:0 0 24px;color:#5A6C7D;font-size:16px;line-height:1.6;">
            We’ll send you an email as soon as your account is approved. This usually takes less than 24–48 hours.
        </p>
        
        <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;line-height:1.6;">
            In the meantime, feel free to check out our <a href="{{ $seller_guide_url }}" style="color:#FF6B35;text-decoration:underline;">Seller Guide</a> to get familiar with how things work.
        </p>
    </td>
</tr>
@endsection