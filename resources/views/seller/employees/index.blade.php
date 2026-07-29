@extends('seller.layouts.app')
@section('title', 'Employees')
@section('content')

    <div class="flex justify-between items-center mb-3 flex-wrap gap-2">
        <h4 class="font-bold mb-0 text-ink">Employees</h4>
        <a href="{{ route('seller.employees.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">
            <i class="bi bi-plus-lg"></i> Add Employee
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="employee-table" class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover bg-white mb-3 align-middle">
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
                                class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors w-lg-auto gap-1">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </a>

                            <button type="button" class="inline-flex items-center justify-center px-3 py-1.5 bg-feedback-warning text-white text-sm font-medium rounded-xs hover:bg-amber-800 focus:outline-none transition-colors w-lg-auto gap-1" data-bs-toggle="modal"
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
                                        <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Save Permissions</button>
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