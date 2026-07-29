@extends('admin.layouts.app')
@section('title', 'Subscription Plans')

@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="text-ink mb-0">Subscription Plans</h4>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#planModal" id="addPlanBtn">
            <i class="bi bi-plus-lg me-1"></i> Add Plan
        </button>
    </div>


    <div class="grid grid-cols-1 gap-4">
        @foreach ($plans as $plan)
            <div class="col-span-full sm:col-span-1 lg:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden shadow-sm h-full border-0">
                    <div class="p-5 flex flex-col">
                        <h5 class="text-lg font-semibold text-center text-brand font-bold mb-3">
                            {{ $plan->name }}
                        </h5>

                        <h2 class="text-center mb-1 font-bold">
                            {{ money($plan->price) }}
                        </h2>
                        <p class="text-center text-ink-tertiary mb-4">
                            {{ ucfirst($plan->duration_type) }}
                        </p>

                        <ul class="list-none text-sm grow">
                            <li>✅ <span
                                    class="font-semibold">{{ $plan->product_limit == 0 ? 'Unlimited' : $plan->product_limit }}</span>
                                Products</li>
                            <li>✅ <span class="font-semibold">{{ $plan->commission_rate }}%</span> Commission</li>

                            @if ($plan->pos_access)
                                <li class="font-semibold">✅ POS Access</li>
                            @else
                                <li class="text-ink-tertiary text-decoration-line-through">❌ POS Access</li>
                            @endif

                            @if ($plan->analytics_access)
                                <li class="font-semibold">✅ Analytics Access</li>
                            @else
                                <li class="text-ink-tertiary text-decoration-line-through">❌ Analytics Access</li>
                            @endif

                            @if ($plan->priority_support)
                                <li class="font-semibold">✅ Priority Support</li>
                            @else
                                <li class="text-ink-tertiary text-decoration-line-through">❌ Priority Support</li>
                            @endif

                            @if ($plan->custom_domain)
                                <li class="font-semibold">✅ Custom Domain</li>
                            @else
                                <li class="text-ink-tertiary text-decoration-line-through">❌ Custom Domain</li>
                            @endif

                            @if ($plan->payment_checker)
                                <li class="font-semibold">✅ Payment Checker</li>
                            @else
                                <li class="text-ink-tertiary text-decoration-line-through">❌ Payment Checker</li>
                            @endif

                            <li>👥 <span class="font-semibold">{{ $plan->staff_account_limit }}</span> Staff Accounts</li>
                        </ul>

                        <div class="flex justify-between gap-2 mt-3">
                            <!-- Edit Button -->
                            <button class="btn btn-primary btn-sm btn-block editPlanBtn"
                                data-plan='@json($plan)' title="Edit Plan">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>

                            <!-- Delete Button -->
                            <button class="btn btn-danger btn-sm btn-block deletePlanBtn" data-id="{{ $plan->id }}"
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
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
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
                        <div class="grid grid-cols-1 gap-3">
                            <div class="md:col-span-1"> <label class="block text-xs font-medium text-ink-secondary mb-1">Plan Name</label> <input type="text"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="name" required> </div>
                            <div class="md:col-span-1"> <label class="block text-xs font-medium text-ink-secondary mb-1">Price (৳)</label> <input type="number"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="price" min="0" required> </div>
                            <div class="md:col-span-1"> <label class="block text-xs font-medium text-ink-secondary mb-1">Duration</label> <select class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                    id="duration_type">
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select> </div>
                            <div class="md:col-span-1"> <label class="block text-xs font-medium text-ink-secondary mb-1">Product Limit</label> <input type="number"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="product_limit" min="0" required> <small
                                    class="text-ink-tertiary">0 = unlimited</small> </div>
                            <div class="md:col-span-1"> <label class="block text-xs font-medium text-ink-secondary mb-1">Commission Rate (%)</label> <input
                                    type="number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="commission_rate" step="0.01" required>
                            </div>
                            <div class="md:col-span-1"> <label class="block text-xs font-medium text-ink-secondary mb-1">Staff Accounts</label> <input type="number"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" id="staff_account_limit" min="0" required> </div>
                            <div class="col-span-full mt-3"> @php $features = [ 'pos_access' => 'POS Access', 'analytics_access' => 'Analytics', 'priority_support' => 'Priority Support', 'custom_domain' => 'Custom Domain', 'payment_checker' => 'Payment Checker',]; @endphp <div class="grid grid-cols-1 gap-2">
                                    @foreach ($features as $key => $label)
                                        <div class="col-span-1 md:col-span-1">
                                            <div class="flex items-center gap-2"> <input type="checkbox" class="h-4 w-4 rounded border-border text-brand focus:ring-brand"
                                                    id="{{ $key }}"> <label class="text-sm text-ink"
                                                    for="{{ $key }}">{{ $label }}</label> </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"> <button type="button" class="btn btn-light"
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
                            showSuccessToast(res.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showErrorToast(res.message || 'Failed to delete plan.');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                showErrorToast(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            showErrorToast(xhr.responseJSON.message);
                        } else {
                            showErrorToast(`Error: ${error || 'An unexpected error occurred.'}`);
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
                            showSuccessToast(res.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showErrorToast(res.message || 'Something went wrong!');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {  
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                showErrorToast(value[0]);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            showErrorToast(xhr.responseJSON.message);
                        } else {
                            showErrorToast(`Error: ${error || 'An unexpected error occurred.'}`);
                        }
                    }
                });
            });
        });
    </script>
@endpush
