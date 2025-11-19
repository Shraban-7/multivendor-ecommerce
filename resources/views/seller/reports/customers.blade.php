@extends('seller.layouts.app')
@section('title', 'Customers Reports')

@push('styles')
<style>
    :root {
        --bs-primary: #0d6efd;
        --bs-success: #198754;
        --bs-info: #0dcaf0;
        --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: var(--card-shadow);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.75rem 1.25rem rgba(0, 0, 0, 0.1);
    }

    .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .kpi-icon {
        font-size: 1.5rem;
        opacity: 0.7;
    }

    .chart-placeholder {
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f4f6f9;
        border-radius: 0.5rem;
        border: 1px solid #e9ecef;
        color: #6c757d;
    }

    /* Subtle gradient accent for KPI cards */
    .card-accent-primary {
        border-left: 5px solid var(--bs-primary);
    }

    .card-accent-info {
        border-left: 5px solid var(--bs-info);
    }

    .card-accent-success {
        border-left: 5px solid var(--bs-success);
    }
</style>
@endpush

@section('content')
<div>
    <header>
        <div class="row align-items-center mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <h2 class="fw-bold mb-1">Customer Report</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item text-muted">Reports</li>
                        <li class="breadcrumb-item active fw-semibold" aria-current="page">Customer Report</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6">
                <form method="GET" class="row g-2 justify-content-end">
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label small">Filter By</label>
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
                    <div class="col-md-6 col-sm-6" id="customDateRange"
                        style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
                        <label class="form-label small">Custom Date Range</label>
                        <div class="input-group input-group-sm">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="form-control">
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-12 d-flex align-items-end">
                        <button class="btn btn-primary btn-sm w-100 mt-1 mt-md-4">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <header class="mb-4 pb-2 border-bottom">
        <h2 class="fw-bold mb-1"><i class="fas fa-users-viewfinder me-2 text-primary"></i> Customer Analytics</h2>
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item text-muted">Analytics</li>
                <li class="breadcrumb-item active fw-semibold" aria-current="page">Customer Analytics</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap gap-3">
            <div class="input-group" style="max-width: 250px;">
                <input type="text" class="form-control form-control-sm" placeholder="Last 90 Days" aria-label="Date Range">
                <span class="input-group-text bg-white"><i class="far fa-calendar-alt text-muted"></i></span>
            </div>
            <button class="btn btn-outline-secondary btn-sm" type="button"><i class="fas fa-sliders-h me-1"></i> Filter Segments</button>
        </div>
    </header>

    <div class="row mb-5 g-4">

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card p-3 h-100 card-accent-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small">Total Customers</span>
                        <h5 class="kpi-value text-primary mb-0">9,850</h5>
                    </div>
                    <i class="fas fa-user-friends kpi-icon text-primary"></i>
                </div>
                <small class="text-success fw-semibold mt-2"><i class="fas fa-arrow-up me-1"></i>4.5%</small>
                <small class="text-muted small">vs last period</small>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card p-3 h-100 card-accent-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small">New Customers</span>
                        <h5 class="kpi-value text-info mb-0">680</h5>
                    </div>
                    <i class="fas fa-user-plus kpi-icon text-info"></i>
                </div>
                <small class="text-success fw-semibold mt-2"><i class="fas fa-arrow-up me-1"></i>12%</small>
                <small class="text-muted small">this month</small>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card p-3 h-100 card-accent-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small">Returning %</span>
                        <h5 class="kpi-value text-success mb-0">42%</h5>
                    </div>
                    <i class="fas fa-redo-alt kpi-icon text-success"></i>
                </div>
                <small class="text-success fw-semibold mt-2"><i class="fas fa-arrow-up me-1"></i>1.5 pts</small>
                <small class="text-muted small">M/M change</small>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="card p-3 h-100 card-accent-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small">Avg CLV</span>
                        <h5 class="kpi-value text-warning mb-0">$355</h5>
                    </div>
                    <i class="fas fa-hand-holding-usd kpi-icon text-warning"></i>
                </div>
                <small class="text-success fw-semibold mt-2"><i class="fas fa-arrow-up me-1"></i>6.0%</small>
                <small class="text-muted small">last 12 months</small>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-12">
            <div class="card p-3 h-100 card-accent-secondary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small">Avg Orders/Cust</span>
                        <h5 class="kpi-value text-secondary mb-0">3.8</h5>
                    </div>
                    <i class="fas fa-cart-shopping kpi-icon text-secondary"></i>
                </div>
                <small class="text-danger fw-semibold mt-2"><i class="fas fa-arrow-down me-1"></i>-0.2</small>
                <small class="text-muted small">vs last period</small>
            </div>
        </div>

    </div>
    <div class="row g-4 mb-5">

        <div class="col-lg-7">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold text-primary mb-3">Customer Growth Trend</h5>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <ul class="nav nav-pills nav-pills-sm">
                        <li class="nav-item"><a class="nav-link active" href="#">Total Customers</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">New vs Returning</a></li>
                    </ul>
                    <span class="text-muted small">Last 12 Months</span>
                </div>

                <div class="chart-placeholder">
                    <span class="fw-semibold">Line Chart Placeholder (Total Customers Over Time)</span>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold text-dark mb-3">Demographics & Spending</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-placeholder" style="min-height: 150px;">
                            <span class="small">Pie Chart (Gender/Age Split)</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-semibold text-muted small mt-3 mt-md-0">Average Spending:</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Segment</th>
                                        <th class="text-end">% of Cust</th>
                                        <th class="text-end">Avg Spending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="fw-semibold">
                                        <td>Male</td>
                                        <td class="text-end">54%</td>
                                        <td class="text-end text-primary">$320</td>
                                    </tr>
                                    <tr>
                                        <td>Female</td>
                                        <td class="text-end">46%</td>
                                        <td class="text-end text-success">$380</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">

        <div class="col-lg-8">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold text-info mb-3">Loyalty & RFM Analysis</h5>

                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        <div class="card p-2 bg-light shadow-sm">
                            <p class="mb-0 small text-muted">Repeat Rate:</p>
                            <h4 class="fw-bold text-info mb-0">42%</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-2 bg-light shadow-sm">
                            <p class="mb-0 small text-muted">Avg. Time Between Purchases:</p>
                            <h4 class="fw-bold text-dark mb-0">18 days</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-2 bg-light shadow-sm">
                            <p class="mb-0 small text-muted">Next Purchase Likelihood:</p>
                            <h4 class="fw-bold text-success mb-0">High</h4>
                        </div>
                    </div>
                </div>

                <h6 class="fw-semibold mt-3 text-secondary">RFM (Recency, Frequency, Monetary) Summary</h6>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Segment</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Revenue Share</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fw-semibold table-success-subtle">
                                <td>Loyal <i class="fas fa-star text-warning"></i></td>
                                <td class="text-end">220</td>
                                <td class="text-end text-success">45%</td>
                                <td>High CLV, frequent buyers</td>
                            </tr>
                            <tr>
                                <td>At-Risk</td>
                                <td class="text-end">60</td>
                                <td class="text-end text-danger">12%</td>
                                <td>Few recent orders, need engagement</td>
                            </tr>
                            <tr>
                                <td>New</td>
                                <td class="text-end">180</td>
                                <td class="text-end text-info">20%</td>
                                <td>Growing base, encourage repeat</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 h-100">
                <h5 class="card-title fw-bold text-success mb-3">Top High-Value Customers</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Customer Name</th>
                                <th class="text-end">Orders</th>
                                <th class="text-end">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fw-semibold">
                                <td><i class="fas fa-trophy me-1 text-warning"></i> A. Rahman</td>
                                <td class="text-end">12</td>
                                <td class="text-end text-success">$1,200</td>
                            </tr>
                            <tr>
                                <td>Sara J.</td>
                                <td class="text-end">8</td>
                                <td class="text-end text-success">$980</td>
                            </tr>
                            <tr>
                                <td>Hussain M.</td>
                                <td class="text-end">15</td>
                                <td class="text-end text-success">$850</td>
                            </tr>
                            <tr>
                                <td>Emily R.</td>
                                <td class="text-end">7</td>
                                <td class="text-end text-success">$720</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h5 class="fw-bold mb-3"><i class="fas fa-bullhorn me-2 text-warning"></i> Actionable Insights</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card p-3 bg-white border-success shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-smile me-3 text-success fa-2x"></i>
                            <p class="mb-0 fw-semibold">Loyal customers up <span class="text-success">9%</span> this month. Maintain personalized offers.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 bg-white border-primary shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-arrow-up-right-dots me-3 text-primary fa-2x"></i>
                            <p class="mb-0 fw-semibold">Returning customers spend <span class="text-primary">30%</span> more per transaction.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 bg-white border-danger shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-simple me-3 text-danger fa-2x"></i>
                            <p class="mb-0 fw-semibold">At-Risk segment grew 5%. Launch a re-engagement campaign.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 pt-3 border-top text-center text-muted">
        <p class="mb-0 small">Customer behavior data powered by [YourBrand] Analytics. Focus on retention.</p>
    </footer>

</div>
@endsection

@push('scripts')

@endpush