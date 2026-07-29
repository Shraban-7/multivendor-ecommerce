@extends('admin.layouts.app')
@section('title', 'Admin Dashboard')
@section('content')

{{-- ═══ PENDING VENDORS ALERT ═══ --}}
@if($pending_sellers_count)
<div class="mb-4 p-4 rounded-sm bg-amber-50 border border-amber-200 flex items-start gap-3" role="alert">
    <span class="shrink-0 w-9 h-9 rounded-full bg-amber-100 text-feedback-warning flex items-center justify-center">
        <i data-lucide="triangle-alert" style="width:18px;height:18px;"></i>
    </span>
    <div class="flex-1">
        <h5 class="mb-1 text-ink font-semibold">Pending Vendor Applications</h5>
        <p class="mb-2 text-sm text-ink-secondary">
            You have <strong class="text-feedback-warning">{{ $pending_sellers_count }}</strong> new vendor{{ $pending_sellers_count !== 1 ? 's' : '' }} waiting for approval.
            @if($pending_sellers->isNotEmpty())
                <span class="text-ink-tertiary">Latest: <strong>{{ $pending_sellers->first()->name }}</strong>@if($pending_sellers_count > 1) and {{ $pending_sellers_count - 1 }} more @endif</span>
            @endif
        </p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.sellers.pending') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs bg-feedback-warning text-white hover:bg-amber-700 transition-colors">
                <i data-lucide="eye" class="me-1" style="width:14px;height:14px;"></i> Review Applications
            </a>
            <button type="button" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs bg-white border border-border text-ink-secondary hover:bg-surface-muted transition-colors" data-bs-dismiss="alert">
                <i data-lucide="x" style="width:14px;height:14px;"></i> Dismiss
            </button>
        </div>
    </div>
</div>
@endif

{{-- ═══ HEADER ═══ --}}
<div class="flex items-end justify-between mb-4 gap-2 flex-wrap">
    <div>
        <h1 class="text-xl font-semibold text-ink mb-0">Marketplace Overview</h1>
        <p class="text-sm text-ink-secondary mt-1">Live snapshot of revenue, vendors, and customer activity.</p>
    </div>
    <div class="flex items-center gap-2">
        <button class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-xs bg-brand text-white hover:bg-brand-deep transition-colors" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
            <i data-lucide="zap" class="me-2" style="width:16px;height:16px;"></i> Quick Actions
        </button>
    </div>
</div>

{{-- ═══ KPI GRID: 8 CARDS ═══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-3 mb-4">

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-brand-tint flex items-center justify-center">
                <i data-lucide="dollar-sign" class="text-brand" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Revenue</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ money($stats['total_revenue']) }}</h4>
                @if($revenue_growth != 0)
                <small class="{{ $revenue_growth >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                    <i data-lucide="{{ $revenue_growth >= 0 ? 'trending-up' : 'trending-down' }}" style="width:10px;height:10px;"></i>
                    {{ abs($revenue_growth) }}% MoM
                </small>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 flex items-center justify-center">
                <i data-lucide="shopping-cart" class="text-feedback-success" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Orders</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ number_format($stats['total_orders']) }}</h4>
                <small class="text-ink-tertiary">{{ $stats['pending_orders'] }} pending · {{ $data['delivered_orders'] }} delivered</small>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-blue-50 flex items-center justify-center">
                <i data-lucide="users" class="text-feedback-info" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Customers</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ number_format($data['total_customers']) }}</h4>
                <small class="text-ink-tertiary">{{ $today_new_customers ?? 0 }} new today</small>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-purple-50 flex items-center justify-center">
                <i data-lucide="package" class="text-purple-600" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Products</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ number_format($data['total_products']) }}</h4>
                <small class="text-ink-tertiary">Listed on marketplace</small>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-amber-50 flex items-center justify-center">
                <i data-lucide="store" class="text-feedback-warning" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Active Vendors</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ number_format($stats['total_vendors']) }}</h4>
                <small class="text-ink-tertiary">{{ $data['total_sellers'] }} total registered</small>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-rose-50 flex items-center justify-center">
                <i data-lucide="percent" class="text-rose-500" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Commission</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ money($data['total_commission']) }}</h4>
                <small class="text-ink-tertiary">Platform earnings</small>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-amber-50 flex items-center justify-center">
                <i data-lucide="hourglass" class="text-feedback-warning" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Pending</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ number_format($stats['pending_orders']) }}</h4>
                <small class="text-ink-tertiary">{{ number_format($pending_sellers_count) }} vendor apps</small>
            </div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-4 transition-shadow hover:shadow-md">
        <div class="flex items-center gap-3">
            <div class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 flex items-center justify-center">
                <i data-lucide="zap" class="text-feedback-success" style="width:20px;height:20px;"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0">Today's Orders</p>
                <h4 class="mb-0 font-bold text-lg text-ink leading-tight">{{ number_format($today->cnt ?? 0) }}</h4>
                <small class="text-ink-tertiary">{{ money($today->revenue ?? 0) }} revenue</small>
            </div>
        </div>
    </div>
</div>

{{-- ═══ ROW 1: 14-DAY CHART + STATUS MIX ═══ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Orders & Revenue — last 14 days</h5>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full bg-brand"></span> Orders</span>
                <span class="flex items-center gap-1 text-ink-secondary"><span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> Revenue</span>
            </div>
        </div>
        <div class="p-4">
            <canvas id="activityChart" height="120"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Order Status Mix</h5>
        </div>
        <div class="p-4 grid grid-cols-2 gap-3">
            @foreach($order_status_distribution as $status)
            <div class="border border-border rounded-sm p-3 text-center bg-surface-muted/40">
                <div class="w-10 h-10 rounded-full {{ $status['bg'] }} flex items-center justify-center mx-auto mb-1">
                    <i data-lucide="{{ $status['icon'] }}" class="{{ $status['color'] }}" style="width:18px;height:18px;"></i>
                </div>
                <p class="mb-0 font-bold text-xl text-ink">{{ number_format($status['count']) }}</p>
                <small class="text-ink-tertiary">{{ $status['label'] }}</small>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ═══ ROW 2: TOP VENDORS + TOP CATEGORIES ═══ --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="store" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Top Vendors — by revenue</h5>
            </div>
            <span class="text-xs text-ink-tertiary">Lifetime</span>
        </div>
        <div class="p-4">
            @foreach($top_vendors as $index => $vendor)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                <div class="shrink-0 w-9 h-9 rounded-full {{ $index === 0 ? 'bg-amber-100 text-feedback-warning' : ($index < 3 ? 'bg-brand-tint text-brand' : 'bg-surface-muted text-ink-tertiary') }} flex items-center justify-center font-bold text-sm">
                    {{ $index + 1 }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="mb-0 text-sm font-medium text-ink truncate">{{ $vendor->name }}</p>
                    <small class="text-ink-tertiary">{{ $vendor->orders_count }} orders</small>
                </div>
                <div class="shrink-0 text-right">
                    <span class="text-xs font-semibold text-feedback-success">{{ money($vendor->total_sales ?? 0) }}</span>
                    @if($index === 0)
                    <div><span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-amber-100 text-feedback-warning text-xs"><i data-lucide="crown" style="width:10px;height:10px;"></i> #1</span></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="folder-tree" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Top Categories — by listings</h5>
            </div>
            <a href="{{ route('admin.subcategories.index') }}" class="text-xs text-brand hover:text-brand-deep no-underline">Manage</a>
        </div>
        <div class="p-4">
            @forelse($top_categories as $cat)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                <div class="shrink-0 w-9 h-9 rounded-full bg-brand-tint text-brand flex items-center justify-center font-bold text-xs">
                    {{ substr($cat->name, 0, 2) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="mb-0 text-sm font-medium text-ink truncate">{{ $cat->name }}</p>
                    <small class="text-ink-tertiary">{{ $cat->active_count }}/{{ $cat->product_count }} active</small>
                </div>
                <div class="shrink-0 text-right">
                    <div class="w-32 bg-surface-muted rounded-full h-2 overflow-hidden">
                        @php $pct = $cat->product_count > 0 ? round(($cat->active_count / $cat->product_count) * 100) : 0; @endphp
                        <div class="bg-brand h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <small class="text-xs text-ink-tertiary">{{ $pct }}% active</small>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-ink-tertiary text-sm">No categories yet.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ═══ ROW 3: 7-DAY ACTIVITY HEATMAP + RECENT ORDERS ═══ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="calendar-days" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">7-Day Activity</h5>
        </div>
        <div class="p-4">
            @for ($i = 6; $i >= 0; $i--)
                @php
                    $date = now()->subDays($i)->toDateString();
                    $count = $activity[$date] ?? 0;
                    $maxCount = max(1, $activity->max() ?? 1);
                    $intensity = $count > 0 ? max(15, round(($count / $maxCount) * 100)) : 5;
                    $dayLabel = now()->subDays($i)->format('D');
                    $dateLabel = now()->subDays($i)->format('M j');
                @endphp
                <div class="flex items-center gap-3 py-2 border-b border-border last:border-0">
                    <div class="w-12 text-xs">
                        <div class="font-semibold text-ink">{{ $dayLabel }}</div>
                        <div class="text-ink-tertiary">{{ $dateLabel }}</div>
                    </div>
                    <div class="flex-1 bg-surface-muted rounded-sm overflow-hidden relative" style="height: 28px;">
                        <div class="absolute inset-y-0 left-0 bg-brand flex items-center px-2 text-white text-xs font-semibold"
                             style="width: {{ $intensity }}%">
                            {{ $count }} {{ Str::plural('order', $count) }}
                        </div>
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
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded-xs bg-brand-tint text-brand hover:bg-brand hover:text-white transition-colors no-underline">
                View all
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted text-xs uppercase tracking-wider text-ink-tertiary">
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
                        <td class="px-4 py-2.5 font-semibold font-mono">#{{ $order->order_number }}</td>
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2">
                                <span class="shrink-0 w-7 h-7 rounded-full bg-surface-muted text-ink-tertiary flex items-center justify-center text-xs font-semibold uppercase">{{ substr($order->user?->name ?? 'N', 0, 1) }}</span>
                                <span class="text-sm">{{ $order->user?->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2.5 text-ink-secondary">{{ $order->seller?->name ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-right font-semibold">{{ money($order->total_amount) }}</td>
                        <td class="px-4 py-2.5 text-center">
                            @php
                            $style = $status_styles[$order->status->label()] ?? ['bg' => 'bg-surface-muted', 'text' => 'text-ink-tertiary'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $style['bg'] }} {{ $style['text'] }}">
                                {{ $order->status->title() }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-ink-tertiary">{{ $order->created_at->format('M d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-6 text-ink-tertiary">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                        <span class="text-sm text-ink">Add New Vendor</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="package" class="text-feedback-success shrink-0" style="width:18px;height:18px;"></i>
                        <span class="text-sm text-ink">Manage Products</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="hourglass" class="text-feedback-warning shrink-0" style="width:18px;height:18px;"></i>
                        <span class="text-sm text-ink">View Pending Orders</span>
                    </a>
                    <a href="{{ route('admin.sellers.pending') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="users" class="text-feedback-info shrink-0" style="width:18px;height:18px;"></i>
                        <span class="text-sm text-ink">Approve Vendors</span>
                    </a>
                    <a href="{{ route('admin.seller-performance.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-surface-muted transition-colors">
                        <i data-lucide="trending-up" class="text-purple-500 shrink-0" style="width:18px;height:18px;"></i>
                        <span class="text-sm text-ink">Generate Report</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const dailyData = @json($dailySeries);
    const currencySymbol = "{{ currency() }}";
    const activityMap = @json($activity);

    const buildLabels = (data) => data.map(d => {
        const dt = new Date(d.date);
        return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });

    const ctx = document.getElementById('activityChart').getContext('2d');
    const ordersGradient = ctx.createLinearGradient(0, 0, 0, 160);
    ordersGradient.addColorStop(0, 'rgba(248, 86, 6, 0.30)');
    ordersGradient.addColorStop(1, 'rgba(248, 86, 6, 0)');
    const revenueGradient = ctx.createLinearGradient(0, 0, 0, 160);
    revenueGradient.addColorStop(0, 'rgba(16, 185, 129, 0.28)');
    revenueGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: buildLabels(dailyData),
            datasets: [{
                type: 'bar',
                label: 'Orders',
                data: dailyData.map(d => Number(d.orders)),
                backgroundColor: 'rgba(248, 86, 6, 0.85)',
                borderRadius: 4,
                yAxisID: 'y',
                order: 2
            }, {
                type: 'line',
                label: 'Revenue',
                data: dailyData.map(d => Number(d.revenue)),
                borderColor: '#10B981',
                backgroundColor: revenueGradient,
                borderWidth: 2.5,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#10B981',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                tension: 0.4,
                fill: true,
                yAxisID: 'y1',
                order: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#191919',
                    titleColor: '#FFFFFF',
                    bodyColor: '#FFFFFF',
                    padding: 10,
                    callbacks: {
                        label: function(ctx) {
                            if (ctx.dataset.yAxisID === 'y1') return ' Revenue: ' + currencySymbol + ' ' + Number(ctx.raw).toLocaleString();
                            return ' Orders: ' + ctx.raw;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear', position: 'left', beginAtZero: true,
                    border: { display: false },
                    grid: { color: 'rgba(0,0,0,.05)' },
                    ticks: { color: '#767676', font: { size: 11 } }
                },
                y1: {
                    type: 'linear', position: 'right', beginAtZero: true,
                    border: { display: false },
                    grid: { display: false },
                    ticks: {
                        color: '#767676', font: { size: 11 },
                        callback: function(v) { return currencySymbol + v.toLocaleString(); }
                    }
                },
                x: {
                    border: { display: false },
                    grid: { display: false },
                    ticks: { color: '#767676', font: { size: 11 } }
                }
            }
        }
    });
</script>
@endpush

@endsection