@extends('seller.layouts.app')
@section('title', 'Request Payout')

@section('content')
<div class="w-full px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.payouts.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="font-bold mb-0 text-ink">Request Payout</h4>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5 class="font-semibold text-ink mb-0">New Withdrawal Request</h5>
                </div>
                <form method="POST" action="{{ route('seller.payouts.store') }}">
                    @csrf
                    <div class="p-5">
                        <div class="mb-4 p-3 rounded-xs" style="background: var(--bs-light-primary);">
                            <div class="flex justify-between items-center">
                                <span class="text-ink-tertiary">Available Balance:</span>
                                <span class="font-bold text-xl" style="color: var(--bs-primary);">{{ money($availableBalance) }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Withdrawal Amount <span class="text-feedback-danger">*</span></label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border">৳</span>
                                <input type="number" step="0.01" min="1" max="{{ $availableBalance }}"
                                    name="amount" id="amount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('amount') is-invalid @enderror"
                                    placeholder="Enter amount" value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback block">{{ $message }}</div>
                            @enderror
                            <small class="text-ink-tertiary">Minimum withdrawal: ৳1. Maximum: {{ money($availableBalance) }}</small>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Payout Method <span class="text-feedback-danger">*</span></label>
                            @if ($methods->count() > 0)
                                <select name="payout_method_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors @error('payout_method_id') is-invalid @enderror" required>
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
                                    <div class="invalid-feedback block">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="flex items-center gap-2 p-4 rounded-xs bg-feedback-warning/10 border border-feedback-warning text-feedback-warning text-sm mb-0">
                                    No payout methods found.
                                    <a href="{{ route('seller.payouts.methods.index') }}" class="font-medium underline">Add a payout method</a> first.
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Note (Optional)</label>
                            <textarea name="seller_note" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('seller_note') is-invalid @enderror"
                                rows="3" placeholder="Any additional information...">{{ old('seller_note') }}</textarea>
                            @error('seller_note')
                                <div class="invalid-feedback block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="p-3 rounded-xs mb-3 border" style="background: var(--bs-surface-muted);">
                            <h6 class="font-semibold mb-2">Fee Breakdown</h6>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-ink-tertiary">Requested Amount</span>
                                <span class="font-medium" id="previewAmount">৳0.00</span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-ink-tertiary">Processing Fee</span>
                                <span class="font-medium" id="previewFee">৳0.00</span>
                            </div>
                            <hr class="my-1">
                            <div class="flex justify-between">
                                <span class="font-semibold">You Will Receive</span>
                                <span class="font-bold" style="color: var(--bs-success);" id="previewNet">৳0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-border bg-white text-right">
                        <a href="{{ route('seller.payouts.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" {{ $methods->isEmpty() ? 'disabled' : '' }}>
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h6 class="font-semibold text-ink mb-0">Payout Rules</h6>
                </div>
                <div class="p-5">
                    <ul class="list-none mb-0 text-sm">
                        <li class="mb-2 flex gap-2">
                            <i data-lucide="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
                            <span>Minimum withdrawal amount is ৳1.00</span>
                        </li>
                        <li class="mb-2 flex gap-2">
                            <i data-lucide="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
                            <span>Processing fee: 1% for amounts up to ৳49,999, 0.5% for ৳50,000+</span>
                        </li>
                        <li class="mb-2 flex gap-2">
                            <i data-lucide="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
                            <span>Payouts are processed within 3-5 business days</span>
                        </li>
                        <li class="mb-2 flex gap-2">
                            <i data-lucide="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
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