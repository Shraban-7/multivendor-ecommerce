@extends('seller.layouts.app')
@section('title', 'Orders')
@section('content')

    <div class="mb-2 rounded d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ ucfirst($type) }} Orders </h4>
        <button class="btn btn-sm btn-primary" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
            <i data-feather="filter" class="icon-xs"></i> Filter
        </button>
    </div>

    <div class="table-responsive">
        <table id="order-table" class="table table-bordered bg-white mb-3 text-nowrap">
            <thead>
                <tr>
                    <th scope="col"># Order ID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Due</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td> 
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" target="__blank">#
                                {{ $order->invoice_id }}
                            </a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y h:i A') }}</td>
                        <td> {{ $order->user->name }} </td>
                        <td> <span class="text-dark">{{ money($order->payable) }}</span> </td>
                        <td> <span class="text-danger"> {{ money($order->due) }}</span> </td>
                        <td>
                            @if ($order->total_commission != null)
                                {{ money($order->total_commission) }}
                                @if ($order->commission_type == \App\Enums\CommissionType::PERCENTAGE->value)
                                    ({{ $order->commission_amount }} %)
                                @endif
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" title="Details"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="clipboard" class="icon-xs"></i> Details
                            </a>
                            <a href="{{ route('invoice', $order->invoice_id) }}" title="Details" target="__blank"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="download" class="icon-xs"></i> Invoice
                            </a>
                            {{-- <a href="{{ route('receipt', $order->invoice_id) }}" title="Details" target="__blank"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="download" class="icon-xs"></i> Receipt
                            </a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $orders->links() }}
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Filter Orders</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            {{-- Dynamically pick the route based on $type --}}
            <form action="{{ route('seller.orders.' . $type) }}" method="GET">
                <div class="mb-3">
                    <label for="invoice_id" class="form-label">Invoice ID</label>
                    <input type="text" class="form-control" id="invoice_id" name="invoice_id"
                        value="{{ request('invoice_id') }}">
                </div>
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                        value="{{ request('customer_name') }}">
                </div>
                <div class="mb-3">
                    <label for="customer_phone" class="form-label">Customer Phone</label>
                    <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                        value="{{ request('customer_phone') }}">
                </div>
                <div class="mb-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>
                <div class="mb-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to"
                        value="{{ request('date_to') }}">
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('seller.orders.' . $type) }}" class="btn btn-outline-secondary w-100">Reset</a>
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>


@endsection
