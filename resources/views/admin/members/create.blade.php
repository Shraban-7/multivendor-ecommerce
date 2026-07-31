@php
    $pageTitle = 'Add Admin';
@endphp
@extends('admin.layouts.app')
@section('title', $pageTitle)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="shield" class="text-brand" style="width:12px;height:12px;"></i>
                    <a href="{{ route('admin.admins.index') }}" class="hover:text-ink-soft transition-colors">Admin Members</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $pageTitle }}</span>
                </nav>
                <h1 class="text-xl font-bold text-ink-emphasis mb-1">{{ $pageTitle }}</h1>
                <p class="text-sm text-ink-secondary mb-0">Invite a teammate to access the admin panel and assign their starting role.</p>
            </div>
            <div>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
    {{-- ═══ FORM ═══ --}}
    <div class="lg:col-span-2">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="user-plus" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Account details</h5>
            </div>
            <form action="{{ route('admin.admins.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Full name <span class="text-feedback-danger">*</span></label>
                        <input type="text" name="name" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Email <span class="text-feedback-danger">*</span></label>
                        <input type="email" name="email" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Assign role <span class="text-feedback-danger">*</span></label>
                    <select name="role_id" required
                            class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $role->is_default ? 'selected' : '' }}>{{ $role->title }}</option>
                        @endforeach
                    </select>
                    <small class="text-ink-tertiary mt-1 block">Roles control which areas of the admin panel this user can access.</small>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Password <span class="text-feedback-danger">*</span></label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Confirm password <span class="text-feedback-danger">*</span></label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                </div>
                <div class="bg-amber-50 rounded-xs p-3 flex items-start gap-2 text-xs text-ink-secondary">
                    <i data-lucide="info" class="text-feedback-warning shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
                    <span>The admin must reset their password after first login. Choose a strong password — at least 8 characters.</span>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-border">
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="user-plus" class="icon-xs me-1"></i> Create Admin
                    </button>
                </div>
            </form>
        </section>
    </div>

    {{-- ═══ TIPS SIDEBAR ═══ --}}
    <div class="lg:col-span-1 space-y-3">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="lightbulb" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Onboarding checklist</h5>
            </div>
            <div class="p-5">
                <ul class="space-y-3 mb-0">
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center"><i data-lucide="user-check" style="width:14px;height:14px;"></i></span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Pick the right role</p>
                            <small class="text-ink-tertiary">Start with the lowest privilege they need.</small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center"><i data-lucide="mail" style="width:14px;height:14px;"></i></span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Use a real work email</p>
                            <small class="text-ink-tertiary">So they receive login & reset emails.</small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center"><i data-lucide="key-round" style="width:14px;height:14px;"></i></span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Set a strong password</p>
                            <small class="text-ink-tertiary">Mix upper, lower, digits, symbols.</small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center"><i data-lucide="shield-check" style="width:14px;height:14px;"></i></span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Audit later</p>
                            <small class="text-ink-tertiary">Review the admin roster monthly.</small>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</div>

@endsection
