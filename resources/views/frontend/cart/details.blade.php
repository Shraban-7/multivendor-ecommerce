@extends('frontend.layouts.app')
@section('title', 'Card Details')

@section('content')
    <main class="pb-5 cart-details-page sm:pb-10">
        <!-- Promotional Header Starts -->
        <section>
            <a href="#" class="block py-3 text-white promo-header bg-light-yellow sm:py-4">
                <div class="container flex flex-wrap items-center justify-center xsm:justify-start gap-x-2">
                    <i class="text-lg fa-solid fa-truck-fast"></i>
                    <h3 class="text-sm">Free Shipping Special For You</h3>
                    <p class="ml-2 text-xs xsm:ml-3">Limited Offer</p>
                </div>
            </a>
        </section>
        <!-- Promotional Header Ended -->

        <!-- Cart Details Main Section Starts -->
        <section class="container page-breadcrumb-links">
            <!-- Page Breadcrumb -->
            <nav class="container flex my-2 md:my-5" aria-label="Breadcrumb">
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
                            <svg class="w-3 h-3 mx-1 rtl:rotate-180 text-davy-gray" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="text-sm ms-1 text-davy-gray md:ms-2">Cart</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid gap-5 xl:gap-10 2xl:gap-20 lg:grid-cols-3">
                <!-- Cart Items Main Section -->
                <div class="lg:col-span-2">
                    <!-- Free Shipping Banner -->
                    <div
                        class="text-sm justify-center lg:text-base text-rustic-red bg-[#E6F3E5] px-4 py-3 flex flex-wrap flex-col xsm:flex-row xsm:justify-between items-center my-2 md:my-5">
                        <div class="flex items-center gap-2 text-center">
                            <i class="fa-solid fa-check text-theme-teal"></i>
                            <span>Free shipping special for you</span>
                        </div>
                        <span class="italic font-light text-leaf-green">Exclusive offer</span>
                    </div>

                    <!-- Cart Items Container -->
                    <div id="cart-wrapper">
                        @foreach ($carts as $sellerId => $cartGroup)
                            @php
                                $seller = \App\Models\Seller::find($sellerId);
                                $sellerName = $seller ? $seller->business_name : '';
                            @endphp
                            <!-- Store/Seller Header with Select All for this seller -->
                            <div class="mt-6 mb-4 seller-section">
                                @if ($seller)
                                    <label for="selectSeller{{ $sellerId }}"
                                        class="flex items-center justify-between w-full px-3 py-2 text-black bg-gray-100 rounded-md cursor-pointer hover:text-black/80">
                                        <p class="flex items-center gap-2 md:text-base">
                                            <input type="radio" name="seller_id" id="selectSeller{{ $sellerId }}"
                                                class="hidden form-checkbox seller-checkbox peer/seller{{ $sellerId }}"
                                                data-seller-id="{{ $sellerId }}" value="{{ $sellerId }}" />
                                            <label for="selectSeller{{ $sellerId }}"
                                                class="inline-block stroke-black peer-checked/seller{{ $sellerId }}:stroke-white rounded-full text-white peer-checked/seller{{ $sellerId }}:text-black border-2 border-black cursor-pointer">
                                                <svg width="28" height="28" class="w-5 h-5 md:w-6 md:h-6"
                                                    viewBox="0 0 32 32" stroke-width="0" fill="currentColor"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="16" cy="16" r="16" fill="currentColor" />
                                                    <path
                                                        d="M9.58789 18.2939C9.58789 18.2939 10.9629 18.2939 12.7962 21.5023C12.7962 21.5023 17.892 13.0992 22.4212 11.4189"
                                                        stroke-width="1.79853" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </label>

                                            <span class="font-medium">{{ $sellerName }}</span>
                                            (<span class="seller-count"
                                                data-seller-id="{{ $sellerId }}">0</span>/<span>{{ count($cartGroup->flatMap->cartItems) }}</span>)
                                        </p>
                                        <i class="text-sm fa-solid fa-store"></i>
                                    </label>
                                @endif

                                <!-- Items for this seller -->
                                <div class="seller-items seller-{{ $sellerId }}">
                                    @foreach ($cartGroup as $key => $cart)
                                        @foreach ($cart->cartItems as $item)
                                            <div class="py-3 border-t md:py-5 border-jet-gray/20 cart-item"
                                                data-price="{{ $item->product_original_price }}"
                                                data-seller-id="{{ $sellerId }}"
                                                data-discounted-price="{{ $item->price }}" data-id="{{ $item->id }}"
                                                data-discount="{{ $item->product->discount }}">
                                                <div class="flex gap-2 sm:gap-4">
                                                    <!-- Item Checkbox -->
                                                    <div class="flex items-start hidden pt-2">
                                                        <input type="checkbox" id="item{{ $key }}"
                                                            class="hidden form-checkbox item-checkbox peer/item{{ $key }}"
                                                            data-item-id="{{ $key }}"
                                                            data-seller-id="{{ $sellerId }}" />
                                                        <label for="item{{ $key }}"
                                                            class="inline-block stroke-black peer-checked/item{{ $key }}:stroke-white rounded-full text-white peer-checked/item{{ $key }}:text-black border-2 border-black cursor-pointer">
                                                            <svg width="24" height="24" class="w-5 h-5"
                                                                viewBox="0 0 32 32" stroke-width="0" fill="currentColor"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <circle cx="16" cy="16" r="16"
                                                                    fill="currentColor" />
                                                                <path
                                                                    d="M9.58789 18.2939C9.58789 18.2939 10.9629 18.2939 12.7962 21.5023C12.7962 21.5023 17.892 13.0992 22.4212 11.4189"
                                                                    stroke-width="1.79853" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </label>
                                                    </div>

                                                    <!-- Item Image -->
                                                    <div
                                                        class="relative w-24 overflow-hidden rounded-md item-image-wrap h-28 xsm:w-36 xsm:h-40">
                                                        <a href="{{ route('products.details', $item->product->slug) }}">
                                                            <img src="{{ storage_url($item->product->thumbnail) }}"
                                                                alt="Product" class="object-cover w-full h-full" />
                                                        </a>
                                                        <span
                                                            class="w-10/12 xsm:w-7/12 text-center text-leaf-green text-[8px] inline-block absolute bottom-3 xsm:bottom-5 left-1/2 -translate-x-1/2 bg-theme-dark text-white rounded-3xl py-1">Almost
                                                            Sold Out</span>
                                                    </div>
                                                    <!-- Item Content -->
                                                    <div class="flex flex-col flex-1 gap-2 sm:gap-5">
                                                        <div class="space-y-1 sm:space-y-2">
                                                            <!-- title -->
                                                            <div class="flex items-start justify-between">
                                                                <h1
                                                                    class="w-11/12 text-sm md:text-base text-rustic-red xsm:w-10/12 md:w-3/4 lg:w-11/12 xl:w-3/4 line-clamp-3 sm:line-clamp-2">
                                                                    {{ $item->product->name }}
                                                                </h1>
                                                                <div class="delete-item">
                                                                    <button type="button" data-id="{{ $item->id }}"
                                                                        class="delete-btn hover:text-persian-red eq lg:text-xl xsm:text-lg">
                                                                        <i class="fa-regular fa-trash-can"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <!-- limited time -->
                                                            <p class="text-xs xsm:text-sm text-persian-red">
                                                                Big Sale / Limited Time
                                                            </p>
                                                        </div>

                                                        <!-- Prices & Quantity Controls -->
                                                        <div class="flex flex-wrap items-center justify-between gap-y-3">
                                                            <!-- price  -->
                                                            <div class="flex flex-wrap items-center gap-2">
                                                                <div
                                                                    class="flex flex-no-wrap items-center gap-1 new-price">
                                                                    <i
                                                                        class="fa-solid fa-bolt text-[#ffa755] lg:text-lg"></i>

                                                                    <div id="cart-item-{{ $item->id }}"
                                                                        class="cart-item">
                                                                        <h3
                                                                            class="text-sm font-bold current-price xsm:text-lg md:text-xl text-primary">
                                                                            {{ money($item->price * $item->quantity) }}
                                                                        </h3>
                                                                    </div>
                                                                </div>
                                                                <span
                                                                    class="text-xs xsm:text-sm px-2.5 py-0.5 rounded-lg border border-primary">
                                                                    - {{ percentage($item->product->discount_percent) }}
                                                                    last 2 days
                                                                </span>

                                                                @if ($item->variant)
                                                                    <div
                                                                        class="w-full text-xs xsm:text-sm text-gray-600 mt-1">
                                                                        @foreach ($item->variant->attributeOptions as $option)
                                                                            <span class="mr-2">
                                                                                {{ $option->productAttribute->name }}:
                                                                                {{ $option->value }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- quantity -->
                                                            <div class="quantity-controls" data-id="{{ $item->id }}">
                                                                <div
                                                                    class="flex items-center gap-2 text-davy-gray flex-nowrap">
                                                                    </h6>
                                                                    <div
                                                                        class="flex items-center p-1 border border-jet-gray/30 rounded">
                                                                        <input type="hidden" class="product-id"
                                                                            value="{{ $key }}">
                                                                        <input type="hidden" class="variant-sku"
                                                                            value="{{ $item->variant?->sku }}">
                                                                        <button type="button"
                                                                            class="flex items-center justify-center w-5 h-5 text-sm font-bold rounded decrease-qty text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 active:text-primary">
                                                                            <i class="fa-solid fa-minus"></i>
                                                                        </button>
                                                                        <input readonly type="number"
                                                                            value="{{ $item->quantity }}" min="1"
                                                                            class="w-12 h-5 text-sm font-medium text-center border-0 quantity-input text-persian-blue focus:ring-0" />
                                                                        <button type="button"
                                                                            class="flex items-center justify-center w-5 h-5 text-sm font-bold rounded increase-qty text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 active:text-primary">
                                                                            <i class="fa-solid fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Recommendations section -->
                    <div class="pt-10 border-t border-jet-gray/30">
                        <h2 class="mb-4 text-xl font-semibold md:text-2xl">
                            You May Like to ADD
                        </h2>

                        <div class="grid grid-cols-1 gap-3 xsm:grid-cols-2 md:grid-cols-3 sm:gap-6">
                            <!-- Product Card 1 -->
                            @include('frontend.partials.product-card-load', ['products' => $products])
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="mt-2 md:mt-5">
                        <h2 class="mb-4 font-semibold lg:text-xl md:text-lg">
                            Order Summary
                        </h2>
                        <div class="order-summary">
                            <!-- summary -->
                            <div class="space-y-2 item-info">
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Item's total:</span>
                                    <span id="itemsTotal"
                                        class="line-through text-jet-gray">{{ money($grand_total) }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Item Discount:</span>
                                    <span id="itemDiscount" class="font-bold text-primary">-{{ money($discount) }}</span>
                                </p>
                            </div>
                            <!-- estimated total -->
                            <div
                                class="flex justify-between pt-3 mt-6 font-medium border-t-2 border-dashed total border-jet-gray/50">
                                <span>Estimated Total (<span id="selectedItemsCount">{{ $total_products_count }}</span>
                                    Items)</span>
                                <span id="estimatedTotal" class="text-xl">{{ money($sub_total) }}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-col space-y-2 sm:mt-6 sm:space-y-3">
                            <a href="{{ route('orders.checkout') }}" id="checkoutLink">
                                <button id="checkoutBtn" type="button"
                                    class="flex flex-col items-center w-full py-2 sm:py-3 text-white bg-primary rounded-full transition-colors duration-200 hover:bg-theme-dark"
                                    data-seller-id="">
                                    Checkout (0)
                                    <span class="text-xs">Almost Sold Out</span>
                                </button>
                            </a>

                            <button type="button"
                                class="flex items-center justify-center w-full gap-1 sm:gap-2 py-2 sm:py-3 text-sm sm:text-base font-bold text-theme-dark border border-jet-gray/50 rounded-full transition-colors duration-200 hover:bg-jet-gray/10">
                                Express checkout with
                                <img src="{{ asset('assets/frontend/images/cart-paypal.png') }}" alt="PayPal"
                                    class="h-6 sm:h-9 w-auto" />
                            </button>
                        </div>

                        <!-- more info -->
                        <div class="p-4 text-xs text-davy-gray">
                            <div class="space-y-3 sm:space-y-4">
                                <p class="space-x-1">
                                    <i class="fa-solid fa-circle-exclamation text-jet-gray/50"></i>
                                    <span>
                                        Item availability and pricing are not guaranteed until
                                        payment is final.
                                    </span>
                                </p>
                                <h2 class="flex items-center gap-2 text-xs font-medium sm:text-sm">
                                    <i class="text-xl fa-solid fa-lock sm:text-2xl text-leaf-green"></i>
                                    <span>
                                        You will not be charged until you review this order on the
                                        next page
                                    </span>
                                </h2>
                                <h2 class="flex items-center gap-2 text-xs font-medium sm:text-sm">
                                    <svg width="22" height="26" class="w-6 h-6 text-leaf-green sm:w-8 sm:h-8"
                                        viewBox="0 0 22 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.82462 0.150834C10.2917 -0.0243682 10.8022 -0.0475726 11.2833 0.0845311L11.4869 0.150834L19.7747 3.25877C20.1948 3.41626 20.5615 3.68983 20.8321 4.04763C21.1027 4.40543 21.2661 4.83275 21.3033 5.27981L21.3115 5.47635V11.826C21.3113 13.7443 20.7932 15.627 19.8119 17.2754C18.8306 18.9237 17.4225 20.2766 15.7362 21.1912L15.4213 21.357L11.4502 23.3413C11.2317 23.4507 10.9929 23.5138 10.7488 23.5266C10.5047 23.5394 10.2607 23.5018 10.0318 23.4159L9.86133 23.3425L5.89027 21.3558C4.17423 20.4978 2.72169 19.1923 1.68598 17.5773C0.650278 15.9623 0.0698315 14.0976 0.00592001 12.18L0 11.8248V5.47754C6.88487e-06 5.02896 0.127427 4.58962 0.367427 4.21065C0.607427 3.83168 0.950134 3.52867 1.35565 3.33691L1.5368 3.25995L9.82462 0.150834ZM9.64111 7.20377L7.28381 11.1322C7.17483 11.3137 7.11597 11.5208 7.11325 11.7325C7.11052 11.9441 7.16402 12.1527 7.26828 12.3369C7.37255 12.5211 7.52384 12.6743 7.70671 12.781C7.88958 12.8876 8.09746 12.9437 8.30913 12.9437H10.9328L9.64111 15.0973C9.49026 15.3659 9.45006 15.6828 9.52903 15.9805C9.60799 16.2783 9.79991 16.5336 10.064 16.6921C10.3282 16.8507 10.6437 16.9001 10.9436 16.8298C11.2436 16.7595 11.5043 16.5751 11.6704 16.3156L14.0277 12.3872C14.1367 12.2057 14.1956 11.9986 14.1983 11.787C14.201 11.5753 14.1475 11.3667 14.0433 11.1825C13.939 10.9983 13.7877 10.8451 13.6048 10.7385C13.422 10.6319 13.2141 10.5757 13.0024 10.5757H10.3787L11.6716 8.42208C11.8332 8.15266 11.8811 7.8301 11.8048 7.52535C11.7286 7.2206 11.5343 6.95863 11.2649 6.79707C10.9955 6.63552 10.6729 6.5876 10.3682 6.66387C10.0634 6.74014 9.80266 6.93435 9.64111 7.20377Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span> Safe Payment Options </span>
                                </h2>
                            </div>
                            <div class="mt-2 space-y-2 sm:mt-3">
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
                                <div class="flex flex-wrap mt-2 gap-x-2 gap-y-1">
                                    @foreach (payment_gateways() as $gateway)
                                        <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}"
                                            class="w-auto h-8 sm:h-10" />
                                    @endforeach
                                </div>
                            </div>
                            <!-- security certification -->
                            <div class="mt-3 sm:mt-4">
                                <h4 class="text-sm">
                                    02. <span class="font-medium">Security Certification</span>
                                </h4>
                                <div class="flex flex-wrap mt-2 gap-x-2 gap-y-1">
                                    <img src="{{ asset('assets/frontend/images/security-1.png') }}" alt="PCI DDS"
                                        class="w-auto border border-jet-gray/30 rounded h-7 sm:h-9" />
                                    <img src="{{ asset('assets/frontend/images/security-2.png') }}" alt="Visa Secure"
                                        class="w-auto border border-jet-gray/30 rounded h-7 sm:h-9" />
                                    <img src="{{ asset('assets/frontend/images/security-3.png') }}"
                                        alt="Mastercard ID check" class="w-auto border border-jet-gray/30 rounded h-7 sm:h-9 " />
                                    <img src="{{ asset('assets/frontend/images/security-4.png') }}"
                                        alt="American Express SafeKey" class="w-auto border border-jet-gray/30 rounded h-7 sm:h-9" />
                                </div>
                            </div>
                            <!-- secure privacy -->
                            <div class="mt-5 space-y-2 sm:space-y-3">
                                <h4 class="flex items-center gap-2 text-sm font-medium">
                                    <i class="text-xl fa-solid fa-lock sm:text-2xl text-leaf-green"></i>
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
                            <div class="mt-3 space-y-2 sm:mt-5 sm:space-y-3">
                                <h4 class="flex items-center gap-1 text-sm font-medium">
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
                                <h4 class="flex items-center gap-2 text-sm font-medium">
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

                                <ul class="grid grid-cols-1 gap-2 mt-2 list-disc list-inside sm:grid-cols-2 sm:mt-3">
                                    <li class="inline-flex items-center gap-2">
                                        <i class="text-lg fa-solid fa-check sm:text-xl text-leaf-green"></i>
                                        <span>$5.00 Credit for delay</span>
                                    </li>
                                    <li class="inline-flex items-center gap-2">
                                        <i class="text-lg fa-solid fa-check sm:text-xl text-leaf-green"></i>
                                        <span>15-day no update refund</span>
                                    </li>
                                    <li class="inline-flex items-center gap-2">
                                        <i class="text-lg fa-solid fa-check sm:text-xl text-leaf-green"></i>
                                        <span> Return if item damaged</span>
                                    </li>
                                    <li class="inline-flex items-center gap-2">
                                        <i class="text-lg fa-solid fa-check sm:text-xl text-leaf-green"></i>
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.seller-checkbox').on('change', function() {
                    const sellerId = $(this).data('seller-id');
                    const isChecked = $(this).prop('checked');

                    $('.seller-checkbox').not(this).prop('checked', false);

                    $('.seller-items').hide();
                    $(`.seller-items.seller-${sellerId}`).show();

                    updateSellerSelection();
                });

                function updateSellerSelection() {
                    $('.item-checkbox').prop('checked', false);
                    updateCounts();
                    updateOrderSummary();
                }

                $('.seller-checkbox').on('change', function() {
                    const sellerId = $(this).data('seller-id');
                    const selectedCount = $('.item-checkbox:checked').length;

                    $('#checkoutBtn')
                        .attr('data-seller-id', sellerId)
                        .find('span:first-child').text(`Checkout (${selectedCount})`);

                    const checkoutRoute = "{{ route('orders.checkout') }}";
                    $('#checkoutLink').attr('href', `${checkoutRoute}?seller_id=${sellerId}`);
                });

                $('.item-checkbox').on('change', function() {
                    const selectedSellerId = $('.seller-checkbox:checked').data('seller-id');
                    const selectedCount = $('.item-checkbox:checked').length;

                    $('#checkoutBtn')
                        .attr('data-seller-id', selectedSellerId)
                        .find('span:first-child').text(`Checkout (${selectedCount})`);

                    const checkoutRoute = "{{ route('orders.checkout') }}";
                    $('#checkoutLink').attr('href', `${checkoutRoute}?seller_id=${selectedSellerId}`);
                });

                $('.increase-qty, .decrease-qty').click(function() {
                    var cartItem = $(this).closest('.quantity-controls');
                    var cartItemId = cartItem.data('id');

                    let quantityInput = cartItem.find('.quantity-input');
                    let currentQuantity = parseInt(quantityInput.val());

                    if ($(this).hasClass('increase-qty')) {
                        currentQuantity++;
                    } else if ($(this).hasClass('decrease-qty') && currentQuantity > 1) {
                        currentQuantity--;
                    }

                    updateCartQuantity(cartItemId, currentQuantity, quantityInput);
                });

                $('.delete-btn').click(function() {
                    var cartItemId = $(this).data('id');
                    deleteCartItem(cartItemId);
                });

                const selectAllCheckbox = $('#selectAll');
                const sellerCheckboxes = $('.seller-checkbox');
                const itemCheckboxes = $('.item-checkbox');

                selectAllCheckbox.on('change', function() {
                    const isChecked = $(this).prop('checked');
                    sellerCheckboxes.prop('checked', isChecked);
                    itemCheckboxes.prop('checked', isChecked);
                    updateCounts();
                    updateOrderSummary();
                });

                sellerCheckboxes.on('change', function() {
                    const sellerId = $(this).data('seller-id');
                    const isChecked = $(this).prop('checked');
                    $(`.item-checkbox[data-seller-id="${sellerId}"]`).prop('checked',
                        isChecked);
                    updateSellerCheckboxes();
                    updateCounts();
                    updateOrderSummary();
                });

                itemCheckboxes.on('change', function() {
                    const sellerId = $(this).data('seller-id');
                    updateSellerCheckbox(sellerId);
                    updateSelectAllCheckbox();
                    updateCounts();
                    updateOrderSummary();
                });

                function updateCartQuantity(cartItemId, quantity, input) {
                    $.ajax({
                        url: "{{ route('cart.update') }}",
                        type: "POST",
                        data: {
                            id: cartItemId,
                            quantity: quantity,
                        },
                        success: function(response) {
                            input.val(quantity);

                            if (response.success) {
                                updateOrderTotals(response);
                                toastr.success(response.message);
                                updateCartData();
                                updateOrderSummary();
                                var priceElement = $('#cart-item-' + cartItemId +
                                    ' .current-price');
                                if (priceElement) {
                                    priceElement.text(response.updatedPrice);
                                }
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('An error occurred while updating the cart.');
                        }
                    });
                }

                function deleteCartItem(cartItemId) {
                    $.ajax({
                        url: "{{ route('cart.delete') }}",
                        type: "POST",
                        data: {
                            id: cartItemId,
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                location.reload();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('An error occurred while deleting the product.');
                        }
                    });
                }

                function updateOrderTotals(response) {
                    $('#itemsTotal').text(response.order_total);
                    $('#estimatedTotal').text(response.order_subtotal);
                    $('#itemDiscount').text('-' + response.discount);
                    $('#selectedItemsCount').text(response.total_products_count);

                    const checkoutBtn = $('#checkoutBtn');
                    checkoutBtn.html(
                        `Checkout (${response.total_products_count}) <span class="text-xs">Almost Sold Out</span>`);

                    if (parseInt(response.total_products_count) === 0) {
                        checkoutBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                    } else {
                        checkoutBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    }
                }

                function updateSellerCheckbox(sellerId) {
                    const sellerItems = $(`.item-checkbox[data-seller-id="${sellerId}"]`);
                    const allSellerItemsChecked = sellerItems.length === sellerItems.filter(':checked').length;
                    $(`.seller-checkbox[data-seller-id="${sellerId}"]`).prop('checked',
                        allSellerItemsChecked);
                }

                function updateSellerCheckboxes() {
                    const allSellersSelected = sellerCheckboxes.length === $('.seller-checkbox:checked').length;
                    selectAllCheckbox.prop('checked', allSellersSelected);
                }

                function updateSelectAllCheckbox() {
                    const allItemsChecked = itemCheckboxes.length === itemCheckboxes.filter(':checked').length;
                    selectAllCheckbox.prop('checked', allItemsChecked);
                }

                function updateCounts() {
                    const selectedItems = $('.item-checkbox:checked');
                    $('#selectedCount').text(selectedItems.length);

                    $('.seller-count').each(function() {
                        const sellerId = $(this).data('seller-id');
                        const sellerItems = $(
                            `.item-checkbox[data-seller-id="${sellerId}"]:checked`);
                        $(this).text(sellerItems.length);
                    });
                }

                function updateOrderSummary() {
                    let discountedTotal = 0;
                    let originalTotal = 0;
                    let selectedCount = 0;

                    $('.item-checkbox:checked').each(function() {
                        const cartItem = $(this).closest('.cart-item');
                        if (cartItem.length) {
                            const price = parseFloat(cartItem.data('price'));
                            const discountedPrice = parseFloat(cartItem.data('discounted-price'));
                            const discount = parseFloat(cartItem.data('discount'));
                            const quantity = parseInt(cartItem.find('.quantity-input').val(), 10);

                            discountedTotal += discountedPrice * quantity;
                            originalTotal += price * quantity;
                            selectedCount += quantity;
                        }
                    });

                    const discount = originalTotal - discountedTotal;

                    $('#itemsTotal').text(formatCurrency(originalTotal));
                    $('#itemDiscount').text('-' + formatCurrency(discount));
                    $('#estimatedTotal').text(formatCurrency(discountedTotal));
                    $('#selectedItemsCount').text(selectedCount);

                    const checkoutBtn = $('#checkoutBtn');
                    checkoutBtn.html(`Checkout (${selectedCount}) <span class="text-xs">Almost Sold Out</span>`);

                    if (selectedCount === 0) {
                        checkoutBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                    } else {
                        checkoutBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    }
                }

                function updateCartData() {
                    $.ajax({
                        url: "{{ route('cart.data') }}",
                        type: "GET",
                        success: function(data) {
                            $('#cartCount').text(data.cartCount);
                            $('#totalPrice').text(data.totalPrice);
                        },
                        error: function() {
                            toastr.error('Failed to update cart data.');
                        }
                    });
                }

                function formatCurrency(amount) {
                    return '৳ ' + amount.toFixed(2);
                }

                updateOrderSummary();
                updateCounts();
            });
        </script>
    @endpush
@endsection
