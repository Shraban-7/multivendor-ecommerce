@props(['product'])

@php
    $discountPercent = 0;
    if ($product->price > 0 && $product->compare_price > 0) {
        $discountPercent = round((($product->price - $product->compare_price) / $product->price) * 100);
    }
@endphp

<div class="flex-shrink-0 w-[155px] sm:w-[185px] bg-white border border-[#E5E5E5] rounded-sm overflow-hidden eq hover:border-[#F85606]">
    <a href="{{ route('products.details', $product->slug) }}" class="block">
        <div class="relative aspect-square bg-[#F5F5F5]">
            @if ($discountPercent > 0)
                <span class="absolute top-2 left-2 z-10 text-white text-[11px] font-bold leading-none" style="background:#F85606; padding:3px 5px 3px 6px; border-radius:2px; display:inline-flex; align-items:center; gap:1px;">
                    -{{ $discountPercent }}%
                </span>
            @endif
            <img src="{{ $product->thumbnail ? storage_url($product->thumbnail) : asset('assets/frontend/images/placeholder.png') }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-contain p-2"
                 loading="lazy">
        </div>
    </a>

    <div class="p-2.5">
        <a href="{{ route('products.details', $product->slug) }}"
           class="block text-xs text-[#191919] leading-snug line-clamp-2 hover:text-[#F85606] eq min-h-[2rem]">
            {{ $product->name }}
        </a>
    </div>
</div>