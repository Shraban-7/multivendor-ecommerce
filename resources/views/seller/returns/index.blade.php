@extends('seller.layouts.app')
@section('title', 'Return Management')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="undo-2" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Returns</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Return Management</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-warning/15 text-feedback-warning">
                        <i data-lucide="package-x" style="width:11px;height:11px;" class="me-1"></i> RMA Pipeline
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage return and exchange requests across your products.</p>
            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 text-feedback-danger text-sm mb-3 alert-dismissible fade show">{{ session('error') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',             'label' => 'Total',             'top' => '#F85606', 'text' => 'text-brand-deep',            'icon' => 'package-x'],
        ['key' => 'pending',           'label' => 'Pending',           'top' => '#fb923c', 'text' => 'text-feedback-warning',      'icon' => 'hourglass'],
        ['key' => 'awaiting_shipment', 'label' => 'Awaiting Shipment', 'top' => '#0ea5e9', 'text' => 'text-feedback-info',         'icon' => 'truck'],
        ['key' => 'approved',          'label' => 'Approved',          'top' => '#10b981', 'text' => 'text-feedback-success',      'icon' => 'check-circle-2'],
        ['key' => 'received',          'label' => 'Item Received',     'top' => '#a855f7', 'text' => 'text-[#a855f7]',             'icon' => 'package-open'],
        ['key' => 'refunded',          'label' => 'Refunded',          'top' => '#6b7280', 'text' => 'text-ink-secondary',         'icon' => 'banknote'],
        ['key' => 'disputed',          'label' => 'Disputed',          'top' => '#ef4444', 'text' => 'text-feedback-danger',       'icon' => 'triangle-alert'],
    ];
@endphp
<section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-3 pt-4">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
                </div>
                <h3 class="text-xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTER + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2 flex-wrap">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>

        <div class="grow"></div>

        {{-- Quick filter chips --}}
        <div class="flex flex-wrap gap-1.5">
            <a href="{{ route('seller.returns.index') }}"
               class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ !request('status') && !request('disputed') ? 'bg-brand text-white' : 'bg-surface-muted text-ink-secondary' }}">
                <i data-lucide="layers" style="width:11px;height:11px;" class="me-1"></i> All
            </a>
            @foreach (['pending' => 'Pending', 'awaiting_shipment' => 'Awaiting', 'approved' => 'Approved', 'item_received' => 'Received', 'refunded' => 'Refunded', 'rejected' => 'Rejected'] as $key => $label)
                <a href="{{ route('seller.returns.index', ['status' => $key]) }}"
                   class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ request('status') === $key ? 'bg-brand text-white' : 'bg-surface-muted text-ink-secondary' }}">
                    {{ $label }}
                </a>
            @endforeach
            <a href="{{ route('seller.returns.index', ['disputed' => 1]) }}"
               class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ request('disputed') ? 'bg-feedback-danger text-white' : 'bg-surface-muted text-ink-secondary' }}">
                <i data-lucide="triangle-alert" style="width:11px;height:11px;" class="me-1"></i> Disputed
            </a>
        </div>
    </div>

    <div class="p-4 border-t border-border">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            @foreach (request()->except(['page', 'search']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <div class="md:col-span-12 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by RMA, order invoice, or customer name…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-12 flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Search
                </button>
                @if(request('search') || request('status') || request('disputed'))
                    <a href="{{ route('seller.returns.index') }}" class="btn btn-light">
                        <i data-lucide="x" style="width:14px;height:14px;"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ $returns->firstItem() ?? 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ $returns->lastItem() ?? 0 }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ $returns->total() }}</span> returns
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">RMA</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Order</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Customer</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Type</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Items</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Refund</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Dispute</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($returns as $return)
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ $return->rma_number }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('seller.orders.details', $return->order->invoice_id) }}" class="text-ink-emphasis hover:text-brand-deep">#{{ $return->order->invoice_id }}</a>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-ink-emphasis">{{ $return->user?->name ?? 'N/A' }}</div>
                            <small class="text-ink-tertiary">{{ $return->user?->phone ?? '—' }}</small>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-muted text-ink-secondary">
                                {{ $return->typeLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-soft">
                            @foreach ($return->items as $item)
                                <div>{{ $item->orderItem?->product?->name ?? 'Item' }} × {{ $item->quantity }}</div>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">{{ number_format($return->totalRefundAmount(), 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider text-white"
                                  style="background-color: {{ $return->statusColor() }}">
                                {{ $return->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($return->is_disputed)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-danger text-white">
                                    Disputed
                                </span>
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $return->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('seller.returns.show', $return) }}" class="btn btn-light btn-sm">
                                <i data-lucide="eye" style="width:13px;height:13px;"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            <div class="py-10 text-center">
                                <i data-lucide="package-x" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No return requests found</p>
                                <p class="text-ink-tertiary text-xs">Customer return requests will appear here once submitted.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $returns->links() }}
    </div>
</section>

@endsection
