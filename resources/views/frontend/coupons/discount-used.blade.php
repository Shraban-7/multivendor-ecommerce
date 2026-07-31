@extends('seller.layouts.app')
@section('title', 'Coupon Discount Used')

@section('content')
    <main class="pt-4 pb-8 coupon-discount-page md:pt-6 md:pb-10">
        <!-- Page Promotion Banner Starts -->
        <section class="container py-5 page-promotion md:w-full">
            <div
                class="promo-wrapper md:container bg-[#5C62D6] grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden">
                <div
                    class="flex flex-col items-start justify-center order-2 gap-3 p-5 md:order-1 promo-content sm:gap-5 md:p-10 lg:p-14 2xl:p-20">
                    <h2
                        class="text-xl font-bold text-white lg:text-3xl md:text-2xl md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2">
                        Existing customers can refer friends or family and receive a
                        discount.
                    </h2>
                    <p class="text-xs text-white md:pr-7 lg:pr-14 2xl:pr-20">
                        Customers earn points for purchases, which can be redeemed for
                        future discounts.
                    </p>
                    <a href="#"
                        class="theme-btn bg-[#5A422A] px-5 py-2 lg:px-7 lg:py-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm">Learn
                        More</a>
                </div>
                <div class="order-1 promo-image">
                    <div class="w-full img-wrap">
                        <div class="w-full h-40 overflow-hidden rounded-lg lg:h-96 md:h-80 md:rounded-3xl">
                            <a href="#" class="block w-full h-full">
                                <img src="./assests/images/promo-banner-image-4.png" alt="Limited Offer Coupons"
                                    class="object-cover w-full h-full" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Page Promotion Banner Ended -->

        <!-- Page Main Content Starts -->
        <section class="container pt-4 coupon-discount-section md:pt-5 lg:pt-6">
            <div class="coupon-discount-head">
                <h2 class="text-xl font-medium sm:text-2xl">Available Coupon</h2>

                <div class="pt-3 pb-5 border-b coupon-discount-menus md:pt-5 md:pb-8">
                    <ul class="flex flex-wrap gap-3">
                        <li>
                            <a href="./couponDiscount.html"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Unused</a>
                        </li>
                        <li aria-current="page">
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Used</a>
                        </li>
                        <li>
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Expired</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- coupon cards -->
            <div class="py-5 coupon-discount-body md:py-8">
                <!-- Grid container for the cards -->
                <div class="grid grid-cols-1 gap-4 lg:max-w-4xl xl:max-w-6xl sm:grid-cols-2 md:gap-6 lg:gap-8">
                    <!-- Card 1 -->
                    <div class="relative rounded-lg shadow cashback-card bg-jet-gray/5">
                        <div class="px-6 pt-2 pb-3 space-y-1 sm:px-8 sm:pt-6 sm:pb-4 sm:space-y-2">
                            <h3 class="font-medium sm:text-lg text-primary/80">
                                Cash Back 20
                            </h3>
                            <p class="text-xs text-davy-gray sm:text-sm">
                                Add Items Worth $250 More To Unlock
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="cashback-icon text-primary">
                                    <i class="fa-solid fa-receipt"></i>
                                </span>
                                <span class="text-sm text-theme-teal">Up To $20.00 Cash Back</span>
                            </div>
                            <!-- Hidden input with the actual code to copy -->
                            <input type="hidden" class="coupon-code" value="CASHBACK20" />
                        </div>
                        <div class="relative">
                            <button
                                class="copy-button bg-primary/20 hover:bg-primary hover:text-white text-primary w-full py-1.5 sm:py-2 text-center rounded-md font-medium text-sm sm:text-base eq">
                                Copy Code
                                <div
                                    class="absolute invisible px-3 py-2 text-xs font-medium text-white transform -translate-x-1/2 rounded-md opacity-0 tooltip -top-10 left-1/2 bg-theme-dark">
                                    <span>Copy to clipboard</span>

                                    <i
                                        class="fa-solid fa-caret-down absolute left-1/2 -translate-x-1/2 -bottom-2.5 text-theme-dark text-xl"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="relative rounded-lg shadow cashback-card bg-jet-gray/5">
                        <div class="px-6 pt-2 pb-3 space-y-1 sm:px-8 sm:pt-6 sm:pb-4 sm:space-y-2">
                            <h3 class="font-medium sm:text-lg text-primary/80">{{ app_name() }} 40</h3>
                            <p class="text-xs text-davy-gray sm:text-sm">
                                Add Items Worth $100 More To Unlock
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="cashback-icon text-primary">
                                    <i class="fa-solid fa-receipt"></i>
                                </span>
                                <span class="text-sm text-theme-teal">Up To 40% Discount on 2 Products</span>
                            </div>
                            <!-- Hidden input with the actual code to copy -->
                            <input type="hidden" class="coupon-code" value="{{ str_replace(' ', '', app_name()) }}40" />
                        </div>
                        <div class="relative">
                            <button
                                class="copy-button bg-primary/20 hover:bg-primary hover:text-white text-primary w-full py-1.5 sm:py-2 text-center rounded-md font-medium text-sm sm:text-base eq">
                                Copy Code
                                <div
                                    class="absolute invisible px-3 py-2 text-xs font-medium text-white transform -translate-x-1/2 rounded-md opacity-0 tooltip -top-10 left-1/2 bg-theme-dark">
                                    <span>Copy to clipboard</span>

                                    <i
                                        class="fa-solid fa-caret-down absolute left-1/2 -translate-x-1/2 -bottom-2.5 text-theme-dark text-xl"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="relative rounded-lg shadow cashback-card bg-jet-gray/5">
                        <div class="px-6 pt-2 pb-3 space-y-1 sm:px-8 sm:pt-6 sm:pb-4 sm:space-y-2">
                            <h3 class="font-medium sm:text-lg text-primary/80">
                                Spinner 50
                            </h3>
                            <p class="text-xs text-davy-gray sm:text-sm">
                                Add Items Worth $999 More To Unlock
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="cashback-icon text-primary">
                                    <i class="fa-solid fa-receipt"></i>
                                </span>
                                <span class="text-sm text-theme-teal">Up To $100.00 Cash Back</span>
                            </div>
                            <!-- Hidden input with the actual code to copy -->
                            <input type="hidden" class="coupon-code" value="SPINNER50" />
                        </div>
                        <div class="relative">
                            <button
                                class="copy-button bg-primary/20 hover:bg-primary hover:text-white text-primary w-full py-1.5 sm:py-2 text-center rounded-md font-medium text-sm sm:text-base eq">
                                Copy Code
                                <div
                                    class="absolute invisible px-3 py-2 text-xs font-medium text-white transform -translate-x-1/2 rounded-md opacity-0 tooltip -top-10 left-1/2 bg-theme-dark">
                                    <span>Copy to clipboard</span>

                                    <i
                                        class="fa-solid fa-caret-down absolute left-1/2 -translate-x-1/2 -bottom-2.5 text-theme-dark text-xl"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="relative rounded-lg shadow cashback-card bg-jet-gray/5">
                        <div class="px-6 pt-2 pb-3 space-y-1 sm:px-8 sm:pt-6 sm:pb-4 sm:space-y-2">
                            <h3 class="font-medium sm:text-lg text-primary/80">Slash 10</h3>
                            <p class="text-xs text-davy-gray sm:text-sm">
                                Add Items Worth $99 More To Unlock
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="cashback-icon text-primary">
                                    <i class="fa-solid fa-receipt"></i>
                                </span>
                                <span class="text-sm text-theme-teal">Up To $10.00 Cash Back</span>
                            </div>
                            <!-- Hidden input with the actual code to copy -->
                            <input type="hidden" class="coupon-code" value="SLASH10" />
                        </div>
                        <div class="relative">
                            <button
                                class="copy-button bg-primary/20 hover:bg-primary hover:text-white text-primary w-full py-1.5 sm:py-2 text-center rounded-md font-medium text-sm sm:text-base eq">
                                Copy Code
                                <div
                                    class="absolute invisible px-3 py-2 text-xs font-medium text-white transform -translate-x-1/2 rounded-md opacity-0 tooltip -top-10 left-1/2 bg-theme-dark">
                                    <span>Copy to clipboard</span>
                                    <i
                                        class="fa-solid fa-caret-down absolute left-1/2 -translate-x-1/2 -bottom-2.5 text-theme-dark text-xl"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Explore Your Interest -->
        <section class="container section-padding">
            <!-- section titile -->
            <div class="flex items-center justify-between mb-5 section-title md:mb-8 lg:mb-10">
                <h2 class="text-xl font-medium capitalize sm:text-2xl lg:text-3xl text-davy-gray text-jet-gray">
                    Explore Your Interest
                </h2>

                <a href="#"
                    class="flex items-center gap-1 text-sm sm:text-base text-sand-brown group/link hover:text-primary eq">See
                    All
                    <i class="text-xs fa-solid fa-chevron-right sm:text-sm lg:group-hover/link:translate-x-1 eq"></i></a>
            </div>

            <!-- Product Card's Wrapper -->
            <div class="grid grid-cols-1 gap-3 xsm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6">
                <!-- Product Card 1 -->
                <div
                    class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq">
                    <div class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60">
                        <a href="#" class="block w-full h-full">
                            <img src="./assests/images/electronic-prod-1.png"
                                alt="ASUS Vivo15 OLED K513 Core-i5 11th Gen 15.6″ FHD Laptop"
                                class="object-cover w-full h-full" />
                        </a>
                        <button
                            class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq">
                            <i class="fa-regular fa-eye"></i>
                            Quick View
                        </button>
                    </div>

                    <div class="p-4 xsm:p-2 lg:p-5">
                        <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                            <a href="#"
                                class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">ASUS Vivo15 OLED
                                K513 Core-i5 11th Gen 15.6″ FHD Laptop</a>
                        </h3>
                        <p class="text-leaf-green">Almost sold Out</p>

                        <div class="flex flex-wrap items-center gap-x-1">
                            <div class="flex flex-no-wrap items-center gap-x-1 text-light-yellow">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <img src="./assests/images/fire-icon.png" class="w-8 h-auto" alt="Fire Icon" />
                            </div>

                            <span class="text-jet-gray">4.5K+ Sold</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                            <span class="text-primary/80">Final Hours</span>
                            <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                <div class="flex flex-no-wrap items-center gap-1 price">
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
                    <div class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60">
                        <a href="#" class="block w-full h-full">
                            <img src="./assests/images/electronic-prod-2.png" alt="Apple watch series 10 depth rainmaker"
                                class="object-cover w-full h-full" />
                        </a>
                        <button
                            class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq">
                            <i class="fa-regular fa-eye"></i>
                            Quick View
                        </button>
                    </div>

                    <div class="p-4 xsm:p-2 lg:p-5">
                        <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                            <a href="#"
                                class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">Apple watch
                                series 10 depth rainmaker</a>
                        </h3>
                        <p class="text-leaf-green">Almost sold Out</p>

                        <div class="flex flex-wrap items-center gap-x-1">
                            <div class="flex flex-no-wrap items-center gap-x-1 text-light-yellow">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <img src="./assests/images/fire-icon.png" class="w-8 h-auto" alt="Fire Icon" />
                            </div>

                            <span class="text-jet-gray">2.8K+ Sold</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                            <span class="text-primary/80">Final Hours</span>
                            <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                <div class="flex flex-no-wrap items-center gap-1 price">
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
                    <div class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60">
                        <a href="#" class="block w-full h-full">
                            <img src="./assests/images/electronic-prod-3.png"
                                alt="Quadcopter With Height Hold, App Control, And Obstacle For flying"
                                class="object-cover w-full h-full" />
                        </a>
                        <button
                            class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq">
                            <i class="fa-regular fa-eye"></i>
                            Quick View
                        </button>
                    </div>

                    <div class="p-4 xsm:p-2 lg:p-5">
                        <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                            <a href="#"
                                class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">Quadcopter With
                                Height Hold, App Control, And Obstacle For
                                flying</a>
                        </h3>
                        <p class="text-leaf-green">Almost sold Out</p>

                        <div class="flex flex-wrap items-center gap-x-1">
                            <div class="flex flex-no-wrap items-center gap-x-1 text-light-yellow">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <img src="./assests/images/fire-icon.png" class="w-8 h-auto" alt="Fire Icon" />
                            </div>

                            <span class="text-jet-gray">1.2K+ Sold</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                            <span class="text-primary/80">Final Hours</span>
                            <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                <div class="flex flex-no-wrap items-center gap-1 price">
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
                    <div class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60">
                        <a href="#" class="block w-full h-full">
                            <img src="./assests/images/electronic-prod-4.png"
                                alt="Sports Wireless Headphones, ANC and ENC Headphone"
                                class="object-cover w-full h-full" />
                        </a>
                        <button
                            class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq">
                            <i class="fa-regular fa-eye"></i>
                            Quick View
                        </button>
                    </div>

                    <div class="p-4 xsm:p-2 lg:p-5">
                        <h3 class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12">
                            <a href="#"
                                class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq">Sports Wireless
                                Headphones, ANC and ENC Headphone</a>
                        </h3>
                        <p class="text-leaf-green">Almost sold Out</p>

                        <div class="flex flex-wrap items-center gap-x-1">
                            <div class="flex flex-no-wrap items-center gap-x-1 text-light-yellow">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <img src="./assests/images/fire-icon.png" class="w-8 h-auto" alt="Fire Icon" />
                            </div>

                            <span class="text-jet-gray">6.2K+ Sold</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                            <span class="text-primary/80">Final Hours</span>
                            <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                <div class="flex flex-no-wrap items-center gap-1 price">
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
            </div>
        </section>
        <!-- Page Main Content Ended -->
    </main>


    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // copy coupon code functionality
                const copyButtons = document.querySelectorAll(".copy-button");
                copyButtons.forEach((button) => {
                    const card = button.closest(".cashback-card");
                    const codeInput = card.querySelector(".coupon-code");
                    const tooltip = button.querySelector(".tooltip");
                    const tooltipText = tooltip.querySelector("span");

                    let tooltipTimeout;

                    function showTooltip() {
                        tooltip.style.visibility = "visible";
                        tooltip.style.opacity = "1";
                    }

                    function hideTooltip() {
                        tooltip.style.visibility = "hidden";
                        tooltip.style.opacity = "0";
                    }

                    button.addEventListener("mouseover", () => {
                        clearTimeout(tooltipTimeout);
                        showTooltip();
                    });

                    button.addEventListener("mouseout", () => {
                        tooltipTimeout = setTimeout(hideTooltip, 100);
                    });

                    button.addEventListener("click", async () => {
                        const code = codeInput.value;
                        await navigator.clipboard
                            .writeText(code)
                            .then(() => {
                                tooltipText.textContent = "Copied";
                                showTooltip();

                                clearTimeout(tooltipTimeout);
                                tooltipTimeout = setTimeout(() => {
                                    hideTooltip();
                                    setTimeout(() => {
                                        tooltipText.textContent =
                                            "Copy to clipboard";
                                    }, 100);
                                }, 2000);
                            })
                            .catch((err) => {
                                console.error("Failed to copy: ", err);
                                alert("Failed to copy code. Please try again.");
                            });
                    });
                });
            });
        </script>
    @endpush
@endsection
