@php
    use App\Domain\Order\Enums\OrderStatus;

    $counts = $counts ?? [];
@endphp
@extends('seller.layouts.app')
@section('title', 'Orders')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="shopping-bag" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $type ? ucfirst(str_replace('_',' ', $type)) : 'All' }} Orders</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $type ? ucfirst(str_replace('_',' ', $type)) : 'All' }} Orders</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="receipt" style="width:11px;height:11px;" class="me-1"></i> Order Pipeline
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Review, search and progress your orders through the fulfilment pipeline.</p>
            </div>
        </div>
    </div>
</section>

{{-- Flash messages --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 text-feedback-danger text-sm mb-3 alert-dismissible fade show">{{ session('error') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $statusCards = [
        ['key' => 'total',         'label' => 'Total',         'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'inbox'],
        ['key' => 'pending',       'label' => 'Pending',       'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'hourglass'],
        ['key' => 'accepted',      'label' => 'Accepted',      'top' => '#fb923c', 'text' => 'text-feedback-warning',  'icon' => 'package-check'],
        ['key' => 'shipped',       'label' => 'Shipped',       'top' => '#a855f7', 'text' => 'text-[#a855f7]',         'icon' => 'truck'],
        ['key' => 'delivered',     'label' => 'Delivered',     'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'package-open'],
        ['key' => 'completed',     'label' => 'Completed',     'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'cancelled',     'label' => 'Cancelled',     'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'x-circle'],
    ];
@endphp
<section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 mb-3">
    @foreach ($statusCards as $card)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $card['top'] }};"></div>
            <div class="p-3 pt-4">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $card['label'] }}</span>
                    <i data-lucide="{{ $card['icon'] }}" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
                </div>
                <h3 class="text-xl font-bold {{ $card['text'] }} mb-0">{{ number_format($counts[$card['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTER CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if(request('invoice_id') || request('customer_name') || request('customer_phone') || request('date_from') || request('date_to'))
            <a href="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}"
               class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-border">
        <form action="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}" method="GET"
              class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-3 relative">
                <i data-lucide="hash" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="invoice_id" value="{{ request('invoice_id') }}"
                       placeholder="Invoice ID…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-3 relative">
                <i data-lucide="user" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="customer_name" value="{{ request('customer_name') }}"
                       placeholder="Customer name…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-2 relative">
                <i data-lucide="phone" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="customer_phone" value="{{ request('customer_phone') }}"
                       placeholder="Phone…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-2 relative">
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors"
                       placeholder="From">
            </div>
            <div class="md:col-span-1">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors"
                       placeholder="To">
            </div>
            <div class="md:col-span-1">
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<form id="orderFilterForm" action="{{ $type ? route('seller.orders.' . $type) : route('seller.orders.index') }}" method="GET" class="hidden">@csrf</form>

{{-- ═══ TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 pt-4 pb-1 flex flex-wrap items-center justify-between gap-2">
        <div class="text-xs text-ink-tertiary">
            Showing
            <span class="text-ink-emphasis font-semibold">{{ $orders->firstItem() ?? 0 }}</span>
            – <span class="text-ink-emphasis font-semibold">{{ $orders->lastItem() ?? 0 }}</span>
            of <span class="text-ink-emphasis font-semibold">{{ $orders->total() }}</span> orders
        </div>
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Order #</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Customer</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Subtotal</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Due</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Commission</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    @php
                        $label = $order->status->label();
                        $pillBg = match (true) {
                            in_array($label, ['completed', 'delivered'])    => 'bg-feedback-success/15 text-feedback-success',
                            in_array($label, ['accepted'])                  => 'bg-surface-muted text-ink-emphasis',
                            in_array($label, ['shipped'])                   => 'bg-[#a855f7]/15 text-[#a855f7]',
                            in_array($label, ['pending', 'return_requested']) => 'bg-feedback-info/15 text-feedback-info',
                            in_array($label, ['cancelled'])                 => 'bg-feedback-danger/15 text-feedback-danger',
                            in_array($label, ['return_approved'])           => 'bg-feedback-info/15 text-feedback-info',
                            in_array($label, ['returned','refunded'])        => 'bg-surface-muted text-ink-secondary',
                            default                                          => 'bg-surface-muted text-ink-tertiary',
                        };
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}"
                               class="font-semibold text-ink-emphasis hover:text-brand-deep">
                                #{{ $order->invoice_id }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $order->created_at->format('d M Y · H:i') }}
                            @if ($order->created_at != $order->updated_at)
                                <small class="text-ink-tertiary block ms-4 mt-0.5">Updated {{ $order->updated_at->diffForHumans() }}</small>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($order->user)
                                <div class="font-medium text-ink-emphasis">{{ $order->user->name }}</div>
                                <small class="text-ink-tertiary">{{ $order->user->phone }}</small>
                            @elseif ($order->customer)
                                <div class="font-medium text-ink-emphasis">{{ $order->customer->name }}</div>
                            @else
                                <span class="text-ink-tertiary">Guest</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ money($order->payable) }}</td>
                        <td class="px-4 py-3 text-feedback-danger">{{ money($order->due) }}</td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            @if ($order->total_commission != null)
                                {{ money($order->total_commission) }}
                                @if ($order->commission_type == \App\Enums\CommissionType::PERCENTAGE->value)
                                    <small class="text-ink-tertiary">({{ $order->commission_amount }}%)</small>
                                @endif
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ $order->status->title() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1.5">
                                <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-light btn-sm">
                                    <i data-lucide="clipboard" style="width:13px;height:13px;"></i> Details
                                </a>
                                <a href="{{ route('invoice', $order->invoice_id) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i data-lucide="download" style="width:13px;height:13px;"></i> Invoice
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="py-10 text-center">
                                <i data-lucide="shopping-bag" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No orders found</p>
                                <p class="text-ink-tertiary text-xs">Once customers place orders they'll appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $orders->links() }}
    </div>
</section>

@endsection
