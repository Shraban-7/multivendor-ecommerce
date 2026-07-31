@php
    $pageTitle = "Customer Report | {$seller->business_name}";
    $filterLabels = ['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'yearly' => 'This Year'];
    $fValue = $filter ?? null;
    $fText = $filterLabels[$fValue] ?? 'All Time';

    $totalChart = $chartData['total'] ?? ['labels' => [], 'data' => []];
    $nrChart = $chartData['new_vs_returning'] ?? ['labels' => [], 'new' => [], 'returning' => []];

    $prevAvgOrders = $avgOrdersPerCustomerCurrent - (float) $avgOrdersPerCustomerChange;
    $clvProgress = min(100, max(0, (float) $avgClvCurrent > 0 ? 100 : 0));
    $retentionTier = (float) $returningPercentage;
    $retentionLabel = $retentionTier >= 60 ? 'Excellent' : ($retentionTier >= 40 ? 'Healthy' : ($retentionTier >= 20 ? 'Building' : 'Low'));
    $retentionColor = $retentionTier >= 60 ? 'success' : ($retentionTier >= 40 ? 'info' : ($retentionTier >= 20 ? 'warning' : 'danger'));
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .cust-dash__table-bar { position: relative; height: 6px; background: rgba(0,0,0,.06); border-radius: 999px; overflow: hidden; }
    .cust-dash__table-bar > span { position: absolute; left: 0; top: 0; bottom: 0; background: #16A34A; border-radius: 999px; }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="users" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Reports</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Customers</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Customer Report</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="users" style="width:11px;height:11px;" class="me-1"></i> Customer Insights
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Customer lifetime value, retention, segments and growth over the last <strong>12 months</strong>.</p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.reports.overview') }}" class="btn btn-light btn-sm"><i data-lucide="layout-dashboard" style="width:14px;height:14px;"></i> Overview</a>
                <a href="{{ route('seller.reports.sales') }}" class="btn btn-light btn-sm"><i data-lucide="shopping-cart" style="width:14px;height:14px;"></i> Sales</a>
                <a href="{{ route('seller.reports.financial') }}" class="btn btn-light btn-sm"><i data-lucide="banknote" style="width:14px;height:14px;"></i> Financial</a>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 text-sm items-center text-ink-secondary">
            <i data-lucide="calendar" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
            <span class="mr-1 font-medium text-ink-emphasis">Period focus:</span>
            <a href="{{ route('seller.reports.customers') }}" class="px-2 py-0.5 rounded-xs transition-colors {{ empty($fValue) ? 'bg-brand-tint text-brand-deep font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">All Time</a>
            @foreach ($filterLabels as $key => $label)
                <a href="{{ route('seller.reports.customers', ['filter' => $key]) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ $fValue == $key ? 'bg-brand-tint text-brand-deep font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ KPI ROW — 6 METRICS ═══ --}}
@php
    $custKpis = [
        ['label' => 'All-Time Customers', 'value' => number_format($allTimeTotalCustomers ?? 0), 'sub' => 'Unique buyers',        'icon' => 'users-round',     'tone' => 'brand'],
        ['label' => 'New Customers',      'value' => number_format($newCustomersCurrent ?? 0), 'sub' => 'Acquired this period', 'icon' => 'user-plus',       'tone' => 'info',  'growth' => $newCustomersChange ?? null],
        ['label' => 'Avg Orders / Customer','value' => number_format((float) ($avgOrdersPerCustomerCurrent ?? 0), 2), 'sub' => 'Frequency', 'icon' => 'repeat', 'tone' => 'success','growth' => $avgOrdersPerCustomerChange ?? null],
        ['label' => 'Avg CLV',            'value' => money($avgClvCurrent ?? 0),                'sub' => 'Per-order value',       'icon' => 'banknote',        'tone' => 'rating','growth' => $avgClvChange ?? null],
        ['label' => 'Returning %',        'value' => number_format((float) ($returningPercentage ?? 0), 1).'%', 'sub' => 'Share returning order', 'icon' => 'refresh-ccw', 'tone' => 'success'],
        ['label' => 'Retention Tier',     'value' => $retentionLabel,                          'sub' => 'Based on '.(float) ($returningPercentage ?? 0).'% returning', 'icon' => 'shield-check', 'tone' => $retentionColor],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
    @foreach ($custKpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : (
                   $kpi['tone'] === 'rating' ? 'bg-purple-500' : (
                   $kpi['tone'] === 'warning' ? 'bg-amber-500' : 'bg-rose-500')))) }}"></div>
            <div class="flex items-start justify-between gap-3 mb-2 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-lg text-ink mt-1">{{ $kpi['value'] }}</h3>
                </div>
                <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                       $kpi['tone'] === 'rating' ? 'bg-purple-50 text-purple-600' : (
                       $kpi['tone'] === 'warning' ? 'bg-amber-50 text-feedback-warning' : 'bg-rose-50 text-rose-600')))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:18px;height:18px;"></i>
                </span>
            </div>
            <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
            @if (!empty($kpi['growth']))
                @php $g = (float) $kpi['growth']; @endphp
                <div class="mt-1 text-xs">
                    @if ($g > 0)
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-feedback-success">
                            <i data-lucide="trending-up" style="width:11px;height:11px;"></i> +{{ number_format(abs($g), 1) }}%
                        </span>
                    @elseif ($g < 0)
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-600">
                            <i data-lucide="trending-down" style="width:11px;height:11px;"></i> {{ number_format($g, 1) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full font-semibold bg-surface-muted text-ink-tertiary">Flat</span>
                    @endif
                </div>
            @endif
        </article>
    @endforeach
</section>

{{-- ═══ CHARTS ROW — 12-MONTH GROWTH + NEW VS RETURNING ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="user-plus" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Customer Growth — last 12 months</h5>
            </div>
            <small class="text-ink-tertiary">Total unique customers per month</small>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">New vs Returning Trend</h5>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="newReturningChart"></canvas>
        </div>
    </div>
</section>

{{-- ═══ RETENTION SEGMENT CARDS ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    @php
        $n = (float) ($newCustomersCurrent ?? 0);
        $r = (float) max(0, (float) ($allTimeTotalCustomers ?? 0) - $n);
        $nPct = ((float) ($allTimeTotalCustomers ?? 0)) > 0 ? ($n / (float) $allTimeTotalCustomers) * 100 : 0;
        $rPct = 100 - $nPct;
        $segments = [
            ['label' => 'New', 'value' => $n, 'pct' => $nPct, 'icon' => 'user-plus', 'color' => '#0ea5e9', 'desc' => 'First-time buyers in this period'],
            ['label' => 'Returning', 'value' => $r, 'pct' => $rPct, 'icon' => 'refresh-ccw', 'color' => '#16A34A', 'desc' => 'Have ordered before'],
        ];
    @endphp
    @foreach ($segments as $seg)
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background: {{ $seg['color'] }};"></div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-3">
                    <span class="shrink-0 w-12 h-12 rounded-sm flex items-center justify-center text-white" style="background: {{ $seg['color'] }}">
                        <i data-lucide="{{ $seg['icon'] }}" style="width:24px;height:24px;"></i>
                    </span>
                    <div>
                        <p class="text-xs text-ink-tertiary mb-0 uppercase font-semibold tracking-wider">{{ $seg['label'] }} Customers</p>
                        <h3 class="mb-0 font-bold text-2xl text-ink">{{ number_format($seg['value']) }}</h3>
                    </div>
                </div>
                <div class="mb-2 flex items-center justify-between">
                    <small class="text-ink-tertiary">{{ $seg['desc'] }}</small>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold text-white" style="background: {{ $seg['color'] }}">{{ number_format($seg['pct'], 1) }}%</span>
                </div>
                <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden">
                    <div class="h-full rounded-full" style="width: {{ min(100, $seg['pct']) }}%; background: {{ $seg['color'] }};"></div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="gem" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">CLV Snapshot</h5>
            </div>
        </div>
        <div class="p-5 space-y-3">
            <div class="text-center">
                <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Average Customer Lifetime Value</p>
                <h3 class="display-6 mb-0 font-bold text-ink">{{ money($avgClvCurrent ?? 0) }}</h3>
                @if (!empty($avgClvChange))
                    @php $cs = (float) $avgClvChange; @endphp
                    <div class="mt-2">
                        @if ($cs > 0)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-feedback-success">
                                <i data-lucide="trending-up" style="width:11px;height:11px;"></i> +{{ number_format(abs($cs), 1) }}% vs previous
                            </span>
                        @elseif ($cs < 0)
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">
                                <i data-lucide="trending-down" style="width:11px;height:11px;"></i> {{ number_format($cs, 1) }}% vs previous
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">Flat vs previous</span>
                        @endif
                    </div>
                @endif
            </div>
            <div class="border-t border-border pt-3">
                <div class="flex justify-between text-sm">
                    <span class="text-ink-secondary">Orders per customer</span>
                    <strong class="text-ink">{{ number_format((float) ($avgOrdersPerCustomerCurrent ?? 0), 2) }}</strong>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-ink-secondary">Returning share</span>
                    <strong class="text-ink">{{ number_format((float) ($returningPercentage ?? 0), 1) }}%</strong>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-ink-secondary">Healthy retention?</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                        {{ $retentionTier >= 40 ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' }}">{{ $retentionTier >= 40 ? 'Yes' : 'Improve' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ TOP CUSTOMERS TABLE ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="crown" class="text-feedback-warning" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Top 5 Spenders — {{ $fText }}</h5>
        </div>
        <small class="text-ink-tertiary">By total spend</small>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 w-10">#</th>
                    <th class="px-4 py-2.5">Customer</th>
                    <th class="px-4 py-2.5 text-right">Orders</th>
                    <th class="px-4 py-2.5 text-right">Total Spent</th>
                    <th class="px-4 py-2.5 text-right">Avg Order</th>
                    <th class="px-4 py-2.5">Tier</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($topCustomers as $i => $cust)
                    @php
                        $avg = $cust['orders'] > 0 ? $cust['spent'] / $cust['orders'] : 0;
                        $tierName = $cust['spent'] >= 50000 ? 'Platinum' : ($cust['spent'] >= 15000 ? 'Gold' : ($cust['spent'] >= 5000 ? 'Silver' : 'Bronze'));
                        $tierPill = match($tierName) {
                            'Platinum' => 'bg-purple-500 text-white',
                            'Gold' => 'bg-amber-500 text-white',
                            'Silver' => 'bg-gray-500 text-white',
                            default => 'bg-orange-700 text-white'
                        };
                    @endphp
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs
                                {{ $i === 0 ? 'bg-amber-100 text-feedback-warning' : ($i < 3 ? 'bg-brand-tint text-brand' : 'bg-surface-muted text-ink-tertiary') }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="shrink-0 w-8 h-8 rounded-full bg-brand-tint text-brand flex items-center justify-center font-bold text-xs uppercase">{{ mb_substr($cust['name'], 0, 1) }}</span>
                                <div>
                                    <p class="mb-0 font-medium text-ink">{{ Str::limit($cust['name'], 30) }}</p>
                                    <small class="text-ink-tertiary">{{ $cust['orders'] }} order{{ $cust['orders'] == 1 ? '' : 's' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($cust['orders']) }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500 text-white">{{ money($cust['spent']) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">{{ money($avg) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $tierPill }}">{{ $tierName }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-6 text-sm text-ink-tertiary">No customer spending in this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- ═══ INSIGHTS ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="lightbulb" class="text-feedback-warning" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Insights & Recommendations</h5>
        </div>
    </div>
    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        @php
            $newChangeVal = (float) ($newCustomersChange ?? 0);
            $returningVal = (float) ($returningPercentage ?? 0);
            $clvChangeVal = (float) ($avgClvChange ?? 0);
        @endphp
        <div class="p-4 bg-{{ ($newChangeVal >= 0) ? 'emerald' : 'rose' }}-50 rounded-sm border border-{{ ($newChangeVal >= 0) ? 'emerald' : 'rose' }}-200 border-l-4 border-l-{{ ($newChangeVal >= 0) ? 'emerald-500' : 'rose-500' }}">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="{{ $newChangeVal >= 0 ? 'trending-up' : 'trending-down' }}" class="text-{{ ($newChangeVal >= 0) ? 'feedback-success' : 'rose-600' }}" style="width:18px;height:18px;"></i>
                <strong class="text-ink">{{ $newChangeVal >= 0 ? 'Acquisition is growing' : 'Acquisition is slowing' }}</strong>
            </div>
            <p class="text-sm text-ink-secondary mb-0">{{ $newChangeVal >= 0 ? 'New customer acquisition is up '.number_format(abs($newChangeVal),1).'%. Consider reinvesting in marketing.' : 'New customer acquisition dropped '.number_format(abs($newChangeVal),1).'%. Run a promotion or ad campaign.' }}</p>
        </div>
        <div class="p-4 bg-{{ ($returningVal >= 40) ? 'emerald' : ($returningVal >= 20 ? 'amber' : 'rose') }}-50 rounded-sm border border-{{ ($returningVal >= 40) ? 'emerald' : ($returningVal >= 20 ? 'amber' : 'rose') }}-200 border-l-4 border-l-{{ ($returningVal >= 40) ? 'emerald-500' : ($returningVal >= 20 ? 'amber-500' : 'rose-500') }}">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="refresh-ccw" class="text-{{ ($returningVal >= 40) ? 'feedback-success' : ($returningVal >= 20 ? 'feedback-warning' : 'rose-600') }}" style="width:18px;height:18px;"></i>
                <strong class="text-ink">Retention is {{ $retentionLabel }}</strong>
            </div>
            <p class="text-sm text-ink-secondary mb-0">{{ number_format($returningVal, 1) }}% of your buyers returned. {{ $returningVal >= 40 ? 'Keep nurturing loyalty with coupons and rewards.' : ($returningVal >= 20 ? 'Try retention emails and a loyalty programme.' : 'Focus on post-purchase follow-ups and review requests.') }}</p>
        </div>
        <div class="p-4 bg-{{ ($clvChangeVal >= 0) ? 'emerald' : 'rose' }}-50 rounded-sm border border-{{ ($clvChangeVal >= 0) ? 'emerald' : 'rose' }}-200 border-l-4 border-l-{{ ($clvChangeVal >= 0) ? 'emerald-500' : 'rose-500' }}">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="gem" class="text-{{ ($clvChangeVal >= 0) ? 'feedback-success' : 'rose-600' }}" style="width:18px;height:18px;"></i>
                <strong class="text-ink">CLV is {{ $clvChangeVal >= 0 ? 'rising' : 'falling' }}</strong>
            </div>
            <p class="text-sm text-ink-secondary mb-0">Average customer value {{ $clvChangeVal >= 0 ? 'increased' : 'decreased' }} by {{ number_format(abs($clvChangeVal),1) }}%. {{ $clvChangeVal >= 0 ? 'Upsell and bundle strategies are working.' : 'Consider product mix review and bundling.' }}</p>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    const brand = '#F85606';
    const success = '#16A34A';
    const info = '#0ea5e9';

    const formatCount = (v) => {
        const n = Number(v) || 0;
        if (Math.abs(n) >= 1000000) return (n / 1000000).toFixed(2).replace(/\.?0+$/, '') + 'M';
        if (Math.abs(n) >= 1000)    return (n / 1000).toFixed(1).replace(/\.?0+$/, '') + 'k';
        return n.toFixed(0);
    };

    const growthLabels = @json($totalChart['labels'] ?? []);
    const growthValues = @json(array_map(fn ($v) => (float) $v, $totalChart['data'] ?? []));

    if (document.getElementById('growthChart') && Array.isArray(growthLabels) && growthLabels.length > 0) {
        const ctx = document.getElementById('growthChart').getContext('2d');
        const chartHeight = 280;
        const gradient = ctx.createLinearGradient(0, 0, 0, chartHeight);
        gradient.addColorStop(0, 'rgba(248,86,6,0.32)');
        gradient.addColorStop(1, 'rgba(248,86,6,0.02)');
        const pointR = growthLabels.length > 60 ? 0 : (growthLabels.length > 30 ? 1 : 3);
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: growthLabels,
                datasets: [{
                    label: 'Customers',
                    data: growthValues,
                    borderColor: brand,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: pointR,
                    pointHoverRadius: 7,
                    pointBackgroundColor: brand,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHitRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 12, right: 16, left: 4, bottom: 4 } },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (c) => Number(c.raw).toLocaleString() + ' customers'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,.05)', drawBorder: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#767676',
                            precision: 0,
                            maxTicksLimit: 6,
                            callback: (v) => formatCount(v)
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#767676',
                            autoSkip: true,
                            autoSkipPadding: 14,
                            maxRotation: growthLabels.some(l => String(l).length > 8) ? 45 : 0,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    }

    const nrLabels = @json($nrChart['labels'] ?? []);
    const nrNew = @json(array_map(fn ($v) => (float) $v, $nrChart['new'] ?? []));
    const nrReturning = @json(array_map(fn ($v) => (float) $v, $nrChart['returning'] ?? []));

    if (document.getElementById('newReturningChart') && Array.isArray(nrLabels) && nrLabels.length > 0) {
        const ctx = document.getElementById('newReturningChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nrLabels,
                datasets: [
                    {
                        label: 'New',
                        data: nrNew,
                        backgroundColor: 'rgba(14,165,233,0.75)',
                        borderColor: '#0ea5e9',
                        borderWidth: 1,
                        borderRadius: { topLeft: 4, topRight: 4 },
                        stack: 's',
                        maxBarThickness: 28
                    },
                    {
                        label: 'Returning',
                        data: nrReturning,
                        backgroundColor: 'rgba(22,163,74,0.85)',
                        borderColor: '#16A34A',
                        borderWidth: 1,
                        borderRadius: { topLeft: 4, topRight: 4 },
                        stack: 's',
                        maxBarThickness: 28
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 12, right: 16, left: 4, bottom: 4 } },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 11 }, padding: 10, usePointStyle: true }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff', bodyColor: '#fff',
                        padding: 10, cornerRadius: 6,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (c) => c.dataset.label + ': ' + Number(c.raw).toLocaleString()
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#767676',
                            autoSkip: true,
                            autoSkipPadding: 14,
                            maxRotation: nrLabels.some(l => String(l).length > 8) ? 45 : 0,
                            minRotation: 0
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,.05)', drawBorder: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#767676',
                            precision: 0,
                            maxTicksLimit: 6,
                            callback: (v) => formatCount(v)
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
