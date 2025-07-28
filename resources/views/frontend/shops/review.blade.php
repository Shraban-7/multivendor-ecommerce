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
                        <div class="text-sm">Get The SlashMart App</div>
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
                            <h2 class="text-3xl md:text-4xl text-persian-blue">{{ number_format($avgRating, 1) }}</h2>
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
                        <div id="reviews-wrapper"
                            class="reviews-wrapper divide-y-2 divide-jet-gray/60 divide-dashed w-10/12 sm:w-3/5 md:w-4/5 xl:w-3/5">
                            @include('frontend.partials.review-card', ['reviews' => $reviews])
                        </div>
                    </div>
                </div>
            </div>

            <!-- Load More Comment Button -->
            <div class="pb-10 text-center border-b-2 border-gray-400 border-dashed load-more-btn">
                <button id="loadMoreReviews" data-offset="2"  data-url="{{ request()->url() }}"
                    class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                    type="button">
                    <span>Load More</span>
                    <i class="text-sm fa-solid fa-chevron-down"></i>
                </button>
            </div>

        </section>
        <!-- Page Main Content Ended -->
    </main>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.helpful-btn').on('click', function() {
                    const $btn = $(this);
                    const requestUrl = $btn.data('url');
                    const countSpan = $btn.find('.helpful-count');

                    $.ajax({
                        url: requestUrl,
                        method: 'POST',
                        data: {},
                        success: function(response) {
                            countSpan.text(response.count);
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

                $('.report-abuse-btn').on('click', function() {
                    const $btn = $(this);
                    const reviewId = $btn.data('review-id');
                    const url = $btn.data('url');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            review_id: reviewId
                        },
                        success: function(response) {
                            toastr.success(response.message || 'Reported successfully!');
                        },
                        error: function(xhr) {
                            if (xhr.status === 401) {
                                toastr.error('You must be logged in to report abuse.');
                            } else {
                                toastr.error('Something went wrong. Please try again.');
                            }
                        }
                    });
                });

                $('#loadMoreReviews').on('click', function() {
                    var $button = $(this);
                    var offset = parseInt($button.data('offset'));
                    var url = $button.data('url');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            offset: offset,
                        },
                        success: function(response) {
                            if ($.trim(response) === '') {

                                $button.hide();
                            } else {
                                $('#reviews-wrapper').append(response);
                                $button.data('offset', offset + 2);
                            }
                        },
                        error: function() {
                            console.error('Failed to load more reviews.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
