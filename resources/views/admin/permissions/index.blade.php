@extends('admin.layouts.app')
@section('title', 'Permissions')
@section('content')
    <?php
    $isSuperAdmin = admin()->role->name == 'super_admin';
    ?>
    <div class="flex justify-between items-end mb-2">
        <h4 class="mb-0">Permissions</h4>
        @if ($isSuperAdmin)
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i data-feather="plus" class="icon-xs"></i> Add Role
            </button>
        @endif
    </div>

    <div class="overflow-x-auto whitespace-nowrap">
        <table class="w-full text-left text-sm text-ink border-collapse table-bordered bg-white">
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Created At</th>
                    @if ($isSuperAdmin)
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->title }}</td>
                        <td class="flex flex-wrap gap-1">
                            @foreach ($role->permissions as $permission)
                                <span class="badge bg-brand-deep me-1">{{ $permission->title }}</span>
                            @endforeach
                        </td>

                        <td>{{ $role->created_at->format('d/m/Y') }}</td>
                        @if ($isSuperAdmin)
                            <td>
                                <div class="flex gap-2">
                                    @if ($role->title != 'Super Admin')
                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                            class="btn btn-light btn-sm edit-btn">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-sm mx-auto modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createRoleForm" action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Role Name</label>
                            <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="title" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
