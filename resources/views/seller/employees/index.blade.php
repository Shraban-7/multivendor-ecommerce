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
                    <th scope="col">Phone</th>
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
                        <td>{{ $employee?->phone }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>
                            @if ($employee->is_active)
                                <span class="badge text-bg-success">Active</span>
                            @else
                                <span class="badge text-bg-warning">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $employee->created_at->format('d/m/Y h:i A') }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('seller.employees.edit', $employee->id) }}"
                                class="btn btn-primary btn-sm w-lg-auto">
                                <i data-feather="edit" class="icon-xs me-1"></i> Edit
                            </a>

                            <button type="button" class="btn btn-warning btn-sm w-lg-auto" data-bs-toggle="modal"
                                data-bs-target="#permissionModal{{ $employee->id }}">
                                <i class="bi bi-shield-lock"></i> Permissions
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="permissionModal{{ $employee->id }}" tabindex="-1"
                        aria-labelledby="permissionModalLabel{{ $employee->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <form method="POST" action="{{ route('seller.employees.set_permissions', $employee->id) }}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="permissionModalLabel{{ $employee->id }}">
                                            Manage Permissions - {{ $employee->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                                            value="{{ $permission['name'] }}"
                                                            id="perm_{{ $employee->id }}_{{ $permission['name'] }}"
                                                            {{ in_array($permission['name'], $employee->permissions ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="perm_{{ $employee->id }}_{{ $permission['name'] }}">
                                                            {{ $permission['title'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
