@extends('seller.layouts.app')
@section('title', 'Seller Dashboard')
@section('content')
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <form id="dateRangeForm" method="GET" action="{{ route('seller.dashboard') }}">
                <div class="d-flex mb-3">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="form-control me-3">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="form-control me-3">

                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-5">
            <div class="row row-cols-lg-4 row-cols-2 g-lg-5 g-2">
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
                                <span class="fw-semi-bold">Shipped Orders</span>
                                <i data-feather="truck" class="text-primary"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $shipped_orders }}</h3>
                            </div>
                            <a href="{{ route('seller.orders.shipped') }}"><small>View Orders</small> </a>
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
                                <span class="fw-semi-bold">Total Revenue</span>
                                <i data-feather="dollar-sign" class="text-success"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ money($total_revenue) }}</h3>
                            </div>
                            <small>Total earnings from sales</small>
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

    <div class="row mt-5">
        <!-- Revenue Analytics Chart with Date Range Filter -->
        <div class="col-7 d-flex flex-column">
            <div class="card flex-fill">
                <div class="card-body">
                    <h5 class="fw-semi-bold">Sales & Order Analytics</h5>
                    <canvas id="salesOrderChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="col-5 d-flex flex-column">
            <div class="card flex-fill">
                <div class="card-body">
                    <h5 class="fw-semi-bold">Top Selling Products</h5>
                    <ul class="list-group">
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

    <div class="row mt-5">
        <!-- Latest Orders Section -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semi-bold">Latest Orders</h5>
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
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->fullname }}</td>
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
                                        <a href="{{ route('seller.orders.details', $order->id) }}"
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


    @push('scripts')
        <script>
            const chartData = <?php echo json_encode($chartData); ?>

            const ctx = document.getElementById('salesOrderChart').getContext('2d');
            const salesOrderChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Orders',
                            data: chartData.orders,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Revenue',
                            data: chartData.sales,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
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
