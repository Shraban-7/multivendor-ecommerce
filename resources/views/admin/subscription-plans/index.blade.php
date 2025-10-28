@extends('admin.layouts.app')
@section('title', 'Subscription Plans')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-dark mb-0">Subscription Plans</h4>
    <button class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#planModal" id="addPlanBtn">
        <i class="bi bi-plus-lg me-1"></i> Add Plan
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price (৳)</th>
                    <th>Duration</th>
                    <th>Product Limit</th>
                    <th>Commission</th>
                    <th>POS</th>
                    <th>Analytics</th>
                    <th>Priority</th>
                    <th>Domain</th>
                    <th>Staff</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plans as $plan)
                <tr>
                    <td class="fw-semibold">{{ $plan->name }}</td>
                    <td>{{ number_format($plan->price, 2) }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($plan->duration_type) }}</span></td>
                    <td>{{ $plan->product_limit == 0 ? 'Unlimited' : $plan->product_limit }}</td>
                    <td>{{ $plan->commission_rate }}%</td>
                    <td>{!! $plan->pos_access ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                    <td>{!! $plan->analytics_access ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                    <td>{!! $plan->priority_support ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                    <td>{!! $plan->custom_domain ? '<i class="bi bi-check-circle text-success"></i>' : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                    <td>{{ $plan->staff_account_limit }}</td>
                    <td class="text-center">
                        <button class="btn btn-outline-primary btn-sm editPlanBtn"
                            data-plan='@json($plan)'>
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-4 text-muted">No subscription plans found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="planForm">
            @csrf
            <input type="hidden" id="plan_id">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="planModalLabel">Add New Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Plan Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Price (৳)</label>
                            <input type="number" class="form-control" id="price" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Duration</label>
                            <select class="form-select" id="duration_type">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Product Limit</label>
                            <input type="number" class="form-control" id="product_limit" min="0" required>
                            <small class="text-muted">0 = unlimited</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Commission Rate (%)</label>
                            <input type="number" class="form-control" id="commission_rate" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Staff Accounts</label>
                            <input type="number" class="form-control" id="staff_account_limit" min="0" required>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="row">
                                @php $features = ['pos_access' => 'POS Access', 'analytics_access' => 'Analytics', 'priority_support' => 'Priority Support', 'custom_domain' => 'Custom Domain']; @endphp
                                @foreach ($features as $key => $label)
                                <div class="col-md-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="{{ $key }}">
                                    <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Plan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="toastMessage" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let modal = new bootstrap.Modal(document.getElementById('planModal'));
        let toastEl = document.getElementById('toastMessage');
        let toast = new bootstrap.Toast(toastEl);
        const planForm = document.getElementById('planForm');

        // Add Plan
        document.getElementById('addPlanBtn').addEventListener('click', () => {
            planForm.reset();
            document.getElementById('planModalLabel').innerText = 'Add New Plan';
            document.getElementById('plan_id').value = '';
        });

        // Edit Plan
        document.querySelectorAll('.editPlanBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                const plan = JSON.parse(btn.dataset.plan);
                document.getElementById('planModalLabel').innerText = 'Edit Plan';
                document.getElementById('plan_id').value = plan.id;
                document.getElementById('name').value = plan.name;
                document.getElementById('price').value = plan.price;
                document.getElementById('duration_type').value = plan.duration_type;
                document.getElementById('product_limit').value = plan.product_limit;
                document.getElementById('commission_rate').value = plan.commission_rate;
                document.getElementById('staff_account_limit').value = plan.staff_account_limit;

                ['pos_access', 'analytics_access', 'priority_support', 'custom_domain'].forEach(k => {
                    document.getElementById(k).checked = plan[k];
                });

                modal.show();
            });
        });

        // Submit Plan Form
        planForm.addEventListener('submit', e => {
            e.preventDefault();
            const id = document.getElementById('plan_id').value;
            const url = id ? `/admin/subscription-plans/${id}` : `/admin/subscription-plans`;
            const method = id ? 'PUT' : 'POST';

            const data = {
                _token: '{{ csrf_token() }}',
                _method: method,
                name: document.getElementById('name').value,
                price: document.getElementById('price').value,
                duration_type: document.getElementById('duration_type').value,
                product_limit: document.getElementById('product_limit').value,
                commission_rate: document.getElementById('commission_rate').value,
                staff_account_limit: document.getElementById('staff_account_limit').value,
                pos_access: document.getElementById('pos_access').checked ? 1 : 0,
                analytics_access: document.getElementById('analytics_access').checked ? 1 : 0,
                priority_support: document.getElementById('priority_support').checked ? 1 : 0,
                custom_domain: document.getElementById('custom_domain').checked ? 1 : 0,
            };

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        document.querySelector('.toast-body').innerText = res.message;
                        toast.show();
                        setTimeout(() => location.reload(), 1000);
                    }
                });
        });
    });
</script>
@endpush