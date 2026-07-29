@extends('admin.layouts.app')
@section('title', 'Orders')
@section('content')

    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Orders</h1>
            <p class="text-sm text-ink-secondary mt-1">View all marketplace orders</p>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                            placeholder="Search by invoice ID..." value="{{ request('search') }}">
                    </div>
                    <div class="w-44">
                        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="">All Status</option>
                            @foreach (\App\Domain\Order\Enums\OrderStatus::cases() as $s)
                                <option value="{{ $s->value }}" {{ request('status') == $s->value ? 'selected' : '' }}>{{ $s->title() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-lucide="search" class="icon-xs"></i> Search
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th scope="col">Invoice ID</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Sale Amount</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="font-medium text-ink">{{ $order->invoice_id }}</td>
                        <td><x-seller :seller="$order->seller" /></td>
                        <td>{{ money($order->payable) }}</td>
                        <td>
                            @if ($order->total_commission != null)
                                {{ money($order->total_commission) }}
                                @if($order->commission_type == \App\Enums\CommissionType::PERCENTAGE->value)
                                    ({{ $order->commission_amount }} %)
                                @endif
                            @else
                                <span class="text-ink-tertiary">—</span>
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
                        <td class="text-ink-tertiary text-xs">{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-ink-tertiary">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-4">
        {{ $orders->links() }}
    </div>

@endsection