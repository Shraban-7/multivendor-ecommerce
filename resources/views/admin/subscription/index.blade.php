@php
    $pageTitle = 'Seller Subscriptions';

    $statusMap = [
        'active'    => ['label' => 'Active',     'pill' => 'bg-emerald-500 text-white',   'icon' => 'circle-check'],
        'trial'     => ['label' => 'Trial',      'pill' => 'bg-amber-500 text-white',     'icon' => 'gift'],
        'expired'   => ['label' => 'Expired',    'pill' => 'bg-rose-500 text-white',      'icon' => 'circle-x'],
        'cancelled' => ['label' => 'Cancelled',  'pill' => 'bg-gray-500 text-white',      'icon' => 'ban'],
        'suspended' => ['label' => 'Suspended',  'pill' => 'bg-cyan-500 text-white',      'icon' => 'pause'],
    ];

    $activeCount   = $subscriptions->where('status', 'active')->count();
    $trialCount    = $subscriptions->where('status', 'trial')->count();
    $expiredCount  = $subscriptions->where('status', 'expired')->count();
    $totalCount    = $subscriptions->total();
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
                    <i data-lucide="credit-card" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Finance</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Subscriptions</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                        <i data-lucide="repeat" style="width:11px;height:11px;" class="me-1"></i> Recurring
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage seller subscription plans, statuses, billing cycles and renewals in one place.</p>
            </div>
            <div>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-primary btn-sm">
                    <i data-lucide="layers" class="icon-xs"></i> Manage Plans
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
<section class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Active</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($activeCount) }}</h3>
                <small class="text-ink-tertiary">Paying subscribers</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center">
                <i data-lucide="circle-check" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Trial</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($trialCount) }}</h3>
                <small class="text-ink-tertiary">Free trial active</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center">
                <i data-lucide="gift" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-rose-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Expired</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($expiredCount) }}</h3>
                <small class="text-ink-tertiary">Needs renewal</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-rose-50 text-rose-500 flex items-center justify-center">
                <i data-lucide="circle-x" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0 flex-1">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Total</p>
                <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format($totalCount) }}</h3>
                <small class="text-ink-tertiary">All subscriptions</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                <i data-lucide="users" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
</section>

{{-- ═══ FILTERS ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3 flex items-center gap-2">
        <i data-lucide="sliders-horizontal" class="text-feedback-info" style="width:16px;height:16px;"></i>
        <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Filter Subscriptions</h5>
    </div>
    <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-5">
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Seller name or email…"
                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-2">
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    @foreach ($statusMap as $key => $meta)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Plan</label>
                <select name="plan_id" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Plans</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" @selected((string) request('plan_id') === (string) $plan->id)>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Filter
                </button>
            </div>
        </div>
        @if (request()->filled('search') || request()->filled('status') || request()->filled('plan_id'))
            <div class="mt-3 flex items-center gap-2 text-xs">
                <span class="text-ink-tertiary">Active filters:</span>
                @if (request()->filled('search'))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-brand-tint text-brand-deep">Search: {{ request('search') }}</span>
                @endif
                @if (request()->filled('status'))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-brand-tint text-brand-deep">Status: {{ $statusMap[request('status')]['label'] ?? request('status') }}</span>
                @endif
                @if (request()->filled('plan_id') && $plans->where('id', request('plan_id'))->first())
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-brand-tint text-brand-deep">Plan: {{ $plans->where('id', request('plan_id'))->first()->name }}</span>
                @endif
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-light btn-sm ms-auto">
                    <i data-lucide="rotate-ccw" style="width:12px;height:12px;"></i> Reset
                </a>
            </div>
        @endif
    </form>
</section>

{{-- ═══ SUBSCRIPTIONS LIST ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="list" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Subscriptions</h5>
            <span class="text-ink-tertiary text-xs">· {{ $subscriptions->total() }} {{ Str::plural('record', $subscriptions->total()) }}</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-soft">
            <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 w-14">#</th>
                    <th class="px-4 py-2.5">Seller</th>
                    <th class="px-4 py-2.5">Plan</th>
                    <th class="px-4 py-2.5 text-center">Status</th>
                    <th class="px-4 py-2.5">Start</th>
                    <th class="px-4 py-2.5">End</th>
                    <th class="px-4 py-2.5 text-center">Days Left</th>
                    <th class="px-4 py-2.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($subscriptions as $subscription)
                    @php
                        $meta = $statusMap[$subscription->status] ?? ['label' => ucfirst($subscription->status), 'pill' => 'bg-surface-muted text-ink-soft', 'icon' => 'circle'];
                        $daysLeft = $subscription->daysRemaining();
                    @endphp
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-mono font-semibold text-ink-emphasis">#{{ $subscription->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $subscription->seller?->businessAvatar }}" alt="" width="32" height="32"
                                     class="rounded-sm object-cover border border-border shrink-0" style="width:32px;height:32px;">
                                <div class="min-w-0">
                                    <p class="mb-0 font-medium text-ink-emphasis truncate">{{ $subscription->seller->name ?? 'N/A' }}</p>
                                    <small class="text-ink-tertiary">{{ $subscription->seller->email ?? '—' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-info-tint text-feedback-info">
                                    <i data-lucide="layers" style="width:11px;height:11px;" class="me-1"></i>
                                    {{ $subscription->plan->name ?? 'N/A' }}
                                </span>
                                @if ($subscription->is_trial)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500 text-white">
                                        <i data-lucide="gift" style="width:11px;height:11px;" class="me-1"></i> Trial
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white {{ $meta['pill'] }}">
                                <i data-lucide="{{ $meta['icon'] }}" style="width:11px;height:11px;" class="me-1"></i>
                                {{ $meta['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink-secondary">{{ $subscription->start_date?->format('d M, Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-ink-secondary">{{ $subscription->end_date?->format('d M, Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($daysLeft > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold text-white {{ $daysLeft <= 3 ? 'bg-rose-500' : 'bg-emerald-500' }}">
                                    <i data-lucide="clock" style="width:11px;height:11px;" class="me-1"></i>
                                    {{ $daysLeft }}d
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-surface-muted text-ink-tertiary">
                                    Expired
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn btn-light btn-sm" title="View">
                                    <i data-lucide="eye" class="icon-xs"></i>
                                </a>
                                @if (in_array($subscription->status, ['active', 'trial'], true))
                                    <button type="button" class="btn btn-warning btn-sm" title="Suspend" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $subscription->id }}">
                                        <i data-lucide="pause" class="icon-xs"></i>
                                    </button>
                                @endif
                                @if ($subscription->status === 'suspended')
                                    <form action="{{ route('admin.subscriptions.activate', $subscription) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Activate" onclick="return confirm('Activate this subscription?')">
                                            <i data-lucide="play" class="icon-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-sm text-ink-tertiary">
                            <i data-lucide="inbox" class="mx-auto mb-3 opacity-50" style="width:40px;height:40px;"></i>
                            <p class="mb-1 font-semibold text-ink-emphasis">No subscriptions match your filters</p>
                            <small>Try resetting filters or onboard sellers to a new plan.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($subscriptions->hasPages())
        <div class="px-5 py-3 bg-surface-muted flex items-center justify-between">
            <small class="text-ink-tertiary">Showing {{ $subscriptions->firstItem() }}–{{ $subscriptions->lastItem() }} of {{ $subscriptions->total() }}</small>
            {{ $subscriptions->links() }}
        </div>
    @endif
</section>

@foreach ($subscriptions as $subscription)
    @push('modals')
    <div class="modal fade" id="suspendModal{{ $subscription->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.subscriptions.suspend', $subscription) }}" method="POST">
                    @csrf
                    <div class="modal-header border-b border-border bg-surface-muted">
                        <div class="flex items-center gap-2">
                            <span class="shrink-0 w-9 h-9 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center">
                                <i data-lucide="pause" style="width:18px;height:18px;"></i>
                            </span>
                            <h5 class="modal-title font-bold text-ink-emphasis mb-0">Suspend Subscription</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-sm text-ink-secondary mb-3">
                            Suspending <strong class="text-ink-emphasis">#{{ $subscription->id }}</strong> for
                            <strong class="text-ink-emphasis">{{ $subscription->seller?->name }}</strong>.
                            They will keep their current access until you reactivate.
                        </p>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Reason for Suspension <span class="text-feedback-danger">*</span></label>
                        <textarea name="reason" required rows="3" placeholder="Why is this subscription being suspended?"
                                  class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></textarea>
                    </div>
                    <div class="modal-footer border-t border-border bg-surface-muted">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i data-lucide="pause" class="icon-xs me-1"></i> Suspend
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush
@endforeach

@endsection
