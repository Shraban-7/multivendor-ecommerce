@extends('seller.layouts.app')
@section('title', 'Orders')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">{{ $type ? ucfirst($type) : 'All' }} Orders </h4>
        <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
            <i data-feather="filter" class="icon-xs"></i> Filter
        </button>
    </div>

    <div class="table-responsive">
        <table id="order-table" class="table table-bordered table-hover bg-white mb-3 text-nowrap">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="small fw-semibold text-muted"># Order ID</th>
                    <th scope="col" class="small fw-semibold text-muted">Date</th>
                    <th scope="col" class="small fw-semibold text-muted">Customer</th>
                    <th scope="col" class="small fw-semibold text-muted">Subtotal</th>
                    <th scope="col" class="small fw-semibold text-muted">Due</th>
                    <th scope="col" class="small fw-semibold text-muted">Commission</th>
                    <th scope="col" class="small fw-semibold text-muted">Status</th>
                    <th scope="col" class="small fw-semibold text-muted">Action</th>
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
                        <td>
                            {{ $order->created_at->format('d/m/Y h:i A') }}
                            @if ($order->created_at != $order->updated_at)
                                <p class="small text-muted mb-0">Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}
                                </p>
                            @endif
                        </td>
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
                            @if ($order->status->label() === 'pending')
                                <span class="badge badge-soft-warning">Pending</span>
                            @elseif ($order->status->label() === 'accepted')
                                <span class="badge badge-soft-secondary">Accepted</span>
                            @elseif ($order->status->label() === 'shipped')
                                <span class="badge badge-soft-primary">Shipped</span>
                            @elseif ($order->status->label() === 'cancelled')
                                <span class="badge badge-soft-danger">Cancelled</span>
                            @elseif ($order->status->label() === 'delivered')
                                <span class="badge badge-soft-success">Delivered</span>
                            @elseif ($order->status->label() === 'returned')
                                <span class="badge badge-soft-secondary">Returned</span>
                            @elseif ($order->status->label() === 'refunded')
                                <span class="badge badge-soft-info">Refunded</span>
                            @elseif ($order->status->label() === 'completed')
                                <span class="badge badge-soft-success">Completed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" title="Details"
                                class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1 me-1">
                                <i data-feather="clipboard" class="icon-xs"></i> Details
                            </a>
                            <a href="{{ route('invoice', $order->invoice_id) }}" title="Invoice" target="__blank"
                                class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1 me-1">
                                <i data-feather="download" class="icon-xs"></i> Invoice
                            </a>
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
            <form action="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}" method="GET">
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
                    <a href="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}"
                        class="btn btn-outline-secondary w-100">Reset</a>
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

@endsection
