@php
    $options = $product['options'] ?? [];
    $variants = $product['variants'] ?? [];
    $hasVariants = count($variants) > 0;
    $hasColor = collect($options)->contains('id', 'color');
    $hasSize = collect($options)->contains('id', 'size');
@endphp

<div class="w-full mt-4 space-y-4">
    <input type="hidden" id="productBasePrice" value="{{ $product['price'] }}">
    <input type="hidden" id="productDiscountedPrice" value="{{ $product['compare_price'] ?? '' }}">
    <input type="hidden" class="has-color" value="{{ $hasColor ? 1 : 0 }}">
    <input type="hidden" class="has-size" value="{{ $hasSize ? 1 : 0 }}">
    <input type="hidden" class="variantId" name="variant_id" value="">

    <form id="variantForm{{ $product['id'] }}" class="space-y-4" data-id="{{ $product['id'] }}"
        data-slug="{{ $product['slug'] }}">

        @foreach ($options as $option)
            <div class="flex items-start gap-3 flex-wrap sm:flex-nowrap" data-option-group="{{ $option['id'] }}">
                <label class="w-16 text-xs font-semibold text-ds-text-secondary uppercase tracking-wide pt-2 shrink-0">{{ $option['name'] }}</label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($option['values'] as $value)
                        @if ($option['id'] === 'color')
                            <button type="button"
                                class="option-value-btn w-8 h-8 rounded-full border-2 transition-all duration-100 border-ds-border-default hover:border-ds-border-strong focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-deep focus-visible:ring-offset-2"
                                data-option-id="color"
                                data-value-id="{{ $value['id'] }}"
                                style="background: {{ $value['hex'] ?? '#ccc' }}"
                                title="{{ $value['value'] }}"
                                aria-label="Color: {{ $value['value'] }}">
                            </button>
                        @else
                            <button type="button"
                                class="option-value-btn text-xs font-medium px-3 py-1.5 rounded-sm border transition-all duration-100 bg-ds-surface-muted text-ds-text-secondary border-ds-border-default hover:border-brand hover:text-brand focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-deep focus-visible:ring-offset-1"
                                data-option-id="{{ $option['id'] }}"
                                data-value-id="{{ $value['id'] }}">
                                {{ $value['value'] }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- SKU / Stock / Availability --}}
        <div class="flex flex-wrap items-center gap-4 text-xs text-ds-text-secondary">
            <span class="flex items-center gap-1">
                <span class="text-ds-text-tertiary">SKU:</span>
                <span class="sku-text font-medium text-ds-text-primary">{{ $hasVariants ? '—' : ($product['sku'] ?? '') }}</span>
            </span>
            <span class="flex items-center gap-1">
                <span class="text-ds-text-tertiary">Stock:</span>
                <span class="stock-text font-medium text-ds-text-primary">{{ $hasVariants ? '—' : ($product['stock'] ?? 0) }}</span>
            </span>
            <span class="availability-text font-medium {{ $hasVariants ? 'text-ds-text-tertiary' : (($product['stock'] ?? 0) > 0 ? 'text-ds-feedback-success' : 'text-ds-feedback-danger') }}">
                {{ $hasVariants ? 'Select options' : (($product['stock'] ?? 0) > 0 ? 'In Stock' : 'Out of Stock') }}
            </span>
        </div>

        {{-- Quantity + Add to Cart --}}
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            {{-- Quantity Stepper --}}
            <div class="flex items-center border border-ds-border-default rounded-sm overflow-hidden shrink-0">
                <button type="button"
                    class="decreaseBtn w-9 h-9 flex items-center justify-center text-ds-text-secondary hover:bg-ds-surface-muted transition-colors duration-100"
                    aria-label="Decrease quantity">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                </button>
                <input readonly type="number" min="1" value="1"
                    class="quantity w-12 text-center text-sm font-semibold text-ds-text-primary border-x border-ds-border-default h-9 focus:outline-none"
                    aria-label="Quantity" />
                <button type="button"
                    class="increaseBtn w-9 h-9 flex items-center justify-center text-ds-text-secondary hover:bg-ds-surface-muted transition-colors duration-100"
                    aria-label="Increase quantity">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </div>

            {{-- Add to Cart + Wishlist --}}
            <div class="flex flex-1 gap-2">
                <button data-id="{{ $product['id'] }}" type="button"
                    class="addToCartBtn flex-1 h-9 bg-brand-deep hover:bg-brand-deep/90 active:bg-brand-deep/80 text-white px-4 rounded-sm text-sm font-semibold transition-colors duration-100 flex items-center justify-center gap-2 {{ $hasVariants ? 'opacity-50 cursor-not-allowed' : (($product['stock'] ?? 0) <= 0 ? 'opacity-50 cursor-not-allowed' : '') }}"
                    @disabled($hasVariants || ($product['stock'] ?? 0) <= 0)
                    aria-label="Add to Cart">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Add to Cart
                </button>

                <button type="button"
                    class="wishlistBtn w-9 h-9 flex items-center justify-center border border-ds-border-default rounded-sm text-ds-text-secondary hover:bg-ds-surface-muted hover:text-ds-feedback-danger transition-colors duration-100 shrink-0"
                    data-id="{{ $product['id'] }}"
                    aria-label="Add to wishlist">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </div>
        </div>
    </form>
</div>
