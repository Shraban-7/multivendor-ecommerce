@php
    $pageTitle = 'Admin Members';
    $roleTone = [
        'super_admin' => 'bg-rose-500 text-white',
        'admin'      => 'bg-info-tint text-feedback-info',
        'manager'    => 'bg-warning-tint text-feedback-warning',
        'staff'      => 'bg-surface-muted text-ink-emphasis',
    ];
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
                    <span>Administration</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Admin Members</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                        <i data-lucide="users" style="width:11px;height:11px;" class="me-1"></i> Admin Team
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage admin users, assign roles, and control who can access the back office.</p>
            </div>
            <div>
                <a href="{{ route('admin.admins.create') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="user-plus" class="icon-xs"></i> Add Admin
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
<section class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-brand"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Total admins</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($admins->count()) }}</h3>
                <small class="text-ink-tertiary">All admin accounts</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center">
                <i data-lucide="users" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-rose-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Super admins</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($admins->filter(fn($a) => $a->role?->name === 'super_admin')->count()) }}</h3>
                <small class="text-ink-tertiary">Unrestricted access</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-rose-50 text-rose-500 flex items-center justify-center">
                <i data-lucide="crown" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Active roles</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($admins->pluck('role_id')->filter()->unique()->count()) }}</h3>
                <small class="text-ink-tertiary">Distinct assigned</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center">
                <i data-lucide="badge-check" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Newest member</p>
                <h3 class="mb-0 font-bold text-lg text-ink-emphasis mt-1 truncate">
                    {{ optional($admins->sortByDesc('created_at')->first())->name ?? '—' }}
                </h3>
                <small class="text-ink-tertiary">{{ optional($admins->sortByDesc('created_at')->first())->created_at?->diffForHumans() ?? '' }}</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                <i data-lucide="user-plus" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
</section>

{{-- ═══ ADMINS TABLE ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="list" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Admin Roster</h5>
            <span class="text-ink-tertiary text-xs">· {{ $admins->count() }} {{ Str::plural('member', $admins->count()) }}</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-soft">
            <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 w-14">#</th>
                    <th class="px-4 py-2.5">Admin</th>
                    <th class="px-4 py-2.5">Role</th>
                    <th class="px-4 py-2.5">Email</th>
                    <th class="px-4 py-2.5">Registered</th>
                    <th class="px-4 py-2.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($admins as $admin)
                    @php
                        $isSuper = $admin->name === 'Super Admin';
                        $roleName = $admin->role?->name ?? 'unassigned';
                        $tone = $roleTone[$roleName] ?? 'bg-surface-muted text-ink-soft';
                    @endphp
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-mono text-ink-emphasis font-semibold">#{{ $admin->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="shrink-0 w-9 h-9 rounded-full {{ $isSuper ? 'bg-rose-50 text-rose-500' : 'bg-info-tint text-feedback-info' }} flex items-center justify-center font-bold text-xs">
                                    @if ($isSuper)
                                        <i data-lucide="crown" style="width:14px;height:14px;"></i>
                                    @else
                                        {{ mb_substr($admin->name, 0, 1) }}
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    <p class="mb-0 font-medium text-ink-emphasis truncate flex items-center gap-2">
                                        {{ $admin->name }}
                                        @if ($isSuper)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500 text-white">Super Admin</span>
                                        @endif
                                    </p>
                                    <small class="text-ink-tertiary">ID: {{ $admin->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $tone }}">
                                {{ $admin->role->title ?? 'Unassigned' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink-secondary">{{ $admin->email }}</td>
                        <td class="px-4 py-3 text-ink-secondary">{{ $admin->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-light btn-sm" title="Edit admin">
                                    <i data-lucide="edit" class="icon-xs"></i>
                                </a>
                                @unless ($isSuper)
                                    <button type="button" class="btn btn-danger btn-sm" title="Delete admin"
                                            onclick="confirmDelete('{{ route('admin.admins.delete', $admin->id) }}')">
                                        <i data-lucide="trash-2" class="icon-xs"></i>
                                    </button>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-sm text-ink-tertiary">
                            <i data-lucide="users" class="mx-auto mb-3 opacity-50" style="width:40px;height:40px;"></i>
                            <p class="mb-1 font-semibold text-ink-emphasis">No admins yet</p>
                            <small>Click <strong>Add Admin</strong> to onboard a teammate.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection
