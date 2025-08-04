<section class="new-arrivals-section section-padding">
    <div class="container">
        <div class="relative sec-heading">
            <h2 class="font-semibold uppercase sm:text-xl md:text-2xl text-theme-dark">
                {{ $section }}
            </h2>

            <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2">
                <a href="#" class="theme-btn theme-outline-btn">View All</a>
            </span>
        </div>

        {{--<div class="mt-5 swiper productCommonSwiper">
            <div class="swiper-wrapper">
                @foreach ($products as $product)
                <div class="swiper-slide group/community-pro-card eq h-full">
                    <div class="flex flex-col items-center w-full p-2 product-card h-full">
                        <div
                            class="w-full h-full border border-jet-gray/30 rounded-md hover:shadow-md eq overflow-hidden flex flex-col">
                            <div
                                class="h-52 px-4 pt-6 pb-4 overflow-hidden item-img flex items-center justify-center">
                                <a href="{{ route('products.details', $product['slug']) }}">
        <img class="object-contain w-full h-full"
            src="{{ storage_url($product['thumbnail']) }}" alt="{{ $product['slug'] }}"
            loading="lazy" />
        </a>
    </div>

    <div class="flex-1 flex flex-col justify-between p-3 space-y-2 sm:p-4">
        @php
        $avgRating = $product['rating'] ?? 0;
        $fullStars = floor($avgRating);
        $halfStar = $avgRating - $fullStars >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        @endphp

        @if ($avgRating > 0)
        <div class="text-xs sm:text-sm text-light-yellow rating-stars">
            @for ($i = 0; $i < $fullStars; $i++)
                <i class="fa-solid fa-star"></i>
                @endfor
                @if ($halfStar)
                <i class="fa-solid fa-star-half-stroke"></i>
                @endif
                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="fa-regular fa-star"></i>
                    @endfor
                    <span
                        class="text-davy-gray ml-1 text-[10px] sm:text-xs">({{ $product['reviews_count'] ?? 0 }})</span>
        </div>
        @endif

        <!-- Name + Price -->
        <div class="flex items-end justify-between gap-2">
            <div class="name-price w-full">
                <h2
                    class="text-sm font-medium text-theme-dark group-hover/community-pro-card:text-butterfly-blue line-clamp-1 leading-snug">
                    <a
                        href="{{ route('products.details', $product['slug']) }}">{{ $product['name'] }}</a>
                </h2>

                @if ($product['discounted_price'] !== null)
                <div class="flex gap-x-2 text-nowrap text-sm sm:text-base">
                    <p class="font-semibold text-primary">
                        {{ money($product['discounted_price']) }}
                    </p>
                    <small class="line-through text-jet-gray align-items-end">
                        {{ money($product['price']) }}
                    </small>
                </div>
                @else
                <div class="flex gap-x-2 text-nowrap text-sm sm:text-base">
                    <p class="font-semibold text-primary">
                        {{ money($product['price']) }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Add to Cart -->
            <div class="shrink-0">
                <button type="button"
                    data-modal-target="quick-view-modal-{{ $product['id'] }}"
                    data-modal-toggle="quick-view-modal-{{ $product['id'] }}"
                    class="flex items-center justify-center text-sm rounded w-8 h-8 sm:w-10 sm:h-10 bg-primary text-theme-light hover:bg-light-yellow eq">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    @endforeach
    </div>
    </div> --}}

    <div class="mt-5 swiper productCommonSwiper">
        <div class="swiper-wrapper">
            @foreach ($products as $product)
            <div class="swiper-slide group/product-card eq h-full">
                <div class="flex flex-col items-center w-full p-2 product-card h-full">
                    <div class="w-full h-full bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition duration-200 flex flex-col overflow-hidden">

                        <!-- Image with aspect ratio box -->
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
    
    @foreach ($products as $product)
    @include('frontend.partials.quick-view-modal', ['product' => $product])
    @endforeach
    </div>
</section>