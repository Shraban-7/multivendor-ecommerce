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

<section class="mb-10">
    {{-- Shop Header --}}
    @include('components.frontend.seller.header')

    {{-- Sorting --}}
    <div class="flex items-center justify-between mb-4">
        <!-- Left: Item Count -->
        <h6 class="text-gray-600 font-medium text-sm sm:text-base">
            {{ $totalItem }} Items
        </h6>

        <!-- Right: Sorting -->
        <form method="GET" action="{{ route('sellers.shop', $seller->username) }}">
            <div class="relative w-40 sm:w-48">
                <select id="sort-by" name="sortBy" onchange="this.form.submit()"
                    class="block w-full rounded-md bg-white border border-gray-300 pl-3 pr-8 py-2 text-sm text-gray-700 
                       focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary hover:border-gray-400 cursor-pointer transition appearance-none">

                    <option value="" disabled {{ request('sortBy') == '' ? 'selected' : '' }}>
                        Sort By
                    </option>
                    <option value="relevance" {{ request('sortBy') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                    <option value="best-selling" {{ request('sortBy') == 'best-selling' ? 'selected' : '' }}>Best Selling</option>
                    <option value="trending" {{ request('sortBy') == 'trending' ? 'selected' : '' }}>Trending</option>
                    <option value="new-arrivals" {{ request('sortBy') == 'new-arrivals' ? 'selected' : '' }}>New Arrivals</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Product Grid --}}
    <div id="product-list"
        class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5">
        @include('frontend.partials.product-card-load', ['products' => $products])
    </div>

    {{-- Load More Button --}}
    <div class="text-center mt-12">
        <button id="loadMoreBtn" data-page="1"
            data-url="{{ route('sellers.shop', $seller->username) }}?sortBy={{ request()->sortBy }}"
            type="button"
            class="bg-primary-500 hover:bg-primary-dark text-white px-8 py-3 rounded-md text-base font-medium inline-flex gap-2 items-center shadow-sm hover:shadow-md hover:scale-105 transition-transform transition-shadow">
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