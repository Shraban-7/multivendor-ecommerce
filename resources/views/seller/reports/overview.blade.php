@extends('seller.layouts.app')
@section('title', 'Report Overview')

@section('content')
    <div>
        <header>
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1 text-dark">Business Overview</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item text-muted">Reports</li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">Business Overview</li>
                        </ol>
                    </nav>
                </div>

                <div class="col-md-6">
                    <form method="GET" class="row g-2 justify-content-end">
                        <div class="col-md-4 col-sm-6">
                            <select name="range" class="form-select form-select-sm"
                                onchange="toggleCustomDates(this.value)">
                                <option disabled selected>--select--</option>
                                <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <div class="col-md-6 col-sm-6" id="customDateRange"
                            style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
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

        <div class="row mb-5 g-3">
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Total Sales</p>
                            <span class="fw-bold fs-4 text-primary">{{ money($calculateMetrics['total_sales']) }}</span>
                        </div>
                        <i class="fas fa-shopping-bag opacity-25 fs-4"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['sales_growth'] }}%"></div>
                        </div>
                        <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['sales_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Orders</p>
                            <span class="fw-bold fs-4 text-info">{{ $calculateMetrics['total_orders'] }}</span>
                        </div>
                        <i class="fas fa-clipboard-list opacity-25 fs-4"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['orders_growth'] }}%"></div>
                        </div>
                        <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['orders_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Net Profit</p>
                            <span class="fw-bold fs-4 text-success">{{ money($calculateMetrics['net_profit']) }}</span>
                        </div>
                        <i class="fas fa-dollar-sign opacity-25 fs-4"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['profit_growth'] }}%"></div>
                        </div>
                        <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['profit_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Ret. Customers</p>
                            <span class="fw-bold fs-4 text-warning">{{ number_format($quickFacts['returning_customers_percent'], 2)}}%</span>
                        </div>
                        <i class="fas fa-users-viewfinder opacity-25 fs-4"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">AOV</p>
                            <span class="fw-bold fs-4 text-secondary">{{ money($calculateMetrics['aov']) }}</span>
                        </div>
                        <i class="fas fa-basket-shopping opacity-25 fs-4"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-success" style="width: {{ $calculateMetrics['aov_growth'] }}%"></div>
                        </div>
                        <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>{{ $calculateMetrics['aov_growth'] }}%</small>
                        <small class="text-muted small">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted text-uppercase mb-1 small fw-semibold">Total Stock</p>
                            <span class="fw-bold fs-4 text-dark">{{ $calculateMetrics['total_stock'] }}</span>
                        </div>
                        <i class="fas fa-boxes-stacked opacity-25 fs-4"></i>
                    </div>
                    <div class="mt-2">
                        <div class="progress mb-1" style="height: 3px;">
                            <div class="progress-bar bg-info" style="width: {{ $calculateMetrics['stock_growth'] }}%"></div>
                        </div>
                        <small class="text-info fw-semibold">Stable</small>
                        <small class="text-muted small">inventory level</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5 g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Revenue & Order Trends</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p class="fw-semibold text-muted mb-1">{{ request('range') }} Revenue Trend</p>
                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center" style="min-height: 250px;">
                                <canvas id="revenueTrend"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-semibold text-muted mb-1">Orders vs. Returns</p>
                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center" style="min-height: 250px;">
                                <canvas id="ordersReturns"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Quick Facts</h5>
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
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Smart Highlights</h5>
                    <div class="list-group list-group-flush">
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-success-subtle text-success me-3 p-2"><i class="fas fa-chart-line"></i></span>
                            <span class="fw-semibold">Revenue grew <span class="text-success">8%</span> last week.</span>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-primary-subtle text-primary me-3 p-2"><i class="fas fa-shirt"></i></span>
                            <span class="fw-semibold">Apparel category contributed <span class="text-primary">32%</span> of total sales.</span>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-warning-subtle text-warning me-3 p-2"><i class="fas fa-repeat"></i></span>
                            <span class="fw-semibold">Returning customers spent <span class="text-warning">18%</span> more.</span>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                            <span class="badge bg-danger-subtle text-danger me-3 p-2"><i class="fas fa-boxes-packing"></i></span>
                            <span class="fw-semibold">High stock alert in Electronics. Review turnover.</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Top Product Snapshot</h5>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="small fw-semibold text-muted">Product</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Units Sold</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Sales</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topProducts as $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $product['name'] }}</td>
                                        <td class="text-end">{{ $product['units_sold'] }}</td>
                                        <td class="text-end text-success fw-semibold">{{ money($product['sales']) }}</td>
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
                <h5 class="fw-bold mb-3">Recent Reports & Exports</h5>
                <div class="d-flex flex-wrap gap-3">
                    <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; width: 18rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 fw-semibold">Weekly Performance Summary</p>
                            <a href="#" class="text-primary" title="Download"><i class="fas fa-download"></i></a>
                        </div>
                        <small class="text-muted">Generated Nov 15, 2025</small>
                    </div>

                    <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; width: 18rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 fw-semibold">Sales vs Target - October</p>
                            <a href="#" class="text-primary" title="Export"><i class="fas fa-file-export"></i></a>
                        </div>
                        <small class="text-muted">Generated Nov 1, 2025</small>
                    </div>

                    <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; width: 18rem;">
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
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush
