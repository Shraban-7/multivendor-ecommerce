@php
    use App\Domain\Vendor\Models\Seller;

    $active   = Seller::ACTIVE;
    $pending  = Seller::PENDING;
    $blocked  = Seller::BLOCKED;
    $deleted  = Seller::DELETED;

    $counts = $counts ?? [];
@endphp
@extends('admin.layouts.app')
@section('title', 'Suppliers')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="store" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Reach</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Suppliers</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Suppliers</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-warning/15 text-feedback-warning">
                        <i data-lucide="store" style="width:11px;height:11px;" class="me-1"></i> Seller Network
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage all registered suppliers, their commission rates and onboarding status.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Seller
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
    $tiles = [
        ['key' => 'total',   'label' => 'Total Sellers',   'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'store'],
        ['key' => 'active',  'label' => 'Active',          'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'pending', 'label' => 'Pending',         'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'hourglass'],
        ['key' => 'blocked', 'label' => 'Blocked',         'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'ban'],
        ['key' => 'deleted', 'label' => 'Deleted',         'top' => '#6b7280', 'text' => 'text-ink-secondary',     'icon' => 'trash-2'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTER + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.sellers.index') }}" class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>

    <div class="p-4 border-t border-border">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-7 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name, email, phone, or shop…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-3">
                <select name="status"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    <option value="{{ $active }}"  @selected(request('status') == (string) $active)>Active</option>
                    <option value="{{ $pending }}" @selected(request('status') == (string) $pending)>Pending</option>
                    <option value="{{ $blocked }}" @selected(request('status') == (string) $blocked)>Blocked</option>
                    <option value="{{ $deleted }}" @selected(request('status') == (string) $deleted)>Deleted</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ $sellers->firstItem() ?? 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ $sellers->lastItem() ?? 0 }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ $sellers->total() }}</span> sellers
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Seller</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Shop</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Contact</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Commission</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sellers as $seller)
                    @php
                        $statusKey = (int) $seller->status;
                        $pillBg = match ($statusKey) {
                            (int) $active  => 'bg-feedback-success/15 text-feedback-success',
                            (int) $pending => 'bg-feedback-info/15 text-feedback-info',
                            (int) $blocked => 'bg-feedback-warning/15 text-feedback-warning',
                            (int) $deleted => 'bg-feedback-danger/15 text-feedback-danger',
                            default         => 'bg-surface-muted text-ink-tertiary',
                        };
                        $pillLabel = match ($statusKey) {
                            (int) $active  => 'Active',
                            (int) $pending => 'Pending',
                            (int) $blocked => 'Blocked',
                            (int) $deleted => 'Deleted',
                            default         => ucfirst((string) $seller->status),
                        };
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $seller->businessAvatar }}" alt=""
                                     width="40" height="40"
                                     style="object-fit:cover;border-radius:8px;" class="shrink-0">
                                <div class="min-w-0">
                                    <div class="font-semibold text-ink-emphasis text-sm truncate max-w-[180px]">{{ $seller->name }}</div>
                                    <div class="text-xs text-ink-tertiary truncate max-w-[180px]">{{ $seller->email }}</div>
                                    <small class="text-ink-tertiary inline-flex items-center gap-1 mt-0.5">
                                        <i data-lucide="calendar" style="width:10px;height:10px;"></i>
                                        Joined {{ $seller->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-ink-emphasis">{{ $seller->business_name }}</div>
                            <small class="text-ink-tertiary">@<span class="font-medium">{{ $seller->username }}</span></small>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="inline-flex items-center gap-1 text-ink-soft">
                                <i data-lucide="phone" style="width:11px;height:11px;" class="text-ink-tertiary"></i>
                                {{ $seller->phone ?? '—' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold text-ink-emphasis">
                            {{ $seller->commission_amount }}
                            <small class="text-ink-tertiary font-normal">
                                {{ $seller->commission_type == \App\Enums\CommissionType::PERCENTAGE->value ? '%' : '' }}
                            </small>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ $pillLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-1.5">
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.sellers.profile', $seller->username) }}">
                                    <i data-lucide="eye" style="width:13px;height:13px;"></i> View
                                </a>
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $seller->id }}">
                                    <i data-lucide="settings" style="width:13px;height:13px;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="py-10 text-center">
                                <i data-lucide="store" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No suppliers found</p>
                                <p class="text-ink-tertiary text-xs">Registered suppliers will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $sellers->links() }}
    </div>
</section>

@push('modals')
    @foreach ($sellers as $seller)
        <div class="modal fade" id="editModal-{{ $seller->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold">Edit Commission and Status</h5>
                            <small class="text-ink-tertiary">{{ $seller->name }} · {{ $seller->business_name }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.sellers.updateStatus', $seller->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Commission</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <select name="commission_type"
                                            class="px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                                        <option value="{{ \App\Enums\CommissionType::PERCENTAGE->value }}"
                                                @selected(\App\Enums\CommissionType::PERCENTAGE->value == $seller->commission_type)>
                                            {{ ucfirst(\App\Enums\CommissionType::PERCENTAGE->label()) }}
                                        </option>
                                        <option value="{{ \App\Enums\CommissionType::FLAT->value }}"
                                                @selected(\App\Enums\CommissionType::FLAT->value == $seller->commission_type)>
                                            {{ ucfirst(\App\Enums\CommissionType::FLAT->label()) }}
                                        </option>
                                    </select>
                                    <input type="number" min="0" max="100" name="commission_amount"
                                           value="{{ $seller->commission_amount ?? $seller?->plan?->commission_rate }}"
                                           placeholder="Amount"
                                           class="col-span-2 w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Status</label>
                                <select name="status"
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                                    <option value="0" @selected($seller->status == $pending)>Inactive</option>
                                    <option value="1" @selected($seller->status == $active)>Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" style="width:14px;height:14px;"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endpush

@endsection
