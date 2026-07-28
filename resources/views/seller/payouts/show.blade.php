@extends('seller.layouts.app')
@section('title', 'Payout #'.$payout->id)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('seller.payouts.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="fw-bold mb-0 text-dark">Payout #{{ $payout->id }}</h4>
        <span class="badge {{ $payout->statusBadge() }} ms-2">{{ $payout->statusLabel() }}</span>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold text-dark mb-0">Payout Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Requested Amount</label>
                            <p class="fw-semibold mb-0 fs-5">{{ money($payout->amount) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Processing Fee</label>
                            <p class="fw-semibold mb-0">{{ money($payout->charge) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Net Amount</label>
                            <p class="fw-semibold mb-0 fs-5" style="color: var(--bs-success);">{{ money($payout->net_amount) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Currency</label>
                            <p class="fw-semibold mb-0">{{ $payout->currency }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Requested Date</label>
                            <p class="fw-semibold mb-0">{{ $payout->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Status</label>
                            <p class="mb-0"><span class="badge {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span></p>
                        </div>

                        @if ($payout->processed_at)
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Processed Date</label>
                                <p class="fw-semibold mb-0">{{ $payout->processed_at->format('d M Y, h:i A') }}</p>
                            </div>
                        @endif

                        @if ($payout->completed_at)
                            <div class="col-md-6">
                                <label class="text-muted small mb-1">Completed Date</label>
                                <p class="fw-semibold mb-0">{{ $payout->completed_at->format('d M Y, h:i A') }}</p>
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
                                <label class="text-muted small mb-1">Your Note</label>
                                <p class="mb-0">{{ $payout->seller_note }}</p>
                            </div>
                        @endif

                        @if ($payout->admin_note)
                            <div class="col-12">
                                <label class="text-muted small mb-1">Admin Response</label>
                                <p class="mb-0">{{ $payout->admin_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-semibold text-dark mb-0">Payout Method</h6>
                </div>
                <div class="card-body">
                    @if ($payout->payoutMethod)
                        <div class="mb-2">
                            <label class="text-muted small mb-1">Method</label>
                            <p class="fw-semibold mb-0">{{ $payout->payoutMethod->methodLabel() }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="text-muted small mb-1">Account Name</label>
                            <p class="fw-semibold mb-0">{{ $payout->payoutMethod->account_name }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="text-muted small mb-1">Account Number</label>
                            <p class="fw-semibold mb-0">{{ $payout->payoutMethod->maskedAccountNumber() }}</p>
                        </div>
                        @if ($payout->payoutMethod->bank_name)
                            <div class="mb-2">
                                <label class="text-muted small mb-1">Bank</label>
                                <p class="fw-semibold mb-0">{{ $payout->payoutMethod->bank_name }}</p>
                            </div>
                        @endif
                    @else
                        <p class="text-muted mb-0">Method deleted or unavailable.</p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3" style="border-radius: 12px;">
                <div class="card-body text-center py-4">
                    @if ($payout->isPending())
                        <div class="text-warning">
                            <i data-feather="clock" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 fw-semibold">Waiting for approval</p>
                            <small class="text-muted">Your request is being reviewed</small>
                        </div>
                    @elseif ($payout->isProcessing())
                        <div class="text-info">
                            <i data-feather="loader" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 fw-semibold">Processing</p>
                            <small class="text-muted">Payout is being processed</small>
                        </div>
                    @elseif ($payout->isCompleted())
                        <div class="text-success">
                            <i data-feather="check-circle" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 fw-semibold">Completed</p>
                            <small class="text-muted">Funds have been sent</small>
                        </div>
                    @else
                        <div class="text-danger">
                            <i data-feather="x-circle" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 fw-semibold">{{ $payout->statusLabel() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
