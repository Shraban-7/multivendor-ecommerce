@props([
    'section' => '',
    'products' => [],
    'slider' => true,
])

<section class="my-5">
    <div class="p-4 bg-white rounded">
        <div class="relative sec-heading">
            <h2 class="sm:text-xl md:text-2xl text-theme-dark">{{ $section }}</h2>
            <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2">
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block transition">
                    View All →
                </a>
            </span>
        </div>

        @if ($slider)
            <div class="swiper productCommonSwiper">
                <div class="swiper-wrapper">
                    @foreach ($products as $product)
                        @php
                            $basePrice = $product['price'];
                            $discountPrice = $product['compare_price'];
                        @endphp
                        <div class="swiper-slide group/product-card h-full">
                            <div class="flex flex-col w-full h-full p-2">
                                <div
                                    class="flex flex-col w-full h-full bg-white border border-gray-200 rounded-md hover:shadow-md transition duration-300 overflow-hidden">

                                    <div class="relative aspect-square bg-white overflow-hidden">
                                        <a href="{{ route('products.details', $product['slug']) }}"
                                            aria-label="View {{ $product['name'] }}">
                                            <img src="{{ $product['thumbnail'] }}"
                                                alt="{{ $product['slug'] }}" loading="lazy"
                                                class="w-full h-full object-cover object-top transition-transform duration-500 group-hover/product-card:scale-105" />
                                        </a>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex flex-col flex-1 px-3 py-3 sm:px-4 sm:py-4">
                                        @php
                                            $avgRating = $product['rating'] ?? 0;
                                            $fullStars = floor($avgRating);
                                            $halfStar = $avgRating - $fullStars >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        @endphp

                                        <!-- Rating -->
                                        @if ($avgRating > 0)
                                            <div class="flex items-center text-yellow-400 text-[11px] sm:text-xs mb-1">
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <i class="fa-solid fa-star"></i>
                                                @endfor
                                                @if ($halfStar)
                                                    <i class="fa-solid fa-star-half-stroke"></i>
                                                @endif
                                                @for ($i = 0; $i < $emptyStars; $i++)
                                                    <i class="fa-regular fa-star"></i>
                                                @endfor
                                                <span class="ml-1 text-gray-500 text-[10px] sm:text-xs">
                                                    ({{ $product['total_reviews'] }})
                                                </span>
                                            </div>
                                        @endif

                                        <div class="min-h-[2.5rem] sm:min-h-[2.75rem] mb-2">
                                            <p
                                                class="text-[13px] sm:text-sm font-medium text-gray-800 group-hover/product-card:text-primary transition-colors line-clamp-2 leading-snug">
                                                <a
                                                    href="{{ route('products.details', $product['slug']) }}?ref={{ auth()->user()->referral_code ?? '' }}">
                                                    {{ $product['name'] }}
                                                </a>
                                            </p>
                                        </div>

                                        <!-- Price + Cart -->
                                        <div class="flex items-center justify-between mt-auto">
                                            <!-- Price -->
                                            <div class="flex items-center gap-2">
                                                @if ($discountPrice !== null && $discountPrice !== 0 && $discountPrice < $basePrice)
                                                    <span class="font-semibold text-primary text-sm sm:text-base">
                                                        {{ money($discountPrice) }}
                                                    </span>
                                                    <span class="line-through text-gray-400 text-xs sm:text-sm">
                                                        {{ money($basePrice) }}
                                                    </span>
                                                @else
                                                    <span class="font-semibold text-primary text-sm sm:text-base">
                                                        {{ money($basePrice) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Add to Cart -->
                                            <a href="{{ route('products.details', $product['slug']) }}"
                                                class="w-6 h-6 sm:w-8 sm:h-8 rounded-md flex items-center justify-center 
                               bg-primary text-white hover:bg-orange-600 transition duration-200">
                                                <i class="fa-solid fa-cart-plus text-xs sm:text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-4">
                @foreach ($products as $product)
                    @php
                        $basePrice = $product['price'];
                        $discountPrice = $product['compare_price'];
                    @endphp
                    <div class="group/product-card h-full">
                        <div
                            class="flex flex-col w-full h-full bg-white border border-gray-200 rounded-md hover:shadow-md transition duration-300 overflow-hidden">

                            <div class="relative aspect-square bg-white overflow-hidden">
                                <a href="{{ route('products.details', $product['slug']) }}"
                                    aria-label="View {{ $product['name'] }}">
                                    <img src="{{ $product['thumbnail'] }}" alt="{{ $product['slug'] }}"
                                        loading="lazy"
                                        class="w-full h-full object-cover object-top transition-transform duration-500 group-hover/product-card:scale-105" />
                                </a>
                            </div>

                            <!-- Content -->
                            <div class="flex flex-col flex-1 px-3 py-3 sm:px-4 sm:py-4 space-y-2">
                                <!-- Rating -->
                                @if ($product['rating'] > 0)
                                    <div class="flex items-center text-yellow-400 text-[11px] sm:text-xs">
                                        @for ($i = 0; $i < floor($product['rating']); $i++)
                                            <i class="fa-solid fa-star"></i>
                                        @endfor
                                        @if ($product['rating'] - floor($product['rating']) >= 0.5)
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        @endif
                                        @for ($i = 0; $i < 5 - ceil($product['rating']); $i++)
                                            <i class="fa-regular fa-star"></i>
                                        @endfor
                                        <span class="ml-1 text-gray-500 text-[10px] sm:text-xs">
                                            ({{ $product['total_reviews'] }})
                                        </span>
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <p
                                        class="text-[13px] sm:text-sm font-medium text-gray-800 group-hover/product-card:text-primary transition-colors line-clamp-2 leading-snug">
                                        <a
                                            href="{{ route('products.details', $product['slug']) }}?ref={{ auth()->user()->referral_code ?? '' }}">
                                            {{ $product['name'] }}
                                        </a>
                                    </p>
                                </div>

                                <!-- Price + Cart -->
                                <div class="flex items-center justify-between mt-auto">
                                    <!-- Price -->
                                    <div class="flex items-center gap-2">
                                        @if ($discountPrice !== null && $discountPrice !== 0 && $discountPrice < $basePrice)
                                            <span class="font-semibold text-primary text-sm sm:text-base">
                                                {{ money($discountPrice) }}
                                            </span>
                                            <span class="line-through text-gray-400 text-xs sm:text-sm">
                                                {{ money($basePrice) }}
                                            </span>
                                        @else
                                            <span class="font-semibold text-primary text-sm sm:text-base">
                                                {{ money($basePrice) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Add to Cart -->
                                    <a href="{{ route('products.details', $product['slug']) }}"
                                        class="w-6 h-6 sm:w-8 sm:h-8 rounded-md flex items-center justify-center 
                           bg-primary text-white hover:bg-orange-600 transition duration-200">
                                        <i class="fa-solid fa-cart-plus text-xs sm:text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
