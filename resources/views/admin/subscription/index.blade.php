@extends('admin.layouts.app')
@section('title', 'Seller Subscriptions')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Seller Subscriptions</h4>
    <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-primary btn-sm px-3">
        <i class="bi bi-card-list me-1"></i> Manage Plans
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Seller Name</th>
                        <th>Email</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Remaining Days</th>
                        <th>Commission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscriptions as $sub)
                    <tr>
                        <td class="fw-semibold text-dark">{{ $sub->seller->name ?? 'N/A' }}</td>
                        <td>{{ $sub->seller->email ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $sub->plan->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if ($sub->status === 'active')
                            <span class="badge bg-success">Active</span>
                            @elseif ($sub->status === 'expired')
                            <span class="badge bg-danger">Expired</span>
                            @else
                            <span class="badge bg-secondary text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $sub->start_date->format('d M, Y') }}</td>
                        <td>{{ $sub->end_date->format('d M, Y') }}</td>
                        <td>
                            @php
                            $remaining = $sub->end_date->diffInDays(now(), false);
                            @endphp
                            @if ($remaining > 0)
                            <span class="text-success fw-semibold">{{ $remaining }} days left</span>
                            @elseif ($remaining === 0)
                            <span class="text-warning fw-semibold">Expiring today</span>
                            @else
                            <span class="text-danger fw-semibold">{{ abs($remaining) }} days ago</span>
                            @endif
                        </td>
                        <td>{{ $sub->commission_rate }}%</td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#changePlanModal{{ $sub->id }}">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#viewPlanModal{{ $sub->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Change Plan Modal -->
                    <div class="modal fade" id="changePlanModal{{ $sub->id }}" tabindex="-1" aria-labelledby="changePlanModalLabel{{ $sub->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="changePlanModalLabel{{ $sub->id }}">Change Subscription Plan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Select New Plan</label>
                                            <select class="form-select" name="plan_id" required>
                                                @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}" {{ $plan->id == $sub->plan_id ? 'selected' : '' }}>
                                                    {{ $plan->name }} - ৳{{ $plan->price }} ({{ $plan->duration_in_days }} days)
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">New End Date</label>
                                            <input type="date" class="form-control" name="end_date"
                                                value="{{ $sub->end_date->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Plan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- View Plan Modal -->
                    <div class="modal fade" id="viewPlanModal{{ $sub->id }}" tabindex="-1" aria-labelledby="viewPlanModalLabel{{ $sub->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title" id="viewPlanModalLabel{{ $sub->id }}">Subscription Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <p class="mb-1 fw-semibold text-muted">Seller</p>
                                            <p class="mb-2">{{ $sub->seller->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 fw-semibold text-muted">Plan</p>
                                            <p class="mb-2">{{ $sub->plan->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 fw-semibold text-muted">Status</p>
                                            <p class="mb-2">{{ ucfirst($sub->status) }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 fw-semibold text-muted">Duration</p>
                                            <p class="mb-2">{{ $sub->plan->duration_in_days }} days</p>
                                        </div>
                                        <div class="col-md-12">
                                            <p class="mb-1 fw-semibold text-muted">Features</p>
                                            <ul class="mb-0">
                                                <li>POS: {{ $sub->plan->pos_access ? 'Yes' : 'No' }}</li>
                                                <li>Analytics: {{ $sub->plan->analytics_access ? 'Yes' : 'No' }}</li>
                                                <li>Priority Support: {{ $sub->plan->priority_support ? 'Yes' : 'No' }}</li>
                                                <li>Product Limit: {{ $sub->plan->product_limit == 0 ? 'Unlimited' : $sub->plan->product_limit }}</li>
                                                <li>Commission Rate: {{ $sub->commission_rate }}%</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No seller subscriptions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{ $subscriptions->links() }}

@endsection