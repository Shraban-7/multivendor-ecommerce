@extends('frontend.layouts.app')
@section('title', $seller->business_name)

@section('content')
<!-- Top Banner -->
<!-- <section class="w-full bg-gradient-to-r from-gray-900 to-black text-white py-3 md:py-4">
        <div class="container mx-auto flex flex-col xsm:flex-row justify-between items-center px-4 gap-2">
            <div class="flex items-center justify-center gap-3 text-[#ADFFA2] p-2">
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                    <i class="fa-solid fa-truck-fast text-lg"></i>
                </div>
                <div>
                    <div class="text-sm font-medium">Free Shipping</div>
                    <div class="text-xs opacity-80">Special For You</div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3 text-[#FFF7A7] p-2">
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                    <i class="fa-solid fa-box text-lg"></i>
                </div>
                <div>
                    <div class="text-sm font-medium">Delivery Guarantee</div>
                    <div class="text-xs opacity-80">Refund for any issues</div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3 text-butterfly-blue p-2">
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                    <i class="fa-solid fa-mobile-screen text-lg"></i>
                </div>
                <div>
                    <div class="text-sm font-medium">Get The SlashMart App</div>
                    <div class="text-xs opacity-80">Exclusive mobile deals</div>
                </div>
            </div>
        </div>
    </section> -->

<!-- Page Main Content Starts -->

<section class="mb-5 container mx-auto px-4">
    <!-- shop header -->
    <div class="relative h-48 md:h-64 rounded">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="container mx-auto px-4 relative h-full flex items-end pb-6">
            <div class="flex items-center">
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                    <img src="{{ storage_url($seller->business_logo) }}" alt="Shop Logo" class="w-full h-full object-cover">
                </div>
                <div class="ml-4 text-white">
                    <h1 class="text-2xl md:text-3xl font-bold">{{ $seller->business_name }}</h1>
                    <p class="flex items-center mt-1">
                        <i class="fas fa-star text-yellow-300 mr-1"></i>
                        <span class="font-semibold">4.8</span>
                        <span class="mx-2">|</span>
                        <span>1.2k Reviews</span>
                        <span class="mx-2">|</span>
                        <span>{{ number_shorten_format($seller->total_followers) }} Followers</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Links -->
    <div class="shop-links mb-8">
        <!-- Navigation -->
        <nav class="flex flex-wrap items-center text-jet-gray gap-x-6 gap-y-3 border-b border-gray-200 py-4">
            <a href="#" class="text-primary border-b-2 border-primary pb-1 font-medium">Products</a>
            <a href="#" class="hover:text-primary transition-colors font-medium">About</a>
            <a href="{{ route('sellers.reviews', $seller->username) }}" class="hover:text-primary transition-colors font-medium">
                Reviews
                <span class="bg-primary/10 text-primary px-2 py-1 rounded-full text-xs font-medium">
                    {{ number_format($avgRating, 2) }} <i class="fa-solid fa-star text-xs"></i>
                </span>
            </a>

            <!-- shop product search -->
            <div class="w-full md:w-auto mt-4 md:mt-0 ml-auto">
                <div class="relative">
                    <input type="text" placeholder="Search all {{ $totalItem }} items"
                        class="text-sm w-full py-2.5 pl-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-700 placeholder-gray-400 transition-all" />
                    <button
                        class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-primary hover:bg-primary-dark p-2 rounded-full text-white transition-colors">
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
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <h6 class="text-gray-600 font-medium"> {{ $totalItem }} Items</h6>
        <form method="GET" action="{{ route('sellers.shop', $seller->username) }}"
            class="flex items-center gap-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors text-sm pl-5 pr-3 py-2 text-gray-700">
            <label for="sort-by" class="whitespace-nowrap">Sort By:</label>
            <select id="sort-by" name="sortBy" onchange="this.form.submit()"
                class="bg-transparent appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 cursor-pointer pr-6">
                <option selected="">Relevance</option>
                <option value="best-selling" {{ request('sortBy') == 'best-selling' ? 'selected' : '' }}>Best Selling</option>
                <option value="trending" {{ request('sortBy') == 'trending' ? 'selected' : '' }}>Trending</option>
                <option value="new-arrivals" {{ request('sortBy') == 'new-arrivals' ? 'selected' : '' }}>New Arrivals</option>
            </select>
        </form>
    </div>

    <!-- Product Card's Wrapper -->
    <div id="product-list"
        class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 xl:gap-5">
        @include('frontend.partials.product-card-load', ['products' => $products])
    </div>

    <div class="load-more-btn text-center mt-12">
        <button id="loadMoreBtn" data-page="1"
            data-url="{{ route('sellers.shop', $seller->username) }}?sortBy={{ request()->sortBy }}"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-full text-base font-medium inline-flex gap-2 items-center shadow-md hover:shadow-lg transition-all"
            type="button">
            <span>Load More</span>
            <i class="fa-solid fa-chevron-down text-sm"></i>
        </button>
    </div>
</section>

<style>
    .more-dropdown-wrapper:hover .dropdown-menu {
        display: block;
    }
</style>

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
                    '<i class="fa fa-spinner fa-spin"></i> Loading...'
                );
            },
            success: function(response) {
                if (response.trim() !== '') {
                    $('#product-list').append(response);

                    button.data('page', page);
                    button.prop('disabled', false).html(
                        '<span>Load More</span> <i class="fa-solid fa-chevron-down text-sm"></i>'
                    );

                    const scriptTags = $(response).filter('script[data-quickview]');
                    scriptTags.each(function() {
                        const json = $(this).html();
                        try {
                            const data = JSON.parse(json);
                            window.quickViewData = window.quickViewData || {};
                            window.quickViewData[data.id] = {
                                product: data.product,
                                defaultVariant: data.defaultVariant
                            };
                        } catch (e) {
                            console.error('Invalid quickview JSON format:', e);
                        }
                    });

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