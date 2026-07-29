@php
    $pageTitle = "Flash Sales | {$seller->business_name}";
    $now = now();
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .flash-dash__countdown { background: rgba(0,0,0,.06); padding: .15rem .5rem; border-radius: 999px; font-weight: 600; font-size: .75rem; }
    .flash-dash__timeline-track { position: relative; height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
    .flash-dash__timeline-bar  { position: absolute; top: 0; left: 0; bottom: 0; border-radius: 999px; background: linear-gradient(90deg, #F85606, #fb923c); }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-500 via-orange-400 to-amber-400" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="zap" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Marketing</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink font-semibold">Flash Sales</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink mb-0">Flash Sales</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-500 text-white">
                        <i data-lucide="zap" style="width:11px;height:11px;" class="me-1"></i> Marketing
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Live campaigns you can join to boost sales volume and acquire new customers.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.products.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="package" style="width:14px;height:14px;"></i> Manage Products
                </a>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="layout-dashboard" style="width:14px;height:14px;"></i> Dashboard
                </a>
            </div>
        </div>
        <div class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-sm flex items-start gap-2 text-sm">
            <i data-lucide="info" class="text-orange-600 shrink-0 mt-0.5" style="width:14px;height:14px;"></i>
            <span class="text-ink-secondary">Submitting a product puts it through admin review. Approved products appear in the campaign at the start time.</span>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES — 5 METRICS ═══ --}}
@php
    $activeCount = $flashSales->count();
    $pastCount   = $sellerFlashSales->count();

    $liveCount = 0;
    $upcomingCount = 0;
    foreach ($flashSales as $s) {
        if ($s->start_time->isPast() && $s->end_time->isFuture()) $liveCount++;
        elseif ($s->start_time->isFuture()) $upcomingCount++;
    }

    $myApproved = 0;
    $myPending  = 0;
    foreach ($sellerFlashSales as $s) {
        $approved = $s->products()->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_APPROVED)->count();
        $pending  = $s->products()->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_PENDING)->count();
        $myApproved += $approved;
        $myPending  += $pending;
    }

    $kpis = [
        ['label' => 'Live now',          'value' => $liveCount,                                      'sub' => 'Active campaigns',       'icon' => 'zap',        'tone' => 'success'],
        ['label' => 'Upcoming',          'value' => $upcomingCount,                                  'sub' => 'Not started yet',        'icon' => 'calendar',   'tone' => 'info'],
        ['label' => 'Total available',   'value' => $activeCount,                                    'sub' => 'Open for submission',    'icon' => 'megaphone',  'tone' => 'warning'],
        ['label' => 'My approved',       'value' => $myApproved,                                     'sub' => 'Across all campaigns',   'icon' => 'check-circle','tone' => 'brand'],
        ['label' => 'Awaiting review',   'value' => $myPending,                                      'sub' => 'Pending admin approval', 'icon' => 'hourglass',  'tone' => 'muted'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
    @foreach ($kpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="h-1 absolute top-0 left-0 right-0
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : (
                   $kpi['tone'] === 'warning' ? 'bg-amber-500' : 'bg-gray-500'))) }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-2xl text-ink mt-1">{{ number_format($kpi['value']) }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : (
                       $kpi['tone'] === 'warning' ? 'bg-amber-50 text-feedback-warning' : 'bg-surface-muted text-ink-tertiary'))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ ACTIVE FLASH SALES ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <span class="shrink-0 w-7 h-7 rounded-sm bg-feedback-success text-white flex items-center justify-center">
                <i data-lucide="zap" style="width:14px;height:14px;"></i>
            </span>
            <h5 class="mb-0 font-bold text-ink">Active & Upcoming Campaigns</h5>
        </div>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500 text-white">
            {{ $activeCount }} {{ Str::plural('campaign', $activeCount) }}
        </span>
    </div>
    @if ($flashSales->isEmpty())
        <div class="p-5 text-center text-sm text-ink-tertiary">
            <i data-lucide="calendar-off" class="mx-auto mb-2 opacity-50" style="width:36px;height:36px;"></i>
            <p class="mb-1">No campaigns are open right now.</p>
            <small>Check back later or watch this space for new promotions.</small>
        </div>
    @else
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            @foreach ($flashSales as $sale)
                @php
                    $isLive     = $sale->start_time->isPast() && $sale->end_time->isFuture();
                    $isUpcoming = $sale->start_time->isFuture();
                    $isEnded    = $sale->end_time->isPast();
                    $totalSecs  = max(0, $sale->end_time->diffInSeconds($now));
                    $daysLeft   = (int) floor($totalSecs / 86400);
                    $hoursLeft  = (int) floor(($totalSecs % 86400) / 3600);
                @endphp
                <article class="border border-border rounded-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow
                    {{ $isLive ? 'ring-2 ring-emerald-200' : '' }}">
                    <div class="px-4 py-3
                        {{ $isLive ? 'bg-emerald-500 text-white' : ($isUpcoming ? 'bg-blue-500 text-white' : 'bg-surface-muted text-ink') }} flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                            @if ($isLive)
                                <span class="inline-block w-2 h-2 rounded-full bg-white animate-pulse"></span> Live now
                            @elseif ($isUpcoming)
                                <i data-lucide="clock" style="width:12px;height:12px;"></i> Upcoming
                            @else
                                Ended
                            @endif
                        </span>
                        @if ($isLive)
                            <span class="flash-dash__countdown text-white">
                                <i data-lucide="hourglass" style="width:11px;height:11px;"></i>
                                {{ $daysLeft > 0 ? $daysLeft.'d ' : '' }}{{ $hoursLeft }}h left
                            </span>
                        @endif
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <h5 class="font-bold text-lg text-ink mb-2">{{ $sale->title }}</h5>
                        <p class="text-sm text-ink-secondary mb-3 flex-1">{!! \Illuminate\Support\Str::limit(strip_tags((string) $sale->description), 120) !!}</p>

                        <div class="space-y-1.5 mb-3 text-xs">
                            <p class="flex items-center gap-2">
                                <i data-lucide="play" class="text-feedback-success shrink-0" style="width:12px;height:12px;"></i>
                                <span class="text-ink-tertiary">Starts:</span>
                                <strong class="text-ink">{{ $sale->start_time->format('d M Y · h:i A') }}</strong>
                            </p>
                            <p class="flex items-center gap-2">
                                <i data-lucide="stop-circle" class="text-rose-500 shrink-0" style="width:12px;height:12px;"></i>
                                <span class="text-ink-tertiary">Ends:</span>
                                <strong class="text-ink">{{ $sale->end_time->format('d M Y · h:i A') }}</strong>
                            </p>
                        </div>

                        <a href="{{ route('seller.flash-sales.details', $sale->id) }}" class="btn {{ $isLive ? 'btn-primary' : ($isUpcoming ? 'btn-light' : 'btn-light') }} w-full">
                            <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
                            {{ $isLive ? 'Join Now' : ($isUpcoming ? 'View & Pre-register' : 'View Recap') }}
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

{{-- ═══ MY PREVIOUS FLASH SALES ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <span class="shrink-0 w-7 h-7 rounded-sm bg-brand-tint text-brand flex items-center justify-center">
                <i data-lucide="history" style="width:14px;height:14px;"></i>
            </span>
            <h5 class="mb-0 font-bold text-ink">My Past Participations</h5>
        </div>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary border border-border">
            {{ $pastCount }} {{ Str::plural('campaign', $pastCount) }}
        </span>
    </div>
    @if ($sellerFlashSales->isEmpty())
        <div class="p-5 text-center text-sm text-ink-tertiary">
            <i data-lucide="inbox" class="mx-auto mb-2 opacity-50" style="width:36px;height:36px;"></i>
            <p class="mb-0">You haven't participated in any flash sales yet.</p>
            <small>Join one of the campaigns above to boost your sales.</small>
        </div>
    @else
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            @foreach ($sellerFlashSales as $sale)
                @php
                    $submitted = $sale->products()->count();
                    $approved  = (clone $sale->products())->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_APPROVED)->count();
                    $pending   = (clone $sale->products())->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_PENDING)->count();
                    $rejected  = (clone $sale->products())->where('status', \App\Domain\Product\Models\FlashSaleProduct::STATUS_REJECTED)->count();
                    $approvalRate = $submitted > 0 ? round(($approved / $submitted) * 100) : 0;
                @endphp
                <article class="border border-border rounded-sm p-4 bg-surface-muted/40 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <h5 class="font-bold text-ink leading-tight mb-0">{{ $sale->name ?? $sale->title ?? 'Campaign' }}</h5>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">
                            Ended
                        </span>
                    </div>
                    <p class="text-xs text-ink-tertiary mb-3 line-clamp-2">{!! \Illuminate\Support\Str::limit(strip_tags((string) ($sale->description ?? '')), 90) !!}</p>

                    <div class="grid grid-cols-3 gap-2 text-center mb-3">
                        <div class="p-2 bg-emerald-50 rounded-sm">
                            <p class="mb-0 font-bold text-base text-feedback-success">{{ $approved }}</p>
                            <small class="text-ink-tertiary">Approved</small>
                        </div>
                        <div class="p-2 bg-amber-50 rounded-sm">
                            <p class="mb-0 font-bold text-base text-feedback-warning">{{ $pending }}</p>
                            <small class="text-ink-tertiary">Pending</small>
                        </div>
                        <div class="p-2 bg-rose-50 rounded-sm">
                            <p class="mb-0 font-bold text-base text-rose-500">{{ $rejected }}</p>
                            <small class="text-ink-tertiary">Rejected</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-ink-tertiary">Approval rate</span>
                            <strong class="text-ink">{{ $approvalRate }}%</strong>
                        </div>
                        <div class="flash-dash__timeline-track">
                            <div class="flash-dash__timeline-bar" style="width: {{ $approvalRate }}%"></div>
                        </div>
                    </div>

                    <a href="{{ route('seller.flash-sales.details', $sale->id) }}" class="btn btn-light btn-sm w-full">
                        <i data-lucide="eye" style="width:14px;height:14px;"></i> View My Submissions
                    </a>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
