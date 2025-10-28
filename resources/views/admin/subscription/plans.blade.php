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
                            <td>{!! $plan->pos_access
                                ? '<i class="bi bi-check-circle text-success"></i>'
                                : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                            <td>{!! $plan->analytics_access
                                ? '<i class="bi bi-check-circle text-success"></i>'
                                : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                            <td>{!! $plan->priority_support
                                ? '<i class="bi bi-check-circle text-success"></i>'
                                : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                            <td>{!! $plan->custom_domain
                                ? '<i class="bi bi-check-circle text-success"></i>'
                                : '<i class="bi bi-x-circle text-danger"></i>' !!}</td>
                            <td>{{ $plan->staff_account_limit }}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-primary btn-sm editPlanBtn me-1"
                                    data-plan='@json($plan)'>
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                {{-- 🔽 Delete button added --}}
                                <button class="btn btn-outline-danger btn-sm deletePlanBtn" data-id="{{ $plan->id }}"
                                    data-name="{{ $plan->name }}">
                                    <i class="bi bi-trash"></i>
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

    {{-- 🔽 Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-white">
                    <h5 class="modal-title" id="deleteConfirmLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete the plan <strong id="deletePlanName"></strong>?</p>
                    <input type="hidden" id="deletePlanId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add/Edit Modal (unchanged) --}}
    <div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="planForm"> @csrf <input type="hidden" id="plan_id">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="planModalLabel">Add New Plan</h5> <button type="button"
                            class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6"> <label class="form-label">Plan Name</label> <input type="text"
                                    class="form-control" id="name" required> </div>
                            <div class="col-md-3"> <label class="form-label">Price (৳)</label> <input type="number"
                                    class="form-control" id="price" min="0" required> </div>
                            <div class="col-md-3"> <label class="form-label">Duration</label> <select class="form-select"
                                    id="duration_type">
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select> </div>
                            <div class="col-md-4"> <label class="form-label">Product Limit</label> <input type="number"
                                    class="form-control" id="product_limit" min="0" required> <small
                                    class="text-muted">0 = unlimited</small> </div>
                            <div class="col-md-4"> <label class="form-label">Commission Rate (%)</label> <input
                                    type="number" class="form-control" id="commission_rate" step="0.01" required> </div>
                            <div class="col-md-4"> <label class="form-label">Staff Accounts</label> <input type="number"
                                    class="form-control" id="staff_account_limit" min="0" required> </div>
                            <div class="col-12 mt-3"> @php $features = [ 'pos_access' => 'POS Access', 'analytics_access' => 'Analytics', 'priority_support' => 'Priority Support', 'custom_domain' => 'Custom Domain', ]; @endphp <div class="row g-2">
                                    @foreach ($features as $key => $label)
                                        <div class="col-6 col-md-3">
                                            <div class="form-check"> <input type="checkbox" class="form-check-input"
                                                    id="{{ $key }}"> <label class="form-check-label"
                                                    for="{{ $key }}">{{ $label }}</label> </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"> <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button> <button type="submit" class="btn btn-success">Save
                            Plan</button> </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const modal = new bootstrap.Modal($('#planModal')[0]);
            const deleteModal = new bootstrap.Modal($('#deleteConfirmModal')[0]);
            const $form = $('#planForm');

            // Add Plan
            $('#addPlanBtn').on('click', function() {
                $form[0].reset();
                $('#planModalLabel').text('Add New Plan');
                $('#plan_id').val('');
            });

            // Edit Plan
            $('.editPlanBtn').on('click', function() {
                const plan = $(this).data('plan');
                $('#planModalLabel').text('Edit Plan');
                $('#plan_id').val(plan.id);
                $('#name').val(plan.name);
                $('#price').val(plan.price);
                $('#duration_type').val(plan.duration_type);
                $('#product_limit').val(plan.product_limit);
                $('#commission_rate').val(plan.commission_rate);
                $('#staff_account_limit').val(plan.staff_account_limit);
                ['pos_access', 'analytics_access', 'priority_support', 'custom_domain'].forEach(key => {
                    $('#' + key).prop('checked', plan[key] == 1);
                });
                modal.show();
            });

            // 🔽 Delete Plan - Open modal
            $('.deletePlanBtn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#deletePlanId').val(id);
                $('#deletePlanName').text(name);
                deleteModal.show();
            });

            // 🔽 Confirm Delete
            $('#confirmDeleteBtn').on('click', function() {
                const id = $('#deletePlanId').val();
                const url = "{{ route('admin.subscription-plans.delete', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(res) {
                        deleteModal.hide();
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            toastr.error(res.message || 'Failed to delete plan.');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error(`Error: ${error || 'An unexpected error occurred.'}`);
                        }
                    }
                });
            });


            // Save Plan (existing logic)
            $form.on('submit', function(e) {
                e.preventDefault();
                const id = $('#plan_id').val();
                const url = id ? `/admin/subscription-plans/${id}` : `/admin/subscription-plans`;
                const method = id ? 'PUT' : 'POST';
                const data = {
                    _token: '{{ csrf_token() }}',
                    _method: method,
                    name: $('#name').val(),
                    price: $('#price').val(),
                    duration_type: $('#duration_type').val(),
                    product_limit: $('#product_limit').val(),
                    commission_rate: $('#commission_rate').val(),
                    staff_account_limit: $('#staff_account_limit').val(),
                    pos_access: $('#pos_access').is(':checked') ? 1 : 0,
                    analytics_access: $('#analytics_access').is(':checked') ? 1 : 0,
                    priority_support: $('#priority_support').is(':checked') ? 1 : 0,
                    custom_domain: $('#custom_domain').is(':checked') ? 1 : 0,
                };

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(res) {
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            toastr.error(res.message || 'Something went wrong!');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error(`Error: ${error || 'An unexpected error occurred.'}`);
                        }
                    }
                });
            });
        });
    </script>
@endpush
