@props(['product'])

<div class="group bg-ds-surface-base border border-ds-border-default rounded-sm overflow-hidden hover:shadow-card transition-shadow duration-200">
    <a href="{{ route('products.details', $product->slug) }}" class="block relative aspect-square bg-ds-surface-muted overflow-hidden">
        @php $discountPercent = $product->price > 0 && $product->compare_price > 0 ? round((($product->price - $product->compare_price) / $product->price) * 100) : 0; @endphp
        @if ($discountPercent > 0)
            <span class="absolute top-1.5 left-1.5 z-10 bg-ds-feedback-danger text-white text-[10px] font-bold px-1.5 py-0.5 rounded-xs">-{{ $discountPercent }}%</span>
        @endif
        <button type="button"
            class="wishlistBtn absolute top-1.5 right-1.5 z-10 w-7 h-7 flex items-center justify-center bg-white/80 rounded-full text-ds-text-secondary hover:bg-white hover:text-ds-feedback-danger transition-colors duration-100 shadow-sm"
            data-id="{{ $product->id }}"
            aria-label="Add to wishlist">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>
        <img src="{{ $product->thumbnail ? storage_url($product->thumbnail) : asset('assets/frontend/images/placeholder.png') }}"
             alt="{{ $product->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
             loading="lazy">
    </a>

    <div class="p-2.5">
        <a href="{{ route('products.details', $product->slug) }}" class="block text-xs text-ds-text-primary leading-snug line-clamp-2 hover:text-brand transition-colors duration-100 min-h-[2.5rem]">
            {{ $product->name }}
        </a>

        @if ($product->reviews_avg_rating ?? $product->avg_rating ?? 0)
            <div class="flex items-center gap-0.5 mt-1">
                <div class="flex text-ds-star">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($product->reviews_avg_rating ?? $product->avg_rating ?? 0))
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @else
                            <svg class="w-3 h-3 fill-ds-border-default" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endif
                    @endfor
                </div>
                <span class="text-[10px] text-ds-text-tertiary">({{ $product->reviews_count ?? $product->rating_count ?? 0 }})</span>
            </div>
        @endif

        <div class="flex items-baseline gap-1.5 mt-1.5">
            @if ($product->compare_price > 0)
                <span class="text-sm font-bold text-ds-feedback-danger">৳{{ number_format($product->compare_price) }}</span>
                <s class="text-[10px] text-ds-text-tertiary" aria-label="Original price ৳{{ number_format($product->price) }}">৳{{ number_format($product->price) }}</s>
            @else
                <span class="text-sm font-bold text-ds-text-primary">৳{{ number_format($product->price) }}</span>
            @endif
        </div>
    </div>
</div>
