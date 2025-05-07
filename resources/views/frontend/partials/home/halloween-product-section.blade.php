<section class="halloween-product-section section-padding">
            <div class="container">
                <!-- Section Title -->
                <div class="relative sec-heading">
                    <h2 class="font-semibold uppercase sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                        {{ $special_category->name }}
                    </h2>

                    <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- Community Product Products Slider -->
                <div class="mt-5 swiper productCommonSwiper md:mt-10">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        @foreach ($special_category->products as $product)
                            <div class="swiper-slide group/community-pro-card eq">
                                <div class="flex flex-col items-center block w-full p-2 product-card">
                                    <div
                                        class="w-full border-1 border-jet-gray/30 rounded-md hover:shadow-md eq overflow-hidden">
                                        <div
                                            class="h-32 px-3 pt-5 pb-3 overflow-hidden item-img sm:h-40 md:h-52 md:pt-10 md:px-5 md:pb-5">
                                            <a href="{{ route('products.details', $product->slug) }}">
                                                <img class="object-contain w-full h-full"
                                                    src="{{ storage_url($product->thumbnail) }}"
                                                    alt="{{ $product->slug }}" />
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
                                                        class="w-full text-sm capitalize text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq line-clamp-2">
                                                        <a
                                                            href="{{ route('products.details', $product->slug) }}">{{ $product->name }}</a>
                                                    </h2>
                                                    <div class="flex flex-wrap gap-x-2 sm:text-lg">
                                                        <p class="font-medium new-price text-theme-teal">
                                                            {{ money($product->discounted_price) }}
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
            </div>
        </section>
