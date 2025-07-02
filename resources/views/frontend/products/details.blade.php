@extends('frontend.layouts.app')
@section('title', $product['name'])

@section('content')
    <main class="product-details-page">
        <section class="container page-breadcrumb-links">
            <!-- Page Breadcrumb -->
            <nav class="container flex my-7" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 rtl:rotate-180 text-davy-gray" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#"
                                class="text-sm ms-1 text-davy-gray hover:text-primary eq md:ms-2">{{ $product['category'] }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 rtl:rotate-180 text-davy-gray" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="text-sm ms-1 text-davy-gray md:ms-2">{{ $product['subcategory'] }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Product Main Section -->
        <section class="product-main-sec">
            <div class="container">
                <!-- Product Contents  -->
                <div class="flex flex-col gap-5 md:flex-row">
                    <!-- Product Images Section -->
                    <div class="lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5">
                        <!-- Thumbnails -->
                        <div class="order-2 w-full space-y-3 lg:w-1/6 lg:order-1">
                            <div class="single-product-thumbnails overflow-hidden xl:h-[37rem] lg:h-[41rem] h-auto">
                                <div class="swiper-wrapper">
                                    @foreach ($product['slider'] as $img)
                                        <div class="swiper-slide">
                                            <div
                                                class="slide-thumb w-full xl:h-24 md:h-22 lg:h-28 h-20 rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary-500 overflow-hidden">
                                                <img src="{{ storage_url($img) }}"
                                                    alt="{{ $product->name ?? 'Product Image' }}"
                                                    class="w-full h-full object-cover" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Main Image Slider -->
                        <div class="relative order-1 w-full lg:w-5/6 lg:order-2">
                            <div
                                class="single-product-swiper overflow-hidden w-full h-96 md:h-[37rem] xl:h-[37rem] lg:h-[41rem] rounded-2xl overflow-hidden relative">
                                <div class="swiper-wrapper">
                                    @foreach ($product['slider'] as $img)
                                        <div class="swiper-slide main h-full aspect-[4/3] rounded-2xl overflow-hidden">
                                            <img src="{{ storage_url($img) }}"
                                                alt="{{ $product['name'] ?? 'Product Image' }}"
                                                class="w-full h-full object-cover" />
                                        </div>
                                    @endforeach

                                </div>
                                <!-- Navigation Buttons -->
                                <div class="swiper-button-prev text-theme-light"></div>
                                <div class="swiper-button-next text-theme-light"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Section -->
                    <div class="lg:w-[45%] md:w-[50%] w-full md:px-2 xl:px-3">
                        <div class="w-full space-y-2">
                            <!-- Free Shipping Banner -->
                            <div
                                class="flex flex-col xsm:flex-row flex-wrap items-center justify-between gap-2 px-4 py-3 text-sm lg:text-base bg-[#FEEFE1] text-rustic-red">
                                <div class="flex items-center gap-2 text-center">
                                    <i class="fa-solid fa-check text-theme-teal"></i>
                                    <span>Free shipping special for you</span>
                                </div>
                                <span class="font-light text-jet-gray">Exclusive offer</span>
                            </div>
                            <h1 class="text-sm lg:text-base text-rustic-red lg:pr-5 xl:pr-16">
                                {{ $product['name'] }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-2 text-sm xsm:gap-5 sm:10 md:gap-2 lg:gap-10">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-2 text-davy-gray">
                                        <span>Seller: </span>
                                        <a href="{{ route('sellers.shop', $seller['username']) }}" class="inline-block">
                                            <span class="text-blue-500 font-bold">{{ $seller['shop_name'] }}</span>
                                        </a>
                                        <span class="border-r border-gray-400 h-4"></span>

                                        @if ($product['sold_out'] > 0)
                                            <span class="pl-2 text-jet-gray">
                                                {{ number_shorten_format($product['sold_out']) }} sold
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs">{{ $product['rating'] }} Star</span>
                                    <span class="flex text-yellow-400 text-sm">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product['rating']))
                                                ★
                                            @elseif ($i - $product['rating'] < 1)
                                                <span class="relative -mx-0.5">★<span
                                                        class="absolute inset-0 overflow-hidden"
                                                        style="width: 50%">★</span></span>
                                            @else
                                                <span class="text-gray-300">★</span>
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @if ($product['seller']['best_seller'])
                                    <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best
                                        Seller</span>
                                @endif

                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                @php
                                    $hasVariants =
                                        isset($product['variants']) &&
                                        is_array($product['variants']) &&
                                        count($product['variants']) > 0;
                                    $firstVariant = $hasVariants ? $product['variants'][0] : null;

                                    $variantDiscountedPrice = $firstVariant['discounted_price'] ?? null;
                                    $variantPrice = $firstVariant['price'] ?? null;

                                    $showVariantDiscount =
                                        $variantDiscountedPrice !== null && $variantDiscountedPrice < $variantPrice;
                                    $showProductDiscount =
                                        $product['discounted_price'] !== null &&
                                        $product['discounted_price'] < $product['price'];
                                @endphp

                                @if ($showVariantDiscount)
                                    <div class="flex flex-no-wrap items-center gap-1 new-price">
                                        <h3 id="discounted_price"
                                            class="font-bold current-price text-primary product-price">
                                            {{ money($variantDiscountedPrice) }}
                                        </h3>
                                    </div>
                                    <h6 id="price" class="line-through text-jet-gray">
                                        {{ money($variantPrice) }}
                                    </h6>
                                @elseif ($showProductDiscount)
                                    <div class="flex flex-no-wrap items-center gap-1 new-price">
                                        <h3 id="discounted_price"
                                            class="font-bold current-price text-primary product-price">
                                            {{ money($product['discounted_price']) }}
                                        </h3>
                                    </div>
                                    <h6 id="price" class="line-through text-jet-gray">
                                        {{ money($product['price']) }}
                                    </h6>
                                @else
                                    <div class="flex flex-no-wrap items-center gap-1 new-price">
                                        <h3 id="price" class="font-bold current-price text-primary product-price">
                                            {{ money($product['price']) }}
                                        </h3>
                                    </div>
                                @endif

                                @if ($product['almost_sold_out'])
                                    <span class="text-xs text-leaf-green">Almost Sold Out</span>
                                @endif
                            </div>
                        </div>

                        <div
                            class="w-full mt-5 overflow-hidden border-2 rounded-lg user-action border-primary xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <!-- Special Sale Banner -->
                            <div
                                class="flex items-center justify-between px-4 py-1 text-sm text-white bg-primary md:text-base">
                                <span>Special Sale | Two Days Left</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>

                            <div class="px-4 py-2 clr-size-qty">
                                <div id="product-attributes">
                                    <input type="hidden" id="productBasePrice{{ $product['id'] }}"
                                        value="{{ $product['price'] }}">
                                    <input type="hidden" id="productDiscountedPrice{{ $product['id'] }}"
                                        value="{{ $product['discounted_price'] }}">

                                    <form id="variantForm" data-slug="{{ $product['slug'] ?? '' }}"
                                        class="flex flex-wrap flex-col" data-id="{{ $product['id'] ?? '' }}">

                                        @php
                                            $defaultVariant = collect($product['variants'])->firstWhere(
                                                'stock',
                                                '>',
                                                0,
                                            );
                                            $defaultValueIds = $defaultVariant['value_ids'] ?? [];
                                        @endphp

                                        @foreach ($product['options'] as $option)
                                            <div class="mb-6" data-option-id="{{ $option['id'] }}">
                                                <h6 class="text-davy-gray text-base sm:text-lg font-medium mb-2">
                                                    {{ $option['name'] }}:</h6>

                                                <div class="flex flex-wrap gap-3">
                                                    @foreach ($option['values'] as $value)
                                                        @php
                                                            $isActive = in_array($value['id'], $defaultValueIds);
                                                        @endphp

                                                        <button type="button"
                                                            class="option-value-btn px-4 py-2 text-sm border rounded-md transition-all duration-200
        {{ $isActive ? 'bg-primary text-white border-primary' : 'bg-white text-gray-800 border-gray-300 hover:bg-primary/90' }}"
                                                            data-option-id="{{ $option['id'] }}"
                                                            data-value-id="{{ $value['id'] }}">
                                                            {{ $value['value'] }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach


                                        <div class="alert alert-info d-none mt-4" id="variantInfo">
                                            <p><strong>SKU:</strong> <span id="sku"></span></p>
                                            {{-- <p><strong>Price:</strong> $<span id="price"></span></p> --}}
                                            <p><strong>Stock:</strong> <span id="stock"></span></p>
                                        </div>

                                        <div class="alert alert-danger d-none mt-4" id="variantNotFound">
                                            Variant not found for selected options.
                                        </div>

                                    </form>

                                </div>

                                <!-- Quantity -->
                                <div class="quantity mt-3">
                                    <div class="text-davy-gray flex items-center gap-2">
                                        <h6 class="sm:text-lg">Quantity :</h6>
                                        <div class="flex items-center border border-jet-gray/30 rounded p-1">
                                            <button id="decreaseBtn"
                                                class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input readonly id="quantity" type="number" min="1"
                                                class="text-center text-persian-blue w-16 h-5 text-sm font-medium border-0 focus:ring-0" />

                                            <button id="increaseBtn"
                                                class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                        <span class="text-davy-gray text-xs">In Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Action Buttons -->

                        <div class="flex flex-wrap w-full gap-3 mt-5 xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <input type="hidden" name="quantity" class="qtyInputValue" value=""
                                id="qtyInput{{ $product['id'] }}">

                            <input type="hidden" id="variantId" name="variant_id" value="">

                            @if ($product['in_stock'] > 0)
                                <button data-id="{{ $product['id'] }}" type="button"
                                    class="cartBtn text-sm md:text-base font-medium flex-1 px-6 py-2 border border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-all">
                                    Add To Cart
                                </button>
                            @else
                                <button data-id="{{ $product['id'] }}" type="button"
                                    class="wishlistBtn text-sm md:text-base font-medium flex-1 px-6 py-2 border border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-all">
                                    <i class="fa-regular fa-heart"></i>
                                    <span>Wishlist</span>
                                </button>
                            @endif

                            <button data-id="{{ $product['id'] }}" data-seller="{{ $product['seller']['id'] }}"
                                class="buyNowBtn text-sm md:text-base font-medium flex-1 px-6 py-2 bg-primary text-white rounded-full hover:bg-theme-dark transition-all">
                                Buy Now
                                <span class="block text-xs font-light">Faster Dispatch</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rating Overview Section -->
                <div class="flex flex-col items-start gap-5 py-8 md:flex-row md:py-10">
                    <!-- Left Column -->
                    <div class="order-2 md:order-1 lg:w-[55%] md:w-[50%] w-full">
                        <!-- Overall Rating -->
                        <div class="flex items-start gap-4">
                            <div class="font-[arial] space-y-1">
                                <div class="text-4xl md:text-5xl text-persian-blue">
                                    {{ $product['rating'] . '%' }}
                                </div>

                                @if ($product['total_reviews'] > 0)
                                    <div class="flex text-3xl text-yellow-400 md:text-4xl">
                                        @php
                                            $average = round($product['rating']);
                                        @endphp
                                        {!! str_repeat('★', $average) . str_repeat('☆', 5 - $average) !!}
                                    </div>
                                @else
                                    <div class="flex text-3xl text-yellow-400 md:text-4xl">
                                        {!! str_repeat('☆', 5) !!}
                                    </div>
                                @endif
                                <div class="text-xs text-davy-gray sm:text-sm">
                                    (Positive reviews)
                                    <span class="font-semibold text-primary/80 lg:pl-4">Top</span>
                                </div>
                            </div>

                            <!-- Rating Bars -->
                            @php
                                $total = $totalReviews ?: 1;
                            @endphp

                            <div class="w-full space-y-1 ratings-wrap sm:w-2/4 md:w-3/4 2xl:w-1/2 lg:w-2/3 md:space-y-2">
                                @foreach ($ratings->sortDesc() as $star => $count)
                                    @php
                                        $percentage = round(($count / $total) * 100);
                                    @endphp
                                    <div class="flex items-center w-full gap-2 md:gap-5">
                                        <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                            <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5">
                                                <div class="bg-yellow-400 h-2 rounded-full lg:h-2.5"
                                                    style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                        <span class="text-xs sm:text-sm text-persian-blue">
                                            {{ $count }} ({{ str_pad($star, 2, '0', STR_PAD_LEFT) }} star)
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Average Rating -->
                        <div class="flex flex-wrap items-center gap-3 my-3 md:my-5">
                            @if ($totalReviews > 0)
                                <span class="text-xl font-medium text-davy-gray sm:text-2xl">
                                    {{ number_format($averageRating, 1) }}
                                </span>
                                <div class="flex gap-1 text-xs flex-nowrap md:text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <i class="fa-solid fa-star text-yellow-400"></i>
                                        @elseif ($i - $averageRating < 1)
                                            <i class="fa-solid fa-star-half-stroke text-yellow-400"></i>
                                        @else
                                            <i class="fa-solid fa-star text-gray-400"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm sm:text-base text-jet-gray">
                                    ({{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }})
                                </span>
                            @endif
                        </div>

                        <!-- Review Section -->
                        <div class="text-sm comments-tags lg:text-base text-davy-gray">
                            <h4>Item Reviews</h4>
                            <!-- review tags -->
                            <div class="flex flex-wrap gap-2 my-3 font-medium review-tags lg:gap-3 md:my-5">
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray gap-2">
                                    <span class="w-auto h-5 flag-wrap lg:h-7"><img class="object-contain w-auto h-full"
                                            src="{{ asset('assets/frontend/images/us-flag.png') }}"
                                            alt="Flag of USA" /></span>
                                    <span>(800)</span>
                                </button>
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray">
                                    Gift (90)
                                </button>
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray">
                                    Adorable (250)
                                </button>
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray">
                                    Beautiful (250)
                                </button>
                            </div>

                            <!-- User Reviews -->
                            <div class="pt-5 space-y-5 reviews-wrapper">
                                @include('frontend.partials.review-card', [
                                    'reviews' => $product['reviews'],
                                ])

                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="order-1 md:order-2 lg:w-[45%] md:w-[50%] w-full md:px-2 xl:px-3">
                        <div class="flex items-center gap-2 mb-5">
                            <span
                                class="bg-leaf-green inline-flex items-center gap-2 text-white text-xs px-2.5 py-1 rounded-full"><img
                                    src="{{ asset('assets/frontend/images/top-rated-icon.png') }}" alt="Top rated icon"
                                    class="object-contain w-3 h-3" />
                                #Top Rated</span>
                            <p class="text-sm text-davy-gray">In Men's Iteams</p>
                        </div>
                        <!-- Shipping Info -->
                        <div class="flex items-center gap-2 mb-5">
                            <img src="{{ asset('assets/frontend/images/carbon_delivery.png') }}" alt="Shipping"
                                class="object-contain w-7 h-7" />
                            <span class="font-medium text-davy-gray">Ships From Tesco</span>
                        </div>

                        <!-- Shipping Options -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2">
                            <!-- Standard Shipping -->
                            <div class="bg-[#F5F5F5] p-4 rounded-lg text-davy-gray lg:space-y-2 space-y-1">
                                <h4 class="text-sm font-semibold text-black">
                                    Standard <span class="text-leaf-green">: Free</span>
                                </h4>
                                <p class="text-xs">
                                    <span class="text-sm">Delivery :</span> Fastest Delivery in
                                    5 Business days
                                </p>
                                <div class="flex items-center gap-1 text-xs">
                                    <span class="text-sm">Courier Company :</span>
                                    <img src="{{ asset('assets/frontend/images/dhl-logo.png') }}" alt="DHL"
                                        class="object-contain w-auto h-4" />
                                    <span>DHL</span>
                                    <img src="{{ asset('assets/frontend/images/ups-logo.png') }}" alt="UPS"
                                        class="object-contain w-auto h-4" />
                                    <span>UPS</span>
                                </div>
                            </div>

                            <!-- Express Shipping -->
                            <div class="bg-[#F5F5F5] p-4 rounded-lg text-davy-gray lg:space-y-2 space-y-1">
                                <h4 class="text-sm font-semibold text-black">
                                    Express <span class="text-leaf-green">: $12.00</span>
                                </h4>
                                <p class="text-xs">
                                    <span class="text-sm">Delivery :</span> Fastest Delivery in
                                    3 Business days
                                </p>
                                <div class="flex items-center gap-1 text-xs">
                                    <span class="text-sm">Courier Company :</span>
                                    <img src="{{ asset('assets/frontend/images/dhl-logo.png') }}" alt="DHL"
                                        class="object-contain w-auto h-4" />
                                    <span>DHL</span>
                                </div>
                            </div>
                        </div>

                        <!-- Commitments -->
                        <div class="mt-5">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-10 h-10 overflow-hidden tesko-icon">
                                    <img src="{{ asset('assets/frontend/images/tesko-icon.png') }}" alt="Tesko Icon"
                                        class="object-contain w-full h-full" />
                                </div>
                                <span class="font-medium text-davy-gray">Our Commitments</span>
                            </div>

                            <div
                                class="grid w-full grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 xsm:w-4/5 md:w-11/12 lg:w-4/6 xl:w-11/12 2xl:w-4/5">
                                <!-- Security & Privacy -->
                                <div class="bg-[#F5F5F5] p-4 rounded-lg">
                                    <h3 class="mb-2 text-leaf-green">Security & Privacy</h3>
                                    <ul class="space-y-1 text-sm lg:space-y-2 text-davy-gray">
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span> Safe Payments </span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span> Secure Privacy </span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Delivery Guarantee -->
                                <div class="bg-[#F5F5F5] p-4 rounded-lg">
                                    <h3 class="mb-2 text-leaf-green">Delivery Guarantee</h3>
                                    <ul class="space-y-1 text-sm lg:space-y-2 text-davy-gray">
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span> Return Item If Damaged</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span>15 Days No Update Refund </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($product['reviews']->count() > 2)
                    <!-- Load More Comment Button -->
                    <div class="pb-10 text-center border-b-2 border-gray-400 border-dashed load-more-btn">
                        <button id="loadMoreReviews" data-offset="2" data-type="reviews"
                            data-url="{{ request()->url() }}"
                            class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                            type="button">
                            <span>Load More</span>
                            <i class="text-sm fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <!-- Product Provider Section Starts -->
        <section class="py-5 text-sm product-provider-sec md:py-8 md:text-base xl:text-lg text-davy-gray">
            <div class="container">
                <!-- Header -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                    <a href="{{ route('sellers.shop', $seller['username']) }}"
                        class="inline-block w-10 h-10 overflow-hidden rounded-full provider-dp lg:w-14 lg:h-14">
                        <img src="{{ storage_url($seller['shop_logo']) }}" alt="Louis Vuitton Logo"
                            class="object-contain w-full h-full" />
                    </a>
                    <div class="provider-info">
                        <h2 class="flex items-center gap-2 text-lg font-medium md:text-xl lg:text-2xl md:gap-5">
                            <a href="{{ route('sellers.shop', $seller['username']) }}"
                                class="hover:text-butterfly-blue eq">{{ $seller['shop_name'] }}</a>
                            <p class="flex items-center gap-2 text-sm font-light md:text-base xl:text-lg">
                                <button class="hover:text-primary eq">
                                    <i class="fa-regular fa-comment-dots"></i>
                                </button>
                                <span>Contact With Provider</span>
                            </p>
                        </h2>

                        <!-- Metrics -->
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span>{{ $seller['total_followers'] }}+ Followers .</span>
                            {{-- <span>{{ $seller['total_sell'] }} Sold .</span> --}}
                            <span class="flex items-center gap-1">
                                <span>{{ $seller['rating'] }}</span>
                                <i class="fa-solid fa-star text-theme-dark"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Buttons -->
                <div class="flex flex-wrap items-center gap-2 mt-4 font-medium md:gap-4">
                    <button
                        class="inline-flex items-center py-1.5 px-5 xsm:px-8 lg:px-10 lg:py-2.5 border border-jet-gray theme-btn gap-2 hover:bg-leaf-green hover:text-white hover:border-transparent eq text-sm md:text-base lg:text-xl font-inherit">
                        <i class="fa-solid fa-store"></i>
                        Follow
                    </button>
                    <a href="{{ route('sellers.shop', $seller['username']) }}"
                        class="inline-flex items-center py-1.5 px-5 xsm:px-8 lg:px-10 lg:py-2.5 border border-jet-gray theme-btn gap-2 hover:bg-primary hover:text-white hover:border-transparent eq text-sm md:text-base lg:text-xl font-inherit">
                        <span>Shop All Items</span>
                        ({{ count($products) }})
                    </a>
                </div>

                <div class="w-full shop-decriptions md:w-2/3 lg:w-1/2">
                    <!-- Description -->
                    <div class="mt-5">
                        <h2>Description:</h2>
                        <p class="mt-2">
                            {{ $product['description'] }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Product Provider Section Ended -->

        <!-- Explore Interest Section Start  -->
        <section class="explore-interest section-padding">
            <div class="container">
                <!-- Section Tittle -->
                <h1 class="mb-5 text-xl font-medium sm:text-2xl lg:text-3xl text-jet-gray md:mb-8 lg:mb-10">
                    Explore Your Interest
                </h1>

                <div id="product-wrapper"
                    class="grid items-start grid-cols-1 gap-5 p-2 xsm:grid-cols-2 md:grid-cols-4 xl:gap-8 lg:p-0">
                    @include('frontend.partials.product-card-load', ['products' => $products])
                </div>

                @if ($products->count() >= 8)
                    <!-- Load More Btn -->
                    <div class="mt-10 text-center load-more-btn">
                        <button data-page="1" data-type="products" data-url="{{ request()->url() }}"
                            id="loadMoreProducts"
                            class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                            type="button">
                            <span>Load More</span>
                            <i class="text-sm fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                @endif
            </div>
        </section>
        <!-- Explore Interest Section Ended  -->
    </main>

    @push('scripts')
        <script>
            const product = @json($product);
            const variants = product.variants;
            const defaultVariant = @json($defaultVariant);
            const selectedOptions = {};
            const valueToOptionMap = {};

            let quantity = 1;
            let currentVariant = defaultVariant;

            const quantityElement = $('#quantity');
            const decreaseBtn = $('#decreaseBtn');
            const increaseBtn = $('#increaseBtn');
            const hiddenInput = $('.qtyInputValue');

            const updateQuantity = () => {
                quantityElement.val(quantity.toString().padStart(2, "0"));
                hiddenInput.val(quantity);

                if (currentVariant) {
                    $('#variantId').val(currentVariant.id);
                    const basePrice = parseFloat(currentVariant.price) || 0;
                    const baseDiscountedPrice = parseFloat(currentVariant.discounted_price) || 0;
                    const totalPrice = (basePrice * quantity).toFixed(2);
                    const totalDiscountedPrice = (baseDiscountedPrice * quantity).toFixed(2);

                    $('#sku').text(currentVariant.sku);
                    $('#price').text(`৳ ${totalPrice}`);
                    $('#discounted_price').text(`৳ ${totalDiscountedPrice}`);
                    $('#availability').text(currentVariant.stock > 0 ? 'In Stock' : 'Out of Stock');
                }
            };

            increaseBtn.on('click', function() {
                quantity++;
                updateQuantity();
            });

            decreaseBtn.on('click', function() {
                if (quantity > 1) {
                    quantity--;
                    updateQuantity();
                }
            });

            quantityElement.on('input', function() {
                const newQuantity = parseInt($(this).val()) || 1;
                quantity = newQuantity < 1 ? 1 : newQuantity;
                updateQuantity();
            });

            product.options.forEach(option => {
                option.values.forEach(value => {
                    valueToOptionMap[value.id] = option.id;
                });
            });

            if (defaultVariant && defaultVariant.value_ids) {
                defaultVariant.value_ids.forEach(valueId => {
                    const optionId = valueToOptionMap[valueId];
                    if (optionId) {
                        selectedOptions[optionId] = valueId;
                        $(`.option-value-btn[data-option-id="${optionId}"]`).removeClass(
                                'bg-primary text-white border-primary')
                            .addClass('bg-white text-gray-800 border-gray-300');

                        $(`.option-value-btn[data-option-id="${optionId}"][data-value-id="${valueId}"]`)
                            .removeClass('bg-white text-gray-800 border-gray-300')
                            .addClass('bg-primary text-white border-primary');
                    }
                });
            }

            updateQuantity();

            $(document).ready(function() {
                $('.option-value-btn').on('click', function() {
                    const optionId = $(this).data('option-id');
                    const valueId = $(this).data('value-id');

                    selectedOptions[optionId] = valueId;

                    $(`.option-value-btn[data-option-id="${optionId}"]`)
                        .removeClass('bg-primary text-white border-primary')
                        .addClass('bg-white text-gray-800 border-gray-300');

                    $(this)
                        .removeClass('bg-white text-gray-800 border-gray-300')
                        .addClass('bg-primary text-white border-primary');

                    const totalOptions = product.options.length;
                    if (Object.keys(selectedOptions).length === totalOptions) {
                        const selectedIds = Object.values(selectedOptions).map(Number).sort();

                        const matchingVariant = variants.find(variant => {
                            const sorted = variant.value_ids.slice().sort();
                            return JSON.stringify(sorted) === JSON.stringify(selectedIds);
                        });

                        if (matchingVariant) {
                            currentVariant = matchingVariant;
                            $('#variantId').val(currentVariant.id);

                            const basePrice = parseFloat(currentVariant.price) || 0;
                            const baseDiscountedPrice = parseFloat(currentVariant.discounted_price) || 0;
                            const totalPrice = (basePrice * quantity).toFixed(2);
                            const totalDiscountedPrice = (baseDiscountedPrice * quantity).toFixed(2);

                            $('#sku').text(currentVariant.sku);
                            $('#price').text(`৳ ${totalPrice}`);
                            $('#discounted_price').text(`৳ ${totalDiscountedPrice}`);
                            $('#availability').text(currentVariant.stock > 0 ? 'In Stock' : 'Out of Stock');
                        } else {
                            currentVariant = null;
                            $('#variantId').val('');
                            $('#sku').text('N/A');
                            $('#price').text('0.00');
                            $('#availability').text('Not Available');
                        }
                    }
                });
            });
        </script>



        <script>
            $(document).ready(function() {


                function updateSliderThumbnailActive(selectedImageUrl) {
                    const normalizedUrl = selectedImageUrl.trim().toLowerCase();
                    $('.swiper-slide').removeClass('swiper-slide-active swiper-slide-thumb-active');

                    $('.swiper-slide').each(function() {
                        const slide = $(this);
                        const img = $(this).find('img').attr('src')?.trim().toLowerCase();

                        if (img === selectedImageUrl) {
                            console.log("variant image match");
                            slide.addClass('swiper-slide-active swiper-slide-thumb-active');
                            return false;
                        }
                    });

                    console.log($('.swiper-slide-active'))

                    $('.main').removeClass('swiper-slide-active');

                    $('.main').each(function(index) {
                        const mainSlide = $(this)
                        const imgSrc = $(this).find('img').attr('src')?.trim().toLowerCase();
                        if (imgSrc === normalizedUrl) {
                            mainSlide.addClass('swiper-slide-active');
                            return false;
                        }
                    });
                }

                const seenAttributes = new Set();

                $('.variantForm .variant-option').each(function() {
                    const attrId = $(this).data('attribute');

                    if (!seenAttributes.has(attrId)) {
                        seenAttributes.add(attrId);
                        $(this).prop('checked', true);
                    }
                });

                updateQuantity();
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#loadMoreReviews').on('click', function() {
                    var $button = $(this);
                    var offset = parseInt($button.data('offset'));
                    var url = $button.data('url');
                    var type = $button.data('type');


                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            offset: offset,
                            type: type
                        },
                        success: function(response) {
                            if ($.trim(response) === '') {

                                $button.hide();
                            } else {
                                $('#reviews-wrapper').append(response);
                                $button.data('offset', offset + 2);
                            }
                        },
                        error: function() {
                            console.error('Failed to load more reviews.');
                        }
                    });
                });
            });
        </script>

        <script>
            $('#loadMoreProducts').on('click', function() {
                let button = $(this);
                let page = parseInt(button.data('page')) + 1;
                let url = button.data('url');

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        page: page,
                        type: 'products'
                    },
                    beforeSend: function() {
                        button.prop('disabled', true).html(
                            '<i class="fa fa-spinner fa-spin"></i> Loading...');
                    },
                    success: function(response) {
                        if (response.trim() !== '') {
                            $('#product-wrapper').append(response);
                            button.data('page', page);
                            button.prop('disabled', false).html(
                                '<span>Load More</span> <i class="fa-solid fa-chevron-down text-sm"></i>'
                            );
                        } else {
                            button.hide();
                        }
                    },
                    error: function() {
                        button.prop('disabled', false).text('Load More');
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        </script>

        <script>
            // Single Product Swiper JS Slider Script's
            // Vertical Product Image Thumbnails
            const productThumbs = new Swiper(".single-product-thumbnails", {
                spaceBetween: 10,
                slidesPerView: 5,
                watchSlidesProgress: true,
                direction: "horizontal",
                spaceBetween: 10,
                grabCursor: true,
                breakpoints: {
                    1024: {
                        direction: "vertical",
                        spaceBetween: 5,
                    },
                    1280: {
                        direction: "vertical",
                        spaceBetween: 10,
                    },
                },
            });

            // Product Images Slider
            const productSwiper = new Swiper(".single-product-swiper", {
                spaceBetween: 10,
                grabCursor: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                thumbs: {
                    swiper: productThumbs,
                },
            });
        </script>
    @endpush
@endsection
