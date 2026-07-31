@php
    $shipments = $shipments ?? collect();
@endphp
@extends('seller.layouts.app')
@section('title', 'Shipments')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="truck" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Shipments</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Shipments</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="package" style="width:11px;height:11px;" class="me-1"></i> Logistics
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Track shipments, monitor carrier activity and follow deliveries to completion.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FILTER + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2 flex-wrap">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if(request('status') || request('tracking_number'))
            <a href="{{ route('seller.shipping.shipments.index') }}"
               class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>

    <div class="p-4 border-t border-border">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-6">
                <select name="status"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Statuses</option>
                    @foreach (\App\Domain\Shipping\Models\Shipment::statuses() as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-5 relative">
                <i data-lucide="hash" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="tracking_number" value="{{ request('tracking_number') }}"
                       placeholder="Tracking number…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-1">
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ method_exists($shipments, 'firstItem') ? $shipments->firstItem() ?? 0 : 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ method_exists($shipments, 'lastItem')  ? $shipments->lastItem()  ?? 0 : $shipments->count() }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ method_exists($shipments, 'total') ? $shipments->total() : $shipments->count() }}</span> shipments
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">#</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Order</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Carrier</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Tracking</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shipments as $shipment)
                    @php
                        $label = $shipment->status;
                        $pillBg = match ($label) {
                            'pending'          => 'bg-feedback-warning/15 text-feedback-warning',
                            'picked_up'        => 'bg-feedback-info/15 text-feedback-info',
                            'in_transit'       => 'bg-brand-tint text-brand-deep',
                            'out_for_delivery' => 'bg-feedback-warning/15 text-feedback-warning',
                            'delivered'        => 'bg-feedback-success/15 text-feedback-success',
                            'failed'           => 'bg-feedback-danger/15 text-feedback-danger',
                            'returned'         => 'bg-surface-muted text-ink-secondary',
                            default            => 'bg-surface-muted text-ink-tertiary',
                        };
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3 font-semibold text-ink-emphasis">#{{ $shipment->id }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('seller.orders.details', $shipment->order?->invoice_id) }}" class="hover:text-brand-deep">
                                {{ $shipment->order?->invoice_id ? '#'.$shipment->order->invoice_id : 'N/A' }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-ink-soft">{{ $shipment->carrier?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-ink-secondary text-xs">
                            @if ($shipment->tracking_number)
                                <code class="px-1.5 py-0.5 rounded-xs bg-surface-muted text-ink-secondary">{{ $shipment->tracking_number }}</code>
                            @else
                                <span class="text-ink-tertiary">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ \App\Domain\Shipping\Models\Shipment::statuses()[$label] ?? ucfirst(str_replace('_',' ', $label)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $shipment->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('seller.shipping.shipments.show', $shipment->id) }}" class="btn btn-light btn-sm">
                                <i data-lucide="eye" style="width:14px;height:14px;"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-10 text-center">
                                <i data-lucide="package" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No shipments yet</p>
                                <p class="text-ink-tertiary text-xs">Shipments you create from order details will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (method_exists($shipments, 'hasPages') && $shipments->hasPages())
        <div class="flex justify-end p-4 border-t border-border">
            {{ $shipments->links() }}
        </div>
    @endif
</section>

@endsection
