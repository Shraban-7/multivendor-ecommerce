<section class="community-product-section section-padding">
    <div class="container">
        <!-- Section Title -->
        <div class="relative sec-heading">
            <h2 class="font-semibold uppercase sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                Community Product
            </h2>

            <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2"><a href="#"
                    class="theme-btn theme-outline-btn">View All</a></span>
        </div>

        <!-- Community Product Products Slider -->
        <div class="mt-5 swiper productCommonSwiper md:mt-10">
            <div class="swiper-wrapper">
                <!-- slide 1 -->
                @foreach ($community_products as $product)
                    <div class="swiper-slide group/community-pro-card eq">
                        <div class="flex flex-col items-center w-full p-2 product-card">
                            <div
                                class="w-full h-full border border-jet-gray/30 rounded-md hover:shadow-md eq overflow-hidden flex flex-col">
                                <!-- Image Section -->
                                <div
                                    class="h-52 px-4 pt-6 pb-4 overflow-hidden item-img flex items-center justify-center">
                                    <a href="{{ route('products.details', $product->slug) }}">
                                        <img class="object-contain w-full h-full"
                                            src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->slug }}"
                                            loading="lazy" />
                                    </a>
                                </div>

                                <!-- Content Section -->
                                <div class="flex-1 flex flex-col justify-between p-3 space-y-2 sm:p-4">
                                    <!-- Ratings -->
                                    <?php
                                    $avgRating = round($product->reviews_avg_rating, 1);
                                    $fullStars = floor($avgRating);
                                    $halfStar = $avgRating - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    ?>

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
                                        </div>
                                    @endif

                                    <!-- Name & Price -->
                                    <div class="flex items-end justify-between gap-2">
                                        <div class="name-price w-full">
                                            <!-- Product Name (no wrap, ellipsis) -->
                                            <h2
                                                class="text-sm font-medium text-theme-dark group-hover/community-pro-card:text-butterfly-blue line-clamp-1 leading-snug">
                                                <a
                                                    href="{{ route('products.details', $product->slug) }}">{{ $product->name }}</a>
                                            </h2>

                                            <!-- Prices (no wrap) -->
                                            @if ($product->discounted_price !== null)
                                                <div class="flex gap-x-2 text-nowrap text-sm sm:text-base">
                                                    <p class="font-semibold  text-primary">
                                                        {{ money($product->discounted_price) }}
                                                    </p>
                                                    <small class="line-through text-jet-gray align-items-end">
                                                        {{ money($product->selling_price) }}
                                                    </small>
                                                </div>
                                            @else
                                                <div class="flex gap-x-2 text-nowrap text-sm sm:text-base">
                                                    <p class="font-semibold  text-primary">
                                                        {{ money($product->selling_price) }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Add to Cart -->
                                        <div class="shrink-0">
                                            <input type="hidden" name="quantity" value="1"
                                                id="qtyInput{{ $product->id }}">
                                            <button data-id="{{ $product->id }}" type="button"
                                                class="flex items-center justify-center text-sm rounded cartBtn w-8 h-8 sm:w-10 sm:h-10 bg-primary text-theme-light hover:bg-light-yellow eq">
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
        </div>
    </div>
</section>
