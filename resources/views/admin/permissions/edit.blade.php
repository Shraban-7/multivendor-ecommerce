@php
    use Illuminate\Support\Facades\DB;

    $pageTitle = 'Edit Role: '.$role->title;
    $permissionNames = $role->permissionNames;
    $isSuper = $role->name === 'super_admin';

    // Group permissions by their category/title prefix for visual grouping
    $permissionGroups = $permissions->groupBy(function ($perm) {
        $parts = preg_split('/[._\-]/', $perm->name, 2);
        return ucwords(str_replace('_', ' ', $parts[0] ?? 'general'));
    });
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
                    <a href="{{ route('admin.roles.index') }}" class="hover:text-ink-soft transition-colors">Roles & Permissions</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $role->title }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $role->title }}</h1>
                    @if ($isSuper)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500 text-white">
                            <i data-lucide="crown" style="width:11px;height:11px;" class="me-1"></i> Super Admin
                        </span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-info-tint text-feedback-info">
                        {{ count($permissionNames) }} of {{ $permissions->count() }} permissions
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">{{ $role->name }} · created {{ $role->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if (hasPermission('admin.roles.update'))
                    @unless ($isSuper)
                        <button type="submit" form="editRoleForm" class="btn btn-primary btn-sm">
                            <i data-lucide="save" class="icon-xs"></i> Save Changes
                        </button>
                    @endunless
                @endif
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
    {{-- ═══ EDIT FORM ═══ --}}
    <div class="lg:col-span-2">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="sliders-horizontal" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Role configuration</h5>
            </div>
            @if (hasPermission('admin.roles.update'))
                <form id="editRoleForm" action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Role Title <span class="text-feedback-danger">*</span></label>
                        <input type="text" name="title" value="{{ $role->title }}" required
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                               @disabled($isSuper)>
                        @if ($isSuper)
                            <small class="text-ink-tertiary mt-1 block">Super Admin role cannot be renamed.</small>
                        @else
                            <small class="text-ink-tertiary mt-1 block">The internal slug <code class="font-mono">{{ $role->name }}</code> stays the same.</small>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-ink-secondary uppercase tracking-wider">Permissions ({{ count($permissionNames) }} / {{ $permissions->count() }} active)</label>
                                <small class="text-ink-tertiary block mt-0.5">Tick the capabilities this role unlocks.</small>
                            </div>
                            @unless ($isSuper)
                                <label class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-ink-emphasis bg-surface-muted rounded-xs cursor-pointer hover:bg-brand-tint transition-colors">
                                    <input type="checkbox" id="select_all"
                                           class="h-4 w-4 rounded border-border text-brand focus:ring-brand focus:ring-2">
                                    Select All
                                </label>
                            @endunless
                        </div>

                        <div class="rounded-xs bg-surface-muted p-4 space-y-4 max-h-[640px] overflow-y-auto">
                            @forelse ($permissionGroups as $group => $perms)
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[11px] font-bold tracking-wider text-ink-tertiary uppercase">{{ $group }}</span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-white text-ink-tertiary border border-border">{{ $perms->count() }}</span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach ($perms as $permission)
                                            <label class="flex items-start gap-2 px-3 py-2 bg-white rounded-xs cursor-pointer hover:bg-brand-tint transition-colors">
                                                <input class="h-4 w-4 mt-0.5 rounded border-border text-brand focus:ring-brand permission-checkbox"
                                                       type="checkbox" name="permissions[]"
                                                       value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                                       @checked(in_array($permission->name, $permissionNames))
                                                       @disabled($isSuper)>
                                                <span class="flex-1 min-w-0">
                                                    <span class="text-sm font-medium text-ink-emphasis block">{{ $permission->title }}</span>
                                                    <code class="text-[11px] text-ink-tertiary font-mono">{{ $permission->name }}</code>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-ink-tertiary text-sm mb-0">No permissions defined yet. Add them in code first.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t border-border">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Cancel</a>
                        @unless ($isSuper)
                            <button type="submit" class="btn btn-success">
                                <i data-lucide="save" class="icon-xs me-1"></i> Save Changes
                            </button>
                        @endunless
                    </div>
                </form>
            @else
                <div class="p-5 text-center text-ink-tertiary">
                    <i data-lucide="lock" class="mx-auto mb-2 opacity-50" style="width:36px;height:36px;"></i>
                    <p class="mb-0 font-semibold text-ink-emphasis">You don't have permission to edit roles</p>
                    <small>Ask a Super Admin to grant <code class="font-mono">admin.roles.update</code>.</small>
                </div>
            @endif
        </section>
    </div>

    {{-- ═══ SUMMARY SIDEBAR ═══ --}}
    <div class="lg:col-span-1 space-y-3">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="pie-chart" class="text-feedback-info" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Coverage</h5>
            </div>
            <div class="p-5">
                <div class="text-center">
                    @php $pct = $permissions->count() > 0 ? round((count($permissionNames) / $permissions->count()) * 100) : 0; @endphp
                    <div class="relative inline-block" style="width:120px;height:120px;">
                        <svg viewBox="0 0 36 36" width="120" height="120" class="transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#E5E7EB" stroke-width="3"/>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#F85606" stroke-width="3"
                                    stroke-dasharray="{{ $pct }} 100" stroke-linecap="round"
                                    transform="rotate(-90 18 18)"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <strong class="font-bold text-ink-emphasis" style="font-size:1.6rem;line-height:1;">{{ $pct }}%</strong>
                            <small class="text-ink-tertiary">covered</small>
                        </div>
                    </div>
                </div>
                <dl class="text-sm space-y-2 mt-4">
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Active permissions</dt>
                        <dd class="font-mono font-semibold text-ink-emphasis">{{ count($permissionNames) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Available permissions</dt>
                        <dd class="font-mono font-semibold text-ink-emphasis">{{ $permissions->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Groups covered</dt>
                        <dd class="font-mono font-semibold text-ink-emphasis">{{ $permissionGroups->keys()->count() }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="info" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Tips</h5>
            </div>
            <div class="p-5">
                <ul class="space-y-3 mb-0 text-sm">
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center"><i data-lucide="lock" style="width:14px;height:14px;"></i></span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis">Super Admin is locked</p>
                            <small class="text-ink-tertiary">Built-in role remains with all permissions.</small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center"><i data-lucide="users" style="width:14px;height:14px;"></i></span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis">Group-aware</p>
                            <small class="text-ink-tertiary">Permissions are grouped by their prefix for easier scanning.</small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center"><i data-lucide="save" style="width:14px;height:14px;"></i></span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis">Save to apply</p>
                            <small class="text-ink-tertiary">Changes take effect immediately for assigned admins.</small>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const isSuper = @json($isSuper);
    if (isSuper) return;

    $('#select_all').on('change', function () {
        $('.permission-checkbox').prop('checked', this.checked);
    });

    $('.permission-checkbox').on('change', function () {
        const total  = $('.permission-checkbox').length;
        const ticked = $('.permission-checkbox:checked').length;
        $('#select_all').prop('checked', total > 0 && ticked === total);
    });

    // Initial sync of "select all"
    const total  = $('.permission-checkbox').length;
    const ticked = $('.permission-checkbox:checked').length;
    $('#select_all').prop('checked', total > 0 && ticked === total);
})();
</script>
@endpush

@endsection
