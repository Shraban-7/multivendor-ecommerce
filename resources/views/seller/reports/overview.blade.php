@php
    $pageTitle = "Reports Overview | {$seller->business_name}";
    $rangeLabels = ['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'yearly' => 'This Year', 'custom' => 'Custom', null => 'All Time'];
    $rangeValue = $filter ?? null;
    $rangeText = $rangeLabels[$rangeValue] ?? 'All Time';
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .reports-dash__kpi-bar { height: 4px; }
    .reports-dash__chart-legend > span { display:inline-flex; align-items:center; gap:.25rem; }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-5 lg:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="folder" style="width:12px;height:12px;"></i>
                    <span>Reports</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink font-medium">Business Overview</span>
                </nav>
                <h1 class="text-xl font-bold text-ink mb-1">Business Overview</h1>
                <p class="text-sm text-ink-secondary mb-0">High-level snapshot of your store's performance for <strong>{{ $rangeText }}</strong>.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.reports.sales') }}" class="btn btn-light btn-sm"><i data-lucide="shopping-cart" style="width:14px;height:14px;"></i> Sales Report</a>
                <a href="{{ route('seller.reports.financial') }}" class="btn btn-light btn-sm"><i data-lucide="banknote" style="width:14px;height:14px;"></i> Financial</a>
                <a href="{{ route('seller.reports.customers') }}" class="btn btn-light btn-sm"><i data-lucide="users" style="width:14px;height:14px;"></i> Customers</a>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 text-sm items-center text-ink-secondary">
            <i data-lucide="calendar" style="width:14px;height:14px;"></i>
            <span class="mr-1 font-medium text-ink">Quick ranges:</span>
            <a href="{{ route('seller.reports.overview', ['range' => 'daily']) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ request('range') == 'daily' ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">Today</a>
            <a href="{{ route('seller.reports.overview', ['range' => 'weekly']) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ request('range') == 'weekly' ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">This Week</a>
            <a href="{{ route('seller.reports.overview', ['range' => 'monthly']) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ request('range') == 'monthly' ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">This Month</a>
            <a href="{{ route('seller.reports.overview', ['range' => 'yearly']) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ request('range') == 'yearly' ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">This Year</a>
            <a href="{{ route('seller.reports.overview') }}" class="px-2 py-0.5 rounded-xs transition-colors {{ !request('range') ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">All Time</a>
            <span class="text-ink-tertiary mx-1">|</span>
            <form method="GET" action="{{ route('seller.reports.overview') }}" class="flex items-center gap-2 flex-wrap">
                <input type="hidden" name="range" value="custom">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-2 py-1 text-sm bg-white text-ink border border-border rounded-xs focus:outline-none focus:border-brand transition-colors">
                <span class="text-ink-tertiary">to</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-2 py-1 text-sm bg-white text-ink border border-border rounded-xs focus:outline-none focus:border-brand transition-colors">
                <button type="submit" class="btn btn-primary btn-sm"><i data-lucide="funnel" style="width:12px;height:12px;"></i> Apply</button>
            </form>
        </div>
    </div>
</section>

{{-- ═══ HERO KPI ROW ═══ --}}
@php
    $profitMargin = ($calculateMetrics['total_sales'] ?? 0) > 0 ? round((($calculateMetrics['net_profit'] ?? 0) / $calculateMetrics['total_sales']) * 100, 1) : 0;
    $kpis = [
        ['label' => 'Total Sales',  'value' => money($calculateMetrics['total_sales'] ?? 0),  'growth' => $calculateMetrics['sales_growth'] ?? 0, 'icon' => 'wallet',      'tone' => 'brand'],
        ['label' => 'Total Orders', 'value' => number_format($calculateMetrics['total_orders'] ?? 0), 'growth' => $calculateMetrics['orders_growth'] ?? 0, 'icon' => 'shopping-bag', 'tone' => 'success'],
        ['label' => 'Avg Order',    'value' => money($calculateMetrics['aov'] ?? 0),        'growth' => $calculateMetrics['aov_growth'] ?? 0,    'icon' => 'receipt',      'tone' => 'info'],
        ['label' => 'Net Profit',   'value' => money($calculateMetrics['net_profit'] ?? 0), 'growth' => $calculateMetrics['profit_growth'] ?? 0, 'icon' => 'trending-up',  'tone' => 'rating'],
        ['label' => 'Stock Units',  'value' => number_format($calculateMetrics['total_stock'] ?? 0), 'growth' => $calculateMetrics['stock_growth'] ?? 0, 'icon' => 'package',  'tone' => 'warning'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
    @foreach ($kpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-5 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="reports-dash__kpi-bar absolute top-0 left-0 right-0
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : (
                   $kpi['tone'] === 'rating' ? 'bg-purple-500' : 'bg-amber-500'))) }}"></div>
            <div class="flex items-start justify-between gap-3 mb-2 mt-1">
                <div class="min-w-0">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-xl text-ink mt-1 truncate">{{ $kpi['value'] }}</h3>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : (
                       $kpi['tone'] === 'rating' ? 'bg-purple-50 text-purple-600' : 'bg-amber-50 text-feedback-warning'))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
            <div class="flex items-center gap-1.5 text-xs">
                @if (($kpi['growth'] ?? 0) > 0)
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold bg-emerald-50 text-feedback-success">
                        <i data-lucide="trending-up" style="width:11px;height:11px;"></i> {{ number_format(abs($kpi['growth']), 1) }}%
                    </span>
                @elseif (($kpi['growth'] ?? 0) < 0)
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-600">
                        <i data-lucide="trending-down" style="width:11px;height:11px;"></i> {{ number_format(abs($kpi['growth']), 1) }}%
                    </span>
                @else
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold bg-surface-muted text-ink-tertiary">
                        <i data-lucide="minus" style="width:11px;height:11px;"></i> 0.0%
                    </span>
                @endif
                <span class="text-ink-tertiary">vs previous period</span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ CHARTS ROW — TREND + ORDERS/RETURNS ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Revenue Trend</h5>
            </div>
            <div class="reports-dash__chart-legend flex items-center gap-3 text-xs">
                <span class="text-ink-tertiary">{{ ucfirst($rangeText) }} breakdown</span>
            </div>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="revenueTrendChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="scale" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Orders vs Returns</h5>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="ordersReturnsChart"></canvas>
        </div>
    </div>
</section>

{{-- ═══ QUICK FACTS + TOP PRODUCTS ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    {{-- Quick Facts --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="zap" class="text-feedback-warning" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Quick Facts</h5>
        </div>
        <div class="p-4 space-y-3">
            <div class="p-3 bg-brand-tint rounded-sm border-l-4 border-brand">
                <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Total Orders</p>
                <p class="mb-0 font-bold text-xl text-ink">{{ number_format($quickFacts['total_orders'] ?? 0) }}</p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-sm border-l-4 border-emerald-500">
                <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Returning Customers</p>
                <p class="mb-0 font-bold text-xl text-feedback-success">{{ number_format($quickFacts['returning_customers_percent'] ?? 0, 1) }}%</p>
                <small class="text-ink-tertiary">Of all orders placed this period.</small>
            </div>
            <div class="p-3 bg-rose-50 rounded-sm border-l-4 border-rose-500">
                <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Refund Rate</p>
                <p class="mb-0 font-bold text-xl text-rose-600">{{ number_format($quickFacts['refund_rate'] ?? 0, 1) }}%</p>
                <small class="text-ink-tertiary">Aim for under 3% for a healthy store.</small>
            </div>
            <div class="p-3 bg-amber-50 rounded-sm border-l-4 border-amber-500">
                <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Best Sales Day</p>
                <p class="mb-0 font-bold text-base text-ink">{{ $quickFacts['best_sales_day'] ?? '—' }}</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-sm border-l-4 border-purple-500">
                <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Profit Margin</p>
                <p class="mb-0 font-bold text-xl text-purple-600">{{ $profitMargin }}%</p>
                <small class="text-ink-tertiary">Net profit ÷ total sales.</small>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="lg:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="crown" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Top 5 Selling Products</h5>
            </div>
            <small class="text-ink-tertiary">By units sold</small>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5 w-10">#</th>
                        <th class="px-4 py-2.5">Product</th>
                        <th class="px-4 py-2.5 text-right">Units Sold</th>
                        <th class="px-4 py-2.5 text-right">Sales</th>
                        <th class="px-4 py-2.5 text-right">Stock</th>
                        <th class="px-4 py-2.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($topProducts as $index => $item)
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs
                                    {{ $index === 0 ? 'bg-amber-100 text-feedback-warning' : ($index < 3 ? 'bg-brand-tint text-brand' : 'bg-surface-muted text-ink-tertiary') }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-ink">{{ Str::limit($item['name'], 35) }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ number_format($item['units_sold']) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-feedback-success">{{ money($item['sales']) }}</td>
                            <td class="px-4 py-3 text-right">
                                @if ($item['stock'] <= 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white">Out</span>
                                @elseif ($item['stock'] <= 10)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500 text-white">{{ $item['stock'] }} left</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500 text-white">{{ $item['stock'] }} left</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($item['stock'] <= 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">Restock</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-feedback-success">In stock</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-6 text-sm text-ink-tertiary">No delivered orders in this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ═══ CARDS — EXPLORE MORE REPORTS ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Explore Other Reports</h5>
        </div>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <a href="{{ route('seller.reports.sales') }}" class="flex items-start gap-3 p-4 bg-surface-muted hover:bg-brand-tint rounded-sm transition-colors no-underline border border-border hover:border-brand group">
                <span class="shrink-0 w-12 h-12 rounded-sm bg-brand-tint flex items-center justify-center text-brand">
                    <i data-lucide="bar-chart-3" style="width:24px;height:24px;"></i>
                </span>
                <div class="min-w-0">
                    <h6 class="mb-1 font-bold text-ink group-hover:text-brand-deep">Sales Report</h6>
                    <p class="text-xs text-ink-tertiary mb-2">Channel mix, category breakdown, regional split, product performance.</p>
                    <span class="text-xs text-brand font-semibold">Open →</span>
                </div>
            </a>
            <a href="{{ route('seller.reports.financial') }}" class="flex items-start gap-3 p-4 bg-surface-muted hover:bg-brand-tint rounded-sm transition-colors no-underline border border-border hover:border-brand group">
                <span class="shrink-0 w-12 h-12 rounded-sm bg-purple-50 flex items-center justify-center text-purple-600">
                    <i data-lucide="banknote" style="width:24px;height:24px;"></i>
                </span>
                <div class="min-w-0">
                    <h6 class="mb-1 font-bold text-ink group-hover:text-brand-deep">Financial Report</h6>
                    <p class="text-xs text-ink-tertiary mb-2">Profit & loss, expense breakdown, income sources, inventory valuation.</p>
                    <span class="text-xs text-brand font-semibold">Open →</span>
                </div>
            </a>
            <a href="{{ route('seller.reports.customers') }}" class="flex items-start gap-3 p-4 bg-surface-muted hover:bg-brand-tint rounded-sm transition-colors no-underline border border-border hover:border-brand group">
                <span class="shrink-0 w-12 h-12 rounded-sm bg-blue-50 flex items-center justify-center text-feedback-info">
                    <i data-lucide="users" style="width:24px;height:24px;"></i>
                </span>
                <div class="min-w-0">
                    <h6 class="mb-1 font-bold text-ink group-hover:text-brand-deep">Customer Report</h6>
                    <p class="text-xs text-ink-tertiary mb-2">CLV, RFM segments, new vs returning growth, top customers.</p>
                    <span class="text-xs text-brand font-semibold">Open →</span>
                </div>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const chartData = @json($chartData);
    const brand = '#F85606';
    const danger = '#EF4444';
    const success = '#16A34A';

    const formatMoney = (v) => {
        const n = Number(v) || 0;
        if (Math.abs(n) >= 1000000) return (n / 1000000).toFixed(2).replace(/\.?0+$/, '') + 'M';
        if (Math.abs(n) >= 1000)    return (n / 1000).toFixed(1).replace(/\.?0+$/, '') + 'k';
        return n.toFixed(0);
    };

    const trendLabels = chartData?.revenueTrend?.labels ?? [];
    const trendValues = (chartData?.revenueTrend?.values ?? []).map(v => Number(v));

    if (trendLabels.length > 0 && document.getElementById('revenueTrendChart')) {
        const ctx = document.getElementById('revenueTrendChart').getContext('2d');
        const chartHeight = 280;
        const gradient = ctx.createLinearGradient(0, 0, 0, chartHeight);
        gradient.addColorStop(0, 'rgba(248, 86, 6, 0.32)');
        gradient.addColorStop(1, 'rgba(248, 86, 6, 0.02)');
        const pointR = trendLabels.length > 60 ? 0 : (trendLabels.length > 30 ? 1 : 3);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Revenue',
                    data: trendValues,
                    borderColor: brand,
                    backgroundColor: gradient,
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2.5,
                    pointRadius: pointR,
                    pointHoverRadius: 6,
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
                        titleColor: '#fff', bodyColor: '#fff',
                        padding: 10, cornerRadius: 6, displayColors: false,
                        callbacks: {
                            label: (c) => 'Revenue: ' + Number(c.raw).toLocaleString()
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
                            maxTicksLimit: 6,
                            callback: (v) => formatMoney(v)
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#767676',
                            autoSkip: true,
                            autoSkipPadding: 14,
                            maxRotation: trendLabels.some(l => String(l).length > 8) ? 45 : 0,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    }

    const ordersCount = Number(chartData?.ordersReturns?.orders ?? 0);
    const returnsCount = Number(chartData?.ordersReturns?.returns ?? 0);

    if (document.getElementById('ordersReturnsChart')) {
        const ctx = document.getElementById('ordersReturnsChart').getContext('2d');
        if (ordersCount === 0 && returnsCount === 0) {
            ctx.canvas.parentElement.innerHTML += '<div class="text-center py-4 text-sm text-ink-tertiary">No completed or returned orders yet.</div>';
        } else {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Delivered', 'Returned'],
                    datasets: [{
                        data: [ordersCount, returnsCount],
                        backgroundColor: [success, danger],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 8, padding: 8, font: { size: 11 } }
                        }
                    }
                }
            });
        }
    }
</script>
@endpush
@endsection
