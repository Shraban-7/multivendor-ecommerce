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
        <div class="row">
            <div class="col-md-6 mb-3">
                <h2 class="fw-bold mb-1">Sales Report</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item text-muted">Reports</li>
                        <li class="breadcrumb-item active fw-semibold" aria-current="page">Sales Report</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 mb-3 d-flex justify-content-end align-items-end">
                <div>
                    <div class="input-group">
                        <input type="date" name="date_from" class="form-control form-control-sm">
                        <input type="date" name="date_to" class="form-control form-control-sm">
                        <button class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="row mb-4 g-3">

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Total Revenue</span>
                        <h5 class="kpi-value text-primary mb-0">$66,000</h5>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x text-primary opacity-50"></i>
                </div>
                <small class="text-success fw-semibold mt-2"><i class="fas fa-arrow-up me-1"></i>15%</small>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-info border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Orders</span>
                        <h5 class="kpi-value text-info mb-0">1,100</h5>
                    </div>
                    <i class="fas fa-box fa-2x text-info opacity-50"></i>
                </div>
                <small class="text-success fw-semibold mt-2"><i class="fas fa-arrow-up me-1"></i>10%</small>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Avg Order Value</span>
                        <h5 class="kpi-value text-warning mb-0">$60.00</h5>
                    </div>
                    <i class="fas fa-receipt fa-2x text-warning opacity-50"></i>
                </div>
                <small class="text-danger fw-semibold mt-2"><i class="fas fa-arrow-down me-1"></i>-3%</small>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Best Seller</span>
                        <h6 class="fw-bold mb-0 text-success">Smartwatch</h6>
                        <p class="mb-0 small text-muted">250 units</p>
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
                        <h5 class="kpi-value text-secondary mb-0">+15.0%</h5>
                    </div>
                    <i class="fas fa-chart-line fa-2x text-secondary opacity-50"></i>
                </div>
                <small class="text-success fw-semibold mt-2">vs previous month</small>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-danger border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase small">Refund Rate</span>
                        <h5 class="kpi-value text-danger mb-0">3.5%</h5>
                    </div>
                    <i class="fas fa-undo fa-2x text-danger opacity-50"></i>
                </div>
                <small class="text-success fw-semibold mt-2"><i class="fas fa-arrow-down me-1"></i>-0.2 pts</small>
            </div>
        </div>

    </div>
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card p-4">
                <h5 class="card-title fw-bold text-dark mb-3">Revenue Trend Over Time</h5>
                <div class="d-flex justify-content-start mb-3">
                    <ul class="nav nav-pills nav-pills-sm">
                        <li class="nav-item"><a class="nav-link active" href="#">Day</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Week</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Month</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Custom</a></li>
                    </ul>
                </div>
                <div class="chart-placeholder">
                    <span class="fw-semibold">Large Line/Area Chart Placeholder (Total Sales)</span>
                </div>
                <p class="alert alert-light alert-success p-2 mt-3 mb-0 text-center fw-semibold">
                    <i class="fas fa-check-circle me-1"></i> Sales are up <span class="text-success">15%</span> vs. the previous month, driven by strong seasonal demand.
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">

        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold text-dark mb-3">Product Category Performance</h5>

                <div class="row">
                    <div class="col-md-5">
                        <div class="chart-placeholder" style="min-height: 200px;">
                            <span class="small">Pie Chart Placeholder (Revenue Share)</span>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <p class="fw-semibold text-muted small mt-3 mt-md-0">Revenue & Order Breakdown:</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">Revenue</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Growth</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="fw-semibold">
                                        <td>Apparel</td>
                                        <td class="text-end">$24,500</td>
                                        <td class="text-end">360</td>
                                        <td class="text-end text-success">+8%</td>
                                    </tr>
                                    <tr>
                                        <td>Accessories</td>
                                        <td class="text-end">$11,200</td>
                                        <td class="text-end">270</td>
                                        <td class="text-end text-success">+12%</td>
                                    </tr>
                                    <tr>
                                        <td>Electronics</td>
                                        <td class="text-end">$30,300</td>
                                        <td class="text-end">470</td>
                                        <td class="text-end text-success">+5%</td>
                                    </tr>
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
                            <tr class="fw-semibold">
                                <td>Web / E-comm <span class="badge bg-primary ms-2">Top Source</span></td>
                                <td class="text-end">$42,000</td>
                                <td class="text-end">780</td>
                                <td class="text-end">64%</td>
                            </tr>
                            <tr>
                                <td>POS (Retail)</td>
                                <td class="text-end">$16,000</td>
                                <td class="text-end">200</td>
                                <td class="text-end">24%</td>
                            </tr>
                            <tr>
                                <td>Mobile App</td>
                                <td class="text-end">$8,000</td>
                                <td class="text-end">120</td>
                                <td class="text-end">12%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <p class="fw-semibold text-muted small mb-1">Total Orders Distribution:</p>
                    <div class="progress" style="height: 15px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 64%" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100">Web (64%)</div>
                        <div class="progress-bar bg-info" role="progressbar" style="width: 24%" aria-valuenow="24" aria-valuemin="0" aria-valuemax="100">POS (24%)</div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100">App (12%)</div>
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
                            <tr class="fw-semibold">
                                <td>Smartwatch X</td>
                                <td class="text-end">$90</td>
                                <td class="text-end">250</td>
                                <td class="text-end text-success">$22,500</td>
                                <td class="text-end text-primary">28%</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-1">
                                    <div class="progress-bar-label">Relative Sales: 100%</div>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>Premium T-shirt</td>
                                <td class="text-end">$15</td>
                                <td class="text-end">320</td>
                                <td class="text-end text-success">$4,800</td>
                                <td class="text-end text-primary">35%</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-1">
                                    <div class="progress-bar-label">Relative Sales: 21%</div>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success" style="width: 21%"></div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>Leather Wallet</td>
                                <td class="text-end">$40</td>
                                <td class="text-end">80</td>
                                <td class="text-end text-success">$3,200</td>
                                <td class="text-end text-primary">30%</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-1">
                                    <div class="progress-bar-label">Relative Sales: 14%</div>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success" style="width: 14%"></div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>Travel Mug</td>
                                <td class="text-end">$25</td>
                                <td class="text-end">100</td>
                                <td class="text-end text-success">$2,500</td>
                                <td class="text-end text-primary">40%</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-1">
                                    <div class="progress-bar-label">Relative Sales: 11%</div>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success" style="width: 11%"></div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold text-dark mb-3">Sales by Region (Orders)</h5>
                <div class="chart-placeholder">
                    <span class="fw-semibold">Map or Placeholder Chart (Geographical Sales Distribution)</span>
                </div>
                <p class="mt-3 mb-0 small text-muted text-center">Focus on regions with high order volume for targeted marketing campaigns.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>

</script>
@endpush