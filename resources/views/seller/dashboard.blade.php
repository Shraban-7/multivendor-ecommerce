@php $pageTitle = "Seller Dashboard | {$seller->business_name}"; @endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@if (!$seller->profile_completed)
    <div class="alert alert-warning d-flex flex-column flex-sm-row align-items-start justify-content-between border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
            <div>
                <strong class="text-dark">Your profile is incomplete.</strong>
                <span class="text-muted">Please complete your profile to ensure full access to all platform features.</span>
            </div>
        </div>
        <a href="{{ route('seller.profile') }}" class="btn btn-sm mt-2 mt-sm-0" style="background: #B7791A; color: #fff; border: none; white-space: nowrap;">Complete Profile</a>
    </div>
@endif

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-end justify-content-between gap-3 mb-4">
    <div>
        <h4 class="mb-0 fw-bold fs-3 text-dark">{{ $seller->business_name }}</h4>
        <p class="text-muted mb-0 small">Welcome back! Here's your business overview.</p>
    </div>
    <form id="dateRangeForm" method="GET" action="{{ route('seller.dashboard') }}" class="flex-shrink-0">
        <div class="d-flex align-items-center gap-2">
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="form-control form-control-sm" style="width: auto; min-width: 130px;">
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="form-control form-control-sm" style="width: auto; min-width: 130px;">
            <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Total Orders</span>
                    <div class="icon-bg-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="shopping-cart" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $total_orders }}</h3>
                <small class="text-muted">Orders received</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Total Sales</span>
                    <div class="icon-bg-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="trending-up" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($total_sales) }}</h3>
                <small class="text-muted">Revenue earned</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Avg Order Value</span>
                    <div class="icon-bg-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="bar-chart" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($average_order_value) }}</h3>
                <small class="text-muted">Per order average</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Total Profit</span>
                    <div class="icon-bg-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="dollar-sign" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($profit) }}</h3>
                <small class="text-muted">Revenue minus cost</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Delivered</span>
                    <div class="icon-bg-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="check-circle" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $delivered_orders }}</h3>
                <a href="{{ route('seller.orders.delivered') }}" class="small text-decoration-none">View All</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Pending</span>
                    <div class="icon-bg-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="clock" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $pending_orders }}</h3>
                <a href="{{ route('seller.orders.pending') }}" class="small text-decoration-none">View All</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Pending Payout</span>
                    <div class="icon-bg-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="credit-card" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($pendingPayout) }}</h3>
                <a href="{{ route('seller.payouts.index') }}" class="small text-decoration-none">View Payouts</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Products</span>
                    <div class="icon-bg-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="package" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $total_products }}</h3>
                <small class="text-muted">Stock value: {{ money($total_stock_value) }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8 d-flex flex-column">
        <div class="card border-0 shadow-sm flex-fill" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i data-feather="bar-chart-2" class="text-primary" style="width: 18px; height: 18px;"></i>
                    Sales & Order Analytics
                </h5>
                <canvas id="salesOrderChart" height="150"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 d-flex flex-column">
        <div class="card border-0 shadow-sm flex-fill" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i data-feather="pie-chart" class="text-primary" style="width: 18px; height: 18px;"></i>
                    Order Status
                </h5>
                <canvas id="statusDonutChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8 d-flex flex-column">
        <div class="card border-0 shadow-sm flex-fill" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i data-feather="award" class="text-primary" style="width: 18px; height: 18px;"></i>
                    Top Selling Products
                </h5>
                @if ($top_selling_products->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach ($top_selling_products as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                        width="36" height="36" class="rounded border border-subtle" style="object-fit: cover;" />
                                    <div>
                                        <span class="small fw-medium text-dark">{{ $product->name }}</span>
                                    </div>
                                </div>
                                <span class="badge rounded-pill px-3 badge-soft-primary">{{ $product->sales_count }} Sold</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center py-3 mb-0">No sales data in this period.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 d-flex flex-column">
        <div class="card border-0 shadow-sm flex-fill" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i data-feather="alert-triangle" class="text-warning" style="width: 18px; height: 18px;"></i>
                    Low Stock Alerts
                </h5>
                @if ($lowStockProducts->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach ($lowStockProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                        width="32" height="32" class="rounded border border-subtle" style="object-fit: cover;" />
                                    <div>
                                        <span class="small fw-medium text-dark">{{ Str::limit($product->name, 30) }}</span>
                                    </div>
                                </div>
                                <span class="badge rounded-pill px-3 {{ $product->available_stock <= $product->low_stock_quantity / 2 ? 'badge-soft-danger' : 'badge-soft-warning' }}">
                                    {{ $product->available_stock }} left
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-primary w-100 mt-3">Manage Inventory</a>
                @else
                    <div class="text-center py-4 text-muted">
                        <i data-feather="check-circle" style="width: 36px; height: 36px;" class="mb-2 text-success"></i>
                        <p class="mb-0 small">All products are well stocked.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                <i data-feather="clipboard" class="text-primary" style="width: 18px; height: 18px;"></i>
                Latest Orders
            </h5>
            <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="small fw-semibold text-muted">Order ID</th>
                        <th scope="col" class="small fw-semibold text-muted">Customer</th>
                        <th scope="col" class="small fw-semibold text-muted">Total</th>
                        <th scope="col" class="small fw-semibold text-muted">Status</th>
                        <th scope="col" class="small fw-semibold text-muted">Date</th>
                        <th scope="col" class="small fw-semibold text-muted">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latest_orders as $order)
                        <tr>
                            <td class="fw-medium">{{ $order->invoice_id }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="fw-medium">{{ money($order->total) }}</td>
                            <td>
                                @php $label = $order->status->label(); @endphp
                                @if ($label === 'pending')
                                    <span class="badge rounded-pill px-3 badge-soft-warning">Pending</span>
                                @elseif ($label === 'shipped')
                                    <span class="badge rounded-pill px-3 badge-soft-primary">Shipped</span>
                                @elseif ($label === 'cancelled')
                                    <span class="badge rounded-pill px-3 badge-soft-danger">Cancelled</span>
                                @elseif ($label === 'delivered' || $label === 'completed')
                                    <span class="badge rounded-pill px-3 badge-soft-success">{{ ucfirst($label) }}</span>
                                @elseif ($label === 'refunded')
                                    <span class="badge rounded-pill px-3 badge-soft-info">Refunded</span>
                                @else
                                    <span class="badge rounded-pill px-3 badge-soft-secondary">{{ ucfirst($label) }}</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('seller.orders.details', $order->invoice_id) }}"
                                    class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                    <i data-feather="eye" style="width: 14px; height: 14px;"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No orders in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3 mt-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-3 text-center">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Commission Paid</span>
                    <div class="icon-bg-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="percent" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark">{{ money($total_commission) }}</h4>
                <small class="text-muted">Platform commission</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-3 text-center">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Expenses</span>
                    <div class="icon-bg-secondary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="dollar-sign" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark">{{ money($total_expense) }}</h4>
                <a href="{{ route('seller.expenses.index') }}" class="small text-decoration-none">View Details</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-3 text-center">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Customers</span>
                    <div class="icon-bg-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="users" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-0 text-dark">{{ $total_customers }}</h4>
                <a href="{{ route('seller.customers') }}" class="small text-decoration-none">View Customers</a>
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
