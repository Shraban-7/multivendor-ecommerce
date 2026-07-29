@php
    $pageTitle = 'Admin Dashboard | Marketplace';
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $todayRevenue = (float) ($today->revenue ?? 0);
    $todayOrders = (int) ($today->cnt ?? 0);
    $grossSales = (float) ($stats['total_revenue'] ?? 0);
    $commissionRate = $grossSales > 0 ? round((($data['total_commission'] ?? 0) / $grossSales) * 100, 1) : 0;
@endphp
@extends('admin.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .admin-dash__kpi-bar { height: 4px; }
    .admin-dash__heat-bar { position: relative; height: 32px; background: rgba(0,0,0,.06); border-radius: 4px; overflow: hidden; }
    .admin-dash__heat-bar > span { display: flex; align-items: center; padding: 0 .5rem; height: 100%; background: linear-gradient(90deg, #F85606, #f97316); color: #fff; font-size: 11px; font-weight: 600; }
</style>
@endpush

{{-- ═══ PENDING VENDORS ALERT ═══ --}}
@if ($pending_sellers_count)
<section class="mb-4 p-4 lg:p-5 rounded-sm bg-amber-50 border border-amber-200 flex items-start gap-4 shadow-sm">
    <span class="shrink-0 w-11 h-11 rounded-full bg-amber-100 text-feedback-warning flex items-center justify-center">
        <i data-lucide="triangle-alert" style="width:20px;height:20px;"></i>
    </span>
    <div class="flex-1 min-w-0">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h5 class="mb-0 font-bold text-ink">Pending Vendor Applications</h5>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-feedback-warning text-white">{{ $pending_sellers_count }}</span>
        </div>
        <p class="mb-3 text-sm text-ink-secondary">
            You have <strong class="text-feedback-warning">{{ $pending_sellers_count }}</strong> new vendor{{ $pending_sellers_count !== 1 ? 's' : '' }} waiting for approval.
            @if($pending_sellers->isNotEmpty())
                <span class="text-ink-tertiary">Latest: <strong class="text-ink">{{ $pending_sellers->first()->name }}</strong>@if($pending_sellers_count > 1) · {{ $pending_sellers_count - 1 }} more @endif</span>
            @endif
        </p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.sellers.pending') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs bg-feedback-warning text-white hover:bg-amber-700 transition-colors no-underline">
                <i data-lucide="eye" style="width:14px;height:14px;" class="me-1"></i> Review Applications
            </a>
            <button type="button" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs bg-white border border-border text-ink-secondary hover:bg-surface-muted transition-colors" data-bs-dismiss="alert">
                <i data-lucide="x" style="width:14px;height:14px;" class="me-1"></i> Dismiss
            </button>
        </div>
    </div>
</section>
@endif

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-5 lg:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <small class="text-ink-tertiary">{{ $greeting }},</small>
                <h1 class="text-xl font-bold text-ink mb-1">Marketplace Overview</h1>
                <p class="text-sm text-ink-secondary mb-0">Live snapshot of revenue, vendors, and customer activity for <strong>{{ now()->format('l, F j, Y') }}</strong>.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="circle-plus" style="width:14px;height:14px;"></i> Add Vendor
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-light btn-sm">
                    <i data-lucide="inbox" style="width:14px;height:14px;"></i> Pending Orders
                </a>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
                    <i data-lucide="zap" style="width:14px;height:14px;"></i> Quick Actions
                </button>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 text-sm items-center text-ink-secondary">
            <i data-lucide="calendar" style="width:14px;height:14px;"></i>
            <span class="font-medium text-ink mr-1">Quick periods:</span>
            <a href="{{ route('admin.dashboard') }}" class="px-2 py-0.5 rounded-xs bg-brand-tint text-brand font-semibold transition-colors">Today</a>
            <a href="{{ route('admin.dashboard') }}" class="px-2 py-0.5 rounded-xs hover:bg-surface-muted text-ink-secondary transition-colors">This Week</a>
            <a href="{{ route('admin.dashboard') }}" class="px-2 py-0.5 rounded-xs hover:bg-surface-muted text-ink-secondary transition-colors">This Month</a>
            <a href="{{ route('admin.seller-performance.index') }}" class="px-2 py-0.5 rounded-xs hover:bg-surface-muted text-ink-secondary transition-colors">Performance</a>
        </div>
    </div>
</section>

{{-- ═══ TODAY'S SNAPSHOT — 4 MINI TILES ═══ --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
        <div class="admin-dash__kpi-bar absolute top-0 left-0 right-0 bg-brand"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Today's Orders</p>
                <h3 class="mb-0 font-bold text-2xl text-ink mt-1">{{ number_format($todayOrders) }}</h3>
                <small class="text-ink-tertiary">Since midnight</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-brand-tint text-brand flex items-center justify-center">
                <i data-lucide="shopping-cart" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
        <div class="admin-dash__kpi-bar absolute top-0 left-0 right-0 bg-emerald-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Today's Revenue</p>
                <h3 class="mb-0 font-bold text-2xl text-feedback-success mt-1">{{ money($todayRevenue) }}</h3>
                <small class="text-ink-tertiary">Delivered + completed</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center">
                <i data-lucide="banknote" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
        <div class="admin-dash__kpi-bar absolute top-0 left-0 right-0 bg-blue-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">New Customers Today</p>
                <h3 class="mb-0 font-bold text-2xl text-feedback-info mt-1">{{ number_format($today_new_customers ?? 0) }}</h3>
                <small class="text-ink-tertiary">Signed up today</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-blue-50 text-feedback-info flex items-center justify-center">
                <i data-lucide="user-plus" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
        <div class="admin-dash__kpi-bar absolute top-0 left-0 right-0 bg-purple-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Commission Rate</p>
                <h3 class="mb-0 font-bold text-2xl text-purple-600 mt-1">{{ $commissionRate }}%</h3>
                <small class="text-ink-tertiary">{{ money($data['total_commission'] ?? 0) }} earned</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-purple-50 text-purple-600 flex items-center justify-center">
                <i data-lucide="percent" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
</section>

{{-- ═══ MAIN KPI GRID — 8 CARDS ═══ --}}
@php
    $dashKpis = [
        ['label' => 'Total Revenue',  'value' => money($stats['total_revenue'] ?? 0), 'growth' => $revenue_growth ?? 0, 'icon' => 'wallet',          'tone' => 'brand',   'sub' => 'All-time marketplace GMV'],
        ['label' => 'Orders',         'value' => number_format($stats['total_orders'] ?? 0), 'sub' => ($stats['pending_orders'] ?? 0).' pending · '.($data['delivered_orders'] ?? 0).' delivered', 'icon' => 'shopping-cart', 'tone' => 'success'],
        ['label' => 'Customers',      'value' => number_format($data['total_customers'] ?? 0),   'sub' => ($today_new_customers ?? 0).' new today',         'icon' => 'users-round',  'tone' => 'info'],
        ['label' => 'Products',       'value' => number_format($data['total_products'] ?? 0),   'sub' => 'Listed on marketplace',                          'icon' => 'package',      'tone' => 'muted'],
        ['label' => 'Active Vendors', 'value' => number_format($stats['total_vendors'] ?? 0),   'sub' => ($data['total_sellers'] ?? 0).' total registered', 'icon' => 'store',        'tone' => 'warning'],
        ['label' => 'Commission',     'value' => money($data['total_commission'] ?? 0),         'sub' => 'Platform earnings',                              'icon' => 'percent',      'tone' => 'danger'],
        ['label' => 'Pending Orders', 'value' => number_format($stats['pending_orders'] ?? 0),  'sub' => ($pending_sellers_count ?? 0).' vendor apps',      'icon' => 'hourglass',    'tone' => 'warning'],
        ['label' => 'Categories',     'value' => number_format($top_categories->count()),      'sub' => $top_categories->sum('product_count').' products','icon' => 'folder-tree',  'tone' => 'rating'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 mb-4">
    @foreach ($dashKpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="admin-dash__kpi-bar absolute top-0 left-0 right-0
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : (
                   $kpi['tone'] === 'warning' ? 'bg-amber-500' : (
                   $kpi['tone'] === 'danger' ? 'bg-rose-500' : (
                   $kpi['tone'] === 'rating' ? 'bg-purple-500' : 'bg-gray-500'))))) }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-lg text-ink mt-1 truncate" title="{{ strip_tags($kpi['value']) }}">{{ $kpi['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                    @if (!empty($kpi['growth']) && $kpi['growth'] != 0)
                        <div class="mt-1">
                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-xs font-semibold {{ $kpi['growth'] > 0 ? 'bg-emerald-50 text-feedback-success' : 'bg-rose-50 text-rose-600' }}">
                                <i data-lucide="{{ $kpi['growth'] > 0 ? 'trending-up' : 'trending-down' }}" style="width:11px;height:11px;"></i> {{ number_format(abs($kpi['growth']), 1) }}% MoM
                            </span>
                        </div>
                    @endif
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : (
                       $kpi['tone'] === 'warning' ? 'bg-amber-50 text-feedback-warning' : (
                       $kpi['tone'] === 'danger' ? 'bg-rose-50 text-rose-500' : (
                       $kpi['tone'] === 'rating' ? 'bg-purple-50 text-purple-600' : 'bg-surface-muted text-ink-tertiary'))))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ CHART ROW — 14-DAY + STATUS MIX ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Orders & Revenue — last 14 days</h5>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#F85606"></span> Orders</span>
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full" style="background:#10B981"></span> Revenue</span>
            </div>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Order Status Mix</h5>
        </div>
        <div class="p-4 relative" style="height: 200px;">
            <canvas id="statusDonutChart"></canvas>
        </div>
        <div class="px-4 pb-4 grid grid-cols-2 gap-2">
            @foreach ($order_status_distribution as $status)
                <div class="flex items-center gap-2 p-2 rounded-sm border border-border {{ $status['bg'] }}">
                    <span class="shrink-0 w-7 h-7 rounded-sm flex items-center justify-center bg-white">
                        <i data-lucide="{{ $status['icon'] }}" class="{{ $status['color'] }}" style="width:14px;height:14px;"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="mb-0 font-bold text-sm text-ink">{{ number_format($status['count']) }}</p>
                        <small class="text-ink-tertiary">{{ $status['label'] }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ TOP VENDORS × TOP CATEGORIES ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-2 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="trophy" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Top Vendors — by revenue</h5>
            </div>
            <a href="{{ route('admin.sellers.index') }}" class="text-xs text-brand hover:text-brand-deep no-underline">All vendors →</a>
        </div>
        <div class="p-4">
            @forelse ($top_vendors as $index => $vendor)
                <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <span class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm
                        {{ $index === 0 ? 'bg-amber-100 text-feedback-warning' : ($index < 3 ? 'bg-brand-tint text-brand' : 'bg-surface-muted text-ink-tertiary') }}">
                        @if ($index === 0)
                            <i data-lucide="crown" style="width:14px;height:14px;"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="mb-0 text-sm font-medium text-ink truncate">{{ $vendor->name ?? '—' }}</p>
                        <small class="text-ink-tertiary">{{ $vendor->orders_count ?? 0 }} orders</small>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500 text-white">{{ money((float) ($vendor->total_sales ?? 0)) }}</span>
                        @if ($index === 0)
                            <div><small class="text-xs text-feedback-warning font-semibold"><i data-lucide="trending-up" style="width:10px;height:10px;"></i> #1</small></div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-sm text-ink-tertiary">
                    <i data-lucide="store" class="mx-auto mb-2 opacity-50" style="width:32px;height:32px;"></i>
                    <p class="mb-0">No vendor data yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="folder-tree" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Top Categories — by listings</h5>
            </div>
            <a href="{{ route('admin.subcategories.index') }}" class="text-xs text-brand hover:text-brand-deep no-underline">Manage →</a>
        </div>
        <div class="p-4">
            @forelse ($top_categories as $cat)
                @php $pct = $cat->product_count > 0 ? round(($cat->active_count / $cat->product_count) * 100) : 0; @endphp
                <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <span class="shrink-0 w-9 h-9 rounded-sm bg-brand-tint text-brand flex items-center justify-center font-bold text-xs uppercase">{{ substr($cat->name, 0, 2) }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="mb-0 text-sm font-medium text-ink truncate">{{ $cat->name }}</p>
                        <small class="text-ink-tertiary">{{ $cat->active_count }}/{{ $cat->product_count }} active</small>
                    </div>
                    <div class="shrink-0 text-right">
                        <div class="w-32 bg-surface-muted rounded-full h-2 overflow-hidden">
                            <div class="h-full rounded-full bg-brand" style="width: {{ $pct }}%"></div>
                        </div>
                        <small class="text-xs font-semibold {{ $pct >= 80 ? 'text-feedback-success' : ($pct >= 50 ? 'text-feedback-warning' : 'text-rose-500') }}">{{ $pct }}% active</small>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-sm text-ink-tertiary">
                    <i data-lucide="folder" class="mx-auto mb-2 opacity-50" style="width:32px;height:32px;"></i>
                    <p class="mb-0">No categories yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══ 7-DAY ACTIVITY HEATMAP × RECENT ORDERS ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="calendar-days" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">7-Day Activity</h5>
            </div>
            <small class="text-ink-tertiary">{{ number_format($activity->sum()) }} orders</small>
        </div>
        <div class="p-4 space-y-2">
            @for ($i = 6; $i >= 0; $i--)
                @php
                    $date = now()->subDays($i)->toDateString();
                    $count = (int) ($activity[$date] ?? 0);
                    $maxCount = max(1, $activity->max() ?? 1);
                    $intensity = $count > 0 ? max(20, round(($count / $maxCount) * 100)) : 8;
                    $dayLabel = now()->subDays($i)->format('D');
                    $dateLabel = now()->subDays($i)->format('M j');
                    $isToday = $i === 0;
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-12 text-xs">
                        <div class="font-semibold {{ $isToday ? 'text-brand' : 'text-ink' }}">{{ $dayLabel }}{{ $isToday ? ' · Today' : '' }}</div>
                        <div class="text-ink-tertiary">{{ $dateLabel }}</div>
                    </div>
                    <div class="admin-dash__heat-bar flex-1">
                        <span style="width: {{ $intensity }}%">
                            @if ($count > 0)
                                {{ $count }} {{ Str::plural('order', $count) }}
                            @else
                                <span class="text-ink-tertiary font-normal">No orders</span>
                            @endif
                        </span>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="receipt" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Recent Orders</h5>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Order</th>
                        <th class="px-4 py-2.5">Customer</th>
                        <th class="px-4 py-2.5">Vendor</th>
                        <th class="px-4 py-2.5 text-right">Amount</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                        <th class="px-4 py-2.5">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($recent_orders as $order)
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3 font-mono font-semibold">#{{ $order->order_number }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="shrink-0 w-7 h-7 rounded-full bg-surface-muted text-ink-tertiary flex items-center justify-center text-xs font-semibold uppercase">{{ substr($order->user?->name ?? 'N', 0, 1) }}</span>
                                    <span>{{ $order->user?->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-ink-secondary">{{ $order->seller?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ money($order->total_amount) }}</td>
                            <td class="px-4 py-3 text-center">
                                @php $style = $status_styles[$order->status->label()] ?? ['bg' => 'bg-surface-muted', 'text' => 'text-ink-tertiary']; @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $style['bg'] }} {{ $style['text'] }}">
                                    {{ $order->status->title() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-ink-tertiary">{{ $order->created_at->format('M d, H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-6 text-ink-tertiary">
                            <i data-lucide="inbox" class="mx-auto mb-2 opacity-50" style="width:32px;height:32px;"></i>
                            <p class="mb-0">No orders yet.</p>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ═══ QUICK ACTIONS MODAL ═══ --}}
<div class="modal fade" id="quickActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-b border-border">
                <h5 class="modal-title font-bold text-ink">Quick Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="divide-y divide-border">
                    <a href="{{ route('admin.sellers.create') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="circle-plus" class="text-brand shrink-0" style="width:18px;height:18px;"></i>
                        <div>
                            <span class="text-sm text-ink font-medium block">Add New Vendor</span>
                            <small class="text-ink-tertiary">Onboard a new seller</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="package" class="text-feedback-success shrink-0" style="width:18px;height:18px;"></i>
                        <div>
                            <span class="text-sm text-ink font-medium block">Manage Products</span>
                            <small class="text-ink-tertiary">All marketplace listings</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="hourglass" class="text-feedback-warning shrink-0" style="width:18px;height:18px;"></i>
                        <div>
                            <span class="text-sm text-ink font-medium block">View Pending Orders</span>
                            <small class="text-ink-tertiary">{{ number_format($stats['pending_orders'] ?? 0) }} orders awaiting</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.sellers.pending') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="users" class="text-feedback-info shrink-0" style="width:18px;height:18px;"></i>
                        <div>
                            <span class="text-sm text-ink font-medium block">Approve Vendors</span>
                            <small class="text-ink-tertiary">{{ number_format($pending_sellers_count ?? 0) }} applications waiting</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.seller-performance.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="trending-up" class="text-purple-500 shrink-0" style="width:18px;height:18px;"></i>
                        <div>
                            <span class="text-sm text-ink font-medium block">Performance Report</span>
                            <small class="text-ink-tertiary">Top performers & insights</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    const dailyData = @json($dailySeries);
    const currencySymbol = "{{ currency() }}";
    const statusData = @json(collect($order_status_distribution)->mapWithKeys(fn ($s, $k) => [$k => $s['count']]));

    const formatMoney = (v) => {
        const n = Number(v) || 0;
        if (Math.abs(n) >= 1000000) return (n / 1000000).toFixed(2).replace(/\.?0+$/, '') + 'M';
        if (Math.abs(n) >= 1000)    return (n / 1000).toFixed(1).replace(/\.?0+$/, '') + 'k';
        return n.toFixed(0);
    };

    // ── 14-DAY ORDERS + REVENUE ──
    if (document.getElementById('activityChart') && Array.isArray(dailyData) && dailyData.length > 0) {
        const ctx = document.getElementById('activityChart').getContext('2d');
        const chartHeight = 280;
        const revenueGradient = ctx.createLinearGradient(0, 0, 0, chartHeight);
        revenueGradient.addColorStop(0, 'rgba(16, 185, 129, 0.32)');
        revenueGradient.addColorStop(1, 'rgba(16, 185, 129, 0.02)');
        const longLabels = dailyData.length > 14;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dailyData.map(d => {
                    const dt = new Date(d.date);
                    return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                }),
                datasets: [
                    {
                        type: 'bar',
                        label: 'Orders',
                        data: dailyData.map(d => Number(d.orders)),
                        backgroundColor: dailyData.map(d => {
                            const max = Math.max(...dailyData.map(x => Number(x.orders)), 1);
                            const pct = Number(d.orders) / max;
                            if (pct >= 0.75) return 'rgba(248, 86, 6, 0.95)';
                            if (pct >= 0.4)  return 'rgba(248, 86, 6, 0.75)';
                            return 'rgba(248, 86, 6, 0.55)';
                        }),
                        borderColor: '#F85606',
                        borderWidth: 1,
                        borderRadius: { topLeft: 4, topRight: 4 },
                        maxBarThickness: 28,
                        yAxisID: 'y',
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Revenue',
                        data: dailyData.map(d => Number(d.revenue)),
                        borderColor: '#10B981',
                        backgroundColor: revenueGradient,
                        borderWidth: 2.5,
                        pointRadius: dailyData.length > 30 ? 0 : 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1',
                        order: 1
                    }
                ]
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
                        titleColor: '#FFFFFF', bodyColor: '#FFFFFF',
                        padding: 10, cornerRadius: 6, displayColors: false,
                        callbacks: {
                            label: (c) => {
                                if (c.dataset.yAxisID === 'y1') return 'Revenue: ' + currencySymbol + ' ' + Number(c.raw).toLocaleString();
                                return 'Orders: ' + c.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear', position: 'left', beginAtZero: true,
                        border: { display: false },
                        grid: { color: 'rgba(0,0,0,.05)' },
                        ticks: { color: '#767676', font: { size: 10 }, precision: 0 }
                    },
                    y1: {
                        type: 'linear', position: 'right', beginAtZero: true,
                        border: { display: false },
                        grid: { display: false },
                        ticks: {
                            color: '#767676', font: { size: 10 },
                            callback: (v) => currencySymbol + formatMoney(v)
                        }
                    },
                    x: {
                        border: { display: false },
                        grid: { display: false },
                        ticks: {
                            color: '#767676', font: { size: 10 },
                            autoSkip: true, autoSkipPadding: 14,
                            maxRotation: longLabels ? 45 : 0
                        }
                    }
                }
            }
        });
    }

    // ── ORDER STATUS DONUT ──
    if (document.getElementById('statusDonutChart')) {
        const ctx = document.getElementById('statusDonutChart').getContext('2d');
        const statusLabels = {
            pending:   { label: 'Pending',   color: '#F59E0B' },
            shipped:   { label: 'Shipped',   color: '#0ea5e9' },
            delivered: { label: 'Delivered', color: '#16A34A' },
            cancelled: { label: 'Cancelled', color: '#EF4444' }
        };
        const filtered = Object.entries(statusData).filter(([_, c]) => Number(c) > 0)
            .sort((a, b) => Number(b[1]) - Number(a[1]));

        if (filtered.length === 0) {
            ctx.canvas.parentElement.innerHTML += '<div class="text-center py-2 text-sm text-ink-tertiary">No order data yet.</div>';
        } else {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: filtered.map(([k]) => statusLabels[k]?.label || k),
                    datasets: [{
                        data: filtered.map(([_, c]) => Number(c)),
                        backgroundColor: filtered.map(([k]) => statusLabels[k]?.color || '#6B7280'),
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '64%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleColor: '#fff', bodyColor: '#fff',
                            padding: 10, cornerRadius: 6
                        }
                    }
                }
            });
        }
    }
</script>
@endpush
@endsection
