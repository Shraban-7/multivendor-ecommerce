@php
    $pageTitle = "Sales Report | {$seller->business_name}";
    $rangeLabels = ['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'yearly' => 'This Year', 'custom' => 'Custom Range'];
    $rangeValue = $range ?? 'monthly';
    $rangeText = $rangeLabels[$rangeValue] ?? 'This Month';
    $bestSellingProduct = optional(optional($bestSelling)->product)->name ?? null;
    $bestSellingUnits = $bestSelling->total_qty ?? 0;
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .sales-dash__filter-input { background-color: #fff; border-color: #E5E5E5; color: #191919; }
    .sales-dash__filter-input:focus { border-color: #F85606; background-color: #fff; }
    .sales-dash__filter-input::placeholder { color: #767676; }
    .sales-dash__table-bar { position: relative; height: 6px; background: rgba(0,0,0,.06); border-radius: 999px; overflow: hidden; }
    .sales-dash__table-bar > span { position: absolute; left: 0; top: 0; bottom: 0; background: #16A34A; border-radius: 999px; }
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
                    <span class="text-ink font-medium">Sales Report</span>
                </nav>
                <h1 class="text-xl font-bold text-ink mb-1">Sales Report</h1>
                <p class="text-sm text-ink-secondary mb-0">Detailed channel mix, category performance and top products for <strong>{{ $rangeText }}</strong>.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.reports.overview') }}" class="btn btn-light btn-sm"><i data-lucide="layout-dashboard" style="width:14px;height:14px;"></i> Overview</a>
                <a href="{{ route('seller.reports.financial') }}" class="btn btn-light btn-sm"><i data-lucide="banknote" style="width:14px;height:14px;"></i> Financial</a>
                <a href="{{ route('seller.reports.customers') }}" class="btn btn-light btn-sm"><i data-lucide="users" style="width:14px;height:14px;"></i> Customers</a>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 text-sm items-center text-ink-secondary">
            <i data-lucide="calendar" style="width:14px;height:14px;"></i>
            <span class="mr-1 font-medium text-ink">Quick ranges:</span>
            @foreach ($rangeLabels as $key => $label)
                @if ($key === 'custom')
                    <form method="GET" action="{{ route('seller.reports.sales') }}" class="flex items-center gap-2 flex-wrap">
                        <input type="hidden" name="range" value="custom">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="sales-dash__filter-input px-2 py-1 text-sm border rounded-xs focus:outline-none transition-colors" placeholder="From">
                        <span class="text-ink-tertiary">to</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="sales-dash__filter-input px-2 py-1 text-sm border rounded-xs focus:outline-none transition-colors" placeholder="To">
                        <button type="submit" class="btn btn-primary btn-sm"><i data-lucide="funnel" style="width:12px;height:12px;"></i> Apply</button>
                    </form>
                @else
                    <a href="{{ route('seller.reports.sales', ['range' => $key]) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ $rangeValue == $key ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">{{ $label }}</a>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ KPI ROW — 6 METRICS ═══ --}}
@php
    $salesKpis = [
        ['label' => 'Total Revenue',     'value' => money($total_revenue),   'growth' => $revenue_growth,       'icon' => 'wallet',         'tone' => 'brand'],
        ['label' => 'Orders',            'value' => number_format($total_order),'growth' => $order_growth,    'icon' => 'shopping-bag',   'tone' => 'info'],
        ['label' => 'Avg Order Value',   'value' => money($avg_order),       'growth' => $avg_order_growth,   'icon' => 'receipt',        'tone' => 'warning'],
        ['label' => 'Refund Rate',       'value' => number_format($refund_rate,1).'%', 'growth' => $refundRateChange, 'icon' => 'undo-2', 'tone' => 'danger', 'growthSuffix' => 'pts', 'inverse' => true],
        ['label' => 'Best Seller',       'value' => $bestSellingProduct ? Str::limit($bestSellingProduct, 18) : '—', 'sub' => $bestSellingUnits > 0 ? $bestSellingUnits.' units' : 'No data', 'icon' => 'crown', 'tone' => 'success'],
        ['label' => 'Avg Growth',        'value' => number_format($revenue_growth, 1).'%', 'sub' => 'vs previous '.$rangeText, 'icon' => 'trending-up', 'tone' => 'rating'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
    @foreach ($salesKpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : (
                   $kpi['tone'] === 'warning' ? 'bg-amber-500' : (
                   $kpi['tone'] === 'danger' ? 'bg-red-500' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : 'bg-purple-500')))) }}"></div>
            <div class="flex items-start justify-between gap-3 mb-2 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-lg text-ink mt-1 truncate" title="{{ strip_tags($kpi['value']) }}">{{ $kpi['value'] }}</h3>
                </div>
                <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : (
                       $kpi['tone'] === 'warning' ? 'bg-amber-50 text-feedback-warning' : (
                       $kpi['tone'] === 'danger' ? 'bg-rose-50 text-rose-600' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : 'bg-purple-50 text-purple-600')))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:18px;height:18px;"></i>
                </span>
            </div>
            @if (!empty($kpi['growth']) || isset($kpi['growth']))
                @php
                    $g = $kpi['growth'] ?? 0;
                    $inverse = $kpi['inverse'] ?? false;
                    $isPositive = $inverse ? ($g < 0) : ($g > 0);
                    $isNegative = $inverse ? ($g > 0) : ($g < 0);
                    $suffix = $kpi['growthSuffix'] ?? '%';
                @endphp
                <div class="flex items-center gap-1.5 text-xs">
                    @if ($g != 0)
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold {{ $isPositive ? 'bg-emerald-50 text-feedback-success' : ($isNegative ? 'bg-rose-50 text-rose-600' : 'bg-surface-muted text-ink-tertiary') }}">
                            <i data-lucide="{{ $isPositive ? 'trending-up' : 'trending-down' }}" style="width:11px;height:11px;"></i> {{ number_format(abs($g), $suffix === '%' ? 1 : 2) }}{{ $suffix }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold bg-surface-muted text-ink-tertiary">
                            <i data-lucide="minus" style="width:11px;height:11px;"></i> 0{{ $suffix }}
                        </span>
                    @endif
                    <span class="text-ink-tertiary">vs previous {{ $rangeText }}</span>
                </div>
            @elseif (!empty($kpi['sub']))
                <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
            @endif
        </article>
    @endforeach
</section>

{{-- ═══ TREND CHART ═══ --}}
@php
    $revArr = is_array($revenues) ? array_map('floatval', $revenues) : [];
    $revMax = $revArr ? max($revArr) : 0;
    $revMin = $revArr ? min($revArr) : 0;
    $revTotal = array_sum($revArr);
    $revAvg = $revArr ? ($revTotal / count($revArr)) : 0;
    $peakIdx = $revArr ? array_search($revMax, $revArr, true) : null;
    $troughIdx = $revArr ? array_search($revMin, $revArr, true) : null;
    $peakLabel = ($peakIdx !== null && isset($labels[$peakIdx])) ? $labels[$peakIdx] : null;
    $troughLabel = ($troughIdx !== null && isset($labels[$troughIdx])) ? $labels[$troughIdx] : null;
@endphp
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="trending-up" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Revenue Trend — {{ $rangeText }}</h5>
        </div>
        <div class="flex items-center gap-3 text-xs text-ink-secondary">
            <span><strong class="text-ink">{{ count($labels ?? []) }}</strong> pts</span>
            <span>·</span>
            <span>Avg <strong class="text-ink">{{ money((float) $revAvg) }}</strong></span>
        </div>
    </div>
    @if (count($revArr) === 0)
        <div class="p-5 text-center text-sm text-ink-tertiary">
            <i data-lucide="bar-chart-3" class="mx-auto mb-2 opacity-50" style="width:32px;height:32px;"></i>
            <p class="mb-0">No revenue data for the selected period.</p>
        </div>
    @else
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="revenueTrendChart"></canvas>
            @if ($peakLabel && $revMax > 0)
                <div class="absolute top-4 right-6 bg-brand text-white text-xs font-semibold px-2 py-1 rounded shadow-sm" style="z-index: 5;">
                    <i data-lucide="arrow-up" style="width:11px;height:11px;"></i>
                    Peak: {{ $peakLabel }} · {{ money($revMax) }}
                </div>
            @endif
        </div>
        <div class="px-4 pb-4 grid grid-cols-1 md:grid-cols-3 gap-2 text-xs">
            <div class="p-2 bg-surface-muted rounded-sm border border-border">
                <small class="text-ink-tertiary uppercase tracking-wider">Period total</small>
                <p class="mb-0 font-bold text-base text-ink">{{ money($revTotal) }}</p>
            </div>
            <div class="p-2 bg-brand-tint rounded-sm border border-brand">
                <small class="text-brand uppercase tracking-wider font-semibold"><i data-lucide="trending-up" style="width:11px;height:11px;"></i> Best day</small>
                <p class="mb-0 font-bold text-base text-brand-deep">{{ $peakLabel ?? '—' }} · <span class="font-semibold">{{ $revMax > 0 ? money($revMax) : '—' }}</span></p>
            </div>
            <div class="p-2 bg-rose-50 rounded-sm border border-rose-200">
                <small class="text-rose-600 uppercase tracking-wider font-semibold"><i data-lucide="trending-down" style="width:11px;height:11px;"></i> Lowest day</small>
                <p class="mb-0 font-bold text-base text-rose-600">{{ $troughLabel ?? '—' }} · <span class="font-semibold">{{ $revMin > 0 ? money($revMin) : '—' }}</span></p>
            </div>
        </div>
        <div class="m-4 mt-0 p-3 bg-surface-muted rounded-sm border border-border flex items-start gap-3 text-sm">
            <i data-lucide="lightbulb" class="text-feedback-warning shrink-0" style="width:18px;height:18px;"></i>
            <span class="text-ink">Sales are
                <strong class="{{ ($revenue_growth ?? 0) >= 0 ? 'text-feedback-success' : 'text-rose-600' }}">
                    {{ ($revenue_growth ?? 0) >= 0 ? 'up' : 'down' }} {{ number_format(abs($revenue_growth ?? 0), 1) }}%
                </strong>
                vs. previous {{ $rangeText }}.
            </span>
        </div>
    @endif
</section>

{{-- ═══ CATEGORY × CHANNEL ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Category Mix</h5>
        </div>
        <div class="p-4 grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <canvas id="categoryPieChart"></canvas>
            </div>
            <div class="lg:col-span-3">
                <p class="text-xs text-ink-tertiary mb-2 uppercase font-semibold tracking-wider">Revenue & Order Breakdown</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <thead class="bg-surface-muted text-xs text-ink-tertiary uppercase tracking-wider">
                            <tr>
                                <th class="px-3 py-2">Category</th>
                                <th class="px-3 py-2 text-right">Sales</th>
                                <th class="px-3 py-2 text-right">Orders</th>
                                <th class="px-3 py-2 text-right">Growth</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse ($categoryData as $data)
                                <tr class="hover:bg-surface-muted/50 transition-colors">
                                    <td class="px-3 py-2.5 font-medium">{{ $data['category'] }}</td>
                                    <td class="px-3 py-2.5 text-right font-semibold">{{ money($data['sales']) }}</td>
                                    <td class="px-3 py-2.5 text-right">{{ $data['orders'] }}</td>
                                    <td class="px-3 py-2.5 text-right">
                                        @if ($data['growth'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-feedback-success">+{{ $data['growth'] }}%</span>
                                        @elseif ($data['growth'] < 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">{{ $data['growth'] }}%</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">0%</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-6 text-sm text-ink-tertiary">No category data in this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="split" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Sales Channel Contribution</h5>
        </div>
        <div class="p-4 space-y-3">
            @php $channelTotalRev = collect($channelData)->sum('revenue'); @endphp
            @foreach ($channelData as $data)
                <div class="border {{ $data['isTop'] ? 'border-brand' : 'border-border' }} rounded-sm p-4 {{ $data['isTop'] ? 'bg-brand-tint' : 'bg-surface-muted' }}">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center
                                {{ $data['isTop'] ? 'bg-brand text-white' : 'bg-blue-500 text-white' }}">
                                <i data-lucide="{{ $data['isTop'] ? 'crown' : 'shopping-cart' }}" style="width:18px;height:18px;"></i>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h6 class="mb-0 font-bold text-ink">{{ $data['channel'] }}</h6>
                                    @if ($data['isTop'])
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-brand text-white">Top Source</span>
                                    @endif
                                </div>
                                <small class="text-ink-tertiary">{{ number_format($data['orders']) }} orders</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <h5 class="mb-0 font-bold text-ink">{{ money($data['revenue']) }}</h5>
                            <small class="font-semibold text-brand">{{ number_format($data['contribution'], 1) }}%</small>
                        </div>
                    </div>
                    <div class="w-full h-2 bg-white rounded-full overflow-hidden border border-border">
                        <div class="h-full rounded-full {{ $data['isTop'] ? 'bg-brand' : 'bg-blue-500' }}" style="width: {{ min(100, $data['contribution']) }}%"></div>
                    </div>
                </div>
            @endforeach
            <div class="p-3 bg-surface-muted rounded-sm border border-border flex items-start gap-3 text-sm">
                <i data-lucide="info" class="text-feedback-info shrink-0" style="width:18px;height:18px;"></i>
                <span class="text-ink-secondary">A higher web share means more trusted traffic; POS drives immediate cash flow.</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══ TOP PRODUCTS ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="crown" class="text-feedback-warning" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Top-Selling Products by Revenue</h5>
        </div>
        <small class="text-ink-tertiary">{{ count($productStats ?? []) }} products</small>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5">#</th>
                    <th class="px-4 py-2.5">Product</th>
                    <th class="px-4 py-2.5 text-right">Price</th>
                    <th class="px-4 py-2.5 text-right">Units Sold</th>
                    <th class="px-4 py-2.5 text-right">Revenue</th>
                    <th class="px-4 py-2.5 text-right">Margin</th>
                    <th class="px-4 py-2.5">Relative Sales</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($productStats as $i => $prod)
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs
                                {{ $i === 0 ? 'bg-amber-100 text-feedback-warning' : ($i < 3 ? 'bg-brand-tint text-brand' : 'bg-surface-muted text-ink-tertiary') }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="mb-0 font-semibold text-ink">{{ Str::limit($prod['product_name'], 32) }}</p>
                        </td>
                        <td class="px-4 py-3 text-right text-ink-secondary">{{ money($prod['price']) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($prod['units_sold']) }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-feedback-success">{{ money($prod['total_sales']) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ($prod['profit_margin'] >= 30)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500 text-white">{{ $prod['profit_margin'] }}%</span>
                            @elseif ($prod['profit_margin'] >= 15)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-500 text-white">{{ $prod['profit_margin'] }}%</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500 text-white">{{ $prod['profit_margin'] }}%</span>
                            @endif
                        </td>
                        <td class="px-4 py-3" style="min-width: 160px;">
                            <div class="sales-dash__table-bar"><span style="width: {{ min(100, $prod['relative_sales']) }}%; background: linear-gradient(90deg, #16A34A, #22C55E);"></span></div>
                            <small class="text-xs text-ink-tertiary mt-1 block">{{ $prod['relative_sales'] }}% of top</small>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-6 text-sm text-ink-tertiary">No product sales in this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- ═══ REGION CHART ═══ --}}
@php
    $regionItems = [];
    if (!empty($divisionLabels) && !empty($divisionOrders)) {
        foreach ($divisionLabels as $i => $name) {
            $regionItems[] = [
                'name' => $name,
                'orders' => isset($divisionOrders[$i]) ? (int) $divisionOrders[$i] : 0,
            ];
        }
        usort($regionItems, fn ($a, $b) => $b['orders'] <=> $a['orders']);
    }
    $regionTotal = array_sum(array_column($regionItems, 'orders'));
    $regionCount = count($regionItems);
    $regionTop = $regionItems[0] ?? null;
    $regionBottom = !empty($regionItems) ? $regionItems[$regionCount - 1] : null;
@endphp
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="map-pin" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Sales by Region (Order Volume)</h5>
        </div>
        <div class="flex items-center gap-3 text-xs text-ink-secondary">
            <span><strong class="text-ink">{{ number_format($regionTotal) }}</strong> orders</span>
            <span>·</span>
            <span><strong class="text-ink">{{ $regionCount }}</strong> region{{ $regionCount == 1 ? '' : 's' }}</span>
        </div>
    </div>
    @if (empty($regionItems))
        <div class="p-5 text-center text-sm text-ink-tertiary">
            <i data-lucide="map-pin-off" class="mx-auto mb-2 opacity-50" style="width:32px;height:32px;"></i>
            <p class="mb-0">No region data in this period.</p>
        </div>
    @else
        <div class="p-4" style="height: 280px;">
            <canvas id="regionChart"></canvas>
        </div>
        <div class="px-4 pb-4 grid grid-cols-1 md:grid-cols-3 gap-2 text-xs">
            <div class="p-2 bg-surface-muted rounded-sm border border-border">
                <small class="text-ink-tertiary uppercase tracking-wider">Total orders</small>
                <p class="mb-0 font-bold text-base text-ink">{{ number_format($regionTotal) }}</p>
            </div>
            @if ($regionTop)
                <div class="p-2 bg-brand-tint rounded-sm border border-brand">
                    <small class="text-brand uppercase tracking-wider font-semibold"><i data-lucide="trophy" style="width:11px;height:11px;"></i> Top region</small>
                    <p class="mb-0 font-bold text-base text-brand-deep">{{ $regionTop['name'] }} · <span class="font-semibold">{{ number_format($regionTop['orders']) }}</span></p>
                </div>
            @endif
            @if ($regionBottom && $regionBottom['name'] !== ($regionTop['name'] ?? null))
                <div class="p-2 bg-rose-50 rounded-sm border border-rose-200">
                    <small class="text-rose-600 uppercase tracking-wider font-semibold"><i data-lucide="trending-down" style="width:11px;height:11px;"></i> Lowest region</small>
                    <p class="mb-0 font-bold text-base text-rose-600">{{ $regionBottom['name'] }} · <span class="font-semibold">{{ number_format($regionBottom['orders']) }}</span></p>
                </div>
            @endif
        </div>
        <div class="m-4 mt-0 p-3 bg-surface-muted rounded-sm border border-border flex items-start gap-3 text-sm">
            <i data-lucide="lightbulb" class="text-feedback-warning shrink-0" style="width:18px;height:18px;"></i>
            <span class="text-ink-secondary">Focus marketing investment on regions with the highest order volume.</span>
        </div>
    @endif
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    const brand = '#F85606';
    const success = '#16A34A';
    const info = '#0ea5e9';
    const palette = ['#F85606', '#16A34A', '#0ea5e9', '#F59E0B', '#EF4444', '#637381', '#fd7e14', '#20c997', '#6610f2', '#6f42c1'];

    const labels = @json($labels ?? []);
    const revenues = @json($revenues ?? []);

    const formatMoney = (v) => {
        const n = Number(v) || 0;
        if (Math.abs(n) >= 1000000) return (n / 1000000).toFixed(2).replace(/\.?0+$/, '') + 'M';
        if (Math.abs(n) >= 1000)    return (n / 1000).toFixed(1).replace(/\.?0+$/, '') + 'k';
        return n.toFixed(0);
    };
    const formatFullMoney = (v) => {
        const n = Number(v) || 0;
        return n.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    };

    if (document.getElementById('revenueTrendChart') && Array.isArray(labels) && labels.length > 0) {
        const ctx = document.getElementById('revenueTrendChart').getContext('2d');
        const chartHeight = 280;
        const gradient = ctx.createLinearGradient(0, 0, 0, chartHeight);
        gradient.addColorStop(0, 'rgba(248, 86, 6, 0.32)');
        gradient.addColorStop(1, 'rgba(248, 86, 6, 0.02)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Revenue',
                    data: revenues,
                    borderColor: brand,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5,
                    pointRadius: labels.length > 60 ? 0 : (labels.length > 30 ? 1 : 3),
                    pointHoverRadius: 7,
                    pointBackgroundColor: brand,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHitRadius: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 28, right: 12, left: 0, bottom: 4 } },
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
                            label: (ctx) => 'Revenue: ' + formatFullMoney(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,.05)', drawBorder: false },
                        ticks: {
                            font: { size: 10 },
                            maxTicksLimit: 6,
                            callback: (v) => formatMoney(v),
                            color: '#767676'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#767676',
                            autoSkip: true,
                            autoSkipPadding: 14,
                            maxRotation: labels.some(l => String(l).length > 7) ? 45 : 0,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    }

    const categoryLabels = @json($categoryData->pluck('category'));
    const categoryRevenue = @json($categoryData->pluck('sales'));
    if (document.getElementById('categoryPieChart') && categoryLabels.length > 0) {
        new Chart(document.getElementById('categoryPieChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryRevenue,
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } }
                }
            }
        });
    }

    const regionLabels = @json(array_column($regionItems ?? [], 'name'));
    const regionData = @json(array_column($regionItems ?? [], 'orders'));
    if (document.getElementById('regionChart') && Array.isArray(regionLabels) && regionLabels.length > 0) {
        const ctx = document.getElementById('regionChart').getContext('2d');
        const maxOrders = Math.max(1, ...regionData);
        const barColors = regionData.map(v => {
            const pct = v / maxOrders;
            if (pct >= 0.75) return 'rgba(248, 86, 6, 0.85)';
            if (pct >= 0.4)  return 'rgba(248, 86, 6, 0.65)';
            return 'rgba(248, 86, 6, 0.45)';
        });
        const borderColors = regionData.map(v => {
            const pct = v / maxOrders;
            if (pct >= 0.75) return '#F85606';
            if (pct >= 0.4)  return '#F85606';
            return '#F85606';
        });
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: regionLabels,
                datasets: [{
                    label: 'Orders',
                    data: regionData,
                    backgroundColor: barColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                    borderRadius: { topLeft: 4, topRight: 4 },
                    maxBarThickness: 36
                }]
            },
            options: {
                indexAxis: regionLabels.length > 6 ? 'y' : 'x',
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 8, right: 16, left: 4, bottom: 4 } },
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
                            label: (c) => 'Orders: ' + Number(c.parsed[regionLabels.length > 6 ? 'x' : 'y']).toLocaleString()
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,.05)', drawBorder: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#767676',
                            precision: 0,
                            callback: (v) => formatMoney(v).replace('k', 'K').replace('M', 'M'),
                            maxTicksLimit: 6
                        }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            color: '#191919',
                            autoSkip: false
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
