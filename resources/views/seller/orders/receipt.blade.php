<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order #{{ $order->invoice_id }} Receipt</title>
    <style>
        body {
            font-family: "Courier Prime", "Courier New", monospace;
            font-size: 13px;
            width: 302px;
            margin: 0 auto;
            font-weight: 700;
            color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .left {text-align: start;}
        .right {text-align: end;}
        .center {text-align: center;}
        .line {border-top: 1px dashed #000; margin: 5px 0;}
        table {width: 100%; border-collapse: collapse;}
        table td {padding: 2px 0; font-weight: 700;}
        @media print {
            body, td, p, h3 {
                color: #000 !important;
                font-weight: 700 !important;
            }
        }
    </style>

</head>

<?php
    $settings = settings();
    $showCurrency = false;
?>

<body>
    <div class="center">
        <h3>★ {{ $order->seller->business_name }} ★</h3>
        <p>
            {{ $order->seller->business_address }} <br>
            Phone: {{ $order->seller->phone }} <br>
        </p>
    </div>

    <div class="line"></div>

    <p>
        <strong>Invoice: #{{ $order->invoice_id }}</strong><br>
        Date: {{ $order->created_at->format('d-m-Y  h:i A') }}<br>
        @if (!is_null($order->customer_id))
        Customer: {{ $order->customer->name }}<br>
        Phone: {{ $order->customer->phone }}
        @endif
    </p>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th class="left" style="width: 70%">Item</th>
                <th class="center" style="width: 10%">Qty</th>
                <th class="right" style="width: 20%">Total</th>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="line"></div>
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td class="left">{{ $item->product->name }} <small>({{ $item->variant->fullName }})</small></td>
                <td class="center">{{ $item->quantity }}</td>
                <td class="right">{{ money($item->original_price * $item->quantity, $showCurrency) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="left">Subtotal</td>
            <td class="right">{{ money($order->sub_total, $showCurrency) }}</td>
        </tr>
        @if ($order->discount > 0)
        <tr>
            <td class="left">Discount</td>
            <td class="right">{{ money($order->discount, $showCurrency) }}</td>
        </tr>
        @endif
        @if ($order->vat > 0)
        <tr>
            <td class="left">VAT ({{ number_format($order->tax_rate ?? 0, 2) }}%)</td>
            <td class="right">{{ money($order->vat, $showCurrency) }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="2">
                <div class="line"></div>
            </td>
        </tr>
        <tr class="totals">
            <td class="left"><strong>TOTAL</strong></td>
            <td class="right"><strong>{{ money($order->total, $showCurrency) }}</strong></td>
        </tr>
    </table>

    @if ($order->due > 0)
    <div class="line"></div>
    <table>
        <tr>
            <td class="left">Amount Paid</td>
            <td class="right">{{ money($order->paid, $showCurrency) }}</td>
        </tr>
        <tr>
            <td class="left"><strong>Amount Due</strong></td>
            <td class="right"><strong>{{ money($order->due, $showCurrency) }}</strong></td>
        </tr>
    </table>
    @endif

    <div class="line"></div>

    <div class="center">
        <p>
            Thank you for shopping!<br> <br>
            www.slash-mart.com
        </p>
    </div>
</body>

</html>