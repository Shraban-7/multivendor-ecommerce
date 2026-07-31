@php
    $sortDir = $filters['direction'] ?? null;
    $isDir   = fn (string $d) => $sortDir === $d;
@endphp
@extends('seller.layouts.app')
@section('title', 'Employees')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="users" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Employees</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Employees</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="user-cog" style="width:11px;height:11px;" class="me-1"></i> Staff Management
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Add team members, grant scoped permissions and keep your shop running smoothly.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('seller.employees.salesReport') }}" class="btn btn-light">
                    <i data-lucide="bar-chart-2" style="width:15px;height:15px;"></i> Sales Report
                </a>
                <a href="{{ route('seller.employees.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Employee
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Flash messages --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 text-feedback-danger text-sm mb-3 alert-dismissible fade show">{{ session('error') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
@php
    $countCards = [
        ['key' => 'total',             'label' => 'Total Employees',  'top' => '#F85606', 'text' => 'text-brand-deep',            'icon' => 'users'],
        ['key' => 'active',            'label' => 'Active',           'top' => '#10b981', 'text' => 'text-feedback-success',      'icon' => 'user-check'],
        ['key' => 'inactive',          'label' => 'Inactive',         'top' => '#fb923c', 'text' => 'text-feedback-warning',      'icon' => 'user-x'],
        ['key' => 'with_permissions',  'label' => 'With Permissions', 'top' => '#0ea5e9', 'text' => 'text-feedback-info',         'icon' => 'shield-check'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
    @foreach ($countCards as $card)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $card['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $card['label'] }}</span>
                    <i data-lucide="{{ $card['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $card['text'] }} mb-0">{{ number_format($counts[$card['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTER + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    {{-- Filter bar --}}
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        {{-- spacer --}}
        <div class="grow"></div>
        @if ($filters['search'] || ($filters['status'] ?? null))
            <a href="{{ route('seller.employees.index') }}" class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-border">
        <form method="GET" action="{{ route('seller.employees.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-5 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ $filters['search'] }}"
                       placeholder="Search by name, email, or phone…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-3">
                <select name="status"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All statuses</option>
                    <option value="active"   @selected(($filters['status'] ?? '') === 'active')>Active</option>
                    <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="per_page"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    @foreach ([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}" @selected((int) request('per_page', 25) === $n)>{{ $n }} / page</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="px-4 pt-4 pb-1 flex flex-wrap items-center justify-between gap-2">
        <div class="text-xs text-ink-tertiary">
            Showing
            <span class="text-ink-emphasis font-semibold">{{ $employees->firstItem() ?? 0 }}</span>
            – <span class="text-ink-emphasis font-semibold">{{ $employees->lastItem() ?? 0 }}</span>
            of <span class="text-ink-emphasis font-semibold">{{ $employees->total() }}</span> employees
        </div>
    </div>
    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Employee</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Phone</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Permissions</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Created</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                    @php
                        $permissionCount = is_array($employee->permissions) ? count($employee->permissions) : 0;
                    @endphp
                    <tr class="hover:bg-surface-muted/40 transition-colors">
                        {{-- Employee --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-brand-tint flex items-center justify-center text-brand-deep text-xs font-bold shrink-0">
                                    {{ mb_strtoupper(mb_substr($employee->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-ink-emphasis text-sm">{{ $employee->name }}</div>
                                    <a href="mailto:{{ $employee->email }}" class="text-xs text-ink-tertiary hover:text-ink-emphasis truncate inline-block max-w-[220px]">
                                        <i data-lucide="mail" style="width:11px;height:11px;" class="me-1 align-text-bottom"></i> {{ $employee->email }}
                                    </a>
                                </div>
                            </div>
                        </td>

                        {{-- Phone --}}
                        <td class="px-4 py-3 text-ink-soft">
                            @if ($employee->phone)
                                <span class="inline-flex items-center gap-1 text-xs">
                                    <i data-lucide="phone" style="width:11px;height:11px;" class="text-ink-tertiary"></i>
                                        {{ $employee->phone }}
                                </span>
                            @else
                                <span class="text-ink-tertiary text-xs">—</span>
                            @endif
                        </td>

                        {{-- Permissions --}}
                        <td class="px-4 py-3">
                            <button type="button" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                {{ $permissionCount > 0 ? 'bg-feedback-info/15 text-feedback-info' : 'bg-surface-muted text-ink-tertiary' }}"
                                data-bs-toggle="modal" data-bs-target="#permissionModal{{ $employee->id }}">
                                <i data-lucide="shield" style="width:11px;height:11px;"></i>
                                {{ $permissionCount }} granted
                            </button>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            @if ($employee->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span> Inactive
                                </span>
                            @endif
                        </td>

                        {{-- Created --}}
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $employee->created_at->format('d M Y') }}
                        </td>

                        {{-- Action dropdown --}}
                        <td class="px-4 py-3 text-right">
                            <div class="dropdown inline-block">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-lucide="more-horizontal" style="width:14px;height:14px;"></i>
                                    <span class="ms-1 inline-block">Manage</span>
                                    <i data-lucide="chevron-down" style="width:12px;height:12px;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm py-1" style="min-width:190px;">
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('seller.employees.edit', $employee->id) }}">
                                            <i data-lucide="pencil" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item py-1.5" data-bs-toggle="modal" data-bs-target="#permissionModal{{ $employee->id }}">
                                            <i data-lucide="shield" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> Permissions
                                        </button>
                                    </li>
                                    <li>
                                        <form action="{{ route('seller.employees.toggle_active', $employee->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-1.5">
                                                @if ($employee->is_active)
                                                    <i data-lucide="user-x" style="width:13px;height:13px;" class="me-2 text-feedback-warning"></i> Deactivate
                                                @else
                                                    <i data-lucide="user-check" style="width:13px;height:13px;" class="me-2 text-feedback-success"></i> Activate
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-1.5 text-feedback-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $employee->id }}">
                                            <i data-lucide="trash-2" style="width:13px;height:13px;" class="me-2"></i> Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-10 text-center">
                                <i data-lucide="user-plus" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No employees yet</p>
                                <p class="text-ink-tertiary text-xs mb-3">Add teammates and grant them scoped permissions to operate your shop.</p>
                                <a href="{{ route('seller.employees.create') }}" class="btn btn-primary btn-sm">
                                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Add your first employee
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $employees->links() }}
    </div>
</section>

{{-- ═══ PER-EMPLOYEE PERMISSION MODAL ═══ --}}
@foreach ($employees as $employee)
    <div class="modal fade" id="permissionModal{{ $employee->id }}" tabindex="-1"
         aria-labelledby="permissionModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="POST" action="{{ route('seller.employees.set_permissions', $employee) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold" id="permissionModalLabel{{ $employee->id }}">
                                Manage Permissions
                            </h5>
                            <small class="text-ink-tertiary">{{ $employee->name }} · {{ $employee->email }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach ($permissions as $permission)
                                <div>
                                    <label class="flex items-start gap-2 cursor-pointer">
                                        <input class="mt-0.5 h-4 w-4 shrink-0 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep" type="checkbox" name="permissions[]"
                                               value="{{ $permission['name'] }}"
                                               id="perm_{{ $employee->id }}_{{ $permission['name'] }}"
                                               {{ in_array($permission['name'], (array) ($employee->permissions ?? []), true) ? 'checked' : '' }}>
                                        <span class="text-sm text-ink-soft" for="perm_{{ $employee->id }}_{{ $permission['name'] }}">
                                            {{ $permission['title'] }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" style="width:14px;height:14px;"></i> Save Permissions
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- ═══ DELETE CONFIRMATION MODAL ═══ --}}
@foreach ($employees as $employee)
    <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1"
         aria-labelledby="deleteModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('seller.employees.destroy', $employee) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold text-feedback-danger" id="deleteModalLabel{{ $employee->id }}">Remove Employee</h5>
                            <small class="text-ink-tertiary">This action cannot be undone</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-feedback-danger/10 rounded-xs p-4 flex items-start gap-3 mb-3">
                            <i data-lucide="triangle-alert" class="text-feedback-danger shrink-0 mt-0.5" style="width:18px;height:18px;"></i>
                            <div class="text-sm text-ink-soft">
                                Are you sure you want to remove <strong>{{ $employee->name }}</strong>? Their access will be revoked immediately and active sessions will be invalidated.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keep Employee</button>
                        <button type="submit" class="btn btn-danger">
                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Yes, Remove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
