@extends('admin.layouts.app')
@section('title', 'Edit Role')
@section('content')

    <?php
    $permissionNames = $role->permissionNames;
    ?>

    <div class="flex justify-between items-end mb-3">
        <h4 class="font-bold mb-0"><span class="font-normal">Edit Role</span></h4>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden w-50">
        <div class="p-5">
            @if (hasPermission('admin.roles.update'))
                <form id="editRoleForm" action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Role Name</label>
                        <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="title" value="{{ $role->title }}" required>
                    </div>

                    <div class="mb-3">
                        <div class="flex justify-between">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Permissions ({{ count($permissionNames) }})</label>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="select_all" />
                                <label class="text-sm text-ink" for="select_all">Select All</label>
                            </div>
                        </div>
                        <div class="border rounded p-3" id="editPermissions">
                            @foreach ($permissions as $permission)
                                <div class="flex items-center gap-2">
                                    <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand permission-checkbox" type="checkbox" name="permissions[]"
                                        value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                        @checked(in_array($permission->name, $permissionNames))>
                                    <label class="text-sm text-ink" for="perm_{{ $permission->id }}">
                                        {{ $permission->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" class="btn btn-light" onclick="history.back()">Back</button>
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
