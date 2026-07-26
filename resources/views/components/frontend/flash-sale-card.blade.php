@props(['product'])

@php
    $discountPercent = 0;
    if ($product->price > 0 && $product->compare_price > 0) {
        $discountPercent = round((($product->price - $product->compare_price) / $product->price) * 100);
    }
@endphp

<div class="bg-white border border-ds-border-default rounded-sm overflow-hidden hover:shadow-card transition-shadow duration-200">
    <a href="{{ route('products.details', $product->slug) }}" class="block">
        <div class="relative aspect-square bg-ds-surface-muted">
            @if ($discountPercent > 0)
                <span class="absolute top-1 left-1 z-10 bg-ds-feedback-danger text-white text-[9px] font-bold px-1 py-0.5 rounded-xs">
                    -{{ $discountPercent }}%
                </span>
            @endif
            <img src="{{ $product->thumbnail ? storage_url($product->thumbnail) : asset('assets/frontend/images/placeholder.png') }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-contain p-1.5"
                 loading="lazy">
        </div>
    </a>

    <div class="p-1.5 sm:p-2">
        <a href="{{ route('products.details', $product->slug) }}"
           class="block text-[10px] sm:text-xs text-ds-text-primary leading-snug line-clamp-2 hover:text-brand transition-colors duration-100 min-h-[2rem]">
            {{ $product->name }}
        </a>

        @if ($product->compare_price > 0)
            <div class="flex items-baseline gap-1 mt-1">
                <span class="text-xs font-bold text-ds-feedback-danger">৳{{ number_format($product->compare_price) }}</span>
                <s class="text-[9px] text-ds-text-tertiary">৳{{ number_format($product->price) }}</s>
            </div>
        @else
            <span class="text-xs font-bold text-ds-text-primary mt-1 block">৳{{ number_format($product->price) }}</span>
        @endif
    </div>
</div>
