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

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
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
            font-size: 1.55rem;
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
        <header>
            <div class="row align-items-center mb-4">
                <!-- Title + Breadcrumb -->
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1">Financial Reports</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item text-muted">Reports</li>
                            <li class="breadcrumb-item active fw-semibold" aria-current="page">Financial Reports</li>
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
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
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
                <div class="card p-3 h-100 border-start border-primary border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sack-dollar kpi-icon text-primary me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="kpi-value fw-bold text-dark mb-0">{{ money($currentMetrics['total_revenue']) }}</h5>
                            <p class="text-muted text-sm mb-0 small">Total Revenue</p>
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
                <div class="card p-3 h-100 border-start border-info border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hand-holding-dollar kpi-icon text-info me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="kpi-value fw-bold text-dark mb-0">{{ money($currentMetrics['gross_profit']) }}</h5>
                            <p class="text-muted text-sm mb-0 small">Gross Profit</p>
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
                <div class="card p-3 h-100 border-start border-success border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-coins kpi-icon text-success me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="kpi-value fw-bold text-dark mb-0">{{ money($currentMetrics['net_profit']) }}</h5>
                            <p class="text-muted text-sm mb-0 small">Net Profit</p>
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
                <div class="card p-3 h-100 border-start border-danger border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-wallet kpi-icon text-danger me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="kpi-value fw-bold text-dark mb-0">{{ money($currentMetrics['total_expense']) }}</h5>
                            <p class="text-muted text-sm mb-0 small">Total Expenses</p>
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
                <div class="card p-3 h-100 border-start border-warning border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-boxes-stacked kpi-icon text-warning me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="kpi-value fw-bold text-dark mb-0">{{ money($inventory_value) }}</h5>
                            <p class="text-muted text-sm mb-0 small">Inventory Value</p>
                        </div>
                    </div>
                    <div class="mt-2">

                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                <div class="card p-3 h-100 border-start border-secondary border-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-percent kpi-icon text-secondary me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="kpi-value fw-bold text-dark mb-0">
                                {{ number_format($currentMetrics['profit_margin'], 2) }}%</h5>
                            <p class="text-muted text-sm mb-0 small">Profit Margin</p>
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
                <button class="nav-link active" id="pnl-tab" data-bs-toggle="tab" data-bs-target="#pnl" type="button"
                    role="tab" aria-controls="pnl" aria-selected="true"><i class="fas fa-chart-line me-2"></i>Profit
                    &
                    Loss</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button"
                    role="tab" aria-controls="income" aria-selected="false"><i
                        class="fas fa-money-bill-transfer me-2"></i>Income Breakdown</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses"
                    type="button" role="tab" aria-controls="expenses" aria-selected="false"><i
                        class="fas fa-hand-holding-usd me-2"></i>Expenses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory"
                    type="button" role="tab" aria-controls="inventory" aria-selected="false"><i
                        class="fas fa-warehouse me-2"></i>Inventory Value</button>
            </li>
        </ul>

        <div class="tab-content" id="financialTabsContent">

            <div class="tab-pane fade show active" id="pnl" role="tabpanel" aria-labelledby="pnl-tab">
                <div class="row g-4">
                    <!-- Monthly Profit Trend Chart -->
                    <div class="col-lg-8">
                        <div class="card p-4 h-100">
                            @php
                                $filterText = match (request('range', 'monthly')) {
                                    'daily' => 'Daily Profit Trend',
                                    'weekly' => 'Weekly Profit Trend',
                                    'monthly' => 'Monthly Profit Trend',
                                    'yearly' => 'Yearly Profit Trend',
                                    'custom' => 'Custom Profit Trend',
                                    default => 'Monthly Profit Trend',
                                };

                                $descriptionText = match (request('range', 'monthly')) {
                                    'daily' => 'Net Profit Over the Last 7 Days',
                                    'weekly' => 'Net Profit Over the Last 12 Weeks',
                                    'monthly' => 'Net Profit Over the Last 12 Months',
                                    'yearly' => 'Net Profit Over the Last 5 Years',
                                    'custom' => 'Net Profit Over the Selected Date Range',
                                    default => 'Net Profit Over the Last 12 Months',
                                };
                            @endphp

                            <h5 class="card-title fw-bold text-primary">{{ $filterText }}</h5>
                            <p class="text-muted">{{ $descriptionText }}</p>

                            <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                                <canvas id="profitChart" class="w-100" style="max-height: 300px;"></canvas>
                            </div>
                            <div class="alert 
                    {{ $currentMetrics['profit_margin'] >= 30 ? 'alert-success' : 'alert-danger' }} 
                    mt-3 fw-bold text-center"
                                role="alert">
                                Net Profit Margin: {{ number_format($currentMetrics['profit_margin'], 2) }}% (Target: 30%)
                            </div>
                        </div>
                    </div>

                    <!-- P&L Summary Table -->
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
                                            <td class="text-end text-dark">
                                                {{ money($currentMetrics['total_revenue']) }}</td>
                                            <td
                                                class="text-end {{ $changes['revenue'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $changes['revenue'] >= 0 ? '+' : '' }}{{ number_format($changes['revenue'], 2) }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cost of Goods Sold</td>
                                            <td class="text-end text-dark">
                                                {{ money($currentMetrics['total_product_cost']) }}</td>
                                            <td
                                                class="text-end {{ $changes['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $changes['gross_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['gross_profit'], 2) }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gross Profit</td>
                                            <td class="text-end text-dark">
                                                {{ money($currentMetrics['gross_profit']) }}</td>
                                            <td
                                                class="text-end {{ $changes['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $changes['gross_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['gross_profit'], 2) }}%
                                            </td>
                                        </tr>
                                        <tr class="table-success">
                                            <td class="fw-bold">Net Profit</td>
                                            <td class="text-end fw-bold">
                                                {{ money($currentMetrics['net_profit']) }}</td>
                                            <td
                                                class="text-end fw-bold {{ $changes['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $changes['net_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['net_profit'], 2) }}%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Profit Margin Card -->
                            <div
                                class="card {{ $currentMetrics['profit_margin'] >= 30 ? 'bg-success' : 'bg-danger' }} text-white p-3 mt-3 border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-6">Current Profit Margin</span>
                                    <span
                                        class="fw-bold fs-4">{{ number_format($currentMetrics['profit_margin'], 2) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="income" role="tabpanel" aria-labelledby="income-tab">
                <div class="row g-4">
                    <!-- Pie Chart -->
                    <div class="col-lg-5">
                        <div class="card p-4 h-100">
                            <h5 class="card-title fw-bold text-info">Income Source Proportions</h5>
                            <p class="text-muted">Visual breakdown of all income streams.</p>
                            <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                                <canvas id="incomePieChart" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Income Table -->
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
                        <div class="card p-3 border-start border-danger border-4">
                            <p class="text-muted mb-0">Total Expense</p>
                            <h4 class="fw-bold mb-0 text-danger">{{ money($totalExpense) }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card p-3 border-start border-warning border-4">
                            <p class="text-muted mb-0">Highest Expense Category</p>
                            <h4 class="fw-bold mb-0 text-warning">
                                {{ $highestExpense->category->name ?? 'N/A' }}
                                ({{ money($highestExpense->total ?? 0) }})
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card p-3 border-start border-secondary border-4">
                            <p class="text-muted mb-0">Expense Growth %</p>
                            <h4 class="fw-bold mb-0 text-{{ $expenseGrowth >= 0 ? 'danger' : 'success' }}">
                                <i class="fas fa-arrow-{{ $expenseGrowth >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ number_format($expenseGrowth, 2) }}%
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Expense Trend Chart -->
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <h5 class="card-title fw-bold text-danger">Expense Trend</h5>
                            <p class="text-muted">{{ ucfirst(request('range')) }} expense comparison.</p>
                            <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                                <canvas id="expenseBarChart" class="w-100" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Expense Breakdown Table -->
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenseCategories ?? [] as $expense)
                                            @php
                                                $lastAmount = \App\Models\SellerExpense::where(
                                                    'seller_id',
                                                    get_seller_id(),
                                                )
                                                    ->where(
                                                        'seller_expense_category_id',
                                                        $expense['seller_expense_category_id'],
                                                    )
                                                    ->whereBetween('created_at', [$lastStart, $lastEnd])
                                                    ->sum('amount');

                                                $change =
                                                    $lastAmount > 0
                                                        ? (($expense['total'] - $lastAmount) / $lastAmount) * 100
                                                        : 100;

                                                $categoryName = $expense['category']['name'] ?? 'N/A';
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold">{{ $categoryName }}</td>
                                                <td class="text-end">{{ money($expense['total']) }}</td>
                                                <td class="text-end {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                                                </td>
                                            </tr>
                                            <tr class="mb-2">
                                                <td colspan="4" class="p-1">
                                                    <div class="progress" style="height: 5px;">
                                                        <div class="progress-bar bg-{{ $loop->index % 2 == 0 ? 'warning' : 'primary' }}"
                                                            role="progressbar"
                                                            style="width: {{ ($expense['total'] / ($totalExpense ?: 1)) * 100 }}%"
                                                            aria-valuenow="{{ $expense['total'] }}" aria-valuemin="0"
                                                            aria-valuemax="{{ $totalExpense }}">
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
                <div class="card p-4 mb-4 border-bottom border-warning border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold text-warning mb-0">
                            Total Inventory Value: <span class="text-dark">{{ money($inventory_value) }}</span>
                        </h4>
                        <span class="badge bg-danger-subtle text-danger p-2 fw-semibold">
                            <i class="fas fa-triangle-exclamation me-1"></i>
                            Low Turnover Warning: {{ $lowTurnoverDays }} Days ({{ $lowTurnoverCount }} SKUs)
                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Value by Category Chart -->
                    <div class="col-lg-6">
                        <div class="card p-4 h-100">
                            <h5 class="card-title fw-bold text-warning">Value by Category</h5>
                            <p class="text-muted">Horizontal Bar Chart showing stock worth.</p>
                            <div class="chart-placeholder text-center py-5 bg-light rounded-3 border">
                                <canvas id="inventoryChart" class="w-100" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Details Table -->
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
                                        @foreach ($inventoryByCategory as $item)
                                            @php
                                                $categoryName = $item->category->name ?? 'N/A';
                                                $percent =
                                                    $totalStockValue > 0
                                                        ? ($item->stock_value / $totalStockValue) * 100
                                                        : 0;
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
        const profitChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendData->pluck('label')) !!}, 
                datasets: [{
                        label: 'Net Profit',
                        data: {!! json_encode($trendData->pluck('net_profit')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Gross Profit',
                        data: {!! json_encode($trendData->pluck('gross_profit')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Revenue',
                        data: {!! json_encode($trendData->pluck('total_revenue')) !!},
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString(); // optional: add currency formatting
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            }
        });


        const incomeCtx = document.getElementById('incomePieChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($incomeData->pluck('source')) !!},
                datasets: [{
                    data: {!! json_encode($incomeData->pluck('amount')) !!},
                    backgroundColor: ['#0d6efd', '#6c757d', '#17a2b8', '#ffc107', '#198754'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const expenseCtx = document.getElementById('expenseBarChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($expenseTrend->pluck('label')) !!}, // changed from 'month' to 'label'
                datasets: [{
                    label: 'Expenses',
                    data: {!! json_encode($expenseTrend->pluck('amount')) !!},
                    backgroundColor: '#dc3545'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '৳ ' + context.formattedValue;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '৳ ' + value; 
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            }
        });


        const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
        const inventoryChart = new Chart(inventoryCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($inventoryByCategory->pluck('category.name')) !!},
                datasets: [{
                    label: "Stock Value ({{ currency() }})",
                    data: {!! json_encode($inventoryByCategory->pluck('stock_value')) !!},
                    backgroundColor: 'rgba(255, 193, 7, 0.7)'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
