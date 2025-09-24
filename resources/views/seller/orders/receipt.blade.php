<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>POS Receipt</title>
    <style>
        body {
            font-family: "Courier New", monospace;
            width: 302px;
            /* ~80mm */
            margin: 0 auto;
            font-size: 13px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }



        table td {
            padding: 2px 0;
        }

        .totals td {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }
    </style>
</head>

<body>
    @php
    $settings = settings();
    @endphp

    <div class="center">
        <h3>★ {{ $order->seller->business_name }} ★</h3>
        <p>
            {{ $order->seller->business_address }} <br>
            Phone: {{ $order->seller->phone }} <br>
        </p>
    </div>

    <div class="line"></div>
    <p>
        Invoice No : {{ $order->invoice_id }}<br>
        Date : {{ $order->created_at->format('d-m-Y  h:i A') }}<br>
        @if(!is_null($order->customer_id))
        Customer : {{ $order->customer->name }} ({{ $order->customer->phone }})
        @endif
    </p>
    <div class="line"></div>

    <table>
        <tr>
            <td class="left" style="width: 60%">Item</td>
            <td class="center" style="width: 20%">QTY</td>
            <td class="right" style="width: 20%">Total</td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="line"></div>
            </td>
        </tr>

        @foreach ($order->items as $item)
        <tr>
            <td class="left">{{ $item->product->name }} ({{ $item->variant->fullName }})</td>
            <td class="center"> {{ $item->quantity }}</td>
            <td class="right">{{ money($item->original_price * $item->quantity) }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="left">Subtotal</td>
            <td class="right">{{ money($order->sub_total + $order->discount) }}</td>
        </tr>
        @if ($order->discount > 0)
        <tr>
            <td class="left">Discount ({{ $order->discount_percentage }}%)</td>
            <td class="right">-{{ money($order->discount) }}</td>
        </tr>
        @endif
        <tr class="totals">
            <td class="left">Net Total</td>
            <td class="right">{{ money($order->total) }}</td>
        </tr>
        @if ($order->vat > 0)
        <tr>
            <td class="left">VAT ({{ $order->tax }}%)</td>
            <td class="right">{{ money($order->tax) }}</td>
        </tr>
        @endif
    </table>

    <div class="line"></div>

    @if ($order->due > 0)
    <table>
        <tr>
            <td class="left">Amount Paid</td>
            <td class="right">{{ money($order->paid) }}</td>
        </tr>
        <tr>
            <td class="left">Due</td>
            <td class="right">{{ money($order->due) }}</td>
        </tr>
    </table>
    @endif

    <div class="line"></div>

    <div class="center">
        <p>
            Thank you for shopping!<br>
            Visit: www.slash-mart.com
        </p>
    </div>

    <script>
        window.print();
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>

</html>