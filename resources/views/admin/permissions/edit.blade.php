@extends('admin.layouts.app')
@section('title', 'Edit Role')
@section('content')

    <?php
    $permissionNames = $role->permissionNames;
    ?>

    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="fw-bold mb-0"><span class="fw-normal">Edit Role</span></h4>
    </div>

    <div class="card w-50">
        <div class="card-body">
            @if (hasPermission('admin.roles.update'))
                <form id="editRoleForm" action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" class="form-control" name="title" value="{{ $role->title }}" required>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label">Permissions ({{ count($permissionNames) }})</label>
                            <div class="form-check">
                                <input type="checkbox" id="select_all" />
                                <label class="form-check-label" for="select_all">Select All</label>
                            </div>
                        </div>
                        <div class="border rounded p-3" id="editPermissions">
                            @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]"
                                        value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                        @checked(in_array($permission->name, $permissionNames))>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="history.back()">Back</button>
                        @if ($role->name != 'super_admin')
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        @endif
                    </div>
                </form>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            $('#select_all').on('click', function() {
                let chk_status = this.checked;
                $('.permission-checkbox').each(function() {
                    this.checked = chk_status;
                });
            });
        </script>
    @endpush

@endsection
