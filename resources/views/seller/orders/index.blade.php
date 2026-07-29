@extends('seller.layouts.app')
@section('title', 'Orders')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">{{ $type ? ucfirst($type) : 'All' }} Orders </h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
            <i data-feather="filter" class="icon-xs"></i> Filter
        </button>
    </div>

    <div class="overflow-x-auto">
        <table id="order-table" class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover bg-white mb-3 whitespace-nowrap">
            <thead class="bg-surface-muted">
                <tr>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary"># Order ID</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Date</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Customer</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Subtotal</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Due</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Commission</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Status</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" target="__blank">#
                                {{ $order->invoice_id }}
                            </a>
                        </td>
                        <td>
                            {{ $order->created_at->format('d/m/Y h:i A') }}
                            @if ($order->created_at != $order->updated_at)
                                <p class="text-sm text-ink-tertiary mb-0">Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}
                                </p>
                            @endif
                        </td>
                        <td>
                            @if ($order->user)
                                {{ $order->user->name }}
                            @elseif ($order->customer)
                                {{ $order->customer->name }}
                            @else
                                <span class="text-ink-tertiary">Guest</span>
                            @endif
                        </td>
                        <td> <span class="text-ink">{{ money($order->payable) }}</span> </td>
                        <td> <span class="text-feedback-danger"> {{ money($order->due) }}</span> </td>
                        <td>
                            @if ($order->total_commission != null)
                                {{ money($order->total_commission) }}
                                @if ($order->commission_type == \App\Enums\CommissionType::PERCENTAGE->value)
                                    ({{ $order->commission_amount }} %)
                                @endif
                            @endif
                        </td>
                        <td>
                            @php $label = $order->status->label(); @endphp
                            @if ($label === 'pending')
                                <span class="badge badge-soft-warning">Pending</span>
                            @elseif ($label === 'accepted')
                                <span class="badge badge-soft-secondary">Accepted</span>
                            @elseif ($label === 'shipped')
                                <span class="badge badge-soft-primary">Shipped</span>
                            @elseif ($label === 'cancelled')
                                <span class="badge badge-soft-danger">Cancelled</span>
                            @elseif ($label === 'delivered')
                                <span class="badge badge-soft-success">Delivered</span>
                            @elseif ($label === 'returned')
                                <span class="badge badge-soft-secondary">Returned</span>
                            @elseif ($label === 'refunded')
                                <span class="badge badge-soft-info">Refunded</span>
                            @elseif ($label === 'completed')
                                <span class="badge badge-soft-success">Completed</span>
                            @elseif ($label === 'return_requested')
                                <span class="badge badge-soft-warning">Return Requested</span>
                            @elseif ($label === 'return_approved')
                                <span class="badge badge-soft-info">Return Approved</span>
                            @else
                                <span class="badge badge-soft-secondary">{{ $order->status->title() }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" title="Details"
                                class="btn btn-light btn-sm me-1">
                                <i data-feather="clipboard" class="icon-xs"></i> Details
                            </a>
                            <a href="{{ route('invoice', $order->invoice_id) }}" title="Invoice" target="__blank"
                                class="btn btn-light btn-sm me-1">
                                <i data-feather="download" class="icon-xs"></i> Invoice
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-ink-tertiary py-4">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="flex justify-end">
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
                    <label for="invoice_id" class="block text-xs font-medium text-ink-secondary mb-1">Invoice ID</label>
                    <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="invoice_id" name="invoice_id"
                        value="{{ request('invoice_id') }}">
                </div>
                <div class="mb-3">
                    <label for="customer_name" class="block text-xs font-medium text-ink-secondary mb-1">Customer Name</label>
                    <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="customer_name" name="customer_name"
                        value="{{ request('customer_name') }}">
                </div>
                <div class="mb-3">
                    <label for="customer_phone" class="block text-xs font-medium text-ink-secondary mb-1">Customer Phone</label>
                    <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="customer_phone" name="customer_phone"
                        value="{{ request('customer_phone') }}">
                </div>
                <div class="mb-3">
                    <label for="date_from" class="block text-xs font-medium text-ink-secondary mb-1">Date From</label>
                    <input type="date" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>
                <div class="mb-3">
                    <label for="date_to" class="block text-xs font-medium text-ink-secondary mb-1">Date To</label>
                    <input type="date" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="date_to" name="date_to"
                        value="{{ request('date_to') }}">
                </div>

                <div class="flex gap-2">
                    <a href="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}"
                        class="btn btn-light w-full">Reset</a>
                    <button type="submit" class="btn btn-primary w-full">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

@endsection
