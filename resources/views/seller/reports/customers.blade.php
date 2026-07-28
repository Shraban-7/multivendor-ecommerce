@extends('seller.layouts.app')
@section('title', 'Customers Reports')

@section('content')
    <div>
        <header>
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1 text-dark">Customer Report</h2>
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
                            <select name="range" class="form-select form-select-sm" onchange="toggleCustomDates(this.value)">
                                <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6" id="customDateRange" style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
                            <label class="form-label small">Custom Date Range</label>
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

        <div class="row mb-5 g-4">
            @php
                $kpis = [
                    ['label' => 'Total Customers', 'value' => $allTimeTotalCustomers, 'change' => 0, 'icon' => 'fa-users', 'color' => 'primary', 'note' => 'all time'],
                    ['label' => 'New Customers', 'value' => $newCustomersCurrent, 'change' => $newCustomersChange, 'icon' => 'fa-user-plus', 'color' => 'info', 'note' => 'this period'],
                    ['label' => 'Returning %', 'value' => $returningPercentage . '%', 'change' => null, 'icon' => 'fa-redo-alt', 'color' => 'success', 'note' => 'vs previous'],
                    ['label' => 'Avg CLV', 'value' => money($avgClvCurrent), 'change' => $avgClvChange, 'icon' => 'fa-hand-holding-usd', 'color' => 'warning', 'note' => 'last period'],
                    ['label' => 'Avg Orders/Cust', 'value' => $avgOrdersPerCustomerCurrent, 'change' => $avgOrdersPerCustomerChange, 'icon' => 'fa-cart-shopping', 'color' => 'secondary', 'note' => 'vs last period'],
                ];
            @endphp
            @foreach ($kpis as $index => $kpi)
                <div class="col-xl-{{ in_array($index, [2, 3, 4]) ? 2 : 3 }} col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 5px solid var(--bs-{{ $kpi['color'] }});">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted text-uppercase small">{{ $kpi['label'] }}</span>
                                <h5 class="fw-bold text-{{ $kpi['color'] }} mb-0">{{ $kpi['value'] }}</h5>
                            </div>
                            <i class="fas {{ $kpi['icon'] }} opacity-50 fs-4"></i>
                        </div>
                        @if (!is_null($kpi['change']))
                            <small class="{{ $kpi['change'] >= 0 ? 'text-success' : 'text-danger' }} fw-semibold mt-2">
                                <i class="fas fa-arrow-{{ $kpi['change'] >= 0 ? 'up' : 'down' }} me-1"></i>{{ abs($kpi['change']) }}%
                            </small>
                        @endif
                        <small class="text-muted small">{{ $kpi['note'] }}</small>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3">Customer Growth Trend</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <ul class="nav nav-pills nav-pills-sm" id="customerTabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="total-tab" data-bs-toggle="pill" href="#total" role="tab">Total Customers</a></li>
                            <li class="nav-item"><a class="nav-link" id="new-returning-tab" data-bs-toggle="pill" href="#new-returning" role="tab">New vs Returning</a></li>
                        </ul>
                        <span class="text-muted small">Last 12 Months</span>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="total" role="tabpanel">
                            <canvas id="totalCustomersChart" height="200"></canvas>
                        </div>
                        <div class="tab-pane fade" id="new-returning" role="tabpanel">
                            <canvas id="newReturningChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3 text-success">Top High-Value Customers</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="small fw-semibold text-muted">Customer Name</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Orders</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topCustomers as $index => $cust)
                                    <tr class="{{ $index === 0 ? 'fw-semibold' : '' }}">
                                        <td>@if ($index === 0)<i class="fas fa-trophy me-1 text-warning"></i>@endif{{ $cust['name'] }}</td>
                                        <td class="text-end">{{ $cust['orders'] }}</td>
                                        <td class="text-end text-success fw-semibold">{{ money($cust['spent']) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">No customers found for this period.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 12px;">
                    <h5 class="fw-bold mb-3 text-info">Loyalty & RFM Analysis</h5>
                    <div class="row mb-3 g-3">
                        <div class="col-md-4">
                            <div class="card p-3 bg-light border-0 shadow-sm" style="border-radius: 12px;">
                                <p class="mb-0 small text-muted">Repeat Rate:</p>
                                <h4 class="fw-bold text-info mb-0">42%</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 bg-light border-0 shadow-sm" style="border-radius: 12px;">
                                <p class="mb-0 small text-muted">Avg. Time Between Purchases:</p>
                                <h4 class="fw-bold text-dark mb-0">18 days</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 bg-light border-0 shadow-sm" style="border-radius: 12px;">
                                <p class="mb-0 small text-muted">Next Purchase Likelihood:</p>
                                <h4 class="fw-bold text-success mb-0">High</h4>
                            </div>
                        </div>
                    </div>
                    <h6 class="fw-semibold mt-3 text-secondary">RFM (Recency, Frequency, Monetary) Summary</h6>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="small fw-semibold text-muted">Segment</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Count</th>
                                    <th scope="col" class="small fw-semibold text-muted text-end">Revenue Share</th>
                                    <th scope="col" class="small fw-semibold text-muted">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="fw-semibold table-success">
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
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Actionable Insights</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #1D8A45;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-smile me-3 text-success fa-2x"></i>
                                <p class="mb-0 fw-semibold">Loyal customers up <span class="text-success">9%</span> this month. Maintain personalized offers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #F85606;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-arrow-up-right-dots me-3 text-primary fa-2x"></i>
                                <p class="mb-0 fw-semibold">Returning customers spend <span class="text-primary">30%</span> more per transaction.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; border-left: 4px solid #D93025;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-simple me-3 text-danger fa-2x"></i>
                                <p class="mb-0 fw-semibold">At-Risk segment grew 5%. Launch a re-engagement campaign.</p>
                            </div>
                        </div>
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

        const totalCtx = document.getElementById('totalCustomersChart').getContext('2d');
        new Chart(totalCtx, {
            type: 'line', data: {
                labels: {!! json_encode($chartData['total']['labels']) !!},
                datasets: [{
                    label: 'Total Customers', data: {!! json_encode($chartData['total']['data']) !!},
                    backgroundColor: 'rgba(248, 86, 6, 0.1)', borderColor: '#F85606', borderWidth: 2, fill: true, tension: 0.3
                }]
            }, options: { responsive: true }
        });

        const newReturningCtx = document.getElementById('newReturningChart').getContext('2d');
        new Chart(newReturningCtx, {
            type: 'line', data: {
                labels: {!! json_encode($chartData['new_vs_returning']['labels']) !!},
                datasets: [
                    { label: 'New Customers', data: {!! json_encode($chartData['new_vs_returning']['new']) !!}, borderColor: '#1D8A45', backgroundColor: 'rgba(29, 138, 69, 0.1)', fill: true, tension: 0.3 },
                    { label: 'Returning Customers', data: {!! json_encode($chartData['new_vs_returning']['returning']) !!}, borderColor: '#F85606', backgroundColor: 'rgba(248, 86, 6, 0.1)', fill: true, tension: 0.3 }
                ]
            }, options: { responsive: true }
        });
    </script>
@endpush
