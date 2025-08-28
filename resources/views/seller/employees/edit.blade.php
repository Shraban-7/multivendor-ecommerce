@extends('seller.layouts.app')
@section('title', 'Edit Employee')
@section('content')

    <div class="card col-6">
        <div class="card-header bg-white">
            <h5 class="mb-0">Edit Employee</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('seller.employees.update', $employee->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Change Active Status</label>
                    <select class="form-select" name="is_active" aria-label="Change Status">
                        <option value="0" {{ $employee->is_active == 0 ? 'selected':''}}>Inactive</option>
                        <option value="1" {{ $employee->is_active == 1 ? 'selected':''}}>Active</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('seller.employees.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

@endsection
