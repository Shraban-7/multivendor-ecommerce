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

    <header class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h2 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-bar me-2 text-primary"></i> Business Overview</h2>
        <div class="d-flex align-items-center">

            <div class="input-group me-3 shadow-sm">
                <input type="text" class="form-control form-control-sm" placeholder="Nov 1, 2025 - Nov 30, 2025" aria-label="Date Range">
                <span class="input-group-text bg-white"><i class="far fa-calendar-alt text-muted"></i></span>
            </div>

            <button class="btn btn-outline-secondary btn-sm shadow-sm" type="button" title="Filters">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </header>

    <div class="row mb-5 g-3">

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase mb-1 small fw-semibold">Total Sales</p>
                        <span class="kpi-value text-primary">$105,000</span>
                    </div>
                    <i class="fas fa-shopping-bag kpi-icon text-primary-emphasis"></i>
                </div>
                <div class="mt-2">
                    <div class="progress mb-1" style="height: 3px;">
                        <div class="progress-bar bg-success" style="width: 75%"></div>
                    </div>
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>12%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase mb-1 small fw-semibold">Orders</p>
                        <span class="kpi-value text-info">1,450</span>
                    </div>
                    <i class="fas fa-clipboard-list kpi-icon text-info-emphasis"></i>
                </div>
                <div class="mt-2">
                    <div class="progress mb-1" style="height: 3px;">
                        <div class="progress-bar bg-success" style="width: 85%"></div>
                    </div>
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>8.5%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase mb-1 small fw-semibold">Net Profit</p>
                        <span class="kpi-value text-success">$34,200</span>
                    </div>
                    <i class="fas fa-dollar-sign kpi-icon text-success-emphasis"></i>
                </div>
                <div class="mt-2">
                    <div class="progress mb-1" style="height: 3px;">
                        <div class="progress-bar bg-success" style="width: 90%"></div>
                    </div>
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>5.0%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase mb-1 small fw-semibold">Ret. Customers</p>
                        <span class="kpi-value text-warning">38%</span>
                    </div>
                    <i class="fas fa-users-viewfinder kpi-icon text-warning-emphasis"></i>
                </div>
                <div class="mt-2">
                    <div class="progress mb-1" style="height: 3px;">
                        <div class="progress-bar bg-danger" style="width: 45%"></div>
                    </div>
                    <small class="text-danger fw-semibold"><i class="fas fa-arrow-down me-1"></i>-5%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase mb-1 small fw-semibold">AOV</p>
                        <span class="kpi-value text-secondary">$72.41</span>
                    </div>
                    <i class="fas fa-basket-shopping kpi-icon text-secondary-emphasis"></i>
                </div>
                <div class="mt-2">
                    <div class="progress mb-1" style="height: 3px;">
                        <div class="progress-bar bg-success" style="width: 55%"></div>
                    </div>
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>3.1%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted text-uppercase mb-1 small fw-semibold">Total Stock</p>
                        <span class="kpi-value text-dark">5,890</span>
                    </div>
                    <i class="fas fa-boxes-stacked kpi-icon text-dark-emphasis"></i>
                </div>
                <div class="mt-2">
                    <div class="progress mb-1" style="height: 3px;">
                        <div class="progress-bar bg-info" style="width: 60%"></div>
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
                        <p class="fw-semibold text-muted mb-1">Monthly Revenue Trend</p>
                        <div class="chart-placeholder">
                            <span class="text-muted">Line Chart Placeholder (Revenue)</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-semibold text-muted mb-1">Orders vs. Returns</p>
                        <div class="chart-placeholder">
                            <span class="text-muted">Bar Chart Placeholder (Orders/Returns)</span>
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
                        Total Orders YTD:
                        <span class="fw-bold text-primary">15,800</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Refund Rate:
                        <span class="fw-bold text-danger">3.1%</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Best Sales Day:
                        <span class="fw-bold text-success">Nov 14 ($9,800)</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Customer Acquisition Cost (CAC):
                        <span class="fw-bold text-secondary">$15.50</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold mb-3"><i class="fas fa-lightbulb me-2 text-info"></i> Smart Highlights</h5>
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success me-3 p-2"><i class="fas fa-chart-line"></i></span>
                        <span class="fw-semibold">Revenue grew <span class="text-success">8%</span> last week.</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                        <span class="badge bg-primary-subtle text-primary me-3 p-2"><i class="fas fa-shirt"></i></span>
                        <span class="fw-semibold">Apparel category contributed <span class="text-primary">32%</span> of total sales.</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                        <span class="badge bg-warning-subtle text-warning me-3 p-2"><i class="fas fa-repeat"></i></span>
                        <span class="fw-semibold">Returning customers spent <span class="text-warning">18%</span> more.</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action border-0 py-2 d-flex align-items-center">
                        <span class="badge bg-danger-subtle text-danger me-3 p-2"><i class="fas fa-boxes-packing"></i></span>
                        <span class="fw-semibold">High stock alert in Electronics. Review turnover.</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold mb-3"><i class="fas fa-star me-2 text-success"></i> Top Product Snapshot</h5>
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
                            <tr class="fw-semibold">
                                <td>Classic T-shirt</td>
                                <td class="text-end">320</td>
                                <td class="text-end text-success">$4,200</td>
                                <td class="text-end">56</td>
                            </tr>
                            <tr>
                                <td>Running Shoes Pro</td>
                                <td class="text-end">140</td>
                                <td class="text-end">$6,000</td>
                                <td class="text-end text-danger">32</td>
                            </tr>
                            <tr>
                                <td>Smart Watch X</td>
                                <td class="text-end">98</td>
                                <td class="text-end">$12,000</td>
                                <td class="text-end">110</td>
                            </tr>
                            <tr>
                                <td>Minimalist Backpack</td>
                                <td class="text-end">85</td>
                                <td class="text-end">$3,500</td>
                                <td class="text-end">180</td>
                            </tr>
                            <tr>
                                <td>Yoga Mat</td>
                                <td class="text-end">75</td>
                                <td class="text-end">$1,500</td>
                                <td class="text-end">45</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-4">
        <div class="col-12">
            <h5 class="fw-bold mb-3"><i class="fas fa-file-invoice me-2 text-secondary"></i> Recent Reports & Exports</h5>
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

@endpush