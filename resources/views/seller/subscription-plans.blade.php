@extends('seller.layouts.app')
@section('title', 'Subscription Plans')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-4">
    <h4 class="fw-bold mb-0 text-dark">Subscription Plans</h4>
</div>

<?php $currentPlanId = $current_subscription->plan_id ?? 0; ?>

<div class="row g-4">
    @foreach ($plans as $plan)
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-center fw-bold mb-3 text-primary">
                    {{ $plan->name }}
                </h5>
                <h2 class="text-center mb-1 fw-bold text-dark">
                    {{ money($plan->price) }}
                </h2>
                <p class="text-center text-muted mb-4">
                    {{ ucfirst($plan->duration_type) }}
                </p>
                <ul class="list-unstyled small flex-grow-1">
                    <li class="mb-1"><span class="fw-semibold">{{ $plan->product_limit == 0 ? 'Unlimited' : $plan->product_limit }}</span> Products</li>
                    <li class="mb-1"><span class="fw-semibold">{{ $plan->commission_rate }}%</span> Commission</li>
                    <li class="mb-1 {{ $plan->pos_access ? 'text-success' : 'text-muted' }}">{{ $plan->pos_access ? '✅' : '❌' }} POS Access</li>
                    <li class="mb-1 {{ $plan->analytics_access ? 'text-success' : 'text-muted' }}">{{ $plan->analytics_access ? '✅' : '❌' }} Analytics Access</li>
                    <li class="mb-1 {{ $plan->priority_support ? 'text-success' : 'text-muted' }}">{{ $plan->priority_support ? '✅' : '❌' }} Priority Support</li>
                    <li class="mb-1 {{ $plan->custom_domain ? 'text-success' : 'text-muted' }}">{{ $plan->custom_domain ? '✅' : '❌' }} Custom Domain</li>
                    <li class="mb-1 {{ $plan->payment_checker ? 'text-success' : 'text-muted' }}">{{ $plan->payment_checker ? '✅' : '❌' }} Payment Checker</li>
                    <li><span class="fw-semibold">{{ $plan->staff_account_limit }}</span> Staff Accounts</li>
                </ul>

                <?php
                $active = optional($current_subscription)->subscription_plan_id == $plan->id;
                $isFreePlan = $plan->price == 0;
                $startDate = optional($current_subscription)->start_date?->format('M d, Y');
                $endDate = optional($current_subscription)->end_date?->format('M d, Y');
                ?>

                @if ($active)
                <div class="d-grid gap-2 mt-auto">
                    <button class="btn btn-success w-100" disabled>
                        {{ $isFreePlan ? 'Free Plan' : 'Current Plan' }}
                    </button>
                    @if (!$isFreePlan)
                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Cancel Subscription
                    </button>
                    <div class="d-flex justify-content-between small text-muted mt-2">
                        <span>Start: {{ $startDate }}</span>
                        <span class="text-danger">End: {{ $endDate }}</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="d-grid mt-auto">
                    <button type="button" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-1" data-bs-toggle="modal" data-bs-target="#upgradeModal{{ $plan->id }}">
                        Choose Plan
                    </button>
                </div>

                <div class="modal fade" id="upgradeModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header">
                                <h5 class="modal-title fw-semibold">Confirm Subscription</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to subscribe to <strong>{{ $plan->name }}</strong> plan?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('seller.plans.subscribe', $plan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">Yes, Subscribe</button>
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
                <h5 class="modal-title fw-semibold">Cancel Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel your subscription? You will be downgraded to the Free Plan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('seller.plans.subscribe', $plan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-1">Yes, Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
