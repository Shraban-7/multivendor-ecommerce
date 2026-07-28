@extends('seller.layouts.app')
@section('title', 'Financial Reports')

@section('content')
    <div>
        <header>
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1 text-dark">Financial Reports</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item text-muted">Reports</li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">Financial Reports</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="row g-2 justify-content-end">
                        <div class="col-md-4 col-sm-6">
                            <select name="range" class="form-select form-select-sm" onchange="toggleCustomDates(this.value)">
                                <option disabled selected>--select--</option>
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

        <div class="row mb-5 g-3">
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #F85606;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sack-dollar me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ money($currentMetrics['total_revenue']) }}</h5>
                            <p class="text-muted small mb-0">Total Revenue</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['revenue'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $changes['revenue'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ number_format(abs($changes['revenue']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #0ea5e9;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hand-holding-dollar me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ money($currentMetrics['gross_profit']) }}</h5>
                            <p class="text-muted small mb-0">Gross Profit</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $changes['gross_profit'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ number_format(abs($changes['gross_profit']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #1D8A45;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-coins me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ money($currentMetrics['net_profit']) }}</h5>
                            <p class="text-muted small mb-0">Net Profit</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $changes['net_profit'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ number_format(abs($changes['net_profit']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #D93025;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-wallet me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ money($currentMetrics['total_expense']) }}</h5>
                            <p class="text-muted small mb-0">Total Expenses</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['expense'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $changes['expense'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ number_format(abs($changes['expense']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #B7791A;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-boxes-stacked me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ money($inventory_value) }}</h5>
                            <p class="text-muted small mb-0">Inventory Value</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #637381;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-percent me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">{{ number_format($currentMetrics['profit_margin'], 2) }}%</h5>
                            <p class="text-muted small mb-0">Profit Margin</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['profit_margin'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas {{ $changes['profit_margin'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                            {{ number_format(abs($changes['profit_margin']), 2) }}% Change
                        </small>
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
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            @php
                                $filterText = match (request('range', 'monthly')) { 'daily' => 'Daily Profit Trend', 'weekly' => 'Weekly Profit Trend', 'monthly' => 'Monthly Profit Trend', 'yearly' => 'Yearly Profit Trend', 'custom' => 'Custom Profit Trend', default => 'Monthly Profit Trend' };
                                $descriptionText = match (request('range', 'monthly')) { 'daily' => 'Net Profit Over the Last 7 Days', 'weekly' => 'Net Profit Over the Last 12 Weeks', 'monthly' => 'Net Profit Over the Last 12 Months', 'yearly' => 'Net Profit Over the Last 5 Years', 'custom' => 'Net Profit Over the Selected Date Range', default => 'Net Profit Over the Last 12 Months' };
                            @endphp
                            <h5 class="fw-bold">{{ $filterText }}</h5>
                            <p class="text-muted">{{ $descriptionText }}</p>
                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center py-5">
                                <canvas id="profitChart" class="w-100" style="max-height: 300px;"></canvas>
                            </div>
                            <div class="alert {{ $currentMetrics['profit_margin'] >= 0 ? 'alert-success' : 'alert-danger' }} mt-3 fw-bold text-center" role="alert">
                                Net Profit Margin: {{ number_format($currentMetrics['profit_margin'], 2) }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            <h5 class="fw-bold">P&L Summary</h5>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 table-borderless">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="small fw-semibold text-muted">Category</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Amount</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Change %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">Total Sales</td>
                                            <td class="text-end">{{ money($currentMetrics['total_revenue']) }}</td>
                                            <td class="text-end {{ $changes['revenue'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $changes['revenue'] >= 0 ? '+' : '' }}{{ number_format($changes['revenue'], 2) }}%</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Cost of Goods Sold</td>
                                            <td class="text-end">{{ money($currentMetrics['total_product_cost']) }}</td>
                                            <td class="text-end {{ $changes['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $changes['gross_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['gross_profit'], 2) }}%</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Gross Profit</td>
                                            <td class="text-end">{{ money($currentMetrics['gross_profit']) }}</td>
                                            <td class="text-end {{ $changes['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $changes['gross_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['gross_profit'], 2) }}%</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td class="fw-bold">Net Profit</td>
                                            <td class="text-end fw-bold">{{ money($currentMetrics['net_profit']) }}</td>
                                            <td class="text-end fw-bold {{ $changes['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">{{ $changes['net_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['net_profit'], 2) }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card {{ $currentMetrics['profit_margin'] >= 0 ? 'bg-success' : 'bg-danger' }} text-white p-3 mt-3 border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Current Profit Margin</span>
                                    <span class="fw-bold fs-4">{{ number_format($currentMetrics['profit_margin'], 2) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="income" role="tabpanel" aria-labelledby="income-tab">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            <h5 class="fw-bold text-info">Income Source Proportions</h5>
                            <p class="text-muted">Visual breakdown of all income streams.</p>
                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center py-5">
                                <canvas id="incomePieChart" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            <h5 class="fw-bold">Income Data Table</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="small fw-semibold text-muted">Source</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Amount</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Contribution %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incomeData as $income)
                                            <tr>
                                                <td class="fw-semibold">{{ $income['source'] }}</td>
                                                <td class="text-end">{{ money($income['amount']) }}</td>
                                                <td class="text-end">{{ number_format($income['percentage'], 2) }}%</td>
                                            </tr>
                                        @endforeach
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
                        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #D93025;">
                            <p class="text-muted mb-0">Total Expense</p>
                            <h4 class="fw-bold mb-0 text-danger">{{ money($totalExpense) }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #B7791A;">
                            <p class="text-muted mb-0">Highest Expense Category</p>
                            <h4 class="fw-bold mb-0 text-warning">{{ $highestExpense->category->name ?? 'N/A' }} ({{ money($highestExpense->total ?? 0) }})</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #637381;">
                            <p class="text-muted mb-0">Expense Growth %</p>
                            <h4 class="fw-bold mb-0 text-{{ $expenseGrowth >= 0 ? 'danger' : 'success' }}">
                                <i class="fas fa-arrow-{{ $expenseGrowth >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ number_format($expenseGrowth, 2) }}%
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            <h5 class="fw-bold text-danger">Expense Trend</h5>
                            <p class="text-muted">{{ ucfirst(request('range')) }} expense comparison.</p>
                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center py-5">
                                <canvas id="expenseBarChart" class="w-100" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            <h5 class="fw-bold">Expense Breakdown Table</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="small fw-semibold text-muted">Category</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Amount</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Change</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenseCategories ?? [] as $expense)
                                            @php
                                                $lastAmount = \App\Domain\Vendor\Models\SellerExpense::where('seller_id', get_seller_id())->where('seller_expense_category_id', $expense['seller_expense_category_id'])->whereBetween('created_at', [$lastStart, $lastEnd])->sum('amount');
                                                $change = $lastAmount > 0 ? (($expense['total'] - $lastAmount) / $lastAmount) * 100 : 100;
                                                $categoryName = $expense['category']['name'] ?? 'N/A';
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold">{{ $categoryName }}</td>
                                                <td class="text-end">{{ money($expense['total']) }}</td>
                                                <td class="text-end {{ $change >= 0 ? 'text-success' : 'text-danger' }}">{{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%</td>
                                            </tr>
                                            <tr class="mb-2">
                                                <td colspan="4" class="p-1">
                                                    <div class="progress" style="height: 5px;">
                                                        <div class="progress-bar bg-{{ $loop->index % 2 == 0 ? 'warning' : 'primary' }}" role="progressbar"
                                                            style="width: {{ ($expense['total'] / ($totalExpense ?: 1)) * 100 }}%"
                                                            aria-valuenow="{{ $expense['total'] }}" aria-valuemin="0" aria-valuemax="{{ $totalExpense }}">
                                                        </div>
                                                    </div>
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
            <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; border-bottom: 4px solid #B7791A;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="fw-bold mb-0">Total Inventory Value: <span class="text-dark">{{ money($inventory_value) }}</span></h4>
                        <span class="badge p-2 badge-soft-danger">
                            <i class="fas fa-triangle-exclamation me-1"></i> Low Turnover Warning: {{ $lowTurnoverDays }} Days ({{ $lowTurnoverCount }} SKUs)
                        </span>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            <h5 class="fw-bold text-warning">Value by Category</h5>
                            <p class="text-muted">Horizontal Bar Chart showing stock worth.</p>
                            <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center py-5">
                                <canvas id="inventoryChart" class="w-100" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                            <h5 class="fw-bold">Inventory Details</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="small fw-semibold text-muted">Category</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">SKU Count</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">Stock Value</th>
                                            <th scope="col" class="small fw-semibold text-muted text-end">% of Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inventoryByCategory as $item)
                                            @php
                                                $categoryName = $item->category->name ?? 'N/A';
                                                $percent = $totalStockValue > 0 ? ($item->stock_value / $totalStockValue) * 100 : 0;
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold">{{ $categoryName }}</td>
                                                <td class="text-end">{{ $item->sku_count }}</td>
                                                <td class="text-end">{{ money($item->stock_value) }}</td>
                                                <td class="text-end">{{ number_format($percent, 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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

        const ctx = document.getElementById('profitChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendData->pluck('label')) !!},
                datasets: [
                    { label: 'Net Profit', data: {!! json_encode($trendData->pluck('net_profit')) !!}, backgroundColor: 'rgba(248, 86, 6, 0.1)', borderColor: '#F85606', borderWidth: 2, fill: true, tension: 0.3 },
                    { label: 'Gross Profit', data: {!! json_encode($trendData->pluck('gross_profit')) !!}, backgroundColor: 'rgba(29, 138, 69, 0.1)', borderColor: '#1D8A45', borderWidth: 2, fill: true, tension: 0.3 },
                    { label: 'Revenue', data: {!! json_encode($trendData->pluck('total_revenue')) !!}, backgroundColor: 'rgba(14, 165, 233, 0.1)', borderColor: '#0ea5e9', borderWidth: 2, fill: false, tension: 0.3 }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });

        const incomeCtx = document.getElementById('incomePieChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($incomeData->pluck('source')) !!},
                datasets: [{ data: {!! json_encode($incomeData->pluck('amount')) !!}, backgroundColor: ['#F85606', '#637381', '#0ea5e9', '#B7791A', '#1D8A45'], borderColor: '#fff', borderWidth: 2 }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        const expenseCtx = document.getElementById('expenseBarChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($expenseTrend->pluck('label')) !!},
                datasets: [{ label: 'Expenses', data: {!! json_encode($expenseTrend->pluck('amount')) !!}, backgroundColor: '#D93025' }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
        new Chart(inventoryCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($inventoryByCategory->pluck('category.name')) !!},
                datasets: [{ label: "Stock Value", data: {!! json_encode($inventoryByCategory->pluck('stock_value')) !!}, backgroundColor: 'rgba(248, 86, 6, 0.7)' }]
            },
            options: { indexAxis: 'y', responsive: true, scales: { x: { beginAtZero: true } } }
        });
    </script>
@endpush
