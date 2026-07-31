@php
    $pageTitle = 'Roles & Permissions';
    $isSuperAdmin = admin()->role->name == 'super_admin';

    $roleTone = [
        'super_admin' => ['bar' => 'bg-rose-500',  'pill' => 'bg-rose-500 text-white',    'icon' => 'crown'],
        'admin'      => ['bar' => 'bg-blue-500',  'pill' => 'bg-info-tint text-feedback-info', 'icon' => 'shield'],
        'manager'    => ['bar' => 'bg-amber-500', 'pill' => 'bg-warning-tint text-feedback-warning', 'icon' => 'briefcase'],
        'staff'      => ['bar' => 'bg-gray-500',  'pill' => 'bg-surface-muted text-ink-emphasis', 'icon' => 'user'],
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
                    <span class="text-ink-soft font-semibold">Roles & Permissions</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                        <i data-lucide="lock" style="width:11px;height:11px;" class="me-1"></i> RBAC
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Define roles, group permissions, and assign them to admins.</p>
            </div>
            @if ($isSuperAdmin)
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i data-lucide="plus" class="icon-xs"></i> Add Role
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
<section class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-brand"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Total roles</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($roles->count()) }}</h3>
                <small class="text-ink-tertiary">Per-system permission sets</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center">
                <i data-lucide="badge-check" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Total permissions</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($permissions->count()) }}</h3>
                <small class="text-ink-tertiary">Granular capabilities</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                <i data-lucide="key" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-rose-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Super Admin role</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($roles->where('name', 'super_admin')->count()) }}</h3>
                <small class="text-ink-tertiary">Locked role</small>
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
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Avg perms / role</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">
                    {{ $roles->count() > 0 ? number_format($roles->avg(fn($r) => $r->permissions->count()), 1) : 0 }}
                </h3>
                <small class="text-ink-tertiary">Across active roles</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center">
                <i data-lucide="bar-chart-3" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
</section>

{{-- ═══ ROLES TABLE ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="list" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Roles</h5>
            <span class="text-ink-tertiary text-xs">· {{ $roles->count() }} {{ Str::plural('role', $roles->count()) }}</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-soft">
            <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5">Role</th>
                    <th class="px-4 py-2.5">Permissions ({{ number_format($permissions->count()) }} total)</th>
                    <th class="px-4 py-2.5">Created</th>
                    @if ($isSuperAdmin)
                        <th class="px-4 py-2.5 text-right">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($roles as $role)
                    @php
                        $isSuper = $role->title === 'Super Admin' || $role->name === 'super_admin';
                        $tone = $roleTone[$role->name] ?? ['bar' => 'bg-gray-500', 'pill' => 'bg-surface-muted text-ink-soft', 'icon' => 'shield'];
                    @endphp
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center {{ $tone['pill'] }}">
                                    <i data-lucide="{{ $tone['icon'] }}" style="width:18px;height:18px;"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="mb-0 font-semibold text-ink-emphasis truncate">{{ $role->title }}</p>
                                    @if ($isSuper)
                                        <small class="text-ink-tertiary">Locked role · cannot be deleted</small>
                                    @else
                                        <small class="text-ink-tertiary font-mono">{{ $role->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($role->permissions->take(8) as $permission)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-brand-tint text-brand-deep">
                                        {{ $permission->title }}
                                    </span>
                                @empty
                                    <span class="text-ink-tertiary text-xs">No permissions assigned</span>
                                @endforelse
                                @if ($role->permissions->count() > 8)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-surface-muted text-ink-emphasis">
                                        +{{ $role->permissions->count() - 8 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-ink-secondary">{{ $role->created_at->format('d M Y') }}</td>
                        @if ($isSuperAdmin)
                            <td class="px-4 py-3 text-right">
                                @unless ($isSuper)
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-light btn-sm">
                                        <i data-lucide="edit" class="icon-xs me-1"></i> Edit
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500 text-white">
                                        <i data-lucide="lock" style="width:11px;height:11px;" class="me-1"></i> Locked
                                    </span>
                                @endunless
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isSuperAdmin ? 4 : 3 }}" class="px-4 py-10 text-center text-sm text-ink-tertiary">
                            <i data-lucide="shield-off" class="mx-auto mb-3 opacity-50" style="width:40px;height:40px;"></i>
                            <p class="mb-1 font-semibold text-ink-emphasis">No roles defined yet</p>
                            <small>Click <strong>Add Role</strong> to define permission groups.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@if ($isSuperAdmin)
@push('modals')
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-header border-b border-border bg-surface-muted">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center">
                            <i data-lucide="plus" style="width:18px;height:18px;"></i>
                        </span>
                        <h5 class="modal-title font-bold text-ink-emphasis mb-0">Create New Role</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Role Title <span class="text-feedback-danger">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. Catalog Manager"
                           class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    <small class="text-ink-tertiary mt-1 block">A short, human-friendly label. The internal slug is auto-generated.</small>
                </div>
                <div class="modal-footer border-t border-border bg-surface-muted">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="check" class="icon-xs me-1"></i> Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endif

@endsection
