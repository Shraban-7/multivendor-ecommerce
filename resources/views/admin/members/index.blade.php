@extends('admin.layouts.app')
@section('title', 'Admin List')
@section('content')

    <div class="d-flex justify-content-between align-items-end mb-2">
        <h4 class="mb-0">Admin List</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white mb-3">
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
                            <div class="d-flex">
                                <a class="btn btn-sm btn-light border me-1"
                                    href="{{ route('admin.admins.edit', $admin->id) }}">
                                    <i class="ri-edit-box-line icon-xs me-1"></i>Edit
                                </a>
                                @if ($admin->name != 'Super Admin')
                                    <button class="btn btn-sm btn-danger" type="button"
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
