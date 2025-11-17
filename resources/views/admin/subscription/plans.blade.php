@extends('admin.layouts.app')
@section('title', 'Subscription Plans')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-dark mb-0">Subscription Plans</h4>
        <button class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#planModal" id="addPlanBtn">
            <i class="bi bi-plus-lg me-1"></i> Add Plan
        </button>
    </div>


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
                            <li>✅ <span
                                    class="fw-semibold">{{ $plan->product_limit == 0 ? 'Unlimited' : $plan->product_limit }}</span>
                                Products</li>
                            <li>✅ <span class="fw-semibold">{{ $plan->commission_rate }}%</span> Commission</li>

                            @if ($plan->pos_access)
                                <li class="fw-semibold">✅ POS Access</li>
                            @else
                                <li class="text-muted text-decoration-line-through">❌ POS Access</li>
                            @endif

                            @if ($plan->analytics_access)
                                <li class="fw-semibold">✅ Analytics Access</li>
                            @else
                                <li class="text-muted text-decoration-line-through">❌ Analytics Access</li>
                            @endif

                            @if ($plan->priority_support)
                                <li class="fw-semibold">✅ Priority Support</li>
                            @else
                                <li class="text-muted text-decoration-line-through">❌ Priority Support</li>
                            @endif

                            @if ($plan->custom_domain)
                                <li class="fw-semibold">✅ Custom Domain</li>
                            @else
                                <li class="text-muted text-decoration-line-through">❌ Custom Domain</li>
                            @endif

                            @if ($plan->payment_checker)
                                <li class="fw-semibold">✅ Payment Checker</li>
                            @else
                                <li class="text-muted text-decoration-line-through">❌ Payment Checker</li>
                            @endif

                            <li>👥 <span class="fw-semibold">{{ $plan->staff_account_limit }}</span> Staff Accounts</li>
                        </ul>

                        <div class="d-flex justify-content-between gap-2 mt-3">
                            <!-- Edit Button -->
                            <button class="btn btn-sm btn-primary rounded-lg w-full editPlanBtn"
                                data-plan='@json($plan)' title="Edit Plan">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>

                            <!-- Delete Button -->
                            <button class="btn btn-sm btn-danger rounded-lg w-full deletePlanBtn" data-id="{{ $plan->id }}"
                                data-name="{{ $plan->name }}" title="Delete Plan">
                                <i class="bi bi-trash-fill"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
                                    type="number" class="form-control" id="commission_rate" step="0.01" required>
                            </div>
                            <div class="col-md-4"> <label class="form-label">Staff Accounts</label> <input type="number"
                                    class="form-control" id="staff_account_limit" min="0" required> </div>
                            <div class="col-12 mt-3"> @php $features = [ 'pos_access' => 'POS Access', 'analytics_access' => 'Analytics', 'priority_support' => 'Priority Support', 'custom_domain' => 'Custom Domain', 'payment_checker' => 'Payment Checker',]; @endphp <div class="row g-2">
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

            $('#addPlanBtn').on('click', function() {
                $form[0].reset();
                $('#planModalLabel').text('Add New Plan');
                $('#plan_id').val('');
            });

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

            $('.deletePlanBtn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                $('#deletePlanId').val(id);
                $('#deletePlanName').text(name);
                deleteModal.show();
            });

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
