@php
    $currentPlanId    = optional($current_subscription)->subscription_plan_id ?? 0;
    $currentStartDate = optional($current_subscription)->start_date?->format('M d, Y');
    $currentEndDate   = optional($current_subscription)->end_date?->format('M d, Y');
    $isOnPaidPlan     = optional($current_subscription)->subscription_plan_id && optional($current_subscription)->plan?->price > 0;

    $featureIcons = [
        // defined per-plan fields below
    ];
@endphp
@extends('seller.layouts.app')
@section('title', 'Subscription Plans')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="credit-card" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Subscription Plans</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Subscription Plans</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-warning/15 text-feedback-warning">
                        <i data-lucide="zap" style="width:11px;height:11px;" class="me-1"></i> Upgrade Your Shop
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Compare plans and unlock more products, lower commissions and premium features.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @if ($isOnPaidPlan)
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i data-lucide="x-circle" style="width:14px;height:14px;"></i> Cancel Subscription
                    </button>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Current plan summary --}}
@if ($current_subscription && optional($current_subscription)->plan)
    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3">
        <div class="p-5 lg:p-6 flex flex-wrap items-center gap-4">
            <div class="w-12 h-12 rounded-sm bg-brand-tint flex items-center justify-center text-brand-deep shrink-0">
                <i data-lucide="award" style="width:22px;height:22px;"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0.5">Currently subscribed</div>
                <div class="text-base font-bold text-ink-emphasis">{{ $current_subscription->plan->name }}</div>
                @if ($currentStartDate && $currentEndDate)
                    <small class="text-ink-tertiary">Valid from {{ $currentStartDate }} → {{ $currentEndDate }}</small>
                @elseif ($currentStartDate)
                    <small class="text-ink-tertiary">Active since {{ $currentStartDate }}</small>
                @endif
            </div>
            <div class="text-right">
                <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-0.5">Billed</div>
                <div class="text-base font-bold text-ink-emphasis">{{ money($current_subscription->plan->price) }} <small class="font-normal text-ink-tertiary">/{{ ucfirst($current_subscription->plan->duration_type) }}</small></div>
            </div>
        </div>
    </section>
@endif

{{-- ═══ PLAN CARD GRID ═══ --}}
<section>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
        @foreach ($plans as $plan)
            @php
                $active     = $currentPlanId == $plan->id;
                $isFreePlan = $plan->price == 0;
                $isBestValue = ! $active && ! $isFreePlan; // simple heuristic — paid non-current plan flagged
            @endphp
            <article class="bg-white rounded-sm shadow-sm overflow-hidden relative flex flex-col h-full {{ $active ? 'ring-2 ring-brand' : '' }}">
                {{-- Tonal top bar --}}
                <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $active ? '#F85606' : ($isFreePlan ? '#a3a3a3' : '#fb923c') }};"></div>

                {{-- Badges --}}
                @if ($active)
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand text-white">
                            <i data-lucide="check" style="width:10px;height:10px;"></i> Current
                        </span>
                    </div>
                @elseif ($isBestValue)
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-feedback-warning/15 text-feedback-warning">
                            <i data-lucide="zap" style="width:10px;height:10px;"></i> Upgrade
                        </span>
                    </div>
                @endif

                <div class="p-5 flex flex-col grow">
                    {{-- Plan name --}}
                    <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">{{ $isFreePlan ? 'Starter' : 'Plan' }}</div>
                    <h3 class="text-lg font-bold text-ink-emphasis mb-1">{{ $plan->name }}</h3>

                    {{-- Price --}}
                    <div class="flex items-baseline gap-1 my-3">
                        <span class="text-3xl font-bold {{ $active ? 'text-brand-deep' : 'text-ink-emphasis' }}">{{ money($plan->price) }}</span>
                        <span class="text-sm text-ink-tertiary">/ {{ $plan->duration_type }}</span>
                    </div>

                    {{-- Features (capabilities) --}}
                    <ul class="space-y-2 mt-2 grow">
                        <li class="flex items-start gap-2 text-sm text-ink-soft">
                            <i data-lucide="{{ $plan->product_limit == 0 ? 'infinity' : 'package' }}" class="text-brand-deep shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
                            <span class="font-semibold text-ink-emphasis">{{ $plan->product_limit == 0 ? 'Unlimited' : number_format($plan->product_limit) }}</span> Products
                        </li>
                        <li class="flex items-start gap-2 text-sm text-ink-soft">
                            <i data-lucide="percent-circle" class="text-brand-deep shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
                            <span class="font-semibold text-ink-emphasis">{{ $plan->commission_rate }}%</span> Commission
                        </li>
                        <li class="flex items-start gap-2 text-sm {{ $plan->pos_access ? 'text-ink-soft' : 'text-ink-tertiary line-through' }}">
                            <i data-lucide="{{ $plan->pos_access ? 'check-circle-2' : 'x-circle' }}"
                               class="shrink-0 mt-0.5 {{ $plan->pos_access ? 'text-feedback-success' : 'text-ink-tertiary' }}"
                               style="width:14px;height:14px;"></i>
                            POS Access
                        </li>
                        <li class="flex items-start gap-2 text-sm {{ $plan->analytics_access ? 'text-ink-soft' : 'text-ink-tertiary line-through' }}">
                            <i data-lucide="{{ $plan->analytics_access ? 'check-circle-2' : 'x-circle' }}"
                               class="shrink-0 mt-0.5 {{ $plan->analytics_access ? 'text-feedback-success' : 'text-ink-tertiary' }}"
                               style="width:14px;height:14px;"></i>
                            Analytics Access
                        </li>
                        <li class="flex items-start gap-2 text-sm {{ $plan->priority_support ? 'text-ink-soft' : 'text-ink-tertiary line-through' }}">
                            <i data-lucide="{{ $plan->priority_support ? 'check-circle-2' : 'x-circle' }}"
                               class="shrink-0 mt-0.5 {{ $plan->priority_support ? 'text-feedback-success' : 'text-ink-tertiary' }}"
                               style="width:14px;height:14px;"></i>
                            Priority Support
                        </li>
                        <li class="flex items-start gap-2 text-sm {{ $plan->custom_domain ? 'text-ink-soft' : 'text-ink-tertiary line-through' }}">
                            <i data-lucide="{{ $plan->custom_domain ? 'check-circle-2' : 'x-circle' }}"
                               class="shrink-0 mt-0.5 {{ $plan->custom_domain ? 'text-feedback-success' : 'text-ink-tertiary' }}"
                               style="width:14px;height:14px;"></i>
                            Custom Domain
                        </li>
                        <li class="flex items-start gap-2 text-sm {{ $plan->payment_checker ? 'text-ink-soft' : 'text-ink-tertiary line-through' }}">
                            <i data-lucide="{{ $plan->payment_checker ? 'check-circle-2' : 'x-circle' }}"
                               class="shrink-0 mt-0.5 {{ $plan->payment_checker ? 'text-feedback-success' : 'text-ink-tertiary' }}"
                               style="width:14px;height:14px;"></i>
                            Payment Checker
                        </li>
                        <li class="flex items-start gap-2 text-sm text-ink-soft">
                            <i data-lucide="users-round" class="text-brand-deep shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
                            <span class="font-semibold text-ink-emphasis">{{ $plan->staff_account_limit }}</span> Staff Accounts
                        </li>
                    </ul>

                    {{-- Action button --}}
                    <div class="mt-5 pt-4 border-t border-border">
                        @if ($active)
                            <button class="btn btn-success w-full" disabled>
                                <i data-lucide="check-circle-2" style="width:14px;height:14px;"></i>
                                {{ $isFreePlan ? 'Active Free Plan' : 'Current Plan' }}
                            </button>
                        @else
                            <button type="button" class="btn btn-primary w-full"
                                    data-bs-toggle="modal" data-bs-target="#upgradePlanModal{{ $plan->id }}">
                                <i data-lucide="arrow-up-right" style="width:14px;height:14px;"></i>
                                {{ $isFreePlan ? 'Downgrade to Free' : 'Choose Plan' }}
                            </button>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- ═══ PER-PLAN UPGRADE MODALS ═══ --}}
@foreach ($plans as $plan)
    <div class="modal fade" id="upgradePlanModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-bold">Confirm Subscription</h5>
                        <small class="text-ink-tertiary">Switch to the {{ $plan->name }} plan</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-brand-tint rounded-xs p-4 flex items-center justify-between mb-3">
                        <div>
                            <div class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Plan</div>
                            <div class="text-base font-bold text-ink-emphasis">{{ $plan->name }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-brand-deep">{{ money($plan->price) }}</div>
                            <div class="text-xs text-ink-tertiary">/{{ $plan->duration_type }}</div>
                        </div>
                    </div>
                    <p class="mb-0 text-sm text-ink-soft">
                        Are you sure you want to subscribe to the <strong class="text-ink-emphasis">{{ $plan->name }}</strong> plan?
                        @if ($plan->price > 0)
                            Your current billing cycle will adjust accordingly.
                        @else
                            This will switch you to the Free plan immediately.
                        @endif
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('seller.plans.subscribe', $plan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="check" style="width:14px;height:14px;"></i> Yes, Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- ═══ CANCEL CONFIRMATION MODAL ═══ --}}
@if ($isOnPaidPlan)
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-bold text-feedback-danger">Cancel Subscription</h5>
                        <small class="text-ink-tertiary">This will downgrade you to the Free plan</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-feedback-danger/10 rounded-xs p-4 flex items-start gap-3 mb-3">
                        <i data-lucide="triangle-alert" class="text-feedback-danger shrink-0 mt-0.5" style="width:18px;height:18px;"></i>
                        <div class="text-sm text-ink-soft">
                            Are you sure you want to cancel your subscription? You will lose access to premium features at the end of the current cycle and be downgraded to the Free Plan.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keep My Subscription</button>
                    <form action="{{ route('seller.plans.subscribe', optional($current_subscription)->plan?->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Yes, Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
