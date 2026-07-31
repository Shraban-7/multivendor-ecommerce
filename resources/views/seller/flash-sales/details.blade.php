@php
    $now = now();
    $isLive     = $flashSale->start_time->isPast() && $flashSale->end_time->isFuture();
    $isUpcoming = $flashSale->start_time->isFuture();
    $isEnded    = $flashSale->end_time->isPast();

    $statusLabel  = $isLive ? 'Live' : ($isUpcoming ? 'Upcoming' : 'Ended');
    if ($isLive) {
        $statusPill = 'bg-emerald-500 text-white animate-pulse';
    } elseif ($isUpcoming) {
        $statusPill = 'bg-blue-500 text-white';
    } else {
        $statusPill = 'bg-surface-muted text-ink-tertiary';
    }

    $approvedSub = ($submitted ?? collect())->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_APPROVED)->count();
    $pendingSub  = ($submitted ?? collect())->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_PENDING)->count();
    $rejectedSub = ($submitted ?? collect())->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_REJECTED)->count();

    // Campaign timeline progress (0–100%)
    $campaignLength = $flashSale->start_time->diffInSeconds($flashSale->end_time);
    $elapsed = $flashSale->end_time->isPast() ? $campaignLength : max(0, $flashSale->start_time->diffInSeconds($now));
    $progressPct = $campaignLength > 0 ? min(100, round(($elapsed / $campaignLength) * 100)) : 0;

    $daysLeft = max(0, (int) floor($flashSale->end_time->diffInDays($now)));
    $hoursLeft = max(0, (int) floor($flashSale->end_time->diffInHours($now) % 24));
@endphp
@extends('seller.layouts.app')
@section('title', 'Flash Sale — ' . $flashSale->title)
@section('content')

@push('style')
<style>
    .flash-details__timeline-track { position: relative; height: 8px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
    .flash-details__timeline-bar  { position: absolute; top: 0; left: 0; bottom: 0; border-radius: 999px; background: linear-gradient(90deg, #F85606, #fb923c); }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="zap" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.flash-sales.index') }}" class="hover:text-ink transition-colors">Flash Sales</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink font-semibold">{{ \Illuminate\Support\Str::limit($flashSale->title, 30) }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink mb-0">{{ $flashSale->title }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $statusPill }}">
                        @if ($isLive)
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-white me-1.5"></span>
                        @endif
                        {{ $statusLabel }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Campaign runs from <strong class="text-ink">{{ $flashSale->start_time->format('d M Y · h:i A') }}</strong> to <strong class="text-ink">{{ $flashSale->end_time->format('d M Y · h:i A') }}</strong></p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#guidelineModal">
                    <i data-lucide="info" style="width:14px;height:14px;"></i> See Guidelines
                </button>
                @if (! $isEnded)
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Product
                    </button>
                @endif
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-center justify-between text-xs text-ink-tertiary mb-1">
                <span>Progress</span>
                <span>
                    @if ($isLive)
                        <strong class="text-ink">{{ $progressPct }}% complete</strong> · {{ $daysLeft }}d {{ $hoursLeft }}h left
                    @elseif ($isUpcoming)
                        Starts in <strong class="text-ink">{{ (int) $flashSale->start_time->diffInDays($now) }} days</strong>
                    @else
                        <span class="text-ink-tertiary">Campaign ended</span>
                    @endif
                </span>
            </div>
            <div class="flash-details__timeline-track">
                <div class="flash-details__timeline-bar" style="width: {{ $progressPct }}%"></div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES — 4 METRICS ═══ --}}
@php
    $detailsKpis = [
        ['label' => 'Submitted',  'value' => number_format(($submitted ?? collect())->count()), 'sub' => 'In this campaign',     'icon' => 'package',  'tone' => 'brand'],
        ['label' => 'Approved',   'value' => number_format($approvedSub),                     'sub' => 'Live on storefront',    'icon' => 'check-circle','tone' => 'success'],
        ['label' => 'Pending',    'value' => number_format($pendingSub),                      'sub' => 'Awaiting admin',        'icon' => 'hourglass',  'tone' => 'warning'],
        ['label' => 'Rejected',   'value' => number_format($rejectedSub),                     'sub' => 'Did not qualify',       'icon' => 'x-circle',  'tone' => 'danger'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    @foreach ($detailsKpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="h-1 absolute top-0 left-0 right-0
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : (
                   $kpi['tone'] === 'warning' ? 'bg-amber-500' : 'bg-rose-500')) }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-2xl text-ink mt-1">{{ $kpi['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                       $kpi['tone'] === 'warning' ? 'bg-amber-50 text-feedback-warning' : 'bg-rose-50 text-rose-500')) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ GUIDELINES ═══ --}}
@if (!empty($flashSale->description))
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
        <i data-lucide="scroll-text" class="text-feedback-info" style="width:16px;height:16px;"></i>
        <h5 class="mb-0 font-bold text-ink">Campaign Guidelines</h5>
    </div>
    <div class="p-5 prose prose-sm max-w-none text-ink-secondary">{!! $flashSale->description !!}</div>
</section>
@endif

{{-- ═══ SUBMITTED PRODUCTS TABLE ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="package-check" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">My Submissions</h5>
        </div>
        @if (! $isEnded && ($myProducts ?? collect())->isNotEmpty())
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i data-lucide="plus" style="width:14px;height:14px;"></i> Submit Another
            </button>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-4 py-2.5 w-10">#</th>
                    <th scope="col" class="px-4 py-2.5">Product</th>
                    <th scope="col" class="px-4 py-2.5 text-right">Stock In</th>
                    <th scope="col" class="px-4 py-2.5 text-right">Stock Out</th>
                    <th scope="col" class="px-4 py-2.5 text-right">Remaining</th>
                    <th scope="col" class="px-4 py-2.5 text-center">Status</th>
                    <th scope="col" class="px-4 py-2.5">Submitted</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse (($submitted ?? collect()) as $i => $s)
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs bg-surface-muted text-ink-tertiary">{{ $i + 1 }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if (!empty($s->product))
                                <div class="flex items-center gap-2">
                                    <img src="{{ storage_url($s->product->thumbnail ?? '') }}" alt="{{ $s->product->name }}" class="w-9 h-9 rounded-sm object-cover border border-border shrink-0">
                                    <div>
                                        <p class="mb-0 font-medium text-ink">{{ \Illuminate\Support\Str::limit($s->product->name, 32) }}</p>
                                        <small class="text-ink-tertiary font-mono">{{ $s->product->sku ?? '' }}</small>
                                    </div>
                                </div>
                            @else
                                <p class="mb-0 text-ink-secondary">— deleted —</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format((int) ($s->stock_in ?? 0)) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format((int) ($s->stock_out ?? 0)) }}</td>
                        <td class="px-4 py-3 text-right">
                            @php $rem = (int) ($s->stock_in ?? 0) - (int) ($s->stock_out ?? 0); @endphp
                            @if ($rem > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500 text-white">{{ number_format($rem) }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500 text-white">Sold out</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ((int) $s->status === \App\Domain\Product\Models\FlashSaleProduct::STATUS_PENDING)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500 text-white"><i data-lucide="hourglass" style="width:11px;height:11px;" class="me-1"></i> Pending</span>
                            @elseif ((int) $s->status === \App\Domain\Product\Models\FlashSaleProduct::STATUS_APPROVED)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500 text-white"><i data-lucide="check" style="width:11px;height:11px;" class="me-1"></i> Approved</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-rose-500 text-white"><i data-lucide="x" style="width:11px;height:11px;" class="me-1"></i> Rejected</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-ink-tertiary">{{ optional($s->created_at)->diffForHumans() ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-ink-tertiary">
                            <i data-lucide="package-x" class="mx-auto mb-2 opacity-50" style="width:36px;height:36px;"></i>
                            <p class="mb-1">You haven't submitted any products yet.</p>
                            @if (! $isEnded && ($myProducts ?? collect())->isNotEmpty())
                                <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Submit Your First Product
                                </button>
                            @else
                                <small>Pick an active campaign above to participate.</small>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- ═══ ADD PRODUCT MODAL — placed via @stack('modals') so Bootstrap centers properly ═══ --}}
@push('modals')
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content border-0 shadow-lg" method="POST" action="{{ route('seller.flash-sales.submit', $flashSale->id) }}">
            @csrf
            <div class="modal-header border-b border-border bg-surface-muted">
                <div class="flex items-center gap-2">
                    <span class="shrink-0 w-8 h-8 rounded-sm bg-brand-tint text-brand flex items-center justify-center">
                        <i data-lucide="plus" style="width:16px;height:16px;"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-bold text-ink mb-0">Submit Product to this Campaign</h5>
                        <small class="text-ink-tertiary">{{ $flashSale->title }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-ink mb-1 uppercase tracking-wider">Select Product <span class="text-rose-500">*</span></label>
                    @php
                        $alreadySubmittedIds = ($submitted ?? collect())->pluck('product_id')->toArray();
                    @endphp
                    <select name="product_id" required class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors product-select">
                        @foreach (($myProducts ?? collect()) as $p)
                            <option value="{{ $p->id }}" {{ in_array($p->id, $alreadySubmittedIds) ? 'disabled' : '' }}>
                                {{ $p->name }}@if(filled($p->sku)) · {{ $p->sku }}@endif · (Stock: {{ (int) ($p->totalStock ?? 0) }})
                                @if(in_array($p->id, $alreadySubmittedIds)) · already submitted @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-ink-tertiary mt-1">Only your active products are listed. Products you've already submitted are disabled.</p>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-sm flex items-start gap-2 text-xs">
                    <i data-lucide="info" class="text-feedback-warning shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
                    <span class="text-ink-secondary">Once submitted, your product will be reviewed by our team. We'll notify you when it's approved or rejected.</span>
                </div>
            </div>

            <div class="modal-footer border-t border-border bg-surface-muted">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">
                    <i data-lucide="send" style="width:14px;height:14px;"></i> Submit Product
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="guidelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-b border-border bg-surface-muted">
                <div class="flex items-center gap-2">
                    <span class="shrink-0 w-8 h-8 rounded-sm bg-feedback-info/10 text-feedback-info flex items-center justify-center">
                        <i data-lucide="scroll-text" style="width:16px;height:16px;"></i>
                    </span>
                    <h5 class="modal-title font-bold text-ink mb-0">Campaign Guidelines</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body prose prose-sm max-w-none text-ink-secondary">{!! $flashSale->description ?? '<p class="text-ink-tertiary">No guidelines have been published for this campaign.</p>' !!}</div>
            <div class="modal-footer border-t border-border bg-surface-muted">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    $(function () {
        if ($.fn.select2 && $('.product-select').length) {
            $('.product-select').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#addProductModal'),
                width: '100%',
                placeholder: 'Choose a product...'
            });
        }
    });
</script>
@endpush
@endsection
