@php
    $statusMap = [
        0 => ['label' => 'Pending',    'pill' => 'bg-amber-500 text-white',                 'icon' => 'hourglass',     'tone' => 'warning'],
        1 => ['label' => 'Processing', 'pill' => 'bg-blue-500 text-white',                  'icon' => 'loader',        'tone' => 'info'],
        2 => ['label' => 'Completed',  'pill' => 'bg-emerald-500 text-white',               'icon' => 'check-circle',  'tone' => 'success'],
        3 => ['label' => 'Cancelled',  'pill' => 'bg-rose-500 text-white',                  'icon' => 'x-circle',      'tone' => 'danger'],
        4 => ['label' => 'Failed',     'pill' => 'bg-rose-600 text-white',                  'icon' => 'alert-circle',  'tone' => 'danger'],
    ];
    $meta = $statusMap[$payout->status] ?? $statusMap[0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Payout #'.$payout->id)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="banknote" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <a href="{{ route('admin.payouts.index') }}" class="hover:text-ink transition-colors">Seller Payouts</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink font-semibold">Payout #{{ $payout->id }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink mb-0">Payout #{{ $payout->id }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider text-white {{ $meta['pill'] }}">
                        <i data-lucide="{{ $meta['icon'] }}" style="width:11px;height:11px;" class="me-1"></i>
                        {{ $meta['label'] }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">
                    Requested by <strong class="text-ink">{{ $payout->seller->business_name ?? $payout->seller->name }}</strong>
                    on {{ $payout->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.payouts.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back to list
                </a>
                @if ($payout->isPending())
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i data-lucide="x" class="icon-xs"></i> Cancel
                    </button>
                    <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this payout and queue it for processing?')">
                            <i data-lucide="check" class="icon-xs"></i> Approve
                        </button>
                    </form>
                @elseif ($payout->isProcessing())
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i data-lucide="x" class="icon-xs"></i> Cancel
                    </button>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#failModal">
                        <i data-lucide="alert-circle" class="icon-xs"></i> Mark Failed
                    </button>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i data-lucide="check-circle" class="icon-xs"></i> Mark Complete
                    </button>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ═══ AMOUNT SUMMARY ═══ --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
    <article class="bg-white border border-border rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-surface-muted"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Requested</p>
                <h3 class="mb-0 font-bold text-xl text-ink mt-1">{{ money($payout->amount) }}</h3>
                <small class="text-ink-tertiary">Gross amount requested</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-surface-muted flex items-center justify-center text-ink-tertiary">
                <i data-lucide="banknote" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white border border-border rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Processing Fee</p>
                <h3 class="mb-0 font-bold text-xl text-ink mt-1">−{{ money($payout->charge) }}</h3>
                <small class="text-ink-tertiary">Platform commission</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center">
                <i data-lucide="percent" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white border border-border rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Net to Seller</p>
                <h3 class="mb-0 font-bold text-2xl text-feedback-success mt-1">{{ money($payout->net_amount) }}</h3>
                <small class="text-ink-tertiary">{{ $payout->currency }} — settled amount</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center">
                <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

    {{-- ═══ PAYOUT DETAILS ═══ --}}
    <div class="lg:col-span-2">
        <section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-text" class="text-brand" style="width:16px;height:16px;"></i>
                    <h5 class="mb-0 font-bold text-ink text-sm">Payout Details</h5>
                </div>
                <small class="text-ink-tertiary">ID: <span class="font-mono">#{{ $payout->id }}</span></small>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Status</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white {{ $meta['pill'] }}">
                                <i data-lucide="{{ $meta['icon'] }}" style="width:11px;height:11px;" class="me-1"></i>
                                {{ $meta['label'] }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Currency</label>
                        <p class="font-semibold mb-0 text-ink mt-1">{{ $payout->currency }}</p>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Requested</label>
                        <p class="font-semibold mb-0 text-ink mt-1">{{ $payout->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @if ($payout->processed_at)
                        <div>
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Processed</label>
                            <p class="font-semibold mb-0 text-ink mt-1">{{ $payout->processed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                    @if ($payout->completed_at)
                        <div>
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Completed</label>
                            <p class="font-semibold mb-0 text-ink mt-1">{{ $payout->completed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                    @if ($payout->processedBy)
                        <div>
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Processed By</label>
                            <p class="font-semibold mb-0 text-ink mt-1">{{ $payout->processedBy->name }}</p>
                        </div>
                    @endif
                    @if ($payout->transaction_id)
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Transaction ID</label>
                            <p class="font-mono font-semibold mb-0 text-ink mt-1 p-2 bg-surface-muted rounded-xs break-all">{{ $payout->transaction_id }}</p>
                        </div>
                    @endif
                    @if ($payout->seller_note)
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Seller Note</label>
                            <div class="mt-1 p-3 rounded-xs bg-surface-muted border-l-4 border-brand">
                                <p class="mb-0 text-sm text-ink">{{ $payout->seller_note }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($payout->admin_note)
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Admin Note</label>
                            <div class="mt-1 p-3 rounded-xs bg-amber-50 border-l-4 border-amber-500">
                                <p class="mb-0 text-sm text-ink">{{ $payout->admin_note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    {{-- ═══ SIDE: SELLER + METHOD ═══ --}}
    <div class="lg:col-span-1 space-y-3">

        {{-- Seller card --}}
        <section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
                <i data-lucide="store" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink text-sm">Seller</h5>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $payout->seller->businessAvatar }}" alt="" height="48" width="48"
                         class="rounded-sm border border-border object-cover shrink-0" style="width:48px;height:48px;">
                    <div class="min-w-0">
                        <p class="mb-0 font-semibold text-ink truncate">{{ $payout->seller->business_name ?? $payout->seller->name }}</p>
                        <small class="text-ink-tertiary">{{ $payout->seller->email }}</small>
                    </div>
                </div>
                <dl class="text-sm space-y-1.5">
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Seller ID</dt>
                        <dd class="font-medium text-ink font-mono">#{{ $payout->seller->id }}</dd>
                    </div>
                    @if ($payout->seller->code)
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Code</dt>
                        <dd class="font-medium text-ink">{{ $payout->seller->code }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Balance</dt>
                        <dd class="font-semibold text-ink">{{ money($payout->seller->balance ?? 0) }}</dd>
                    </div>
                    @if ($payout->seller->phone)
                    <div class="flex justify-between">
                        <dt class="text-ink-tertiary">Phone</dt>
                        <dd class="font-medium text-ink">{{ $payout->seller->phone }}</dd>
                    </div>
                    @endif
                </dl>
                <a href="{{ route('admin.sellers.profile', $payout->seller->username) }}" class="btn btn-outline-primary btn-sm mt-3 w-full">
                    <i data-lucide="external-link" class="icon-xs me-1"></i> View Seller Profile
                </a>
            </div>
        </section>

        {{-- Method card --}}
        @if ($payout->payoutMethod)
            <section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
                    <i data-lucide="credit-card" class="text-feedback-info" style="width:16px;height:16px;"></i>
                    <h5 class="mb-0 font-bold text-ink text-sm">Payout Method</h5>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                            <i data-lucide="{{ $payout->payoutMethod->method_type === 'bank' ? 'building' : ($payout->payoutMethod->method_type === 'mobile_banking' ? 'smartphone' : 'dollar-sign') }}"
                               style="width:18px;height:18px;"></i>
                        </span>
                        <div>
                            <p class="mb-0 font-semibold text-ink">{{ $payout->payoutMethod->methodLabel() }}</p>
                            @if ($payout->payoutMethod->is_default)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-feedback-success">
                                    <i data-lucide="star" style="width:9px;height:9px;" class="me-0.5"></i> Default
                                </span>
                            @endif
                        </div>
                    </div>
                    <dl class="text-sm space-y-1.5">
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Account</dt>
                            <dd class="font-medium text-ink">{{ $payout->payoutMethod->account_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Number</dt>
                            <dd class="font-mono font-medium text-ink">{{ $payout->payoutMethod->maskedAccountNumber() }}</dd>
                        </div>
                        @if ($payout->payoutMethod->bank_name)
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Bank</dt>
                            <dd class="font-medium text-ink">{{ $payout->payoutMethod->bank_name }}</dd>
                        </div>
                        @endif
                        @if ($payout->payoutMethod->mobile_provider)
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Provider</dt>
                            <dd class="font-medium text-ink">{{ ucfirst($payout->payoutMethod->mobile_provider) }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </section>
        @endif
    </div>
</div>

{{-- ═══ MODALS ═══ --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('admin.payouts.cancel', $payout) }}">
                @csrf
                <div class="modal-header border-b border-border bg-surface-muted">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-rose-50 text-rose-500 flex items-center justify-center">
                            <i data-lucide="x-circle" style="width:18px;height:18px;"></i>
                        </span>
                        <h5 class="modal-title font-bold text-ink mb-0">Cancel Payout</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-sm text-ink-secondary">Are you sure you want to cancel this payout? The amount will be returned to the seller's balance.</p>
                    <div class="mt-3">
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Reason <span class="text-ink-tertiary font-normal normal-case">(Optional)</span></label>
                        <textarea name="admin_note" rows="3" placeholder="Reason for cancellation..."
                                  class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-t border-border bg-surface-muted">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">
                        <i data-lucide="x" class="icon-xs me-1"></i> Cancel Payout
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('admin.payouts.complete', $payout) }}">
                @csrf
                <div class="modal-header border-b border-border bg-surface-muted">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center">
                            <i data-lucide="check-circle" style="width:18px;height:18px;"></i>
                        </span>
                        <h5 class="modal-title font-bold text-ink mb-0">Complete Payout</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-sm text-ink-secondary">Confirm that the funds have been sent to the seller's payout method.</p>
                    <div class="mt-3">
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Transaction ID <span class="text-ink-tertiary font-normal normal-case">(Optional)</span></label>
                        <input type="text" name="transaction_id" placeholder="e.g. TXN123456"
                               class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                </div>
                <div class="modal-footer border-t border-border bg-surface-muted">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i data-lucide="check-circle" class="icon-xs me-1"></i> Mark Complete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="failModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('admin.payouts.fail', $payout) }}">
                @csrf
                <div class="modal-header border-b border-border bg-surface-muted">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-amber-50 text-feedback-warning flex items-center justify-center">
                            <i data-lucide="alert-triangle" style="width:18px;height:18px;"></i>
                        </span>
                        <h5 class="modal-title font-bold text-ink mb-0">Mark as Failed</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-sm text-ink-secondary">Mark this payout as failed. The amount will be returned to the seller's balance.</p>
                    <div class="mt-3">
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Reason <span class="text-ink-tertiary font-normal normal-case">(Optional)</span></label>
                        <textarea name="admin_note" rows="3" placeholder="Why did it fail?"
                                  class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-t border-border bg-surface-muted">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">
                        <i data-lucide="alert-circle" class="icon-xs me-1"></i> Mark Failed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
