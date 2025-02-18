@extends('frontend.layouts.app')
@section('title', 'A Multi-Vendor E-Commerce platform')

@section('content')
    <main class="home-page">
        <!-- Hero Section Starts -->
        <section class="hero-section flex flex-wrap lg:h-screen 2xl:h-[110vh]">
            <div class="w-full md:w-1/2 h-full">
                <a href="#">
                    <img src="{{ asset('assets/frontend/images/hero-image-1.png') }}" alt="Image 1"
                        class="w-full h-full object-cover" />
                </a>
            </div>

            <div class="w-full md:w-1/2 h-full">
                <div class="flex h-1/2">
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-2.png') }}" alt="Image 2"
                                class="w-full h-full object-cover" />
                        </a>
                    </div>
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-3.png') }}" alt="Image 3"
                                class="w-full h-full object-cover" />
                        </a>
                    </div>
                </div>
                <div class="flex h-1/2">
                    <div class="md:w-[45%] w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-4.png') }}" alt="Image 4"
                                class="w-full h-full object-cover" />
                        </a>
                    </div>
                    <div class="md:w-[55%] w-1/2">
                        <a href="#">
                            <img src="{{ asset('assets/frontend/images/hero-image-5.jpg') }}" alt="Image 5"
                                class="w-full h-full object-cover" />
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
                <div class="container flex items-center flex-col md:flex-row gap-3 md:gap-0 justify-between py-3 md:py-5">
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
                        class="text-3xl flex flex-col md:flex-row items-center gap-2 md:gap-5 font-semibold text-theme-light">
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
                        <div class="swiper-slide py-5 px-1">
                            <a href="#" class="block product-card w-full rounded-lg hover:shadow-lg p-3 eq group">
                                <!-- slide image -->
                                <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/light-deal-1.png') }}"
                                        alt="Model wearing a jacket"
                                        class="w-full h-full object-cover group-hover:scale-125 eq" />
                                    <span
                                        class="block absolute bottom-9 left-1/2 -translate-x-1/2 bg-white px-4 py-3 rounded-full text-sm w-3/5 text-center">Almost
                                        Sold Out</span>
                                </div>
                                <!-- Slide Content -->
                                <div class="card-content mt-2 space-y-1">
                                    <!-- price & sold info -->
                                    <div class="price-sold-amount flex items-center gap-2">
                                        <h2 class="text-2xl font-bold text-primary">
                                            <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                            <span class="align-middle text-xs text-[#ffa755]">$</span>
                                            25.00
                                        </h2>
                                        <p class="text-base">4.5K+ Sold</p>
                                    </div>
                                    <!-- time -->
                                    <div class="time-progres flex items-center flex-wrap gap-2">
                                        <div class="w-[60%] bg-gray-200 rounded-full h-2">
                                            <div class="progress bg-primary h-2 rounded-full" style="width: 95%"></div>
                                        </div>
                                        <span
                                            class="w-[35%] due-time text-sm inline-flex flex-no-wrap gap-1 items-center"><i
                                                class="fa-regular fa-clock"></i> 1:12:53.55</span>
                                    </div>
                                    <!-- rating -->
                                    <div class="flex items-center gap-2">
                                        <div class="rating-stars text-xs text-light-yellow">
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
                        <!-- slide 2 -->
                        <div class="swiper-slide py-5 px-1">
                            <a href="#" class="block product-card w-full rounded-lg hover:shadow-lg p-3 eq group">
                                <!-- slide image -->
                                <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/light-deal-2.png') }}"
                                        alt="Ladies Vanity Bag"
                                        class="w-full h-full object-cover group-hover:scale-125 eq" />
                                    <span
                                        class="block absolute bottom-9 left-1/2 -translate-x-1/2 bg-white px-4 py-3 rounded-full text-sm w-3/5 text-center">Almost
                                        Sold Out</span>
                                </div>
                                <!-- Slide Content -->
                                <div class="card-content mt-2 space-y-1">
                                    <!-- price & sold info -->
                                    <div class="price-sold-amount flex items-center gap-2">
                                        <h2 class="text-2xl font-bold text-primary">
                                            <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                            <span class="align-middle text-xs text-[#ffa755]">$</span>
                                            40.00
                                        </h2>
                                        <p class="text-base">8.2K+ Sold</p>
                                    </div>
                                    <!-- time -->
                                    <div class="time-progres flex items-center flex-wrap gap-2">
                                        <div class="w-[60%] bg-gray-200 rounded-full h-2">
                                            <div class="progress bg-primary h-2 rounded-full" style="width: 95%"></div>
                                        </div>
                                        <span
                                            class="w-[35%] due-time text-sm inline-flex flex-no-wrap gap-1 items-center"><i
                                                class="fa-regular fa-clock"></i> 0:45:53.55</span>
                                    </div>
                                    <!-- rating -->
                                    <div class="flex items-center gap-2">
                                        <div class="rating-stars text-xs text-light-yellow">
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
                        <!-- slide 3 -->
                        <div class="swiper-slide py-5 px-1">
                            <a href="#" class="block product-card w-full rounded-lg hover:shadow-lg p-3 eq group">
                                <!-- slide image -->
                                <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/light-deal-3.png') }}"
                                        alt="Exclusive shoe's for Boy's"
                                        class="w-full h-full object-cover group-hover:scale-125 eq" />
                                    <span
                                        class="block absolute bottom-9 left-1/2 -translate-x-1/2 bg-white px-4 py-3 rounded-full text-sm w-3/5 text-center">Almost
                                        Sold Out</span>
                                </div>
                                <!-- Slide Content -->
                                <div class="card-content mt-2 space-y-1">
                                    <!-- price & sold info -->
                                    <div class="price-sold-amount flex items-center gap-2">
                                        <h2 class="text-2xl font-bold text-primary">
                                            <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                            <span class="align-middle text-xs text-[#ffa755]">$</span>
                                            30.00
                                        </h2>
                                        <p class="text-base">1.9K+ Sold</p>
                                    </div>
                                    <!-- time -->
                                    <div class="time-progres flex items-center flex-wrap gap-2">
                                        <div class="w-[60%] bg-gray-200 rounded-full h-2">
                                            <div class="progress bg-primary h-2 rounded-full" style="width: 65%"></div>
                                        </div>
                                        <span
                                            class="w-[35%] due-time text-sm inline-flex flex-no-wrap gap-1 items-center"><i
                                                class="fa-regular fa-clock"></i> 18:13:45.12</span>
                                    </div>
                                    <!-- rating -->
                                    <div class="flex items-center gap-2">
                                        <div class="rating-stars text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        </div>
                                        <span class="text-sm text-primary">Final Hours</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- slide 4 -->
                        <div class="swiper-slide py-5 px-1">
                            <a href="#" class="block product-card w-full rounded-lg hover:shadow-lg p-3 eq group">
                                <!-- slide image -->
                                <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/light-deal-4.png') }}"
                                        alt="A man wearing a Yellow T-Shirt & Black Short Pant"
                                        class="w-full h-full object-cover group-hover:scale-125 eq" />
                                    <span
                                        class="block absolute bottom-9 left-1/2 -translate-x-1/2 bg-white px-4 py-3 rounded-full text-sm w-3/5 text-center">Almost
                                        Sold Out</span>
                                </div>
                                <!-- Slide Content -->
                                <div class="card-content mt-2 space-y-1">
                                    <!-- price & sold info -->
                                    <div class="price-sold-amount flex items-center gap-2">
                                        <h2 class="text-2xl font-bold text-primary">
                                            <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                            <span class="align-middle text-xs text-[#ffa755]">$</span>
                                            99.99
                                        </h2>
                                        <p class="text-base">0.5K+ Sold</p>
                                    </div>
                                    <!-- time -->
                                    <div class="time-progres flex items-center flex-wrap gap-2">
                                        <div class="w-[60%] bg-gray-200 rounded-full h-2">
                                            <div class="progress bg-primary h-2 rounded-full" style="width: 45%"></div>
                                        </div>
                                        <span
                                            class="w-[35%] due-time text-sm inline-flex flex-no-wrap gap-1 items-center"><i
                                                class="fa-regular fa-clock"></i> 12:11:14.17</span>
                                    </div>
                                    <!-- rating -->
                                    <div class="flex items-center gap-2">
                                        <div class="rating-stars text-xs text-light-yellow">
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
                        <!-- slide 5 -->
                        <div class="swiper-slide py-5 px-1">
                            <a href="#" class="block product-card w-full rounded-lg hover:shadow-lg p-3 eq group">
                                <!-- slide image -->
                                <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                    <img src="{{ asset('assets/frontend/images/light-deal-5.png') }}"
                                        alt="Electronics Accessories By Apple"
                                        class="w-full h-full object-cover group-hover:scale-125 eq" />
                                    <span
                                        class="block absolute bottom-9 left-1/2 -translate-x-1/2 bg-white px-4 py-3 rounded-full text-sm w-3/5 text-center">Almost
                                        Sold Out</span>
                                </div>
                                <!-- Slide Content -->
                                <div class="card-content mt-2 space-y-1">
                                    <!-- price & sold info -->
                                    <div class="price-sold-amount flex items-center gap-2">
                                        <h2 class="text-2xl font-bold text-primary">
                                            <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                            <span class="align-middle text-xs text-[#ffa755]">$</span>
                                            299.99
                                        </h2>
                                        <p class="text-base">10.5K+ Sold</p>
                                    </div>
                                    <!-- time -->
                                    <div class="time-progres flex items-center flex-wrap gap-2">
                                        <div class="w-[60%] bg-gray-200 rounded-full h-2">
                                            <div class="progress bg-primary h-2 rounded-full" style="width: 25%"></div>
                                        </div>
                                        <span
                                            class="w-[35%] due-time text-sm inline-flex flex-no-wrap gap-1 items-center"><i
                                                class="fa-regular fa-clock"></i> 0:50:13.45</span>
                                    </div>
                                    <!-- rating -->
                                    <div class="flex items-center gap-2">
                                        <div class="rating-stars text-xs text-light-yellow">
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
                    </div>
                </div>
            </div>
        </section>
        <!-- Light Deals Section Ended -->

        <!-- Interest Section Starts -->
        <section class="interest-section section-padding">
            <div class="container">
                <!-- section title -->
                <div class="sec-heading relative">
                    <h2
                        class="font-semibold md:text-center sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl uppercase text-theme-dark">
                        Explore your Interest
                    </h2>

                    <span class="inline-block absolute top-1/2 -translate-y-1/2 right-0"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- Interest categores swiper carousel -->
                <div class="swiper categoriesSwiper mt-10 md:mt-16">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        @foreach ($categories as $category)
                            <div class="swiper-slide group/categores eq">
                                <a href="#" class="block product-card w-full flex flex-col items-center">
                                    <!-- slide image -->
                                    <div class="card-image lg:h-28 lg:w-28 md:w-24 md:h-24 w-16 h-16 relative">
                                        <img src="{{ asset('assets/' . $category->image) }}" alt="Grocery"
                                            class="w-full h-full object-contain" />
                                    </div>
                                    <!-- Slide Content -->
                                    <div class="card-content mt-3 lg:mt-5">
                                        <a href="#"
                                            class="block text-black text-center group-hover/categores:text-light-yellow font-medium md:text-lg lg:text-xl text-sm eq">{{ $category->name }}</a>
                                    </div>
                                </a>
                            </div>
                        @endforeach

                    </div>
                </div>

                <!-- Interest Products -->
                <div class="swiper fiveSlideSwiper mt-10 md:mt-20">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        <div class="swiper-slide group/interest-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div class="w-full bg-theme-light rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 px-10 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/int-pro-1.png') }}"
                                                alt="Ladies Large chocolate vanity Bag" />
                                        </a>
                                    </div>
                                    <div class="p-2 sm:p-4 space-y-1">
                                        <h2
                                            class="text-theme-dark group-hover/interest-pro-card:text-persian-blue font-semibold line-clamp-3 md:line-clamp-2 eq text-sm md:text-base h-16 md:h-12">
                                            <a href="#">Muffets & Tuffets Whole Wheat Bread 400 g</a>
                                        </h2>
                                        <div class="rating-stars text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <p class="text-persian-blue">1 KG</p>
                                        <p class="font-semibold text-sand-brown">$ 80.00</p>

                                        <div class="add-cart">
                                            <button
                                                class="block bg-white h-10 flex justify-between items-center w-full rounded-full p-2 mt-2 hover:shadow-md eq">
                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 inline-flex items-center justify-center rounded-full bg-primary text-white text-xs md:text-sm"><i
                                                        class="fa-solid fa-cart-plus"></i></span>

                                                <span class="text-sm md:text-base">Add</span>

                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-[#F9F8F6] text-sand-brown text-xs sm:text-sm"><i
                                                        class="fa-solid fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- slide 2 -->
                        <div class="swiper-slide group/interest-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div class="w-full bg-theme-light rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 px-10 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/int-pro-2.png') }}"
                                                alt="Smart Watch" />
                                        </a>
                                    </div>
                                    <div class="p-2 sm:p-4 space-y-1">
                                        <h2
                                            class="text-theme-dark group-hover/interest-pro-card:text-persian-blue font-semibold line-clamp-3 md:line-clamp-2 eq text-sm md:text-base h-16 md:h-12">
                                            <a href="#">Tracker for Android IPhone Devices</a>
                                        </h2>
                                        <div class="rating-stars text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        </div>
                                        <p class="text-persian-blue">1 piece</p>
                                        <p class="font-semibold text-sand-brown">$ 120.00</p>

                                        <div class="add-cart">
                                            <button
                                                class="block bg-white h-10 flex justify-between items-center w-full rounded-full p-2 mt-2 hover:shadow-md eq">
                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 inline-flex items-center justify-center rounded-full bg-primary text-white text-xs md:text-sm"><i
                                                        class="fa-solid fa-cart-plus"></i></span>

                                                <span class="text-sm md:text-base">Add</span>

                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-[#F9F8F6] text-sand-brown text-xs sm:text-sm"><i
                                                        class="fa-solid fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- slide 3 -->
                        <div class="swiper-slide group/interest-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div class="w-full bg-theme-light rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 px-10 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/int-pro-3.png') }}"
                                                alt="Cool Shirt and Pant" />
                                        </a>
                                    </div>
                                    <div class="p-2 sm:p-4 space-y-1">
                                        <h2
                                            class="text-theme-dark group-hover/interest-pro-card:text-persian-blue font-semibold line-clamp-3 md:line-clamp-2 eq text-sm md:text-base h-16 md:h-12">
                                            <a href="#">Exclusive T-Shirt, Shirt, & Gavadin Pant Combo
                                                offer</a>
                                        </h2>
                                        <div class="rating-stars text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        </div>
                                        <p class="text-persian-blue">3 piece</p>
                                        <p class="font-semibold text-sand-brown">$ 299.00</p>

                                        <div class="add-cart">
                                            <button
                                                class="block bg-white h-10 flex justify-between items-center w-full rounded-full p-2 mt-2 hover:shadow-md eq">
                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 inline-flex items-center justify-center rounded-full bg-primary text-white text-xs md:text-sm"><i
                                                        class="fa-solid fa-cart-plus"></i></span>

                                                <span class="text-sm md:text-base">Add</span>

                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-[#F9F8F6] text-sand-brown text-xs sm:text-sm"><i
                                                        class="fa-solid fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- slide 4 -->
                        <div class="swiper-slide group/interest-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div class="w-full bg-theme-light rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 px-10 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/int-pro-4.png') }}"
                                                alt="Winter Shoe" />
                                        </a>
                                    </div>
                                    <div class="p-2 sm:p-4 space-y-1">
                                        <h2
                                            class="text-theme-dark group-hover/interest-pro-card:text-persian-blue font-semibold line-clamp-3 md:line-clamp-2 eq text-sm md:text-base h-16 md:h-12">
                                            <a href="#">Imported Sheep's wool Shoe for Winter</a>
                                        </h2>
                                        <div class="rating-stars text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        </div>
                                        <p class="text-persian-blue">1 pair</p>
                                        <p class="font-semibold text-sand-brown">$ 99.00</p>

                                        <div class="add-cart">
                                            <button
                                                class="block bg-white h-10 flex justify-between items-center w-full rounded-full p-2 mt-2 hover:shadow-md eq">
                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 inline-flex items-center justify-center rounded-full bg-primary text-white text-xs md:text-sm"><i
                                                        class="fa-solid fa-cart-plus"></i></span>

                                                <span class="text-sm md:text-base">Add</span>

                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-[#F9F8F6] text-sand-brown text-xs sm:text-sm"><i
                                                        class="fa-solid fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- slide 5 -->
                        <div class="swiper-slide group/interest-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div class="w-full bg-theme-light rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-36 md:h-52 px-10 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/int-pro-5.png') }}"
                                                alt="Men's Solid Slim Width Necktie" />
                                        </a>
                                    </div>
                                    <div class="p-2 sm:p-4 space-y-1">
                                        <h2
                                            class="text-theme-dark group-hover/interest-pro-card:text-persian-blue font-semibold line-clamp-3 md:line-clamp-2 eq text-sm md:text-base h-16 md:h-12">
                                            <a href="#">Men's Solid Slim Width Necktie</a>
                                        </h2>
                                        <div class="rating-stars text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                        </div>
                                        <p class="text-persian-blue">1 pair</p>
                                        <p class="font-semibold text-sand-brown">$ 99.00</p>

                                        <div class="add-cart">
                                            <button
                                                class="block bg-white h-10 flex justify-between items-center w-full rounded-full p-2 mt-2 hover:shadow-md eq">
                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 inline-flex items-center justify-center rounded-full bg-primary text-white text-xs md:text-sm"><i
                                                        class="fa-solid fa-cart-plus"></i></span>

                                                <span class="text-sm md:text-base">Add</span>

                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-[#F9F8F6] text-sand-brown text-xs sm:text-sm"><i
                                                        class="fa-solid fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- slide 6 -->
                        <div class="swiper-slide group/interest-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div class="w-full bg-theme-light rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 px-10 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/int-pro-3.png') }}"
                                                alt="Cool Shirt and Pant" />
                                        </a>
                                    </div>
                                    <div class="p-2 sm:p-4 space-y-1">
                                        <h2
                                            class="text-theme-dark group-hover/interest-pro-card:text-persian-blue font-semibold line-clamp-3 md:line-clamp-2 eq text-sm md:text-base h-16 md:h-12">
                                            <a href="#">Exclusive T-Shirt, Shirt, & Gavadin Pant Combo
                                                offer</a>
                                        </h2>
                                        <div class="rating-stars text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <p class="text-persian-blue">3 piece</p>
                                        <p class="font-semibold text-sand-brown">$ 299.00</p>

                                        <div class="add-cart">
                                            <button
                                                class="block bg-white h-10 flex justify-between items-center w-full rounded-full p-2 mt-2 hover:shadow-md eq">
                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 inline-flex items-center justify-center rounded-full bg-primary text-white text-xs md:text-sm"><i
                                                        class="fa-solid fa-cart-plus"></i></span>

                                                <span class="text-sm md:text-base">Add</span>

                                                <span
                                                    class="w-6 h-6 sm:w-8 sm:h-8 flex items-center justify-center rounded-full bg-[#F9F8F6] text-sand-brown text-xs sm:text-sm"><i
                                                        class="fa-solid fa-plus"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Interest Section Ended -->

        <!-- Feature Gallery Section Starts -->
        <section class="feature-gallery">
            <div class="container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
                <!-- col 1 -->
                <div class="relative lg:col-span-2 lg:row-span-2 lg:h-[33rem] h-96">
                    <div class="relative group overflow-hidden rounded-xl h-full">
                        <div class="w-full h-full">
                            <!-- gallery image -->
                            <img src="{{ asset('assets/frontend/images/gallery-feature-pro-1.png') }}"
                                alt="Slow cooker with ingredients" class="w-full h-full object-cover" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 w-full h-full p-6 text-white flex flex-col items-start justify-center gap-2 sm:gap-5">
                            <p class="text-sm md:text-lg font-medium lg:text-xl">
                                It's slow-cook season
                            </p>
                            <h2 class="text-2xl md:text-4xl xl:text-5xl font-semibold !leading-[1.2]">
                                Comfort coming right up now
                            </h2>
                            <button
                                class="bg-white text-black px-6 text-sm sm:text-base md:px-8 font-medium py-2 rounded-full hover:bg-primary hover:text-white eq">
                                Shop Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- col 2 -->
                <div class="relative lg:col-span-2 lg:h-[33rem] overflow-hidden flex flex-col gap-4">
                    <!-- row 1 -->
                    <div class="relative group overflow-hidden rounded-xl h-1/2">
                        <!-- gallery image -->
                        <div class="w-full h-full">
                            <img src="{{ asset('assets/frontend/images/gallery-feature-pro-2.png') }}"
                                alt="Coats and jackets collection" class="w-full h-full object-cover" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 w-full h-full p-6 text-white flex flex-col items-start gap-2 sm:gap-5">
                            <p class="text-sm md:text-lg font-medium lg:text-xl">
                                Coat, Jackets & More
                            </p>
                            <h2 class="text-2xl md:text-3xl xl:text-4xl font-semibold !leading-[1.2]">
                                Beat The Chill
                            </h2>
                            <button
                                class="bg-white text-black px-6 text-sm sm:text-base md:px-8 font-medium py-2 rounded-full hover:bg-primary hover:text-white eq">
                                Shop Now
                            </button>
                        </div>
                    </div>

                    <!-- row 2 -->
                    <div class="h-1/2 grid grid-cols-2 gap-2">
                        <div class="relative group overflow-hidden rounded-xl h-full">
                            <!-- gallery image -->
                            <div class="w-full h-full">
                                <img src="{{ asset('assets/frontend/images/gallery-feature-pro-3.png') }}"
                                    alt="Home decor items" class="w-full h-full object-cover" />
                            </div>
                            <!-- overlay -->
                            <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                            <!-- content -->
                            <div class="absolute top-0 left-0 w-full h-full p-6 text-white">
                                <h2 class="text-xl md:text-lg xl:text-[1.7rem] font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                    Festive decor in everywhere
                                </h2>
                                <a href="#" class="text-white underline font-medium hover:text-primary eq">Shop
                                    Now</a>
                            </div>
                        </div>

                        <div class="relative group overflow-hidden rounded-xl h-full">
                            <!-- gallery image -->
                            <div class="w-full h-full">
                                <img src="{{ asset('assets/frontend/images/gallery-feature-pro-4.png') }}"
                                    alt="Fresh produce and vegetables" class="w-full h-full object-cover" />
                            </div>
                            <!-- overlay -->
                            <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                            <!-- content -->
                            <div class="absolute top-0 left-0 w-full h-full p-6 text-white">
                                <h2 class="text-xl md:text-lg xl:text-[1.7rem] font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                    Holiday Kitchen
                                </h2>
                                <a href="#" class="text-white underline font-medium hover:text-primary eq">Shop
                                    Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- col 3 -->
                <div class="lg:row-span-2 lg:h-[33rem] md:col-span-2 lg:col-span-1 h-96">
                    <div class="relative group overflow-hidden rounded-xl h-full">
                        <!-- gallery image -->
                        <div class="w-full lg:h-full">
                            <img src="{{ asset('assets/frontend/images/gallery-feature-pro-5.png') }}"
                                alt="Fashion collection" class="w-full h-full lg:h-full object-cover" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 w-full h-full p-6 text-white flex flex-col items-start justify-center gap-5">
                            <h2 class="text-xl md:text-lg xl:text-2xl font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                Curted Fits for the season
                            </h2>
                            <button
                                class="bg-white text-black px-6 text-sm sm:text-base md:px-8 font-medium py-2 rounded-full hover:bg-primary hover:text-white eq">
                                Shop Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Feature Gallery Section Starts -->

        <!-- Promotional Header Section -->
        <section class="promo-sec bg-light-yellow my-5">
            <div class="container flex items-center flex-col md:flex-row gap-2 md:gap-5 justify-center py-4 md:py-6">
                <!-- promo title -->
                <h2 class="text-xl text-jet-gray text-center">
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
                <div class="sec-heading relative">
                    <h2 class="font-semibold sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl uppercase text-theme-dark">
                        New Arrivals
                    </h2>

                    <span class="inline-block absolute top-1/2 -translate-y-1/2 right-0"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- New Arrivals Products Slider -->
                <div class="swiper productCommonSwiper mt-5 md:mt-10">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        <div class="swiper-slide group/new-arriv-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/new-arrival-prod-1.png') }}"
                                                alt="Exclusive Chair with foam seat" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/new-arriv-pro-card:text-butterfly-blue eq md:text-xl capitalize line-clamp-1 w-full">
                                                    <a href="#">Wooden chair</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $65.21
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $71.25
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 2 -->
                        <div class="swiper-slide group/new-arriv-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/new-arrival-prod-2.png') }}"
                                                alt="Gaming Console Remote" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/new-arriv-pro-card:text-butterfly-blue eq md:text-xl capitalize line-clamp-1 w-full">
                                                    <a href="#">Gaming Remote</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $99.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $220.50
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 3 -->
                        <div class="swiper-slide group/new-arriv-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/new-arrival-prod-3.png') }}"
                                                alt="Iphone 15 Pro Max" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/new-arriv-pro-card:text-butterfly-blue eq md:text-xl capitalize line-clamp-1 w-full">
                                                    <a href="#">iPhone 15 pro</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $1350
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $1599
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 4 -->
                        <div class="swiper-slide group/new-arriv-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/new-arrival-prod-4.png') }}"
                                                alt="Ladies Perfum" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/new-arriv-pro-card:text-butterfly-blue eq md:text-xl capitalize line-clamp-1 w-full">
                                                    <a href="#">Parfum</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $20.50
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $59.00
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 5 -->
                        <div class="swiper-slide group/new-arriv-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/new-arrival-prod-5.png') }}"
                                                alt="Electronic Bicycle" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/new-arriv-pro-card:text-butterfly-blue eq md:text-xl capitalize line-clamp-1 w-full">
                                                    <a href="#">Electronic Bicycle</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $999
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $1200
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 6 -->
                        <div class="swiper-slide group/new-arriv-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div class="item-img h-32 sm:h-40 md:h-52 pt-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/new-arrival-prod-2.png') }}"
                                                alt="Gaming Console Remote" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/new-arriv-pro-card:text-butterfly-blue eq md:text-xl capitalize line-clamp-1 w-full">
                                                    <a href="#">Remote</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $20.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $29.50
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Become Sellers, Trending Products & Promo Poster -->
                <div class="promo-trending-products flex flex-col lg:flex-row gap-5 mt-10">
                    <div class="trend-prods flex w-full sm:flex-row flex-col gap-5 lg:w-7/12 sm:h-96">
                        <!-- seller -->
                        <div class="seller h-auto sm:h-full sm:w-1/2 w-full">
                            <div class="item-img h-full w-full">
                                <a href="#">
                                    <img src="{{ asset('assets/frontend/images/hero-image-2.png') }}"
                                        class="w-full h-full object-cover" alt="Become our sellers" />
                                </a>
                            </div>
                        </div>

                        <!-- trending -->
                        <div class="products h-auto sm:h-full sm:w-1/2 w-full bg-[#F8F8F8] rounded-lg">
                            <!-- Product Cards -->
                            <div class="trending-phones p-5">
                                <h3 class="text-lg text-rangoon-green font-semibold mb-4 capitalize">
                                    Trending Products

                                    <span class="block w-28 h-[1.85px] bg-theme-teal"></span>
                                </h3>

                                <div class="trending-items-wrapper space-y-4">
                                    <!-- item 1 -->
                                    <div class="group/trending py-2 border-dashed border-b trending-item-card flex gap-3">
                                        <div class="item-image w-1/4">
                                            <a href="#" target="_blank">
                                                <img src="{{ asset('assets/frontend/images/trend-prod-1.png') }}"
                                                    alt="Meatigo Premium Goat Curry"
                                                    class="w-full h-full object-contain group-hover/trending:rotate-12 eq" />
                                            </a>
                                        </div>
                                        <div class="item-details flex flex-col gap-2 w-3/4 text-xs">
                                            <h4>
                                                <a href="#" target="_self"
                                                    class="text-theme-dark line-clamp-1 group-hover/trending:text-theme-teal font-semibold eq">
                                                    Meatigo Premium Goat Curry
                                                </a>
                                            </h4>
                                            <p class="text-jet-gray">450 G</p>
                                            <p class="text-theme-teal font-semibold">$ 70.00</p>
                                        </div>
                                    </div>

                                    <!-- item 2 -->
                                    <div class="group/trending py-2 border-dashed border-b trending-item-card flex gap-3">
                                        <div class="item-image w-1/4">
                                            <a href="#" target="_blank">
                                                <img src="{{ asset('assets/frontend/images/trend-prod-2.png') }}"
                                                    alt="Coral Bean Bag Chair"
                                                    class="w-full h-full object-contain group-hover/trending:rotate-12 eq" />
                                            </a>
                                        </div>
                                        <div class="item-details flex flex-col gap-2 w-3/4 text-xs">
                                            <h4>
                                                <a href="#" target="_self"
                                                    class="line-clamp-1 text-theme-dark group-hover/trending:text-theme-teal font-semibold eq">
                                                    Coral Bean Bag Chair
                                                </a>
                                            </h4>
                                            <p class="text-jet-gray">450 G</p>
                                            <p class="text-theme-teal font-semibold">$ 40.00</p>
                                        </div>
                                    </div>

                                    <!-- item 3 -->
                                    <div class="group/trending py-2 border-dashed border-b trending-item-card flex gap-3">
                                        <div class="item-image w-1/4">
                                            <a href="#" target="_blank">
                                                <img src="{{ asset('assets/frontend/images/trend-prod-3.png') }}"
                                                    alt="Benefits of using natural ston"
                                                    class="w-full h-full object-contain group-hover/trending:rotate-12 eq" />
                                            </a>
                                        </div>
                                        <div class="item-details flex flex-col gap-2 w-3/4 text-xs">
                                            <h4>
                                                <a href="#" target="_self"
                                                    class="line-clamp-1 text-theme-dark group-hover/trending:text-theme-teal font-semibold eq">
                                                    Benefits of using natural ston adash asb
                                                </a>
                                            </h4>
                                            <p class="text-jet-gray">1 KG</p>
                                            <p class="text-theme-teal font-semibold">$ 80.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- promotional poster -->
                    <div class="promotional-poster w-full lg:w-5/12 sm:h-96 h-auto">
                        <div class="promo-img h-full w-full rounded-2xl overflow-hidden">
                            <a href="#">
                                <img src="{{ asset('assets/frontend/images/promo-fifty.png') }}"
                                    class="w-full h-full object-cover sm:object-contain"
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
                <div class="sec-heading relative">
                    <h2 class="font-semibold sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl uppercase text-theme-dark">
                        Community Product
                    </h2>

                    <span class="inline-block absolute top-1/2 -translate-y-1/2 right-0"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- Community Product Products Slider -->
                <div class="swiper productCommonSwiper mt-5 md:mt-10">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/community-pro-1.png') }}"
                                                alt="Xbox Series S 1TB + Controller" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Xbox Series S 1TB + Controller</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $20.21
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $35.25
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 2 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/community-pro-2.png') }}"
                                                alt="Great Value Ground Cinnamon, 4.2 oz" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Great Value Ground Cinnamon, 4.2 oz</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $15.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $20.50
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 3 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/community-pro-3.png') }}"
                                                alt="2.25 lb Tray, Fresh, All Natural*" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">2.25 lb Tray, Fresh, All Natural*</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $299
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $350
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 4 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/community-pro-4.png') }}"
                                                alt="Hamburger Buns, 8 Count, 11 oz" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Hamburger Buns, 8 Count, 11 oz</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $49.99
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $60.00
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 5 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/community-pro-2.png') }}"
                                                alt="Fully Cooked Chicken Nuggets, 32 " />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Fully Cooked Chicken Nuggets, 32'</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $30.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $1200
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 6 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/community-pro-2.png') }}"
                                                alt="Great Value Ground Cinnamon, 4.2 oz" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Great Value Ground Cinnamon, 4.2 oz</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $15.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $20.50
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Community Product Section Ended -->

        <!-- Sessional Promotion Thumbnail Section Starts -->
        <section class="thumbnail-gallery">
            <div class="container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4">
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
                                <div class="relative group overflow-hidden rounded-xl h-full">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="w-full h-full object-cover" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif($key === 1)
                            <!-- Layout for the second category (split into two rows) -->
                            <div class="relative {{ $gridClass }}">
                                <!-- Top row (single banner) -->
                                <div class="relative group overflow-hidden rounded-xl h-1/2">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="w-full h-full object-cover" />
                                        </a>
                                    </div>
                                </div>

                                <!-- Bottom row (grid of two banners) -->
                                <div class="h-1/2 grid grid-cols-2 gap-2">
                                    @if (isset($special_category->banners[$key + 1]))
                                        <div class="relative group overflow-hidden rounded-xl h-full">
                                            <div class="w-full h-full">
                                                <a href="#">
                                                    <img src="{{ asset('assets/' . $special_category->banners[$key + 1]->image) }}"
                                                        alt="{{ $category->name }}" class="w-full h-full object-cover" />
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($special_category->banners[$key + 2]))
                                        <div class="relative group overflow-hidden rounded-xl h-full">
                                            <div class="w-full h-full">
                                                <a href="#">
                                                    <img src="{{ asset('assets/' . $special_category->banners[$key + 2]->image) }}"
                                                        alt="{{ $category->name }}" class="w-full h-full object-cover" />
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                    @elseif($key === 4)
                        <!-- Layout for the third category (tall single banner) -->
                        <div class="relative {{ $gridClass }}">
                            <div class="relative group overflow-hidden rounded-xl h-full">
                                <div class="w-full h-full">
                                    <a href="#">
                                        <img src="{{ asset('assets/' . $banner->image) }}" alt="{{ $category->name }}"
                                            class="w-full h-full object-cover" />
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
                <div class="sec-heading relative">
                    <h2 class="font-semibold sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl uppercase text-theme-dark">
                        Halloween Product
                    </h2>

                    <span class="inline-block absolute top-1/2 -translate-y-1/2 right-0"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- Community Product Products Slider -->
                <div class="swiper productCommonSwiper mt-5 md:mt-10">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/halloween-product-1.png') }}"
                                                alt="Halloween Black Ladies Dress" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Halloween Black Colour Ladies Frock</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $49.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $79.50
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 2 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/halloween-product-2.png') }}"
                                                alt="Halloween 3D Scary Bats Wall Sticker" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Halloween 3D Scary Bats Wall Sticker</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $10.50
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $15.00
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 3 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/halloween-product-3.png') }}"
                                                alt="Yellow Halloween pumpkin*" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">The Halloween Lighting pumpkin</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $49.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $99.00
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 4 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/halloween-product-4.png') }}"
                                                alt="Halloween Hershey candy" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Halloween Hershey candy</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $30.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $50.00
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 5 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/halloween-product-5.png') }}"
                                                alt="Halloween Cute White Ghost with Pink Pumpkin" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Halloween Cutey Pie White Doll</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $15.00
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $20.00
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- slide 6 -->
                        <div class="swiper-slide group/community-pro-card eq">
                            <div class="block product-card w-full flex flex-col items-center p-2">
                                <div
                                    class="w-full border border-[jet-gray]/30 rounded-md hover:shadow-md eq overflow-hidden">
                                    <div
                                        class="item-img h-32 sm:h-40 md:h-52 md:pt-10 pt-5 px-3 md:px-5 pb-3 md:pb-5 overflow-hidden">
                                        <a href="#">
                                            <img class="w-full h-full object-contain"
                                                src="{{ asset('assets/frontend/images/halloween-product-2.png') }}"
                                                alt="Halloween 3D Scary Bats Wall Sticker" />
                                        </a>
                                    </div>
                                    <div class="item-info p-2 sm:p-4 space-y-1">
                                        <div class="rating-stars sm:text-sm text-xs text-light-yellow">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-regular fa-star"></i>
                                        </div>
                                        <div class="flex items-end justify-between">
                                            <div class="name-price">
                                                <h2
                                                    class="text-theme-dark group-hover/community-pro-card:text-butterfly-blue eq text-sm capitalize line-clamp-2 w-full">
                                                    <a href="#">Halloween 3D Scary Bats Wall Sticker</a>
                                                </h2>
                                                <div class="flex gap-x-2 flex-wrap sm:text-lg">
                                                    <p class="new-price text-theme-teal font-medium">
                                                        $10.50
                                                    </p>
                                                    <p class="old-price text-jet-gray line-through">
                                                        $15.00
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="add-cart">
                                                <button
                                                    class="w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center rounded bg-primary text-theme-light text-sm sm:text-base hover:bg-light-yellow eq">
                                                    <span><i class="fa-solid fa-plus"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Halloween Product Section Ended -->

        <!-- Featured Videos Section Starts -->
        <section class="featured-videos-section section-padding">
            <div class="container">
                <!-- Section Title -->
                <div class="sec-heading relative">
                    <h2 class="font-semibold sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl uppercase text-theme-dark">
                        Featured In Videos
                    </h2>

                    <span class="inline-block absolute top-1/2 -translate-y-1/2 right-0"><a href="#"
                            class="theme-btn theme-outline-btn">View All</a></span>
                </div>

                <!-- Featured Video Swiper Slider -->
                <div class="swiper featuredVideoSwiper mt-5 md:mt-10">
                    <div class="swiper-wrapper">
                        <!-- slide 1 -->
                        <div class="swiper-slide group/featured-videos-pro-card py-3 eq">
                            <div
                                class="relative group rounded-t-lg rounded-b-sm hover:shadow-lg eq border overflow-hidden">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="w-full h-full object-cover cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-1.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-1.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8 w-1/3">
                                        <a href="#"
                                            class="text-white w-full block truncate font-light hover:text-light-yellow eq">@jesikaperker07854</a>
                                    </div>
                                    <div
                                        class="absolute bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 flex gap-2 md:gap-3">
                                        <button
                                            class="play-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="mute-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="py-4 px-2 sm:px-3 md:px-6 flex items-start gap-3">
                                    <div class="w-15 h-15 overflow-hidden">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-1.png') }}"
                                                alt="Commercial Slushy Machine 24L Frozen Drink Machine 1050W
                          Slush Smoothies Maker"
                                                class="w-full h-auto object-contain" />
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
                        <div class="swiper-slide group/featured-videos-pro-card py-3 eq">
                            <div
                                class="relative group rounded-t-lg rounded-b-sm hover:shadow-lg eq border overflow-hidden">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="w-full h-full object-cover cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-2.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-2.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8 w-1/3">
                                        <a href="#"
                                            class="text-white w-full block truncate font-light hover:text-light-yellow eq">@spinnertech2025</a>
                                    </div>
                                    <div
                                        class="absolute bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 flex gap-2 md:gap-3">
                                        <button
                                            class="play-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="mute-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="py-4 px-2 sm:px-3 md:px-6 flex items-start gap-3">
                                    <div class="w-15 h-15 overflow-hidden">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-2.png') }}"
                                                alt="Table lamp BUNDLE CANIS set of 1, with charger IP65, beige, mat dimmable - Deko-Light"
                                                class="w-full h-auto object-contain" />
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
                        <div class="swiper-slide group/featured-videos-pro-card py-3 eq">
                            <div
                                class="relative group rounded-t-lg rounded-b-sm hover:shadow-lg eq border overflow-hidden">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="w-full h-full object-cover cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-3.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-3.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8 w-1/3">
                                        <a href="#"
                                            class="text-white w-full block truncate font-light hover:text-light-yellow eq">@sarahperker47854</a>
                                    </div>
                                    <div
                                        class="absolute bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 flex gap-2 md:gap-3">
                                        <button
                                            class="play-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="mute-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="py-4 px-2 sm:px-3 md:px-6 flex items-start gap-3">
                                    <div class="w-15 h-15 overflow-hidden">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-3.png') }}"
                                                alt="Hamilton Beach 2 Slice Toaster with Extra-Wide Slots - Black in Bangladesh at BDT 7239, Rating"
                                                class="w-full h-auto object-contain" />
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
                        <div class="swiper-slide group/featured-videos-pro-card py-3 eq">
                            <div
                                class="relative group rounded-t-lg rounded-b-sm hover:shadow-lg eq border overflow-hidden">
                                <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                    <video class="w-full h-full object-cover cursor-pointer"
                                        poster="{{ asset('assets/frontend/images/featured-video-thumb-2.png') }}" muted
                                        loop>
                                        <source src="{{ asset('assets/frontend/videos/video-product-2.mp4') }}"
                                            type="video/mp4" />
                                    </video>
                                    <div class="absolute bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8 w-1/3">
                                        <a href="#"
                                            class="text-white w-full block truncate font-light hover:text-light-yellow eq">@spinnertech2025</a>
                                    </div>
                                    <div
                                        class="absolute bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 flex gap-2 md:gap-3">
                                        <button
                                            class="play-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                        <button
                                            class="mute-btn bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors sm:w-10 sm:h-10 w-8 h-8 text-white flex items-center justify-center">
                                            <i class="fa-solid fa-volume-high"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Product Info -->
                                <div class="py-4 px-2 sm:px-3 md:px-6 flex items-start gap-3">
                                    <div class="w-15 h-15 overflow-hidden">
                                        <a href="#">
                                            <img src="{{ asset('assets/frontend/images/video-prod-small-2.png') }}"
                                                alt="Table lamp BUNDLE CANIS set of 1, with charger IP65, beige, mat dimmable - Deko-Light"
                                                class="w-full h-auto object-contain" />
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
@endsection
