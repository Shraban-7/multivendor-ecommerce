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
                            <div class="product-thumbnails overflow-hidden xl:h-[37rem] lg:h-[41rem] h-auto">
                                <div class="swiper-wrapper">
                                    <!-- thumb 1 -->
                                    @foreach ($product['images'] as $thumb)
                                        <div class="swiper-slide">
                                            <div
                                                class="w-full h-20 overflow-hidden border-2 border-transparent cursor-pointer slide-thumb xl:h-24 md:h-22 lg:h-28 rounded-2xl hover:border-primary">
                                                <img src="{{ storage_url($thumb) }}"
                                                    alt="Product thumbnail of A Young boy wear a jacket with green T-Shirt & Short Pant"
                                                    class="object-cover w-full h-full" />
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- Repeat thumb for more thumbnails -->
                                </div>
                            </div>
                        </div>

                        <!-- Main Image Slider -->
                        <div class="relative order-1 w-full lg:w-5/6 lg:order-2">
                            <div
                                class="product-swiper overflow-hidden w-full h-96 md:h-[37rem] xl:h-[37rem] lg:h-[41rem] rounded-2xl overflow-hidden relative">
                                <div class="swiper-wrapper">
                                    <!-- product image 1 -->
                                    @foreach ($product['images'] as $slider)
                                        <div class="h-full overflow-hidden swiper-slide rounded-2xl">
                                            <img src="{{ storage_url($slider) }}" alt=""
                                                class="object-cover w-full h-full" />
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
                                class="text-sm justify-center lg:text-base text-rustic-red bg-[#FEEFE1] px-4 py-3 flex flex-wrap flex-col xsm:flex-row justify-between items-center">
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
                                    <span
                                        class="pr-2 border-r border-gray-400 text-jet-gray">{{ number_shorten_format($product['sold_out']) }}
                                        sold</span>
                                    <div class="flex items-center gap-2 text-davy-gray">
                                        <span>Provided By</span>
                                        <a href="{{ route('sellers.shop', $seller['username']) }}"
                                            class="inline-block w-6 h-6 overflow-hidden rounded-full provider-icon">
                                            <img src="{{ storage_url($seller['shop_logo']) }}"
                                                alt="{{ $seller['shop_name'] }}" class="object-contain w-full h-full" />
                                        </a>
                                        <span>({{ number_shorten_format($product['sold_out']) }}+ sold)</span>
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
                                <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best Seller</span>
                                <p class="text-sm text-davy-gray">From this provider</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <div class="flex flex-no-wrap items-center gap-1 new-price">
                                    <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                    <h3 id="current-price" class="font-bold current-price text-primary product-price">
                                        {{ $product['discount_price'] }}</h3>
                                </div>
                                <h6 id="old-price" class="line-through text-jet-gray">{{ $product['price'] }}
                                </h6>
                                <span class="text-xs px-2.5 py-0.5 rounded-lg border border-primary discount-badge">
                                    -{{ $product['discount']['amount'] }} last 2 days
                                </span>
                                <span class="text-xs text-leaf-green">Almost Sold Out</span>
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
                                    <form id="variantForm" data-slug="{{ $product['slug'] }}"
                                        class="flex flex-wrap flex-col">
                                        @foreach ($productAttributes as $attribute)
                                            <div class="mt-2">
                                                <h6 class="text-davy-gray sm:text-lg">{{ $attribute['name'] }} :</h6>
                                                <div class="flex flex-wrap items-center gap-4 sm:gap-5">
                                                    @foreach ($attribute['options'] as $option)
                                                        @php
                                                            $inputId =
                                                                strtolower($attribute['name']) .
                                                                '-' .
                                                                strtolower($option['value']);
                                                            $inputName = 'option_' . $attribute['id'];
                                                        @endphp

                                                        <div class="form-ctrl flex flex-col gap-2 items-center">
                                                            <input id="{{ $inputId }}" type="radio"
                                                                value="{{ $option['id'] }}"
                                                                data-option-id="{{ $option['id'] }}"
                                                                name="{{ $inputName }}"
                                                                class="hidden peer variant-option" />

                                                            @if (strtolower($attribute['name']) === 'color')
                                                                <label for="{{ $inputId }}"
                                                                    class="w-6 h-6 sm:w-8 sm:h-8 block peer-checked:ring peer-checked:ring-{{ strtolower($option['value']) }}-800 bg-{{ strtolower($option['value']) }}-700 rounded-full peer-checked:border-2 sm:peer-checked:border-4 border border-black peer-checked:border-primary cursor-pointer">
                                                                </label>
                                                            @else
                                                                <label for="{{ $inputId }}"
                                                                    class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer">
                                                                    {{ $option['value'] }}
                                                                </label>
                                                            @endif

                                                            @if (strtolower($attribute['name']) === 'color')
                                                                <label for="{{ $inputId }}"
                                                                    class="block cursor-pointer text-davy-gray text-sm sm:text-base">
                                                                    {{ $option['value'] }}
                                                                </label>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </form>
                                </div>

                                <!-- Quantity -->
                                <div class="quantity mt-3">
                                    <div class="text-davy-gray flex items-center gap-2">
                                        <h6 class="sm:text-lg">Quantity :</h6>
                                        <div class="flex items-center border rounded p-1">
                                            <button id="decreaseBtn"
                                                class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input readonly id="quantity" type="number" min="1"
                                                class="text-center text-persian-blue w-12 h-5 text-sm font-medium border-0 focus:ring-0" />
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

                            <input type="hidden" id="variantSku" value="">

                            @if ($product['in_stock'] > 0)
                                <button data-id="{{ $product['id'] }}" type="button"
                                    class="cartBtn text-sm md:text-base font-medium flex-1 px-6 py-2 border border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-all">
                                    Add To Cart
                                    <span class="block text-xs font-light">{{ $product['discount']['percent'] }}%
                                        Discount</span>
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
                                <!-- review 1 -->
                                <div class="space-y-2 review-item">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 overflow-hidden rounded-full user-avatar">
                                            <img src="{{ asset('assets/frontend/images/user-avatar-1.png') }}"
                                                alt="Alan Walker" />
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                                            <h3 class="font-medium">Alan Walker</h3>
                                            <span class="flex gap-2 text-gray-400">
                                                In
                                                <span class="w-auto h-4 lg:h-6"><img class="object-contain w-auto h-full"
                                                        src="{{ asset('assets/frontend/images/us-flag.png') }}"
                                                        alt="Flag of USA" /></span>
                                                on Jan 20, 2025
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Rating -->
                                    <div class="flex flex-wrap items-center gap-3 rating">
                                        <div class="flex gap-1 text-xs flex-nowrap text-theme-dark md:text-sm">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <span class="text-lg font-medium text-davy-gray sm:text-xl">5.0</span>
                                    </div>
                                    <h6 class="product-colour">Purchased : Black</h6>
                                    <p class="w-10/12 product-feedback sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        Absolutely beautiful, good price perfect, perfect
                                        excellent product, very nice quality 😇😇
                                    </p>
                                    <div
                                        class="flex items-center justify-center w-10/12 text-xs text-black xsm:text-sm lg:text-base xl:text-lg sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        <div class="flex items-start gap-3 divide-x divide-black">
                                            <button class="flex items-center gap-2 hover:text-primary eq">
                                                <svg class="w-5 h-5" width="26" height="32" viewBox="0 0 26 32"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M18.7299 11.2163H21.6028C22.3648 11.2163 23.0955 11.5156 23.6343 12.0485C24.1731 12.5814 24.4758 13.3041 24.4758 14.0577V27.6963C24.4758 28.4499 24.1731 29.1726 23.6343 29.7054C23.0955 30.2383 22.3648 30.5377 21.6028 30.5377H4.36514C3.60318 30.5377 2.87244 30.2383 2.33366 29.7054C1.79487 29.1726 1.49219 28.4499 1.49219 27.6963V14.0577C1.49219 13.3041 1.79487 12.5814 2.33366 12.0485C2.87244 11.5156 3.60318 11.2163 4.36514 11.2163H7.23809M18.7299 6.67006L12.984 0.987305M12.984 0.987305L7.23809 6.67006M12.984 0.987305V20.3797"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                Share
                                            </button>
                                            <button class="flex items-center gap-2 pl-2 hover:text-butterfly-blue eq">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                </svg>
                                                Helpful (1)
                                            </button>
                                        </div>
                                        <button class="ml-auto text-xl md:text-2xl lg:text-3xl" id="alan-walker-btn"
                                            data-dropdown-toggle="alan-walker-comment-dropdown" type="button">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="alan-walker-comment-dropdown"
                                            class="z-30 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-38 md:w-44">
                                            <div class="py-2 text-sm text-gray-700" aria-labelledby="alan-walker-btn">
                                                <button class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                                    Not Helpful
                                                </button>

                                                <button
                                                    class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-persian-red">
                                                    Report Abuse
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- review 2 -->
                                <div class="space-y-2 review-item">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 overflow-hidden rounded-full user-avatar">
                                            <img src="{{ asset('assets/frontend/images/user-avatar-2.png') }}"
                                                alt="Josesph Man" />
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                                            <h3 class="font-medium">Josesph Man</h3>
                                            <span class="flex gap-2 text-gray-400">
                                                In
                                                <span class="w-auto h-4 lg:h-6"><img class="object-contain w-auto h-full"
                                                        src="{{ asset('assets/frontend/images/us-flag.png') }}"
                                                        alt="Flag of USA" /></span>
                                                on Jan 22, 2025
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Rating -->
                                    <div class="flex flex-wrap items-center gap-3 rating">
                                        <div class="flex gap-1 text-xs flex-nowrap text-theme-dark md:text-sm">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <span class="text-lg font-medium text-davy-gray sm:text-xl">5.0</span>
                                    </div>

                                    <h6 class="product-colour">Purchased : Green</h6>
                                    <p class="w-10/12 product-feedback sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        Fantastic product at a great price. Truly impressed with
                                        the exceptional quality. Beautifully crafted and exceeds
                                        expectations 🥰 Highly recommend✅
                                    </p>

                                    <div
                                        class="flex items-center justify-center w-10/12 text-xs text-black xsm:text-sm lg:text-base xl:text-lg sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        <div class="flex items-start gap-3 divide-x divide-black">
                                            <button class="flex items-center gap-2 hover:text-primary eq">
                                                <svg class="w-5 h-5" width="26" height="32" viewBox="0 0 26 32"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M18.7299 11.2163H21.6028C22.3648 11.2163 23.0955 11.5156 23.6343 12.0485C24.1731 12.5814 24.4758 13.3041 24.4758 14.0577V27.6963C24.4758 28.4499 24.1731 29.1726 23.6343 29.7054C23.0955 30.2383 22.3648 30.5377 21.6028 30.5377H4.36514C3.60318 30.5377 2.87244 30.2383 2.33366 29.7054C1.79487 29.1726 1.49219 28.4499 1.49219 27.6963V14.0577C1.49219 13.3041 1.79487 12.5814 2.33366 12.0485C2.87244 11.5156 3.60318 11.2163 4.36514 11.2163H7.23809M18.7299 6.67006L12.984 0.987305M12.984 0.987305L7.23809 6.67006M12.984 0.987305V20.3797"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                Share
                                            </button>
                                            <button class="flex items-center gap-2 pl-2 hover:text-butterfly-blue eq">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                </svg>
                                                Helpful (1)
                                            </button>
                                        </div>
                                        <button class="ml-auto text-xl md:text-2xl lg:text-3xl" id="josesph-man-btn"
                                            data-dropdown-toggle="josesph-man-comment-dropdown" type="button">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>

                                        <!-- Dropdown menu -->
                                        <div id="josesph-man-comment-dropdown"
                                            class="z-30 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-38 md:w-44">
                                            <div class="py-2 text-sm text-gray-700" aria-labelledby="josesph-man-btn">
                                                <button class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                                    Not Helpful
                                                </button>

                                                <button
                                                    class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-persian-red">
                                                    Report Abuse
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

                <!-- Load More Comment Button -->
                <div class="pb-10 text-center border-b-2 border-gray-400 border-dashed load-more-btn">
                    <button
                        class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                        type="button">
                        <span>Load More</span>
                        <i class="text-sm fa-solid fa-chevron-down"></i>
                    </button>
                </div>
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
                            <span>{{ $seller['total_sell'] }} Sold .</span>
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
                    @include('frontend.partials.product-card-load', ['products' => $interest_products])
                </div>

                @if ($products->count() >= 8)
                    <!-- Load More Btn -->
                    <div class="mt-10 text-center load-more-btn">
                        <button data-page="1" data-url="{{ route('products.details', $product['slug']) }}"
                            id="loadMoreBtn"
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
            $(document).ready(function() {
                var quantity = 1;

                const quantityElement = $('#quantity');
                const decreaseBtn = $('#decreaseBtn');
                const increaseBtn = $('#increaseBtn');
                const hiddenInput = $('.qtyInputValue');

                const updateQuantity = () => {
                    quantityElement.val(quantity.toString().padStart(2, "0"));
                    hiddenInput.val(quantity);
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
                    var newQuantity = $(this).val();
                    quantity = parseInt(newQuantity) || 1;
                    updateQuantity();
                });

                updateQuantity();
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#variantForm').on('change', '.variant-option', function() {
                    let selectedOptions = [];
                    $('.variant-option:checked').each(function() {
                        selectedOptions.push($(this).val());
                    });

                    let productSlug = $('#variantForm').data('slug');

                    $.ajax({
                        url: '/products/' + productSlug + '/get-variant',
                        method: 'POST',
                        data: {
                            option_ids: selectedOptions
                        },
                        success: function(response) {

                            if (response.price !== undefined) {
                                $('#current-price').text('৳ ' + (parseInt(response.discounted_price)));
                                $('#variantSku').val(response.sku);

                                $('#old-price').text('৳ ' + (parseInt(response.price)));
                                // $('.discount-badge').hide();
                            }

                            if (response.stock !== undefined) {
                                if (response.stock > 0) {
                                    $('#product-stock').text('In Stock').removeClass('text-red-500')
                                        .addClass('text-green-600');
                                    $('#add-to-cart-button').prop('disabled', false);
                                } else {
                                    $('#product-stock').text('Out of Stock').removeClass(
                                        'text-green-600').addClass('text-red-500');
                                    $('#add-to-cart-button').prop('disabled', true);
                                }
                            }

                            if (response.image) {
                                $('#product-image').attr('src', response.image);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching variant:', xhr.responseJSON?.message ||
                                'Something went wrong');
                        }
                    });
                });
            });
        </script>

        <script>
            $('#loadMoreBtn').on('click', function() {
                let button = $(this);
                let page = parseInt(button.data('page')) + 1;
                let url = button.data('url');

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        page: page
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
    @endpush
@endsection
