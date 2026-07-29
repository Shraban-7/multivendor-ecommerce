@extends('seller.layouts.app')
@section('title', 'Add Employee')
@section('content')

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden w-1/2" style="border-radius: 12px;">
    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
        <h5 class="font-semibold mb-0">Add New Employee</h5>
    </div>
    <div class="p-5">
        <form action="{{ route('seller.employees.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-xs font-medium text-ink-secondary mb-1">Name</label>
                <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="block text-xs font-medium text-ink-secondary mb-1">Phone</label>
                <input type="text" name="phone" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('phone') }}" required>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-ink-secondary mb-1">Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-ink-secondary mb-1">Password</label>
                <input type="password" name="password" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-ink-secondary mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('seller.employees.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>

@endsection