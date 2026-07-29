@extends('seller.layouts.app')
@section('title', 'Employees')
@section('content')

    <div class="flex justify-between items-center mb-3 flex-wrap gap-2">
        <h4 class="font-bold mb-0 text-ink">Employees</h4>
        <a href="{{ route('seller.employees.create') }}" class="btn btn-primary">
            <i data-lucide="plus"></i> Add Employee
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="employee-table" class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Name</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Phone</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Email</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Status</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Created At</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Actions</th>
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
                                <span class="badge badge-soft-success">Active</span>
                            @else
                                <span class="badge badge-soft-warning">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $employee->created_at->format('d/m/Y h:i A') }}</td>
                        <td class="flex gap-2">
                            <a href="{{ route('seller.employees.edit', $employee->id) }}"
                                class="btn btn-primary btn-sm">
                                <i data-lucide="edit" class="icon-xs"></i> Edit
                            </a>

                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#permissionModal{{ $employee->id }}">
                                <i data-lucide="shield"></i> Permissions
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="permissionModal{{ $employee->id }}" tabindex="-1"
                        aria-labelledby="permissionModalLabel{{ $employee->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <form method="POST" action="{{ route('seller.employees.set_permissions', $employee->id) }}">
                                @csrf
                                <div class="modal-content border-0">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="permissionModalLabel{{ $employee->id }}">
                                            Manage Permissions - {{ $employee->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            @foreach ($permissions as $permission)
                                                <div class="md:col-span-1">
                                                    <div class="flex items-center gap-2">
                                                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="permissions[]"
                                                            value="{{ $permission['name'] }}"
                                                            id="perm_{{ $employee->id }}_{{ $permission['name'] }}"
                                                            {{ in_array($permission['name'], $employee->permissions ?? []) ? 'checked' : '' }}>
                                                        <label class="text-sm text-ink"
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