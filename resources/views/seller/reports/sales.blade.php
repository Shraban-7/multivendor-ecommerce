@extends('seller.layouts.app')
@section('title', 'Sales Reports')

@push('styles')
    <style>
        :root {
            --bs-primary: #007bff;
            --bs-success: #28a745;
            --bs-danger: #dc3545;
            --card-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-1px);
        }

        .kpi-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .chart-placeholder {
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f6f9;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
            color: #6c757d;
        }

        .progress-bar-label {
            font-size: 0.75rem;
            color: #495057;
        }
    </style>
@endpush
@section('content')

    <div>
        <header>
            <div class="row align-items-center mb-4">
                <!-- Title + Breadcrumb -->
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1">Sales Report</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item text-muted">Reports</li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">Sales Report</li>
                        </ol>
                    </nav>
                </div>

                <!-- Filter Section -->
                <div class="col-md-6">
                    <form method="GET" class="row g-2 justify-content-end">

                        <!-- Filter Dropdown -->
                        <div class="col-md-4 col-sm-6">
                            <select name="range" class="form-select form-select-sm"
                                onchange="toggleCustomDates(this.value)">
                                <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly
                                </option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <!-- Custom Date Range -->
                        <div class="col-md-6 col-sm-6" id="customDateRange"
                            style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
                            <div class="input-group input-group-sm">
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    class="form-control">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                            </div>
                        </div>

                        <!-- Filter Button -->
                        <div class="col-md-2 col-sm-12 d-flex align-items-end">
                            <button class="btn btn-primary btn-sm w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </header>

        <div class="row mb-4 g-3">

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100 border-start border-primary border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Total Revenue</span>
                            <h5 class="kpi-value text-primary mb-0">{{ money($total_revenue) }}</h5>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x text-primary opacity-50"></i>
                    </div>
                    <small class="{{ $revenue_growth >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas {{ $revenue_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ $revenue_growth }}%
                    </small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100 border-start border-info border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Orders</span>
                            <h5 class="kpi-value text-info mb-0">{{ $total_order }}</h5>
                        </div>
                        <i class="fas fa-box fa-2x text-info opacity-50"></i>
                    </div>
                    <small class="{{ $order_growth >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas {{ $order_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ $order_growth }}%
                    </small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100 border-start border-warning border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Avg Order Value</span>
                            <h5 class="kpi-value text-warning mb-0">{{ money($avg_order) }}</h5>
                        </div>
                        <i class="fas fa-receipt fa-2x text-warning opacity-50"></i>
                    </div>
                    <small class="{{ $avg_order_growth >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas {{ $avg_order_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ $avg_order_growth }}%
                    </small>

                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100 border-start border-success border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Best Seller</span>
                            @php
                                if ($bestSelling) {
                                    $productName = $bestSelling->product->name;
                                    $unitsSold = $bestSelling->total_qty;
                                } else {
                                    $productName = null;
                                    $unitsSold = 0;
                                }
                            @endphp
                            <h6 class="fw-bold mb-0 text-success">{{ $productName }}</h6>
                            <p class="mb-0 small text-muted">{{ $unitsSold }} units</p>
                        </div>
                        <i class="fas fa-award fa-2x text-success opacity-50"></i>
                    </div>
                    <small class="text-muted mt-2">Highest revenue driver</small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100 border-start border-secondary border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Growth %</span>
                            <h5 class="kpi-value text-secondary mb-0">
                                {{ $avg_order_growth > 0 ? '+' : '' }}{{ $avg_order_growth }}%
                            </h5>
                        </div>
                        <i class="fas fa-chart-line fa-2x text-secondary opacity-50"></i>
                    </div>
                    <small class="text-success fw-semibold mt-2">vs previous {{ request('range') }}</small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100 border-start border-danger border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Refund Rate</span>
                            <h5 class="kpi-value text-danger mb-0">{{ $refund_rate }}%</h5>
                        </div>
                        <i class="fas fa-undo fa-2x text-danger opacity-50"></i>
                    </div>
                    <small class="text-{{ $refundRateChange >= 0 ? 'success' : 'danger' }} fw-semibold mt-2">
                        <i class="fas fa-arrow-{{ $refundRateChange >= 0 ? 'up' : 'down' }} me-1"></i>
                        {{ $refundRateChange }} pts
                    </small>
                </div>
            </div>

        </div>
        <div class="row g-4 mb-5">
            <div class="col-lg-12">
                <div class="card p-4">
                    <h5 class="card-title fw-bold text-dark mb-3">Revenue Trend Over Time</h5>

                    {{-- Filter Nav --}}
                    <div class="d-flex justify-content-start mb-3">
                        <ul class="nav nav-pills nav-pills-sm">
                            @php $range = request('range', 'daily'); @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'daily' ? 'active' : '' }}">Day</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'weekly' ? 'active' : '' }}">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'monthly' ? 'active' : '' }}">Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'yearly' ? 'active' : '' }}">Yearly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'custom' ? 'active' : '' }}">Custom</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Chart Canvas --}}
                    <canvas id="revenueTrendChart" height="100"></canvas>

                    {{-- Alert for Growth --}}
                    <p class="alert alert-light alert-success p-2 mt-3 mb-0 text-center fw-semibold">
                        <i class="fas fa-check-circle me-1"></i>
                        Sales are {{ $revenue_growth >= 0 ? 'up' : 'down' }}
                        <span
                            class="{{ $revenue_growth >= 0 ? 'text-success' : 'text-danger' }}">{{ abs($revenue_growth) }}%</span>
                        vs. previous period.
                    </p>
                </div>
            </div>
        </div>


        <div class="row g-4 mb-5">

            <div class="col-lg-6">
                <div class="card p-4 h-100">
                    <h5 class="card-title fw-bold text-dark mb-3">Product Category Performance</h5>

                    <div class="row">
                        <div class="col-md-5 d-flex justify-content-center align-items-center">
                            <div style="width:100%; min-height: 200px;">
                                <canvas id="categoryPieChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <p class="fw-semibold text-muted small mt-3 mt-md-0">Revenue & Order Breakdown:</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Category</th>
                                            <th class="text-end">Sales</th>
                                            <th class="text-end">Orders</th>
                                            <th class="text-end">Growth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categoryData as $data)
                                            <tr class="{{ $data['growth'] >= 0 ? 'fw-semibold' : '' }}">
                                                <td>{{ $data['category'] }}</td>
                                                <td class="text-end">{{ money($data['sales']) }}</td>
                                                <td class="text-end">{{ $data['orders'] }}</td>
                                                <td
                                                    class="text-end {{ $data['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $data['growth'] >= 0 ? '+' : '' }}{{ $data['growth'] }}%
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


            <div class="col-lg-6">
                <div class="card p-4 h-100">
                    <h5 class="card-title fw-bold text-dark mb-3">Sales Channel Contribution</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Channel</th>
                                    <th class="text-end">Revenue</th>
                                    <th class="text-end">Orders</th>
                                    <th class="text-end">Contribution %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($channelData as $data)
                                    <tr class="{{ $data['isTop'] ? 'fw-semibold' : '' }}">
                                        <td>
                                            {{ $data['channel'] }}
                                            @if ($data['isTop'])
                                                <span class="badge bg-primary ms-2">Top Source</span>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ money($data['revenue']) }}</td>
                                        <td class="text-end">{{ $data['orders'] }}</td>
                                        <td class="text-end">{{ $data['contribution'] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <p class="fw-semibold text-muted small mb-1">Total Orders Distribution:</p>
                        <div class="progress" style="height: 15px;">
                            @foreach ($channelData as $data)
                                <div class="progress-bar {{ $data['isTop'] ? 'bg-primary' : 'bg-info' }}"
                                    role="progressbar" style="width: {{ $data['contribution'] }}%"
                                    aria-valuenow="{{ $data['contribution'] }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $data['channel'] }} ({{ $data['contribution'] }}%)
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-7">
                <div class="card p-4 h-100">
                    <h5 class="card-title fw-bold text-dark mb-3">Top-Selling Products by Revenue</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Units Sold</th>
                                    <th class="text-end">Total Sales</th>
                                    <th class="text-end">Profit Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productStats as $prod)
                                    <tr class="fw-semibold">
                                        <td>{{ $prod['product_name'] }}</td>
                                        <td class="text-end">{{ money($prod['price']) }}</td>
                                        <td class="text-end">{{ $prod['units_sold'] }}</td>
                                        <td class="text-end text-success">{{ money($prod['total_sales']) }}</td>
                                        <td class="text-end text-primary">{{ $prod['profit_margin'] }}%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="p-1">
                                            <div class="progress-bar-label">Relative Sales: {{ $prod['relative_sales'] }}%
                                            </div>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-success"
                                                    style="width: {{ $prod['relative_sales'] }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card p-4 h-100">
                    <h5 class="card-title fw-bold text-dark mb-3">Sales by Region (Orders)</h5>
                    <canvas id="regionChart" style="min-height: 200px;"></canvas>
                    <p class="mt-3 mb-0 small text-muted text-center">
                        Focus on regions with high order volume for targeted marketing campaigns.
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        function toggleCustomDates(value) {
            const custom = document.getElementById('customDateRange');
            custom.style.display = (value === 'custom') ? 'block' : 'none';
        }

        const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
        const revenueTrendChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenues),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
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
                                return '৳' + value; 
                            }
                        }
                    }
                }
            }
        });

        const categoryPieCtx= document.getElementById('categoryPieChart').getContext('2d');

        const categoryLabels = {!! json_encode($categoryData->pluck('category')) !!};
        const categoryRevenue = {!! json_encode($categoryData->pluck('sales')) !!};

        new Chart(categoryPieCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Revenue',
                    data: categoryRevenue,
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                        '#858796', '#fd7e14', '#20c997', '#6610f2', '#6f42c1'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        const ctxRegion = document.getElementById('regionChart').getContext('2d');
        new Chart(ctxRegion, {
            type: 'bar', // or 'pie', 'doughnut'
            data: {
                labels: @json($divisionLabels),
                datasets: [{
                    label: 'Orders',
                    data: @json($divisionOrders),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
