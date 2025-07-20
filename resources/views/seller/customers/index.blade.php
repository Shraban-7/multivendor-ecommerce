@extends('seller.layouts.app')
@section('title', 'Customers')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">
    <h4 class="mb-0">Customers</h4>
</div>

<div class="table-responsive">
    <table id="customer-table" class="table table-bordered bg-white mb-3 align-middle">
        <thead class="table-light">
            <tr>
                <th scope="col">Customer</th>
                <th scope="col">Country</th>
                <th scope="col">Phone Code</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Registration Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td><x-user :user="$customer" /></td>
                    <td>{{ $customer->country->name ?? '' }}</td>
                    <td>{{ $customer->country->phone_code ?? '' }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td class="text-break">{{ $customer->email }}</td>
                    <td>{{ $customer->created_at->format('d/m/Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
    <script>
        new DataTable('#customer-table', {
            responsive: true
        });
    </script>
@endpush

@endsection
