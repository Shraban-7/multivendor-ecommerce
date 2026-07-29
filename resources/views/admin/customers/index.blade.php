@extends('admin.layouts.app')
@section('title', 'Customers')
@section('content')

<div class="flex justify-between items-end mb-3">
    <h4 class="mb-0">Customers</h4>
</div>

<div class="overflow-x-auto">
    <table id="customer-table" class="w-full text-left text-sm text-ink border-collapse table-bordered bg-white mb-3">
        <thead>
            <tr>
                <th scope="col">Customer</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Registration Date</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td>
                    <x-user :user="$customer" />
                </td>
                <td> {{ $customer->phone }} </td>
                <td> {{ $customer->email }} </td>
                <td> {{ $customer->created_at->format('d/m/Y h:i A') }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

 @push('scripts')
        <script>
            new DataTable('#customer-table');
        </script>
    @endpush

@endsection
