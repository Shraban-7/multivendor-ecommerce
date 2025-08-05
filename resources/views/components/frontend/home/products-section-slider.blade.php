@props([
    'section' => '',
    'products' => [],
    'slider' => true,
])

<section class="section-padding">
    <div class="container">
        <div class="relative sec-heading">
            <h2 class="font-semibold uppercase sm:text-xl md:text-2xl text-theme-dark">
                {{ $section }}
            </h2>
            <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2">
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 mt-2 inline-block transition">
                    View All →
                </a>
            </span>
        </div>

        @if($slider)
        <div class="mt-5 swiper productCommonSwiper">
            <div class="swiper-wrapper">
                @foreach ($products as $product)
                <div class="swiper-slide group/product-card eq h-full">
                    <div class="flex flex-col items-center w-full p-2 product-card h-full">
                        <div class="w-full h-full bg-white border border-gray-200 rounded-xl hover:shadow-md transition duration-200 flex flex-col overflow-hidden">
                            <div class="bg-gray-50 relative pt-[100%] overflow-hidden">
                                <a href="{{ route('products.details', $product['slug']) }}" aria-label="View {{ $product['name'] }}">
                                    <img src="{{ storage_url($product['thumbnail']) }}"
                                        alt="{{ $product['slug'] }}"
                                        loading="lazy"
                                        class="absolute top-0 left-0 w-full h-full object-contain p-4 transition-transform duration-300 group-hover/product-card:scale-105" />
                                </a>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 flex flex-col justify-between px-3 py-3 sm:px-4 sm:py-4 space-y-2">
                                @php
                                $avgRating = $product['rating'] ?? 0;
                                $fullStars = floor($avgRating);
                                $halfStar = $avgRating - $fullStars >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                <!-- Rating -->
                                @if ($avgRating > 0)
                                <div class="flex items-center text-[11px] text-light-yellow">
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <i class="fa-solid fa-star"></i>
                                        @endfor
                                        @if ($halfStar)
                                        <i class="fa-solid fa-star-half-stroke"></i>
                                        @endif
                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <i class="fa-regular fa-star"></i>
                                            @endfor
                                            <span class="text-davy-gray ml-1 text-[10px] sm:text-xs">({{ $product['reviews_count'] ?? 0 }})</span>
                                </div>
                                @endif

                                <!-- Name + Price + Cart -->
                                <div class="flex items-end justify-between gap-2">
                                    <div class="flex-1">
                                        <!-- Name -->
                                        <h2 class="text-sm font-medium text-gray-800 group-hover/product-card:text-primary transition-colors line-clamp-2 leading-snug">
                                            <a href="{{ route('products.details', $product['slug']) }}">
                                                {{ $product['name'] }}
                                            </a>
                                        </h2>

                                        <!-- Price -->
                                        <div class="flex gap-2 items-center mt-1 text-sm sm:text-base">
                                            @if ($product['discounted_price'] !== null)
                                            <span class="font-semibold text-primary">{{ money($product['discounted_price']) }}</span>
                                            <span class="line-through text-gray-400 text-xs sm:text-sm">{{ money($product['price']) }}</span>
                                            @else
                                            <span class="font-semibold text-primary">{{ money($product['price']) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <div class="shrink-0">
                                        <button type="button"
                                            data-modal-target="quick-view-modal-{{ $product['id'] }}"
                                            data-modal-toggle="quick-view-modal-{{ $product['id'] }}"
                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-primary text-white hover:bg-light-yellow transition duration-200">
                                            <i class="fa-solid fa-plus text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach ($products as $product)
            <div class="group/product-card eq h-full">
                <div class="flex flex-col w-full h-full bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition duration-200 overflow-hidden">

                    <!-- Image with aspect ratio -->
                    <div class="relative pt-[100%] bg-gray-50 overflow-hidden">
                        <a href="{{ route('products.details', $product['slug']) }}" aria-label="View {{ $product['name'] }}">
                            <img src="{{ storage_url($product['thumbnail']) }}"
                                alt="{{ $product['slug'] }}"
                                loading="lazy"
                                class="absolute inset-0 w-full h-full object-contain p-3 transition-transform duration-300 group-hover/product-card:scale-105" />
                        </a>
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col justify-between flex-1 px-3 py-3 sm:px-4 sm:py-4 space-y-2">
                        @php
                        $avgRating = $product['rating'] ?? 0;
                        $fullStars = floor($avgRating);
                        $halfStar = $avgRating - $fullStars >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp

                        <!-- Rating -->
                        @if ($avgRating > 0)
                        <div class="flex items-center text-[11px] text-light-yellow">
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fa-solid fa-star"></i>
                                @endfor
                                @if ($halfStar)
                                <i class="fa-solid fa-star-half-stroke"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="fa-regular fa-star"></i>
                                    @endfor
                                    <span class="text-davy-gray ml-1 text-[10px] sm:text-xs">
                                        ({{ $product['reviews_count'] ?? 0 }})
                                    </span>
                        </div>
                        @endif

                        <!-- Name + Price + Cart -->
                        <div class="flex items-end justify-between gap-2">
                            <!-- Name & Price -->
                            <div class="flex-1">
                                <!-- Name (2 lines max) -->
                                <h2 class="text-sm font-medium text-gray-800 group-hover/product-card:text-primary transition-colors line-clamp-2 leading-snug">
                                    <a href="{{ route('products.details', $product['slug']) }}">
                                        {{ $product['name'] }}
                                    </a>
                                </h2>

                                <!-- Price -->
                                <div class="flex gap-2 items-center mt-1 text-sm sm:text-base">
                                    @if ($product['discounted_price'] !== null)
                                    <span class="font-semibold text-primary">{{ money($product['discounted_price']) }}</span>
                                    <span class="line-through text-gray-400 text-xs sm:text-sm">{{ money($product['price']) }}</span>
                                    @else
                                    <span class="font-semibold text-primary">{{ money($product['price']) }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            <div class="shrink-0">
                                <button type="button"
                                    data-modal-target="quick-view-modal-{{ $product['id'] }}"
                                    data-modal-toggle="quick-view-modal-{{ $product['id'] }}"
                                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-primary text-white hover:bg-light-yellow transition duration-200">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
        @endif

        @foreach ($products as $product)
        @include('frontend.partials.quick-view-modal', ['product' => $product])
        @endforeach
    </div>
</section>