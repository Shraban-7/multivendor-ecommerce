<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="images/favicon.png" rel="icon" />
    <title>Invoice {{ $order->invoice_id }}</title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <div class="container-fluid invoice-container">
        <header>
            <div class="row align-items-center gy-3">
                <div class="col-sm-7 text-center text-sm-start">
                    @isset($order->seller->business_logo)
                    <img src="{{ storage_url($order->seller->business_logo) }}" height="100" alt="img" />
                    @endisset
                </div>
                <div class="col-sm-5 text-center text-sm-end">
                    <h4 class="text-7 mb-0">Invoice</h4>
                </div>
            </div>
            <hr>
        </header>

        <main>
            <div class="row">
                <div class="col-sm-6"><strong>Date:</strong> {{ $order->created_at }}</div>
                <div class="col-sm-6 text-sm-end"> <strong>Invoice No:</strong> {{ $order->invoice_id }}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-6 text-sm-end order-sm-1"> <strong>Pay To:</strong>
                    @isset($order->seller)
                    <address>
                        @if($order->seller->business_name)
                        {{ $order->seller->business_name }} <br>
                        @endif
                        @if($order->seller->business_address)
                        {{ $order->seller->business_address }} <br>
                        @endif
                        @if($order->seller->phone)
                        {{ $order->seller->phone }} <br>
                        @endif
                        @if($order->seller->business_email)
                        {{ $order->seller->business_email }} <br>
                        @endif
                    </address>
                    @endisset
                </div>
                <div class="col-sm-6 order-sm-0"> <strong>Invoiced To:</strong>
                    <address>
                        {{ $order->customer_name ?? $order->user->name }} <br>
                        {{ $order->customer_address ?? $order->user->country->name }} <br>
                        {{ $order->customer_phone ?? $order->user->phone }}
                    </address>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table border mb-0">
                    <thead>
                        <tr class="bg-light">
                            <td class="col-4"><strong>Item</strong></td>
                            <td class="col-4 text-center"><strong>Rate</strong></td>
                            <td class="col-1 text-center"><strong>QTY</strong></td>
                            <td class="col-2 text-end"><strong>Amount</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td class="col-4">{{ $item->product->name }}</td>
                            <td class="col-4 text-center">{{ $item->unit_price }}</td>
                            <td class="col-1 text-center">{{ $item->quantity }}</td>
                            <td class="col-2 text-end">{{ $item->sub_total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-borderless">
                    <tr>
                        <td class="text-end"><strong>Sub Total:</strong></td>
                        <td class="col-sm-2 text-end">{{ $order->total }}</td>
                    </tr>
                    @if($order->discount > 0)
                    <tr>
                        <td class="text-end"><strong>Discount (-):</strong></td>
                        <td class="col-sm-2 text-end">{{ $order->discount }}</td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>Total:</strong></td>
                        <td class="col-sm-2 text-end fw-bold">{{ $order->payable }}</td>
                    </tr>
                    @endif
                    @if($order->due > 0)
                    <tr>
                        <td class="text-end"><strong>Paid:</strong></td>
                        {{-- <td class="col-sm-2 text-end">{{ $order->paid }}</td> --}}
                    </tr>
                    <tr>
                        <td class="text-end text-danger"><strong>Due:</strong></td>
                        {{-- <td class="col-sm-2 text-end text-danger fw-bold">{{ $order->due }}</td> --}}
                    </tr>
                    @endif
                </table>
            </div>
        </main>

        <footer class="text-center">
            <div class="d-flex justify-content-end text-dark">
                <div>
                    {{-- @isset($settings->signature)
                    <img id="signature" src="{{ storage_url($settings->signature) }}" height="100" alt="signature" />
                    @endisset --}}
                    <div class="border mb-2" style="width: 200px;"></div>
                    <p>Signature</p>
                </div>
            </div>
        </footer>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<script>
    window.print();
    window.onafterprint = function() {
        window.close();
    };
</script>

</html>
