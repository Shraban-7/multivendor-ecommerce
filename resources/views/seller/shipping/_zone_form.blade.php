@php $z = $zone ?? null; @endphp

<div class="grid grid-cols-1 md:grid-cols-12 gap-3">
    <div class="md:col-span-6">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Zone Name <span class="text-feedback-danger">*</span></label>
        <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('name', $z->name ?? '') }}" required>
    </div>
    <div class="md:col-span-6">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Type <span class="text-feedback-danger">*</span></label>
        <select name="type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
            @foreach (\App\Domain\Shipping\Models\SellerShippingZone::types() as $value => $label)
                <option value="{{ $value }}" {{ $z && $z->type === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-4">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Rate (৳) <span class="text-feedback-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="rate" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('rate', $z->rate ?? 0) }}" required>
    </div>
    <div class="md:col-span-4">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Free Above (৳)</label>
        <input type="number" step="0.01" min="0" name="free_above" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('free_above', $z->free_above ?? '') }}">
    </div>
    <div class="md:col-span-4">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Extra Rate/kg (৳)</label>
        <input type="number" step="0.01" min="0" name="extra_rate_per_kg" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('extra_rate_per_kg', $z->extra_rate_per_kg ?? '') }}">
    </div>
    <div class="md:col-span-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Min Weight (kg)</label>
        <input type="number" step="0.01" min="0" name="min_weight" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('min_weight', $z->min_weight ?? '') }}">
    </div>
    <div class="md:col-span-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Max Weight (kg)</label>
        <input type="number" step="0.01" min="0" name="max_weight" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('max_weight', $z->max_weight ?? '') }}">
    </div>
    <div class="md:col-span-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Min Order (৳)</label>
        <input type="number" step="0.01" min="0" name="min_order" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('min_order', $z->min_order ?? '') }}">
    </div>
    <div class="md:col-span-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Max Order (৳)</label>
        <input type="number" step="0.01" min="0" name="max_order" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('max_order', $z->max_order ?? '') }}">
    </div>
    <div class="md:col-span-6">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Carrier</label>
        <select name="carrier_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            <option value="">Select carrier...</option>
            @foreach ($carriers as $carrier)
                <option value="{{ $carrier->id }}" {{ $z && $z->carrier_id === $carrier->id ? 'selected' : '' }}>{{ $carrier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Est. Delivery (min days)</label>
        <input type="number" min="1" name="estimated_days_min" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('estimated_days_min', $z->estimated_days_min ?? '') }}">
    </div>
    <div class="md:col-span-3">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Est. Delivery (max days)</label>
        <input type="number" min="1" name="estimated_days_max" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('estimated_days_max', $z->estimated_days_max ?? '') }}">
    </div>
    <div class="col-span-full">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Covered Districts</label>
        <select name="districts[]" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors select-districts" multiple>
            @foreach ($districts as $district)
                <option value="{{ $district->id }}"
                    {{ $z && in_array($district->id, $z->districts ?? []) ? 'selected' : '' }}>
                    {{ $district->name }}
                </option>
            @endforeach
        </select>
        <small class="text-ink-tertiary text-sm">Leave empty to apply to all districts.</small>
    </div>
    <div class="col-span-full">
        <div class="flex gap-4">
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_cod_available" value="1" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="codCheck{{ $z->id ?? 'new' }}"
                    {{ !$z || $z->is_cod_available ? 'checked' : '' }}>
                <label class="text-sm text-ink" for="codCheck{{ $z->id ?? 'new' }}">COD Available</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="activeCheck{{ $z->id ?? 'new' }}"
                    {{ !$z || $z->is_active ? 'checked' : '' }}>
                <label class="text-sm text-ink" for="activeCheck{{ $z->id ?? 'new' }}">Active</label>
            </div>
        </div>
    </div>
</div>
