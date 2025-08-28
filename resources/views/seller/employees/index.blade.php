@extends('seller.layouts.app')
@section('title', 'Employees')
@section('content')

    <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Employees</h4>
        <a href="{{ route('seller.employees.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Employee
        </a>
    </div>

    <div class="table-responsive">
        <table id="employee-table" class="table table-bordered bg-white mb-3 align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>
                            @if ($employee->is_active)
                                <span class="badge text-bg-success">Active</span>
                            @else
                               <span class="badge text-bg-warning">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $employee->created_at->format('d/m/Y h:i A') }}</td>
                        <td>
                            <a href="{{ route('seller.employees.edit', $employee->id) }}"
                                class="btn btn-primary btn-sm w-lg-auto">
                                <i data-feather="edit" class="icon-xs me-1"></i> Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            new DataTable('#employee-table', {
                responsive: true
            });
        </script>
    @endpush

@endsection
