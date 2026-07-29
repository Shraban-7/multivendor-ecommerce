@extends('seller.layouts.app')
@section('title', 'Financial Reports')

@section('content')
    <div>
        <header>
            <div class="grid grid-cols-1 items-center mb-4">
                <div class="md:col-span-1 mb-3 mb-md-0">
                    <h2 class="font-bold mb-1 text-ink">Financial Reports</h2>
                    <nav aria-label="flex items-center gap-2 text-sm">
                        <ol class="flex items-center gap-2 text-sm mb-0 text-sm">
                            <li class="text-ink-tertiary text-ink-tertiary">Reports</li>
                            <li class="text-ink-tertiary active font-semibold" aria-current="page">Financial Reports</li>
                        </ol>
                    </nav>
                </div>
                <div class="md:col-span-1">
                    <form method="GET" class="grid grid-cols-1 gap-2 justify-end">
                        <div class="md:col-span-1 sm:col-span-1">
                            <select name="range" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors-sm" onchange="toggleCustomDates(this.value)">
                                <option disabled selected>--select--</option>
                                <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="md:col-span-1 sm:col-span-1" id="customDateRange" style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
                            <div class="flex flex">
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                        </div>
                        <div class="md:col-span-1 sm:col-span-full flex items-end">
                            <button class="btn btn-primary btn-sm w-full">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 mb-5 g-3">
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #F85606;">
                    <div class="flex items-center">
                        <i data-lucide="wallet" class="me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="font-bold mb-0 text-ink">{{ money($currentMetrics['total_revenue']) }}</h5>
                            <p class="text-ink-tertiary text-sm mb-0">Total Revenue</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['revenue'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                            <i data-lucide="{{ $changes['revenue'] >= 0 ? 'arrow-up' : 'arrow-down' }}" class="me-1"></i>
                            {{ number_format(abs($changes['revenue']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #0ea5e9;">
                    <div class="flex items-center">
                        <i data-lucide="hand-coins" class="me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="font-bold mb-0 text-ink">{{ money($currentMetrics['gross_profit']) }}</h5>
                            <p class="text-ink-tertiary text-sm mb-0">Gross Profit</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['gross_profit'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                            <i data-lucide="{{ $changes['gross_profit'] >= 0 ? 'arrow-up' : 'arrow-down' }}" class="me-1"></i>
                            {{ number_format(abs($changes['gross_profit']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #1D8A45;">
                    <div class="flex items-center">
                        <i data-lucide="coins" class="me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="font-bold mb-0 text-ink">{{ money($currentMetrics['net_profit']) }}</h5>
                            <p class="text-ink-tertiary text-sm mb-0">Net Profit</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['net_profit'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                            <i data-lucide="{{ $changes['net_profit'] >= 0 ? 'arrow-up' : 'arrow-down' }}" class="me-1"></i>
                            {{ number_format(abs($changes['net_profit']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #D93025;">
                    <div class="flex items-center">
                        <i data-lucide="wallet" class="me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="font-bold mb-0 text-ink">{{ money($currentMetrics['total_expense']) }}</h5>
                            <p class="text-ink-tertiary text-sm mb-0">Total Expenses</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['expense'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                            <i data-lucide="{{ $changes['expense'] >= 0 ? 'arrow-up' : 'arrow-down' }}" class="me-1"></i>
                            {{ number_format(abs($changes['expense']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #B7791A;">
                    <div class="flex items-center">
                        <i data-lucide="boxes" class="me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="font-bold mb-0 text-ink">{{ money($inventory_value) }}</h5>
                            <p class="text-ink-tertiary text-sm mb-0">Inventory Value</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #637381;">
                    <div class="flex items-center">
                        <i data-lucide="percent" class="me-3 opacity-50" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="font-bold mb-0 text-ink">{{ number_format($currentMetrics['profit_margin'], 2) }}%</h5>
                            <p class="text-ink-tertiary text-sm mb-0">Profit Margin</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="{{ $changes['profit_margin'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                            <i data-lucide="{{ $changes['profit_margin'] >= 0 ? 'arrow-up' : 'arrow-down' }}" class="me-1"></i>
                            {{ number_format(abs($changes['profit_margin']), 2) }}% Change
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="financialTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pnl-tab" data-bs-toggle="tab" data-bs-target="#pnl" type="button" role="tab" aria-controls="pnl" aria-selected="true"><i data-lucide="chart-line" class="me-2"></i>Profit & Loss</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button" role="tab" aria-controls="income" aria-selected="false"><i data-lucide="banknote" class="me-2"></i>Income Breakdown</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab" aria-controls="expenses" aria-selected="false"><i data-lucide="hand-coins" class="me-2"></i>Expenses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="false"><i data-lucide="warehouse" class="me-2"></i>Inventory Value</button>
            </li>
        </ul>

        <div class="tab-content" id="financialTabsContent">
            <div class="tab-pane fade show active" id="pnl" role="tabpanel" aria-labelledby="pnl-tab">
                <div class="grid grid-cols-1 gap-4">
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            @php
                                $filterText = match (request('range', 'monthly')) { 'daily' => 'Daily Profit Trend', 'weekly' => 'Weekly Profit Trend', 'monthly' => 'Monthly Profit Trend', 'yearly' => 'Yearly Profit Trend', 'custom' => 'Custom Profit Trend', default => 'Monthly Profit Trend' };
                                $descriptionText = match (request('range', 'monthly')) { 'daily' => 'Net Profit Over the Last 7 Days', 'weekly' => 'Net Profit Over the Last 12 Weeks', 'monthly' => 'Net Profit Over the Last 12 Months', 'yearly' => 'Net Profit Over the Last 5 Years', 'custom' => 'Net Profit Over the Selected Date Range', default => 'Net Profit Over the Last 12 Months' };
                            @endphp
                            <h5 class="font-bold">{{ $filterText }}</h5>
                            <p class="text-ink-tertiary">{{ $descriptionText }}</p>
                            <div class="bg-surface-muted rounded-md border flex items-center justify-center py-5">
                                <canvas id="profitChart" class="w-full" style="max-height: 300px;"></canvas>
                            </div>
                            <div class="alert {{ $currentMetrics['profit_margin'] >= 0 ? 'alert-success' : 'alert-danger' }} mt-3 font-bold text-center" role="alert">
                                Net Profit Margin: {{ number_format($currentMetrics['profit_margin'], 2) }}%
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            <h5 class="font-bold">P&L Summary</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-ink border-collapse">
                                    <thead class="bg-surface-muted">
                                        <tr>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Category</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Amount</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Change %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-semibold">Total Sales</td>
                                            <td class="text-right">{{ money($currentMetrics['total_revenue']) }}</td>
                                            <td class="text-right {{ $changes['revenue'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">{{ $changes['revenue'] >= 0 ? '+' : '' }}{{ number_format($changes['revenue'], 2) }}%</td>
                                        </tr>
                                        <tr>
                                            <td class="font-semibold">Cost of Goods Sold</td>
                                            <td class="text-right">{{ money($currentMetrics['total_product_cost']) }}</td>
                                            <td class="text-right {{ $changes['gross_profit'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">{{ $changes['gross_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['gross_profit'], 2) }}%</td>
                                        </tr>
                                        <tr>
                                            <td class="font-semibold">Gross Profit</td>
                                            <td class="text-right">{{ money($currentMetrics['gross_profit']) }}</td>
                                            <td class="text-right {{ $changes['gross_profit'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">{{ $changes['gross_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['gross_profit'], 2) }}%</td>
                                        </tr>
                                        <tr class="bg-emerald-50">
                                            <td class="font-bold">Net Profit</td>
                                            <td class="text-right font-bold">{{ money($currentMetrics['net_profit']) }}</td>
                                            <td class="text-right font-bold {{ $changes['net_profit'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">{{ $changes['net_profit'] >= 0 ? '+' : '' }}{{ number_format($changes['net_profit'], 2) }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden {{ $currentMetrics['profit_margin'] >= 0 ? 'bg-feedback-success' : 'bg-feedback-danger' }} text-white p-3 mt-3 border-0">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold">Current Profit Margin</span>
                                    <span class="font-bold text-xl">{{ number_format($currentMetrics['profit_margin'], 2) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="income" role="tabpanel" aria-labelledby="income-tab">
                <div class="grid grid-cols-1 gap-4">
                    <div class="lg:col-span-5">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            <h5 class="font-bold text-feedback-info">Income Source Proportions</h5>
                            <p class="text-ink-tertiary">Visual breakdown of all income streams.</p>
                            <div class="bg-surface-muted rounded-md border flex items-center justify-center py-5">
                                <canvas id="incomePieChart" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-7">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            <h5 class="font-bold">Income Data Table</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-ink border-collapse">
                                    <thead class="bg-surface-muted">
                                        <tr>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Source</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Amount</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Contribution %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incomeData as $income)
                                            <tr>
                                                <td class="font-semibold">{{ $income['source'] }}</td>
                                                <td class="text-right">{{ money($income['amount']) }}</td>
                                                <td class="text-right">{{ number_format($income['percentage'], 2) }}%</td>
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
                <div class="grid grid-cols-1 mb-4 g-3">
                    <div class="lg:col-span-1 md:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #D93025;">
                            <p class="text-ink-tertiary mb-0">Total Expense</p>
                            <h4 class="font-bold mb-0 text-feedback-danger">{{ money($totalExpense) }}</h4>
                        </div>
                    </div>
                    <div class="lg:col-span-1 md:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #B7791A;">
                            <p class="text-ink-tertiary mb-0">Highest Expense Category</p>
                            <h4 class="font-bold mb-0 text-feedback-warning">{{ $highestExpense->category->name ?? 'N/A' }} ({{ money($highestExpense->total ?? 0) }})</h4>
                        </div>
                    </div>
                    <div class="lg:col-span-1 md:col-span-full">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #637381;">
                            <p class="text-ink-tertiary mb-0">Expense Growth %</p>
                            <h4 class="font-bold mb-0 text-{{ $expenseGrowth >= 0 ? 'danger' : 'success' }}">
                                <i data-lucide="{{ $expenseGrowth >= 0 ? 'arrow-up' : 'arrow-down' }}" class="me-1"></i>
                                {{ number_format($expenseGrowth, 2) }}%
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            <h5 class="font-bold text-feedback-danger">Expense Trend</h5>
                            <p class="text-ink-tertiary">{{ ucfirst(request('range')) }} expense comparison.</p>
                            <div class="bg-surface-muted rounded-md border flex items-center justify-center py-5">
                                <canvas id="expenseBarChart" class="w-full" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            <h5 class="font-bold">Expense Breakdown Table</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-ink border-collapse">
                                    <thead class="bg-surface-muted">
                                        <tr>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Category</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Amount</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Change</th>
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
                                                <td class="font-semibold">{{ $categoryName }}</td>
                                                <td class="text-right">{{ money($expense['total']) }}</td>
                                                <td class="text-right {{ $change >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">{{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%</td>
                                            </tr>
                                            <tr class="mb-2">
                                                <td colspan="4" class="p-1">
                                                    <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden" style="height: 5px;">
                                                        <div class="h-full bg-brand-deep rounded-full transition-all bg-{{ $loop->index % 2 == 0 ? 'warning' : 'primary' }}" role="w-full h-2 bg-surface-muted rounded-full overflow-hiddenbar"
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
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; border-b: 4px solid #B7791A;">
                    <div class="flex justify-between items-center flex-wrap gap-2">
                        <h4 class="font-bold mb-0">Total Inventory Value: <span class="text-ink">{{ money($inventory_value) }}</span></h4>
                        <span class="badge p-2 badge-soft-danger">
                            <i data-lucide="triangle-alert" class="me-1"></i> Low Turnover Warning: {{ $lowTurnoverDays }} Days ({{ $lowTurnoverCount }} SKUs)
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            <h5 class="font-bold text-feedback-warning">Value by Category</h5>
                            <p class="text-ink-tertiary">Horizontal Bar Chart showing stock worth.</p>
                            <div class="bg-surface-muted rounded-md border flex items-center justify-center py-5">
                                <canvas id="inventoryChart" class="w-full" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                            <h5 class="font-bold">Inventory Details</h5>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-ink border-collapse">
                                    <thead class="bg-surface-muted">
                                        <tr>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Category</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">SKU Count</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Stock Value</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">% of Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inventoryByCategory as $item)
                                            @php
                                                $categoryName = $item->category->name ?? 'N/A';
                                                $percent = $totalStockValue > 0 ? ($item->stock_value / $totalStockValue) * 100 : 0;
                                            @endphp
                                            <tr>
                                                <td class="font-semibold">{{ $categoryName }}</td>
                                                <td class="text-right">{{ $item->sku_count }}</td>
                                                <td class="text-right">{{ money($item->stock_value) }}</td>
                                                <td class="text-right">{{ number_format($percent, 2) }}%</td>
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
