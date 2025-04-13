@extends('frontend.layouts.app')
@section('title', 'Shop Detail ' . $seller->business_name)

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
                            <img src="{{ asset('assets/' . $seller->business_logo) }}" alt="Louis Vuitton Logo"
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
                                <div class="font-medium">5.5k+</div>
                                <div class="text-xs text-jet-gray">Followers</div>
                            </div>
                            <div class="sold">
                                <div class="font-medium">200k+</div>
                                <div class="text-xs text-jet-gray">Sold</div>
                            </div>
                            <div class="items">
                                <div class="font-medium">{{ count($seller->products) }}</div>
                                <div class="text-xs text-jet-gray">Items</div>
                            </div>
                        </div>

                        <button
                            class="eq bg-[#08C514] hover:bg-black text-white px-3 py-1 md:px-6 md:py-2 rounded-full text-sm inline-flex items-center justify-center gap-2 text-center">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Follow
                        </button>
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
                    <a href="#" class="hover:text-black eq">Mens hoodies</a>
                    <a href="#" class="hover:text-black eq">Mens T-shirt</a>
                    <a href="#" class="hover:text-black eq">More Selection
                        <i class="fa-solid fa-chevron-down"></i>
                    </a>
                    <a href="/shopReviews.html"
                        class="hover:text-black eq inline-flex items-center gap-1 flex-nowrap">Reviews (5.00<i
                            class="fa-solid fa-star text-xs"></i>)</a>
                    <!-- shop product search -->
                    <div class="xsm:w-6/12 md:w-5/12 lg:w-3/12 w-9/12 mr-auto xsm:ml-auto sm:mr-0 sm:ml-auto">
                        <div class="relative">
                            <input type="text" placeholder="Search all {{ count($seller->products) }} items"
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

            <!-- Sorting -->
            <div class="mt-3 flex items-center justify-between">
                <h6>{{ count($seller->products) }} Items</h6>
                <form
                    class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray">
                    <label for="sort-by" class="block whitespace-nowrap">Sort By:</label>
                    <select id="sort-by"
                        class="block w-full bg-transparent appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer">
                        <option selected="">Relevance</option>
                        <option value="best-selling">Best Selling</option>
                        <option value="trending">Trending</option>
                        <option value="popularity">Popularity</option>
                        <option value="new-arrivals">New Arrivals</option>
                    </select>
                </form>
            </div>

            <!-- Product Card's Wrapper -->
            <div class="mt-8 grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6 gap-3">
                <!-- Product Card 1 -->
                @foreach ($seller->products as $product)
                    <div
                        class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq">
                        <div class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg">
                            <a href="{{ route('product.details',$product->slug) }}" class="block w-full h-full">
                                <img src="{{ asset('assets/'.$product->thumbnail) }}"
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
                            <h3 class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14">
                                <a href="{{ route('product.details',$product->slug) }}" class="hover:text-primary eq">{{ $product->name }}</a>
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

                                <span class="text-jet-gray">{{ number_shorten_format($product->stock_out) }} Sold</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                <span class="text-primary/80">Final Hours</span>
                                <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                    <div class="price flex items-center gap-1 flex-no-wrap">
                                        <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                        {{-- <span class="align-center text-sm text-[#ffa755]">$</span> --}}
                                        <h3 class="font-bold text-primary">{{ money($product->selling_price) }}</h3>
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

            <!-- Load More Btn -->
            <div class="load-more-btn text-center mt-10">
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
