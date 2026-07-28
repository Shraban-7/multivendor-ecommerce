@extends('admin.layouts.app')
@section('title', 'Payout #'.$payout->id)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.payouts.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
    </a>
    <h3 class="fw-bold mb-0">Payout #{{ $payout->id }}</h3>
    <span class="badge {{ $payout->statusBadge() }} ms-2">{{ $payout->statusLabel() }}</span>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-semibold mb-0">Payout Details</h5>
                <div class="d-flex gap-2">
                    @if ($payout->isPending())
                        <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this payout?')">
                                <i data-feather="check" class="icon-xs me-1"></i> Approve
                            </button>
                        </form>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i data-feather="x" class="icon-xs me-1"></i> Cancel
                        </button>
                    @elseif ($payout->isProcessing())
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#completeModal">
                            <i data-feather="check-circle" class="icon-xs me-1"></i> Mark Complete
                        </button>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#failModal">
                            <i data-feather="alert-circle" class="icon-xs me-1"></i> Mark Failed
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i data-feather="x" class="icon-xs me-1"></i> Cancel
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Requested Amount</label>
                        <p class="fw-bold fs-5 mb-0">{{ money($payout->amount) }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Processing Fee</label>
                        <p class="fw-semibold mb-0">{{ money($payout->charge) }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Net Amount</label>
                        <p class="fw-bold fs-5 mb-0" style="color: var(--bs-success);">{{ money($payout->net_amount) }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Currency</label>
                        <p class="fw-semibold mb-0">{{ $payout->currency }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Requested</label>
                        <p class="fw-semibold mb-0">{{ $payout->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small mb-1">Status</label>
                        <p class="mb-0"><span class="badge {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span></p>
                    </div>

                    @if ($payout->processed_at)
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Processed At</label>
                            <p class="fw-semibold mb-0">{{ $payout->processed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif

                    @if ($payout->completed_at)
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Completed At</label>
                            <p class="fw-semibold mb-0">{{ $payout->completed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif

                    @if ($payout->processedBy)
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Processed By</label>
                            <p class="fw-semibold mb-0">{{ $payout->processedBy->name }}</p>
                        </div>
                    @endif

                    @if ($payout->transaction_id)
                        <div class="col-12">
                            <label class="text-muted small mb-1">Transaction ID</label>
                            <p class="fw-semibold mb-0">{{ $payout->transaction_id }}</p>
                        </div>
                    @endif

                    @if ($payout->seller_note)
                        <div class="col-12">
                            <label class="text-muted small mb-1">Seller Note</label>
                            <div class="p-3 rounded" style="background: var(--bs-surface-muted);">
                                <p class="mb-0">{{ $payout->seller_note }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($payout->admin_note)
                        <div class="col-12">
                            <label class="text-muted small mb-1">Admin Note</label>
                            <div class="p-3 rounded" style="background: var(--bs-surface-muted);">
                                <p class="mb-0">{{ $payout->admin_note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom">
                <h6 class="fw-semibold mb-0">Seller Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $payout->seller->businessAvatar }}" height="48" width="48" class="rounded me-3 border" style="object-fit:scale-down;">
                    <div>
                        <h6 class="fw-semibold mb-0">{{ $payout->seller->business_name ?? $payout->seller->name }}</h6>
                        <small class="text-muted">{{ $payout->seller->email }}</small>
                    </div>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Seller ID:</span>
                        <span class="fw-medium">{{ $payout->seller->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Code:</span>
                        <span class="fw-medium">{{ $payout->seller->code }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Balance:</span>
                        <span class="fw-medium">{{ money($payout->seller->balance) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Phone:</span>
                        <span class="fw-medium">{{ $payout->seller->phone }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.sellers.profile', $payout->seller->username) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                    <i data-feather="external-link" class="icon-xs me-1"></i> View Seller Profile
                </a>
            </div>
        </div>

        @if ($payout->payoutMethod)
            <div class="card border-0 shadow-sm mt-3" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-semibold mb-0">Payout Method</h6>
                </div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Type:</span>
                        <span class="fw-medium">{{ $payout->payoutMethod->methodLabel() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Account:</span>
                        <span class="fw-medium">{{ $payout->payoutMethod->account_name }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Number:</span>
                        <span class="fw-medium">{{ $payout->payoutMethod->maskedAccountNumber() }}</span>
                    </div>
                    @if ($payout->payoutMethod->bank_name)
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Bank:</span>
                            <span class="fw-medium">{{ $payout->payoutMethod->bank_name }}</span>
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
                        <label class="form-label">Reason (Optional)</label>
                        <textarea name="admin_note" class="form-control" rows="3" placeholder="Reason for cancellation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <label class="form-label">Transaction ID (Optional)</label>
                        <input type="text" name="transaction_id" class="form-control" placeholder="e.g., TXN123456">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <label class="form-label">Reason (Optional)</label>
                        <textarea name="admin_note" class="form-control" rows="3" placeholder="Why did it fail?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Mark Failed</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
