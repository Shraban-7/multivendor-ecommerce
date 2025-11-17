@extends('seller.layouts.app')
@section('title', 'Subscription Plans')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Subscription Plans</h4>
</div>

<?php
$currentPlanId = $current_subscription->plan_id ?? 0;
?>

<div class="row g-4">
    @foreach ($plans as $plan)
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-center text-primary fw-bold mb-3">
                    {{ $plan->name }}
                </h5>

                <h2 class="text-center mb-1 fw-bold">
                    {{ money($plan->price) }}
                </h2>
                <p class="text-center text-muted mb-4">
                    {{ ucfirst($plan->duration_type) }}
                </p>

                <ul class="list-unstyled small flex-grow-1">
                    <li>✅ <span class="fw-semibold">{{ $plan->product_limit == 0 ? 'Unlimited' : $plan->product_limit }}</span> Products</li>
                    <li>✅ <span class="fw-semibold">{{ $plan->commission_rate }}%</span> Commission</li>

                    @if ($plan->pos_access)
                    <li class="text-success">✅ POS Access</li>
                    @else
                    <li class="text-muted text-decoration-line-through">❌ POS Access</li>
                    @endif

                    @if ($plan->analytics_access)
                    <li class="text-success">✅ Analytics Access</li>
                    @else
                    <li class="text-muted text-decoration-line-through">❌ Analytics Access</li>
                    @endif

                    @if ($plan->priority_support)
                    <li class="text-success">✅ Priority Support</li>
                    @else
                    <li class="text-muted text-decoration-line-through">❌ Priority Support</li>
                    @endif

                    @if ($plan->custom_domain)
                    <li class="text-success">✅ Custom Domain</li>
                    @else
                    <li class="text-muted text-decoration-line-through">❌ Custom Domain</li>
                    @endif

                    <li>👥 <span class="fw-semibold">{{ $plan->staff_account_limit }}</span> Staff Accounts</li>
                </ul>

                <?php
                $active = optional($current_subscription)->subscription_plan_id == $plan->id;
                $isFreePlan = $plan->price == 0;
                $startDate = optional($current_subscription)->start_date?->format('M d, Y');
                $endDate = optional($current_subscription)->end_date?->format('M d, Y');
                ?>

                @if ($active)
                <button class="btn btn-success w-100 mb-2" disabled>
                    {{ $isFreePlan ? 'Free Plan' : 'Current Plan' }}
                </button>
                @if (!$isFreePlan)
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                    data-bs-target="#cancelModal">
                    Cancel Subscription
                </button>
                <div class="d-flex gap-3 small text-muted mt-2">
                    <span>
                        Start: {{ $startDate }}
                    </span>
                    <span class="text-danger">
                        End: {{ $endDate }}
                    </span>
                </div>
                @endif
                @else
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                    data-bs-target="#upgradeModal{{ $plan->id }}">
                    Choose Plan
                </button>

                <div class="modal fade" id="upgradeModal{{ $plan->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Subscription</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to subscribe to <strong>{{ $plan->name }}</strong>
                                plan?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel your subscription? You will be downgraded to the Free Plan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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