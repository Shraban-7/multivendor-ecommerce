@extends('seller.layouts.app')
@section('title', 'Orders')
@section('content')

    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">{{ $type ? ucfirst($type) : 'All' }} Orders</h1>
            <p class="text-sm text-ink-secondary mt-1">View and manage your orders</p>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
        </div>
        <div class="p-4">
            <form action="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div>
                        <input type="text" name="invoice_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Invoice ID" value="{{ request('invoice_id') }}">
                    </div>
                    <div>
                        <input type="text" name="customer_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Customer Name" value="{{ request('customer_name') }}">
                    </div>
                    <div>
                        <input type="text" name="customer_phone" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Customer Phone" value="{{ request('customer_phone') }}">
                    </div>
                    <div>
                        <input type="date" name="date_from" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                            value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <input type="date" name="date_to" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                            value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="search" class="icon-xs"></i> Search
                    </button>
                    @if(request('invoice_id') || request('customer_name') || request('customer_phone') || request('date_from') || request('date_to'))
                        <a href="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}" class="btn btn-light btn-sm">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th scope="col"># Order ID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Due</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="font-medium text-ink">#{{ $order->invoice_id }}</a>
                        </td>
                        <td>
                            <div>{{ $order->created_at->format('d/m/Y h:i A') }}</div>
                            @if ($order->created_at != $order->updated_at)
                                <div class="text-xs text-ink-tertiary">Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}</div>
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
                        <td>{{ money($order->payable) }}</td>
                        <td class="text-red-600">{{ money($order->due) }}</td>
                        <td>
                            @if ($order->total_commission != null)
                                {{ money($order->total_commission) }}
                                @if ($order->commission_type == \App\Enums\CommissionType::PERCENTAGE->value)
                                    ({{ $order->commission_amount }} %)
                                @endif
                            @endif
                        </td>
                        <td>
                            @php
                                $label = $order->status->label();
                                $colors = [
                                    'pending' => 'text-white bg-blue-500',
                                    'accepted' => 'text-ink-tertiary bg-surface-muted',
                                    'shipped' => 'text-white bg-indigo-500',
                                    'delivered' => 'text-white bg-green-500',
                                    'completed' => 'text-white bg-green-500',
                                    'cancelled' => 'text-white bg-red-500',
                                    'return_requested' => 'text-ink bg-yellow-400',
                                    'return_approved' => 'text-white bg-blue-500',
                                    'returned' => 'text-ink-tertiary bg-surface-muted',
                                    'refunded' => 'text-white bg-indigo-500',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $colors[$label] ?? 'text-ink-tertiary bg-surface-muted' }}">{{ $order->status->title() }}</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('seller.orders.details', $order->invoice_id) }}" title="Details" class="btn btn-light btn-sm">
                                    <i data-lucide="clipboard" class="icon-xs"></i> Details
                                </a>
                                <a href="{{ route('invoice', $order->invoice_id) }}" title="Invoice" target="__blank" class="btn btn-light btn-sm">
                                    <i data-lucide="download" class="icon-xs"></i> Invoice
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-ink-tertiary">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="flex justify-end mt-4">
            {{ $orders->links() }}
        </div>
    </div>

@endsection