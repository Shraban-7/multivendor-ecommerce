<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="images/favicon.png" rel="icon" />
    <title>Invoice {{ $order->invoice_id }}</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900'
        type='text/css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/invoice/css/stylesheet.css') }}" />

    <style>
        html {
            min-height: 100vh;
        }

        .invoice-container {
            min-height: 100vh;
            position: relative;
        }

        .table-borderless td {
            padding: 5px !important;
        }

        footer {
            position: absolute;
            bottom: 0;
            right: 0;
        }

        #signature {
            margin-bottom: -20px !important;
        }
    </style>
</head>

<body>
    @php
        $settings = settings();
    @endphp
    <div class="container-fluid invoice-container">
        {{-- <header>
            <div class="row align-items-center gy-3">
                <div class="col-sm-7 text-center text-sm-start">
                    @isset($settings->logo)
                        <a href="{{ route('home') }}">
                            <img src="{{ storage_url($settings->logo) }}" height="64px" alt="img" />
                        </a>
                    @endisset
                </div>
                <div class="col-sm-5 text-center text-sm-end">
                    <h4 class="text-7 mb-3 fw-bold">Invoice</h4>
                    <table class="table table-sm m-0 border  border-1">
                        <tbody>
                            <tr>
                                <td class="pe-2 text-muted">Date:</td>
                                <td class="fw-semibold">{{ $order->created_at->format('d M, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="pe-2 text-muted">Order No:</td>
                                <td class="fw-semibold">{{ $order->order_no ?? $order->invoice_id }}</td>
                            </tr>
                            <tr>
                                <td class="pe-2 text-muted">Invoice No:</td>
                                <td class="fw-semibold">{{ $order->invoice_id }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
            <hr>
        </header> --}}

        <header class="mb-4">
            <div class="row">
                <div class="col-sm-7">
                    @isset($settings->logo)
                        <a href="{{ route('home') }}">
                            <img src="{{ storage_url($settings->logo) }}" height="100" alt="img" />
                        </a>
                    @endisset
                    <p class="mb-0">
                        {{ $settings->address ?? '' }}<br>
                        Phone: {{ $settings->phone ?? '' }}
                    </p>
                </div>
                <div class="col-sm-5 text-end">
                    <h3 class="fw-bold">INVOICE</h3>
                    <table class="table table-bordered table-sm mb-0">
                        <tr>
                            <td>Date</td>
                            <td>{{ $order->created_at->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <td>Order No</td>
                            <td>{{ $order->invoice_id }}</td>
                        </tr>
                        <tr>
                            <td>Invoice No</td>
                            <td>{{ $order->invoice_id }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </header>

        <hr class="border-3 bg-dark">

        <main>
            <div class="row">
                <div class="col-sm-6 text-sm-end order-sm-1 mb-3">
                    <strong>Pay To:</strong>
                    @isset($order->seller)
                        <address class="mb-0">
                            @if ($order->seller->business_name)
                                {{ $order->seller->business_name }} <br>
                            @endif
                            @if ($order->seller->business_address)
                                {{ $order->seller->business_address }} <br>
                            @endif
                            @if ($order->seller->phone)
                                {{ $order->seller->phone }} <br>
                            @endif
                            @if ($order->seller->business_email)
                                {{ $order->seller->business_email }} <br>
                            @endif
                        </address>
                    @endisset
                </div>

                <div class="col-sm-6 order-sm-0 mb-3">
                    <strong>Invoiced To:</strong>
                    <address class="mb-0">
                        {{ $order->customer_name ?? '' }} <br>
                        {{ $order->customer_address ?? '' }} <br>
                        {{ $order->customer_phone ?? '' }}
                    </address>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="col-4"><strong>Item</strong></th>
                            <th class="col-4 text-center"><strong>Rate</strong></th>
                            <th class="col-1 text-center"><strong>QTY</strong></th>
                            <th class="col-2 text-end"><strong>Amount</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product->name }}
                                    @if ($item->product_variant_id && $item->variant && $item->variant->option_values)
                                        <div class="text-muted small mt-1">
                                            @foreach ($item->variant->option_values as $value)
                                                <span class="me-2">
                                                    {{ $value->option->name ?? '' }}: {{ $value->value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->selling_price > $item->unit_price)
                                        <span style="text-decoration: line-through;">
                                            {{ money($item->selling_price) }}
                                        </span>
                                    @endif
                                    {{ money($item->unit_price) }}  
                                </td>

                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">
                                    @if ($item->selling_price > $item->unit_price)
                                        <span style="text-decoration: line-through;">
                                            {{ money($item->selling_price * $item->quantity) }}
                                        </span>
                                    @endif
                                    {{ money($item->total) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <p><strong>Total Qty:</strong> {{ $order->items->sum('quantity') }}</p>
                    <p><strong>In Words:</strong> {{ convert_number_to_words_bdt($order->total) }} Taka only.</p>
                    <p><strong>Payment Term:</strong>
                        @if ($order->due > 0)
                            Cash on Delivery (COD)
                        @else
                            Paid in Full
                        @endif
                    </p>
                </div>


                <div class="col-md-6">
                    <table class="table table-bordered table-sm mb-0">
                        <tr>
                            <td class="text-end text-uppercase"><strong>SUB TOTAL:</strong></td>
                            <td class="text-end text-uppercase">
                                {{ strtoupper(money($order->total + $order->discount)) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end text-uppercase"><strong>SHIPPING FEE:</strong></td>
                            <td class="text-end text-uppercase">{{ strtoupper(money($order->shipping_fee)) }}</td>
                        </tr>
                        @if ($order->discount)
                            <tr>
                                <td class="text-end text-uppercase"><strong>DISCOUNT:</strong></td>
                                <td class="text-end text-uppercase">{{ strtoupper(money($order->discount)) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-end text-uppercase"><strong>TOTAL:</strong></td>
                            <td class="text-end fw-bold text-uppercase">{{ strtoupper(money($order->payable)) }}</td>
                        </tr>
                        @if ($order->due > 0)
                            <tr>
                                <td class="text-end text-uppercase"><strong>PAID:</strong></td>
                                <td class="text-end text-uppercase">
                                    {{ strtoupper(money($order->payable - $order->due)) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end text-danger text-uppercase"><strong>DUE:</strong></td>
                                <td class="text-end text-danger fw-bold text-uppercase">
                                    {{ strtoupper(money($order->due)) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-end text-uppercase"><strong>PAID:</strong></td>
                                <td class="text-end text-success fw-bold text-uppercase">
                                    {{ strtoupper(money($order->payable)) }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </main>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
</script>
<script>
    window.print();
    window.onafterprint = function() {
        window.close();
    };
</script>

</html>
