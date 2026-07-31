@php
    $pageTitle = 'Seller Payouts';

    $kpis = [
        ['label' => 'Pending',    'value' => money($stats['pending'] ?? 0),                               'sub' => ($stats['total_pending_count'] ?? 0).' requests awaiting review', 'icon' => 'clock',         'tone' => 'warning'],
        ['label' => 'Processing', 'value' => money($stats['processing'] ?? 0),                            'sub' => 'In-flight transfers',                                     'icon' => 'loader',         'tone' => 'info'],
        ['label' => 'Completed',  'value' => money($stats['completed'] ?? 0),                             'sub' => 'Settled this period',                                     'icon' => 'check-circle',   'tone' => 'success'],
        ['label' => 'Total Paid', 'value' => money($stats['total_paid'] ?? ($stats['completed'] ?? 0)),   'sub' => 'Lifetime disbursed to sellers',                           'icon' => 'dollar-sign',    'tone' => 'brand'],
    ];

    $toneBg = [
        'warning' => 'bg-amber-500',
        'info'    => 'bg-blue-500',
        'success' => 'bg-emerald-500',
        'danger'  => 'bg-rose-500',
        'brand'   => 'bg-brand',
        'muted'   => 'bg-gray-500',
    ];

    $toneIcon = [
        'warning' => 'bg-warning-tint text-feedback-warning',
        'info'    => 'bg-info-tint text-feedback-info',
        'success' => 'bg-emerald-50 text-feedback-success',
        'danger'  => 'bg-rose-50 text-rose-500',
        'brand'   => 'bg-brand-tint text-brand',
        'muted'   => 'bg-surface-muted text-ink-tertiary',
    ];

    $statusMap = [
        0 => ['label' => 'Pending',    'pill' => 'bg-amber-500 text-white',                 'icon' => 'hourglass'],
        1 => ['label' => 'Processing', 'pill' => 'bg-blue-500 text-white',                  'icon' => 'loader'],
        2 => ['label' => 'Completed',  'pill' => 'bg-emerald-500 text-white',               'icon' => 'check-circle'],
        3 => ['label' => 'Cancelled',  'pill' => 'bg-rose-500 text-white',                  'icon' => 'x-circle'],
        4 => ['label' => 'Failed',     'pill' => 'bg-rose-600 text-white',                  'icon' => 'alert-circle'],
    ];
@endphp
@extends('admin.layouts.app')
@section('title', $pageTitle)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="banknote" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Finance</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink font-semibold">Seller Payouts</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink mb-0">{{ $pageTitle }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand">
                        <i data-lucide="wallet" style="width:11px;height:11px;" class="me-1"></i> Finance
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Review, approve, and reconcile all seller withdrawal requests in one place.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES — 4 CARDS ═══ --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    @foreach ($kpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 {{ $toneBg[$kpi['tone']] ?? 'bg-gray-500' }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-2xl text-ink mt-1">{{ $kpi['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center {{ $toneIcon[$kpi['tone']] ?? 'bg-surface-muted text-ink-tertiary' }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTERS ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
        <i data-lucide="sliders-horizontal" class="text-feedback-info" style="width:16px;height:16px;"></i>
        <h5 class="mb-0 font-bold text-ink text-sm">Filter Payouts</h5>
    </div>
    <form method="GET" class="px-4 py-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    @foreach ($statusMap as $key => $meta)
                        <option value="{{ $key }}" {{ (string) request('status') === (string) $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Seller ID</label>
                <input type="text" name="seller_id" value="{{ request('seller_id') }}" placeholder="Seller ID"
                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Apply
                </button>
                <a href="{{ route('admin.payouts.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="rotate-ccw" style="width:14px;height:14px;"></i> Reset
                </a>
            </div>
        </div>
    </form>
</section>

{{-- ═══ PAYOUT LIST ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="list" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink text-sm">Payout Requests</h5>
        </div>
        @if (request()->hasAnyFilled(['status', 'seller_id', 'date_from', 'date_to']))
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-brand-tint text-brand">
                <i data-lucide="filter" style="width:11px;height:11px;" class="me-1"></i> Filtered
            </span>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 w-10">#</th>
                    <th class="px-4 py-2.5">Seller</th>
                    <th class="px-4 py-2.5 text-right">Amount</th>
                    <th class="px-4 py-2.5 text-right">Fee</th>
                    <th class="px-4 py-2.5 text-right">Net</th>
                    <th class="px-4 py-2.5">Method</th>
                    <th class="px-4 py-2.5 text-center">Status</th>
                    <th class="px-4 py-2.5">Date</th>
                    <th class="px-4 py-2.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($payouts as $payout)
                    @php $meta = $statusMap[$payout->status] ?? $statusMap[0]; @endphp
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs bg-surface-muted text-ink-tertiary">#{{ $payout->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $payout->seller->businessAvatar }}" alt="" height="32" width="32"
                                     class="rounded-sm border border-border object-cover shrink-0" style="width:32px;height:32px;">
                                <div class="min-w-0">
                                    <p class="mb-0 font-medium text-ink truncate">{{ $payout->seller->business_name ?? $payout->seller->name }}</p>
                                    <small class="text-ink-tertiary font-mono">ID: {{ $payout->seller_id }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ money($payout->amount) }}</td>
                        <td class="px-4 py-3 text-right text-ink-tertiary">{{ money($payout->charge) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ money($payout->net_amount) }}</td>
                        <td class="px-4 py-3">
                            @if ($payout->payoutMethod)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-info-tint text-feedback-info">
                                    <i data-lucide="credit-card" style="width:11px;height:11px;" class="me-1"></i>
                                    {{ $payout->payoutMethod->methodLabel() }}
                                </span>
                            @else
                                <span class="text-ink-tertiary text-xs">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white {{ $meta['pill'] }}">
                                <i data-lucide="{{ $meta['icon'] }}" style="width:11px;height:11px;" class="me-1"></i>
                                {{ $meta['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-ink-tertiary">
                            <span>{{ $payout->created_at->format('d M, Y') }}</span>
                            <small class="block text-[11px]">{{ $payout->created_at->format('h:i A') }}</small>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.payouts.show', $payout) }}" class="btn btn-light btn-sm" title="View details">
                                <i data-lucide="eye" class="icon-xs"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-sm text-ink-tertiary">
                            <i data-lucide="inbox" class="mx-auto mb-3 opacity-50" style="width:40px;height:40px;"></i>
                            <p class="mb-1 font-semibold text-ink">No payouts match your filters</p>
                            <small>Try clearing filters above or wait for new seller requests to arrive.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($payouts->hasPages())
        <div class="px-4 py-3 border-t border-border bg-surface-muted flex items-center justify-between">
            <small class="text-ink-tertiary">Showing {{ $payouts->firstItem() }}–{{ $payouts->lastItem() }} of {{ $payouts->total() }}</small>
            {{ $payouts->links() }}
        </div>
    @endif
</section>

@endsection
