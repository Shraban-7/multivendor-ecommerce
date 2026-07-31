@php
    $counts = $counts ?? ['total' => 0, 'active' => 0, 'cod' => 0, 'free' => 0];
@endphp
@extends('seller.layouts.app')
@section('title', 'Shipping Zones')

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="map-pin" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Shipping Zones</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Shipping Zones</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="globe-2" style="width:11px;height:11px;" class="me-1"></i> Logistics
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Configure delivery rates, free-shipping thresholds and carrier coverage.</p>
            </div>
            <div class="shrink-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addZoneModal">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Add Zone
                </button>
            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif

@php
    $tiles = [
        ['key' => 'total',  'label' => 'Total Zones', 'top' => '#0ea5e9', 'text' => 'text-feedback-info',         'icon' => 'map-pin'],
        ['key' => 'active', 'label' => 'Active',       'top' => '#10b981', 'text' => 'text-feedback-success',      'icon' => 'check-circle-2'],
        ['key' => 'cod',    'label' => 'COD Enabled',  'top' => '#fb923c', 'text' => 'text-feedback-warning',      'icon' => 'banknote'],
        ['key' => 'free',   'label' => 'Free Shipping','top' => '#a855f7', 'text' => 'text-[#a855f7]',             'icon' => 'gift'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($counts[$tile['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

@if ($zones->count() > 0)
    <section class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
            <i data-lucide="map-pin" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
            <h3 class="text-sm font-bold text-ink-emphasis mb-0">All Zones</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 p-4">
            @foreach ($zones as $zone)
                <article class="bg-surface-muted rounded-sm overflow-hidden flex flex-col">
                    <div class="p-5 grow">
                        <div class="flex justify-between items-start mb-3">
                            <h6 class="font-bold text-ink-emphasis mb-0">{{ $zone->name }}</h6>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $zone->is_active ? 'bg-feedback-success/15 text-feedback-success' : 'bg-ink-tertiary/15 text-ink-tertiary' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ $zone->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="text-sm space-y-1.5">
                            <div class="flex justify-between"><span class="text-ink-tertiary">Type</span><span class="font-medium text-ink-emphasis">{{ \App\Domain\Shipping\Models\SellerShippingZone::types()[$zone->type] ?? $zone->type }}</span></div>
                            <div class="flex justify-between"><span class="text-ink-tertiary">Rate</span><span class="font-semibold text-ink-emphasis">{{ money($zone->rate) }}</span></div>
                            @if ($zone->free_above)
                                <div class="flex justify-between"><span class="text-ink-tertiary">Free above</span><span class="font-medium text-ink-emphasis">{{ money($zone->free_above) }}</span></div>
                            @endif
                            @if ($zone->carrier)
                                <div class="flex justify-between"><span class="text-ink-tertiary">Carrier</span><span class="font-medium text-ink-emphasis">{{ $zone->carrier->name }}</span></div>
                            @endif
                            @if ($zone->estimated_days_min)
                                <div class="flex justify-between"><span class="text-ink-tertiary">Delivery</span><span class="font-medium text-ink-emphasis">{{ $zone->estimated_days_min }}-{{ $zone->estimated_days_max }} days</span></div>
                            @endif
                            <div class="flex justify-between"><span class="text-ink-tertiary">COD</span>
                                <span class="font-medium {{ $zone->is_cod_available ? 'text-feedback-success' : 'text-ink-tertiary' }}">
                                    {{ $zone->is_cod_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                            @if ($zone->districts)
                                <div class="flex justify-between"><span class="text-ink-tertiary">Coverage</span><span class="font-medium text-ink-emphasis">{{ count($zone->districts) }} districts</span></div>
                            @endif
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-border flex gap-2">
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editZoneModal-{{ $zone->id }}">
                            <i data-lucide="pencil" style="width:13px;height:13px;"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('seller.shipping.zones.destroy', $zone) }}"
                              onsubmit="return confirm('Delete this shipping zone?')" class="grow">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm w-full text-feedback-danger" style="color:#dc2625;">
                                <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($zones->hasPages())
            <div class="flex justify-end p-4 border-t border-border">{{ $zones->links() }}</div>
        @endif
    </section>
@else
    <section class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="p-10 text-center">
            <i data-lucide="map-pin" class="text-ink-tertiary mx-auto mb-3" style="width:40px;height:40px;"></i>
            <h5 class="font-semibold mb-1 text-ink-emphasis">No Shipping Zones</h5>
            <p class="text-sm text-ink-tertiary mb-3">Create shipping zones to set delivery rates for different regions.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addZoneModal">
                <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Shipping Zone
            </button>
        </div>
    </section>
@endif

@foreach ($zones as $zone)
    <div class="modal fade" id="editZoneModal-{{ $zone->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('seller.shipping.zones.update', $zone) }}">
                    @csrf
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title font-bold">Edit Shipping Zone</h5>
                            <small class="text-ink-tertiary">{{ $zone->name }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('seller.shipping._zone_form', ['zone' => $zone])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" style="width:14px;height:14px;"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="addZoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('seller.shipping.zones.store') }}">
                @csrf
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-bold">Add Shipping Zone</h5>
                        <small class="text-ink-tertiary">Configure a new region or carrier cost</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('seller.shipping._zone_form', ['zone' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width:14px;height:14px;"></i> Create Zone
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    $('.select-districts').each(function() {
        if ($(this).data('select2')) return;
        $(this).select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select districts...',
            dropdownParent: $(this).closest('.modal')
        });
        $(this).data('select2', true);
    });
});
</script>
@endpush

@endsection
