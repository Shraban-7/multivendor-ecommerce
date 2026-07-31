@extends('seller.layouts.app')
@section('title', 'Request Payout')

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
                    <span class="text-ink-soft font-semibold">Request Payout</span>
                </nav>
                <h1 class="text-xl font-bold text-ink-emphasis mb-1">Request a Withdrawal</h1>
                <p class="text-sm text-ink-secondary mb-0">Withdraw funds from your available balance to your registered payout method.</p>
            </div>
            <div>
                <a href="{{ route('seller.payouts.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ AVAILABLE BALANCE BANNER ═══ --}}
<section class="bg-gradient-to-r from-orange-500 to-orange-400 rounded-sm shadow-sm overflow-hidden mb-4 text-white relative">
    <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="shrink-0 w-12 h-12 rounded-sm bg-white/20 flex items-center justify-center">
                <i data-lucide="wallet" style="width:24px;height:24px;"></i>
            </span>
            <div>
                <p class="text-xs uppercase tracking-wider opacity-75 mb-0 font-semibold text-white/90">Available Balance</p>
                <h3 class="font-bold text-2xl mb-0 text-white">{{ money($availableBalance ?? 0) }}</h3>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs uppercase tracking-wider opacity-75 mb-0 font-semibold text-white/90">Withdraw Up To</p>
            <h3 class="font-bold text-2xl mb-0 text-white">{{ money($availableBalance ?? 0) }}</h3>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

    {{-- ═══ FORM ═══ --}}
    <div class="lg:col-span-2">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="send" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">New Withdrawal Request</h5>
            </div>
            <form method="POST" action="{{ route('seller.payouts.store') }}">
                @csrf
                <div class="p-5 space-y-4">

                    {{-- Amount --}}
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">
                            Withdrawal Amount <span class="text-feedback-danger">*</span>
                        </label>
                        <div class="flex bg-surface-muted rounded-xs overflow-hidden focus-within:ring-1 focus-within:ring-brand-deep">
                            <span class="inline-flex items-center px-3 text-ink-tertiary font-semibold text-sm">{{ currency('symbol') }}</span>
                            <input type="number" step="0.01" min="1" max="{{ $availableBalance ?? 0 }}"
                                   name="amount" id="amount"
                                   value="{{ old('amount') }}"
                                   placeholder="Enter amount"
                                   class="w-full px-3 py-2 text-sm text-ink-emphasis bg-transparent focus:outline-none placeholder:text-ink-tertiary transition-colors @error('amount') is-invalid @enderror"
                                   required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback block mt-1 text-xs text-feedback-danger">{{ $message }}</div>
                        @enderror
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <small class="text-ink-tertiary">Minimum {{ money($feeConfig['minimum_withdrawal'] ?? 1) }} — Maximum <span class="font-semibold text-ink-soft">{{ money($availableBalance ?? 0) }}</span></small>
                            <div class="flex gap-1 ml-auto">
                                @foreach ([25, 50, 75, 100] as $pct)
                                    <button type="button" class="preset-amt px-2 py-0.5 text-[11px] font-semibold rounded-full bg-surface-muted text-ink-soft hover:bg-brand-tint hover:text-brand-deep transition-colors"
                                            data-percent="{{ $pct }}">{{ $pct }}%</button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Method --}}
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">
                            Payout Method <span class="text-feedback-danger">*</span>
                        </label>
                        @if ($methods && $methods->count() > 0)
                            <select name="payout_method_id"
                                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors @error('payout_method_id') is-invalid @enderror"
                                    required>
                                <option value="">Select a payout method…</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method->id }}" {{ old('payout_method_id', optional($methods->where('is_default', true))->first()->id) == $method->id ? 'selected' : '' }}>
                                        {{ $method->methodLabel() }} — {{ $method->maskedAccountNumber() }}
                                        @if ($method->is_default) (Default) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('payout_method_id')
                                <div class="invalid-feedback block mt-1 text-xs text-feedback-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-ink-tertiary mt-1 block">
                                Need a different method?
                                <a href="{{ route('seller.payouts.methods.index') }}" class="text-brand hover:text-brand-deep font-semibold">Manage payment methods</a>
                            </small>
                        @else
                            <div class="flex items-start gap-2 p-4 rounded-xs bg-amber-50 text-ink-soft">
                                <i data-lucide="alert-triangle" class="text-feedback-warning shrink-0 mt-0.5" style="width:16px;height:16px;"></i>
                                <div class="text-sm">
                                    <p class="mb-1 font-semibold text-ink-emphasis"><strong>No payout methods found.</strong></p>
                                    <p class="mb-0 text-ink-secondary">Add a payout method first, then come back to withdraw.</p>
                                </div>
                                <a href="{{ route('seller.payouts.methods.index') }}" class="btn btn-warning btn-sm ms-auto shrink-0">
                                    <i data-lucide="plus" class="icon-xs me-1"></i> Add Method
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Note --}}
                    <div>
                        <label class="block text-xs font-semibold text-ink-secondary mb-1 uppercase tracking-wider">Note <span class="text-ink-tertiary font-normal normal-case">(Optional)</span></label>
                        <textarea name="seller_note" rows="3"
                                  placeholder="Any additional information for the admin team…"
                                  class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('seller_note') is-invalid @enderror">{{ old('seller_note') }}</textarea>
                        @error('seller_note')
                            <div class="invalid-feedback block mt-1 text-xs text-feedback-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Fee breakdown (live) --}}
                    <div class="rounded-xs bg-surface-muted overflow-hidden">
                        <div class="px-4 py-2 bg-brand-tint flex items-center gap-2">
                            <i data-lucide="calculator" class="text-brand-deep" style="width:14px;height:14px;"></i>
                            <span class="text-xs font-bold text-brand-deep uppercase tracking-wider">Fee Breakdown</span>
                            <span class="text-[11px] text-ink-tertiary ms-auto">Auto-calculated</span>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-secondary">Requested amount</span>
                                <span class="font-semibold text-ink-emphasis" id="previewAmount">{{ currency('symbol') }}0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-secondary">Processing fee</span>
                                <span class="font-semibold text-feedback-danger" id="previewFee">− {{ currency('symbol') }}0.00</span>
                            </div>
                            <div class="border-t border-border pt-2 mt-2 flex justify-between items-center">
                                <span class="font-bold text-ink-emphasis">You will receive</span>
                                <span class="font-bold text-2xl text-feedback-success" id="previewNet">{{ currency('symbol') }}0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-3 bg-surface-muted flex flex-wrap items-center justify-end gap-2">
                    <a href="{{ route('seller.payouts.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary" {{ !$methods || $methods->isEmpty() ? 'disabled' : '' }}>
                        <i data-lucide="send" class="icon-xs me-1"></i> Submit Request
                    </button>
                </div>
            </form>
        </section>
    </div>

    {{-- ═══ RULES ═══ --}}
    <div class="lg:col-span-1">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="info" class="text-feedback-info" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Payout Rules</h5>
            </div>
            <div class="p-5">
                <ul class="space-y-3 mb-0">
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-brand-tint text-brand-deep flex items-center justify-center">
                            <i data-lucide="circle-dollar-sign" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Minimum Withdrawal</p>
                            <small class="text-ink-tertiary">{{ money($feeConfig['minimum_withdrawal'] ?? 1) }} per request</small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center">
                            <i data-lucide="percent" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Processing Fee</p>
                            <small class="text-ink-tertiary">
                                @php
                                    $tiers = collect($feeConfig['tiers'] ?? [])->sortByDesc('min')->values();
                                    $fixedFee = (float) ($feeConfig['fixed_fee'] ?? 0);
                                    $lowestMin = $tiers->last()['min'] ?? null;
                                @endphp
                                @if ($tiers->isEmpty())
                                    No fee applies
                                @else
                                    @foreach ($tiers as $i => $tier)
                                        @if ($i > 0) · @endif
                                        {{ $tier['rate'] }}% for {{ $i === 0 ? '≥ ' . number_format($tier['min']) : number_format($tier['min']) . '–' . number_format(($tiers[$i - 1]['min'] ?? 0) - 1) }}
                                    @endforeach
                                    @if ($fixedFee > 0 && $lowestMin !== null)
                                        · {{ money($fixedFee) }} below {{ number_format($lowestMin) }}
                                    @endif
                                @endif
                            </small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                            <i data-lucide="clock" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Processing Time</p>
                            <small class="text-ink-tertiary">3–5 business days after approval</small>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-7 h-7 rounded-sm bg-emerald-50 text-feedback-success flex items-center justify-center">
                            <i data-lucide="shield-check" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis text-sm">Safe Transfer</p>
                            <small class="text-ink-tertiary">Funds are sent to your selected payout method</small>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 p-3 rounded-xs bg-emerald-50">
                    <p class="mb-0 flex items-start gap-2 text-xs text-ink-soft">
                        <i data-lucide="shield-check" class="text-feedback-success shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
                        <span>Once submitted, your request will be reviewed by our team. We'll notify you when it's approved or rejected.</span>
                    </p>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    const feeConfig = @json($feeConfig ?? []);
    const fixedFee  = Number(feeConfig.fixed_fee || 0);
    const tiers     = (feeConfig.tiers || []).slice().sort((a, b) => b.min - a.min);
    const maxAmount = {{ (float) ($availableBalance ?? 0) }};
    const currency  = @json(currency('symbol'));

    function fmt(n) {
        const v = (Number(n) || 0).toFixed(2);
        return (currency ? currency + ' ' : '') + v;
    }

    function calculateFee(amount) {
        amount = Number(amount) || 0;
        let chargePercent = 0;
        for (const tier of tiers) {
            if (amount >= Number(tier.min)) {
                chargePercent = Number(tier.rate);
                break;
            }
        }
        if (chargePercent === 0) {
            return Math.max(0, fixedFee);
        }
        return Math.max((amount * chargePercent) / 100, fixedFee);
    }

    function recalc() {
        const amount = Number($('#amount').val()) || 0;
        const fee    = calculateFee(amount);
        const net    = Math.max(amount - fee, 0);
        $('#previewAmount').text(fmt(amount));
        $('#previewFee').text('− ' + fmt(fee));
        $('#previewNet').text(fmt(net));
    }

    $('#amount').on('input', recalc);
    recalc();

    $('.preset-amt').on('click', function () {
        const pct = Number($(this).data('percent')) || 0;
        const raw = (maxAmount * pct) / 100;
        const value = pct === 100 ? maxAmount : Math.floor(raw * 100) / 100;
        $('#amount').val(value).trigger('input');
    });
});
</script>
@endpush

@endsection
