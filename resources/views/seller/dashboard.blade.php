@php
    $pageTitle = "Seller Dashboard | {$seller->business_name}";
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $periodLabel = \Carbon\Carbon::parse($start_date)->format('M j').' – '.\Carbon\Carbon::parse($end_date)->format('M j, Y');
    $marginPct = $total_sales > 0 ? round((($profit ?? 0) / $total_sales) * 100, 1) : 0;
    $avgTicket = $total_orders > 0 ? round($total_sales / $total_orders, 0) : 0;
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@if (!$seller->profile_completed)
<div class="mb-4 p-4 rounded-sm bg-amber-50 border border-amber-200 flex items-start gap-3" role="alert">
    <span class="shrink-0 w-9 h-9 rounded-full bg-amber-100 text-feedback-warning flex items-center justify-center">
        <i data-lucide="triangle-alert" style="width:18px;height:18px;"></i>
    </span>
    <div class="flex-1">
        <h5 class="mb-1 text-ink font-semibold">Your profile is incomplete</h5>
        <p class="mb-2 text-sm text-ink-secondary">Complete your profile to unlock full platform access and increase buyer trust.</p>
        <a href="{{ route('seller.profile') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs bg-feedback-warning text-white hover:bg-amber-700 transition-colors no-underline">Complete Profile →</a>
    </div>
</div>
@endif

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="gauge" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Dashboard</span>
                </nav>
                <span class="inline-block text-ink-tertiary text-sm">{{ $greeting }},</span>
                <h1 class="text-xl font-bold text-ink-emphasis mb-1">{{ $seller->business_name }}</h1>
                <p class="text-sm text-ink-secondary">Performance for <strong>{{ $periodLabel }}</strong></p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm"><i data-lucide="plus" style="width:14px;height:14px;"></i> Add Product</a>
                <a href="{{ route('seller.orders.pending') }}" class="btn btn-light btn-sm"><i data-lucide="inbox" style="width:14px;height:14px;"></i> Pending Orders</a>
                <a href="{{ route('seller.performance.dashboard') }}" class="btn btn-light btn-sm"><i data-lucide="gauge" style="width:14px;height:14px;"></i> Performance</a>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 text-sm text-ink-secondary items-center">
            <i data-lucide="calendar" style="width:14px;height:14px;"></i>
            <span>Custom range:</span>
            <form method="GET" action="{{ route('seller.dashboard') }}" class="flex items-center gap-2 flex-wrap">
                <input type="date" name="start_date" value="{{ $start_date }}" class="px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                <span class="text-ink-tertiary">to</span>
                <input type="date" name="end_date" value="{{ $end_date }}" class="px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                <button type="submit" class="btn btn-primary btn-sm"><i data-lucide="funnel" style="width:14px;height:14px;"></i> Apply</button>
            </form>
            <span class="text-ink-tertiary mx-1">·</span>
            <a href="{{ route('seller.dashboard', ['start_date' => now()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="px-2 py-0.5 rounded-xs bg-surface-muted text-ink-emphasis hover:bg-brand-tint hover:text-brand-deep transition-colors">Today</a>
            <a href="{{ route('seller.dashboard', ['start_date' => now()->copy()->startOfWeek()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="px-2 py-0.5 rounded-xs bg-surface-muted text-ink-emphasis hover:bg-brand-tint hover:text-brand-deep transition-colors">This Week</a>
            <a href="{{ route('seller.dashboard', ['start_date' => now()->copy()->startOfMonth()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="px-2 py-0.5 rounded-xs bg-surface-muted text-ink-emphasis hover:bg-brand-tint hover:text-brand-deep transition-colors">This Month</a>
            <a href="{{ route('seller.dashboard', ['start_date' => now()->copy()->subDays(30)->toDateString(), 'end_date' => now()->toDateString()]) }}" class="px-2 py-0.5 rounded-xs bg-surface-muted text-ink-emphasis hover:bg-brand-tint hover:text-brand-deep transition-colors">Last 30d</a>
        </div>
    </div>
</section>

{{-- ═══ ATTENTION ROW — 4 CARDS ═══ --}}
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <a href="{{ route('seller.orders.pending') }}" class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md border-l-4 no-underline" style="border-left-color: #f59e0b">
        <div class="flex items-center gap-3">
            <span class="shrink-0 w-10 h-10 rounded-sm bg-amber-50 flex items-center justify-center">
                <i data-lucide="clock" class="text-feedback-warning" style="width:20px;height:20px;"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Pending orders</p>
                <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($pending_orders) }}</h4>
                <small class="text-ink-tertiary">Act quickly on these</small>
            </div>
        </div>
    </a>
    <a href="{{ route('seller.returns.index') }}" class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md border-l-4 no-underline" style="border-left-color: #ef4444">
        <div class="flex items-center gap-3">
            <span class="shrink-0 w-10 h-10 rounded-sm bg-rose-50 flex items-center justify-center">
                <i data-lucide="rotate-ccw" class="text-rose-500" style="width:20px;height:20px;"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Open returns</p>
                <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($open_returns) }}</h4>
                <small class="text-ink-tertiary">Awaiting decision</small>
            </div>
        </div>
    </a>
    <a href="{{ route('seller.reviews.index') }}" class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md border-l-4 no-underline" style="border-left-color: #2563eb">
        <div class="flex items-center gap-3">
            <span class="shrink-0 w-10 h-10 rounded-sm bg-blue-50 flex items-center justify-center">
                <i data-lucide="message-square" class="text-feedback-info" style="width:20px;height:20px;"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Unreplied reviews</p>
                <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($unreplied_reviews) }}</h4>
                <small class="text-ink-tertiary">Engage buyers faster</small>
            </div>
        </div>
    </a>
    <a href="{{ route('seller.products.index') }}" class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md border-l-4 no-underline" style="border-left-color: #7c3aed">
        <div class="flex items-center gap-3">
            <span class="shrink-0 w-10 h-10 rounded-sm bg-purple-50 flex items-center justify-center">
                <i data-lucide="package-x" class="text-purple-600" style="width:20px;height:20px;"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Low stock items</p>
                <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($lowStockProducts->count()) }}</h4>
                <small class="text-ink-tertiary">Restock critical SKUs</small>
            </div>
        </div>
    </a>
</section>

{{-- ═══ KPI ROW — SALES / ORDERS / CUSTOMERS / PROFIT ═══ --}}
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <article class="bg-white border border-border rounded-sm shadow-sm p-5 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Total Sales</p>
                <h3 class="mb-0 font-bold text-2xl text-ink leading-tight">{{ money($total_sales) }}</h3>
            </div>
            <span class="shrink-0 w-12 h-12 rounded-sm bg-brand-tint flex items-center justify-center">
                <i data-lucide="wallet" class="text-brand" style="width:24px;height:24px;"></i>
            </span>
        </div>
        <div class="flex items-center justify-between text-xs">
            <span class="text-ink-tertiary">Avg ticket <strong class="text-ink">{{ money($avgTicket) }}</strong></span>
            <span class="text-ink-tertiary">{{ money($total_earnings) }} earned</span>
        </div>
    </article>

    <article class="bg-white border border-border rounded-sm shadow-sm p-5 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Orders</p>
                <h3 class="mb-0 font-bold text-2xl text-ink leading-tight">{{ number_format($total_orders) }}</h3>
            </div>
            <span class="shrink-0 w-12 h-12 rounded-sm bg-emerald-50 flex items-center justify-center">
                <i data-lucide="shopping-cart" class="text-feedback-success" style="width:24px;height:24px;"></i>
            </span>
        </div>
        <div class="flex items-center justify-between text-xs">
            <span class="text-ink-tertiary">Delivered <strong class="text-ink">{{ number_format($delivered_orders) }}</strong></span>
            <span class="text-ink-tertiary">Cancelled <strong class="text-ink">{{ number_format($cancelled_orders) }}</strong></span>
        </div>
    </article>

    <article class="bg-white border border-border rounded-sm shadow-sm p-5 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Profit</p>
                <h3 class="mb-0 font-bold text-2xl text-ink leading-tight">{{ money($profit) }}</h3>
            </div>
            <span class="shrink-0 w-12 h-12 rounded-sm bg-purple-50 flex items-center justify-center">
                <i data-lucide="trending-up" class="text-purple-600" style="width:24px;height:24px;"></i>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex-1 bg-surface-muted rounded-full h-2 overflow-hidden">
                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ min(100, max(0, $marginPct)) }}%"></div>
            </div>
            <span class="text-xs font-semibold text-purple-700">{{ $marginPct }}% margin</span>
        </div>
    </article>

    <article class="bg-white border border-border rounded-sm shadow-sm p-5 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Customers</p>
                <h3 class="mb-0 font-bold text-2xl text-ink leading-tight">{{ number_format($total_customers) }}</h3>
            </div>
            <span class="shrink-0 w-12 h-12 rounded-sm bg-blue-50 flex items-center justify-center">
                <i data-lucide="users" class="text-feedback-info" style="width:24px;height:24px;"></i>
            </span>
        </div>
        <div class="flex items-center justify-between text-xs">
            <span class="text-ink-tertiary">Rating <strong class="text-ink">{{ number_format((float) $avg_rating, 1) }}/5</strong></span>
            <span class="text-ink-tertiary">{{ $review_count }} reviews</span>
        </div>
    </article>
</section>

{{-- ═══ CHARTS ROW — SALES TREND + STATUS MIX ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Sales, Orders & Profit</h5>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#F85606"></span> Sales</span>
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#0ea5e9"></span> Orders</span>
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#16A34A"></span> Profit</span>
            </div>
        </div>
        <div class="p-4">
            <canvas id="salesOrderChart" height="120"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Order Status Mix</h5>
        </div>
        <div class="p-4">
            <canvas id="statusDonutChart" height="160"></canvas>
        </div>
    </div>
</section>

{{-- ═══ ORDER PIPELINE — FUNNEL ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="workflow" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Order Pipeline — conversion funnel</h5>
        </div>
        <a href="{{ route('seller.orders.index') }}" class="text-xs text-brand hover:text-brand-deep no-underline">All orders →</a>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-2">
            @foreach ([
                ['label' => 'Pending',     'value' => $pending_orders,    'icon' => 'clock',         'color' => '#d97706'],
                ['label' => 'Accepted',    'value' => $accepted_orders,   'icon' => 'check',         'color' => '#2563eb'],
                ['label' => 'Shipped',     'value' => $shipped_orders,    'icon' => 'truck',         'color' => '#0ea5e9'],
                ['label' => 'Delivered',   'value' => $delivered_orders,  'icon' => 'package-check', 'color' => '#059669'],
                ['label' => 'Cancelled',   'value' => $cancelled_orders,  'icon' => 'x-circle',     'color' => '#dc2626'],
                ['label' => 'Refunded',    'value' => $refunded_orders,   'icon' => 'undo-2',       'color' => '#7c3aed'],
            ] as $step)
                <div class="bg-surface-muted rounded-sm p-3 text-center">
                    <div class="w-10 h-10 rounded-full bg-white mx-auto mb-2 flex items-center justify-center" style="color: {{ $step['color'] }}">
                        <i data-lucide="{{ $step['icon'] }}" style="width:20px;height:20px;"></i>
                    </div>
                    <p class="mb-0 font-bold text-xl text-ink">{{ number_format($step['value']) }}</p>
                    <small class="text-ink-tertiary">{{ $step['label'] }}</small>
                </div>
            @endforeach
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-ink-secondary">Delivery rate</span>
                    <strong class="text-ink">{{ $delivery_rate }}%</strong>
                </div>
                <div class="w-full bg-surface-muted rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full" style="width: {{ min(100, $delivery_rate) }}%; background: #059669"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-ink-secondary">Cancel rate</span>
                    <strong class="text-ink">{{ $cancel_rate }}%</strong>
                </div>
                <div class="w-full bg-surface-muted rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full" style="width: {{ min(100, $cancel_rate) }}%; background: #dc2626"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FINANCE + CATALOG — 6 METRIC TILES ═══ --}}
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
    @php
        $metrics = [
            ['label' => 'Seller earnings',  'value' => money($total_earnings),   'icon' => 'banknote',     'tone' => 'success',  'sub' => 'After commission',         'href' => route('seller.payouts.index')],
            ['label' => 'Commission paid',  'value' => money($total_commission),'icon' => 'percent',     'tone' => 'primary',  'sub' => 'Platform fee'],
            ['label' => 'Pending payout',   'value' => money($pendingPayout),   'icon' => 'credit-card', 'tone' => 'warning',  'sub' => 'Awaiting transfer',       'href' => route('seller.payouts.index')],
            ['label' => 'Expenses',         'value' => money($total_expense),   'icon' => 'receipt',     'tone' => 'muted',    'sub' => 'Period spend',            'href' => route('seller.expenses.index')],
            ['label' => 'Stock value',      'value' => money($total_stock_value),'icon' => 'package',    'tone' => 'info',     'sub' => $active_products.' active of '.number_format($total_products).' products', 'href' => route('seller.products.index')],
            ['label' => 'Shop rating',      'value' => number_format((float) $avg_rating, 1).'/5','icon' => 'star','tone' => 'rating','sub' => $review_count.' reviews',     'href' => route('seller.reviews.index')],
        ];
    @endphp
    @foreach ($metrics as $metric)
        <a href="{{ $metric['href'] ?? '#' }}" class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md no-underline">
            <div class="flex items-center gap-3">
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center
                    {{ $metric['tone'] === 'success' ? 'bg-emerald-50' : (
                       $metric['tone'] === 'primary' ? 'bg-brand-tint' : (
                       $metric['tone'] === 'warning' ? 'bg-amber-50' : (
                       $metric['tone'] === 'info' ? 'bg-blue-50' : (
                       $metric['tone'] === 'rating' ? 'bg-purple-50' : 'bg-surface-muted')))) }}
                    {{ $metric['tone'] === 'success' ? 'text-feedback-success' : (
                       $metric['tone'] === 'primary' ? 'text-brand' : (
                       $metric['tone'] === 'warning' ? 'text-feedback-warning' : (
                       $metric['tone'] === 'info' ? 'text-feedback-info' : (
                       $metric['tone'] === 'rating' ? 'text-purple-600' : 'text-ink-tertiary')))) }}">
                    <i data-lucide="{{ $metric['icon'] }}" style="width:18px;height:18px;"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0">{{ $metric['label'] }}</p>
                    <strong class="text-ink font-bold text-sm block truncate">{{ $metric['value'] }}</strong>
                    <small class="text-ink-tertiary">{{ $metric['sub'] }}</small>
                </div>
            </div>
        </a>
    @endforeach
</section>

{{-- ═══ 3-COLUMN: TOP PRODUCTS · LOW STOCK · REVIEWS ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="crown" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Top Selling Products</h5>
            </div>
            <a href="{{ route('seller.products.index') }}" class="text-xs text-brand hover:text-brand-deep no-underline">Catalog →</a>
        </div>
        <div class="p-4">
            @forelse ($top_selling_products->take(6) as $index => $product)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs
                    {{ $index === 0 ? 'bg-amber-100 text-feedback-warning' : ($index < 3 ? 'bg-brand-tint text-brand' : 'bg-surface-muted text-ink-tertiary') }}">
                    {{ $index + 1 }}
                </span>
                <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}" class="w-9 h-9 rounded-sm object-cover border border-border shrink-0">
                <div class="min-w-0 flex-1">
                    <p class="mb-0 text-sm font-medium text-ink truncate">{{ $product->name }}</p>
                    <small class="text-ink-tertiary">{{ money($product->price) }}</small>
                </div>
                <span class="shrink-0 text-xs font-semibold px-2 py-0.5 bg-emerald-50 text-feedback-success rounded-full">{{ $product->sales_count }} sold</span>
            </div>
            @empty
            <div class="text-center py-6 text-sm text-ink-tertiary">
                <i data-lucide="shopping-bag" style="width:24px;height:24px;" class="mx-auto"></i>
                <p class="mt-2 mb-0">No sales in this period yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="package-x" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Low Stock Alerts</h5>
            </div>
            <a href="{{ route('seller.products.index') }}" class="text-xs text-brand hover:text-brand-deep no-underline">Inventory →</a>
        </div>
        <div class="p-4">
            @forelse ($lowStockProducts->take(6) as $product)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}" class="w-9 h-9 rounded-sm object-cover border border-border shrink-0">
                <div class="min-w-0 flex-1">
                    <p class="mb-0 text-sm font-medium text-ink truncate">{{ Str::limit($product->name, 30) }}</p>
                    <small class="text-ink-tertiary">Threshold: {{ $product->low_stock_quantity }}</small>
                </div>
                <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full
                    {{ $product->available_stock <= $product->low_stock_quantity / 2 ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-feedback-warning' }}">
                    {{ $product->available_stock }} left
                </span>
            </div>
            @empty
            <div class="text-center py-6 text-sm text-feedback-success">
                <i data-lucide="check-circle" style="width:24px;height:24px;" class="mx-auto"></i>
                <p class="mt-2 mb-0">All products are well stocked.</p>
            </div>
            @endforelse
            @if($lowStockProducts->count() > 0)
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-primary btn-sm w-full mt-3"><i data-lucide="package" style="width:14px;height:14px;"></i> Manage Inventory</a>
            @endif
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="star" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Latest Reviews</h5>
            </div>
            <a href="{{ route('seller.reviews.index') }}" class="text-xs text-brand hover:text-brand-deep no-underline">All reviews →</a>
        </div>
        <div class="p-4">
            @forelse ($recentReviews->take(4) as $review)
            <div class="py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                <div class="flex items-center justify-between mb-1">
                    <strong class="text-sm text-ink">{{ $review->user?->name ?? 'Customer' }}</strong>
                    <div class="flex items-center gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                            <i data-lucide="star" style="width:11px;height:11px;" class="{{ $i <= (int) $review->rating ? 'fill-current text-feedback-warning' : 'text-ink-tertiary' }}"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-sm text-ink-secondary mb-1">{{ Str::limit($review->description ?? 'No comment.', 80) }}</p>
                <small class="text-xs text-ink-tertiary">{{ optional($review->created_at)->diffForHumans() }} · {{ Str::limit($review->product?->name ?? 'Product', 26) }}</small>
            </div>
            @empty
            <div class="text-center py-6 text-sm text-ink-tertiary">
                <i data-lucide="star" style="width:24px;height:24px;" class="mx-auto"></i>
                <p class="mt-2 mb-0">No reviews yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══ QUICK ACTIONS ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="zap" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Quick Actions</h5>
        </div>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            @foreach ([
                ['label' => 'Add product',     'href' => route('seller.products.create'),     'icon' => 'plus-circle', 'tone' => 'brand'],
                ['label' => 'Pending orders', 'href' => route('seller.orders.pending'),    'icon' => 'inbox',       'tone' => 'warning'],
                ['label' => 'Inventory',      'href' => route('seller.products.index'),     'icon' => 'boxes',       'tone' => 'info'],
                ['label' => 'Coupons',        'href' => route('seller.coupons.index'),      'icon' => 'ticket-percent','tone' => 'success'],
                ['label' => 'Payout',         'href' => route('seller.payouts.create'),    'icon' => 'landmark',    'tone' => 'primary'],
                ['label' => 'Performance',    'href' => route('seller.performance.dashboard'),'icon' => 'gauge','tone' => 'rating'],
                ['label' => 'Support',        'href' => route('seller.support.index'),     'icon' => 'life-buoy',   'tone' => 'muted'],
                ['label' => 'Settings',       'href' => route('seller.settings.index'),     'icon' => 'settings',    'tone' => 'ink'],
            ] as $action)
                <a href="{{ $action['href'] }}" class="flex flex-col items-center gap-2 p-4 bg-surface-muted hover:bg-brand-tint rounded-sm transition-colors no-underline text-center border border-border hover:border-brand">
                    <span class="w-12 h-12 rounded-full flex items-center justify-center
                        {{ $action['tone'] === 'warning' ? 'bg-amber-100 text-feedback-warning' : (
                           $action['tone'] === 'success' ? 'bg-emerald-100 text-feedback-success' : (
                           $action['tone'] === 'info' ? 'bg-blue-100 text-feedback-info' : (
                           $action['tone'] === 'rating' ? 'bg-purple-100 text-purple-600' : (
                           $action['tone'] === 'primary' ? 'bg-brand-tint text-brand' : (
                           $action['tone'] === 'brand' ? 'bg-brand-tint text-brand' :
                           'bg-surface-muted text-ink-tertiary'))))) }}">
                        <i data-lucide="{{ $action['icon'] }}" style="width:24px;height:24px;"></i>
                    </span>
                    <span class="text-sm text-ink font-medium">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ LATEST ORDERS ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="receipt" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Latest Orders</h5>
        </div>
        <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-primary btn-sm">View all orders</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-4 py-2.5">Order ID</th>
                    <th scope="col" class="px-4 py-2.5">Customer</th>
                    <th scope="col" class="px-4 py-2.5 text-right">Total</th>
                    <th scope="col" class="px-4 py-2.5 text-center">Status</th>
                    <th scope="col" class="px-4 py-2.5">Date</th>
                    <th scope="col" class="px-4 py-2.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($latest_orders as $order)
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3 font-mono font-medium">{{ $order->invoice_id }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="shrink-0 w-7 h-7 rounded-full bg-surface-muted text-ink-tertiary flex items-center justify-center text-xs font-semibold uppercase">{{ substr($order->user?->name ?? 'N', 0, 1) }}</span>
                                <span>{{ $order->user?->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ money($order->total) }}</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $label = $order->status->label();
                                $pillBg = match (true) {
                                    in_array($label, ['completed','delivered'])  => 'bg-feedback-success/15 text-feedback-success',
                                    in_array($label, ['accepted'])                 => 'bg-surface-muted text-ink-emphasis',
                                    in_array($label, ['shipped'])                  => 'bg-[#a855f7]/15 text-[#a855f7]',
                                    in_array($label, ['pending','return_requested']) => 'bg-feedback-info/15 text-feedback-info',
                                    in_array($label, ['cancelled'])                => 'bg-feedback-danger/15 text-feedback-danger',
                                    in_array($label, ['return_approved'])          => 'bg-feedback-info/15 text-feedback-info',
                                    in_array($label, ['returned','refunded'])       => 'bg-surface-muted text-ink-secondary',
                                    default                                          => 'bg-surface-muted text-ink-tertiary',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ ucfirst($order->status->title()) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-ink-tertiary">{{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-outline-primary btn-sm">
                                <i data-lucide="eye" style="width:14px;height:14px;"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-6 text-ink-tertiary">No orders in this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@push('scripts')
<script>
    const chartData = @json($chartData);
    const statusData = @json($orderStatusDistribution);
    const brand = '#F85606';
    const success = '#16A34A';

    const statusLabels = {
        0: 'Pending', 1: 'Accepted', 2: 'Shipped', 3: 'Delivered',
        4: 'Completed', 5: 'Cancelled', 6: 'Return Requested',
        7: 'Return Approved', 8: 'Returned', 9: 'Refunded'
    };

    const statusColors = {
        0: '#F59E0B', 1: '#6366F1', 2: '#0ea5e9', 3: '#22C55E',
        4: '#16A34A', 5: '#EF4444', 6: '#F97316', 7: '#A855F7',
        8: '#EC4899', 9: '#94A3B8'
    };

    const ctx = document.getElementById('salesOrderChart').getContext('2d');
    const salesGradient = ctx.createLinearGradient(0, 0, 0, 160);
    salesGradient.addColorStop(0, 'rgba(248, 86, 6, 0.28)');
    salesGradient.addColorStop(1, 'rgba(248, 86, 6, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Sales',
                data: chartData.sales,
                borderColor: brand,
                backgroundColor: salesGradient,
                tension: 0.4,
                fill: true,
                borderWidth: 2.5,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointBackgroundColor: brand,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }, {
                label: 'Orders',
                data: chartData.orders,
                borderColor: '#0ea5e9',
                backgroundColor: 'transparent',
                tension: 0.4,
                fill: false,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 5
            }, {
                label: 'Profit',
                data: chartData.profits || chartData.sales,
                borderColor: success,
                backgroundColor: 'transparent',
                tension: 0.4,
                fill: false,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { usePointStyle: true, boxWidth: 6, padding: 12, font: { size: 11 } }
                }
            },
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,.04)', drawBorder: false },
                    ticks: { font: { size: 10 }, maxTicksLimit: 5 }
                },
                x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 8 } }
            }
        }
    });

    const statusCtx = document.getElementById('statusDonutChart').getContext('2d');
    const filteredStatuses = Object.entries(statusData).filter(([_, count]) => parseInt(count) > 0);
    if (filteredStatuses.length === 0) {
        statusCtx.canvas.parentElement.innerHTML += '<div class="text-center py-4 text-sm text-ink-tertiary">No order data yet.</div>';
    } else {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: filteredStatuses.map(([key]) => statusLabels[key] || 'Unknown'),
                datasets: [{
                    data: filteredStatuses.map(([_, count]) => count),
                    backgroundColor: filteredStatuses.map(([key]) => statusColors[key] || '#6B7280'),
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
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
                        labels: { boxWidth: 8, padding: 8, font: { size: 10 } }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection