@extends('frontend.layouts.app')
@section('title',$seller->business_name)

@section('content')
    <main class="shop-details-page">
        <!-- Top Banner -->
        <section class="w-full bg-black text-white py-2 md:py-4">
            <div class="container mx-auto flex flex-col xsm:flex-row flex-wrap justify-between items-center px-4">
                <div
                    class="flex items-center justify-center gap-2 text-[#ADFFA2] border-b border-jet-gray/30 xsm:border-r xsm:border-jet-gray/30  xsm:border-b-0 xsm:border-jet-gray/30  p-2 md:p-0 md:pr-16 xsm:pr-16 border-white/30">
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


                <div class="flex flex-wrap gap-y-2 justify-between items-center mb-3">
                    <!-- Left Side -->
                    <div class="flex items-center gap-2 md:gap-4">
                        <div class="w-14 md:w-20 h-14 md:h-20 rounded-full overflow-hidden">
                            <img src="{{ storage_url($seller->business_logo) }}" alt="{{ $seller->business_name }}"
                                class="w-full h-full object-cover" />
                        </div>

                        <div class="flex flex-col md:gap-2">
                            <h1 class="text-xl md:text-2xl font-light">{{ $seller->business_name }}</h1>
                            <div class="flex items-center gap-1 flex-nowrap text-sm">
                                @if ($seller->is_best_seller)
                                    <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best
                                        Seller</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex flex-col gap-2 text-center">
                        <div class="flex items-center gap-10 text-davy-gray text-sm md:text-base">
                            <div class="followers">
                                <div class="font-medium">{{ number_shorten_format($seller->total_followers) }}+</div>
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

                @if ($seller->banner_images->isNotEmpty())
                    <div id="controls-carousel" class="relative w-full mb-3" data-carousel="slide">
                        <!-- Carousel wrapper -->
                        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                            <!-- Item 1 -->
                            @foreach ($seller->banner_images as $banner_image)
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <img src="{{ storage_url($banner_image->image) }}"
                                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                                        alt="...">
                                </div>
                            @endforeach

                        </div>

                        <!-- Slider controls -->
                        <button type="button"
                            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            data-carousel-prev>
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 1 1 5l4 4" />
                                </svg>
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button type="button"
                            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                            data-carousel-next>
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                                <svg class="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="sr-only">Next</span>
                            </span>
                        </button>
                    </div>
                @endif
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                <h6> {{ $totalItem }} Items</h6>
                <form method="GET" action="{{ route('sellers.shop', $seller->username) }}"
                    class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray">
                    <label for="sort-by" class="block whitespace-nowrap">Sort By:</label>
                    <select id="sort-by" name="sortBy" onchange="this.form.submit()"
                        class="block w-full bg-transparent appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer">
                        <option selected="">Relevance</option>
                        <option value="best-selling" {{ request('sortBy') == 'best-selling' ? 'selected' : '' }}>Best
                            Selling</option>
                        <option value="trending" {{ request('sortBy') == 'trending' ? 'selected' : '' }}>Trending</option>
                        <option value="new-arrivals" {{ request('sortBy') == 'new-arrivals' ? 'selected' : '' }}>New
                            Arrivals</option>
                    </select>
                </form>
            </div>

            <!-- Product Card's Wrapper -->
            <div id="product-list"
                class="mt-8 grid items-start grid-cols-1 gap-5 p-2 xsm:grid-cols-2 md:grid-cols-4 xl:gap-8 lg:p-0">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>

            <div class="load-more-btn text-center mt-10">
                <button id="loadMoreBtn" data-page="1"
                    data-url="{{ route('sellers.shop', $seller->username) }}?sortBy={{ request()->sortBy }}"
                    class="theme-btn bg-theme-teal hover:bg-aqua-deep text-white px-5 py-2 xl:text-xl text-base md:text-lg inline-flex gap-2 items-center eq"
                    type="button">
                    <span>Load More</span>
                    <i class="fa-solid fa-chevron-down text-sm"></i>
                </button>
            </div>
        </section>
        <!-- Page Main Content Ended -->
    </main>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.more-toggle').on('click', function(e) {
                    e.preventDefault();
                    $(this).next('.dropdown-menu').toggle();
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.more-dropdown-wrapper').length) {
                        $('.dropdown-menu').hide();
                    }
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
                            $('#product-list').append(response);
                            button.data('page', page);
                            button.prop('disabled', false).html(
                                '<span>Load More</span> <i class="fa-solid fa-chevron-down text-sm"></i>'
                            );

                            if (typeof initFlowbite === 'function') {
                                initFlowbite();
                            }

                            if (typeof initQuickViewModals === 'function') {
                                initQuickViewModals();
                            }

                            if (typeof initProductSwipers === 'function') {
                                initProductSwipers();
                            }
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
