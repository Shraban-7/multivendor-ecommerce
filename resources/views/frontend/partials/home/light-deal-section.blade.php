<section class="light-deals-section">
    <!-- promotional header -->
    <div class="section-promo-header bg-[#FF4F4F]">
        <div class="container flex flex-col items-center justify-between gap-3 py-3 md:flex-row md:gap-0 md:py-5">
            <!-- star icon -->
            <span><svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M28 0C28.9506 15.0527 40.9472 27.0495 56 28C40.9472 28.9506 28.9506 40.9472 28 56C27.0495 40.9472 15.0527 28.9506 0 28C15.0527 27.0495 27.0495 15.0527 28 0Z"
                        fill="white" />
                </svg>
            </span>
            <!-- promo title -->
            <h2 class="flex flex-col items-center gap-2 text-3xl font-semibold md:flex-row md:gap-5 text-theme-light">
                <p>
                    <span><i class="fa-solid fa-bolt"></i></span>
                    Light deals
                </p>
                <p class="text-base font-medium">
                    Limited Time Offer
                    <span class="text-xs"><i class="fa-solid fa-chevron-right"></i></span>
                </p>
            </h2>
            <!-- star icon -->
            <span><svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M28 0C28.9506 15.0527 40.9472 27.0495 56 28C40.9472 28.9506 28.9506 40.9472 28 56C27.0495 40.9472 15.0527 28.9506 0 28C15.0527 27.0495 27.0495 15.0527 28 0Z"
                        fill="white" />
                </svg>
            </span>
        </div>
    </div>

    <!-- light deals swiper carousel -->
    <div class="container">
        <div class="swiper lightDealsSwiper">
            <div class="swiper-wrapper">
                <!-- slide 1 -->
                @foreach ($light_deals as $light_deal)
                    <div class="px-1 py-5 swiper-slide">
                        <a href="{{ route('products.details', $light_deal->slug) }}"
                            class="block w-full p-3 rounded-lg product-card hover:shadow-lg eq group">
                            <!-- slide image -->
                            <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                <img src="{{ storage_url($light_deal->thumbnail) }}" alt="{{ $light_deal->slug }}"
                                    class="object-cover w-full h-full group-hover:scale-125 eq" loading="lazy" />
                                @if ($light_deal->stock_in - $light_deal->stock_out <= $light_deal->low_stock_quantity)
                                    <span
                                        class="absolute block w-3/5 px-4 py-3 text-sm text-center -translate-x-1/2 bg-white rounded-full bottom-9 left-1/2">Almost
                                        Sold Out</span>
                                @endif
                            </div>
                            <!-- Slide Content -->
                            <div class="mt-2 space-y-1 card-content">
                                <!-- price & sold info -->
                                <div class="flex items-center gap-2 price-sold-amount">
                                    <h2 class="text-2xl font-bold text-primary">
                                        <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                        <span class="align-middle text-xs text-[#ffa755]">{{ CURRENCY_SYMBOL }}</span>
                                        {{ number_format($light_deal->selling_price, 2) }}
                                    </h2>
                                    @if ($light_deal->stock_out > 0)
                                        <p class="text-base">{{ number_shorten_format($light_deal->stock_out) }}+ Sold
                                            Out</p>
                                    @endif
                                </div>
                                <!-- time -->
                                <?php
                                $total_stock = $light_deal->stock_in + $light_deal->stock_out;
                                $sold_out_progress = $total_stock > 0 ? ($light_deal->stock_out / $total_stock) * 100 : 0;
                                ?>

                                <!-- Progress bar and time (side by side) -->
                                <div class="flex items-center justify-between gap-3 mt-2">
                                    <!-- Progress Bar -->
                                    <div class="flex-1 bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                        <div class="h-2.5 bg-primary transition-all duration-300 ease-in-out"
                                            style="width: {{ round($sold_out_progress, 2) }}%"></div>
                                    </div>

                                    <!-- Time -->
                                    <div class="flex items-center text-sm text-gray-700 whitespace-nowrap">
                                        <i class="fa-regular fa-clock me-1 text-primary"></i>
                                        <span class="countdown-timer"
                                            data-end-time="{{ $light_deal->campaign_end_date }}">
                                            Loading...
                                        </span>
                                    </div>

                                </div>

                                <!-- rating -->
                                <div class="flex items-center gap-2">
                                    <?php
                                    $avgRating = round($light_deal->reviews_avg_rating, 1);
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
                                    <span class="text-sm text-primary">Final Hours</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

