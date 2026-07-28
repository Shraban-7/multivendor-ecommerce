@extends('seller.layouts.app')
@section('title', 'Shipping Zones')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Shipping Zones</h4>
        <button class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addZoneModal">
            <i data-feather="plus" style="width: 16px; height: 16px;"></i> Add Zone
        </button>
    </div>

    @if ($zones->count() > 0)
        <div class="row g-3">
            @foreach ($zones as $zone)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-semibold mb-0">{{ $zone->name }}</h6>
                                <div class="d-flex gap-1">
                                    <span class="badge {{ $zone->is_active ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                        {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Type:</span>
                                    <span class="fw-medium">{{ \App\Domain\Shipping\Models\SellerShippingZone::types()[$zone->type] ?? $zone->type }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Rate:</span>
                                    <span class="fw-medium">{{ money($zone->rate) }}</span>
                                </div>
                                @if ($zone->free_above)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Free above:</span>
                                        <span class="fw-medium">{{ money($zone->free_above) }}</span>
                                    </div>
                                @endif
                                @if ($zone->carrier)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Carrier:</span>
                                        <span class="fw-medium">{{ $zone->carrier->name }}</span>
                                    </div>
                                @endif
                                @if ($zone->estimated_days_min)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Delivery:</span>
                                        <span class="fw-medium">{{ $zone->estimated_days_min }}-{{ $zone->estimated_days_max }} days</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">COD:</span>
                                    <span class="fw-medium">{{ $zone->is_cod_available ? 'Available' : 'Unavailable' }}</span>
                                </div>
                                @if ($zone->districts)
                                    <div class="mt-2">
                                        <span class="text-muted small">Coverage:</span>
                                        <span class="fw-medium small">{{ count($zone->districts) }} district(s)</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editZoneModal-{{ $zone->id }}">
                                    <i data-feather="edit" style="width: 14px; height: 14px;"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('seller.shipping.zones.destroy', $zone) }}"
                                      onsubmit="return confirm('Delete this shipping zone?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger">
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Zone</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($zones->hasPages())
            <div class="mt-3 d-flex justify-content-end">{{ $zones->links() }}</div>
        @endif
    @else
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center py-5">
                <i data-feather="truck" style="width: 64px; height: 64px;" class="text-muted mb-3"></i>
                <h5 class="fw-semibold mb-2">No Shipping Zones</h5>
                <p class="text-muted mb-3">Create shipping zones to set delivery rates for different regions.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addZoneModal">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Zone</button>
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
