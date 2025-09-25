<?php
    $pageTitle =  "Seller Dashboard | {$seller->business_name}"; 
?>
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

<div class="row">
    <div class="col-md-6 mb-2 d-flex align-items-end">
        <h4 class="mb-0 fw-bold fs-3">{{ $seller->business_name }}</h4>
    </div>
    <div class="col-md-6 mb-2">
        <div class="d-flex justify-content-end">
            <form id="dateRangeForm" method="GET" action="{{ route('seller.dashboard') }}" class="w-100 w-md-auto">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-end gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="form-control form-control-sm w-100 w-md-auto">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="form-control form-control-sm w-100 w-md-auto">
                    <button type="submit" class="btn btn-white border btn-sm w-100 w-md-auto">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <div class="row row-cols-lg-4 row-cols-2 g-lg-3 g-2">
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
                            <span class="fw-semi-bold">Pending Orders</span>
                            <i data-feather="clock" class="text-warning"></i>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ $pending_orders }}</h3>
                        </div>
                        <a href="{{ route('seller.orders.cancelled') }}"><small>View Orders</small> </a>
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
                        <a href="{{ route('seller.orders.delivered') }}"><small>View Orders</small> </a>
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
                        <a href="{{ route('seller.orders.cancelled') }}"><small>View Orders</small> </a>
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
                        <a href="{{ route('seller.products.index') }}"><small>Total products listed</small></a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Stock Value</span>
                            <span class="text-success text-xxl font-semibold">{{ currency() }}</span>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ money($total_stock_product_amount) }}</h3>
                        </div>
                        <small>Total worth of products</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semi-bold">Total Profit</span>
                            <span class="text-success text-xxl font-semibold">{{ currency() }}</span>
                        </div>
                        <div class="mt-2 mb-2">
                            <h3 class="fw-bold mb-0">{{ money($profit) }}</h3>
                        </div>
                        <small>Total profit from sales</small>
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
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Analytics Chart with Date Range Filter -->
    <div class="col-md-7 d-flex flex-column mb-3">
        <div class="card flex-fill">
            <div class="card-body">
                <h5 class="fw-semi-bold">Sales & Order Analytics</h5>
                <canvas id="salesOrderChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-md-5 d-flex flex-column mb-3">
        <div class="card flex-fill">
            <div class="card-body">
                <h5 class="fw-semi-bold">Top Selling Products</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($top_selling_products as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                width="40" height="40" class="rounded me-2" />
                            <span>{{ $product->name }}</span>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $product->sales_count }} Sold</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Latest Orders Section -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-semi-bold">Latest Orders</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latest_orders as $order)
                            <tr>
                                <td>{{ $order->invoice_id }}</td>
                                <td>{{ $order->user->name ?? '' }}</td>
                                <td>{{ money($order->total) }}</td>
                                <td>
                                    @if ($order->status->label() === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif ($order->status->label() === 'shipped')
                                    <span class="badge bg-primary">Shipped</span>
                                    @elseif ($order->status->label() === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @elseif ($order->status->label() === 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                    @endif
                                </td>
                                <td>{{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('seller.orders.details', $order->invoice_id) }}"
                                        class="btn btn-info btn-sm">View</a>
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
                    borderColor: 'rgba(0, 0, 0, 1)',
                    backgroundColor: 'rgba(0, 0, 0, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Sales',
                    data: chartData.sales,
                    borderColor: 'rgba(0, 123, 255, 1)',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Profit',
                    data: chartData.profits,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    tension: 0.4,
                    fill: true
                }
            ]

        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '৳' + value;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush

@endsection