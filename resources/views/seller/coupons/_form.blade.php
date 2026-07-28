@php $c = $coupon ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Coupon Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $c->title ?? '') }}" placeholder="e.g., Summer Sale 2026">
    </div>
    <div class="col-md-6">
        <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                   value="{{ old('code', $c->code ?? '') }}" placeholder="e.g., SUMMER20" required>
            <button type="button" class="btn btn-light border" onclick="generateCode()">
                <i data-feather="refresh-cw" style="width: 16px; height: 16px;"></i>
            </button>
        </div>
        @error('code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
        <select name="discount_type" class="form-select" required>
            <option value="percentage" {{ $c && $c->discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
            <option value="flat" {{ $c && $c->discount_type === 'flat' ? 'selected' : '' }}>Flat (৳)</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Discount Value <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="discount_value" class="form-control"
               value="{{ old('discount_value', $c->discount_value ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Max Discount (৳)</label>
        <input type="number" step="0.01" min="0" name="max_discount" class="form-control"
               value="{{ old('max_discount', $c->max_discount ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Min Purchase (৳)</label>
        <input type="number" step="0.01" min="0" name="min_purchase" class="form-control"
               value="{{ old('min_purchase', $c->min_purchase ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Usage Limit</label>
        <input type="number" min="1" name="usage_limit" class="form-control"
               value="{{ old('usage_limit', $c->usage_limit ?? '') }}" placeholder="Unlimited">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="1" {{ !$c || $c->status ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $c && !$c->status ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Valid From <span class="text-danger">*</span></label>
        <input type="date" name="valid_from" class="form-control"
               value="{{ old('valid_from', $c ? $c->valid_from->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Valid Until <span class="text-danger">*</span></label>
        <input type="date" name="valid_until" class="form-control"
               value="{{ old('valid_until', $c ? $c->valid_until->format('Y-m-d') : now()->addMonth()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Applicable Products</label>
        <select name="product_ids[]" class="form-select" multiple style="min-height: 120px;">
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                    {{ $c && $c->products->contains($product->id) ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Leave empty to apply to all products.</small>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $c->description ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.querySelector('input[name="code"]').value = code;
}
</script>
@endpush
