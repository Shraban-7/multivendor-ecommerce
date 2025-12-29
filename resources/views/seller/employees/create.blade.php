@extends('seller.layouts.app')
@section('title', 'Add Employee')
@section('content')

<div class="card col-6">
    <div class="card-header bg-white">
        <h5 class="mb-0">Add New Employee</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('seller.employees.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('seller.employees.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
