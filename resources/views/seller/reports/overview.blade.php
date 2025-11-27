@extends('seller.layouts.app')
@section('title', 'Report Overview')

@push('styles')
    <style>
        :root {
            --bs-primary: #007bff;
            --bs-success: #28a745;
            --bs-danger: #dc3545;
            --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.075);
        }

        body {
            background-color: #f8f9fa;
            /* Light gray background */
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .kpi-value {
            font-size: 1.75rem;
            /* Compact size */
            font-weight: 700;
        }

        .kpi-icon {
            font-size: 1.5rem;
            color: rgba(0, 0, 0, 0.1);
            /* Subtle icon coloring */
        }

        .chart-placeholder {
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f6f9;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
        }
    </style>
@endpush

@section('content')
    <div>
        <header>
            <div class="row align-items-center mb-4">
                <!-- Title + Breadcrumb -->
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1">Business Overview</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item text-muted">Reports</li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">Business Overview</li>
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
                                <option disabled selected>--select--</option>
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

        <div class="row mb-5 g-3">

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Total Sales</p>
                            <span class="kpi-value text-primary">{{ money($calculateMetrics['total_sales']) }}</span>
                        </div>
                        <i class="fas fa-shopping-bag kpi-icon text-primary-emphasis"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['sales_growth'] }}%">
                            </div>
                        </div>
                        <small class="text-success fw-semibold"><i
                                class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['sales_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Orders</p>
                            <span class="kpi-value text-info">{{ $calculateMetrics['total_orders'] }}</span>
                        </div>
                        <i class="fas fa-clipboard-list kpi-icon text-info-emphasis"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['orders_growth'] }}%">
                            </div>
                        </div>
                        <small class="text-success fw-semibold"><i
                                class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['orders_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Net Profit</p>
                            <span class="kpi-value text-success">{{ money($calculateMetrics['net_profit']) }}</span>
                        </div>
                        <i class="fas fa-dollar-sign kpi-icon text-success-emphasis"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['profit_growth'] }}%">
                            </div>
                        </div>
                        <small class="text-success fw-semibold"><i
                                class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['profit_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Ret. Customers</p>
                            <span class="kpi-value text-warning">{{ number_format($quickFacts['returning_customers_percent'] ,2)}}%</span>
                        </div>
                        <i class="fas fa-users-viewfinder kpi-icon text-warning-emphasis"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">AOV</p>
                            <span class="kpi-value text-secondary">{{ money($calculateMetrics['aov']) }}</span>
                        </div>
                        <i class="fas fa-basket-shopping kpi-icon text-secondary-emphasis"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['aov_growth'] }}%">
                            </div>
                        </div>
                        <small class="text-success fw-semibold"><i
                                class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['aov_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Total Stock</p>
                            <span class="kpi-value text-dark">{{ $calculateMetrics['total_stock'] }}</span>
                        </div>
                        <i class="fas fa-boxes-stacked kpi-icon text-dark-emphasis"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-info" style="width: {{ $calculateMetrics['stock_growth'] }}%">
                            </div>
                        </div>
                        <small class="text-info fw-semibold">Stable</small>
                        <small class="text-muted small">inventory level</small>
                    </div>
                </div>
            </div>

        </div>
        <div class="row mb-5 g-4">
            <div class="col-lg-8">
                <div class="card p-4 h-100">
                    <h5 class="card-title fw-bold text-primary mb-3">Revenue & Order Trends</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p class="fw-semibold text-muted mb-1">{{ request('range') }} Revenue Trend</p>
                            <div class="chart-placeholder">
                                <canvas id="revenueTrend"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-semibold text-muted mb-1">Orders vs. Returns</p>
                            <div class="chart-placeholder">
                                <canvas id="ordersReturns"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 h-100 bg-white">
                    <h5 class="card-title fw-bold mb-3"><i class="fas fa-bolt me-2 text-warning"></i>Quick Facts</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Total Orders:
                            <span class="fw-bold text-primary">{{ $quickFacts['total_orders'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Refund Rate:
                            <span class="fw-bold text-danger">{{ $quickFacts['refund_rate'] }}%</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Best Sales Day:
                            <span class="fw-bold text-success">{{ $quickFacts['best_sales_day'] ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="row g-4">

            <div class="col-lg-6">
                <div class="card p-4 h-100">
                    <h5 class="card-title fw-bold mb-3"><i class="fas fa-lightbulb me-2 text-info"></i> Smart Highlights
                    </h5>
                    <div class="list-group list-group-flush">
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-success-subtle text-success me-3 p-2"><i
                                    class="fas fa-chart-line"></i></span>
                            <span class="fw-semibold">Revenue grew <span class="text-success">8%</span> last week.</span>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-primary-subtle text-primary me-3 p-2"><i
                                    class="fas fa-shirt"></i></span>
                            <span class="fw-semibold">Apparel category contributed <span class="text-primary">32%</span>
                                of total sales.</span>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-warning-subtle text-warning me-3 p-2"><i
                                    class="fas fa-repeat"></i></span>
                            <span class="fw-semibold">Returning customers spent <span class="text-warning">18%</span>
                                more.</span>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-danger-subtle text-danger me-3 p-2"><i
                                    class="fas fa-boxes-packing"></i></span>
                            <span class="fw-semibold">High stock alert in Electronics. Review turnover.</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card p-4 h-100">
                    <h5 class="card-title fw-bold mb-3"><i class="fas fa-star me-2 text-success"></i> Top Product Snapshot
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Units Sold</th>
                                    <th class="text-end">Sales</th>
                                    <th class="text-end">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topProducts as $product)
                                    <tr class="fw-semibold">
                                        <td>{{ $product['name'] }}</td>
                                        <td class="text-end">{{ $product['units_sold'] }}</td>
                                        <td class="text-end text-success">{{ money($product['sales']) }}</td>
                                        <td class="text-end">{{ $product['stock'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-12">
                <h5 class="fw-bold mb-3"><i class="fas fa-file-invoice me-2 text-secondary"></i> Recent Reports & Exports
                </h5>
                <div class="d-flex flex-wrap gap-3">

                    <div class="card p-3 shadow-sm" style="width: 18rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 fw-semibold">Weekly Performance Summary</p>
                            <a href="#" class="text-primary" title="Download"><i class="fas fa-download"></i></a>
                        </div>
                        <small class="text-muted">Generated Nov 15, 2025</small>
                    </div>

                    <div class="card p-3 shadow-sm" style="width: 18rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 fw-semibold">Sales vs Target - October</p>
                            <a href="#" class="text-primary" title="Export"><i class="fas fa-file-export"></i></a>
                        </div>
                        <small class="text-muted">Generated Nov 1, 2025</small>
                    </div>

                    <div class="card p-3 shadow-sm" style="width: 18rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 fw-semibold">Customer Segment Analysis</p>
                            <a href="#" class="text-primary" title="Export"><i class="fas fa-file-export"></i></a>
                        </div>
                        <small class="text-muted">Generated Nov 18, 2025</small>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleCustomDates(value) {
            const custom = document.getElementById('customDateRange');
            custom.style.display = (value === 'custom') ? 'block' : 'none';
        }

        const revenueCtx = document.getElementById('revenueTrend');

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['revenueTrend']['labels']),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chartData['revenueTrend']['values']),
                    borderWidth: 2
                }]
            }
        });

        const ordersCtx = document.getElementById('ordersReturns');

        new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: ['Orders', 'Returns'],
                datasets: [{
                    label: 'Orders vs Returns',
                    data: @json([$chartData['ordersReturns']['orders'] ?? 0, $chartData['ordersReturns']['returns'] ?? 0]),
                    backgroundColor: ['#4e73df', '#e74a3b'], 
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
  

    </script>
@endpush
