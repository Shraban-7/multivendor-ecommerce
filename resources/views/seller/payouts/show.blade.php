@extends('seller.layouts.app')
@section('title', 'Payout #'.$payout->id)

@section('content')
<div class="w-full px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.payouts.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="font-bold mb-0 text-ink">Payout #{{ $payout->id }}</h4>
        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs {{ $payout->statusBadge() }} ms-2">{{ $payout->statusLabel() }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5 class="font-semibold text-ink mb-0">Payout Details</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Requested Amount</label>
                            <p class="font-semibold mb-0 text-xl">{{ money($payout->amount) }}</p>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Processing Fee</label>
                            <p class="font-semibold mb-0">{{ money($payout->charge) }}</p>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Net Amount</label>
                            <p class="font-semibold mb-0 text-xl" style="color: var(--bs-success);">{{ money($payout->net_amount) }}</p>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Currency</label>
                            <p class="font-semibold mb-0">{{ $payout->currency }}</p>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Requested Date</label>
                            <p class="font-semibold mb-0">{{ $payout->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-ink-tertiary text-sm mb-1">Status</label>
                            <p class="mb-0"><span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-xs {{ $payout->statusBadge() }}">{{ $payout->statusLabel() }}</span></p>
                        </div>

                        @if ($payout->processed_at)
                            <div class="md:col-span-1">
                                <label class="text-ink-tertiary text-sm mb-1">Processed Date</label>
                                <p class="font-semibold mb-0">{{ $payout->processed_at->format('d M Y, h:i A') }}</p>
                            </div>
                        @endif

                        @if ($payout->completed_at)
                            <div class="md:col-span-1">
                                <label class="text-ink-tertiary text-sm mb-1">Completed Date</label>
                                <p class="font-semibold mb-0">{{ $payout->completed_at->format('d M Y, h:i A') }}</p>
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
                                <label class="text-ink-tertiary text-sm mb-1">Your Note</label>
                                <p class="mb-0">{{ $payout->seller_note }}</p>
                            </div>
                        @endif

                        @if ($payout->admin_note)
                            <div class="col-span-full">
                                <label class="text-ink-tertiary text-sm mb-1">Admin Response</label>
                                <p class="mb-0">{{ $payout->admin_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h6 class="font-semibold text-ink mb-0">Payout Method</h6>
                </div>
                <div class="p-5">
                    @if ($payout->payoutMethod)
                        <div class="mb-2">
                            <label class="text-ink-tertiary text-sm mb-1">Method</label>
                            <p class="font-semibold mb-0">{{ $payout->payoutMethod->methodLabel() }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="text-ink-tertiary text-sm mb-1">Account Name</label>
                            <p class="font-semibold mb-0">{{ $payout->payoutMethod->account_name }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="text-ink-tertiary text-sm mb-1">Account Number</label>
                            <p class="font-semibold mb-0">{{ $payout->payoutMethod->maskedAccountNumber() }}</p>
                        </div>
                        @if ($payout->payoutMethod->bank_name)
                            <div class="mb-2">
                                <label class="text-ink-tertiary text-sm mb-1">Bank</label>
                                <p class="font-semibold mb-0">{{ $payout->payoutMethod->bank_name }}</p>
                            </div>
                        @endif
                    @else
                        <p class="text-ink-tertiary mb-0">Method deleted or unavailable.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mt-3" style="border-radius: 12px;">
                <div class="p-5 text-center py-4">
                    @if ($payout->isPending())
                        <div class="text-feedback-warning">
                            <i data-feather="clock" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 font-semibold">Waiting for approval</p>
                            <small class="text-ink-tertiary">Your request is being reviewed</small>
                        </div>
                    @elseif ($payout->isProcessing())
                        <div class="text-feedback-info">
                            <i data-feather="loader" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 font-semibold">Processing</p>
                            <small class="text-ink-tertiary">Payout is being processed</small>
                        </div>
                    @elseif ($payout->isCompleted())
                        <div class="text-feedback-success">
                            <i data-feather="check-circle" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 font-semibold">Completed</p>
                            <small class="text-ink-tertiary">Funds have been sent</small>
                        </div>
                    @else
                        <div class="text-feedback-danger">
                            <i data-feather="x-circle" style="width: 48px; height: 48px;" class="mb-2"></i>
                            <p class="mb-0 font-semibold">{{ $payout->statusLabel() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection