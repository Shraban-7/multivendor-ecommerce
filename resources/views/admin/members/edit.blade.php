@php
    $pageTitle = 'Edit Admin';
    $isSuper = $admin->name === 'Super Admin';
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
                    <span class="text-ink-soft font-semibold">{{ $admin->name }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    @if ($isSuper)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500 text-white">
                            <i data-lucide="crown" style="width:11px;height:11px;" class="me-1"></i> Super Admin
                        </span>
                    @endif
                </div>
                <p class="text-sm text-ink-secondary mb-0">
                    {{ $admin->email }} · joined {{ $admin->created_at->diffForHumans() }}
                </p>
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
                <i data-lucide="user-cog" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Account details</h5>
            </div>
            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Full name <span class="text-feedback-danger">*</span></label>
                        <input type="text" name="name" value="{{ $admin->name }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                               @disabled($isSuper)>
                        @if ($isSuper)
                            <small class="text-ink-tertiary mt-1 block">Super Admin name cannot be changed.</small>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Email <span class="text-feedback-danger">*</span></label>
                        <input type="email" name="email" value="{{ $admin->email }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                </div>
                @unless ($isSuper)
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Role <span class="text-feedback-danger">*</span></label>
                        <select name="role_id" required
                                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected($admin->role_id == $role->id)>{{ $role->title }}</option>
                            @endforeach
                        </select>
                        <small class="text-ink-tertiary mt-1 block">Move the admin to a different permission set without resetting their password.</small>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Reset password</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <input type="password" name="password" minlength="8" placeholder="Leave blank to keep current"
                                   class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            <input type="password" name="password_confirmation" minlength="8" placeholder="Confirm new password"
                                   class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        </div>
                        <small class="text-ink-tertiary mt-1 block">Only fill in to set a new password. The current password remains otherwise.</small>
                    </div>
                @else
                    <div class="p-3 rounded-xs bg-amber-50 flex items-start gap-2 text-xs text-ink-secondary">
                        <i data-lucide="lock" class="text-feedback-warning shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
                        <span>The Super Admin role is locked. Name and role cannot be changed from this page.</span>
                    </div>
                @endunless

                <div class="flex justify-end gap-2 pt-2 border-t border-border">
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" class="icon-xs me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </section>
    </div>

    {{-- ═══ ACCOUNT SUMMARY SIDEBAR ═══ --}}
    <div class="lg:col-span-1 space-y-3">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="id-card" class="text-feedback-info" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">{{ $admin->name }}</h5>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <span class="shrink-0 w-14 h-14 rounded-full {{ $isSuper ? 'bg-rose-50 text-rose-500' : 'bg-info-tint text-feedback-info' }} flex items-center justify-center font-bold text-lg">
                        @if ($isSuper)
                            <i data-lucide="crown" style="width:22px;height:22px;"></i>
                        @else
                            {{ mb_substr($admin->name, 0, 1) }}
                        @endif
                    </span>
                    <div class="min-w-0">
                        <p class="mb-1 font-semibold text-ink-emphasis truncate">{{ $admin->name }}</p>
                        <small class="text-ink-tertiary">{{ $admin->email }}</small>
                    </div>
                </div>
                <dl class="text-sm space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Role</dt>
                        <dd>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $isSuper ? 'bg-rose-500 text-white' : 'bg-info-tint text-feedback-info' }}">
                                {{ $admin->role->title ?? 'Unassigned' }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Admin ID</dt>
                        <dd class="font-mono text-ink-emphasis">#{{ $admin->id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Joined</dt>
                        <dd class="text-ink-emphasis">{{ $admin->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Last updated</dt>
                        <dd class="text-ink-emphasis">{{ $admin->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
                @unless ($isSuper)
                    <div class="mt-4 pt-3 border-t border-border">
                        <button type="button" class="btn btn-danger btn-sm w-full"
                                onclick="confirmDelete('{{ route('admin.admins.delete', $admin->id) }}')">
                            <i data-lucide="trash-2" class="icon-xs me-1"></i> Delete Admin
                        </button>
                    </div>
                @endunless
            </div>
        </section>
    </div>
</div>

@endsection
