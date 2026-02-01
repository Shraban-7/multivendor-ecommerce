@extends('seller.layouts.app')
@section('title', 'Sales Report')
@section('content')

<h4>Employee Sales Report</h4>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control"
                    value="{{ request('start_date', $startDate->toDateString()) }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control"
                    value="{{ request('end_date', $endDate->toDateString()) }}">
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    Filter
                </button>

                <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100">
                    Clear
                </a>
            </div>
        </form>


        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th class="text-center">Orders</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td class="text-center">{{ $employee->total_orders  }}</td>
                    <td class="text-end">{{ money($employee->total_sales) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">
                        No employees found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection