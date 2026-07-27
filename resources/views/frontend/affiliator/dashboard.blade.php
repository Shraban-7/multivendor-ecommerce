@extends('frontend.layouts.app')
@section('title', 'Affiliate Dashboard')

@section('dashboard')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-bold text-[#191919]">Affiliate Dashboard</h1>
            <form method="GET" action="{{ route('affiliator.dashboard') }}">
                <select name="filter" onchange="this.form.submit()"
                    class="px-3 py-2 border border-[#E5E5E5] rounded-sm text-sm text-[#595959] focus:outline-none focus:border-[#F85606] bg-white">
                    <option value="week" {{ $filter=='week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ $filter=='month' ? 'selected' : '' }}>This Month</option>
                    <option value="3months" {{ $filter=='3months' ? 'selected' : '' }}>Last 3 Months</option>
                    <option value="6months" {{ $filter=='6months' ? 'selected' : '' }}>Last 6 Months</option>
                    <option value="year" {{ $filter=='year' ? 'selected' : '' }}>Last 1 Year</option>
                </select>
            </form>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-sm border border-[#E5E5E5] p-5">
                <p class="text-sm text-[#767676] mb-1">Total Clicks</p>
                <p class="text-2xl font-bold text-purple-600">{{ $clicks }}</p>
            </div>
            <div class="bg-white rounded-sm border border-[#E5E5E5] p-5">
                <p class="text-sm text-[#767676] mb-1">Total Earnings</p>
                <p class="text-2xl font-bold text-green-600">{{ money($earnings) }}</p>
            </div>
            <div class="bg-white rounded-sm border border-[#E5E5E5] p-5">
                <p class="text-sm text-[#767676] mb-1">Pending Earnings</p>
                <p class="text-2xl font-bold text-yellow-500">{{ money($pending_earnings) }}</p>
            </div>
            <div class="bg-white rounded-sm border border-[#E5E5E5] p-5">
                <p class="text-sm text-[#767676] mb-1">Total Orders</p>
                <p class="text-2xl font-bold text-blue-600">{{ $total_orders }}</p>
            </div>
        </div>

        {{-- Chart --}}
        <div class="bg-white rounded-sm border border-[#E5E5E5] p-5">
            <h2 class="text-base font-semibold text-[#191919] mb-4">Performance Overview</h2>
            <canvas id="earningsChart" class="w-full" style="max-height:320px"></canvas>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('earningsChart'), {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: "Earnings ({{ currency() }})",
                        data: @json($earnings_data),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: 4
                    },
                    {
                        label: "Pending Earnings ({{ currency() }})",
                        data: @json($pending_data),
                        borderColor: '#eab308',
                        backgroundColor: 'rgba(234,179,8,0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#eab308',
                        pointRadius: 4
                    },
                    {
                        label: 'Clicks',
                        data: @json($clicks_data),
                        borderColor: '#9333ea',
                        backgroundColor: 'rgba(147,51,234,0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#9333ea',
                        pointRadius: 4
                    },
                    {
                        label: 'Orders',
                        data: @json($orders_data),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
    @endpush
@endsection
