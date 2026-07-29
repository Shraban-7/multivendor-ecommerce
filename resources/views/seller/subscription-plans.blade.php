@extends('seller.layouts.app')
@section('title', 'Subscription Plans')
@section('content')

<div class="flex justify-between items-end mb-4">
    <h4 class="font-bold mb-0 text-ink">Subscription Plans</h4>
</div>

<?php $currentPlanId = $current_subscription->plan_id ?? 0; ?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ($plans as $plan)
    <div class="col-span-full sm:col-span-1 lg:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full" style="border-radius: 12px;">
            <div class="p-5 flex flex-col">
                <h5 class="text-center font-bold mb-3 text-brand">
                    {{ $plan->name }}
                </h5>
                <h2 class="text-center mb-1 font-bold text-ink">
                    {{ money($plan->price) }}
                </h2>
                <p class="text-center text-ink-tertiary mb-4">
                    {{ ucfirst($plan->duration_type) }}
                </p>
                <ul class="list-none text-sm grow">
                    <li class="mb-1"><span class="font-semibold">{{ $plan->product_limit == 0 ? 'Unlimited' : $plan->product_limit }}</span> Products</li>
                    <li class="mb-1"><span class="font-semibold">{{ $plan->commission_rate }}%</span> Commission</li>
                    <li class="mb-1 {{ $plan->pos_access ? 'text-feedback-success' : 'text-ink-tertiary' }}">{{ $plan->pos_access ? '✅' : '❌' }} POS Access</li>
                    <li class="mb-1 {{ $plan->analytics_access ? 'text-feedback-success' : 'text-ink-tertiary' }}">{{ $plan->analytics_access ? '✅' : '❌' }} Analytics Access</li>
                    <li class="mb-1 {{ $plan->priority_support ? 'text-feedback-success' : 'text-ink-tertiary' }}">{{ $plan->priority_support ? '✅' : '❌' }} Priority Support</li>
                    <li class="mb-1 {{ $plan->custom_domain ? 'text-feedback-success' : 'text-ink-tertiary' }}">{{ $plan->custom_domain ? '✅' : '❌' }} Custom Domain</li>
                    <li class="mb-1 {{ $plan->payment_checker ? 'text-feedback-success' : 'text-ink-tertiary' }}">{{ $plan->payment_checker ? '✅' : '❌' }} Payment Checker</li>
                    <li><span class="font-semibold">{{ $plan->staff_account_limit }}</span> Staff Accounts</li>
                </ul>

                <?php
                $active = optional($current_subscription)->subscription_plan_id == $plan->id;
                $isFreePlan = $plan->price == 0;
                $startDate = optional($current_subscription)->start_date?->format('M d, Y');
                $endDate = optional($current_subscription)->end_date?->format('M d, Y');
                ?>

                @if ($active)
                <div class="grid gap-2 mt-auto">
                    <button class="btn btn-success w-full" disabled>
                        {{ $isFreePlan ? 'Free Plan' : 'Current Plan' }}
                    </button>
                    @if (!$isFreePlan)
                    <button type="button" class="btn btn-outline-danger w-full" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Cancel Subscription
                    </button>
                    <div class="flex justify-between text-sm text-ink-tertiary mt-2">
                        <span>Start: {{ $startDate }}</span>
                        <span class="text-feedback-danger">End: {{ $endDate }}</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="grid mt-auto">
                    <button type="button" class="btn btn-primary w-full" data-bs-toggle="modal" data-bs-target="#upgradeModal{{ $plan->id }}">
                        Choose Plan
                    </button>
                </div>

                <div class="modal fade" id="upgradeModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold">Confirm Subscription</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to subscribe to <strong>{{ $plan->name }}</strong> plan?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('seller.plans.subscribe', $plan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Yes, Subscribe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@if (optional($current_subscription)->subscription_plan_id && $current_subscription->plan->price > 0)
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title font-semibold">Cancel Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel your subscription? You will be downgraded to the Free Plan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('seller.plans.subscribe', $plan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
