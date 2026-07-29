@extends('seller.layouts.auth')
@section('title', 'Seller Login')

@section('content')
    <div class="max-w-md mx-auto w-full px-4">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="px-6 py-5 border-b border-border">
                <h1 class="text-xl font-bold text-ink mb-1">Seller Login</h1>
                <p class="text-sm text-ink-tertiary mb-0">Sign in to your seller panel</p>
            </div>

            <form method="POST" action="{{ route('seller.login') }}" class="px-6 py-5 space-y-4">
                @csrf

                @foreach (['success', 'error', 'warning'] as $flash)
                    @if (session($flash))
                        <div class="text-sm rounded-xs px-3 py-2
                            {{ $flash === 'success' ? 'text-feedback-success bg-emerald-50 border border-emerald-100' : '' }}
                            {{ $flash === 'error' ? 'text-feedback-danger bg-red-50 border border-red-100' : '' }}
                            {{ $flash === 'warning' ? 'text-feedback-warning bg-amber-50 border border-amber-100' : '' }}">
                            {{ session($flash) }}
                        </div>
                    @endif
                @endforeach

                <div>
                    <label for="login-email" class="block text-sm font-medium text-ink mb-1">Email</label>
                    <input required type="email" name="email" id="login-email" value="{{ old('email') }}" autofocus
                        class="w-full px-3 py-2 text-sm border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep">
                </div>

                <div>
                    <label for="login-password" class="block text-sm font-medium text-ink mb-1">Password</label>
                    <input required type="password" name="password" id="login-password"
                        class="w-full px-3 py-2 text-sm border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep">
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-ink-secondary">
                    <input type="checkbox" name="remember" value="1" class="rounded-xs border-border">
                    Remember me
                </label>

                <button type="submit" class="btn btn-primary w-full">Login</button>

                <p class="text-center text-sm text-ink-tertiary mb-0">
                    Don't have an account?
                    <a href="{{ route('seller.signup') }}" class="text-brand hover:text-brand-deep">Register here</a>
                </p>
            </form>
        </div>
    </div>
@endsection
