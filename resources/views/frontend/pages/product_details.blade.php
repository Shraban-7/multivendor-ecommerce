@extends('frontend.layouts.app')
@section('title', $product->name)

@section('content')
    @php
        use App\Enums\DiscountType;
    @endphp
    <main class="product-details-page">
        <section class="page-breadcrumb-links container">
            <!-- Page Breadcrumb -->
            <nav class="flex my-7 container" aria-label="Breadcrumb">
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
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#"
                                class="ms-1 text-sm text-davy-gray hover:text-primary eq md:ms-2">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-davy-gray md:ms-2">{{ $product->subcategory }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Product Main Section -->
        <section class="product-main-sec">
            <div class="container">
                <!-- Product Contents  -->
                <div class="flex flex-col md:flex-row gap-5">
                    <!-- Product Images Section -->
                    <div class="lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5">
                        <!-- Thumbnails -->
                        <div class="lg:w-1/6 w-full space-y-3 order-2 lg:order-1">
                            <div class="product-thumbnails overflow-hidden xl:h-[37rem] lg:h-[41rem] h-auto">
                                <div class="swiper-wrapper">
                                    <!-- thumb 1 -->
                                    @foreach ($product->images as $thumb)
                                        <div class="swiper-slide">
                                            <div
                                                class="slide-thumb w-full xl:h-24 md:h-22 lg:h-28 h-20 rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary overflow-hidden">
                                                <img src="{{ asset('assets/' . $thumb->image) }}"
                                                    alt="Product thumbnail of A Young boy wear a jacket with green T-Shirt & Short Pant"
                                                    class="w-full h-full object-cover" />
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- Repeat thumb for more thumbnails -->
                                </div>
                            </div>
                        </div>

                        <!-- Main Image Slider -->
                        <div class="lg:w-5/6 w-full relative order-1 lg:order-2">
                            <div
                                class="product-swiper overflow-hidden w-full h-96 md:h-[37rem] xl:h-[37rem] lg:h-[41rem] rounded-2xl overflow-hidden relative">
                                <div class="swiper-wrapper">
                                    <!-- product image 1 -->
                                    @foreach ($product->images as $slider)
                                        <div class="swiper-slide h-full rounded-2xl overflow-hidden">
                                            <img src="{{ asset('assets/' . $slider->image) }}"
                                                alt="A Young boy wear a jacket with green T-Shirt & Short Pant"
                                                class="w-full h-full object-cover" />
                                        </div>
                                    @endforeach
                                    <!-- Repeat product image for more slides -->
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
                                <span class="text-jet-gray font-light">Exclusive offer</span>
                            </div>

                            <h1 class="lg:text-base text-rustic-red text-sm lg:pr-5 xl:pr-16">
                                {{ $product->name }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-2 xsm:gap-5 sm:10 md:gap-2 lg:gap-10 text-sm">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-jet-gray border-r border-gray-400 pr-2">{{ number_shorten_format($product->stock_out) }}
                                        sold</span>
                                    <div class="flex items-center gap-2 text-davy-gray">
                                        <span>Provided By</span>
                                        <a href="{{ route('seller.shop_details', $product->seller->username) }}"
                                            class="inline-block provider-icon w-6 h-6 overflow-hidden rounded-full">
                                            <img src="{{ asset('assets/' . $product->seller->business_logo) }}"
                                                alt="Louis Vuitton" class="h-full w-full object-contain" />
                                        </a>
                                        <span>({{ number_shorten_format($product->stock_out) }}+ sold)</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <!-- 5 star rating -->
                                    <span class="text-xs">5.00 Star</span>
                                    <!-- Repeat for 5 stars -->
                                    <span>★★★★★</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best Seller</span>
                                <p class="text-davy-gray text-sm">From this provider</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <div class="new-price flex items-center gap-1 flex-no-wrap">
                                    <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                    {{-- <span class="align-center text-sm text-[#ffa755]">$</span> --}}
                                    @php
                                        if ($product->discount_type != null) {
                                            if ($product->discount_type == DiscountType::FLAT) {
                                                $price = $product->selling_price - $product->discount_amount;
                                            } elseif ($product->discount_type == DiscountType::PERCENTAGE) {
                                                $price =
                                                    $product->selling_price -
                                                    ($product->selling_price * $product->discount_amount) / 100;
                                            }
                                        } else {
                                            $price = $product->selling_price;
                                        }
                                    @endphp
                                    <h3 id="current-price" class="current-price font-bold text-primary">
                                        {{ currency($price) }}</h3>
                                </div>
                                <h6 class="old-price text-jet-gray line-through">{{ currency($product->selling_price) }}
                                </h6>
                                <span
                                    class="text-xs px-2.5 py-0.5 rounded-lg border border-primary">-{{ currency($product->discount_amount) }}
                                    last 2
                                    days</span>
                                <span class="text-leaf-green text-xs">Almost Sold Out</span>
                            </div>
                        </div>

                        <div
                            class="user-action rounded-lg border-primary border-2 overflow-hidden mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <!-- Special Sale Banner -->
                            <div
                                class="bg-primary text-sm md:text-base text-white px-4 py-1 flex justify-between items-center">
                                <span>Special Sale | Two Days Left</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>

                            <div class="clr-size-qty p-4">
                                <!-- Color Selection -->

                                <!-- Size Selection -->
                                @foreach ($product->product_attributes as $product_attribute)
                                    <div class="size mt-3">
                                        <div class="text-davy-gray flex items-center gap-2">
                                            <h6 class="sm:text-lg">{{ $product_attribute->name }} :</h6>
                                            {{-- <a href="#"
                                                class="inline-flex items-center hover:text-violet-700 hover:underline eq">
                                                <img src="{{ asset('assets/frontend/images/size-scale.png') }}"
                                                    alt="Size Chart" class="w-10 xsm:w-14 h-auto" />
                                                <span class="text-xs"> Size Chart</span>
                                            </a> --}}
                                            {{-- <a href="#"
                                                class="hover:text-light-yellow hover:underline eq ml-2 xsm:ml-4">
                                                <span class="text-xs"> What's My Size?</span>
                                            </a> --}}
                                        </div>
                                        <form class="flex flex-wrap items-center gap-2 mt-2 text-xs">
                                            @foreach ($product_attribute->product_attribute_options as $option)
                                                <div class="form-ctrl">
                                                    <input id="{{ $option->value }}" type="radio"
                                                        value="{{ $option->value }}"
                                                        data-additional-price="{{ $option->additional_price }}"
                                                        name="product_attribute_{{ $product_attribute->id }}"
                                                        class="hidden peer option-selector" />
                                                    <label for="{{ $option->value }}"
                                                        class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer">{{ strtoupper($option->value) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </form>

                                    </div>
                                @endforeach
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
                        @php
                            $discount = ($product->discount_amount / $product->selling_price) * 100;
                        @endphp
                        <div class="flex gap-4 mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <button
                                class="text-sm md:text-base font-medium flex-1 px-6 py-1.5 border border-primary text-primary rounded-full hover:bg-primary hover:text-white eq">
                                Add To Cart
                                <span class="block text-xs font-light">{{ percentage($discount) }} of Discount</span>
                            </button>
                            <button
                                class="text-sm md:text-base font-medium flex-1 px-6 py-1.5 bg-primary text-white rounded-full hover:bg-theme-dark eq">
                                Buy Now
                                <span class="block text-xs font-light">Faster Dispatch</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rating Overview Section -->
                <div class="flex flex-col md:flex-row gap-5 items-start py-8 md:py-10">
                    <!-- Left Column -->
                    <div class="order-2 md:order-1 lg:w-[55%] md:w-[50%] w-full">
                        <!-- Overall Rating -->
                        <div class="flex items-start gap-4">
                            <div class="font-[arial] space-y-1">
                                <div class="text-4xl md:text-5xl text-persian-blue">
                                    94.0%
                                </div>
                                <div class="flex text-yellow-400 text-3xl md:text-4xl">
                                    ★★★★★
                                </div>
                                <div class="text-davy-gray text-xs sm:text-sm">
                                    (Positive reviews)
                                    <span class="text-primary/80 font-semibold lg:pl-4">Top</span>
                                </div>
                            </div>

                            <!-- Rating Bars -->
                            <div class="ratings-wrap w-full sm:w-2/4 md:w-3/4 2xl:w-1/2 lg:w-2/3 space-y-1 md:space-y-2">
                                <!-- 5 star -->
                                <div class="flex gap-2 md:gap-5 w-full items-center gap-2">
                                    <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                        <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5">
                                            <div class="bg-yellow-400 h-2 rounded-full lg:h-2.5" style="width: 90%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm text-persian-blue">(05 star)</span>
                                </div>
                                <!-- 4 star -->
                                <div class="flex gap-2 md:gap-5 w-full items-center gap-2">
                                    <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                        <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5">
                                            <div class="bg-yellow-400 h-2 rounded-full lg:h-2.5" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm text-persian-blue">(04 star)</span>
                                </div>
                                <!-- 3 star -->
                                <div class="flex gap-2 md:gap-5 w-full items-center gap-2">
                                    <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                        <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5">
                                            <div class="bg-yellow-400 h-2 rounded-full lg:h-2.5" style="width: 55%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm text-persian-blue">(03 star)</span>
                                </div>
                                <!-- 2 star -->
                                <div class="flex gap-2 md:gap-5 w-full items-center gap-2">
                                    <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                        <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5">
                                            <div class="bg-yellow-400 h-2 rounded-full lg:h-2.5" style="width: 40%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm text-persian-blue">(02 star)</span>
                                </div>
                                <!-- 1 star -->
                                <div class="flex gap-2 md:gap-5 w-full items-center gap-2">
                                    <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                        <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5">
                                            <div class="bg-yellow-400 h-2 rounded-full lg:h-2.5" style="width: 20%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm text-persian-blue">(01 star)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Average Rating -->
                        <div class="flex flex-wrap items-center gap-3 my-3 md:my-5">
                            <span class="text-davy-gray text-xl sm:text-2xl font-medium">4.8</span>
                            <div class="flex flex-nowrap gap-1 text-xs md:text-sm">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star text-gray-400"></i>
                            </div>
                            <span class="text-sm sm:text-base text-jet-gray">(5K+ Review)</span>
                        </div>

                        <!-- Review Section -->
                        <div class="comments-tags text-sm lg:text-base text-davy-gray">
                            <h4>Item Reviews</h4>
                            <!-- review tags -->
                            <div class="review-tags flex flex-wrap gap-2 lg:gap-3 my-3 md:my-5 font-medium">
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray gap-2">
                                    <span class="flag-wrap h-5 lg:h-7 w-auto"><img class="w-auto h-full object-contain"
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
                            <div class="reviews-wrapper space-y-5 pt-5">
                                <!-- review 1 -->
                                <div class="review-item space-y-2">
                                    <div class="flex items-center gap-3">
                                        <div class="user-avatar w-12 h-12 rounded-full overflow-hidden">
                                            <img src="{{ asset('assets/frontend/images/user-avatar-1.png') }}"
                                                alt="Alan Walker" />
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                                            <h3 class="font-medium">Alan Walker</h3>
                                            <span class="flex gap-2 text-gray-400">
                                                In
                                                <span class="h-4 lg:h-6 w-auto"><img class="w-auto h-full object-contain"
                                                        src="{{ asset('assets/frontend/images/us-flag.png') }}"
                                                        alt="Flag of USA" /></span>
                                                on Jan 20, 2025
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Rating -->
                                    <div class="rating flex flex-wrap items-center gap-3">
                                        <div class="flex flex-nowrap gap-1 text-theme-dark text-xs md:text-sm">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <span class="text-davy-gray text-lg sm:text-xl font-medium">5.0</span>
                                    </div>

                                    <h6 class="product-colour">Purchased : Black</h6>
                                    <p class="product-feedback w-10/12 sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        Absolutely beautiful, good price perfect, perfect
                                        excellent product, very nice quality 😇😇
                                    </p>

                                    <div
                                        class="flex justify-center items-center text-black text-xs xsm:text-sm lg:text-base xl:text-lg w-10/12 sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        <div class="flex items-start divide-x divide-black gap-3">
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
                                            <button class="pl-2 flex items-center gap-2 hover:text-butterfly-blue eq">
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
                                                <button class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                                    Not Helpful
                                                </button>

                                                <button
                                                    class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-persian-red">
                                                    Report Abuse
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- review 2 -->
                                <div class="review-item space-y-2">
                                    <div class="flex items-center gap-3">
                                        <div class="user-avatar w-12 h-12 rounded-full overflow-hidden">
                                            <img src="{{ asset('assets/frontend/images/user-avatar-2.png') }}"
                                                alt="Josesph Man" />
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                                            <h3 class="font-medium">Josesph Man</h3>
                                            <span class="flex gap-2 text-gray-400">
                                                In
                                                <span class="h-4 lg:h-6 w-auto"><img class="w-auto h-full object-contain"
                                                        src="{{ asset('assets/frontend/images/us-flag.png') }}"
                                                        alt="Flag of USA" /></span>
                                                on Jan 22, 2025
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Rating -->
                                    <div class="rating flex flex-wrap items-center gap-3">
                                        <div class="flex flex-nowrap gap-1 text-theme-dark text-xs md:text-sm">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <span class="text-davy-gray text-lg sm:text-xl font-medium">5.0</span>
                                    </div>

                                    <h6 class="product-colour">Purchased : Green</h6>
                                    <p class="product-feedback w-10/12 sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        Fantastic product at a great price. Truly impressed with
                                        the exceptional quality. Beautifully crafted and exceeds
                                        expectations 🥰 Highly recommend✅
                                    </p>

                                    <div
                                        class="flex justify-center items-center text-black text-xs xsm:text-sm lg:text-base xl:text-lg w-10/12 sm:w-3/5 md:w-4/5 xl:w-3/5">
                                        <div class="flex items-start divide-x divide-black gap-3">
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
                                            <button class="pl-2 flex items-center gap-2 hover:text-butterfly-blue eq">
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
                                                <button class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                                    Not Helpful
                                                </button>

                                                <button
                                                    class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-persian-red">
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
                                    class="w-3 h-3 object-contain" />
                                #Top Rated</span>
                            <p class="text-davy-gray text-sm">In Men's Iteams</p>
                        </div>
                        <!-- Shipping Info -->
                        <div class="flex items-center gap-2 mb-5">
                            <img src="{{ asset('assets/frontend/images/carbon_delivery.png') }}" alt="Shipping"
                                class="w-7 h-7 object-contain" />
                            <span class="font-medium text-davy-gray">Ships From Tesco</span>
                        </div>

                        <!-- Shipping Options -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 gap-4">
                            <!-- Standard Shipping -->
                            <div class="bg-[#F5F5F5] p-4 rounded-lg text-davy-gray lg:space-y-2 space-y-1">
                                <h4 class="text-sm font-semibold text-black">
                                    Standard <span class="text-leaf-green">: Free</span>
                                </h4>
                                <p class="text-xs">
                                    <span class="text-sm">Delivery :</span> Fastest Delivery in
                                    5 Business days
                                </p>
                                <div class="text-xs flex items-center gap-1">
                                    <span class="text-sm">Courier Company :</span>
                                    <img src="{{ asset('assets/frontend/images/dhl-logo.png') }}" alt="DHL"
                                        class="h-4 w-auto object-contain" />
                                    <span>DHL</span>
                                    <img src="{{ asset('assets/frontend/images/ups-logo.png') }}" alt="UPS"
                                        class="h-4 w-auto object-contain" />
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
                                <div class="text-xs flex items-center gap-1">
                                    <span class="text-sm">Courier Company :</span>
                                    <img src="{{ asset('assets/frontend/images/dhl-logo.png') }}" alt="DHL"
                                        class="h-4 w-auto object-contain" />
                                    <span>DHL</span>
                                </div>
                            </div>
                        </div>

                        <!-- Commitments -->
                        <div class="mt-5">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="tesko-icon w-10 h-10 overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/tesko-icon.png') }}" alt="Tesko Icon"
                                        class="w-full h-full object-contain" />
                                </div>
                                <span class="font-medium text-davy-gray">Our Commitments</span>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 gap-4 w-full xsm:w-4/5 md:w-11/12 lg:w-4/6 xl:w-11/12 2xl:w-4/5">
                                <!-- Security & Privacy -->
                                <div class="bg-[#F5F5F5] p-4 rounded-lg">
                                    <h3 class="text-leaf-green mb-2">Security & Privacy</h3>
                                    <ul class="space-y-1 lg:space-y-2 text-sm text-davy-gray">
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
                                    <h3 class="text-leaf-green mb-2">Delivery Guarantee</h3>
                                    <ul class="space-y-1 lg:space-y-2 text-sm text-davy-gray">
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
                <div class="load-more-btn text-center border-b-2 border-gray-400 border-dashed pb-10">
                    <button
                        class="theme-btn bg-theme-teal hover:bg-aqua-deep text-white px-5 py-2 xl:text-xl text-base md:text-lg inline-flex gap-2 items-center eq"
                        type="button">
                        <span>Load More</span>
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- Product Provider Section Starts -->
        <section class="product-provider-sec py-5 md:py-8 text-sm md:text-base xl:text-lg text-davy-gray">
            <div class="container">
                <!-- Header -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                    <a href="{{ route('seller.shop_details', $product->seller->username) }}"
                        class="inline-block provider-dp w-10 h-10 lg:w-14 lg:h-14 rounded-full overflow-hidden">
                        <img src="{{ asset('assets/frontend/images/provider-logo-1.png') }}" alt="Louis Vuitton Logo"
                            class="w-full h-full object-contain" />
                    </a>
                    <div class="provider-info">
                        <h2 class="text-lg md:text-xl lg:text-2xl font-medium flex items-center gap-2 md:gap-5">
                            <a href="{{ route('seller.shop_details', $product->seller->username) }}"
                                class="hover:text-butterfly-blue eq">{{ $product->seller->business_name }}</a>
                            <p class="text-sm md:text-base xl:text-lg font-light flex items-center gap-2">
                                <button class="hover:text-primary eq">
                                    <i class="fa-regular fa-comment-dots"></i>
                                </button>
                                <span>Contact With Provider</span>
                            </p>
                        </h2>

                        <!-- Metrics -->
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span>5.5k+ Followers .</span>
                            <span>{{ number_shorten_format($total_sell) }} Sold .</span>
                            <span class="flex items-center gap-1">
                                <span>5.00</span>
                                <i class="fa-solid fa-star text-theme-dark"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Buttons -->
                <div class="flex flex-wrap font-medium items-center gap-2 md:gap-4 mt-4">
                    <button
                        class="inline-flex items-center py-1.5 px-5 xsm:px-8 lg:px-10 lg:py-2.5 border border-jet-gray theme-btn gap-2 hover:bg-leaf-green hover:text-white hover:border-transparent eq text-sm md:text-base lg:text-xl font-inherit">
                        <i class="fa-solid fa-store"></i>
                        Follow
                    </button>
                    <a href="{{ route('seller.shop_details', $product->seller->username) }}"
                        class="inline-flex items-center py-1.5 px-5 xsm:px-8 lg:px-10 lg:py-2.5 border border-jet-gray theme-btn gap-2 hover:bg-primary hover:text-white hover:border-transparent eq text-sm md:text-base lg:text-xl font-inherit">
                        <span>Shop All Items</span>
                        ({{ count($products) }})
                    </a>
                </div>

                <div class="shop-decriptions w-full md:w-2/3 lg:w-1/2">
                    <!-- Description -->
                    <div class="mt-5">
                        <h2>Description:</h2>
                        <p class="mt-2">
                            {{ $product->description }}
                        </p>
                    </div>
                    <!-- Details -->
                    {{-- <div class="mt-5">
                        <h2>Details:</h2>
                        <div class="mt-2">
                            <h3 class="font-extrabold">Highlights</h3>
                            <p>· Cotton linen · Garment dyed · Two-button closure</p>
                            <h3 class="font-extrabold mt-2">Shape & Fit</h3>
                            <p>· Regular fit · Our model is 1.86m and wears size 50</p>
                            <h3 class="font-extrabold mt-2">Composition & Care</h3>
                            <p>· 66% cotton, 34% linen</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>
        <!-- Product Provider Section Ended -->

        <!-- Explore Interest Section Start  -->
        <section class="explore-interest section-padding">
            <div class="container">
                <!-- Section Tittle -->
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-medium text-jet-gray mb-5 md:mb-8 lg:mb-10">
                    Explore Your Interest
                </h1>

                <div id="product-wrapper"
                    class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-3 gap-5 xl:gap-8 lg:p-0 p-2 items-start">
                    <!-- Product Card 1 -->
                    @foreach ($interest_products as $product)
                        <div
                            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq">
                            <div
                                class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg">
                                <a href="{{ route('product_details', $product->slug) }}" class="block w-full h-full">
                                    <img src="{{ asset('assets/' . $product->thumbnail) }}"
                                        alt="The Iconic Doeskin Blazer" class="w-full h-full object-cover" />
                                </a>
                                <button
                                    class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                    <i class="fa-regular fa-eye"></i>
                                    Quick View
                                </button>
                            </div>

                            <div class="p-4 xsm:p-2 lg:p-5">
                                <h3
                                    class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3">
                                    <a href="{{ route('product_details', $product->slug) }}"
                                        class="hover:text-primary eq">{{ $product->name }}</a>
                                </h3>
                                <p class="text-leaf-green">Almost sold Out</p>

                                <div class="flex flex-wrap items-center gap-x-1">
                                    <div class="flex items-center flex-no-wrap gap-x-1 text-light-yellow">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <img src="{{ asset('assets/frontend/images/fire-icon.png') }}" class="w-8 h-auto"
                                            alt="Fire Icon" />
                                    </div>

                                    <span class="text-jet-gray">{{ number_shorten_format($product->stock_out) }}
                                        Sold</span>
                                </div>

                                <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                    <span class="text-primary/80">Final Hours</span>
                                    <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                        <div class="price flex items-center gap-1 flex-no-wrap">
                                            <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                            {{-- <span class="align-center text-sm text-[#ffa755]">$</span> --}}
                                            <h3 class="font-bold text-primary">{{ currency($product->selling_price) }}
                                            </h3>
                                        </div>
                                        <div>
                                            <button
                                                class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Load More Products Button -->
                <div class="load-more-btn text-center pt-10">
                    <button id="load-more-products"
                        class="theme-btn bg-theme-teal hover:bg-aqua-deep text-white px-5 py-2 xl:text-xl text-base md:text-lg inline-flex gap-2 items-center eq"
                        type="button">
                        <span>Load More</span>
                        <i class="fa-solid fa-caret-down"></i>
                    </button>
                </div>
            </div>
        </section>
        <!-- Explore Interest Section Ended  -->
    </main>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let discountedPrice = parseFloat($('#current-price').text().replace(/[^0-9.]/g, ''));

                let withoutDiscountPrice = parseFloat($('.old-price').text().replace(/[^0-9.]/g, ''));

                $('.option-selector').change(function() {
                    let totalAdditionalPrice = 0;

                    $('.option-selector:checked').each(function() {
                        totalAdditionalPrice += parseFloat($(this).data('additional-price')) || 0;
                    });

                    let newPrice = discountedPrice + totalAdditionalPrice;
                    let oldPrice = withoutDiscountPrice + totalAdditionalPrice;

                    $('#current-price').text(new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(newPrice));

                    $('.old-price').text(new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(oldPrice));
                });
            });
        </script>
    @endpush
@endsection
