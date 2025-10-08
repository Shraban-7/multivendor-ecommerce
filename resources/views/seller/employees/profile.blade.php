@extends('seller.layouts.app')
@section('title', 'Edit Profile')
@section('content')

    <div class="card col-6 col-xl-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Edit Profile</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('seller.employees.updateProfile') }}" method="POST">
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

                <input type="hidden" name="is_active" value="1">

                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>

@endsection
