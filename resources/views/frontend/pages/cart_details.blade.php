@extends('frontend.layouts.app')
@section('title', 'Card Details')

@section('content')
    <main class="cart-details-page pb-5 sm:pb-10">
        <!-- Promotional Header Starts -->
        <section>
            <a href="#" class="block promo-header bg-light-yellow text-white py-3 sm:py-4">
                <div class="container flex flex-wrap justify-center xsm:justify-start items-center gap-x-2">
                    <i class="fa-solid fa-truck-fast text-lg"></i>
                    <h3 class="text-sm">Free Shipping Special For You</h3>
                    <p class="text-xs ml-2 xsm:ml-3">Limited Offer</p>
                </div>
            </a>
        </section>
        <!-- Promotional Header Ended -->

        <!-- Cart Details Main Section Starts -->
        <section class="page-breadcrumb-links container">
            <!-- Page Breadcrumb -->
            <nav class="flex container my-2 md:my-5" aria-label="Breadcrumb">
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-davy-gray md:ms-2">Cart</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid gap-5 xl:gap-10 2xl:gap-20 lg:grid-cols-3">
                <!-- Cart Items Main Section -->
                <div class="lg:col-span-2">
                    <!-- Free Shipping Banner -->
                    <div
                        class="text-sm justify-center lg:text-base text-rustic-red bg-[#E6F3E5] px-4 py-3 flex flex-wrap flex-col xsm:flex-row justify-between items-center my-2 md:my-5">
                        <div class="flex items-center gap-2 text-center">
                            <i class="fa-solid fa-check text-theme-teal"></i>
                            <span>Free shipping special for you</span>
                        </div>
                        <span class="text-leaf-green italic font-light">Exclusive offer</span>
                    </div>

                    <!-- Select All Checkbox -->
                    <label for="selectAll"
                        class="w-full flex items-center justify-between cursor-pointer text-black hover:text-black/80 my-3 md:my-4">
                        <p class="md:text-lg flex items-center gap-2">
                            <input type="checkbox" id="selectAll" class="hidden form-checkbox peer/selectAll" />
                            <label for="selectAll"
                                class="inline-block stroke-black peer-checked/selectAll:stroke-white rounded-full text-white peer-checked/selectAll:text-black border-2 border-black cursor-pointer">
                                <svg width="32" height="32" class="w-6 md:w-7 h-6 md:h-7" viewBox="0 0 32 32"
                                    stroke-width="0" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="16" cy="16" r="16" fill="currentColor" />
                                    <path
                                        d="M9.58789 18.2939C9.58789 18.2939 10.9629 18.2939 12.7962 21.5023C12.7962 21.5023 17.892 13.0992 22.4212 11.4189"
                                        stroke-width="1.79853" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </label>

                            Select All (<span id="selectedCount">0</span>)
                        </p>

                        <i class="fa-solid fa-list-ul"></i>
                    </label>

                    <!-- Cart Items Container -->
                    <div id="cart-wrapper">
                        <!-- Cart Item 1 -->
                        <div class="md:py-5 py-3 cart-item border-t border-jet-gray/20" data-price="1599.50"
                            data-discounted-price="959.70">
                            <div class="flex gap-2 sm:gap-4">
                                <!-- Item Image -->
                                <div
                                    class="item-image-wrap w-24 h-28 xsm:w-36 xsm:h-40 rounded-md relative overflow-hidden">
                                    <a href="#">
                                        <img src="{{ asset('assets/frontend/images/cart-prod-1.png') }}" alt="Product"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <span
                                        class="w-10/12 xsm:w-7/12 text-center text-leaf-green text-[8px] inline-block absolute bottom-3 xsm:bottom-5 left-1/2 -translate-x-1/2 bg-theme-dark text-white rounded-3xl py-1">Almost
                                        Sold Out</span>
                                    <!-- Custom Check Icon -->
                                    <label for="item-1"
                                        class="inline-flex items-center justify-between cursor-pointer text-black hover:text-black/80 absolute top-2 left-2">
                                        <input id="item-1" type="checkbox"
                                            class="product-checkbox hidden form-checkbox peer/item-1" />
                                        <label for="item-1"
                                            class="inline-block stroke-black peer-checked/item-1:stroke-white rounded-full text-white peer-checked/item-1:text-black border-2 border-black cursor-pointer">
                                            <svg width="32" height="32" class="w-6 md:w-7 h-6 md:h-7"
                                                viewBox="0 0 32 32" stroke-width="0" fill="currentColor"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="16" cy="16" r="16" fill="currentColor" />
                                                <path
                                                    d="M9.58789 18.2939C9.58789 18.2939 10.9629 18.2939 12.7962 21.5023C12.7962 21.5023 17.892 13.0992 22.4212 11.4189"
                                                    stroke-width="1.79853" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </label>
                                    </label>
                                </div>
                                <!-- Item Content -->
                                <div class="flex flex-col gap-2 sm:gap-5 flex-1">
                                    <div class="space-y-1 sm:space-y-2">
                                        <!-- title -->
                                        <div class="flex items-start justify-between">
                                            <h1
                                                class="md:text-base text-rustic-red text-sm w-11/12 xsm:w-10/12 md:w-3/4 lg:w-11/12 xl:w-3/4 line-clamp-3 sm:line-clamp-2">
                                                Men's Fit Stretch Jaket - Caramel, Woven Fabric,
                                                Matching Washable - Perfect for Business Casual &
                                                Summer Wear
                                            </h1>
                                            <button class="hover:text-persian-red eq lg:text-xl xsm:text-lg">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                        <!-- size -->
                                        <p class="text-xs xsm:text-sm text-gray-500 uppercase">
                                            Label Size: XL
                                        </p>
                                        <!-- limited time -->
                                        <p class="text-xs xsm:text-sm text-persian-red">
                                            Big Sale / Limited Time
                                        </p>
                                    </div>

                                    <!-- Prices & Quantity Controls -->
                                    <div class="flex flex-wrap gap-y-3 items-center justify-between">
                                        <!-- price  -->
                                        <div class="flex flex-wrap items-center gap-2">
                                            <div class="new-price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755] lg:text-lg"></i>
                                                <span
                                                    class="align-center text-xs xsm:text-sm lg:text-base text-[#ffa755]">$</span>
                                                <h3
                                                    class="current-price text-sm xsm:text-lg md:text-xl font-bold text-primary">
                                                    959.70
                                                </h3>
                                            </div>
                                            <h6
                                                class="old-price text-xs xsm:text-sm xsm:text-base text-jet-gray line-through">
                                                $ 1599.50
                                            </h6>
                                            <span
                                                class="text-xs xsm:text-sm px-2.5 py-0.5 rounded-lg border border-primary">-
                                                40% last 2 days</span>
                                        </div>
                                        <!-- quantity -->
                                        <div class="quantity-controls">
                                            <div class="text-davy-gray flex flex-nowrap items-center gap-2">
                                                <h6 class="text-sm xsm:text-base sm:text-lg">
                                                    Quantity :
                                                </h6>
                                                <div class="flex items-center border rounded p-1">
                                                    <button
                                                        class="decrease-qty w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <input readonly type="number" value="1" min="1"
                                                        class="quantity-input text-center text-persian-blue w-12 h-5 text-sm font-medium border-0 focus:ring-0" />
                                                    <button
                                                        class="increase-qty w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Item 2 -->
                        <div class="md:py-5 py-3 cart-item border-t border-jet-gray/20" data-price="2500.00"
                            data-discounted-price="2000.00">
                            <div class="flex gap-2 sm:gap-4">
                                <!-- Item Image -->
                                <div
                                    class="item-image-wrap w-24 h-28 xsm:w-36 xsm:h-40 rounded-md relative overflow-hidden">
                                    <a href="#">
                                        <img src="{{ asset('assets/frontend/images/cart-prod-2.png') }}" alt="Product"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <span
                                        class="w-10/12 xsm:w-7/12 text-center text-leaf-green text-[8px] inline-block absolute bottom-3 xsm:bottom-5 left-1/2 -translate-x-1/2 bg-theme-dark text-white rounded-3xl py-1">Almost
                                        Sold Out</span>
                                    <!-- Custom Check Icon -->
                                    <label for="item-2"
                                        class="inline-flex items-center justify-between cursor-pointer text-black hover:text-black/80 absolute top-2 left-2">
                                        <input id="item-2" type="checkbox"
                                            class="product-checkbox hidden form-checkbox peer/item-2" />
                                        <label for="item-2"
                                            class="inline-block stroke-black peer-checked/item-2:stroke-white rounded-full text-white peer-checked/item-2:text-black border-2 border-black cursor-pointer">
                                            <svg width="32" height="32" class="w-6 md:w-7 h-6 md:h-7"
                                                viewBox="0 0 32 32" stroke-width="0" fill="currentColor"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="16" cy="16" r="16" fill="currentColor" />
                                                <path
                                                    d="M9.58789 18.2939C9.58789 18.2939 10.9629 18.2939 12.7962 21.5023C12.7962 21.5023 17.892 13.0992 22.4212 11.4189"
                                                    stroke-width="1.79853" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </label>
                                    </label>
                                </div>
                                <!-- Item Content -->
                                <div class="flex flex-col gap-2 sm:gap-5 flex-1">
                                    <div class="space-y-1 sm:space-y-2">
                                        <!-- title -->
                                        <div class="flex items-start justify-between">
                                            <h1
                                                class="md:text-base text-rustic-red text-sm w-11/12 xsm:w-10/12 md:w-3/4 lg:w-11/12 xl:w-3/4 line-clamp-3 sm:line-clamp-2">
                                                The Iconic Doeskin Blazer - Caramel, Woven Fabric,
                                                Machine Washable - Perfect for Business Casual &
                                                Summer Wear
                                            </h1>
                                            <button class="hover:text-persian-red eq lg:text-xl xsm:text-lg">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                        <!-- size -->
                                        <p class="text-xs xsm:text-sm text-gray-500 uppercase">
                                            Label Size: L
                                        </p>
                                        <!-- limited time -->
                                        <p class="text-xs xsm:text-sm text-persian-red">
                                            Big Sale / Limited Time
                                        </p>
                                    </div>

                                    <!-- Prices & Quantity Controls -->
                                    <div class="flex flex-wrap gap-y-3 items-center justify-between">
                                        <!-- price  -->
                                        <div class="flex flex-wrap items-center gap-2">
                                            <div class="new-price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755] lg:text-lg"></i>
                                                <span
                                                    class="align-center text-xs xsm:text-sm lg:text-base text-[#ffa755]">$</span>
                                                <h3
                                                    class="current-price text-sm xsm:text-lg md:text-xl font-bold text-primary">
                                                    2000.00
                                                </h3>
                                            </div>
                                            <h6
                                                class="old-price text-xs xsm:text-sm xsm:text-base text-jet-gray line-through">
                                                $ 2500.00
                                            </h6>
                                            <span
                                                class="text-xs xsm:text-sm px-2.5 py-0.5 rounded-lg border border-primary">-
                                                20% last 2 days</span>
                                        </div>
                                        <!-- quantity -->
                                        <div class="quantity-controls">
                                            <div class="text-davy-gray flex flex-nowrap items-center gap-2">
                                                <h6 class="text-sm xsm:text-base sm:text-lg">
                                                    Quantity :
                                                </h6>
                                                <div class="flex items-center border rounded p-1">
                                                    <button
                                                        class="decrease-qty w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <input readonly type="number" value="1" min="1"
                                                        class="quantity-input text-center text-persian-blue w-12 h-5 text-sm font-medium border-0 focus:ring-0" />
                                                    <button
                                                        class="increase-qty w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommendations section -->
                    <div class="border-t pt-10">
                        <h2 class="md:text-2xl text-xl font-semibold mb-4">
                            You May Like to ADD
                        </h2>

                        <div class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-3 sm:gap-6 gap-3">
                            <!-- Product Card 1 -->
                            <div
                                class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq">
                                <div
                                    class="relative h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60 overflow-hidden rounded-lg">
                                    <a href="#" class="block w-full h-full">
                                        <img src="{{ asset('assets/frontend/images/electronic-prod-1.png') }}"
                                            alt="ASUS Vivo15 OLED K513 Core-i5 11th Gen 15.6″ FHD Laptop"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <button
                                        class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                        <i class="fa-regular fa-eye"></i>
                                        Quick View
                                    </button>
                                </div>

                                <div class="p-4 xsm:p-2 lg:p-5">
                                    <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                                        <a href="#"
                                            class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">ASUS
                                            Vivo15 OLED K513 Core-i5 11th Gen 15.6″ FHD
                                            Laptop</a>
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

                                        <span class="text-jet-gray">4.5K+ Sold</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                        <span class="text-primary/80">Final Hours</span>
                                        <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                            <div class="price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                                <span class="align-center text-sm text-[#ffa755]">$</span>
                                                <h3 class="font-bold text-primary">25.89</h3>
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
                            <!-- Product Card 2 -->
                            <div
                                class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq">
                                <div
                                    class="relative h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60 overflow-hidden rounded-lg">
                                    <a href="#" class="block w-full h-full">
                                        <img src="{{ asset('assets/frontend/images/electronic-prod-2.png') }}"
                                            alt="Apple watch series 10 depth rainmaker"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <button
                                        class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                        <i class="fa-regular fa-eye"></i>
                                        Quick View
                                    </button>
                                </div>

                                <div class="p-4 xsm:p-2 lg:p-5">
                                    <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                                        <a href="#"
                                            class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">Apple
                                            watch series 10 depth rainmaker</a>
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

                                        <span class="text-jet-gray">2.8K+ Sold</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                        <span class="text-primary/80">Final Hours</span>
                                        <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                            <div class="price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                                <span class="align-center text-sm text-[#ffa755]">$</span>
                                                <h3 class="font-bold text-primary">30.50</h3>
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
                            <!-- Product Card 3 -->
                            <div
                                class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq">
                                <div
                                    class="relative h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60 overflow-hidden rounded-lg">
                                    <a href="#" class="block w-full h-full">
                                        <img src="{{ asset('assets/frontend/images/electronic-prod-3.png') }}"
                                            alt="Quadcopter With Height Hold, App Control, And Obstacle For flying"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <button
                                        class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                        <i class="fa-regular fa-eye"></i>
                                        Quick View
                                    </button>
                                </div>

                                <div class="p-4 xsm:p-2 lg:p-5">
                                    <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                                        <a href="#"
                                            class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">Quadcopter
                                            With Height Hold, App Control, And Obstacle
                                            For flying</a>
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

                                        <span class="text-jet-gray">1.2K+ Sold</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                        <span class="text-primary/80">Final Hours</span>
                                        <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                            <div class="price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                                <span class="align-center text-sm text-[#ffa755]">$</span>
                                                <h3 class="font-bold text-primary">45.34</h3>
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
                            <!-- Product Card 4 -->
                            <div
                                class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq">
                                <div
                                    class="relative h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60 overflow-hidden rounded-lg">
                                    <a href="#" class="block w-full h-full">
                                        <img src="{{ asset('assets/frontend/images/electronic-prod-4.png') }}"
                                            alt="Sports Wireless Headphones, ANC and ENC Headphone"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <button
                                        class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                        <i class="fa-regular fa-eye"></i>
                                        Quick View
                                    </button>
                                </div>

                                <div class="p-4 xsm:p-2 lg:p-5">
                                    <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                                        <a href="#"
                                            class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">Sports
                                            Wireless Headphones, ANC and ENC Headphone</a>
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

                                        <span class="text-jet-gray">6.2K+ Sold</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                        <span class="text-primary/80">Final Hours</span>
                                        <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                            <div class="price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                                <span class="align-center text-sm text-[#ffa755]">$</span>
                                                <h3 class="font-bold text-primary">80.00</h3>
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
                            <!-- Product Card 5 -->
                            <div
                                class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq">
                                <div
                                    class="relative h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60 overflow-hidden rounded-lg">
                                    <a href="#" class="block w-full h-full">
                                        <img src="{{ asset('assets/frontend/images/electronic-prod-5.png') }}"
                                            alt="SAMSUNG GALAXY A15 LTE Blue 6 +128GB Dual Sim, Smartphone"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <button
                                        class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                        <i class="fa-regular fa-eye"></i>
                                        Quick View
                                    </button>
                                </div>

                                <div class="p-4 xsm:p-2 lg:p-5">
                                    <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                                        <a href="#"
                                            class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">SAMSUNG
                                            GALAXY A15 LTE Blue 6 +128GB Dual Sim,
                                            Smartphone</a>
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

                                        <span class="text-jet-gray">4.8K+ Sold</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                        <span class="text-primary/80">Final Hours</span>
                                        <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                            <div class="price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                                <span class="align-center text-sm text-[#ffa755]">$</span>
                                                <h3 class="font-bold text-primary">30.50</h3>
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
                            <!-- Product Card 6 -->
                            <div
                                class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq">
                                <div
                                    class="relative h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60 overflow-hidden rounded-lg">
                                    <a href="#" class="block w-full h-full">
                                        <img src="{{ asset('assets/frontend/images/electronic-prod-6.png') }}"
                                            alt="Electric
                  Bike, 500W Motor, 14'' Tire Folding Mini Ebikes"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <button
                                        class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                        <i class="fa-regular fa-eye"></i>
                                        Quick View
                                    </button>
                                </div>

                                <div class="p-4 xsm:p-2 lg:p-5">
                                    <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                                        <a href="#"
                                            class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">Electric
                                            Bike, 500W Motor, 14" Tire Folding Mini
                                            Ebikes</a>
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

                                        <span class="text-jet-gray">8.7K+ Sold</span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                        <span class="text-primary/80">Final Hours</span>
                                        <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                            <div class="price flex items-center gap-1 flex-no-wrap">
                                                <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                                <span class="align-center text-sm text-[#ffa755]">$</span>
                                                <h3 class="font-bold text-primary">20.25</h3>
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
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="mt-2 md:mt-5">
                        <h2 class="lg:text-xl md:text-lg font-semibold mb-4">
                            Order Summary
                        </h2>
                        <div class="order-summary">
                            <!-- summary -->
                            <div class="item-info space-y-2">
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Item's total:</span>
                                    <span id="itemsTotal" class="text-jet-gray line-through">$0.00</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Item Discount:</span>
                                    <span id="itemDiscount" class="text-primary font-bold">-$0.00</span>
                                </p>
                            </div>
                            <!-- estimated total -->
                            <div
                                class="total border-t-2 border-jet-gray/50 border-dashed pt-3 mt-6 flex justify-between font-medium">
                                <span>Estimated Total (<span id="selectedItemsCount">0</span>
                                    Items)</span>
                                <span id="estimatedTotal" class="text-xl">$0.00</span>
                            </div>
                        </div>

                        <!-- order action btn -->
                        <div class="mt-4 sm:mt-6 space-y-2 sm:space-y-3">
                            <a href="./checkout.html">
                                <button id="checkoutBtn"
                                    class="eq w-full flex flex-col items-center bg-jet-gray/40 text-white sm:py-3 py-2 rounded-full cursor-not-allowed"
                                    disabled>
                                    Checkout (0) <span class="text-xs">Almost Sold Out</span>
                                </button>
                            </a>
                            <button
                                class="eq w-full border border-jet-gray/50 text-theme-dark sm:py-3 py-2 rounded-full font-bold flex items-center justify-center xl:gap-2 gap-1 hover:bg-jet-gray/10 text-sm sm:text-base">
                                Express checkout with
                                <img src="{{ asset('assets/frontend/images/cart-paypal.png') }}" alt="PayPal" class="sm:h-9 h-6 w-auto" />
                            </button>
                        </div>

                        <!-- more info -->
                        <div class="text-davy-gray text-xs p-4">
                            <div class="space-y-3 sm:space-y-4">
                                <p class="space-x-1">
                                    <i class="fa-solid fa-circle-exclamation text-jet-gray/50"></i>
                                    <span>
                                        Item availability and pricing are not guaranteed until
                                        payment is final.
                                    </span>
                                </p>
                                <h2 class="text-xs sm:text-sm font-medium flex items-center gap-2">
                                    <i class="fa-solid fa-lock text-xl sm:text-2xl text-leaf-green"></i>
                                    <span>
                                        You will not be charged until you review this order on the
                                        next page
                                    </span>
                                </h2>
                                <h2 class="text-xs sm:text-sm font-medium flex items-center gap-2">
                                    <svg width="22" height="26" class="text-leaf-green w-6 h-6 sm:w-8 sm:h-8"
                                        viewBox="0 0 22 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.82462 0.150834C10.2917 -0.0243682 10.8022 -0.0475726 11.2833 0.0845311L11.4869 0.150834L19.7747 3.25877C20.1948 3.41626 20.5615 3.68983 20.8321 4.04763C21.1027 4.40543 21.2661 4.83275 21.3033 5.27981L21.3115 5.47635V11.826C21.3113 13.7443 20.7932 15.627 19.8119 17.2754C18.8306 18.9237 17.4225 20.2766 15.7362 21.1912L15.4213 21.357L11.4502 23.3413C11.2317 23.4507 10.9929 23.5138 10.7488 23.5266C10.5047 23.5394 10.2607 23.5018 10.0318 23.4159L9.86133 23.3425L5.89027 21.3558C4.17423 20.4978 2.72169 19.1923 1.68598 17.5773C0.650278 15.9623 0.0698315 14.0976 0.00592001 12.18L0 11.8248V5.47754C6.88487e-06 5.02896 0.127427 4.58962 0.367427 4.21065C0.607427 3.83168 0.950134 3.52867 1.35565 3.33691L1.5368 3.25995L9.82462 0.150834ZM9.64111 7.20377L7.28381 11.1322C7.17483 11.3137 7.11597 11.5208 7.11325 11.7325C7.11052 11.9441 7.16402 12.1527 7.26828 12.3369C7.37255 12.5211 7.52384 12.6743 7.70671 12.781C7.88958 12.8876 8.09746 12.9437 8.30913 12.9437H10.9328L9.64111 15.0973C9.49026 15.3659 9.45006 15.6828 9.52903 15.9805C9.60799 16.2783 9.79991 16.5336 10.064 16.6921C10.3282 16.8507 10.6437 16.9001 10.9436 16.8298C11.2436 16.7595 11.5043 16.5751 11.6704 16.3156L14.0277 12.3872C14.1367 12.2057 14.1956 11.9986 14.1983 11.787C14.201 11.5753 14.1475 11.3667 14.0433 11.1825C13.939 10.9983 13.7877 10.8451 13.6048 10.7385C13.422 10.6319 13.2141 10.5757 13.0024 10.5757H10.3787L11.6716 8.42208C11.8332 8.15266 11.8811 7.8301 11.8048 7.52535C11.7286 7.2206 11.5343 6.95863 11.2649 6.79707C10.9955 6.63552 10.6729 6.5876 10.3682 6.66387C10.0634 6.74014 9.80266 6.93435 9.64111 7.20377Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span> Safe Payment Options </span>
                                </h2>
                            </div>
                            <div class="mt-2 sm:mt-3 space-y-2">
                                <p class="text-sm text-leaf-green">
                                    Tesco is committed to protecting your payment information.
                                </p>
                                <p class="text-muted-foreground">
                                    We follow PCI DSS standards, use strong encryption, and
                                    perform regular reviews of its system to protect your
                                    privacy.
                                </p>
                            </div>
                            <!-- payment methods -->
                            <div class="mt-4 sm:mt-5">
                                <h4 class="text-sm">
                                    01. <span class="font-medium">Payment Method</span>
                                </h4>
                                <div class="flex flex-wrap gap-x-2 gap-y-1 mt-2">
                                    <img src="{{ asset('assets/frontend/images/cart-payment-method-1.png') }}" alt="Visa card"
                                        class="w-auto h-8 sm:h-10 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/cart-payment-method-2.png') }}" alt="mastercard"
                                        class="w-auto h-8 sm:h-10 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/cart-payment-method-3.png') }}" alt="American Express"
                                        class="w-auto h-8 sm:h-10 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/cart-payment-method-4.png') }}" alt="Discover"
                                        class="w-auto h-8 sm:h-10 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/cart-payment-method-5.png') }}" alt="Paypal"
                                        class="w-auto h-8 sm:h-10 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/cart-payment-method-6.png') }}" alt="Apple Pay"
                                        class="w-auto h-8 sm:h-10 border rounded" />
                                    <img src=".{{ asset('assets/frontend/images/cart-payment-method-7.png') }}" alt="G Pay"
                                        class="w-auto h-8 sm:h-10 border rounded" />
                                </div>
                            </div>
                            <!-- security certification -->
                            <div class="mt-3 sm:mt-4">
                                <h4 class="text-sm">
                                    02. <span class="font-medium">Security Certification</span>
                                </h4>
                                <div class="flex flex-wrap gap-x-2 gap-y-1 mt-2">
                                    <img src="{{ asset('assets/frontend/images/security-1.png') }}" alt="PCI DDS"
                                        class="w-auto h-7 sm:h-9 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/security-2.png') }}" alt="Visa Secure"
                                        class="w-auto h-7 sm:h-9 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/security-3.png') }}" alt="Mastercard ID check"
                                        class="w-auto h-7 sm:h-9 border rounded" />
                                    <img src="{{ asset('assets/frontend/images/security-4.png') }}" alt="American Express SafeKey"
                                        class="w-auto h-7 sm:h-9 border rounded" />
                                </div>
                            </div>
                            <!-- secure privacy -->
                            <div class="mt-5 space-y-2 sm:space-y-3">
                                <h4 class="text-sm font-medium flex items-center gap-2">
                                    <i class="fa-solid fa-lock text-xl sm:text-2xl text-leaf-green"></i>
                                    <span> Secure privacy </span>
                                </h4>

                                <p>
                                    Protecting your privacy is important to us! Please be
                                    assured that your information will be kept secured and
                                    uncompromised. We will only use your information in
                                    accordance with our privacy policy to provide and improve
                                    our services to you.
                                </p>

                                <a href="#"
                                    class="inline-flex items-center gap-2 text-primary hover:gap-3 hover:text-theme-dark eq">Learn
                                    More <i class="fa-solid fa-chevron-right"></i></a>
                            </div>
                            <!-- tesco purchase protection  -->
                            <div class="mt-3 sm:mt-5 space-y-2 sm:space-y-3">
                                <h4 class="text-sm font-medium flex items-center gap-1">
                                    <!-- cart icon -->
                                    <span class="text-leaf-green">
                                        <svg width="32" height="34" class="w-7 h-7 sm:w-9 sm:h-9"
                                            viewBox="0 0 32 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.34993 4.675H6.71953C6.40127 4.675 6.09605 4.54067 5.871 4.30156C5.64596 4.06245 5.51953 3.73815 5.51953 3.4C5.51953 3.06185 5.64596 2.73755 5.871 2.49844C6.09605 2.25933 6.40127 2.125 6.71953 2.125H9.28433C9.55133 2.12503 9.8107 2.21967 10.0214 2.39395C10.2321 2.56822 10.382 2.81218 10.4475 3.0872L12.8507 13.1801C13.3272 12.898 13.8637 12.7499 14.4091 12.75H27.1915C27.6757 12.7512 28.1533 12.8684 28.5889 13.0929C29.0246 13.3173 29.4069 13.6433 29.7075 14.0466C30.0081 14.4498 30.2193 14.9199 30.3253 15.4218C30.4312 15.9237 30.4293 16.4446 30.3195 16.9456L29.0667 22.8293C28.9074 23.5858 28.5106 24.2623 27.9416 24.7476C27.3726 25.233 26.6652 25.4983 25.9355 25.5H15.6635C14.9344 25.498 14.2275 25.2327 13.6589 24.7478C13.0902 24.2628 12.6935 23.5869 12.5339 22.831L11.2795 16.9439C11.2623 16.8623 11.2479 16.7801 11.2363 16.6974C11.2041 16.6203 11.1774 16.5407 11.1563 16.4594L8.34993 4.675ZM14.7995 31.45C15.5421 31.45 16.2543 31.1366 16.7794 30.5786C17.3045 30.0207 17.5995 29.264 17.5995 28.475C17.5995 27.686 17.3045 26.9293 16.7794 26.3714C16.2543 25.8134 15.5421 25.5 14.7995 25.5C14.0569 25.5 13.3447 25.8134 12.8196 26.3714C12.2945 26.9293 11.9995 27.686 11.9995 28.475C11.9995 29.264 12.2945 30.0207 12.8196 30.5786C13.3447 31.1366 14.0569 31.45 14.7995 31.45ZM25.9995 31.45C26.7421 31.45 27.4543 31.1366 27.9794 30.5786C28.5045 30.0207 28.7995 29.264 28.7995 28.475C28.7995 27.686 28.5045 26.9293 27.9794 26.3714C27.4543 25.8134 26.7421 25.5 25.9995 25.5C25.2569 25.5 24.5447 25.8134 24.0196 26.3714C23.4945 26.9293 23.1995 27.686 23.1995 28.475C23.1995 29.264 23.4945 30.0207 24.0196 30.5786C24.5447 31.1366 25.2569 31.45 25.9995 31.45Z"
                                                fill="currentColor" />
                                            <path
                                                d="M5.93842 4.25005H3.99922C3.78705 4.25005 3.58356 4.1605 3.43353 4.00109C3.2835 3.84168 3.19922 3.62548 3.19922 3.40005C3.19922 3.17461 3.2835 2.95841 3.43353 2.79901C3.58356 2.6396 3.78705 2.55005 3.99922 2.55005H6.56402C6.74247 2.5501 6.91578 2.61354 7.05639 2.73029C7.197 2.84704 7.29683 3.01038 7.34002 3.19435L10.3752 16.1143C10.4211 16.331 10.3854 16.558 10.2757 16.747C10.166 16.936 9.99114 17.0719 9.78839 17.1257C9.58564 17.1794 9.37114 17.1467 9.19077 17.0346C9.0104 16.9225 8.87848 16.7399 8.82322 16.5257L5.93842 4.25005Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M24.7909 12.75H12.0069C11.7659 12.75 11.5275 12.7783 11.2917 12.835C10.4595 13.0421 9.73837 13.5911 9.28618 14.3618C8.83399 15.1325 8.68756 16.0622 8.87893 16.9473L10.1317 22.831C10.2914 23.5872 10.6883 24.2633 11.2573 24.7483C11.8262 25.2333 12.5335 25.4984 13.2629 25.5H23.5381C24.2673 25.498 24.9742 25.2327 25.5428 24.7478C26.1115 24.2628 26.5081 23.5869 26.6677 22.831L27.9205 16.9456C27.9739 16.6951 28.0005 16.4407 28.0005 16.1823C28.0024 15.733 27.9209 15.2877 27.7606 14.8719C27.6004 14.4562 27.3645 14.078 27.0665 13.7592C26.7685 13.4404 26.4143 13.1872 26.0241 13.014C25.6339 12.8408 25.2154 12.7511 24.7925 12.75M11.6517 14.4925C11.7688 14.4642 11.8885 14.4499 12.0085 14.45H24.7909C25.0349 14.4514 25.2753 14.5113 25.4944 14.6253C25.7135 14.7392 25.9056 14.9042 26.0563 15.108C26.2071 15.3118 26.3125 15.5491 26.3649 15.8022C26.4173 16.0553 26.4153 16.3177 26.3589 16.5699L25.1045 22.4553C25.0252 22.835 24.8268 23.1748 24.5419 23.4191C24.2571 23.6634 23.9026 23.7977 23.5365 23.8H13.2629C12.5141 23.8 11.8629 23.2424 11.6949 22.4553L10.4421 16.5699C10.345 16.1239 10.4179 15.6552 10.6448 15.266C10.8717 14.8768 11.2326 14.5988 11.6517 14.4925Z"
                                                fill="currentColor" />
                                            <path
                                                d="M27.1984 28.475C27.1984 29.264 26.9034 30.0207 26.3783 30.5786C25.8532 31.1366 25.141 31.45 24.3984 31.45C23.6558 31.45 22.9436 31.1366 22.4185 30.5786C21.8934 30.0207 21.5984 29.264 21.5984 28.475C21.5984 27.686 21.8934 26.9293 22.4185 26.3714C22.9436 25.8134 23.6558 25.5 24.3984 25.5C25.141 25.5 25.8532 25.8134 26.3783 26.3714C26.9034 26.9293 27.1984 27.686 27.1984 28.475ZM15.9984 28.475C15.9984 28.8657 15.926 29.2525 15.7853 29.6135C15.6446 29.9744 15.4383 30.3024 15.1783 30.5786C14.9183 30.8549 14.6097 31.074 14.27 31.2235C13.9302 31.373 13.5661 31.45 13.1984 31.45C12.8307 31.45 12.4666 31.373 12.1269 31.2235C11.7872 31.074 11.4785 30.8549 11.2185 30.5786C10.9585 30.3024 10.7523 29.9744 10.6116 29.6135C10.4709 29.2525 10.3984 28.8657 10.3984 28.475C10.3984 27.686 10.6934 26.9293 11.2185 26.3714C11.7436 25.8134 12.4558 25.5 13.1984 25.5C13.941 25.5 14.6532 25.8134 15.1783 26.3714C15.7034 26.9293 15.9984 27.686 15.9984 28.475Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>

                                    <span>Tesco Purchase Protection </span>
                                </h4>
                                <p>
                                    Shop confidently on Temu knowing that if something goes
                                    wrong, we've always got your back.
                                </p>
                            </div>
                            <!-- delivary guarantee -->
                            <div class="mt-3 sm:mt-5">
                                <h4 class="text-sm font-medium flex items-center gap-2">
                                    <!-- truck icon -->
                                    <span class="text-leaf-green">
                                        <svg width="24" height="24" class="w-6 h-6 sm:w-7 sm:h-7"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3 4C2.46957 4 1.96086 4.21071 1.58579 4.58579C1.21071 4.96086 1 5.46957 1 6V17H3C3 17.7956 3.31607 18.5587 3.87868 19.1213C4.44129 19.6839 5.20435 20 6 20C6.79565 20 7.55871 19.6839 8.12132 19.1213C8.68393 18.5587 9 17.7956 9 17H15C15 17.7956 15.3161 18.5587 15.8787 19.1213C16.4413 19.6839 17.2044 20 18 20C18.7956 20 19.5587 19.6839 20.1213 19.1213C20.6839 18.5587 21 17.7956 21 17H23V12L20 8H17V4M10 6L14 10L10 14V11H4V9H10M17 9.5H19.5L21.47 12H17M6 15.5C6.39782 15.5 6.77936 15.658 7.06066 15.9393C7.34196 16.2206 7.5 16.6022 7.5 17C7.5 17.3978 7.34196 17.7794 7.06066 18.0607C6.77936 18.342 6.39782 18.5 6 18.5C5.60218 18.5 5.22064 18.342 4.93934 18.0607C4.65804 17.7794 4.5 17.3978 4.5 17C4.5 16.6022 4.65804 16.2206 4.93934 15.9393C5.22064 15.658 5.60218 15.5 6 15.5ZM18 15.5C18.3978 15.5 18.7794 15.658 19.0607 15.9393C19.342 16.2206 19.5 16.6022 19.5 17C19.5 17.3978 19.342 17.7794 19.0607 18.0607C18.7794 18.342 18.3978 18.5 18 18.5C17.6022 18.5 17.2206 18.342 16.9393 18.0607C16.658 17.7794 16.5 17.3978 16.5 17C16.5 16.6022 16.658 16.2206 16.9393 15.9393C17.2206 15.658 17.6022 15.5 18 15.5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <span> Delivery guarantee </span>
                                </h4>

                                <ul class="list-disc list-inside grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2 sm:mt-3">
                                    <li class="inline-flex items-center gap-2">
                                        <i class="fa-solid fa-check text-lg sm:text-xl text-leaf-green"></i>
                                        <span>$5.00 Credit for delay</span>
                                    </li>
                                    <li class="inline-flex items-center gap-2">
                                        <i class="fa-solid fa-check text-lg sm:text-xl text-leaf-green"></i>
                                        <span>15-day no update refund</span>
                                    </li>
                                    <li class="inline-flex items-center gap-2">
                                        <i class="fa-solid fa-check text-lg sm:text-xl text-leaf-green"></i>
                                        <span> Return if item damaged</span>
                                    </li>
                                    <li class="inline-flex items-center gap-2">
                                        <i class="fa-solid fa-check text-lg sm:text-xl text-leaf-green"></i>
                                        <span>30-day no delivery refund</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Cart Details Main Section Ended -->
    </main>
@endsection
