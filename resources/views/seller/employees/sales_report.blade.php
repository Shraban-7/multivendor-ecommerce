@extends('seller.layouts.app')
@section('title', 'Sales Report')
@section('content')

<h4 class="font-bold mb-3 text-ink">Employee Sales Report</h4>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
    <div class="p-5">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-ink-secondary mb-1">Start Date</label>
                <input type="date" name="start_date" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                    value="{{ request('start_date', $startDate->toDateString()) }}">
            </div>

            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-ink-secondary mb-1">End Date</label>
                <input type="date" name="end_date" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                    value="{{ request('end_date', $endDate->toDateString()) }}">
            </div>

            <div class="md:col-span-1 flex items-end gap-2">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors w-full gap-1">
                    Filter
                </button>

                <a href="{{ url()->current() }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium border border-border text-ink-secondary rounded-xs hover:bg-surface-muted focus:outline-none transition-colors w-full gap-1">
                    Clear
                </a>
            </div>
        </form>


        <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-striped table-hover mb-0">
            <thead class="bg-surface-muted">
                <tr>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Employee</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-center">Orders</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td class="text-center">{{ $employee->total_orders  }}</td>
                    <td class="text-right">{{ money($employee->total_sales) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center text-ink-tertiary">
                        No employees found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection