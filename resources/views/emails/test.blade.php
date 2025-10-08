@extends('emails.layouts.base')

@section('content')
    <x-email.badge bgColor="#E8F5E9" textColor="#2E7D32">
        ✓ Order Confirmed
    </x-email.badge>
    
    <tr>
        <td style="padding:24px 40px 32px;">
            <h2 style="margin:0 0 8px;color:#2C3E50;font-size:24px;font-weight:600;text-align:center;">
                Thank you, {{ $customerName }}!
            </h2>
            <p style="margin:0 0 24px;color:#5A6C7D;font-size:15px;text-align:center;">
                Your order has been confirmed and is being processed.
            </p>
            
            <x-email.info-box bgColor="#FFF4F0" extraStyle="border:2px solid #FF6B35;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding:4px 0;">
                            <span style="color:#5A6C7D;font-size:13px;">Order Number</span>
                        </td>
                        <td align="right" style="padding:4px 0;">
                            <strong style="color:#2C3E50;font-size:14px;">#{{ $orderId }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;">
                            <span style="color:#5A6C7D;font-size:13px;">Order Date</span>
                        </td>
                        <td align="right" style="padding:4px 0;">
                            <strong style="color:#2C3E50;font-size:14px;">{{ $orderDate }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0;">
                            <span style="color:#5A6C7D;font-size:13px;">Total Amount</span>
                        </td>
                        <td align="right" style="padding:4px 0;">
                            <strong style="color:#FF6B35;font-size:16px;">{{ $totalAmount }}</strong>
                        </td>
                    </tr>
                </table>
            </x-email.info-box>
            
            <h3 style="margin:0 0 16px;color:#2C3E50;font-size:18px;font-weight:600;">Order Details</h3>
            
            @foreach($items as $item)
                @include('emails.partials.order-item', ['item' => $item])
            @endforeach
            
            <x-email.button :url="$trackOrderUrl">
                Track Your Order
            </x-email.button>
        </td>
    </tr>
@endsection