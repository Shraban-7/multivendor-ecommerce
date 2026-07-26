@props(['product'])

<div class="group bg-white border border-[#E5E5E5] rounded-sm overflow-hidden hover:shadow-md eq">
    <a href="{{ route('products.details', $product->slug) }}" class="block relative aspect-square bg-[#F5F5F5] overflow-hidden">
        @php $discountPercent = $product->selling_price > 0 && $product->discounted_price > 0 ? round((($product->selling_price - $product->discounted_price) / $product->selling_price) * 100) : 0; @endphp
        @if ($discountPercent > 0)
            <span class="absolute top-1 left-1 z-10 discount-badge">-{{ $discountPercent }}%</span>
        @endif
        <img src="{{ $product->thumbnail ? storage_url($product->thumbnail) : asset('assets/frontend/images/placeholder.png') }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover group-hover:scale-105 eq"
             loading="lazy">
    </a>

    <div class="p-2 sm:p-3">
        <a href="{{ route('products.details', $product->slug) }}" class="block text-sm text-[#191919] leading-snug line-clamp-2 hover:text-[#F85606] eq min-h-[2.5rem]">
            {{ $product->name }}
        </a>

        @if ($product->reviews_avg_rating ?? $product->rating ?? 0)
            <div class="flex items-center gap-1 mt-1">
                <div class="flex text-[#FFA000] text-[10px]">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($product->reviews_avg_rating ?? $product->rating ?? 0))
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @else
                            <svg class="w-3 h-3 fill-[#E5E5E5]" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endif
                    @endfor
                </div>
                <span class="text-[10px] text-[#767676]">({{ $product->reviews_count ?? 0 }})</span>
            </div>
        @endif

        <div class="flex items-baseline gap-1.5 mt-1">
            @if ($product->discounted_price > 0)
                <span class="text-base font-bold text-[#F85606]">৳{{ number_format($product->discounted_price) }}</span>
                <s class="text-xs text-[#767676]" aria-label="Original price ৳{{ number_format($product->selling_price) }}">৳{{ number_format($product->selling_price) }}</s>
            @else
                <span class="text-base font-bold text-[#191919]">৳{{ number_format($product->selling_price) }}</span>
            @endif
        </div>
    </div>
</div>
