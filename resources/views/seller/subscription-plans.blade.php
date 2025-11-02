@extends('seller.layouts.app')

@section('title', 'Subscription Plans')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="mb-0">Subscription Plans</h4>
    </div>

    <div class="row">
        @foreach ($plans as $plan)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm border-0 h-100 @if (optional($current_subscription)->subscription_plan_id == $plan->id) border-primary @endif">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold mb-2">{{ $plan->name }}</h5>
                        <h3 class="text-primary mb-3">
                            {{ money($plan->price) }}
                            <small class="text-muted">/ {{ $plan->duration_type }}</small>
                        </h3>

                        <ul class="list-group list-group-flush text-start mb-3">
                            <li class="list-group-item">
                                <i data-feather="shopping-cart" class="me-2"></i> Product Limit:
                                {{ $plan->product_limit }}
                            </li>
                            <li class="list-group-item">
                                <i data-feather="dollar-sign" class="me-2"></i> Commission Rate:
                                {{ $plan->commission_rate }}%
                            </li>
                            <li class="list-group-item">
                                <i data-feather="users" class="me-2"></i> Staff Accounts: {{ $plan->staff_account_limit }}
                            </li>
                            <li class="list-group-item">
                                <i data-feather="bar-chart-2" class="me-2"></i> Analytics Access:
                                @if ($plan->analytics_access)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </li>
                            <li class="list-group-item">
                                <i data-feather="credit-card" class="me-2"></i> POS Access:
                                @if ($plan->pos_access)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </li>
                            <li class="list-group-item">
                                <i data-feather="headphones" class="me-2"></i> Priority Support:
                                @if ($plan->priority_support)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </li>
                            <li class="list-group-item">
                                <i data-feather="globe" class="me-2"></i> Custom Domain:
                                @if ($plan->custom_domain)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </li>
                        </ul>

                        @php
                            $active = optional($current_subscription)->subscription_plan_id == $plan->id;
                            $isFreePlan = $plan->price == 0;
                            $startDate = optional($current_subscription)->start_date?->format('M d, Y');
                            $endDate = optional($current_subscription)->end_date?->format('M d, Y');
                        @endphp

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
                            <button type="button" class="btn btn-theme w-100" data-bs-toggle="modal"
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
                                            <form action="{{ route('seller.subscriptions.subscribe') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                <button type="submit" class="btn btn-theme">Yes, Subscribe</button>
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
                        <form action="{{ route('seller.subscriptions.subscribe') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="">
                            <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
