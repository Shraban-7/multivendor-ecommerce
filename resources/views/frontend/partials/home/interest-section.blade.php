<section class="interest-section section-padding">
            <div class="container">
                <!-- section title -->
                <div class="relative sec-heading">
                    <h2
                        class="font-semibold uppercase md:text-center sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                        Explore your Interest
                    </h2>

                    <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- Interest categores swiper carousel -->
                <div class="mt-10 swiper categoriesSwiper md:mt-16">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        @foreach ($categories as $category)
                            <div class="swiper-slide group/categores eq">
                                <a href="{{ route('category.details', $category->slug) }}" class="flex flex-col items-center block w-full product-card">
                                    <!-- slide image -->
                                    <div class="relative w-16 h-16 card-image lg:h-28 lg:w-28 md:w-24 md:h-24">
                                        <img src="{{ storage_url($category->image) }}" alt="Grocery"
                                            class="object-contain w-full h-full" />
                                    </div>
                                    <!-- Slide Content -->
                                    <div class="mt-3 card-content lg:mt-5">
                                        <a href="#"
                                            class="block text-sm font-medium text-center text-black group-hover/categores:text-light-yellow md:text-lg lg:text-xl eq">{{ $category->name }}</a>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Interest Products -->
                <div class="mt-10 swiper fiveSlideSwiper md:mt-20">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        @foreach ($interest_products as $product)
                            <div class="swiper-slide group/interest-pro-card eq">
                                <div class="flex flex-col items-center block w-full p-2 product-card">
                                    <div class="w-full overflow-hidden rounded-md bg-theme-light hover:shadow-md eq">
                                        <div class="h-32 px-10 pt-5 overflow-hidden item-img sm:h-40 md:h-52">
                                            <a href="{{ route('products.details', $product->slug) }}">
                                                <img class="object-contain w-full h-full"
                                                    src="{{ storage_url($product->thumbnail) }}"
                                                    alt="{{ $product->slug }}" loading="lazy"/>
                                            </a>
                                        </div>
                                        <div class="p-2 space-y-1 sm:p-4">
                                            <h2
                                                class="h-16 text-sm font-semibold text-theme-dark group-hover/interest-pro-card:text-persian-blue line-clamp-3 md:line-clamp-2 eq md:text-base md:h-12">
                                                <a
                                                    href="{{ route('products.details', $product->slug) }}">{{ $product->name }}</a>
                                            </h2>
                                            <?php
                                            $avgRating = round($product->reviews_avg_rating, 1);
                                            $fullStars = floor($avgRating);
                                            $halfStar = $avgRating - $fullStars >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                            ?>

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
                                            <p class="text-persian-blue">{{ $product->quantity }}
                                                {{ $product->unit->name }}</p>
                                            <p class="font-semibold text-sand-brown">
                                                {{ money($product->selling_price) }}</p>

                                            <div class="add-cart">
                                                <input type="hidden" name="quantity" value="1"
                                                    id="qtyInput{{ $product->id }}">
                                                <button data-id="{{ $product->id }}" type="button"
                                                    class="flex items-center justify-between block w-full h-10 p-2 mt-2 bg-white rounded-full cartBtn hover:shadow-md eq">
                                                    <span
                                                        class="inline-flex items-center justify-center w-6 h-6 text-xs text-white rounded-full sm:w-8 sm:h-8 bg-primary md:text-sm">
                                                        <i class="fa-solid fa-cart-plus"></i>
                                                    </span>
                                                    <span class="text-sm md:text-base">Add</span>
                                                    <span
                                                        class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-[#F9F8F6] text-sand-brown text-xs sm:text-sm">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </span>
                                                </button>
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
