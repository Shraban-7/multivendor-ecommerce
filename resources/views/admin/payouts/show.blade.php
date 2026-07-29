@extends('admin.layouts.app')
@section('title', 'Payout #'.$payout->id)

@section('content')
<div class="flex items-center gap-2 mb-3">
    <a href="{{ route('admin.payouts.index') }}" class="btn btn-light btn-sm">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
    </a>
    <h3 class="font-bold mb-0">Payout #{{ $payout->id }}</h3>
    <span class="badge {{ $payout->statusBadge() }} ms-2">{{ $payout->statusLabel() }}</span>
</div>

<div class="grid grid-cols-1 gap-3">
    <div class="lg:col-span-2">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
            <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b flex justify-between items-center">
                <h5 class="font-semibold mb-0">Payout Details</h5>
                <div class="flex gap-2">
                    @if ($payout->isPending())
                        <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this payout?')">
                                <i data-feather="check" class="icon-xs me-1"></i> Approve
                            </button>
                        </form>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i data-feather="x" class="icon-xs me-1"></i> Cancel
                        </button>
                    @elseif ($payout->isProcessing())
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#completeModal">
                            <i data-feather="check-circle" class="icon-xs me-1"></i> Mark Complete
                        </button>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#failModal">
                            <i data-feather="alert-circle" class="icon-xs me-1"></i> Mark Failed
                        </button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i data-feather="x" class="icon-xs me-1"></i> Cancel
                        </button>
                    @endif
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 gap-3">
                    <div class="md:col-span-1">
                        <label class="text-ink-tertiary text-sm mb-1">Requested Amount</label>
                        <p class="font-bold text-base mb-0">{{ money($payout->amount) }}</p>
                    </div>
                    <div class="md:col-span-1">
                        <label class="text-ink-tertiary text-sm mb-1">Processing Fee</label>
                        <p class="font-semibold mb-0">{{ money($payout->charge) }}</p>
                    </div>
                    <div class="md:col-span-1">
                        <label class="text-ink-tertiary text-sm mb-1">Net Amount</label>
                        <p class="font-bold text-base mb-0" style="color: var(--bs-success);">{{ money($payout->net_amount) }}</p>
                    </div>
                    <div class="md:col-span-1">
                        <label class="text-ink-tertiary text-sm mb-1">Currency</label>
                        <p class="font-semibold mb-0">{{ $payout->currency }}</p>
                    </div>
                    <div class="md:col-span-1">
                        <label class="text-ink-tertiary text-sm mb-1">Requested</label>
                        <p class="font-semibold mb-0">{{ $payout->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="md:col-span-1">
                        <label class="text-ink-tertiary text-sm mb-1">Status</label>
                        <p class="mb-0"><span class="badge {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span></p>
                    </div>

                    @if ($payout->processed_at)
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Processed At</label>
                            <p class="font-semibold mb-0">{{ $payout->processed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif

                    @if ($payout->completed_at)
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Completed At</label>
                            <p class="font-semibold mb-0">{{ $payout->completed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif

                    @if ($payout->processedBy)
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Processed By</label>
                            <p class="font-semibold mb-0">{{ $payout->processedBy->name }}</p>
                        </div>
                    @endif

                    @if ($payout->transaction_id)
                        <div class="col-span-full">
                            <label class="text-ink-tertiary text-sm mb-1">Transaction ID</label>
                            <p class="font-semibold mb-0">{{ $payout->transaction_id }}</p>
                        </div>
                    @endif

                    @if ($payout->seller_note)
                        <div class="col-span-full">
                            <label class="text-ink-tertiary text-sm mb-1">Seller Note</label>
                            <div class="p-3 rounded" style="background: var(--bs-surface-muted);">
                                <p class="mb-0">{{ $payout->seller_note }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($payout->admin_note)
                        <div class="col-span-full">
                            <label class="text-ink-tertiary text-sm mb-1">Admin Note</label>
                            <div class="p-3 rounded" style="background: var(--bs-surface-muted);">
                                <p class="mb-0">{{ $payout->admin_note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
            <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b">
                <h6 class="font-semibold mb-0">Seller Information</h6>
            </div>
            <div class="p-5">
                <div class="flex items-center mb-3">
                    <img src="{{ $payout->seller->businessAvatar }}" height="48" width="48" class="rounded me-3 border" style="object-fit:scale-down;">
                    <div>
                        <h6 class="font-semibold mb-0">{{ $payout->seller->business_name ?? $payout->seller->name }}</h6>
                        <small class="text-ink-tertiary">{{ $payout->seller->email }}</small>
                    </div>
                </div>
                <div class="small">
                    <div class="flex justify-between mb-1">
                        <span class="text-ink-tertiary">Seller ID:</span>
                        <span class="font-medium">{{ $payout->seller->id }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-ink-tertiary">Code:</span>
                        <span class="font-medium">{{ $payout->seller->code }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-ink-tertiary">Balance:</span>
                        <span class="font-medium">{{ money($payout->seller->balance) }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-ink-tertiary">Phone:</span>
                        <span class="font-medium">{{ $payout->seller->phone }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.sellers.profile', $payout->seller->username) }}" class="btn btn-outline-primary btn-sm mt-3">
                    <i data-feather="external-link" class="icon-xs me-1"></i> View Seller Profile
                </a>
            </div>
        </div>

        @if ($payout->payoutMethod)
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mt-3" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b">
                    <h6 class="font-semibold mb-0">Payout Method</h6>
                </div>
                <div class="p-5 text-sm">
                    <div class="flex justify-between mb-1">
                        <span class="text-ink-tertiary">Type:</span>
                        <span class="font-medium">{{ $payout->payoutMethod->methodLabel() }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-ink-tertiary">Account:</span>
                        <span class="font-medium">{{ $payout->payoutMethod->account_name }}</span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-ink-tertiary">Number:</span>
                        <span class="font-medium">{{ $payout->payoutMethod->maskedAccountNumber() }}</span>
                    </div>
                    @if ($payout->payoutMethod->bank_name)
                        <div class="flex justify-between mb-1">
                            <span class="text-ink-tertiary">Bank:</span>
                            <span class="font-medium">{{ $payout->payoutMethod->bank_name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.payouts.cancel', $payout) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this payout? The amount will be returned to the seller's balance.</p>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Reason (Optional)</label>
                        <textarea name="admin_note" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="Reason for cancellation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Cancel Payout</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Complete Modal --}}
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.payouts.complete', $payout) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Complete Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Confirm that this payout has been sent to the seller.</p>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Transaction ID (Optional)</label>
                        <input type="text" name="transaction_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g., TXN123456">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Mark Complete</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Fail Modal --}}
<div class="modal fade" id="failModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.payouts.fail', $payout) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Mark as Failed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Mark this payout as failed. The amount will be returned to the seller's balance.</p>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Reason (Optional)</label>
                        <textarea name="admin_note" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="Why did it fail?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Mark Failed</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
