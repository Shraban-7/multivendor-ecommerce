@php
    $methodIconMap = [
        'bank'           => ['icon' => 'building',   'tone' => 'brand',   'pill' => 'bg-brand-tint text-brand-deep'],
        'mobile_banking' => ['icon' => 'smartphone', 'tone' => 'success', 'pill' => 'bg-emerald-50 text-feedback-success'],
    ];
@endphp
@extends('seller.layouts.app')
@section('title', 'Payout Methods')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="banknote" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.payouts.index') }}" class="hover:text-ink-soft transition-colors">Payouts</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Payout Methods</span>
                </nav>
                <h1 class="text-xl font-bold text-ink-emphasis mb-1">Payout Methods</h1>
                <p class="text-sm text-ink-secondary mb-0">
                    @if ($methods->count() > 0)
                        {{ $methods->count() }} {{ Str::plural('method', $methods->count()) }} saved · {{ $methods->where('is_default', true)->count() }} default
                    @else
                        Add at least one payout method to withdraw your earnings.
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.payouts.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back
                </a>
                @if ($methods->count() > 0)
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                        <i data-lucide="plus" class="icon-xs"></i> Add Method
                    </button>
                @endif
            </div>
        </div>
    </div>
</section>

@if ($methods->count() > 0)
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach ($methods as $method)
            @php
                $meta = $methodIconMap[$method->method_type] ?? ['icon' => 'dollar-sign', 'tone' => 'muted', 'pill' => 'bg-surface-muted text-ink-soft'];
            @endphp
            <article class="bg-white rounded-sm shadow-sm overflow-hidden h-full relative">
                <div class="h-1 {{ $method->is_default ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center {{ $meta['pill'] }}">
                                <i data-lucide="{{ $meta['icon'] }}" style="width:20px;height:20px;"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="mb-0 font-bold text-ink-emphasis truncate">{{ $method->methodLabel() }}</p>
                                <p class="mb-0 text-[11px] text-ink-tertiary uppercase tracking-wider font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $method->method_type)) }}
                                </p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm" data-bs-toggle="dropdown" aria-expanded="false" title="Manage">
                                <i data-lucide="more-vertical" style="width:16px;height:16px;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editMethodModal-{{ $method->id }}">
                                        <i data-lucide="edit" class="icon-xs me-2"></i> Edit
                                    </button>
                                </li>
                                @if (! $method->is_default)
                                    <li>
                                        <form method="POST" action="{{ route('seller.payouts.methods.default', $method) }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i data-lucide="star" class="icon-xs me-2"></i> Set as Default
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('seller.payouts.methods.destroy', $method) }}"
                                          onsubmit="return confirm('Delete this payout method? Pending withdrawals may be affected.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-rose-600">
                                            <i data-lucide="trash-2" class="icon-xs me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if ($method->is_default)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-feedback-success mb-3">
                            <i data-lucide="star" style="width:11px;height:11px;" class="me-1"></i> Default
                        </span>
                    @endif

                    <dl class="text-sm space-y-1.5">
                        <div class="flex justify-between gap-2">
                            <dt class="text-ink-tertiary shrink-0">Account Name</dt>
                            <dd class="font-medium text-ink-emphasis truncate text-right">{{ $method->account_name }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt class="text-ink-tertiary shrink-0">Account Number</dt>
                            <dd class="font-mono font-medium text-ink-emphasis">{{ $method->maskedAccountNumber() }}</dd>
                        </div>
                        @if ($method->bank_name)
                        <div class="flex justify-between gap-2">
                            <dt class="text-ink-tertiary shrink-0">Bank</dt>
                            <dd class="font-medium text-ink-emphasis truncate text-right">{{ $method->bank_name }}</dd>
                        </div>
                        @endif
                        @if ($method->branch_name)
                        <div class="flex justify-between gap-2">
                            <dt class="text-ink-tertiary shrink-0">Branch</dt>
                            <dd class="font-medium text-ink-emphasis truncate text-right">{{ $method->branch_name }}</dd>
                        </div>
                        @endif
                        @if ($method->routing_number)
                        <div class="flex justify-between gap-2">
                            <dt class="text-ink-tertiary shrink-0">Routing</dt>
                            <dd class="font-mono font-medium text-ink-emphasis">{{ $method->routing_number }}</dd>
                        </div>
                        @endif
                        @if ($method->mobile_provider)
                        <div class="flex justify-between gap-2">
                            <dt class="text-ink-tertiary shrink-0">Provider</dt>
                            <dd class="font-medium text-ink-emphasis">{{ ucfirst($method->mobile_provider) }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </article>

            {{-- Edit Modal --}}
            <div class="modal fade" id="editMethodModal-{{ $method->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                        <form method="POST" action="{{ route('seller.payouts.methods.update', $method) }}">
                            @csrf
                            <div class="modal-header border-b border-border bg-surface-muted">
                                <div class="flex items-center gap-2">
                                    <span class="shrink-0 w-9 h-9 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center">
                                        <i data-lucide="edit" style="width:18px;height:18px;"></i>
                                    </span>
                                    <h5 class="modal-title font-bold text-ink-emphasis mb-0">Edit {{ $method->methodLabel() }}</h5>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('seller.payouts._method_form', ['method' => $method])
                            </div>
                            <div class="modal-footer border-t border-border bg-surface-muted">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="save" class="icon-xs me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@else
    <section class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="p-10 text-center">
            <div class="shrink-0 w-16 h-16 rounded-full bg-brand-tint text-brand-deep mx-auto flex items-center justify-center mb-4">
                <i data-lucide="credit-card" style="width:32px;height:32px;"></i>
            </div>
            <h5 class="font-bold text-ink-emphasis mb-1">No Payout Methods</h5>
            <p class="text-ink-tertiary mb-4">Add a payout method to start withdrawing your earnings. Your default method will be pre-selected when requesting a payout.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                <i data-lucide="plus" class="icon-xs me-1"></i> Add Payout Method
            </button>
        </div>
    </section>
@endif

{{-- Add Method Modal --}}
<div class="modal fade" id="addMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('seller.payouts.methods.store') }}">
                @csrf
                <div class="modal-header border-b border-border bg-surface-muted">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center">
                            <i data-lucide="plus" style="width:18px;height:18px;"></i>
                        </span>
                        <h5 class="modal-title font-bold text-ink-emphasis mb-0">Add Payout Method</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('seller.payouts._method_form', ['method' => null])
                </div>
                <div class="modal-footer border-t border-border bg-surface-muted">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" class="icon-xs me-1"></i> Save Method
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    function toggleFields() {
        $('.method-type-select').each(function () {
            const type = $(this).val();
            const body = $(this).closest('.modal-body');
            body.find('.bank-fields').toggleClass('d-none', type !== 'bank');
            body.find('.mobile-fields').toggleClass('d-none', type !== 'mobile_banking');
        });
    }

    $(document).on('change', '.method-type-select', toggleFields);
    toggleFields();
});
</script>
@endpush

@endsection
