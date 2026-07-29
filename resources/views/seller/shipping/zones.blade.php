@extends('seller.layouts.app')
@section('title', 'Shipping Zones')

@section('content')
<div class="w-full px-0">
    <div class="flex flex-wrap justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">Shipping Zones</h4>
        <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1" data-bs-toggle="modal" data-bs-target="#addZoneModal">
            <i data-feather="plus" style="width: 16px; height: 16px;"></i> Add Zone
        </button>
    </div>

    @if ($zones->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($zones as $zone)
                <div>
                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 h-full" style="border-radius: 12px;">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <h6 class="font-semibold mb-0">{{ $zone->name }}</h6>
                                <div class="flex gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $zone->is_active ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                        {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-sm">
                                <div class="flex justify-between mb-1">
                                    <span class="text-ink-tertiary">Type:</span>
                                    <span class="font-medium">{{ \App\Domain\Shipping\Models\SellerShippingZone::types()[$zone->type] ?? $zone->type }}</span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-ink-tertiary">Rate:</span>
                                    <span class="font-medium">{{ money($zone->rate) }}</span>
                                </div>
                                @if ($zone->free_above)
                                    <div class="flex justify-between mb-1">
                                        <span class="text-ink-tertiary">Free above:</span>
                                        <span class="font-medium">{{ money($zone->free_above) }}</span>
                                    </div>
                                @endif
                                @if ($zone->carrier)
                                    <div class="flex justify-between mb-1">
                                        <span class="text-ink-tertiary">Carrier:</span>
                                        <span class="font-medium">{{ $zone->carrier->name }}</span>
                                    </div>
                                @endif
                                @if ($zone->estimated_days_min)
                                    <div class="flex justify-between mb-1">
                                        <span class="text-ink-tertiary">Delivery:</span>
                                        <span class="font-medium">{{ $zone->estimated_days_min }}-{{ $zone->estimated_days_max }} days</span>
                                    </div>
                                @endif
                                <div class="flex justify-between mb-1">
                                    <span class="text-ink-tertiary">COD:</span>
                                    <span class="font-medium">{{ $zone->is_cod_available ? 'Available' : 'Unavailable' }}</span>
                                </div>
                                @if ($zone->districts)
                                    <div class="mt-2">
                                        <span class="text-ink-tertiary text-sm">Coverage:</span>
                                        <span class="font-medium text-sm">{{ count($zone->districts) }} district(s)</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3 flex gap-2">
                                <button class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1" data-bs-toggle="modal" data-bs-target="#editZoneModal-{{ $zone->id }}">
                                    <i data-feather="edit" style="width: 14px; height: 14px;"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('seller.shipping.zones.destroy', $zone) }}"
                                      onsubmit="return confirm('Delete this shipping zone?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1 text-feedback-danger">
                                        <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editZoneModal-{{ $zone->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('seller.shipping.zones.update', $zone) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Shipping Zone</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('seller.shipping._zone_form', ['zone' => $zone])
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Update Zone</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($zones->hasPages())
            <div class="mt-3 flex justify-end">{{ $zones->links() }}</div>
        @endif
    @else
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0" style="border-radius: 12px;">
            <div class="p-5 text-center py-5">
                <i data-feather="truck" style="width: 64px; height: 64px;" class="text-ink-tertiary mb-3"></i>
                <h5 class="font-semibold mb-2">No Shipping Zones</h5>
                <p class="text-ink-tertiary mb-3">Create shipping zones to set delivery rates for different regions.</p>
                <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1" data-bs-toggle="modal" data-bs-target="#addZoneModal">
                    <i data-feather="plus" class="me-1" style="width: 16px; height: 16px;"></i> Add Shipping Zone
                </button>
            </div>
        </div>
    @endif
</div>

{{-- Add Zone Modal --}}
<div class="modal fade" id="addZoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('seller.shipping.zones.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Shipping Zone</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('seller.shipping._zone_form', ['zone' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Create Zone</button>
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
