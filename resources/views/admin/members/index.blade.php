@extends('admin.layouts.app')
@section('title', 'Admin List')
@section('content')

    <div class="flex justify-between items-end mb-2">
        <h4 class="mb-0">Admin List</h4>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Registration Time</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->role->title }}</td>
                        <td>{{ $admin->created_at->format('d M y h:i A') }}</td>
                        <td>
                            <div class="flex">
                                <a class="btn btn-light btn-sm me-1"
                                    href="{{ route('admin.admins.edit', $admin->id) }}">
                                    <i class="ri-edit-box-line icon-xs me-1"></i>Edit
                                </a>
                                @if ($admin->name != 'Super Admin')
                                    <button class="btn btn-danger btn-sm" type="button"
                                        onclick="confirmDelete('{{ route('admin.admins.delete', $admin->id) }}')">
                                        <i class="ri-delete-bin-7-line icon-xs me-1"></i> Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
