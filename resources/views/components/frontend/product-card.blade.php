<div
    class="product-card bg-white rounded-lg border border-gray-100 hover:border-primary-500 hover:shadow-lg transition-all duration-300 group overflow-hidden flex flex-col h-full relative">
    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
        <span class="bg-primary-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">SALE</span>
    </div>

    <div
        class="product-image-container h-56 w-full border-b border-gray-50 relative bg-white flex items-center justify-center overflow-hidden">
        <img src="{{ storage_url($product->thumbnail) }}"
            class="object-fit mix-blend-multiply group-hover:scale-110 transition duration-500">

        <!-- Hover Actions (Grid) -->
        <div
            class="grid-hover-actions absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2 backdrop-blur-[1px]">
            <button class="btn-quickview w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg
                    flex items-center justify-center hover:bg-primary-600 hover:text-white
                    transform translate-y-4 group-hover:translate-y-0 transition delay-75"
                data-slug="{{ $product->slug }}">
                <i class="far fa-eye icon"></i>
                <span
                    class="spinner hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin">
                </span>
            </button>

            <button data-id="{{ $product->id }}"
                class="wishlistBtn w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-red-500 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-100">
                <i class="far fa-heart"></i>
            </button>
        </div>
    </div>

    <div class="px-3 py-2 flex flex-col flex-1">
        <a href="{{ route('products.details', $product->slug) }}">
            <h3
                class="text-[14px] sm:text-[15px] font-medium text-gray-800 truncate mb-2 hover:text-primary-600 transition cursor-pointer">
                {{ $product->name }}
            </h3>
        </a>

        <div class="flex items-center gap-1 mb-2">
            <div class="flex text-yellow-400 text-[10px] sm:text-xs">
                @php
                    $avg = $product->avg_rating ?? 0;
                    $fullStars = floor($avg);
                    $halfStar = $avg - $fullStars >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - ($fullStars + $halfStar);
                @endphp

                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star"></i>
                @endfor

                @if ($halfStar)
                    <i class="fas fa-star-half-alt"></i>
                @endif
                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star"></i>
                @endfor
            </div>

            <span class="text-[10px] text-gray-400">({{ $product->rating_count }})</span>
        </div>

        <!-- List Description (Hidden in Grid Mode) -->
        <p class="list-view-desc hidden text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed"></p>

        <div class="mt-auto pt-2 flex items-end justify-between">
            <div class="flex items-baseline gap-2">
                @if ($product->discounted_price)
                    <span
                        class="text-[11px] text-gray-400 line-through">{{ money($product->selling_price) }}</span>
                    <span
                        class="text-primary-600 font-semibold text-[15px] sm:text-[16px]">{{ money($product->discounted_price) }}</span>
                @else
                    <span
                        class="text-primary-600 font-semibold text-[15px] sm:text-[16px]">{{ money($product->selling_price) }}</span>
                @endif
            </div>

            <!-- Grid Button -->
            <button
                class="grid-view-btn w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center hover:bg-primary-600 hover:text-white transition shadow-sm">
                <i class="fas fa-plus text-xs"></i>
            </button>

            <!-- List Buttons -->
            <div class="list-view-btns hidden flex gap-2">
                <button
                    class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center hover:border-red-300 hover:bg-red-50 hover:text-red-500 transition"><i
                        class="far fa-heart"></i></button>
                <button
                    class="px-4 py-2 bg-primary-600 text-white text-xs font-bold rounded-lg hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition flex items-center gap-2">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

@if(isset($oldProduct))
<div
    class="product-card bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col h-full relative">
    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
        <span class="bg-primary-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">SALE</span>
    </div>
    <div
        class="product-image-container h-48 w-full border-b border-gray-50 relative bg-white p-4 flex items-center justify-center overflow-hidden">
        <img src="{{ storage_url($product->thumbnail) }}"
            class="max-h-full max-w-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">
        <!-- Hover Actions (Grid) -->
        <div
            class="grid-hover-actions absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2 backdrop-blur-[1px]">
            <button class="btn-quickview w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg
                    flex items-center justify-center hover:bg-primary-600 hover:text-white
                    transform translate-y-4 group-hover:translate-y-0 transition delay-75"
                data-slug="{{ $product->slug }}">

                <i class="far fa-eye icon"></i>
                <span class="spinner hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin">
                </span>
            </button>

            <button data-id="{{ $product->id }}"
                class="wishlistBtn w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-red-500 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-100"><i
                    class="far fa-heart"></i></button>
        </div>
    </div>
    <div class="p-3 sm:p-4 flex flex-col flex-1">
        {{--<span class="text-[10px] text-gray-400 uppercase tracking-wide mb-1 font-medium">{{ $product->category->name }}</span>--}}
        <a href="{{ route('products.details', $product->slug) }}">
            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 hover:text-primary-600 transition cursor-pointer">
                {{ $product->name }}
            </h3>
        </a>
        <div class="flex items-center gap-1 mb-2">
            <div class="flex text-yellow-400 text-[10px] sm:text-xs">
                @php
                    $avg = $product->avg_rating ?? 0;
                    $fullStars = floor($avg);
                    $halfStar = $avg - $fullStars >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - ($fullStars + $halfStar);
                @endphp

                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star"></i>
                @endfor

                @if ($halfStar)
                    <i class="fas fa-star-half-alt"></i>
                @endif
                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star"></i>
                @endfor

            </div>

            <span class="text-[10px] text-gray-400">({{ $product->rating_count }})</span>
        </div>
        <!-- List View Desc -->
        <p class="list-view-desc hidden text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed">

        </p>
        <div class="mt-auto pt-2 flex items-end justify-between">
            <div class="flex flex-col">
                @if ($product->discounted_price)
                    <span
                        class="text-[10px] sm:text-xs text-gray-400 line-through">{{ money($product->selling_price) }}</span>
                    <span
                        class="text-primary-600 font-bold text-base sm:text-lg">{{ money($product->discounted_price) }}</span>
                @else
                    <span
                        class="text-primary-600 font-bold text-base sm:text-lg">{{ money($product->selling_price) }}</span>
                @endif
            </div>
            <!-- Grid Button -->
            <button
                class="grid-view-btn w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center hover:bg-primary-600 hover:text-white transition shadow-sm">
                <i class="fas fa-plus text-xs"></i>
            </button>
            <!-- List Buttons -->
            <div class="list-view-btns hidden flex gap-2">
                <button
                    class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center hover:border-red-300 hover:bg-red-50 hover:text-red-500 transition"><i
                        class="far fa-heart"></i></button>
                <button
                    class="px-4 py-2 bg-primary-600 text-white text-xs font-bold rounded-lg hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition flex items-center gap-2">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>
@endif
