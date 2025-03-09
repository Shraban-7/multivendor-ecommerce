@extends('frontend.layouts.app')
@section('title', 'A Multi-Vendor E-Commerce platform')

@section('content')
    <main class="home-page">
        <!-- Hero Section Starts -->
        <section class="hero-section flex flex-wrap lg:h-screen 2xl:h-[110vh]">
            <div class="w-full h-full md:w-1/2">
                <a href="#">
                    <img src="{{ asset('assets/frontend/images/hero-image-1.png') }}" alt="Image 1"
                        class="object-cover w-full h-full" />
                </a>
            </div>

            <div class="w-full h-full md:w-1/2">
                <div class="flex h-1/2">
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-2.png') }}" alt="Image 2"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-3.png') }}" alt="Image 3"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                </div>
                <div class="flex h-1/2">
                    <div class="md:w-[45%] w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-4.png') }}" alt="Image 4"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                    <div class="md:w-[55%] w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-5.jpg') }}" alt="Image 5"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Hero Section Ended -->

        <!-- Light Deals Section Starts -->
        <section class="light-deals-section">
            <!-- promotional header -->
            <div class="section-promo-header bg-[#FF4F4F]">
                <div class="container flex flex-col items-center justify-between gap-3 py-3 md:flex-row md:gap-0 md:py-5">
                    <!-- star icon -->
                    <span><svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M28 0C28.9506 15.0527 40.9472 27.0495 56 28C40.9472 28.9506 28.9506 40.9472 28 56C27.0495 40.9472 15.0527 28.9506 0 28C15.0527 27.0495 27.0495 15.0527 28 0Z"
                                fill="white" />
                        </svg>
                    </span>
                    <!-- promo title -->
                    <h2
                        class="flex flex-col items-center gap-2 text-3xl font-semibold md:flex-row md:gap-5 text-theme-light">
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
                                <a href="{{ route('product.details', $light_deal->slug) }}"
                                    class="block w-full p-3 rounded-lg product-card hover:shadow-lg eq group">
                                    <!-- slide image -->
                                    <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                        <img src="{{ storage_url($light_deal->thumbnail) }}"
                                            alt="{{ $light_deal->name }}"
                                            class="object-cover w-full h-full group-hover:scale-125 eq" />
                                        <span
                                            class="absolute block w-3/5 px-4 py-3 text-sm text-center -translate-x-1/2 bg-white rounded-full bottom-9 left-1/2">Almost
                                            Sold Out</span>
                                    </div>
                                    <!-- Slide Content -->
                                    <div class="mt-2 space-y-1 card-content">
                                        <!-- price & sold info -->
                                        <div class="flex items-center gap-2 price-sold-amount">
                                            <h2 class="text-2xl font-bold text-primary">
                                                <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                                <span
                                                    class="align-middle text-xs text-[#ffa755]">{{ CURRENCY_SYMBOL }}</span>
                                                {{ number_format($light_deal->selling_price, 2) }}
                                            </h2>
                                            <p class="text-base">{{ number_shorten_format($light_deal->stock_out) }}+ Sold
                                                Out</p>
                                        </div>
                                        <!-- time -->
                                        @php
                                            $sold_out_progress =
                                                ($light_deal->stock_out /
                                                    ($light_deal->stock_out + $light_deal->stock_in)) *
                                                100;
                                        @endphp
                                        <div class="flex flex-wrap items-center gap-2 time-progres">
                                            <div class="w-[60%] bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full progress bg-primary"
                                                    style="width: {{ percentage($sold_out_progress) }}"></div>
                                            </div>
                                            <span
                                                class="w-[35%] due-time text-sm inline-flex flex-no-wrap gap-1 items-center"><i
                                                    class="fa-regular fa-clock"></i>
                                                {{ datetime_format($light_deal->lightdeal_expired_at) }}</span>
                                        </div>
                                        <!-- rating -->
                                        <div class="flex items-center gap-2">
                                            <div class="text-xs rating-stars text-light-yellow">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
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
        <!-- Light Deals Section Ended -->

        <!-- Interest Section Starts -->
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
                                <a href="#" class="flex flex-col items-center block w-full product-card">
                                    <!-- slide image -->
                                    <div class="relative w-16 h-16 card-image lg:h-28 lg:w-28 md:w-24 md:h-24">
                                        <img src="{{ asset('assets/' . $category->image) }}" alt="Grocery"
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
                                            <a href="{{ route('product.details', $product->slug) }}">
                                                <img class="object-contain w-full h-full"
                                                    src="{{ storage_url($product->thumbnail) }}"
                                                    alt="{{ $product->name }}" />
                                            </a>
                                        </div>
                                        <div class="p-2 space-y-1 sm:p-4">
                                            <h2
                                                class="h-16 text-sm font-semibold text-theme-dark group-hover/interest-pro-card:text-persian-blue line-clamp-3 md:line-clamp-2 eq md:text-base md:h-12">
                                                <a
                                                    href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
                                            </h2>
                                            <div class="text-xs rating-stars text-light-yellow">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                            </div>
                                            <p class="text-persian-blue">{{ $product->unit }}</p>
                                            <p class="font-semibold text-sand-brown">
                                                {{ currency($product->selling_price) }}</p>

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
        <!-- Interest Section Ended -->

        <!-- Feature Gallery Section Starts -->
        <section class="feature-gallery">
            <div class="container grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-5 md:gap-4">
                <!-- col 1 -->
                <div class="relative lg:col-span-2 lg:row-span-2 lg:h-[33rem] h-96">
                    <div class="relative h-full overflow-hidden group rounded-xl">
                        <div class="w-full h-full">
                            <!-- gallery image -->
                            <img src="{{ asset('assets/frontend/images/gallery-feature-pro-1.png') }}"
                                alt="Slow cooker with ingredients" class="object-cover w-full h-full" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 flex flex-col items-start justify-center w-full h-full gap-2 p-6 text-white sm:gap-5">
                            <p class="text-sm font-medium md:text-lg lg:text-xl">
                                It's slow-cook season
                            </p>
                            <h2 class="text-2xl md:text-4xl xl:text-5xl font-semibold !leading-[1.2]">
                                Comfort coming right up now
                            </h2>
                            <button
                                class="px-6 py-2 text-sm font-medium text-black bg-white rounded-full sm:text-base md:px-8 hover:bg-primary hover:text-white eq">
                                Shop Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- col 2 -->
                <div class="relative lg:col-span-2 lg:h-[33rem] overflow-hidden flex flex-col gap-4">
                    <!-- row 1 -->
                    <div class="relative overflow-hidden group rounded-xl h-1/2">
                        <!-- gallery image -->
                        <div class="w-full h-full">
                            <img src="{{ asset('assets/frontend/images/gallery-feature-pro-2.png') }}"
                                alt="Coats and jackets collection" class="object-cover w-full h-full" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 flex flex-col items-start w-full h-full gap-2 p-6 text-white sm:gap-5">
                            <p class="text-sm font-medium md:text-lg lg:text-xl">
                                Coat, Jackets & More
                            </p>
                            <h2 class="text-2xl md:text-3xl xl:text-4xl font-semibold !leading-[1.2]">
                                Beat The Chill
                            </h2>
                            <button
                                class="px-6 py-2 text-sm font-medium text-black bg-white rounded-full sm:text-base md:px-8 hover:bg-primary hover:text-white eq">
                                Shop Now
                            </button>
                        </div>
                    </div>

                    <!-- row 2 -->
                    <div class="grid grid-cols-2 gap-2 h-1/2">
                        <div class="relative h-full overflow-hidden group rounded-xl">
                            <!-- gallery image -->
                            <div class="w-full h-full">
                                <img src="{{ asset('assets/frontend/images/gallery-feature-pro-3.png') }}"
                                    alt="Home decor items" class="object-cover w-full h-full" />
                            </div>
                            <!-- overlay -->
                            <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                            <!-- content -->
                            <div class="absolute top-0 left-0 w-full h-full p-6 text-white">
                                <h2 class="text-xl md:text-lg xl:text-[1.7rem] font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                    Festive decor in everywhere
                                </h2>
                                <a href="#" class="font-medium text-white underline hover:text-primary eq">Shop
                                    Now</a>
                            </div>
                        </div>

                        <div class="relative h-full overflow-hidden group rounded-xl">
                            <!-- gallery image -->
                            <div class="w-full h-full">
                                <img src="{{ asset('assets/frontend/images/gallery-feature-pro-4.png') }}"
                                    alt="Fresh produce and vegetables" class="object-cover w-full h-full" />
                            </div>
                            <!-- overlay -->
                            <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                            <!-- content -->
                            <div class="absolute top-0 left-0 w-full h-full p-6 text-white">
                                <h2 class="text-xl md:text-lg xl:text-[1.7rem] font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                    Holiday Kitchen
                                </h2>
                                <a href="#" class="font-medium text-white underline hover:text-primary eq">Shop
                                    Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- col 3 -->
                <div class="lg:row-span-2 lg:h-[33rem] md:col-span-2 lg:col-span-1 h-96">
                    <div class="relative h-full overflow-hidden group rounded-xl">
                        <!-- gallery image -->
                        <div class="w-full lg:h-full">
                            <img src="{{ asset('assets/frontend/images/gallery-feature-pro-5.png') }}"
                                alt="Fashion collection" class="object-cover w-full h-full lg:h-full" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 flex flex-col items-start justify-center w-full h-full gap-5 p-6 text-white">
                            <h2 class="text-xl md:text-lg xl:text-2xl font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                Curted Fits for the season
                            </h2>
                            <button
                                class="px-6 py-2 text-sm font-medium text-black bg-white rounded-full sm:text-base md:px-8 hover:bg-primary hover:text-white eq">
                                Shop Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Feature Gallery Section Starts -->

        <!-- Promotional Header Section -->
        <section class="my-5 promo-sec bg-light-yellow">
            <div class="container flex flex-col items-center justify-center gap-2 py-4 md:flex-row md:gap-5 md:py-6">
                <!-- promo title -->
                <h2 class="text-xl text-center text-jet-gray">
                    Earn 5% Cash Back on Tesko.com
                </h2>
                <a href="#"
                    class="border inline-block border-theme-light text-theme-light py-1.5 px-3.5 md:py-2 md:px-5 rounded-3xl font-medium text-sm md:text-base hover:bg-theme-teal eq">
                    Learn More
                </a>
            </div>
        </section>
        <!-- Promotional Header -->

        <!-- New Arrivals Section Start -->
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
                                            <a href="{{ route('product.details', $product->slug) }}">
                                                <img class="object-contain w-full h-full"
                                                    src="{{ storage_url($product->thumbnail)}}"
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
                                                            href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
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
                                                            {{ currency($price) }}
                                                        </p>
                                                        <p class="line-through old-price text-jet-gray">
                                                            {{ currency($product->selling_price) }}
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
                                <a href="#">
                                    <img src="{{ asset('assets/frontend/images/hero-image-2.png') }}"
                                        class="object-cover w-full h-full" alt="Become our sellers" />
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
                                                <a href="{{ route('product.details', $product->slug) }}" target="_blank">
                                                    <img src="{{ storage_url($product->thumbnail) }}"
                                                        alt="Meatigo Premium Goat Curry"
                                                        class="object-contain w-full h-full group-hover/trending:rotate-12 eq" />
                                                </a>
                                            </div>
                                            <div class="flex flex-col w-3/4 gap-2 text-xs item-details">
                                                <h4>
                                                    <a href="{{ route('product.details', $product->slug) }}"
                                                        target="_self"
                                                        class="font-semibold text-theme-dark line-clamp-1 group-hover/trending:text-theme-teal eq">
                                                        {{ $product->name }}
                                                    </a>
                                                </h4>
                                                <p class="text-jet-gray">{{ $product->unit }}</p>
                                                <p class="font-semibold text-theme-teal">
                                                    {{ currency($product->selling_price) }}</p>
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
                            <a href="#">
                                <img src="{{ asset('assets/frontend/images/promo-fifty.png') }}"
                                    class="object-cover w-full h-full sm:object-contain"
                                    alt="50% off pormotional poster" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- New Arrivals Section Ended -->

        <!-- Community Product Section Starts -->
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
                                <div class="flex flex-col items-center block w-full p-2 product-card">
                                    <div
                                        class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                        <div
                                            class="h-32 px-3 pt-5 pb-3 overflow-hidden item-img sm:h-40 md:h-52 md:pt-10 md:px-5 md:pb-5">
                                            <a href="{{ route('product.details', $product->slug) }}">
                                                <img class="object-contain w-full h-full"
                                                    src="{{ storage_url($product->thumbnail) }}"
                                                    alt="Xbox Series S 1TB + Controller" />
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
                                                            href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
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
                                                            {{ currency($price) }}
                                                        </p>
                                                        <p class="line-through old-price text-jet-gray">
                                                            {{ currency($product->selling_price) }}
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
        <!-- Community Product Section Ended -->

        <!-- Sessional Promotion Thumbnail Section Starts -->
        <section class="thumbnail-gallery">
            <div class="container grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-5 md:gap-4">
                @if ($special_category)
                    @foreach ($special_category->banners as $key => $banner)
                        @php
                            $gridClass = match ($key) {
                                0 => 'lg:col-span-2 lg:row-span-2 md:h-[33rem] h-96',
                                1 => 'lg:col-span-2 lg:h-[33rem] flex flex-col gap-4',

                                4 => 'lg:row-span-2 lg:h-[33rem] md:col-span-2 lg:col-span-1 h-96',
                                default => '',
                            };
                        @endphp

                        @if ($key === 0)
                            <!-- Layout for the first category (big single banner) -->
                            <div class="relative {{ $gridClass }}">
                                <div class="relative h-full overflow-hidden group rounded-xl">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif($key === 1)
                            <!-- Layout for the second category (split into two rows) -->
                            <div class="relative {{ $gridClass }}">
                                <!-- Top row (single banner) -->
                                <div class="relative overflow-hidden group rounded-xl h-1/2">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                        </a>
                                    </div>
                                </div>

                                <!-- Bottom row (grid of two banners) -->
                                <div class="grid grid-cols-2 gap-2 h-1/2">
                                    @if (isset($special_category->banners[$key + 1]))
                                        <div class="relative h-full overflow-hidden group rounded-xl">
                                            <div class="w-full h-full">
                                                <a href="#">
                                                    <img src="{{ asset('assets/' . $special_category->banners[$key + 1]->image) }}"
                                                        alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($special_category->banners[$key + 2]))
                                        <div class="relative h-full overflow-hidden group rounded-xl">
                                            <div class="w-full h-full">
                                                <a href="#">
                                                    <img src="{{ asset('assets/' . $special_category->banners[$key + 2]->image) }}"
                                                        alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($key === 4)
                            <!-- Layout for the third category (tall single banner) -->
                            <div class="relative {{ $gridClass }}">
                                <div class="relative h-full overflow-hidden group rounded-xl">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </section>
        <!-- Sessional Promotion Thumbnail Section Ended -->

        <!-- Halloween Product Section Starts -->

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
                                        class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                        <div
                                            class="h-32 px-3 pt-5 pb-3 overflow-hidden item-img sm:h-40 md:h-52 md:pt-10 md:px-5 md:pb-5">
                                            <a href="{{ route('product.details', $product->slug) }}">
                                                <img class="object-contain w-full h-full"
                                                    src="{{ storage_url($product->thumbnail) }}"
                                                    alt="Halloween Black Ladies Dress" />
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
                                                            href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
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
                                                            {{ currency($price) }}
                                                        </p>
                                                        <p class="line-through old-price text-jet-gray">
                                                            {{ currency($product->selling_price) }}
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

        <!-- Halloween Product Section Ended -->

        <!-- Featured Videos Section Starts -->
        <section class="featured-videos-section section-padding">
            <div class="container">
                <!-- Section Title -->
                <div class="relative sec-heading">
                    <h2 class="font-semibold uppercase sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                        Featured In Videos
                    </h2>

                    <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- Featured Video Swiper Slider -->
                <div class="mt-5 swiper featuredVideoSwiper md:mt-10">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        <div class="py-3 swiper-slide group/featured-videos-pro-card eq">
                            <div
                                class="relative overflow-hidden border rounded-t-lg rounded-b-sm group hover:shadow-lg eq">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="object-cover w-full h-full cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-1.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-1.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute w-1/3 bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8">
                                        <a href="#"
                                            class="block w-full font-light text-white truncate hover:text-light-yellow eq">@jesikaperker07854</a>
                                    </div>
                                    <div
                                        class="absolute flex gap-2 bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 md:gap-3">
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full play-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full mute-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="flex items-start gap-3 px-2 py-4 sm:px-3 md:px-6">
                                    <div class="overflow-hidden w-15 h-15">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-1.png') }}"
                                                alt="Commercial Slushy Machine 24L Frozen Drink Machine 1050W
                          Slush Smoothies Maker"
                                                class="object-contain w-full h-auto" />
                                        </a>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold">$450.00</p>
                                        <p class="text-xs text-gray-400 line-clamp-2">
                                            Commercial Slushy Machine 24L Frozen Drink Machine 1050W
                                            Slush Smoothies Maker
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 2 -->
                        <div class="py-3 swiper-slide group/featured-videos-pro-card eq">
                            <div
                                class="relative overflow-hidden border rounded-t-lg rounded-b-sm group hover:shadow-lg eq">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="object-cover w-full h-full cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-2.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-2.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute w-1/3 bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8">
                                        <a href="#"
                                            class="block w-full font-light text-white truncate hover:text-light-yellow eq">@spinnertech2025</a>
                                    </div>
                                    <div
                                        class="absolute flex gap-2 bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 md:gap-3">
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full play-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full mute-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="flex items-start gap-3 px-2 py-4 sm:px-3 md:px-6">
                                    <div class="overflow-hidden w-15 h-15">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-2.png') }}"
                                                alt="Table lamp BUNDLE CANIS set of 1, with charger IP65, beige, mat dimmable - Deko-Light"
                                                class="object-contain w-full h-auto" />
                                        </a>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold">$30.00</p>
                                        <p class="text-xs text-gray-400 line-clamp-2">
                                            Table lamp BUNDLE CANIS set of 1, with charger IP65,
                                            beige, mat dimmable - Deko-Light
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 3 -->
                        <div class="py-3 swiper-slide group/featured-videos-pro-card eq">
                            <div
                                class="relative overflow-hidden border rounded-t-lg rounded-b-sm group hover:shadow-lg eq">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="object-cover w-full h-full cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-3.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-3.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute w-1/3 bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8">
                                        <a href="#"
                                            class="block w-full font-light text-white truncate hover:text-light-yellow eq">@sarahperker47854</a>
                                    </div>
                                    <div
                                        class="absolute flex gap-2 bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 md:gap-3">
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full play-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full mute-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="flex items-start gap-3 px-2 py-4 sm:px-3 md:px-6">
                                    <div class="overflow-hidden w-15 h-15">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-3.png') }}"
                                                alt="Hamilton Beach 2 Slice Toaster with Extra-Wide Slots - Black in Bangladesh at BDT 7239, Rating"
                                                class="object-contain w-full h-auto" />
                                        </a>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold">$50.00</p>
                                        <p class="text-xs text-gray-400 line-clamp-2">
                                            Hamilton Beach 2 Slice Toaster with Extra-Wide Slots -
                                            Black in Bangladesh at BDT 7239, Rating
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 2 -->
                        <div class="py-3 swiper-slide group/featured-videos-pro-card eq">
                            <div
                                class="relative overflow-hidden border rounded-t-lg rounded-b-sm group hover:shadow-lg eq">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="object-cover w-full h-full cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-2.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-2.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute w-1/3 bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8">
                                        <a href="#"
                                            class="block w-full font-light text-white truncate hover:text-light-yellow eq">@spinnertech2025</a>
                                    </div>
                                    <div
                                        class="absolute flex gap-2 bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 md:gap-3">
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full play-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full mute-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="flex items-start gap-3 px-2 py-4 sm:px-3 md:px-6">
                                    <div class="overflow-hidden w-15 h-15">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-2.png') }}"
                                                alt="Table lamp BUNDLE CANIS set of 1, with charger IP65, beige, mat dimmable - Deko-Light"
                                                class="object-contain w-full h-auto" />
                                        </a>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold">$30.00</p>
                                        <p class="text-xs text-gray-400 line-clamp-2">
                                            Table lamp BUNDLE CANIS set of 1, with charger IP65,
                                            beige, mat dimmable - Deko-Light
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Featured Videos Section Ended -->
    </main>

    @push('scripts')
        <!-- cart-->

    @endpush
@endsection
