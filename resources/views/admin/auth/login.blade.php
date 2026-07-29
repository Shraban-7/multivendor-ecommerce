@extends('admin.layouts.auth')
@section('title', 'Admin Login')

@section('content')
    <div class="max-w-md mx-auto w-full px-4">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="px-6 py-5 border-b border-border">
                <h1 class="text-xl font-bold text-ink mb-1">Admin Login</h1>
                <p class="text-sm text-ink-tertiary mb-0">Sign in to the admin panel</p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="px-6 py-5 space-y-4">
                @csrf

                @if (session('error'))
                    <div class="text-sm text-feedback-danger bg-red-50 border border-red-100 rounded-xs px-3 py-2">
                        {{ session('error') }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-ink mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3 py-2 text-sm border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-ink mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 text-sm border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep">
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-ink-secondary">
                    <input type="checkbox" name="remember" value="1" class="rounded-xs border-border">
                    Remember me
                </label>

                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>
        </div>
    </div>
@endsection
