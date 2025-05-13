@extends('admin.layouts.app')
@section('title', 'Customers')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Customers</h4>
</div>

<div class="table-responsive">
    <table id="customer-table" class="table table-bordered bg-white mb-3">
        <thead>
            <tr>
                <th scope="col">Customer</th>
                <th scope="col">Country</th>
                <th scope="col">Phone Code</th>
                <th scope="col">Phone</th>
                <th scope="col">Email</th>
                <th scope="col">Registration Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td>
                    <x-user :user="$customer" />
                </td>


                <td> {{ $customer->country->name ?? '' }} </td>
                <td> {{ $customer->country->phone_code ?? '' }} </td>
                <td> {{ $customer->phone }} </td>
                <td> {{ $customer->email }} </td>
                <td> {{ $customer->created_at->format('d/m/Y h:i A') }} </td>
                <td>
                    <div>
                        <button class="btn btn-light border  btn-sm mb-1" data-bs-toggle="modal"
                            data-bs-target=""><i data-feather="edit"
                                class="icon-xs"></i> Edit
                        </button>
                        <button class="btn btn-danger border btn-sm me-1 mb-1" data-bs-toggle="modal"
                            data-bs-target="">
                            <i data-feather="trash" class="icon-xs"></i> Delete
                        </button>
                    </div>
                </td>
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
