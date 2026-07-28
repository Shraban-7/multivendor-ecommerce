@php $z = $zone ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Zone Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $z->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Type <span class="text-danger">*</span></label>
        <select name="type" class="form-select" required>
            @foreach (\App\Domain\Shipping\Models\SellerShippingZone::types() as $value => $label)
                <option value="{{ $value }}" {{ $z && $z->type === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Rate (৳) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="rate" class="form-control" value="{{ old('rate', $z->rate ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Free Above (৳)</label>
        <input type="number" step="0.01" min="0" name="free_above" class="form-control" value="{{ old('free_above', $z->free_above ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Extra Rate/kg (৳)</label>
        <input type="number" step="0.01" min="0" name="extra_rate_per_kg" class="form-control" value="{{ old('extra_rate_per_kg', $z->extra_rate_per_kg ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Min Weight (kg)</label>
        <input type="number" step="0.01" min="0" name="min_weight" class="form-control" value="{{ old('min_weight', $z->min_weight ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Max Weight (kg)</label>
        <input type="number" step="0.01" min="0" name="max_weight" class="form-control" value="{{ old('max_weight', $z->max_weight ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Min Order (৳)</label>
        <input type="number" step="0.01" min="0" name="min_order" class="form-control" value="{{ old('min_order', $z->min_order ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Max Order (৳)</label>
        <input type="number" step="0.01" min="0" name="max_order" class="form-control" value="{{ old('max_order', $z->max_order ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Carrier</label>
        <select name="carrier_id" class="form-select">
            <option value="">Select carrier...</option>
            @foreach ($carriers as $carrier)
                <option value="{{ $carrier->id }}" {{ $z && $z->carrier_id === $carrier->id ? 'selected' : '' }}>{{ $carrier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Est. Delivery (min days)</label>
        <input type="number" min="1" name="estimated_days_min" class="form-control" value="{{ old('estimated_days_min', $z->estimated_days_min ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Est. Delivery (max days)</label>
        <input type="number" min="1" name="estimated_days_max" class="form-control" value="{{ old('estimated_days_max', $z->estimated_days_max ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Covered Districts</label>
        <select name="districts[]" class="form-select select-districts" multiple>
            @foreach ($districts as $district)
                <option value="{{ $district->id }}"
                    {{ $z && in_array($district->id, $z->districts ?? []) ? 'selected' : '' }}>
                    {{ $district->name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Leave empty to apply to all districts.</small>
    </div>
    <div class="col-12">
        <div class="d-flex gap-4">
            <div class="form-check">
                <input type="checkbox" name="is_cod_available" value="1" class="form-check-input" id="codCheck{{ $z->id ?? 'new' }}"
                    {{ !$z || $z->is_cod_available ? 'checked' : '' }}>
                <label class="form-check-label" for="codCheck{{ $z->id ?? 'new' }}">COD Available</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="activeCheck{{ $z->id ?? 'new' }}"
                    {{ !$z || $z->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="activeCheck{{ $z->id ?? 'new' }}">Active</label>
            </div>
        </div>
    </div>
</div>
