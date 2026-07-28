<?php
$pageTitle = "Seller Dashboard | {$seller->business_name}";
?>
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@if(!$seller->profile_completed)
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
                    <span class="small fw-semibold text-muted">Pending Orders</span>
                    <div class="icon-bg-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="clock" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $pending_orders }}</h3>
                <a href="{{ route('seller.orders.cancelled') }}" class="small text-decoration-none">View Orders</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Delivered Orders</span>
                    <div class="icon-bg-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="check-circle" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $delivered_orders }}</h3>
                <a href="{{ route('seller.orders.delivered') }}" class="small text-decoration-none">View Orders</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Cancelled Orders</span>
                    <div class="icon-bg-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="x-circle" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $cancelled_orders }}</h3>
                <a href="{{ route('seller.orders.cancelled') }}" class="small text-decoration-none">View Orders</a>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Expenses</span>
                    <div class="icon-bg-secondary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="dollar-sign" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($total_expense) }}</h3>
                <small class="text-muted">Total expenses</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Stock Value</span>
                    <div class="icon-bg-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="package" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($total_stock_product_amount) }}</h3>
                <small class="text-muted">Total worth of products</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Total Profit</span>
                    <div class="icon-bg-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="trending-up" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($profit) }}</h3>
                <small class="text-muted">Total profit from sales</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Total Commission</span>
                    <div class="icon-bg-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; border-radius: 10px;">
                        <i data-feather="percent" style="width: 18px; height: 18px;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ money($total_commission) }}</h3>
                <small class="text-muted">Total commission from sales</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-7 d-flex flex-column">
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
    <div class="col-md-5 d-flex flex-column">
        <div class="card border-0 shadow-sm flex-fill" style="border-radius: 12px;">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i data-feather="award" class="text-primary" style="width: 18px; height: 18px;"></i>
                    Top Selling Products
                </h5>
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
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body p-4">
        <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
            <i data-feather="clipboard" class="text-primary" style="width: 18px; height: 18px;"></i>
            Latest Orders
        </h5>
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
                    @foreach ($latest_orders as $order)
                        <tr>
                            <td class="fw-medium">{{ $order->invoice_id }}</td>
                            <td>{{ $order->user->name ?? '' }}</td>
                            <td class="fw-medium">{{ money($order->total) }}</td>
                            <td>
                                @if ($order->status->label() === 'pending')
                                    <span class="badge rounded-pill px-3 badge-soft-warning">Pending</span>
                                @elseif ($order->status->label() === 'shipped')
                                    <span class="badge rounded-pill px-3 badge-soft-primary">Shipped</span>
                                @elseif ($order->status->label() === 'cancelled')
                                    <span class="badge rounded-pill px-3 badge-soft-danger">Cancelled</span>
                                @elseif ($order->status->label() === 'delivered')
                                    <span class="badge rounded-pill px-3 badge-soft-success">Delivered</span>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chartData = <?php echo json_encode($chartData); ?>;

    const ctx = document.getElementById('salesOrderChart').getContext('2d');
    const salesOrderChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                    label: 'Orders',
                    data: chartData.orders,
                    borderColor: '#F85606',
                    backgroundColor: 'rgba(248, 86, 6, 0.08)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#F85606',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                },
                {
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
                },
                {
                    label: 'Profit',
                    data: chartData.profits,
                    borderColor: '#1D8A45',
                    backgroundColor: 'rgba(29, 138, 69, 0.08)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#1D8A45',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        usePointStyle: true,
                        boxWidth: 6,
                        font: { size: 12 }
                    }
                }
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        callback: function(value) {
                            return '৳' + value;
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
