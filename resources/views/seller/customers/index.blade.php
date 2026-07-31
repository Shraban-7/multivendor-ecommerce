@extends('seller.layouts.app')
@section('title', 'Website Customers')

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
                    <span class="text-ink-soft font-semibold">Website Customers</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Website Customers</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="globe" style="width:11px;height:11px;" class="me-1"></i> Online Buyers
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Customers who registered and purchased from your shop on the website.</p>
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
        ['key' => 'total',       'label' => 'Total Customers', 'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'users'],
        ['key' => 'verified',    'label' => 'Verified Email',  'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'badge-check'],
        ['key' => 'unverified',  'label' => 'Unverified',      'top' => '#fb923c', 'text' => 'text-feedback-warning',  'icon' => 'help-circle'],
        ['key' => 'repeat',      'label' => 'Repeat Buyers',  'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'repeat'],
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
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if ($filters['search'] || ($filters['verified'] ?? null))
            <a href="{{ route('seller.customers') }}" class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-border">
        <form method="GET" action="{{ route('seller.customers') }}" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-6 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ $filters['search'] }}"
                       placeholder="Search by name, username, email or phone…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-3">
                <select name="verified"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All verification</option>
                    <option value="yes" @selected(($filters['verified'] ?? '') === 'yes')>Verified</option>
                    <option value="no"  @selected(($filters['verified'] ?? '') === 'no')>Unverified</option>
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
            <div class="md:col-span-1">
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Counts --}}
    <div class="px-4 pt-4 pb-1 flex flex-wrap items-center justify-between gap-2">
        <div class="text-xs text-ink-tertiary">
            Showing
            <span class="text-ink-emphasis font-semibold">{{ $customers->firstItem() ?? 0 }}</span>
            – <span class="text-ink-emphasis font-semibold">{{ $customers->lastItem() ?? 0 }}</span>
            of <span class="text-ink-emphasis font-semibold">{{ $customers->total() }}</span> customers
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Customer</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Phone</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Email</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Orders</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Total Spent</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Verified</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Joined</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr class="hover:bg-surface-muted/40 transition-colors">
                        {{-- Customer --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-brand-tint flex items-center justify-center text-brand-deep text-xs font-bold shrink-0">
                                    {{ mb_strtoupper(mb_substr($customer->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-ink-emphasis text-sm truncate max-w-[180px]">{{ $customer->name }}</div>
                                    <div class="text-xs text-ink-tertiary">
                                        @<span class="font-semibold">{{ $customer->username }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-ink-soft text-xs">
                            @if ($customer->phone)
                                <i data-lucide="phone" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i> {{ $customer->phone }}
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-ink-soft text-xs">
                            @if ($customer->email)
                                <i data-lucide="mail" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i> {{ $customer->email }}
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ ($customer->orders_count ?? 0) > 1 ? 'bg-feedback-info/15 text-feedback-info' : 'bg-surface-muted text-ink-emphasis' }}">
                                {{ $customer->orders_count ?? 0 }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-ink-emphasis text-sm font-semibold">
                            {{ money($customer->total_spent ?? 0) }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($customer->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-success/15 text-feedback-success">
                                    <i data-lucide="badge-check" style="width:11px;height:11px;" class="me-1"></i> Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary">
                                    <i data-lucide="help-circle" style="width:11px;height:11px;" class="me-1"></i> Unverified
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $customer->created_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-right">
                            <a href="mailto:{{ $customer->email }}" class="btn btn-light btn-sm" title="Email">
                                <i data-lucide="send" style="width:13px;height:13px;"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="py-10 text-center">
                                <i data-lucide="user-plus" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No website customers yet</p>
                                <p class="text-ink-tertiary text-xs">Registered customers who've placed orders on your shop will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $customers->links() }}
    </div>
</section>

@endsection
