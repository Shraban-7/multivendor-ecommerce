@extends('seller.layouts.app')
@section('title', 'Financial Reports')

@push('styles')
<style>
        :root {
            --bs-primary: #007bff;
            --bs-secondary: #6c757d;
            --bs-success: #28a745;
            --bs-danger: #dc3545;
            --bs-warning: #ffc107;
            --bs-info: #17a2b8;
            --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
        }

        .kpi-icon {
            font-size: 2rem;
            opacity: 0.7;
        }

        .kpi-value {
            font-size: 2.25rem;
        }

        .nav-tabs .nav-link {
            color: var(--bs-secondary);
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            margin-right: 0.5rem;
            transition: background-color 0.3s;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        /* Responsive Table styling */
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.03);
        }
    </style>    
@endpush

@section('content')

<div>
    <div class="page-header bg-white mb-4 p-3 rounded-3 shadow-sm">
        <h3 class="fw-bold mb-1">Financial Reports</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item text-muted">Reports</li>
                <li class="breadcrumb-item active fw-semibold" aria-current="page">Financial Reports</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-5 g-3">
        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-sack-dollar kpi-icon text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="kpi-value fw-bold text-dark mb-0">$38,200</h5>
                        <p class="text-muted text-sm mb-0 small">Total Revenue</p>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>8.5%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-info border-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-hand-holding-dollar kpi-icon text-info me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="kpi-value fw-bold text-dark mb-0">$22,000</h5>
                        <p class="text-muted text-sm mb-0 small">Gross Profit</p>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>4.2%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-success border-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-coins kpi-icon text-success me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="kpi-value fw-bold text-dark mb-0">$18,000</h5>
                        <p class="text-muted text-sm mb-0 small">Net Profit</p>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>9.0%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-wallet kpi-icon text-danger me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="kpi-value fw-bold text-dark mb-0">$10,200</h5>
                        <p class="text-muted text-sm mb-0 small">Total Expenses</p>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-danger fw-semibold"><i class="fas fa-arrow-up me-1"></i>2.5%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-boxes-stacked kpi-icon text-warning me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="kpi-value fw-bold text-dark mb-0">$47,000</h5>
                        <p class="text-muted text-sm mb-0 small">Inventory Value</p>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>1.1%</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
            <div class="card p-3 h-100 border-start border-secondary border-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-percent kpi-icon text-secondary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="kpi-value fw-bold text-dark mb-0">34.6%</h5>
                        <p class="text-muted text-sm mb-0 small">Profit Margin</p>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i>0.5 pts</small>
                    <small class="text-muted small">vs last month</small>
                </div>
            </div>
        </div>
    </div>


    <ul class="nav nav-tabs mb-4" id="financialTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pnl-tab" data-bs-toggle="tab" data-bs-target="#pnl" type="button" role="tab" aria-controls="pnl" aria-selected="true"><i class="fas fa-chart-line me-2"></i>Profit & Loss</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button" role="tab" aria-controls="income" aria-selected="false"><i class="fas fa-money-bill-transfer me-2"></i>Income Breakdown</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab" aria-controls="expenses" aria-selected="false"><i class="fas fa-hand-holding-usd me-2"></i>Expenses</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="false"><i class="fas fa-warehouse me-2"></i>Inventory Value</button>
        </li>
    </ul>

    <div class="tab-content" id="financialTabsContent">

        <div class="tab-pane fade show active" id="pnl" role="tabpanel" aria-labelledby="pnl-tab">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold text-primary">Monthly Profit Trend</h5>
                        <p class="text-muted">Net Profit Over the Last 12 Months</p>
                        <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                            <canvas id="profitChart" class="w-100" style="max-height: 300px;"></canvas>
                            <p class="text-muted mb-0">Line/Area Chart Placeholder (Profit Trend)</p>
                        </div>
                        <div class="alert alert-success mt-3 fw-bold text-center" role="alert">
                            Net Profit Margin: 34.6% (Target: 30%)
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold">P&L Summary</h5>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 table-borderless fw-semibold">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">Change %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total Sales</td>
                                        <td class="text-end text-dark">$52,000</td>
                                        <td class="text-end text-success">+12%</td>
                                    </tr>
                                    <tr>
                                        <td>Cost of Goods Sold</td>
                                        <td class="text-end text-dark">$30,000</td>
                                        <td class="text-end text-success">+4%</td>
                                    </tr>
                                    <tr>
                                        <td>Gross Profit</td>
                                        <td class="text-end text-dark">$22,000</td>
                                        <td class="text-end text-success">+14%</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td class="fw-bold">Net Profit</td>
                                        <td class="text-end fw-bold">$18,000</td>
                                        <td class="text-end fw-bold text-success">+9%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card bg-dark text-white p-3 mt-3 border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-6">Current Profit Margin</span>
                                <span class="fw-bold fs-4">34.6%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="income" role="tabpanel" aria-labelledby="income-tab">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold text-info">Income Source Proportions</h5>
                        <p class="text-muted">Visual breakdown of all income streams.</p>
                        <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                            <div id="incomePieChart" style="max-height: 300px;"></div>
                            <p class="text-muted mb-0">Pie/Donut Chart Placeholder (Income Distribution)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold">Income Data Table</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">Contribution %</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Product Sales</td>
                                        <td class="text-end">$40,000</td>
                                        <td class="text-end">78%</td>
                                        <td><span class="badge bg-primary">Primary Source</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Service Fees</td>
                                        <td class="text-end">$7,000</td>
                                        <td class="text-end">14%</td>
                                        <td><span class="badge bg-secondary">Stable Stream</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">POS Sales</td>
                                        <td class="text-end">$3,000</td>
                                        <td class="text-end">8%</td>
                                        <td><span class="badge bg-info">New Stream</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="expenses" role="tabpanel" aria-labelledby="expenses-tab">
            <div class="row mb-4 g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="card p-3 border-start border-danger border-4">
                        <p class="text-muted mb-0">Total Expense</p>
                        <h4 class="fw-bold mb-0 text-danger">$10,200</h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card p-3 border-start border-warning border-4">
                        <p class="text-muted mb-0">Highest Expense Category</p>
                        <h4 class="fw-bold mb-0 text-warning">Operations ($10,500)</h4>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="card p-3 border-start border-secondary border-4">
                        <p class="text-muted mb-0">Expense Growth %</p>
                        <h4 class="fw-bold mb-0 text-danger"><i class="fas fa-arrow-up me-1"></i> 2.5%</h4>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold text-danger">Expense Trend</h5>
                        <p class="text-muted">Monthly expense comparison.</p>
                        <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                            <canvas id="expenseBarChart" class="w-100" style="max-height: 300px;"></canvas>
                            <p class="text-muted mb-0">Bar Chart Placeholder (Expense Growth)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold">Expense Breakdown Table</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">Change</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Operations</td>
                                        <td class="text-end">$10,500</td>
                                        <td class="text-end text-success">+5%</td>
                                        <td>Rent, utilities</td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td colspan="4" class="p-1">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 47%;" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Marketing</td>
                                        <td class="text-end">$4,000</td>
                                        <td class="text-end text-danger">+25%</td>
                                        <td>Campaigns</td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td colspan="4" class="p-1">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: 18%;" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Staff</td>
                                        <td class="text-end">$6,500</td>
                                        <td class="text-end text-success">+2%</td>
                                        <td>Salaries</td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td colspan="4" class="p-1">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Other</td>
                                        <td class="text-end">$2,200</td>
                                        <td class="text-end text-success">-10%</td>
                                        <td>Misc.</td>
                                    </tr>
                                    <tr class="mb-2">
                                        <td colspan="4" class="p-1">
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 5%;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
            <div class="card p-4 mb-4 border-bottom border-warning border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold text-warning mb-0">Total Inventory Value: <span class="text-dark">$47,000</span></h4>
                    <span class="badge bg-danger-subtle text-danger p-2 fw-semibold"><i class="fas fa-triangle-exclamation me-1"></i>Low Turnover Warning: 90 Days</span>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold text-warning">Value by Category</h5>
                        <p class="text-muted">Horizontal Bar Chart showing stock worth.</p>
                        <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                            <canvas id="inventoryChart" class="w-100" style="max-height: 300px;"></canvas>
                            <p class="text-muted mb-0">Horizontal Bar Chart Placeholder (Inventory Value)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card p-4 h-100">
                        <h5 class="card-title fw-bold">Inventory Details</h5>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">SKU Count</th>
                                        <th class="text-end">Stock Value</th>
                                        <th class="text-end">% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Electronics</td>
                                        <td class="text-end">120</td>
                                        <td class="text-end">$22,000</td>
                                        <td class="text-end">46%</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Apparel</td>
                                        <td class="text-end">250</td>
                                        <td class="text-end">$15,000</td>
                                        <td class="text-end">31%</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Accessories</td>
                                        <td class="text-end">180</td>
                                        <td class="text-end">$10,000</td>
                                        <td class="text-end">23%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="mt-5 pt-3 border-top text-center text-muted">
        <p class="mb-0">© [YourBrand] Financial Dashboard - Updated November 18, 2025</p>
    </footer>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Profit Chart (Line/Area)
        const profitCtx = document.getElementById('profitChart');
        if (profitCtx) {
            new Chart(profitCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Net Profit ($)',
                        data: [12000, 14000, 11000, 16000, 17500, 15000, 18000, 20000, 19000, 21000, 18000, 22000],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: false } }
                }
            });
        }
        
        // Inventory Chart (Horizontal Bar)
        const inventoryCtx = document.getElementById('inventoryChart');
        if (inventoryCtx) {
            new Chart(inventoryCtx, {
                type: 'bar',
                data: {
                    labels: ['Electronics', 'Apparel', 'Accessories'],
                    datasets: [{
                        label: 'Stock Value ($)',
                        data: [22000, 15000, 10000],
                        backgroundColor: ['#ffc107', '#007bff', '#6c757d'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y', // Makes it horizontal
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } }
                }
            });
        }
        
        // Expense Bar Chart (Simple Bar)
        const expenseCtx = document.getElementById('expenseBarChart');
        if (expenseCtx) {
            new Chart(expenseCtx, {
                type: 'bar',
                data: {
                    labels: ['Operations', 'Marketing', 'Staff', 'Other'],
                    datasets: [{
                        label: 'Expense ($)',
                        data: [10500, 4000, 6500, 2200],
                        backgroundColor: ['#ffc107', '#007bff', '#28a745', '#6c757d'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }          
    });
</script>
@endpush