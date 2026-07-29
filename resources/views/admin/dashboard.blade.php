@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')

@if($pending_sellers_count)
<div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-start gap-3 alert-dismissible fade show shadow-sm" role="alert">
    <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-triangle text-xl me-2 text-feedback-warning"></i>
        <h5 class="mb-0 text-ink">Pending Vendor Applications</h5>
    </div>
    <p class="mb-1 text-ink">
        You have <strong>{{ $pending_sellers_count }}</strong> new vendor{{ $pending_sellers_count !== 1 ? 's' : '' }} waiting for approval.
        Review them to activate their shops.
    </p>

    <div class="mt-2">
        <a href="" class="btn btn-warning btn-sm me-2">
            <i class="fas fa-eye me-1"></i> View Applications
        </a>
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="alert">
            <i class="fas fa-times"></i> Dismiss
        </button>
    </div>

    @if($pending_sellers->isNotEmpty())
    <hr class="my-2">
    <small class="block text-ink-tertiary">
        <strong>New:</strong>
        @foreach($pending_sellers->take(3) as $shop)
        {{ $shop->name }}@if(!$loop->last), @endif
        @endforeach
        @if($pending_sellers_count > 3){{ '...' }}@endif
    </small>
    @endif
</div>
@endif

<div class="mt-2">
    <div class="grid grid-cols-1 mb-2">
        <div class="col flex items-end">
            <h3 class="font-bold mb-0">Dashboard</h3>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#quickActionsModal">
                <i class="bi bi-lightning-fill me-2"></i>Quick Actions
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-3 mb-4">
        <div class="xl:col-span-1 md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm h-full">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="bg-brand-deep bg-opacity-10 
                                text-brand rounded p-3">
                                <i class="bi bi-currency-dollar text-xl"></i>
                            </div>
                        </div>
                        <div class="grow ms-3">
                            <h6 class="text-ink-tertiary mb-1">Total Revenue</h6>
                            <h3 class="mb-0 font-bold">
                                {{ money($stats['total_revenue']) }}
                            </h3>
                            <!-- <small class="text-feedback-success">
                                <i class="bi bi-arrow-up"></i> 12.5% from last month
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm h-full">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="bg-feedback-success bg-opacity-10 
                                text-feedback-success rounded p-3">
                                <i class="bi bi-cart-check text-xl"></i>
                            </div>
                        </div>
                        <div class="grow ms-3">
                            <h6 class="text-ink-tertiary mb-1">Total Orders</h6>
                            <h3 class="mb-0 font-bold">
                                {{ number_format($stats['total_orders']) }}
                            </h3>
                            <!-- <small class="text-ink-tertiary">
                                {{ $stats['pending_orders'] }} pending
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm h-full">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="bg-feedback-warning bg-opacity-10 
                                text-feedback-warning rounded p-3">
                                <i class="bi bi-shop text-xl"></i>
                            </div>
                        </div>
                        <div class="grow ms-3">
                            <h6 class="text-ink-tertiary mb-1">Active Vendors</h6>
                            <h3 class="mb-0 font-bold">
                                {{ number_format($stats['total_vendors']) }}
                            </h3>
                            <!-- <small class="text-feedback-success">
                                <i class="bi bi-arrow-up"></i> 3 new this week
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm h-full">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <div class="bg-feedback-info bg-opacity-10 
                                text-feedback-info rounded p-3">
                                <i class="bi bi-people text-xl"></i>
                            </div>
                        </div>
                        <div class="grow ms-3">
                            <h6 class="text-ink-tertiary mb-1">Total Customers</h6>
                            <h3 class="mb-0 font-bold">
                                {{ number_format($stats['total_customers']) }}
                            </h3>
                            <!-- <small class="text-feedback-success">
                                <i class="bi bi-arrow-up"></i> 8.2% growth
                            </small> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 mb-4">
        <!-- Revenue Chart -->
        <div class="xl:col-span-1-8">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm h-full">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-0 py-3">
                    <div class="grid grid-cols-1 items-center">
                        <div class="col">
                            <h5 class="mb-0 font-bold">Revenue Overview</h5>
                        </div>
                        <div class="col-auto">
                            <select class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                <option>Last 6 Months</option>
                                <option>Last Year</option>
                                <option>All Time</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <canvas id="revenueChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Vendors -->
        <div class="xl:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm h-full">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-0 py-3">
                    <h5 class="mb-0 font-bold">Top Vendors</h5>
                </div>
                <div class="p-5">
                    <div class="flex flex-col ">
                        @foreach($top_vendors as $index => $vendor)
                        <div class="flex items-center px-0 py-2 border-b border-border border-0 px-0 py-3">
                            <div class="flex items-center">
                                <div class="shrink-0">
                                    <div class="bg-surface-muted rounded-full 
                                        flex items-center 
                                        justify-center"
                                        style="width: 40px; height: 40px;">
                                        <span class="font-bold text-brand">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                                <div class="grow ms-3">
                                    <h6 class="mb-0">{{ $vendor->name }}</h6>
                                    <small class="text-ink-tertiary">
                                        {{ $vendor->orders_count }} orders
                                    </small>
                                </div>
                                <div class="shrink-0">
                                    <span class="badge bg-emerald-50 
                                        text-feedback-success">
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
    <div class="grid grid-cols-1 gap-3">
        <div class="col-span-full">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-0 py-3">
                    <div class="grid grid-cols-1 items-center">
                        <div class="col">
                            <h5 class="mb-0 font-bold">Recent Orders</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.orders.index') }}"
                                class="btn btn-outline-primary btn-sm">
                                View All
                            </a>
                        </div>
                    </div>
                </div>
                <div class="p-5 p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse table-hover mb-0">
                            <thead class="bg-surface-muted">
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
                                    <td class="font-bold">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="avatar-sm bg-surface-muted 
                                                rounded-full me-2 flex 
                                                items-center 
                                                justify-center">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            {{ $order->user?->name }}
                                        </div>
                                    </td>
                                    <td>{{ $order->seller->name }}</td>
                                    <td class="font-bold">
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
                                            <button class="btn btn-light"
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
                <h5 class="modal-title font-bold">Quick Actions</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="flex flex-col ">
                    <a href=""
                        class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0">
                        <i class="bi bi-plus-circle text-brand me-3"></i>
                        Add New Vendor
                    </a>
                    <a href=""
                        class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0">
                        <i class="bi bi-box text-feedback-success me-3"></i>
                        Add New Product
                    </a>
                    <a href=""
                        class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0">
                        <i class="bi bi-hourglass text-feedback-warning me-3"></i>
                        View Pending Orders
                    </a>
                    <a href=""
                        class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0">
                        <i class="bi bi-graph-up text-feedback-info me-3"></i>
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
<div class="grid grid-cols-1 d-none">
    <div class="col-span-full mb-5">
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 lg:gap-5">
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Pending Orders</span>
                            <i data-feather="clock" class="text-feedback-warning"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ $pending_orders }}</h3>
                        </div>
                        <a href="#"><small>View Orders</small> </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Delivered Orders</span>
                            <i data-feather="check-circle" class="text-feedback-success"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ $delivered_orders }}</h3>
                        </div>
                        <a href="#"><small>View Orders</small> </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Cancelled Orders</span>
                            <i data-feather="x-circle" class="text-feedback-danger"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ $cancelled_orders }}</h3>
                        </div>
                        <a href="#"><small>View Orders</small> </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Products</span>
                            <i data-feather="box" class="text-feedback-info"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ $total_products }}</h3>
                        </div>
                        <a href="#"><small>Total products listed</small></a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Orders</span>
                            <i data-feather="shopping-cart" class="text-feedback-success"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ $total_orders }}</h3>
                        </div>
                        <small>Orders received</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Sellers</span>
                            <i data-feather="users" class="text-brand"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ $total_sellers }}</h3>
                        </div>
                        <small>Total earnings from sales</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Sales</span>
                            <span class="text-feedback-success text-xxl font-semibold">{{ currency() }}</span>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ money($total_sales) }}</h3>
                        </div>
                        <small>Total earnings from sales</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Commission</span>
                            <span class="text-feedback-success text-xxl font-semibold">{{ currency() }}</span>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ money($total_commission) }}</h3>
                        </div>
                        <small>Total commission from sales</small>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Customers</span>
                            <i data-feather="users" class="text-brand"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="font-bold mb-0">{{ $total_customers }}</h3>
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