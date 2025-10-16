@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

@if($pending_sellers_count)
<div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
    <div class="d-flex align-items-center mb-2">
        <i class="fas fa-exclamation-triangle fs-4 me-2 text-warning"></i>
        <h5 class="mb-0 text-dark">Pending Vendor Applications</h5>
    </div>
    <p class="mb-1 text-dark">
        You have <strong>{{ $pending_sellers_count }}</strong> new vendor{{ $pending_sellers_count !== 1 ? 's' : '' }} waiting for approval.
        Review them to activate their shops.
    </p>

    <div class="mt-2">
        <a href="" class="btn btn-sm btn-warning me-2">
            <i class="fas fa-eye me-1"></i> View Applications
        </a>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="alert">
            <i class="fas fa-times"></i> Dismiss
        </button>
    </div>

    @if($pending_sellers->isNotEmpty())
    <hr class="my-2">
    <small class="d-block text-muted">
        <strong>New:</strong>
        @foreach($pending_sellers->take(3) as $shop)
        {{ $shop->name }}@if(!$loop->last), @endif
        @endforeach
        @if($pending_sellers_count > 3){{ '...' }}@endif
    </small>
    @endif
</div>
@endif

<div class="container-fluid mt-2">
    <div class="row mb-2">
        <div class="col d-flex align-items-end">
            <h3 class="fw-bold mb-0">Dashboard</h3>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#quickActionsModal">
                <i class="bi bi-lightning-fill me-2"></i>Quick Actions
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 
                                text-primary rounded p-3">
                                <i class="bi bi-currency-dollar fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ money($stats['total_revenue']) }}
                            </h3>
                            <!-- <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 12.5% from last month
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 
                                text-success rounded p-3">
                                <i class="bi bi-cart-check fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Orders</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ number_format($stats['total_orders']) }}
                            </h3>
                            <!-- <small class="text-muted">
                                {{ $stats['pending_orders'] }} pending
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 
                                text-warning rounded p-3">
                                <i class="bi bi-shop fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Active Vendors</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ number_format($stats['total_vendors']) }}
                            </h3>
                            <!-- <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 3 new this week
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 
                                text-info rounded p-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Customers</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ number_format($stats['total_customers']) }}
                            </h3>
                            <!-- <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 8.2% growth
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0 fw-bold">Revenue Overview</h5>
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm">
                                <option>Last 6 Months</option>
                                <option>Last Year</option>
                                <option>All Time</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Vendors -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Top Vendors</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($top_vendors as $index => $vendor)
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-light rounded-circle 
                                        d-flex align-items-center 
                                        justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <span class="fw-bold text-primary">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $vendor->name }}</h6>
                                    <small class="text-muted">
                                        {{ $vendor->orders_count }} orders
                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success-subtle 
                                        text-success">
                                        {{ money($vendor->total_sales ?? 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0 fw-bold">Recent Orders</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.orders.index') }}"
                                class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Order ID</th>
                                    <th class="border-0">Customer</th>
                                    <th class="border-0">Vendor</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td class="fw-bold">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light 
                                                rounded-circle me-2 d-flex 
                                                align-items-center 
                                                justify-content-center">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            {{ $order->user?->name }}
                                        </div>
                                    </td>
                                    <td>{{ $order->seller->name }}</td>
                                    <td class="fw-bold">
                                        {{ money($order->total_amount) }}
                                    </td>
                                    <td>
                                        @php
                                        $statusClass = match($order->status) {
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'secondary'
                                        };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($order->status->value) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#"
                                                class="btn btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-outline-secondary"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-pencil me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-printer me-2"></i>Print
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Modal -->
<div class="modal fade" id="quickActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Quick Actions</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <a href=""
                        class="list-group-item list-group-item-action border-0">
                        <i class="bi bi-plus-circle text-primary me-3"></i>
                        Add New Vendor
                    </a>
                    <a href=""
                        class="list-group-item list-group-item-action border-0">
                        <i class="bi bi-box text-success me-3"></i>
                        Add New Product
                    </a>
                    <a href=""
                        class="list-group-item list-group-item-action border-0">
                        <i class="bi bi-hourglass text-warning me-3"></i>
                        View Pending Orders
                    </a>
                    <a href=""
                        class="list-group-item list-group-item-action border-0">
                        <i class="bi bi-graph-up text-info me-3"></i>
                        Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthly_revenue);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(item => item.revenue),
                borderColor: 'rgb(13, 110, 253)',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return "{{ currency() }}" + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

@if(false)
<div class="row d-none">
    <div class="col-12 mb-5">
        <div class="row row-cols-lg-3 row-cols-2 g-lg-5 g-2">
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Pending Orders</span>
                            <i data-feather="clock" class="text-warning"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $pending_orders }}</h3>
                        </div>
                        <a href="#"><small>View Orders</small> </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Delivered Orders</span>
                            <i data-feather="check-circle" class="text-success"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $delivered_orders }}</h3>
                        </div>
                        <a href="#"><small>View Orders</small> </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Cancelled Orders</span>
                            <i data-feather="x-circle" class="text-danger"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $cancelled_orders }}</h3>
                        </div>
                        <a href="#"><small>View Orders</small> </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Total Products</span>
                            <i data-feather="box" class="text-info"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $total_products }}</h3>
                        </div>
                        <a href="#"><small>Total products listed</small></a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Total Orders</span>
                            <i data-feather="shopping-cart" class="text-success"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $total_orders }}</h3>
                        </div>
                        <small>Orders received</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Total Sellers</span>
                            <i data-feather="users" class="text-primary"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $total_sellers }}</h3>
                        </div>
                        <small>Total earnings from sales</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Total Sales</span>
                            <span class="text-success text-xxl font-semibold">{{ currency() }}</span>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ money($total_sales) }}</h3>
                        </div>
                        <small>Total earnings from sales</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Total Commission</span>
                            <span class="text-success text-xxl font-semibold">{{ currency() }}</span>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ money($total_commission) }}</h3>
                        </div>
                        <small>Total commission from sales</small>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Total Customers</span>
                            <i data-feather="users" class="text-primary"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $total_customers }}</h3>
                        </div>
                        <small>Registered customers</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection