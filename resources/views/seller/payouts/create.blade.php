@extends('seller.layouts.app')
@section('title', 'Request Payout')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('seller.payouts.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="fw-bold mb-0 text-dark">Request Payout</h4>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold text-dark mb-0">New Withdrawal Request</h5>
                </div>
                <form method="POST" action="{{ route('seller.payouts.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="mb-4 p-3 rounded" style="background: var(--bs-light-primary);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Available Balance:</span>
                                <span class="fw-bold fs-5" style="color: var(--bs-primary);">{{ money($availableBalance) }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Withdrawal Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" min="1" max="{{ $availableBalance }}"
                                    name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="Enter amount" value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum withdrawal: ৳1. Maximum: {{ money($availableBalance) }}</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Payout Method <span class="text-danger">*</span></label>
                            @if ($methods->count() > 0)
                                <select name="payout_method_id" class="form-select @error('payout_method_id') is-invalid @enderror" required>
                                    <option value="">Select a payout method</option>
                                    @foreach ($methods as $method)
                                        <option value="{{ $method->id }}" {{ old('payout_method_id') == $method->id ? 'selected' : '' }}
                                            {{ $method->is_default ? 'selected' : '' }}>
                                            {{ $method->methodLabel() }} - {{ $method->maskedAccountNumber() }}
                                            {{ $method->is_default ? '(Default)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payout_method_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="alert alert-warning mb-0">
                                    No payout methods found.
                                    <a href="{{ route('seller.payouts.methods.index') }}" class="alert-link">Add a payout method</a> first.
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note (Optional)</label>
                            <textarea name="seller_note" class="form-control @error('seller_note') is-invalid @enderror"
                                rows="3" placeholder="Any additional information...">{{ old('seller_note') }}</textarea>
                            @error('seller_note')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="p-3 rounded mb-3 border" style="background: var(--bs-surface-muted);">
                            <h6 class="fw-semibold mb-2">Fee Breakdown</h6>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Requested Amount</span>
                                <span class="fw-medium" id="previewAmount">৳0.00</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Processing Fee</span>
                                <span class="fw-medium" id="previewFee">৳0.00</span>
                            </div>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">You Will Receive</span>
                                <span class="fw-bold" style="color: var(--bs-success);" id="previewNet">৳0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top text-end">
                        <a href="{{ route('seller.payouts.index') }}" class="btn btn-light border me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" {{ $methods->isEmpty() ? 'disabled' : '' }}>
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-semibold text-dark mb-0">Payout Rules</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2 d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Minimum withdrawal amount is ৳1.00</span>
                        </li>
                        <li class="mb-2 d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Processing fee: 1% for amounts up to ৳49,999, 0.5% for ৳50,000+</span>
                        </li>
                        <li class="mb-2 d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Payouts are processed within 3-5 business days</span>
                        </li>
                        <li class="mb-2 d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Funds will be sent to your selected payout method</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    const feeRateLow = 1;
    const feeRateHigh = 0.5;
    const feeThreshold = 50000;
    const fixedFee = 10;

    function calculateFee(amount) {
        amount = parseFloat(amount) || 0;
        if (amount >= feeThreshold) {
            return Math.max((amount * feeRateHigh) / 100, fixedFee);
        }
        return Math.max((amount * feeRateLow) / 100, fixedFee);
    }

    $('#amount').on('input', function() {
        let amount = parseFloat($(this).val()) || 0;
        let fee = calculateFee(amount);
        let net = Math.max(amount - fee, 0);

        $('#previewAmount').text('৳' + amount.toFixed(2));
        $('#previewFee').text('৳' + fee.toFixed(2));
        $('#previewNet').text('৳' + net.toFixed(2));
    });
});
</script>
@endpush
@endsection
