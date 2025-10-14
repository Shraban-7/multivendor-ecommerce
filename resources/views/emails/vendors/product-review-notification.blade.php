@extends('emails.layouts.base')

@section('content')
    <tr>
        <td style="padding:40px 40px 32px;">
            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;">New Review Received ⭐</h2>
            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;line-height:1.6;">A customer has left a review for one of
                your products.</p>

            <!-- Product Info -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin:0 0 24px;padding:16px;background-color:#F7F9FC;border-radius:6px;">
                <tr>
                    <td width="80" style="padding-right:16px;" valign="top">
                        <img src="{{ $product_image }}" alt="{{ $product_name }}"
                            style="width:80px;height:80px;object-fit:cover;border-radius:6px;display:block;">
                    </td>
                    <td valign="top">
                        <p style="margin:0 0 4px;color:#2C3E50;font-size:15px;font-weight:600;">{{ $product_name }}</p>
                        <p style="margin:0;color:#5A6C7D;font-size:13px;">SKU: {{ $product_sku }}</p>
                    </td>
                </tr>
            </table>

            <!-- Rating -->
            <div style="padding:20px;background-color:#FFF4F0;border-radius:6px;margin-bottom:24px;">
                <div style="text-align:center;margin-bottom:12px;">
                    <span style="color:#FF6B35;font-size:32px;">{{ $star_rating }}</span>
                </div>
                <p style="margin:0;color:#2C3E50;font-size:14px;text-align:center;font-weight:600;">{{ $rating_number }}
                    out of 5 stars</p>
            </div>

            <!-- Review Content -->
            <div
                style="padding:20px;background-color:#F7F9FC;border-left:4px solid #FF6B35;border-radius:6px;margin-bottom:16px;">
                <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;">Review by <strong
                        style="color:#2C3E50;">{{ $customer_name }}</strong> on {{ $review_date }}</p>
                <p style="margin:0;color:#2C3E50;font-size:14px;line-height:1.6;">"{{ $review_text }}"</p>
            </div>

            <!-- CTA -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td align="center">
                        <a href="{{ $respond_to_review_url }}"
                            style="display:inline-block;padding:14px 32px;background-color:#FF6B35;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;box-shadow:0 4px 12px rgba(255,107,53,0.3);">Respond
                            to Review</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
