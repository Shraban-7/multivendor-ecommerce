@php
    $pageTitle = "Financial Report | {$seller->business_name}";
    $rangeLabels = ['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month', 'yearly' => 'This Year', 'custom' => 'Custom Range'];
    $rangeValue = $filter ?? 'monthly';
    $rangeText = $rangeLabels[$rangeValue] ?? 'This Month';

    $cm = $currentMetrics ?? [];
    $lm = $lastMetrics ?? [];
    $nm = $nextMetrics ?? [];
    $changes = $changes ?? [];

    $profitBar = max(0, min(100, (float)($cm['profit_margin'] ?? 0)));
    $prevProfitBar = max(0, min(100, (float)($lm['profit_margin'] ?? 0)));

    $totalIncome = (float) collect($incomeData ?? [])->sum('amount');
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .fin-dash__filter-input { background-color: #fff; border-color: #E5E5E5; color: #191919; }
    .fin-dash__filter-input:focus { border-color: #F85606; background-color: #fff; }
    .fin-dash__filter-input::placeholder { color: #767676; }
    .fin-dash__tile-color { width: 4px; height: 36px; border-radius: 2px; }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #16a34a, #22c55e, #86efac);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="banknote" class="text-feedback-success" style="width:12px;height:12px;"></i>
                    <span>Reports</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Financial</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Financial Report</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                        <i data-lucide="banknote" style="width:11px;height:11px;" class="me-1"></i> {{ $rangeText }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Profit & loss, expenses, inventory value & income sources.</p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.reports.overview') }}" class="btn btn-light btn-sm"><i data-lucide="layout-dashboard" style="width:14px;height:14px;"></i> Overview</a>
                <a href="{{ route('seller.reports.sales') }}" class="btn btn-light btn-sm"><i data-lucide="shopping-cart" style="width:14px;height:14px;"></i> Sales</a>
                <a href="{{ route('seller.reports.customers') }}" class="btn btn-light btn-sm"><i data-lucide="users" style="width:14px;height:14px;"></i> Customers</a>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 text-sm items-center text-ink-secondary">
            <i data-lucide="calendar" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
            <span class="mr-1 font-medium text-ink-emphasis">Quick ranges:</span>
            @foreach ($rangeLabels as $key => $label)
                @if ($key === 'custom')
                    <form method="GET" action="{{ route('seller.reports.financial') }}" class="flex items-center gap-2 flex-wrap">
                        <input type="hidden" name="range" value="custom">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-2 py-1 text-sm bg-surface-muted text-ink-emphasis rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="From">
                        <span class="text-ink-tertiary">to</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-2 py-1 text-sm bg-surface-muted text-ink-emphasis rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="To">
                        <button type="submit" class="btn btn-primary btn-sm"><i data-lucide="funnel" style="width:12px;height:12px;"></i> Apply</button>
                    </form>
                @else
                    <a href="{{ route('seller.reports.financial', ['range' => $key]) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ $rangeValue == $key ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">{{ $label }}</a>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ KPI ROW — 6 P&L TILES ═══ --}}
@php
    $finKpis = [
        ['label' => 'Revenue',     'value' => money($cm['total_revenue'] ?? 0),  'sub' => 'Gross sales',       'icon' => 'wallet',         'tone' => 'brand',   'growth' => $changes['revenue'] ?? null, 'inverse' => false],
        ['label' => 'Gross Profit','value' => money($cm['gross_profit'] ?? 0),  'sub' => 'Revenue − cost',    'icon' => 'trending-up',    'tone' => 'success', 'growth' => $changes['gross_profit'] ?? null, 'inverse' => false],
        ['label' => 'Total Expenses','value' => money($cm['total_expense'] ?? 0),'sub' => 'Operating costs',  'icon' => 'receipt',        'tone' => 'warning', 'growth' => $changes['expense'] ?? null, 'inverse' => true],
        ['label' => 'Net Profit',  'value' => money($cm['net_profit'] ?? 0),    'sub' => 'Bottom line',       'icon' => 'banknote',       'tone' => 'rating',  'growth' => $changes['net_profit'] ?? null, 'inverse' => false],
        ['label' => 'Profit Margin','value' => number_format($cm['profit_margin'] ?? 0, 1).'%', 'sub' => 'Net / Revenue',  'icon' => 'percent',        'tone' => 'info',    'growth' => $changes['profit_margin'] ?? null, 'inverse' => false, 'useDiff' => true],
        ['label' => 'Inventory Value','value' => money($inventory_value ?? 0), 'sub' => 'Cost × stock',       'icon' => 'package',        'tone' => 'muted',   'growth' => null, 'inverse' => false],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
    @foreach ($finKpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : (
                   $kpi['tone'] === 'warning' ? 'bg-amber-500' : (
                   $kpi['tone'] === 'rating' ? 'bg-purple-500' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : 'bg-gray-500')))) }}"></div>
            <div class="flex items-start justify-between gap-3 mb-2 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-lg text-ink mt-1">{{ $kpi['value'] }}</h3>
                </div>
                <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                       $kpi['tone'] === 'warning' ? 'bg-amber-50 text-feedback-warning' : (
                       $kpi['tone'] === 'rating' ? 'bg-purple-50 text-purple-600' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : 'bg-surface-muted text-ink-tertiary')))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:18px;height:18px;"></i>
                </span>
            </div>
            <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
            @if (($kpi['growth'] ?? null) !== null)
                @php
                    $g = (float) $kpi['growth'];
                    $inverse = $kpi['inverse'] ?? false;
                    $useDiff = $kpi['useDiff'] ?? false;
                    $isPositive = $useDiff ? ($g >= 0) : ($inverse ? ($g < 0) : ($g > 0));
                    $isNegative = $useDiff ? ($g < 0) : ($inverse ? ($g > 0) : ($g < 0));
                @endphp
                <div class="mt-1 text-xs">
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full font-semibold {{ $isPositive ? 'bg-emerald-50 text-feedback-success' : ($isNegative ? 'bg-rose-50 text-rose-600' : 'bg-surface-muted text-ink-tertiary') }}">
                        <i data-lucide="{{ $isPositive ? 'trending-up' : 'trending-down' }}" style="width:11px;height:11px;"></i>
                        {{ $useDiff ? number_format($g, 1).' pts' : number_format(abs($g), 1).'%' }}
                    </span>
                </div>
            @endif
        </article>
    @endforeach
</section>

{{-- ═══ CHART ROW — REV/PROFIT TREND + EXPENSE TREND ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-2 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Revenue & Profit Trend</h5>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#F85606"></span> Revenue</span>
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#16A34A"></span> Gross</span>
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#7c3aed"></span> Net</span>
            </div>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Expense Trend</h5>
            </div>
            <small class="text-ink-tertiary">Total this period: {{ money($totalExpense ?? 0) }}</small>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="expenseChart"></canvas>
        </div>
    </div>
</section>

{{-- ═══ INCOME × EXPENSE BREAKDOWN ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="arrow-down-circle" class="text-feedback-success" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Income Sources</h5>
        </div>
        <div class="p-4 space-y-3">
            @forelse ($incomeData ?? [] as $src)
                <div class="border border-border rounded-sm p-4 bg-surface-muted">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center
                                {{ $src['source'] === 'Product Sales' ? 'bg-brand text-white' : 'bg-feedback-info text-white' }}">
                                <i data-lucide="{{ $src['source'] === 'Product Sales' ? 'shopping-bag' : 'store' }}" style="width:18px;height:18px;"></i>
                            </span>
                            <div>
                                <h6 class="mb-0 font-bold text-ink">{{ $src['source'] }}</h6>
                                <small class="text-ink-tertiary">{{ number_format((float)$src['percentage'], 1) }}% of total income</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <h5 class="mb-0 font-bold text-feedback-success">{{ money($src['amount']) }}</h5>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $src['source'] === 'Product Sales' ? 'bg-brand text-white' : 'bg-feedback-info text-white' }}">{{ $src['status'] }}</span>
                        </div>
                    </div>
                    <div class="w-full h-2 bg-white rounded-full overflow-hidden border border-border">
                        <div class="h-full rounded-full {{ $src['source'] === 'Product Sales' ? 'bg-brand' : 'bg-feedback-info' }}" style="width: {{ min(100, (float)$src['percentage']) }}%"></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-sm text-ink-tertiary">No income recorded for this period.</div>
            @endforelse
            <div class="p-3 bg-emerald-50 rounded-sm border border-emerald-200 border-l-4 border-l-emerald-500">
                <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Total Income</p>
                <h4 class="mb-0 font-bold text-xl text-feedback-success">{{ money($totalIncome) }}</h4>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="arrow-up-circle" class="text-rose-500" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Expense Breakdown</h5>
        </div>
        <div class="p-4 space-y-3">
            <div class="grid grid-cols-2 gap-3 mb-2">
                <div class="p-3 bg-rose-50 rounded-sm border border-rose-200 border-l-4 border-l-rose-500">
                    <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Total Expenses</p>
                    <h4 class="mb-0 font-bold text-xl text-rose-600">{{ money($totalExpense ?? 0) }}</h4>
                </div>
                <div class="p-3 bg-amber-50 rounded-sm border border-amber-200 border-l-4 border-l-amber-500">
                    <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Growth vs Last</p>
                    <h4 class="mb-0 font-bold text-xl
                        {{ ($expenseGrowth ?? 0) <= 0 ? 'text-feedback-success' : 'text-feedback-warning' }}">
                        {{ number_format((float)($expenseGrowth ?? 0), 1) }}%
                    </h4>
                </div>
            </div>
            @php
                $expenseTotal = (float) collect($expenseCategories ?? [])->sum('total');
            @endphp
            @forelse ($expenseCategories ?? [] as $cat)
                <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center
                        @if (isset($highestExpense) && $highestExpense->seller_expense_category_id == $cat->seller_expense_category_id)
                            bg-rose-500 text-white
                        @else
                            bg-surface-muted text-ink-tertiary
                        @endif">
                        <i data-lucide="{{ isset($highestExpense) && $highestExpense->seller_expense_category_id == $cat->seller_expense_category_id ? 'flame' : 'tag' }}" style="width:18px;height:18px;"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="mb-0 font-medium text-ink">{{ $cat->category?->name ?? 'Uncategorized' }}
                            @if (isset($highestExpense) && $highestExpense->seller_expense_category_id == $cat->seller_expense_category_id)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500 text-white ms-1">Highest</span>
                            @endif
                        </p>
                        <div class="w-full h-1.5 bg-surface-muted rounded-full overflow-hidden mt-1">
                            <div class="h-full rounded-full bg-rose-500" style="width: {{ $expenseTotal > 0 ? min(100, ($cat->total / $expenseTotal) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="mb-0 font-bold text-ink">{{ money((float) $cat->total) }}</p>
                        <small class="text-ink-tertiary">{{ $expenseTotal > 0 ? number_format(($cat->total / $expenseTotal) * 100, 1) : 0 }}%</small>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-sm text-ink-tertiary">
                    <i data-lucide="circle-check" class="mx-auto mb-2 text-feedback-success" style="width:32px;height:32px;"></i>
                    <p class="mb-2">No expenses recorded for this period.</p>
                    <a href="{{ route('seller.expenses.index') }}" class="btn btn-light btn-sm">Manage expenses</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══ INVENTORY VALUE BREAKDOWN + LOW TURNOVER ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="package" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Inventory Value</h5>
        </div>
        <div class="p-4">
            <p class="text-xs text-ink-tertiary mb-1 uppercase font-semibold tracking-wider">Total Locked Capital</p>
            <h3 class="mb-3 font-bold text-2xl text-ink">{{ money($inventory_value ?? 0) }}</h3>
            <div class="mt-3 p-3 bg-amber-50 rounded-sm border border-amber-200 border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-sm text-ink-secondary font-semibold">Low-turnover SKUs</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white">{{ number_format($lowTurnoverCount ?? 0) }}</span>
                </div>
                <small class="text-ink-tertiary mt-1 block">Not sold in the past {{ $lowTurnoverDays ?? 90 }} days.</small>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Inventory Value by Category</h5>
            </div>
            <small class="text-ink-tertiary">Total: {{ money($totalStockValue ?? 0) }}</small>
        </div>
        <div class="p-4 space-y-2">
            @php
                $invTotal = (float) collect($inventoryByCategory ?? [])->sum('stock_value');
            @endphp
            @forelse ($inventoryByCategory ?? [] as $row)
                <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <span class="shrink-0 w-9 h-9 rounded-sm bg-brand-tint text-brand flex items-center justify-center">
                        <i data-lucide="folder" style="width:18px;height:18px;"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="mb-0 font-medium text-ink">{{ $row->category?->name ?? 'Uncategorized' }}</p>
                        <div class="w-full h-1.5 bg-surface-muted rounded-full overflow-hidden mt-1">
                            <div class="h-full rounded-full bg-brand" style="width: {{ $invTotal > 0 ? min(100, ($row->stock_value / $invTotal) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="mb-0 font-bold text-ink">{{ money((float) $row->stock_value) }}</p>
                        <small class="text-ink-tertiary">{{ $row->sku_count }} SKUs</small>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-sm text-ink-tertiary">No inventory value data available.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══ PERIOD COMPARISON ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
        <i data-lucide="scale" class="text-brand" style="width:16px;height:16px;"></i>
        <h5 class="mb-0 font-bold text-ink">Period Comparison</h5>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5">Metric</th>
                    <th class="px-4 py-2.5 text-right">Previous</th>
                    <th class="px-4 py-2.5 text-right">Current · {{ $rangeText }}</th>
                    <th class="px-4 py-2.5">Change</th>
                    <th class="px-4 py-2.5 text-right">Upcoming</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @foreach ([
                    ['label' => 'Revenue',         'prev' => $lm['total_revenue'] ?? 0, 'curr' => $cm['total_revenue'] ?? 0, 'next' => $nm['total_revenue'] ?? 0],
                    ['label' => 'Total Cost',      'prev' => $lm['total_product_cost'] ?? 0, 'curr' => $cm['total_product_cost'] ?? 0, 'next' => $nm['total_product_cost'] ?? 0],
                    ['label' => 'Gross Profit',    'prev' => $lm['gross_profit'] ?? 0, 'curr' => $cm['gross_profit'] ?? 0, 'next' => $nm['gross_profit'] ?? 0],
                    ['label' => 'Operating Expense','prev' => $lm['total_expense'] ?? 0, 'curr' => $cm['total_expense'] ?? 0, 'next' => $nm['total_expense'] ?? 0],
                    ['label' => 'Net Profit',      'prev' => $lm['net_profit'] ?? 0, 'curr' => $cm['net_profit'] ?? 0, 'next' => $nm['net_profit'] ?? 0],
                ] as $row)
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3 font-medium text-ink">{{ $row['label'] }}</td>
                        <td class="px-4 py-3 text-right text-ink-secondary">{{ money((float) $row['prev']) }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-brand-tint text-brand">{{ money((float) $row['curr']) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $diff = (float) $row['curr'] - (float) $row['prev'];
                                $pct = ((float) $row['prev'] != 0) ? ($diff / (float) $row['prev']) * 100 : 0;
                            @endphp
                            @if ($pct > 0)
                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-feedback-success">
                                    <i data-lucide="trending-up" style="width:11px;height:11px;"></i> +{{ number_format($pct, 1) }}%
                                </span>
                            @elseif ($pct < 0)
                                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">
                                    <i data-lucide="trending-down" style="width:11px;height:11px;"></i> {{ number_format($pct, 1) }}%
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">Flat</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-ink-secondary">{{ $row['next'] !== null ? money((float) $row['next']) : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    const brand = '#F85606';
    const success = '#16A34A';
    const rating = '#7c3aed';
    const danger = '#EF4444';

    const trendLabels = @json(($trendData ?? collect())->pluck('label'));
    const trendRevenue = @json(($trendData ?? collect())->pluck('total_revenue')->map(fn ($v) => (float) $v));
    const trendGross = @json(($trendData ?? collect())->pluck('gross_profit')->map(fn ($v) => (float) $v));
    const trendNet = @json(($trendData ?? collect())->pluck('net_profit')->map(fn ($v) => (float) $v));

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

    if (document.getElementById('trendChart') && Array.isArray(trendLabels) && trendLabels.length > 0) {
        const ctx = document.getElementById('trendChart').getContext('2d');
        const chartHeight = 280;
        const gradient = ctx.createLinearGradient(0, 0, 0, chartHeight);
        gradient.addColorStop(0, 'rgba(248,86,6,0.32)');
        gradient.addColorStop(1, 'rgba(248,86,6,0.02)');
        const longXLabels = trendLabels.some(l => String(l).length > 8);
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [
                    {
                        label: 'Revenue', data: trendRevenue,
                        borderColor: brand, backgroundColor: gradient, tension: 0.35, fill: true,
                        borderWidth: 2.5,
                        pointRadius: trendLabels.length > 60 ? 0 : (trendLabels.length > 30 ? 1 : 2),
                        pointHoverRadius: 6,
                        pointBackgroundColor: brand,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5,
                        pointHitRadius: 10
                    },
                    {
                        label: 'Gross Profit', data: trendGross,
                        borderColor: success, backgroundColor: 'transparent', tension: 0.35, fill: false,
                        borderWidth: 2, pointRadius: 0, pointHoverRadius: 6
                    },
                    {
                        label: 'Net Profit', data: trendNet,
                        borderColor: rating, backgroundColor: 'transparent', tension: 0.35, fill: false,
                        borderWidth: 2, borderDash: [4, 3], pointRadius: 0, pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                layout: { padding: { top: 12, right: 16, left: 4, bottom: 4 } },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff', bodyColor: '#fff',
                        padding: 10, cornerRadius: 6, displayColors: false,
                        callbacks: {
                            label: (c) => c.dataset.label + ': ' + formatFullMoney(c.parsed.y)
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
                            maxRotation: longXLabels ? 45 : 0,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    }

    const expLabels = @json(($expenseTrend ?? collect())->pluck('label'));
    const expAmounts = @json(($expenseTrend ?? collect())->pluck('amount')->map(fn ($v) => (float) $v));

    if (document.getElementById('expenseChart') && Array.isArray(expLabels) && expLabels.length > 0) {
        const ctx = document.getElementById('expenseChart').getContext('2d');
        const max = Math.max(1, ...expAmounts);
        const longXLabels = expLabels.some(l => String(l).length > 8);
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: expLabels,
                datasets: [{
                    label: 'Expenses',
                    data: expAmounts,
                    backgroundColor: expAmounts.map(v => v > 0 ? 'rgba(239,68,68,0.75)' : 'rgba(22,163,74,0.55)'),
                    borderColor: expAmounts.map(v => v > 0 ? '#EF4444' : '#16A34A'),
                    borderWidth: 1,
                    borderRadius: { topLeft: 4, topRight: 4 },
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                layout: { padding: { top: 12, right: 16, left: 4, bottom: 4 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff', bodyColor: '#fff',
                        padding: 10, cornerRadius: 6, displayColors: false,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (c) => 'Expense: ' + formatFullMoney(c.parsed.y)
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
                            maxRotation: longXLabels ? 45 : 0,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
