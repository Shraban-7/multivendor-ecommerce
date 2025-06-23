@extends('admin.layouts.app')
@section('title', 'Add Admin')
@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>Edit Admin</h4>
                <hr>
                <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                    @csrf
                    @if($admin->name != 'Super Admin')
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role_id" class="form-select" required>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected($admin->role_id == $role->id)>{{ $role->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
