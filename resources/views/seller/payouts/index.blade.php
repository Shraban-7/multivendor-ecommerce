@php
    $pageTitle = 'Payouts';

    $kpis = [
        ['label' => 'Available Balance', 'value' => money($availableBalance ?? 0), 'sub' => 'Ready to withdraw',        'icon' => 'dollar-sign',  'tone' => 'success'],
        ['label' => 'Pending Earnings',  'value' => money($pendingEarnings ?? 0),  'sub' => 'Awaiting clearance',      'icon' => 'trending-up',  'tone' => 'info'],
        ['label' => 'Pending Clearance', 'value' => money($pendingBalance ?? 0),  'sub' => 'Held by platform',        'icon' => 'clock',        'tone' => 'warning'],
        ['label' => 'Total Withdrawn',   'value' => money($totalWithdrawn ?? 0),  'sub' => 'Lifetime disbursed',      'icon' => 'check-circle', 'tone' => 'brand'],
    ];

    $toneBg = [
        'success' => 'bg-emerald-500',
        'info'    => 'bg-blue-500',
        'warning' => 'bg-amber-500',
        'danger'  => 'bg-rose-500',
        'brand'   => 'bg-brand',
        'muted'   => 'bg-gray-500',
    ];

    $toneIcon = [
        'success' => 'bg-emerald-50 text-feedback-success',
        'info'    => 'bg-info-tint text-feedback-info',
        'warning' => 'bg-warning-tint text-feedback-warning',
        'danger'  => 'bg-rose-50 text-rose-500',
        'brand'   => 'bg-brand-tint text-brand',
        'muted'   => 'bg-surface-muted text-ink-soft',
    ];

    $statusMap = \App\Domain\Vendor\Models\SellerPayout::statusMetas();
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="banknote" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Finance</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $pageTitle }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                        <i data-lucide="wallet" style="width:11px;height:11px;" class="me-1"></i> Finance
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Track your earnings, request withdrawals, and manage payout methods.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.payouts.methods.index') }}" class="btn btn-outline-primary btn-sm">
                    <i data-lucide="credit-card" class="icon-xs"></i> Payment Methods
                </a>
                <a href="{{ route('seller.payouts.create') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="plus" class="icon-xs"></i> Request Payout
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    @foreach ($kpis as $kpi)
        <article class="bg-white rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 {{ $toneBg[$kpi['tone']] ?? 'bg-gray-500' }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ $kpi['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center {{ $toneIcon[$kpi['tone']] ?? 'bg-surface-muted text-ink-soft' }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ PAYOUT LIST ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="history" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Payout History</h5>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Filter:</label>
            <select class="px-3 py-1.5 text-sm text-ink-soft bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors"
                    id="statusFilter"
                    onchange="window.location.href='{{ route('seller.payouts.index') }}?status='+this.value">
                <option value="">All Status</option>
                @foreach ($statusMap as $key => $meta)
                    <option value="{{ $key }}" {{ (string) ($statusFilter ?? '') === (string) $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-soft">
            <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 w-10">#</th>
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
                        <td class="px-4 py-3 text-right font-semibold text-ink-emphasis">{{ money($payout->amount) }}</td>
                        <td class="px-4 py-3 text-right text-ink-tertiary">−{{ money($payout->charge) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-feedback-success">{{ money($payout->net_amount) }}</td>
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
                        <td class="px-4 py-3 text-sm text-ink-secondary">
                            <span class="text-ink-soft">{{ $payout->created_at->format('d M, Y') }}</span>
                            <small class="block text-[11px] text-ink-tertiary">{{ $payout->created_at->format('h:i A') }}</small>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('seller.payouts.show', $payout) }}" class="btn btn-light btn-sm" title="View details">
                                <i data-lucide="eye" class="icon-xs"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-sm text-ink-tertiary">
                            <i data-lucide="inbox" class="mx-auto mb-3 opacity-50" style="width:40px;height:40px;"></i>
                            <p class="mb-1 font-semibold text-ink-emphasis">No payout requests yet</p>
                            <small class="block mb-3">Once you have a payout method added, you can withdraw your earnings here.</small>
                            <a href="{{ route('seller.payouts.create') }}" class="btn btn-primary btn-sm">
                                <i data-lucide="plus" class="icon-xs me-1"></i> Request Your First Payout
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($payouts->hasPages())
        <div class="px-5 py-3 bg-surface-muted flex items-center justify-between">
            <small class="text-ink-tertiary">Showing {{ $payouts->firstItem() }}–{{ $payouts->lastItem() }} of {{ $payouts->total() }}</small>
            {{ $payouts->links() }}
        </div>
    @endif
</section>

@endsection
