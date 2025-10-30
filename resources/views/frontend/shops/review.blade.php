@extends('frontend.layouts.app')

@section('title', 'Reviews | Shop Details')

@section('content')
    <main class="shop-details-page">
        <!-- Page Main Content Starts -->
        <section class="mb-5 container mx-auto px-4">
            @include('components.frontend.seller.header')

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
                <button id="loadMoreReviews" data-offset="2" data-url="{{ request()->url() }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-full text-base font-medium inline-flex gap-2 items-center shadow-md hover:shadow-lg transition-all"
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
