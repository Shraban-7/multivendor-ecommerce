@php
    $statusMap = \App\Domain\Vendor\Models\SellerPayout::statusMetas();
    $meta = \App\Domain\Vendor\Models\SellerPayout::statusMeta((int) $payout->status);

    $toneBanner = [
        'warning' => 'bg-warning-tint text-feedback-warning',
        'info'    => 'bg-info-tint text-feedback-info',
        'success' => 'bg-emerald-50 text-feedback-success',
        'danger'  => 'bg-rose-50 text-rose-600',
        'brand'   => 'bg-brand-tint text-brand-deep',
        'muted'   => 'bg-surface-muted text-ink-secondary',
    ];
@endphp
@extends('seller.layouts.app')
@section('title', 'Payout #'.$payout->id)

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
                    <span class="text-ink-soft font-semibold">Payout #{{ $payout->id }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Payout #{{ $payout->id }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider text-white {{ $meta['pill'] }}">
                        <i data-lucide="{{ $meta['icon'] }}" style="width:11px;height:11px;" class="me-1"></i>
                        {{ $meta['label'] }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">
                    Submitted on {{ $payout->created_at->format('d M Y, h:i A') }} · {{ $payout->currency }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.payouts.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back to list
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ AMOUNT SUMMARY ═══ --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-surface-muted"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Requested</p>
                <h3 class="mb-0 font-bold text-xl text-ink-emphasis mt-1">{{ money($payout->amount) }}</h3>
                <small class="text-ink-tertiary">Gross amount</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-surface-muted flex items-center justify-center text-ink-tertiary">
                <i data-lucide="banknote" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">Processing Fee</p>
                <h3 class="mb-0 font-bold text-xl text-brand-deep mt-1">−{{ money($payout->charge) }}</h3>
                <small class="text-ink-tertiary">Platform commission</small>
            </div>
            <span class="shrink-0 w-10 h-10 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center">
                <i data-lucide="percent" style="width:20px;height:20px;"></i>
            </span>
        </div>
    </article>
    <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
        <div class="flex items-start justify-between gap-3 mt-1">
            <div class="min-w-0">
                <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">You Will Receive</p>
                <h3 class="mb-0 font-bold text-2xl text-feedback-success mt-1">{{ money($payout->net_amount) }}</h3>
                <small class="text-ink-tertiary">{{ $payout->currency }} — net settlement</small>
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
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="file-text" class="text-brand" style="width:16px;height:16px;"></i>
                    <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Payout Details</h5>
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
                        <p class="font-semibold mb-0 text-ink-emphasis mt-1">{{ $payout->currency }}</p>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Requested Date</label>
                        <p class="font-semibold mb-0 text-ink-emphasis mt-1">{{ $payout->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    @if ($payout->processed_at)
                        <div>
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Processed Date</label>
                            <p class="font-semibold mb-0 text-ink-emphasis mt-1">{{ $payout->processed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                    @if ($payout->completed_at)
                        <div>
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Completed Date</label>
                            <p class="font-semibold mb-0 text-ink-emphasis mt-1">{{ $payout->completed_at->format('d M Y, h:i A') }}</p>
                        </div>
                    @endif
                    @if ($payout->transaction_id)
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Transaction ID</label>
                            <p class="font-mono font-semibold mb-0 text-ink-emphasis mt-1 p-2 bg-surface-muted rounded-xs break-all">{{ $payout->transaction_id }}</p>
                        </div>
                    @endif
                    @if ($payout->seller_note)
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Your Note</label>
                            <div class="mt-1 p-3 rounded-xs bg-surface-muted">
                                <p class="mb-0 text-sm text-ink-soft">{{ $payout->seller_note }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($payout->admin_note)
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Admin Response</label>
                            <div class="mt-1 p-3 rounded-xs bg-amber-50">
                                <p class="mb-0 text-sm text-ink-soft">{{ $payout->admin_note }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    {{-- ═══ SIDE: METHOD + STATUS BANNER ═══ --}}
    <div class="lg:col-span-1 space-y-3">

        {{-- Status banner --}}
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="h-1 {{ $meta['pill'] }}"></div>
            <div class="p-5 text-center">
                <span class="inline-flex items-center justify-center w-14 h-14 rounded-full {{ $toneBanner[$meta['tone']] ?? 'bg-surface-muted text-ink-secondary' }} mb-3">
                    <i data-lucide="{{ $meta['icon'] }}" style="width:28px;height:28px;"></i>
                </span>
                <h5 class="font-bold text-ink-emphasis mb-1">{{ $meta['msg'] }}</h5>
                <small class="text-ink-tertiary">{{ $meta['sub'] }}</small>
            </div>
        </section>

        {{-- Method card --}}
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="credit-card" class="text-feedback-info" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Payout Method</h5>
            </div>
            <div class="p-5">
                @if ($payout->payoutMethod)
                    <div class="flex items-center gap-2 mb-3">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                            <i data-lucide="{{ $payout->payoutMethod->method_type === 'bank' ? 'building' : ($payout->payoutMethod->method_type === 'mobile_banking' ? 'smartphone' : 'dollar-sign') }}"
                               style="width:18px;height:18px;"></i>
                        </span>
                        <div>
                            <p class="mb-0 font-semibold text-ink-emphasis">{{ $payout->payoutMethod->methodLabel() }}</p>
                        </div>
                    </div>
                    <dl class="text-sm space-y-1.5">
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Account</dt>
                            <dd class="font-medium text-ink-emphasis">{{ $payout->payoutMethod->account_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Number</dt>
                            <dd class="font-mono font-medium text-ink-emphasis">{{ $payout->payoutMethod->maskedAccountNumber() }}</dd>
                        </div>
                        @if ($payout->payoutMethod->bank_name)
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Bank</dt>
                            <dd class="font-medium text-ink-emphasis">{{ $payout->payoutMethod->bank_name }}</dd>
                        </div>
                        @endif
                        @if ($payout->payoutMethod->mobile_provider)
                        <div class="flex justify-between">
                            <dt class="text-ink-tertiary">Provider</dt>
                            <dd class="font-medium text-ink-emphasis">{{ ucfirst($payout->payoutMethod->mobile_provider) }}</dd>
                        </div>
                        @endif
                    </dl>
                @else
                    <div class="text-center py-3">
                        <i data-lucide="minus-circle" class="text-ink-tertiary mx-auto mb-2" style="width:32px;height:32px;"></i>
                        <p class="text-ink-tertiary mb-0">Method no longer available.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

@endsection
