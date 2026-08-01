@php
    $pageTitle = 'Subscription Plans';

    $featureLabels = [
        'pos_access' => ['label' => 'POS Access', 'icon' => 'monitor'],
        'analytics_access' => ['label' => 'Analytics', 'icon' => 'bar-chart-3'],
        'priority_support' => ['label' => 'Priority Support', 'icon' => 'headphones'],
        'custom_domain' => ['label' => 'Custom Domain', 'icon' => 'globe'],
        'payment_checker' => ['label' => 'Payment Checker', 'icon' => 'shield-check'],
        'analytics' => ['label' => 'Analytics', 'icon' => 'bar-chart-3'],
    ];
@endphp
@extends('admin.layouts.app')
@section('title', $pageTitle)

@section('content')

    {{-- ═══ HERO ═══ --}}
    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);">
        </div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="credit-card" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                        <span>Finance</span>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">Subscription Plans</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                            <i data-lucide="layers" style="width:11px;height:11px;" class="me-1"></i> Catalog
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Define the pricing tiers, product limits and feature flags
                        you sell to sellers.</p>
                </div>
                <div>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-light btn-sm me-1">
                        <i data-lucide="users" class="icon-xs"></i> Subscribers
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#planModal"
                        id="addPlanBtn">
                        <i data-lucide="plus" class="icon-xs"></i> Add Plan
                    </button>
                </div>
            </div>
        </div>
    </section>

    @if ($plans->isEmpty())
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="p-10 text-center">
                <div
                    class="shrink-0 w-16 h-16 rounded-full bg-brand-tint text-brand-deep mx-auto flex items-center justify-center mb-4">
                    <i data-lucide="layers" style="width:32px;height:32px;"></i>
                </div>
                <h5 class="font-bold text-ink-emphasis mb-1">No subscription plans yet</h5>
                <p class="text-ink-tertiary mb-4">Define at least one plan so sellers can subscribe to your marketplace.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#planModal">
                    <i data-lucide="plus" class="icon-xs me-1"></i> Create First Plan
                </button>
            </div>
        </section>
    @else
        {{-- ═══ PRICE GRID ═══ --}}
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-4">
            @foreach ($plans as $i => $plan)
                <article class="bg-white rounded-sm shadow-sm overflow-hidden h-full relative">
                    <div @class([
                        'absolute top-0 left-0 right-0 h-1',
                        'bg-brand' => $i === 0,
                        'bg-blue-500' => $i === 1,
                        'bg-amber-500' => $i === 2,
                        'bg-emerald-500' => $i === 3,
                        'bg-purple-500' => $i > 3,
                    ])></div>

                    <div class="p-5 flex flex-col h-full">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-ink-tertiary">Tier
                                    {{ $i + 1 }}</p>
                                <h3 class="font-bold text-xl text-ink-emphasis mb-0 truncate">{{ $plan->name }}</h3>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" class="btn btn-light btn-sm editPlanBtn"
                                    data-plan='@json($plan)' title="Edit plan">
                                    <i data-lucide="edit" class="icon-xs"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm deletePlanBtn"
                                    data-id="{{ $plan->id }}" data-name="{{ $plan->name }}" title="Delete plan">
                                    <i data-lucide="trash-2" class="icon-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-center mb-3">
                            <div class="flex items-baseline justify-center gap-1">
                                <h2 class="font-bold text-ink-emphasis" style="font-size:2.4rem;line-height:1;">
                                    {{ money($plan->price) }}</h2>
                            </div>
                            <small class="text-ink-tertiary">per
                                {{ $plan->duration_type === 'yearly' ? 'year' : 'month' }}</small>
                        </div>

                        <ul class="space-y-2 text-sm mb-4 grow">
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="text-feedback-success shrink-0 mt-0.5"
                                    style="width:16px;height:16px;"></i>
                                <span class="text-ink-emphasis"><strong>{{ $plan->commission_rate }}%</strong>
                                    commission</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="text-feedback-success shrink-0 mt-0.5"
                                    style="width:16px;height:16px;"></i>
                                <span class="text-ink-emphasis">
                                    <strong>{{ $plan->product_limit == 0 ? 'Unlimited' : number_format($plan->product_limit) }}</strong>
                                    {{ Str::plural('product', $plan->product_limit ?: 1) }}
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="text-feedback-success shrink-0 mt-0.5"
                                    style="width:16px;height:16px;"></i>
                                <span class="text-ink-emphasis">
                                    <strong>{{ $plan->staff_account_limit }}</strong>
                                    {{ Str::plural('staff account', $plan->staff_account_limit) }}
                                </span>
                            </li>
                            @foreach (['pos_access', 'analytics', 'priority_support', 'custom_domain', 'payment_checker'] as $feature)
                                @if (!empty($plan->{$feature}))
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="check-circle" class="text-feedback-success shrink-0 mt-0.5"
                                            style="width:16px;height:16px;"></i>
                                        <span
                                            class="text-ink-emphasis">{{ $featureLabels[$feature]['label'] ?? ucwords(str_replace('_', ' ', $feature)) }}</span>
                                    </li>
                                @endif
                            @endforeach
                            @foreach (['pos_access', 'analytics', 'priority_support', 'custom_domain', 'payment_checker'] as $feature)
                                @if (empty($plan->{$feature}))
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="minus-circle" class="text-ink-tertiary shrink-0 mt-0.5"
                                            style="width:16px;height:16px;"></i>
                                        <span
                                            class="text-ink-tertiary line-through">{{ $featureLabels[$feature]['label'] ?? ucwords(str_replace('_', ' ', $feature)) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                        <div class="flex gap-2 pt-3 border-t border-border">
                            <button type="button" class="btn btn-primary btn-sm flex-1 editPlanBtn"
                                data-plan='@json($plan)'>
                                <i data-lucide="pencil" class="icon-xs me-1"></i> Edit
                            </button>
                            <button type="button" class="btn btn-light btn-sm deletePlanBtn"
                                data-id="{{ $plan->id }}" data-name="{{ $plan->name }}">
                                <i data-lucide="trash-2" class="icon-xs"></i>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif

    @push('modals')
        {{-- Delete Confirmation --}}
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-b border-border bg-surface-muted">
                        <div class="flex items-center gap-2">
                            <span
                                class="shrink-0 w-9 h-9 rounded-sm bg-rose-50 text-rose-500 flex items-center justify-center">
                                <i data-lucide="trash-2" style="width:18px;height:18px;"></i>
                            </span>
                            <h5 class="modal-title font-bold text-ink-emphasis mb-0" id="deleteConfirmLabel">Delete Plan</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-sm text-ink-secondary mb-0">
                            Are you sure you want to delete <strong class="text-ink-emphasis" id="deletePlanName"></strong>?
                            Subscribed sellers will need to be migrated before this takes effect.
                        </p>
                        <input type="hidden" id="deletePlanId">
                    </div>
                    <div class="modal-footer border-t border-border bg-surface-muted">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                            <i data-lucide="trash-2" class="icon-xs me-1"></i> Delete Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add / Edit Plan --}}
        <div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form id="planForm">
                    @csrf
                    <input type="hidden" id="plan_id">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header border-b border-border bg-surface-muted">
                            <div class="flex items-center gap-2">
                                <span
                                    class="shrink-0 w-9 h-9 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center">
                                    <i data-lucide="layers" style="width:18px;height:18px;"></i>
                                </span>
                                <h5 class="modal-title font-bold text-ink-emphasis mb-0" id="planModalLabel">Add New Plan</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Plan
                                        Name </label>
                                    <input type="text" id="name" required placeholder="e.g. Pro Plan"
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Price
                                        (৳) </label>
                                    <input type="number" id="price" min="0" step="0.01" required
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Duration</label>
                                    <select id="duration_type"
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Commission
                                        Rate (%) </label>
                                    <input type="number" id="commission_rate" step="0.01" required
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Product
                                        Limit</label>
                                    <input type="number" id="product_limit" min="0" required
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                    <small class="text-ink-tertiary mt-1 block">0 = unlimited</small>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Staff
                                        Accounts</label>
                                    <input type="number" id="staff_account_limit" min="0" required
                                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                </div>
                            </div>

                            <div class="mt-4">
                                <p class="block text-xs font-semibold text-ink-secondary mb-2 uppercase tracking-wider">Feature
                                    flags</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach (['pos_access' => 'POS Access', 'analytics' => 'Analytics', 'priority_support' => 'Priority Support', 'custom_domain' => 'Custom Domain', 'payment_checker' => 'Payment Checker'] as $key => $label)
                                        <label
                                            class="flex items-center gap-2 px-3 py-2 rounded-xs bg-surface-muted cursor-pointer hover:bg-brand-tint transition-colors">
                                            <input type="checkbox" id="{{ $key }}"
                                                class="h-4 w-4 rounded border-border text-brand focus:ring-brand focus:ring-2">
                                            <span class="text-sm text-ink-emphasis">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-t border-border bg-surface-muted">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="icon-xs me-1"></i> Save Plan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endpush

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
                    ['pos_access', 'analytics', 'priority_support', 'custom_domain', 'payment_checker'].forEach(
                        function(key) {
                            $('#' + key).prop('checked', plan[key] == 1 || plan[key] === true);
                        });
                    modal.show();
                });

                $('.deletePlanBtn').on('click', function() {
                    $('#deletePlanId').val($(this).data('id'));
                    $('#deletePlanName').text($(this).data('name'));
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
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                showErrorToast(xhr.responseJSON.message);
                            } else {
                                showErrorToast('An unexpected error occurred.');
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
                        analytics: $('#analytics').is(':checked') ? 1 : 0,
                        priority_support: $('#priority_support').is(':checked') ? 1 : 0,
                        custom_domain: $('#custom_domain').is(':checked') ? 1 : 0,
                        payment_checker: $('#payment_checker').is(':checked') ? 1 : 0,
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
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    showErrorToast(value[0]);
                                });
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                showErrorToast(xhr.responseJSON.message);
                            } else {
                                showErrorToast('An unexpected error occurred.');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
