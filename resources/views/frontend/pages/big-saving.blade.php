@extends('seller.layouts.app')
@section('title', 'Big Saving')

@section('content')
    <main class="pt-4 pb-8 big-saving-page md:pt-6 md:pb-10">
        <!-- Page Promotion Banner Starts -->
        <section class="container py-5 page-promotion md:w-full">
            <div
                class="promo-wrapper md:container bg-[#FFC81B] grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden">
                <div
                    class="flex flex-col items-start justify-center order-2 gap-3 p-5 md:order-1 promo-content sm:gap-5 md:p-10 lg:p-14 2xl:p-20">
                    <h2
                        class="text-xl font-bold text-white lg:text-3xl md:text-2xl md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2">
                        Big Savings, Bigger Smiles! Shop Now & Save More!" 🎉🛍️
                    </h2>
                    <p class="text-xs text-white md:pr-7 lg:pr-14 2xl:pr-20">
                        Whether it's fashion, electronics, home essentials, or more, grab
                        massive discounts and special deals before they're gone!
                    </p>
                    <a href="#"
                        class="theme-btn bg-[#5A422A] px-5 py-2 lg:px-7 lg:py-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm">Learn
                        More</a>
                </div>
                <div class="order-1 promo-image">
                    <div class="w-full img-wrap">
                        <div class="w-full h-40 overflow-hidden rounded-lg lg:h-96 md:h-80 md:rounded-3xl">
                            <a href="#" class="block w-full h-full">
                                <img src="./assests/images/promo-banner-image-3.png" alt="A man viewing a large size Laptop"
                                    class="object-cover w-full h-full" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Page Promotion Banner Ended -->

        <!-- Page Main Content Starts -->
        <section class="container pt-4 big-saving-section md:pt-5 lg:pt-6">
            <!-- Limited Deals -->
            <div class="p-5 rounded-lg limited-deals-promo bg-primary md:rounded-3xl md:p-8 lg:p-10">
                <div class="flex items-center justify-between promo-title">
                    <h2 class="text-sm font-medium capitalize md:text-xl sm:text-2xl text-theme-light">
                        Limited deals only for today
                    </h2>

                    <a href="#"
                        class="flex items-center gap-1 text-xs xsm:text-sm text-nowrap sm:text-base text-theme-light group/link hover:text-theme-dark eq">Ends
                        In
                        <i
                            class="text-xs fa-solid fa-chevron-right sm:text-sm lg:group-hover/link:translate-x-1 eq"></i></a>
                </div>

                <!-- limited deals items item -->
                <div class="flex flex-row flex-wrap gap-2 pt-4 promo-items-wrap md:gap-4 md:pt-8 lg:pt-10">
                    <a href="#"
                        class="promo-item w-[48%] xsm:w-auto bg-theme-light inline-flex items-center flex-col p-3 sm:p-4 md:p-5 rounded-lg md:rounded-xl gap-1 sm:gap-2 hover:shadow-lg hover:bg-theme-light/95 hover:border-theme-dark/80 border border-transparent eq">
                        <div class="overflow-hidden rounded-full img-wrap size-14 xsm:size-16 md:size-20 lg:size-28">
                            <img src="./assests/images/limited-deal-1.png" alt="Black Color Fridge" />
                        </div>

                        <h2 class="flex items-center gap-1 text-xl font-bold sm:gap-2 lg:text-2xl text-primary">
                            <span class="text-lg font-normal sm:text-xl text-light-yellow">$</span>
                            51.00
                            <span class="text-xl font-normal sm:text-2xl text-light-yellow">
                                <i class="fa-solid fa-down-long text-persian-red"></i></span>
                        </h2>

                        <p class="flex flex-wrap justify-center gap-y-1 gap-x-2">
                            <span class="text-light-yellow">79% Off</span>
                            <span class="text-primary">3 days left</span>
                        </p>
                    </a>
                    <a href="#"
                        class="promo-item w-[48%] xsm:w-auto bg-theme-light inline-flex items-center flex-col p-3 sm:p-4 md:p-5 rounded-lg md:rounded-xl gap-1 sm:gap-2 hover:shadow-lg hover:bg-theme-light/95 hover:border-theme-dark/80 border border-transparent eq">
                        <div class="overflow-hidden rounded-full img-wrap size-14 xsm:size-16 md:size-20 lg:size-28">
                            <img src="./assests/images/limited-deal-2.png" alt="Coffee Maker" />
                        </div>

                        <h2 class="flex items-center gap-1 text-xl font-bold sm:gap-2 lg:text-2xl text-primary">
                            <span class="text-lg font-normal sm:text-xl text-light-yellow">$</span>
                            75.00
                            <span class="text-xl font-normal sm:text-2xl text-light-yellow"><i
                                    class="fa-solid fa-down-long text-persian-red"></i></span>
                        </h2>

                        <p class="flex flex-wrap justify-center gap-y-1 gap-x-2">
                            <span class="text-light-yellow">80% Off</span>
                            <span class="text-primary">2 days left</span>
                        </p>
                    </a>
                    <a href="#"
                        class="promo-item w-[48%] xsm:w-auto bg-theme-light inline-flex items-center flex-col p-3 sm:p-4 md:p-5 rounded-lg md:rounded-xl gap-1 sm:gap-2 hover:shadow-lg hover:bg-theme-light/95 hover:border-theme-dark/80 border border-transparent eq">
                        <div class="overflow-hidden rounded-full img-wrap size-14 xsm:size-16 md:size-20 lg:size-28">
                            <img src="./assests/images/limited-deal-3.png" alt="Converse Shoe" />
                        </div>

                        <h2 class="flex items-center gap-1 text-xl font-bold sm:gap-2 lg:text-2xl text-primary">
                            <span class="text-lg font-normal sm:text-xl text-light-yellow">$</span>
                            40.00
                            <span class="text-xl font-normal sm:text-2xl text-light-yellow"><i
                                    class="fa-solid fa-down-long text-persian-red"></i></span>
                        </h2>

                        <p class="flex flex-wrap justify-center gap-y-1 gap-x-2">
                            <span class="text-light-yellow">50% Off</span>
                            <span class="text-primary">1 days left</span>
                        </p>
                    </a>
                    <a href="#"
                        class="promo-item w-[48%] xsm:w-auto bg-theme-light inline-flex items-center flex-col p-3 sm:p-4 md:p-5 rounded-lg md:rounded-xl gap-1 sm:gap-2 hover:shadow-lg hover:bg-theme-light/95 hover:border-theme-dark/80 border border-transparent eq">
                        <div class="overflow-hidden rounded-full img-wrap size-14 xsm:size-16 md:size-20 lg:size-28">
                            <img src="./assests/images/limited-deal-1.png" alt="Black Color Fridge" />
                        </div>

                        <h2 class="flex items-center gap-1 text-xl font-bold sm:gap-2 lg:text-2xl text-primary">
                            <span class="text-lg font-normal sm:text-xl text-light-yellow">$</span>
                            51.00
                            <span class="text-xl font-normal sm:text-2xl text-light-yellow">
                                <i class="fa-solid fa-down-long text-persian-red"></i></span>
                        </h2>

                        <p class="flex flex-wrap justify-center gap-x-2">
                            <span class="text-light-yellow">79% Off</span>
                            <span class="text-primary">3 days left</span>
                        </p>
                    </a>
                    <a href="#"
                        class="promo-item w-[48%] xsm:w-auto bg-theme-light inline-flex items-center flex-col p-3 sm:p-4 md:p-5 rounded-lg md:rounded-xl gap-1 sm:gap-2 hover:shadow-lg hover:bg-theme-light/95 hover:border-theme-dark/80 border border-transparent eq">
                        <div class="overflow-hidden rounded-full img-wrap size-14 xsm:size-16 md:size-20 lg:size-28">
                            <img src="./assests/images/limited-deal-2.png" alt="Coffey maker" />
                        </div>

                        <h2 class="flex items-center gap-1 text-xl font-bold sm:gap-2 lg:text-2xl text-primary">
                            <span class="text-lg font-normal sm:text-xl text-light-yellow">$</span>
                            75.00
                            <span class="text-xl font-normal sm:text-2xl text-light-yellow"><i
                                    class="fa-solid fa-down-long text-persian-red"></i></span>
                        </h2>

                        <p class="flex flex-wrap justify-center gap-y-1 gap-x-2">
                            <span class="text-light-yellow">80% Off</span>
                            <span class="text-primary">2 days left</span>
                        </p>
                    </a>
                </div>
            </div>

            <!-- Big Save -->
            <div class="pt-4 promos-container md:pt-5 lg:pt-6">
                <div class="promo-head">
                    <div class="pt-3 pb-5 border-b promo-categories-menus md:pt-5 md:pb-8">
                        <ul class="flex flex-wrap gap-3">
                            <li aria-current="page">
                                <a href="#"
                                    class="inline-block sm:px-4 px-2.5 sm:py-2 py-1 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl border border-jet-gray/40">Feature
                                    Items</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="inline-block sm:px-4 px-2.5 sm:py-2 py-1 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl border border-jet-gray/40">Electronics</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="inline-block sm:px-4 px-2.5 sm:py-2 py-1 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl border border-jet-gray/40">Accessories</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Items Included -->
                <div class="grid grid-cols-1 md:grid-cols-2 text-davy-gray space-y-1.5">
                    <!-- Item 1 -->
                    <div class="flex gap-2 py-4 md:gap-4 md:py-5">
                        <a href="#"
                            class="flex-shrink-0 block w-24 h-32 overflow-hidden xsm:w-36 xsm:h-44 md:w-28 md:h-36 lg:w-40 lg:h-48 xl:w-48 xl:h-56 rounded-xl">
                            <img src="./assests/images/big-save-feature-1.png" alt="NYC Washable Wool-Blend Jumper" />
                        </a>

                        <div class="flex-grow space-y-2 md:space-y-3">
                            <span
                                class="text-xs xsm:text-sm lg:text-base xl:text-lg bg-persian-red/70 text-theme-light px-4 py-1.5 xsm:px-6 xsm:py-2 rounded-3xl">Big
                                Save</span>
                            <p class="text-sm font-medium xsm:text-base lg:text-lg xl:text-xl hover:text-primary eq">
                                <a href="#" class="line-clamp-2">NYC Washable Wool-Blend Jumper</a>
                            </p>
                            <p class="text-sm lg:text-base xl:text-lg text-jet-gray">
                                250 Sold
                                <i class="fa-solid fa-star text-theme-dark"></i> 4.8
                            </p>
                            <a href="#" class="relative flex flex-col lg:flex-row">
                                <button
                                    class="inline-flex items-center justify-center lg:justify-start lg:text-left w-3/4 xsm:w-2/3 lg:w-1/2 px-3 py-2 xsm:px-5 xsm:py-3 md:py-2 rounded-[3.5rem] gap-1 sm:gap-2 xl:px-10 md:text-lg lg:text-xl font-bold text-theme-light bg-theme-dark">
                                    <span class="font-normal lg:text-lg xl:text-xl text-white/70">$</span>
                                    51.00
                                </button>
                                <button
                                    class="inline-flex items-center justify-center w-3/4 gap-1 px-5 py-2 -mt-2 text-sm font-bold rounded-full lg:mt-0 lg:-ml-14 2xl:-ml-20 xsm:w-2/3 lg:w-1/2 lg:gap-2 sm:gap-3 xsm:text-base lg:text-base xl:text-xl text-theme-light bg-primary">
                                    <i
                                        class="font-normal fa-solid fa-down-long text-persian-red md:text-xl lg:text-2xl xl:text-3xl text-light-yellow"></i>

                                    <p class="flex flex-col text-nowrap">
                                        <span>79% Off</span>
                                        <span class="-mt-1 font-medium md:text-sm lg:text-lg lg:-mt-2 xl:-mt-1">3 days
                                            left</span>
                                    </p>
                                </button>
                            </a>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex gap-2 py-4 md:gap-4 md:py-5">
                        <a href="#"
                            class="flex-shrink-0 block w-24 h-32 overflow-hidden xsm:w-36 xsm:h-44 md:w-28 md:h-36 lg:w-40 lg:h-48 xl:w-48 xl:h-56 rounded-xl">
                            <img src="./assests/images/big-save-feature-2.png" alt="Pocket Reporter Bag" />
                        </a>

                        <div class="flex-grow space-y-2 md:space-y-3">
                            <span
                                class="text-xs xsm:text-sm lg:text-base xl:text-lg bg-persian-red/70 text-theme-light px-4 py-1.5 xsm:px-6 xsm:py-2 rounded-3xl">Big
                                Save</span>
                            <p class="text-sm font-medium xsm:text-base lg:text-lg xl:text-xl hover:text-primary eq">
                                <a href="#" class="line-clamp-2">Pocket Reporter Bag</a>
                            </p>
                            <p class="text-sm lg:text-base xl:text-lg text-jet-gray">
                                500 Sold
                                <i class="fa-solid fa-star text-theme-dark"></i> 4.8
                            </p>
                            <a href="#" class="relative flex flex-col lg:flex-row">
                                <button
                                    class="inline-flex items-center justify-center lg:justify-start lg:text-left w-3/4 xsm:w-2/3 lg:w-1/2 px-3 py-2 xsm:px-5 xsm:py-3 md:py-2 rounded-[3.5rem] gap-1 sm:gap-2 xl:px-10 md:text-lg lg:text-xl font-bold text-theme-light bg-theme-dark">
                                    <span class="font-normal lg:text-lg xl:text-xl text-white/70">$</span>
                                    82.00
                                </button>
                                <button
                                    class="inline-flex items-center justify-center w-3/4 gap-1 px-5 py-2 -mt-2 text-sm font-bold rounded-full lg:mt-0 lg:-ml-14 2xl:-ml-20 xsm:w-2/3 lg:w-1/2 lg:gap-2 sm:gap-3 xsm:text-base lg:text-base xl:text-xl text-theme-light bg-primary">
                                    <i
                                        class="font-normal fa-solid fa-down-long text-persian-red md:text-xl lg:text-2xl xl:text-3xl text-light-yellow"></i>

                                    <p class="flex flex-col text-nowrap">
                                        <span>79% Off</span>
                                        <span class="md:text-sm lg:text-lg font-mediu -mt-1m lg:-mt-2 xl:-mt-1">3 days
                                            left</span>
                                    </p>
                                </button>
                            </a>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="flex gap-2 py-4 md:gap-4 md:py-5">
                        <a href="#"
                            class="flex-shrink-0 block w-24 h-32 overflow-hidden xsm:w-36 xsm:h-44 md:w-28 md:h-36 lg:w-40 lg:h-48 xl:w-48 xl:h-56 rounded-xl">
                            <img src="./assests/images/big-save-feature-3.png" alt="Silicone Navy Dial Watch" />
                        </a>

                        <div class="flex-grow space-y-2 md:space-y-3">
                            <span
                                class="text-xs xsm:text-sm lg:text-base xl:text-lg bg-persian-red/70 text-theme-light px-4 py-1.5 xsm:px-6 xsm:py-2 rounded-3xl">Big
                                Save</span>
                            <p class="text-sm font-medium xsm:text-base lg:text-lg xl:text-xl hover:text-primary eq">
                                <a href="#" class="line-clamp-2">Silicone Navy Dial Watch</a>
                            </p>
                            <p class="text-sm lg:text-base xl:text-lg text-jet-gray">
                                352 Sold
                                <i class="fa-solid fa-star text-theme-dark"></i> 4.8
                            </p>
                            <a href="#" class="relative flex flex-col lg:flex-row">
                                <button
                                    class="inline-flex items-center justify-center lg:justify-start lg:text-left w-3/4 xsm:w-2/3 lg:w-1/2 px-3 py-2 xsm:px-5 xsm:py-3 md:py-2 rounded-[3.5rem] gap-1 sm:gap-2 xl:px-10 md:text-lg lg:text-xl font-bold text-theme-light bg-theme-dark">
                                    <span class="font-normal lg:text-lg xl:text-xl text-white/70">$</span>
                                    55.00
                                </button>
                                <button
                                    class="inline-flex items-center justify-center w-3/4 gap-1 px-5 py-2 -mt-2 text-sm font-bold rounded-full lg:mt-0 lg:-ml-14 2xl:-ml-20 xsm:w-2/3 lg:w-1/2 lg:gap-2 sm:gap-3 xsm:text-base lg:text-base xl:text-xl text-theme-light bg-primary">
                                    <i
                                        class="font-normal fa-solid fa-down-long text-persian-red md:text-xl lg:text-2xl xl:text-3xl text-light-yellow"></i>

                                    <p class="flex flex-col text-nowrap">
                                        <span>79% Off</span>
                                        <span class="md:text-sm lg:text-lg font-mediu -mt-1m lg:-mt-2 xl:-mt-1">3 days
                                            left</span>
                                    </p>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Page Main Content Ended -->
    </main>

    @push('scripts')

    @endpush
@endsection
