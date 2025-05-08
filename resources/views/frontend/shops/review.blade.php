@extends('frontend.layouts.app')

@section('title', 'Reviews | Shop Details')

@section('content')
    <main class="shop-details-page">
        <!-- Top Banner -->
        <section class="w-full bg-black text-white py-2 md:py-4">
            <div class="container mx-auto flex flex-col xsm:flex-row flex-wrap justify-between items-center px-4">
                <div
                    class="flex items-center justify-center gap-2 text-[#ADFFA2] border-b xsm:border-r xsm:border-b-0 p-2 md:p-0 md:pr-16 xsm:pr-16 border-white/30">
                    <i class="fa-solid fa-truck-fast text-2xl"></i>
                    <div>
                        <div class="text-sm">Free Shipping</div>
                        <div class="text-xs">Special For You</div>
                    </div>
                </div>
                <div class="flex items-center p-2 md:p-0 justify-center gap-2 text-[#FFF7A7]">
                    <i class="fa-solid fa-box text-2xl"></i>
                    <div>
                        <div class="text-sm">Delivery Guarantee</div>
                        <div class="text-xs">Refund for any issues</div>
                    </div>
                </div>
                <div
                    class="flex xsm:flex-1 md:flex-none items-center p-2 md:p-0 md:pl-16 justify-center gap-2 text-butterfly-blue border-t sm:border-t-0 sm:border-l sm:pl-16 border-white/30">
                    <i class="fa-solid fa-mobile-screen text-2xl"></i>
                    <div>
                        <div class="text-sm">Get The Tesko App</div>
                        <div class="text-xs">Refund for any issues</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page Main Content Starts -->
        <section class="products-section py-8 container">
            <!-- shop header -->
            <div class="shop-header">
                <div class="flex flex-wrap gap-y-2 justify-between items-center">
                    <!-- Left Side -->
                    <div class="flex items-center gap-2 md:gap-4">
                        <div class="w-14 md:w-20 h-14 md:h-20 rounded-full overflow-hidden">
                            <img src="{{ storage_url($seller->business_logo) }}" alt="{{ $seller->business_name }}"
                                class="w-full h-full object-cover" />
                        </div>

                        <div class="flex flex-col md:gap-2">
                            <h1 class="text-xl md:text-2xl font-light">{{ $seller->business_name }}</h1>
                            <div class="flex items-center gap-1 flex-nowrap text-sm">
                                <i class="fa-solid fa-circle-check text-butterfly-blue md:text-xl"></i>
                                <span>Authorized By Tesco</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex flex-col gap-2 text-center">
                        <div class="flex items-center gap-10 text-davy-gray text-sm md:text-base">
                            <div class="followers">
                                <div class="font-medium">{{ number_shorten_format($seller->total_follower) }}+</div>
                                <div class="text-xs text-jet-gray">Followers</div>
                            </div>
                            <div class="sold">
                                <div class="font-medium">{{ number_shorten_format($seller->total_sold) }}+</div>
                                <div class="text-xs text-jet-gray">Sold</div>
                            </div>
                            <div class="items">
                                <div class="font-medium">{{ $totalItem }}</div>
                                <div class="text-xs text-jet-gray">Items</div>
                            </div>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('sellers.follow', $seller->username) }}">
                                @CSRF
                                @if ($alreadyFollowed)
                                    <button type="submit"
                                        class="eq bg-[#e7a922] hover:bg-black text-white px-3 py-1 md:px-6 md:py-2 rounded-full text-sm inline-flex items-center justify-center gap-2 text-center">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                        Unfollow
                                    </button>
                                @else
                                    <button type="submit"
                                        class="eq bg-[#08C514] hover:bg-black text-white px-3 py-1 md:px-6 md:py-2 rounded-full text-sm inline-flex items-center justify-center gap-2 text-center">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                        Follow
                                    </button>
                                @endif
                            </form>
                        @endauth
                    </div>
                </div>

                <div class="text-sm md:text-base my-3">
                    <span class="text-theme-teal">(#10 top ranked provider)</span>
                    <span class="text-davy-gray">in sports supplies</span>
                </div>
            </div>

            <!-- Shop Links -->
            <div class="shop-links">
                <!-- Navigation -->
                <nav
                    class="flex flex-wrap items-center text-jet-gray/70 gap-x-4 gap-y-2 lg:gap-8 border-theme-dark/50 border-y py-2 pl-3">
                    <a href="#" class="text-black hover:text-black eq">Items</a>
                    @php
                        $firstTwo = $categories->take(2);
                        $remaining = $categories->skip(2);
                    @endphp

                    <div class="flex items-center gap-4 relative">
                        {{-- Show first two --}}
                        @foreach ($firstTwo as $category)
                            <a href="{{ route('category.details', $category->slug) }}" class="hover:text-black eq">
                                {{ $category->name }}
                            </a>
                        @endforeach

                        {{-- More dropdown --}}
                        @if ($remaining->count())
                            <div class="more-dropdown-wrapper relative">
                                <a href="#" class="hover:text-black eq more-toggle inline-flex items-center gap-1">
                                    More Selection <i class="fa-solid fa-chevron-down text-xs"></i>
                                </a>
                                <div
                                    class="dropdown-menu absolute top-full mt-2 left-0 bg-white border rounded shadow-lg z-50 min-w-max px-4 py-2 space-y-2 hidden">
                                    @foreach ($remaining as $category)
                                        <a href="{{ route('category.details', $category->slug) }}"
                                            class="block hover:text-primary">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('sellers.reviews', $seller->username) }}"
                        class="hover:text-black eq inline-flex items-center gap-1 flex-nowrap">Reviews
                        ({{ number_format($avgRating, 2) }}<i class="fa-solid fa-star text-xs"></i>)</a>
                    <!-- shop product search -->
                    <div class="xsm:w-6/12 md:w-5/12 lg:w-3/12 w-9/12 mr-auto xsm:ml-auto sm:mr-0 sm:ml-auto">
                        <div class="relative">
                            <input type="text" placeholder="Search all {{ $totalItem }} items"
                                class="text-sm md:text-xs lg:text-base w-full py-2 pl-4 lg:py-2 lg:pl-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:border-primary focus:ring-[2px] focus:ring-light-yellow text-jet-gray placeholder:text-jet-gray eq" />
                            <button
                                class="absolute top-1/2 right-1 transform -translate-y-1/2 bg-theme-light hover:bg-aqua-deep/10 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Rating Overview Section -->
            <div class="flex flex-col md:flex-row gap-5 items-start py-2 md:py-4">
                <div class="lg:w-4/12 md:w-[50%] w-full">
                    <h3 class="text-davy-gray md:text-lg font-medium mb-5">
                        Customers Reviews
                    </h3>
                    <!-- Customer Rating -->
                    <div class="flex items-start gap-4">
                        <div class="space-y-1">
                            <div class="text-3xl md:text-4xl text-persian-blue">{{ round($avgRating * 20) }}%</div>

                            <div class="flex text-yellow-400 text-2xl md:text-3xl">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span>
                                        @if ($i <= round($avgRating))
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    </span>
                                @endfor
                            </div>

                        </div>

                        <!-- Rating Bars -->
                        <div class="ratings-wrap w-full sm:w-2/4 md:w-3/4 2xl:w-1/2 lg:w-7/12 space-y-1">
                            @foreach ([5, 4, 3, 2, 1] as $star)
                                <div class="flex gap-2 md:gap-5 w-full items-center">
                                    <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-400 h-2 rounded-full"
                                                style="width: {{ $ratingDistribution[$star] ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-persian-blue">({{ str_pad($star, 2, '0', STR_PAD_LEFT) }}
                                        star)</span>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <h3 class="text-davy-gray md:text-lg font-medium my-5">
                        Store Creadibility
                    </h3>
                    <!-- Store Creadibility-->
                    <div class="flex items-start gap-4">
                        <div class="space-y-1 w-[50%] sm:w-[22%] xsm:w-[30%] md:w-[35%]">
                            <h2 class="text-3xl md:text-4xl text-persian-blue">{{ number_format($avgRating,1) }}</h2>
                            <p class="text-davy-gray text-xs sm:text-sm">Store Ratings</p>
                        </div>

                        <!-- credit label -->
                        <div class="ratings-wrap w-full sm:w-2/4 md:w-3/4 2xl:w-1/2 lg:w-7/12 space-y-1">
                            <!-- credit 1 -->
                            <div class="flex gap-2 md:gap-5 w-full items-center gap-1">
                                <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-7/12">
                                    <p class="text-xs">Items Description</p>
                                </div>
                                <span class="text-persian-blue">4.9</span>
                            </div>
                            <!-- credit 2 -->
                            <div class="flex gap-2 md:gap-5 w-full items-center gap-1">
                                <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-7/12">
                                    <p class="text-xs">Communication</p>
                                </div>
                                <span class="text-persian-blue">5.0</span>
                            </div>
                            <!-- credit 3 -->
                            <div class="flex gap-2 md:gap-5 w-full items-center gap-1">
                                <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-7/12">
                                    <p class="text-xs">Shipping Speed</p>
                                </div>
                                <span class="text-persian-blue">4.8</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:w-8/12 md:w-[50%] w-full md:px-2 xl:px-3">
                    <!-- Sorting -->
                    <div class="my-2 flex flex-wrap items-center justify-between">
                        <h6 class="text-persian-blue md:text-lg">{{ $totalReviews }} reviews</h6>
                        <form class="items-center gap-1 xsm:gap-2 md:gap-3 sm:text-sm text-xs flex text-theme-dark">
                            <label for="sort-by" class="block whitespace-nowrap text-jet-gray">Sort By</label>
                            <select id="sort-by"
                                class="inline-block bg-white hover:bg-aqua-deep/10 eq appearance-none border border-jet-gray/30  focus:outline-none focus:ring-0 focus:border-light-yellow cursor-pointer rounded-3xl py-1.5 px-2.5">
                                <option selected="">Relevance</option>
                                <option value="best-selling">Best Selling</option>
                                <option value="trending">Trending</option>
                                <option value="popularity">Popularity</option>
                                <option value="new-arrivals">New Arrivals</option>
                            </select>
                            <a href="#"
                                class="inline-block w-full bg-white hover:bg-aqua-deep/10 eq appearance-none border border-jet-gray/30  focus:border-light-yellow rounded-3xl py-1.5 px-2.5">
                                Photos / Videos
                            </a>
                        </form>
                    </div>
                    <h3 class="inline-block px-3 pr-10 py-2.5 bg-theme-teal/5 text-theme-teal space-x-2 text-sm mb-5">
                        <i class="fa-solid fa-check"></i>
                        <span>All reviews are from verified purchases</span>
                    </h3>

                    <!-- Average Rating -->
                    <div class="flex flex-wrap items-center gap-3 mb-3 md:mb-5">
                        <span class="text-davy-gray text-xl sm:text-2xl font-medium">
                            {{ number_format($avgRating, 1) }}
                        </span>
                        <div class="flex flex-nowrap gap-1 text-xs md:text-sm text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($avgRating >= $i)
                                    <i class="fa-solid fa-star"></i>
                                @elseif ($avgRating >= $i - 0.5)
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                @else
                                    <i class="fa-solid fa-star text-gray-400"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm sm:text-base text-jet-gray">
                            ({{ number_shorten_format($totalReviews) }}+ Review)
                        </span>
                    </div>

                    <!-- Review Section -->
                    <div class="comments-tags text-xs lg:text-sm text-davy-gray">
                        <h4>Item Reviews</h4>
                        <!-- review tags -->
                        <div class="review-tags flex flex-wrap gap-2 mt-2 md:mt-4 font-medium">
                            <button
                                class="inline-flex items-center lg:px-3 px-2 py-1 rounded-full border border-jet-gray gap-2">
                                <span class="flag-wrap h-4 lg:h-6 w-auto"><img class="w-auto h-full object-contain"
                                        src="{{ asset('assets/frontend/images/us-flag.png') }}"
                                        alt="Flag of USA" /></span>
                                <span>(800)</span>
                            </button>
                            <button class="inline-flex items-center lg:px-3 px-3 py-1 rounded-full border border-jet-gray">
                                Gift (90)
                            </button>
                            <button class="inline-flex items-center lg:px-3 px-3 py-1 rounded-full border border-jet-gray">
                                Adorable (250)
                            </button>
                            <button class="inline-flex items-center lg:px-3 px-3 py-1 rounded-full border border-jet-gray">
                                Beautiful (250)
                            </button>
                        </div>

                        <!-- User Reviews -->
                        <div
                            class="reviews-wrapper divide-y-2 divide-jet-gray/60 divide-dashed w-10/12 sm:w-3/5 md:w-4/5 xl:w-3/5">
                            <!-- review 1 -->
                            <div class="review-item space-y-2 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="user-avatar w-12 h-12 rounded-full overflow-hidden">
                                        <img src="{{ asset('assets/frontend/images/user-avatar-1.png') }}"
                                            alt="Alan Walker" />
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                        <h3 class="font-medium">Alan Walker</h3>
                                        <span class="flex items-center gap-2 text-gray-400">
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
                                <!-- colour -->
                                <h6 class="product-colour">Purchased : Gray</h6>
                                <!-- product images -->
                                <div class="flex product-images gap-2 md:gap-3 py-2">
                                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                                        <img src="{{ asset('assets/frontend/images/review-prod-1.png') }}" alt=""
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                                        <img src="{{ asset('assets/frontend/images/review-prod-2.png') }}" alt=""
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                                        <img src="{{ asset('assets/frontend/images/review-prod-3.png') }}" alt=""
                                            class="w-full h-full object-cover" />
                                    </div>
                                </div>
                                <!-- comment -->
                                <p class="product-feedback">
                                    Absolutely beautiful, good price perfect, perfect excellent
                                    product, very nice quality 😇😇
                                </p>

                                <div
                                    class="flex justify-center items-center text-black text-xs xsm:text-sm lg:text-base xl:text-lg">
                                    <div class="flex items-start divide-x divide-black gap-3 pt-2">
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
                            <div class="review-item space-y-2 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="user-avatar w-12 h-12 rounded-full overflow-hidden">
                                        <img src="{{ asset('assets/frontend/images/user-avatar-2.png') }}"
                                            alt="Josesph Man" />
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                        <h3 class="font-medium">Josesph Man</h3>
                                        <span class="flex items-center gap-2 text-gray-400">
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
                                    <span class="text-davy-gray text-lg sm:text-xl font-medium">4.8</span>
                                </div>
                                <!-- colour -->
                                <h6 class="product-colour">Purchased : Navy Blue</h6>
                                <!-- product images -->
                                <div class="flex product-images gap-2 md:gap-3 py-2">
                                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                                        <img src="{{ asset('assets/frontend/images/review-prod-4.png') }}" alt=""
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                                        <img src="{{ asset('assets/frontend/images/review-prod-5.png') }}" alt=""
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                                        <img src="{{ asset('assets/frontend/images/review-prod-6.png') }}" alt=""
                                            class="w-full h-full object-cover" />
                                    </div>
                                </div>
                                <!-- comment -->
                                <p class="product-feedback">
                                    Fantastic product at a great price. Truly impressed with the
                                    exceptional quality. Beautifully crafted and exceeds
                                    expectations 🥰 Highly recommend✅
                                </p>

                                <div
                                    class="flex justify-center items-center text-black text-xs xsm:text-sm lg:text-base xl:text-lg">
                                    <div class="flex items-start divide-x divide-black gap-3 pt-2">
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
            </div>

            <!-- Load More Btn -->
            <div class="load-more-btn text-center my-5">
                <button
                    class="theme-btn bg-theme-teal hover:bg-aqua-deep text-white px-5 py-2 xl:text-xl text-base md:text-lg inline-flex gap-2 items-center eq"
                    type="button">
                    <span>Load More</span>
                    <i class="fa-solid fa-chevron-down text-sm"></i>
                </button>
            </div>
        </section>
        <!-- Page Main Content Ended -->
    </main>
@endsection
