@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- ═══ PENDING VENDORS ALERT ═══ --}}
@if($pending_sellers_count)
<div class="mb-4 p-4 rounded-sm bg-amber-50 border border-amber-200 shadow-sm" role="alert">
    <div class="flex items-center gap-2 mb-1">
        <i data-lucide="triangle-alert" class="text-feedback-warning" style="width:20px;height:20px;"></i>
        <h5 class="mb-0 text-ink font-semibold">Pending Vendor Applications</h5>
    </div>
    <p class="mb-2 text-sm text-ink-secondary">
        You have <strong>{{ $pending_sellers_count }}</strong> new vendor{{ $pending_sellers_count !== 1 ? 's' : '' }} waiting for approval.
    </p>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.sellers.pending') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs bg-feedback-warning text-white hover:bg-amber-700 transition-colors">
            <i data-lucide="eye" class="me-1" style="width:14px;height:14px;"></i> View Applications
        </a>
        <button type="button" class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs bg-white border border-border text-ink-secondary hover:bg-surface-muted transition-colors" data-bs-dismiss="alert">
            <i data-lucide="x" style="width:14px;height:14px;"></i> Dismiss
        </button>
    </div>
    @if($pending_sellers->isNotEmpty())
    <hr class="my-2 border-border">
    <small class="text-ink-tertiary">
        <strong>New:</strong>
        @foreach($pending_sellers->take(3) as $shop)
        {{ $shop->name }}@if(!$loop->last), @endif
        @endforeach
        @if($pending_sellers_count > 3) ... @endif
    </small>
    @endif
</div>
@endif

{{-- ═══ HEADER ═══ --}}
<div class="flex items-center justify-between mb-4">
    <h3 class="font-bold text-xl text-ink mb-0">Dashboard</h3>
    <button class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-xs bg-brand text-white hover:bg-brand-deep transition-colors" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
        <i data-lucide="zap" class="me-2" style="width:16px;height:16px;"></i>Quick Actions
    </button>
</div>

{{-- ═══ STAT CARDS ═══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-4">

    {{-- Revenue --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-sm bg-brand-tint flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="text-brand" style="width:20px;height:20px;"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0">Total Revenue</p>
                    <h4 class="mb-0 font-bold text-lg text-ink">{{ money($stats['total_revenue']) }}</h4>
                    @if($revenue_growth != 0)
                    <small class="{{ $revenue_growth >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                        <i data-lucide="{{ $revenue_growth >= 0 ? 'arrow-up' : 'arrow-down' }}" style="width:10px;height:10px;"></i>
                        {{ abs($revenue_growth) }}% vs last month
                    </small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Orders --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="text-feedback-success" style="width:20px;height:20px;"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0">Total Orders</p>
                    <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($stats['total_orders']) }}</h4>
                    <small class="text-ink-tertiary">{{ $data['pending_orders'] }} pending · {{ $data['delivered_orders'] }} delivered</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Vendors --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-sm bg-amber-50 flex items-center justify-center">
                    <i data-lucide="store" class="text-feedback-warning" style="width:20px;height:20px;"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0">Active Vendors</p>
                    <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($stats['total_vendors']) }}</h4>
                    <small class="text-ink-tertiary">{{ $data['total_sellers'] }} total registered</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Customers --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-sm bg-blue-50 flex items-center justify-center">
                    <i data-lucide="users" class="text-feedback-info" style="width:20px;height:20px;"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0">Total Customers</p>
                    <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($stats['total_customers']) }}</h4>
                    <small class="text-ink-tertiary">{{ $stats['total_customers'] }} registered users</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-sm bg-purple-50 flex items-center justify-center">
                    <i data-lucide="package" class="text-purple-600" style="width:20px;height:20px;"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0">Total Products</p>
                    <h4 class="mb-0 font-bold text-lg text-ink">{{ number_format($stats['total_products']) }}</h4>
                    <small class="text-ink-tertiary">Listed on marketplace</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Sales --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 rounded-sm bg-rose-50 flex items-center justify-center">
                    <i data-lucide="trending-up" class="text-rose-500" style="width:20px;height:20px;"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0">Total Commission</p>
                    <h4 class="mb-0 font-bold text-lg text-ink">{{ money($data['total_commission']) }}</h4>
                    <small class="text-ink-tertiary">Earned from sales</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ CHART + TOP VENDORS ═══ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">

    {{-- Revenue Chart --}}
    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between">
            <h5 class="mb-0 font-bold text-ink">Revenue Overview</h5>
            <select class="text-xs text-ink-secondary bg-surface-muted border border-border rounded-xs px-2 py-1 focus:outline-none focus:border-brand-deep">
                <option>Last 6 Months</option>
                <option>Last Year</option>
                <option>All Time</option>
            </select>
        </div>
        <div class="p-4">
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>

    {{-- Right column: stacked cards --}}
    <div class="flex flex-col gap-3">

        {{-- Top Vendors --}}
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
            <div class="px-4 py-3 border-b border-border">
                <h5 class="mb-0 font-bold text-ink">Top Vendors</h5>
            </div>
            <div class="p-4">
                @foreach($top_vendors as $index => $vendor)
                <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                    <div class="shrink-0 w-9 h-9 rounded-full bg-surface-muted flex items-center justify-center">
                        <span class="font-bold text-sm {{ $index < 3 ? 'text-brand' : 'text-ink-tertiary' }}">
                            {{ $index + 1 }}
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="mb-0 text-sm font-medium text-ink truncate">{{ $vendor->name }}</p>
                        <small class="text-ink-tertiary">{{ $vendor->orders_count }} orders</small>
                    </div>
                    <div class="shrink-0 text-xs font-semibold text-feedback-success bg-emerald-50 px-2 py-1 rounded-xs">
                        {{ money($vendor->total_sales ?? 0) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Order Status Summary --}}
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md">
            <div class="px-4 py-3 border-b border-border">
                <h5 class="mb-0 font-bold text-ink">Order Overview</h5>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-3">
                    @foreach($order_status_distribution as $status)
                    <div class="text-center">
                        <div class="w-9 h-9 rounded-full {{ $status['bg'] }} flex items-center justify-center mx-auto mb-1">
                            <i data-lucide="{{ $status['icon'] }}" class="{{ $status['color'] }}" style="width:16px;height:16px;"></i>
                        </div>
                        <p class="mb-0 font-bold text-lg text-ink">{{ number_format($status['count']) }}</p>
                        <small class="text-ink-tertiary">{{ $status['label'] }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ═══ RECENT ORDERS ═══ --}}
<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden transition-shadow hover:shadow-md mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between">
        <h5 class="mb-0 font-bold text-ink">Recent Orders</h5>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded-xs bg-brand-tint text-brand hover:bg-brand hover:text-white transition-colors">
            View All
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Vendor</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_orders as $order)
                <tr>
                    <td class="font-semibold">#{{ $order->order_number }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-surface-muted flex items-center justify-center">
                                <i data-lucide="user" style="width:12px;height:12px;" class="text-ink-tertiary"></i>
                            </div>
                            <span>{{ $order->user?->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>{{ $order->seller?->name ?? 'N/A' }}</td>
                    <td class="font-semibold">{{ money($order->total_amount) }}</td>
                    <td>
                        @php
                        $style = $status_styles[$order->status->label()] ?? ['bg' => 'bg-surface-muted', 'text' => 'text-ink-tertiary'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs {{ $style['bg'] }} {{ $style['text'] }}">
                            {{ $order->status->title() }}
                        </span>
                    </td>
                    <td class="text-ink-tertiary">{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="flex items-center justify-center gap-1">
                            <a href="#" class="inline-flex items-center justify-center w-7 h-7 rounded-xs bg-surface-muted text-ink-secondary hover:bg-brand-tint hover:text-brand transition-colors">
                                <i data-lucide="eye" style="width:14px;height:14px;"></i>
                            </a>
                            <div class="dropdown">
                                <button class="inline-flex items-center justify-center w-7 h-7 rounded-xs bg-surface-muted text-ink-secondary hover:bg-surface-strong hover:text-white transition-colors" data-bs-toggle="dropdown">
                                    <i data-lucide="ellipsis-vertical" style="width:14px;height:14px;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i data-lucide="pencil" class="me-2" style="width:14px;height:14px;"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i data-lucide="printer" class="me-2" style="width:14px;height:14px;"></i>Print</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthly_revenue);
    const currencySymbol = "{{ currency() }}";

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(function(item) { return item.month; }),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(function(item) { return item.revenue; }),
                borderColor: '#F85606',
                backgroundColor: 'rgba(248, 86, 6, 0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#F85606',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#191919',
                    titleColor: '#FFFFFF',
                    bodyColor: '#FFFFFF',
                    padding: 10,
                    cornerRadius: 4,
                    callbacks: {
                        label: function(context) {
                            return currencySymbol + ' ' + Number(context.raw).toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#F0F0F0' },
                    ticks: {
                        color: '#767676',
                        font: { size: 11 },
                        callback: function(value) {
                            return currencySymbol + value.toLocaleString();
                        }
                    }
                },
                x: {
                    border: { display: false },
                    grid: { display: false },
                    ticks: {
                        color: '#767676',
                        font: { size: 11 }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
</script>
@endpush

@endsection