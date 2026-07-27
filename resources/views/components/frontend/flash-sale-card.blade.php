@props(['product'])

@php
    $discountPercent = 0;
    if ($product && $product->price > 0 && $product->compare_price > 0 && $product->compare_price < $product->price) {
        $discountPercent = round((($product->price - $product->compare_price) / $product->price) * 100);
    }
@endphp

@if ($product)
<div class="bg-white border border-[#E5E5E5] rounded-sm overflow-hidden hover:shadow-sm transition-shadow duration-200 h-full flex flex-col">
    <a href="{{ route('products.details', $product->slug) }}" class="block">
        <div class="relative aspect-square bg-[#F5F5F5]">
            @if ($discountPercent > 0)
                <span class="absolute top-1 left-1 z-10 bg-[#D93025] text-white text-[9px] font-bold px-1 py-0.5 rounded-sm">
                    -{{ $discountPercent }}%
                </span>
            @endif
            <img src="{{ $product->thumbnail ? storage_url($product->thumbnail) : asset('assets/frontend/images/placeholder.png') }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover"
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('assets/frontend/images/default.png') }}';">
        </div>
    </a>

    <div class="p-1.5 flex flex-col flex-1">
        <a href="{{ route('products.details', $product->slug) }}"
           class="block text-[11px] text-[#191919] leading-snug line-clamp-2 hover:text-[#F85606] transition-colors min-h-[2rem]">
            {{ $product->name }}
        </a>

        @if ($product->compare_price > 0 && $product->compare_price < $product->price)
            <div class="flex items-baseline gap-1 mt-1">
                <span class="text-xs font-bold text-[#D93025]">৳{{ number_format($product->compare_price) }}</span>
                <s class="text-[9px] text-[#767676]">৳{{ number_format($product->price) }}</s>
            </div>
        @else
            <span class="text-xs font-bold text-[#191919] mt-1 block">৳{{ number_format($product->price) }}</span>
        @endif
    </div>
</div>
@endif
