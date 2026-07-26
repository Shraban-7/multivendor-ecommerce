<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order #{{ $order->invoice_id }} Receipt</title>
    <style>
        body {
            font-family: "Courier New", "OCR A Std", monospace;
            font-size: 13px;
            width: 302px;
            margin: 0 auto;
            font-weight: 600;
            color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .left {text-align: start;}
        .right {text-align: end;}
        .center {text-align: center;}
        .line {border-top: 1px dashed #000; margin: 5px 0;}
        table {width: 100%; border-collapse: collapse;}
        table td {
            padding: 2px 0;
            /* font-weight: 600; */
        }
        h3 {margin-bottom: 5px;}
        p {margin-top: 5px; margin-bottom: 8px;}
        @media print {
            body, td, p, h3 {
                color: #000 !important;
                font-weight: 600 !important;
            }
            .bold {
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
        <h3 class="bold">{{ $order->seller->business_name }}</h3>
        <p>{{ $order->seller->business_address }}</p>
        <p> Phone: {{ $order->seller->phone }}</p>
    </div>

    <div class="line" style="margin-top: 20px;"></div>

    <p>
        <div style="display: flex; justify-content: space-between; margin-bottom:10px;">
            <span>#{{ $order->invoice_id }}</span>
            <span>{{ $order->created_at->format('d/m/y h:ia') }}</span>
        </div>

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
                <td class="left">{{ $item->product->name }} @if ($item->variant)
                    <small>({{ $item->variant->label }})</small>
                @endif </td>
                <td class="center">{{ $item->quantity }}</td>
                <td class="right">{{ money($item->unit_price * $item->quantity, $showCurrency) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <!-- <tr>
            <td class="left">Subtotal</td>
            <td class="right">{{ money($order->sub_total, $showCurrency) }}</td>
        </tr> -->
        <!-- @if ($order->discount > 0)
        <tr>
            <td class="left">Discount</td>
            <td class="right">{{ money($order->discount, $showCurrency) }}</td>
        </tr>
        @endif -->
        <!-- <tr>
            <td colspan="2">
                <div class="line"></div>
            </td>
        </tr> -->
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
        <p style="margin-top: 20px;">Thank you for shopping with us!</p>
        <p>www.slash-mart.com</p>
    </div>
</body>

</html>