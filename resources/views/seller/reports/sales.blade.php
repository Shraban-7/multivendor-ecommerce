@extends('seller.layouts.app')
@section('title', 'Sales Reports')

@section('content')
    <div>
        <header>
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1 text-dark">Sales Report</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item text-muted">Reports</li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">Sales Report</li>
                        </ol>
                    </nav>
                </div>

                <div class="col-md-6">
                    <form method="GET" class="row g-2 justify-content-end">
                        <div class="col-md-4 col-sm-6">
                            <select name="range" class="form-select form-select-sm" onchange="toggleCustomDates(this.value)">
                                <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6" id="customDateRange" style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
                            <div class="input-group input-group-sm">
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12 d-flex align-items-end">
                            <button class="btn btn-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </header>

        <div class="row mb-4 g-3">
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #F85606;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Total Revenue</span>
                            <h5 class="fw-bold mb-0 text-primary">{{ money($total_revenue) }}</h5>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-25"></i>
                    </div>
                    <small class="{{ $revenue_growth >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas {{ $revenue_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{ $revenue_growth }}%
                    </small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #0ea5e9;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Orders</span>
                            <h5 class="fw-bold mb-0 text-info">{{ $total_order }}</h5>
                        </div>
                        <i class="fas fa-box fa-2x opacity-25"></i>
                    </div>
                    <small class="{{ $order_growth >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas {{ $order_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{ $order_growth }}%
                    </small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #B7791A;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Avg Order Value</span>
                            <h5 class="fw-bold mb-0 text-warning">{{ money($avg_order) }}</h5>
                        </div>
                        <i class="fas fa-receipt fa-2x opacity-25"></i>
                    </div>
                    <small class="{{ $avg_order_growth >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas {{ $avg_order_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{ $avg_order_growth }}%
                    </small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #1D8A45;">
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
                        <i class="fas fa-award fa-2x opacity-25"></i>
                    </div>
                    <small class="text-muted mt-2">Highest revenue driver</small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #637381;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Growth %</span>
                            <h5 class="fw-bold mb-0 text-secondary">{{ $avg_order_growth > 0 ? '+' : '' }}{{ $avg_order_growth }}%</h5>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-25"></i>
                    </div>
                    <small class="text-success fw-semibold mt-2">vs previous {{ request('range') }}</small>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #D93025;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small">Refund Rate</span>
                            <h5 class="fw-bold mb-0 text-danger">{{ $refund_rate }}%</h5>
                        </div>
                        <i class="fas fa-undo fa-2x opacity-25"></i>
                    </div>
                    <small class="fw-semibold mt-2 {{ $refundRateChange >= 0 ? 'text-success' : 'text-danger' }}">
                        <i class="fas fa-arrow-{{ $refundRateChange >= 0 ? 'up' : 'down' }} me-1"></i> {{ $refundRateChange }} pts
                    </small>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Revenue Trend Over Time</h5>

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

                    <canvas id="revenueTrendChart" height="100"></canvas>

                    <p class="alert alert-light p-2 mt-3 mb-0 text-center fw-semibold">
                        <i class="fas fa-check-circle me-1"></i>
                        Sales are {{ $revenue_growth >= 0 ? 'up' : 'down' }}
                        <span class="{{ $revenue_growth >= 0 ? 'text-success' : 'text-danger' }}">{{ abs($revenue_growth) }}%</span>
                        vs. previous period.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Product Category Performance</h5>
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
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="small fw-semibold text-muted">Category</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Sales</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Orders</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Growth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categoryData as $data)
                                            <tr class="{{ $data['growth'] >= 0 ? 'fw-semibold' : '' }}">
                                                <td>{{ $data['category'] }}</td>
                                                <td class="text-end">{{ money($data['sales']) }}</td>
                                                <td class="text-end">{{ $data['orders'] }}</td>
                                                <td class="text-end {{ $data['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
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
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Sales Channel Contribution</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="small fw-semibold text-muted">Channel</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Revenue</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Orders</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Contribution %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($channelData as $data)
                                    <tr class="{{ $data['isTop'] ? 'fw-semibold' : '' }}">
                                        <td>
                                            {{ $data['channel'] }}
                                            @if ($data['isTop'])
                                                <span class="badge badge-soft-primary ms-2">Top Source</span>
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
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Top-Selling Products by Revenue</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="small fw-semibold text-muted">Product</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Price</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Units Sold</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Total Sales</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Profit Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productStats as $prod)
                                    <tr>
                                        <td class="fw-semibold">{{ $prod['product_name'] }}</td>
                                        <td class="text-end">{{ money($prod['price']) }}</td>
                                        <td class="text-end">{{ $prod['units_sold'] }}</td>
                                        <td class="text-end text-success fw-semibold">{{ money($prod['total_sales']) }}</td>
                                        <td class="text-end text-primary fw-semibold">{{ $prod['profit_margin'] }}%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="p-1">
                                            <div class="small text-muted">Relative Sales: {{ $prod['relative_sales'] }}%</div>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-success" style="width: {{ $prod['relative_sales'] }}%"></div>
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
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Sales by Region (Orders)</h5>
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
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenues),
                    backgroundColor: 'rgba(248, 86, 6, 0.1)',
                    borderColor: '#F85606',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        const categoryPieCtx = document.getElementById('categoryPieChart').getContext('2d');
        const categoryLabels = {!! json_encode($categoryData->pluck('category')) !!};
        const categoryRevenue = {!! json_encode($categoryData->pluck('sales')) !!};
        new Chart(categoryPieCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryRevenue,
                    backgroundColor: ['#F85606', '#1D8A45', '#0ea5e9', '#B7791A', '#D93025', '#637381', '#fd7e14', '#20c997', '#6610f2', '#6f42c1'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 20, padding: 15 } }
                }
            }
        });

        const ctxRegion = document.getElementById('regionChart').getContext('2d');
        new Chart(ctxRegion, {
            type: 'bar',
            data: {
                labels: @json($divisionLabels),
                datasets: [{
                    label: 'Orders',
                    data: @json($divisionOrders),
                    backgroundColor: 'rgba(248, 86, 6, 0.6)',
                    borderColor: '#F85606',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush
