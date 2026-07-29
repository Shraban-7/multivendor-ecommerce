@php $pageTitle = "Seller Dashboard | {$seller->business_name}"; @endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@if (!$seller->profile_completed)
    <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex flex-col sm:flex-row items-start justify-between mb-4" role="alert">
        <div class="flex items-center gap-2">
            <i data-lucide="triangle-alert" class="text-feedback-warning text-xl"></i>
            <div>
                <strong class="text-ink">Your profile is incomplete.</strong>
                <span class="text-ink-tertiary">Please complete your profile to ensure full access to all platform features.</span>
            </div>
        </div>
        <a href="{{ route('seller.profile') }}" class="btn btn-light btn-sm mt-2 sm:mt-0" style="background: #B7791A; color: #fff; border: none; white-space: nowrap;">Complete Profile</a>
    </div>
@endif

<div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-3 mb-4">
    <div>
        <h4 class="mb-0 font-bold text-2xl text-ink">{{ $seller->business_name }}</h4>
        <p class="text-ink-tertiary mb-0 text-sm">Welcome back! Here's your business overview.</p>
    </div>
    <form id="dateRangeForm" method="GET" action="{{ route('seller.dashboard') }}" class="shrink-0">
        <div class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" style="width: auto; min-width: 130px;">
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" style="width: auto; min-width: 130px;">
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="funnel"></i> Filter
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Total Orders</span>
                    <div class="icon-bg-success flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="shopping-cart" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ $total_orders }}</h3>
                <small class="text-ink-tertiary">Orders received</small>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Total Sales</span>
                    <div class="icon-bg-primary flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="trending-up" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ money($total_sales) }}</h3>
                <small class="text-ink-tertiary">Revenue earned</small>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Avg Order Value</span>
                    <div class="icon-bg-info flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="bar-chart" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ money($average_order_value) }}</h3>
                <small class="text-ink-tertiary">Per order average</small>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Total Profit</span>
                    <div class="icon-bg-success flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="dollar-sign" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ money($profit) }}</h3>
                <small class="text-ink-tertiary">Revenue minus cost</small>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Delivered</span>
                    <div class="icon-bg-success flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ $delivered_orders }}</h3>
                <a href="{{ route('seller.orders.delivered') }}" class="text-sm no-underline">View All</a>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Pending</span>
                    <div class="icon-bg-warning flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="clock" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ $pending_orders }}</h3>
                <a href="{{ route('seller.orders.pending') }}" class="text-sm no-underline">View All</a>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Pending Payout</span>
                    <div class="icon-bg-warning flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="credit-card" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ money($pendingPayout) }}</h3>
                <a href="{{ route('seller.payouts.index') }}" class="text-sm no-underline">View Payouts</a>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-3">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Products</span>
                    <div class="icon-bg-info flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="package" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="font-bold mb-0 text-ink">{{ $total_products }}</h3>
                <small class="text-ink-tertiary">Stock value: {{ money($total_stock_value) }}</small>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    <div class="lg:col-span-2 flex flex-col">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden flex-1" style="border-radius: 12px;">
            <div class="p-4">
                <h5 class="font-semibold mb-3 flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="text-brand" style="width: 18px; height: 18px;"></i>
                    Sales & Order Analytics
                </h5>
                <canvas id="salesOrderChart" height="150"></canvas>
            </div>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden flex-1" style="border-radius: 12px;">
            <div class="p-4">
                <h5 class="font-semibold mb-3 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="text-brand" style="width: 18px; height: 18px;"></i>
                    Order Status
                </h5>
                <canvas id="statusDonutChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    <div class="lg:col-span-2 flex flex-col">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden flex-1" style="border-radius: 12px;">
            <div class="p-4">
                <h5 class="font-semibold mb-3 flex items-center gap-2">
                    <i data-lucide="award" class="text-brand" style="width: 18px; height: 18px;"></i>
                    Top Selling Products
                </h5>
                @if ($top_selling_products->count() > 0)
                    <ul class="flex flex-col mb-0">
                        @foreach ($top_selling_products as $product)
                            <li class="flex justify-between items-center px-0 py-2 border-b-0">
                                <div class="flex items-center gap-2">
                                    <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                        width="36" height="36" class="rounded border border-border" style="object-fit: cover;" />
                                    <div>
                                        <span class="text-sm font-medium text-ink">{{ $product->name }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-primary">{{ $product->sales_count }} Sold</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-ink-tertiary text-center py-3 mb-0">No sales data in this period.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden flex-1" style="border-radius: 12px;">
            <div class="p-4">
                <h5 class="font-semibold mb-3 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="text-feedback-warning" style="width: 18px; height: 18px;"></i>
                    Low Stock Alerts
                </h5>
                @if ($lowStockProducts->count() > 0)
                    <ul class="flex flex-col mb-0">
                        @foreach ($lowStockProducts as $product)
                            <li class="flex justify-between items-center px-0 py-2 border-b-0">
                                <div class="flex items-center gap-2">
                                    <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                        width="32" height="32" class="rounded border border-border" style="object-fit: cover;" />
                                    <div>
                                        <span class="text-sm font-medium text-ink">{{ Str::limit($product->name, 30) }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold {{ $product->available_stock <= $product->low_stock_quantity / 2 ? 'badge-soft-danger' : 'badge-soft-warning' }}">
                                    {{ $product->available_stock }} left
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-primary btn-sm w-full mt-3">Manage Inventory</a>
                @else
                    <div class="text-center py-4 text-ink-tertiary">
                        <i data-lucide="check-circle" style="width: 36px; height: 36px;" class="mb-2 text-feedback-success"></i>
                        <p class="mb-0 text-sm">All products are well stocked.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
    <div class="p-4">
        <div class="flex justify-between items-center mb-3">
            <h5 class="font-semibold mb-0 flex items-center gap-2">
                <i data-lucide="clipboard" class="text-brand" style="width: 18px; height: 18px;"></i>
                Latest Orders
            </h5>
            <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-primary btn-sm">View All Orders</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="text-sm font-semibold text-ink-tertiary">Order ID</th>
                        <th scope="col" class="text-sm font-semibold text-ink-tertiary">Customer</th>
                        <th scope="col" class="text-sm font-semibold text-ink-tertiary">Total</th>
                        <th scope="col" class="text-sm font-semibold text-ink-tertiary">Status</th>
                        <th scope="col" class="text-sm font-semibold text-ink-tertiary">Date</th>
                        <th scope="col" class="text-sm font-semibold text-ink-tertiary">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latest_orders as $order)
                        <tr>
                            <td class="font-medium">{{ $order->invoice_id }}</td>
                            <td class="px-4 py-3 border-b border-border text-sm align-middle">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="font-medium">{{ money($order->total) }}</td>
                            <td>
                                @php $label = $order->status->label(); @endphp
                                @if ($label === 'pending')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-warning">Pending</span>
                                @elseif ($label === 'shipped')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-primary">Shipped</span>
                                @elseif ($label === 'cancelled')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-danger">Cancelled</span>
                                @elseif ($label === 'delivered' || $label === 'completed')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-success">{{ ucfirst($label) }}</span>
                                @elseif ($label === 'refunded')
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-info">Refunded</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-secondary">{{ ucfirst($label) }}</span>
                                @endif
                            </td>
                            <td class="text-sm text-ink-tertiary">{{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('seller.orders.details', $order->invoice_id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-ink-tertiary">No orders in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3 mb-4">
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="p-3 text-center">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Commission Paid</span>
                    <div class="icon-bg-primary flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="percent" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h4 class="font-bold mb-0 text-ink">{{ money($total_commission) }}</h4>
                <small class="text-ink-tertiary">Platform commission</small>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="p-3 text-center">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Expenses</span>
                    <div class="icon-bg-secondary flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="dollar-sign" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h4 class="font-bold mb-0 text-ink">{{ money($total_expense) }}</h4>
                <a href="{{ route('seller.expenses.index') }}" class="text-sm no-underline">View Details</a>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="p-3 text-center">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-ink-tertiary">Customers</span>
                    <div class="icon-bg-success flex items-center justify-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-lucide="users" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h4 class="font-bold mb-0 text-ink">{{ $total_customers }}</h4>
                <a href="{{ route('seller.customers') }}" class="text-sm no-underline">View Customers</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const chartData = @json($chartData);
    const statusData = @json($orderStatusDistribution);

    const statusLabels = {
        0: 'Pending', 1: 'Accepted', 2: 'Shipped', 3: 'Delivered',
        4: 'Completed', 5: 'Cancelled', 6: 'Return Requested',
        7: 'Return Approved', 8: 'Returned', 9: 'Refunded'
    };

    const statusColors = {
        0: '#F59E0B', 1: '#6366F1', 2: '#3B82F6', 3: '#10B981',
        4: '#059669', 5: '#EF4444', 6: '#F97316', 7: '#8B5CF6',
        8: '#EC4899', 9: '#6B7280'
    };

    const ctx = document.getElementById('salesOrderChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Orders',
                data: chartData.orders,
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--bs-primary').trim() || '#F85606',
                backgroundColor: 'rgba(248, 86, 6, 0.08)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--bs-primary').trim() || '#F85606',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }, {
                label: 'Sales',
                data: chartData.sales,
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.08)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0ea5e9',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }, {
                label: 'Profit',
                data: chartData.profits,
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--bs-success').trim() || '#1D8A45',
                backgroundColor: 'rgba(29, 138, 69, 0.08)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--bs-success').trim() || '#1D8A45',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: { usePointStyle: true, boxWidth: 6, font: { size: 12 } }
                }
            },
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: { callback: function(value) { return '৳' + value; } }
                },
                x: { grid: { display: false } }
            }
        }
    });

    const statusCtx = document.getElementById('statusDonutChart').getContext('2d');
    const filteredStatuses = Object.entries(statusData).filter(([_, count]) => parseInt(count) > 0);
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: filteredStatuses.map(([key]) => statusLabels[key] || 'Unknown'),
            datasets: [{
                data: filteredStatuses.map(([_, count]) => count),
                backgroundColor: filteredStatuses.map(([key]) => statusColors[key] || '#6B7280'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 10, padding: 8, font: { size: 11 } }
                }
            }
        }
    });
</script>
@endpush
