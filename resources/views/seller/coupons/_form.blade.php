@php $c = $coupon ?? null; @endphp

<div class="grid grid-cols-1 gap-3">
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Title</label>
        <input type="text" name="title" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('title', $c->title ?? '') }}" placeholder="e.g., Summer Sale 2026">
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Code <span class="text-feedback-danger">*</span></label>
        <div class="flex">
            <input type="text" name="code" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('code') is-invalid @enderror"
                   value="{{ old('code', $c->code ?? '') }}" placeholder="e.g., SUMMER20" required>
            <button type="button" class="btn btn-light" onclick="generateCode()">
                <i data-lucide="refresh-cw" style="width: 16px; height: 16px;"></i>
            </button>
        </div>
        @error('code') <div class="invalid-feedback block">{{ $message }}</div> @enderror
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Type <span class="text-feedback-danger">*</span></label>
        <select name="discount_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
            <option value="percentage" {{ $c && $c->discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
            <option value="flat" {{ $c && $c->discount_type === 'flat' ? 'selected' : '' }}>Flat (৳)</option>
        </select>
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Value <span class="text-feedback-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="discount_value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
               value="{{ old('discount_value', $c->discount_value ?? '') }}" required>
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Max Discount (৳)</label>
        <input type="number" step="0.01" min="0" name="max_discount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
               value="{{ old('max_discount', $c->max_discount ?? '') }}">
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Min Purchase (৳)</label>
        <input type="number" step="0.01" min="0" name="min_purchase" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
               value="{{ old('min_purchase', $c->min_purchase ?? 0) }}">
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Usage Limit</label>
        <input type="number" min="1" name="usage_limit" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
               value="{{ old('usage_limit', $c->usage_limit ?? '') }}" placeholder="Unlimited">
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
            <option value="1" {{ !$c || $c->status ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $c && !$c->status ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Valid From <span class="text-feedback-danger">*</span></label>
        <input type="date" name="valid_from" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
               value="{{ old('valid_from', $c ? $c->valid_from->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
    </div>
    <div class="md:col-span-1">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Valid Until <span class="text-feedback-danger">*</span></label>
        <input type="date" name="valid_until" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
               value="{{ old('valid_until', $c ? $c->valid_until->format('Y-m-d') : now()->addMonth()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-span-full">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Applicable Products</label>
        <select id="applicableProducts" name="product_ids[]" class="w-full" multiple="multiple" data-placeholder="Search & pick products (leave empty to apply to all)">
            @php
                $selectedIds = old('product_ids', $c ? $c->products->pluck('id')->toArray() : []);
                $selectedIds = array_map('strval', (array) $selectedIds);
            @endphp
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                    {{ in_array((string) $product->id, $selectedIds, true) ? 'selected' : '' }}>
                    {{ $product->name }}@if($product->sku) <small class="text-ink-tertiary">[{{ $product->sku }}]</small>@endif
                </option>
            @endforeach
        </select>
        <small class="text-ink-tertiary">Each selected product shows as a removable tag. Leave empty to apply to all products.</small>
    </div>
    <div class="col-span-full">
        <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
        <x-textarea-input name="description" :value="old('description', $c->description ?? '')" />
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

function formatProductOption(state) {
    if (!state.id) return state.text;
    const safe = $('<div>').text(state.text).html();
    return $('<span>').html(safe);
}

$(function() {
    const $select = $('#applicableProducts');
    if ($select.length === 0) return;

    $select.select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: $select.data('placeholder') || 'Search & pick products',
        allowClear: true,
        closeOnSelect: false,
        templateResult: formatProductOption,
        language: {
            noResults: function() { return 'No products found. Try another search term.'; }
        }
    });
});
</script>
@endpush

