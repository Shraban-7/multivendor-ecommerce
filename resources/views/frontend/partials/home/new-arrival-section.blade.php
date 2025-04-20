 <section class="new-arrivals-section section-padding">
            <div class="container">
                <!-- Section Title -->
                <div class="relative sec-heading">
                    <h2 class="font-semibold uppercase sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                        New Arrivals
                    </h2>

                    <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- New Arrivals Products Slider -->
                <div class="mt-5 swiper productCommonSwiper md:mt-10">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        @foreach ($new_arrival_products as $product)
                            <div class="swiper-slide group/new-arriv-pro-card eq">
                                <div class="flex flex-col items-center block w-full p-2 product-card">
                                    <div
                                        class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                        <div class="h-32 pt-5 overflow-hidden item-img sm:h-40 md:h-52">
                                            <a href="{{ route('products.details', $product->slug) }}">
                                                <img class="object-contain w-full h-full"
                                                    src="{{ storage_url($product->thumbnail) }}"
                                                    alt="Exclusive Chair with foam seat" />
                                            </a>
                                        </div>
                                        <div class="p-2 space-y-1 item-info sm:p-4">
                                            <div class="text-xs rating-stars sm:text-sm text-light-yellow">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-regular fa-star"></i>
                                            </div>
                                            <div class="flex items-end justify-between">
                                                <div class="name-price">
                                                    <h2
                                                        class="w-full capitalize text-theme-dark group-hover/new-arriv-pro-card:text-butterfly-blue eq md:text-xl line-clamp-1">
                                                        <a
                                                            href="{{ route('products.details', $product->slug) }}">{{ $product->name }}</a>
                                                    </h2>
                                                    <div class="flex flex-wrap gap-x-2 sm:text-lg">
                                                        @php
                                                            if ($product->discount_type != null) {
                                                                if (
                                                                    $product->discount_type ==
                                                                    \App\Enums\DiscountType::FLAT
                                                                ) {
                                                                    $price =
                                                                        $product->selling_price -
                                                                        $product->discount_amount;
                                                                } elseif (
                                                                    $product->discount_type ==
                                                                    \App\Enums\DiscountType::PERCENTAGE
                                                                ) {
                                                                    $price =
                                                                        $product->selling_price -
                                                                        ($product->selling_price *
                                                                            $product->discount_amount) /
                                                                            100;
                                                                }
                                                            } else {
                                                                $price = $product->selling_price;
                                                            }
                                                        @endphp
                                                        <p class="font-medium new-price text-theme-teal">
                                                            {{ money($price) }}
                                                        </p>
                                                        <p class="line-through old-price text-jet-gray">
                                                            {{ money($product->selling_price) }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="add-cart">
                                                    <input type="hidden" name="quantity" value="1"
                                                        id="qtyInput{{ $product->id }}">
                                                    <button data-id="{{ $product->id }}" type="button"
                                                        class="flex items-center justify-center text-sm rounded cartBtn w-7 h-7 sm:w-10 sm:h-10 bg-primary text-theme-light sm:text-base hover:bg-light-yellow eq">
                                                        <span><i class="fa-solid fa-plus"></i></span>
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

                <!-- Become Sellers, Trending Products & Promo Poster -->
                <div class="flex flex-col gap-5 mt-10 promo-trending-products lg:flex-row">
                    <div class="flex flex-col w-full gap-5 trend-prods sm:flex-row lg:w-7/12 sm:h-96">
                        <!-- seller -->
                        <div class="w-full h-auto seller sm:h-full sm:w-1/2">
                            <div class="w-full h-full item-img">
                                <a href="{{ $promo_poster_one->link }}">
                                    <img src="{{ storage_url($promo_poster_one->image) }}"
                                        class="object-cover w-full h-full" alt="{{ storage_url($promo_poster_one->title) }}" />
                                </a>
                            </div>
                        </div>

                        <!-- trending -->
                        <div class="products h-auto sm:h-full sm:w-1/2 w-full bg-[#F8F8F8] rounded-lg">
                            <!-- Product Cards -->
                            <div class="p-5 trending-phones">
                                <h3 class="mb-4 text-lg font-semibold capitalize text-rangoon-green">
                                    Trending Products
                                    <span class="block w-28 h-[1.85px] bg-theme-teal"></span>
                                </h3>
                                <div class="space-y-4 trending-items-wrapper">
                                    <!-- item 1 -->
                                    @foreach ($trending_products as $product)
                                        <div
                                            class="flex gap-3 py-2 border-b border-dashed group/trending trending-item-card">
                                            <div class="w-1/4 item-image">
                                                <a href="{{ route('products.details', $product->slug) }}" target="_blank">
                                                    <img src="{{ storage_url($product->thumbnail) }}"
                                                        alt="Meatigo Premium Goat Curry"
                                                        class="object-contain w-full h-full group-hover/trending:rotate-12 eq" />
                                                </a>
                                            </div>
                                            <div class="flex flex-col w-3/4 gap-2 text-xs item-details">
                                                <h4>
                                                    <a href="{{ route('products.details', $product->slug) }}"
                                                        target="_self"
                                                        class="font-semibold text-theme-dark line-clamp-1 group-hover/trending:text-theme-teal eq">
                                                        {{ $product->name }}
                                                    </a>
                                                </h4>
                                                <p class="text-jet-gray">{{ $product->quantity }}
                                                    {{ $product->unit->name }}</p>
                                                <p class="font-semibold text-theme-teal">
                                                    {{ money($product->selling_price) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- promotional poster -->
                    <div class="w-full h-auto promotional-poster lg:w-5/12 sm:h-96">
                        <div class="w-full h-full overflow-hidden promo-img rounded-2xl">
                            <a href="{{ $promo_poster_two->link }}">
                                <img src="{{ storage_url($promo_poster_two->image) }}"
                                    class="object-cover w-full h-full sm:object-contain"
                                    alt="{{ $promo_poster_two->title }}" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
