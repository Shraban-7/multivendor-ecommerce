<div class="group relative rounded-md bg-white shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col">

    <!-- Wishlist -->
    <button type="button"
        class="absolute top-3 left-3 z-10 text-gray-400 hover:text-red-500 transition duration-200">
        <i class="fa-regular fa-heart text-lg"></i>
    </button>

    @php
    $original_price = $product['price'];
    $compare_price = $product['compare_price'];
    $discountPercent = ($compare_price && $original_price > 0) ? round((($original_price - $compare_price) / $original_price) * 100) : null;
    @endphp

    <!-- Discount Badge -->
    @if ($discountPercent > 0)
    <span
        class="absolute top-3 right-3 z-10 bg-primary text-white text-[11px] px-2 py-0.5 rounded-full shadow-sm">
        -{{ $discountPercent }}%
    </span>
    @endif

    <!-- Product Image -->
    <div class="relative aspect-square overflow-hidden">
        <a href="{{ route('products.details', $product['slug']) }}">
            <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}"
                class="w-full h-full object-cover object-top 
                    transition-transform duration-500 group-hover:scale-105" />
        </a>
    </div>

    <!-- Product Info -->
    <div class="p-3 flex flex-col">
        <!-- Name -->
        <h3 class="text-sm font-medium text-gray-700 line-clamp-2 leading-snug mb-1">
            <a href="{{ route('products.details', $product['slug']) }}"
                class="hover:text-primary transition-colors duration-200">
                {{ $product['name'] }}
            </a>
        </h3>

        <!-- Price -->
        <div class="flex items-center gap-2 mb-1">
            @if ($compare_price != null && $compare_price > 0)
            <span class="text-primary font-semibold text-base">
                {{ money($compare_price) }}
            </span>
            <span class="text-xs text-gray-400 line-through">
                {{ money($original_price) }}
            </span>
            @else
            <span class="text-primary font-semibold text-base">
                {{ money($original_price) }}
            </span>
            @endif
        </div>

        <!-- Rating + Add to Cart -->
        <div class="flex items-center justify-between">
            @if ($product['rating'] > 0)
            <div class="flex items-center gap-0.5 text-yellow-400 text-xs">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa-solid fa-star {{ $i <= floor($product['rating']) ? '' : 'text-gray-300' }}"></i>
                    @endfor
            </div>
            @endif

            @auth
            <button type="button" data-id="{{ $product['id'] }}"
                class="addToCartNoVariant w-8 h-8 flex items-center justify-center bg-primary hover:bg-theme-dark text-white text-xs rounded-full transition duration-200">
                <i class="fa-solid fa-cart-plus icon"></i>
                <span class="spinner hidden w-3 h-3 border-2 border-current border-t-transparent rounded-full animate-spin"></span>
            </button>
            @endauth
        </div>
    </div>
</div>