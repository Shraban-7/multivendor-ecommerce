@extends('frontend.layouts.app')
@section('title', $category->name)

@push('header')
<style>
    .filter-dropdown {
        position: relative;
        min-width: 120px;
    }

    .filter-dropdown-inner {
        position: relative;
        display: flex;
        align-items: center;
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .filter-dropdown:hover .filter-dropdown-inner {
        border-color: #9ca3af;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .filter-select {
        width: 100%;
        padding: 8px 36px 8px 12px;
        font-size: 14px;
        line-height: 1.5;
        color: #111827;
        background-color: transparent;
        border: none;
        appearance: none;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .filter-dropdown {
            min-width: 100px;
        }

        .filter-select {
            padding: 6px 32px 6px 10px;
            font-size: 13px;
        }
    }
</style>

@endpush

@section('content')
<main class="grocery-essentials-page">
    <!-- Page Promotion Banner Starts -->
    <section class="container py-5 page-promotion md:w-full">
        <div style="background-color: {{ $category->cover_bg_color }}"
            class="promo-wrapper md:container  grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden">
            <div
                class="flex flex-col items-start justify-center order-2 gap-3 p-5 md:order-1 promo-content sm:gap-5 md:p-10 lg:p-14 2xl:p-20">
                <h2 style="color: {{ $category->cover_text_color }}"
                    class="lg:text-3xl md:text-2xl text-xl  font-bold md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2">
                    {{ $category->cover_title }}
                </h2>
                <p style="color: {{ $category->cover_text_color }}" class="text-xs  md:pr-7 lg:pr-14 2xl:pr-20">
                    {!! $category->cover_description !!}
                </p>
                <a href="#" style="background-color: {{ $category->cover_button_color }}"
                    class="theme-btn px-5 py-2 lg:px-7 lg:px-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm">Learn
                    More</a>
            </div>
            <div class="order-1 promo-image">
                <div class="w-full img-wrap">
                    <div class="w-full h-40 overflow-hidden rounded-lg lg:h-96 md:h-80 md:rounded-3xl">
                        <a href="#" class="block w-full h-full">
                            <img src="{{ storage_url($category->cover_image) }}" alt="{{ $category->name }}"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Promotion Banner Ended -->

    <!-- All Filterts Sidebar Starts -->
    <section id="all-filters-drawer"
        class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80 text-theme-dark"
        tabindex="-1" aria-labelledby="drawer-label">
        <h5 id="drawer-label" class="inline-flex items-center mb-4 text-persian-blue">
            Filter search
        </h5>
        <button type="button" data-drawer-hide="all-filters-drawer" aria-controls="all-filters-drawer"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 right-2.5 inline-flex items-center justify-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
        <form method="GET" action="{{ route('category.details', $category->slug) }}" class="space-y-4">
            <!-- Categories -->
            <div>
                <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                    Categories
                </h3>
                <div class="space-y-2">
                    @foreach ($category->subcategories as $subCategory)
                    <label class="flex items-center">
                        <input type="checkbox" {{ request('subcategory') == $subCategory->slug ? 'checked' : '' }}
                            name="subcategory" value="{{ $subCategory->slug }}"
                            class="w-4 h-4 text-primary focus:ring-primary" />
                        <span class="ml-2 text-sm">{{ $subCategory->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Brand -->
            <div>
                <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                    Brand
                </h3>
                <div class="space-y-2">
                    @foreach ($brands as $brand)
                    <label class="flex items-center">
                        <input type="checkbox" {{ request('brand') == $brand->slug ? 'checked' : '' }}
                            name="brand" value="{{ $brand->slug }}"
                            class="w-4 h-4 text-primary focus:ring-primary" />
                        <span class="ml-2 text-sm">{{ $brand->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Review -->
            <div>
                <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                    Review
                </h3>
                <div class="space-y-2">
                    @for ($i = 5; $i >= 1; $i--)
                    <label class="flex items-center">
                        <input type="checkbox" name="review" value="{{ $i }}"
                            {{ request('review') == $i ? 'checked' : '' }}
                            class="w-4 h-4 text-primary focus:ring-primary" />
                        <div class="flex items-center ml-2">
                            <div class="flex text-light-yellow">
                                {!! str_repeat('★', $i) . str_repeat('☆', 5 - $i) !!}
                            </div>
                            <span class="ml-1 text-sm text-jet-gray">{{ $i }} Star</span>
                        </div>
                    </label>
                    @endfor
                </div>
            </div>

            <!-- Price -->
            <div>
                <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                    Price
                </h3>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="price" value="under"
                            {{ request('price') == 'under' ? 'checked' : '' }}
                            class="w-4 h-4 text-primary focus:ring-primary" />
                        <span class="ml-2 text-sm">Under {{ money(500) }}</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="price" value="range"
                            {{ request('price') == 'range' ? 'checked' : '' }}
                            class="w-4 h-4 text-primary focus:ring-primary" />
                        <span class="ml-2 text-sm">{{ money(500) }} - {{ money(5000) }}</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="price" value="upper"
                            {{ request('price') == 'upper' ? 'checked' : '' }}
                            class="w-4 h-4 text-primary focus:ring-primary" />
                        <span class="ml-2 text-sm">{{ money(5000) }}+</span>
                    </label>
                </div>

                <div class="flex gap-2 mt-5">
                    <div class="inline-flex items-center">
                        <input id="min" type="radio" value="min" name="price"
                            {{ request('price') == 'min' ? 'checked' : '' }} class="sr-only peer" />
                        <label for="min"
                            class="px-5 border rounded-3xl py-1 text-base text-jet-gray border-gray-300 peer-checked:ring-primary peer-checked:ring-[1px] peer-checked:!border-primary peer-checked:text-primary">
                            {{ currency() }} Min
                        </label>
                    </div>
                    <div class="inline-flex items-center">
                        <input id="max" type="radio" value="max" name="price"
                            {{ request('price') == 'max' ? 'checked' : '' }} class="sr-only peer" />
                        <label for="max"
                            class="px-5 border rounded-3xl py-1 text-base text-jet-gray border-gray-300 peer-checked:ring-primary peer-checked:ring-[1px] peer-checked:!border-primary peer-checked:text-primary">
                            {{ currency() }} Max
                        </label>
                    </div>
                </div>
            </div>

            <!-- Ships From -->
            <div>
                <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                    Ships From
                </h3>

                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="shipping" class="w-4 h-4 text-primary focus:ring-primary" />
                        <span class="ml-2 text-sm text-gray-600">Local Area (2 miles)</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-start gap-3">
                <a href="{{ route('category.details', $category->slug) }}"
                    class="px-5 py-2 text-sm text-gray-600 border-2 rounded-full border-theme-dark hover:border-persian-red hover:bg-persian-red hover:text-theme-light eq">
                    Reset
                </a>
                <button type="submit"
                    class="flex-1 px-4 py-2 text-sm text-white rounded-full bg-primary hover:bg-theme-dark eq">
                    Filter
                </button>
            </div>
        </form>
    </section>
    <!-- All Filterts Sidebar Ended-->

    <!-- Page Main Content Starts -->
    <section class="container products-section section-padding">
        @if(false)
        <div class="mb-8 md:mb-11">
            <h1 class="mb-5 text-xl font-medium sm:text-2xl text-jet-gray md:mb-10">
                {{ strtoupper($category->name) }}/ALL CATEGORIES
            </h1>
            <div class="flex items-start justify-between flex-nowrap">
                <div class="flex flex-wrap items-center w-10/12 gap-2 sm:gap-4 xl:w-auto lg:w-9/12 lg:w-auto">
                    <!-- All Categories -->
                    <form method="GET" action="{{ route('category.details', $category->slug) }}"
                        class="flex items-center gap-1 rounded-3xl bg-aqua-deep hover:bg-rangoon-green eq sm:text-sm text-xs md:text-base sm:pl-3 pl-3 sm:!pr-3 !pr-2 py-1.2 sm:py-1 inline-flex text-white cursor-pointer">
                        <label for="sort-by" class="block sr-only whitespace-nowrap">All Categories</label>
                        <select name="subcategory" id="sort-by" onchange="this.form.submit()"
                            class="block w-full border-0 appearance-none cursor-pointer bg-inherit focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option value="all" {{ request('subcategory') == 'all' ? 'selected' : '' }}>All
                                Categories</option>
                            @foreach ($category->subcategories as $subcategory)
                            <option value="{{ $subcategory->slug }}"
                                {{ request('subcategory') == $subcategory->slug ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="hidden">Search</button>
                    </form>

                    <!-- Relevance -->
                    <form
                        class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-3 pl-3 sm:!pr-3 !pr-2 py-1.2 sm:py-1 inline-flex text-jet-gray">
                        <label for="sort-by" class="block whitespace-nowrap">Sort By:</label>
                        <select id="sort-by"
                            class="block w-full bg-transparent border-0 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>Relevance</option>
                            <option value="best-selling">Best Selling</option>
                            <option value="trending">Trending</option>
                            <option value="popularity">Popularity</option>
                            <option value="new-arrivals">New Arrivals</option>
                        </select>
                    </form>

                    <!-- Material -->
                    @foreach ($productOptions as $productOption)
                    <form method="GET" action="{{ route('category.details', $category->slug) }}"
                        class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-3 pl-3 sm:!pr-3 !pr-2 py-1.2 sm:py-1 inline-flex text-jet-gray">
                        <label for="attribute-{{ $productOption->name }}"
                            class="block sr-only whitespace-nowrap">{{ $productOption->name }}</label>
                        <select name="{{ strtolower($productOption->name) }}"
                            id="attribute-{{ $productOption->name }}" onchange="this.form.submit()"
                            class="block w-full bg-transparent border-0 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option value="all"
                                {{ request(strtolower($productOption->name)) == 'all' ? 'selected' : '' }}>
                                {{ $productOption->name }}
                            </option>
                            @foreach ($productOption->options as $option)
                            <option value="{{ $option->value }}"
                                {{ request(strtolower($productOption->name)) == $option->value ? 'selected' : '' }}>
                                {{ strtoupper($option->value) }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                    @endforeach

                    <!-- Review -->
                    <form
                        class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-3 pl-3 sm:!pr-3 !pr-2 py-1.2 sm:py-1 inline-flex text-jet-gray">
                        <label for="sort-by" class="block sr-only whitespace-nowrap">Review</label>
                        <select id="sort-by"
                            class="block w-full bg-transparent border-0 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>Review</option>
                            <option value="highest-rated">Highest Rated</option>
                            <option value="most-reviewed">Most Reviewed</option>
                            <option value="top-feedback">Top Feedback</option>
                            <option value="verified-reviews">Verified Reviews</option>
                            <option value="plant">Plant</option>
                            <option value="alant">Animal</option>
                        </select>
                    </form>

                    <!-- Recommended -->
                    <form
                        class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-3 pl-3 sm:!pr-3 !pr-2 py-1.2 sm:py-1 inline-flex text-jet-gray">
                        <label for="sort-by" class="block sr-only whitespace-nowrap">Recommended</label>
                        <select id="sort-by"
                            class="block w-full bg-transparent border-0 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>Recommended</option>
                            <option value="best-sellers">Best Sellers</option>
                            <option value="editor-pick">Editor's Pick</option>
                            <option value="customers-choice">Customers' Choice</option>
                            <option value="staff-recommended">Staff Recommended</option>
                        </select>
                    </form>
                </div>

                <!-- All Filters Trigure Btn -->
                <div class="w-2/12 lg:w-3/12 xl:w-auto">
                    <button data-drawer-target="all-filters-drawer" data-drawer-show="all-filters-drawer"
                        aria-controls="all-filters-drawer"
                        class="flex items-center justify-center w-20 h-10 gap-1 ml-auto text-sm text-white rounded-full md:w-auto md:rounded-3xl bg-primary hover:bg-theme-dark eq md:px-5 md:py-3">
                        <span class="hidden md:inline-block whitespace-nowrap">All Filters</span>
                        <i class="fa-solid fa-filter text-xs md:text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="container products-section">
            <!-- Page Header -->
            <div class="mb-8 md:mb-12">
                <div class="flex flex-col items-start justify-between gap-4 mb-6 md:flex-row md:items-center md:gap-6">
                    <h1 class="text-2xl font-semibold text-gray-900 md:text-3xl">
                        {{ strtoupper($category->name) }}
                    </h1>

                    <!-- Mobile Filters Button -->
                    <button data-drawer-target="all-filters-drawer" data-drawer-show="all-filters-drawer"
                        aria-controls="all-filters-drawer"
                        class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg md:hidden bg-primary hover:bg-theme-dark transition-colors">
                        <span>All Filters</span>
                        <i class="fa-solid fa-filter text-xs"></i>
                    </button>
                </div>

                <!-- Filters Section -->
                <div class="flex flex-col gap-4">
                    <!-- Main Filters Row -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- All Categories -->
                        <form method="GET" action="{{ route('category.details', $category->slug) }}"
                            class="filter-dropdown group">
                            <label for="sort-by" class="sr-only">All Categories</label>
                            <div class="filter-dropdown-inner">
                                <select name="subcategory" id="sort-by" onchange="this.form.submit()"
                                    class="filter-select">
                                    <option value="all" {{ request('subcategory') == 'all' ? 'selected' : '' }}>All Categories</option>
                                    @foreach ($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->slug }}"
                                        {{ request('subcategory') == $subcategory->slug ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="hidden">Search</button>
                        </form>

                        <!-- Sort By -->
                        <form class="filter-dropdown group">
                            <label for="sort-by" class="sr-only">Sort By</label>
                            <div class="filter-dropdown-inner">
                                <select id="sort-by" class="filter-select">
                                    <option selected>Sort By: Relevance</option>
                                    <option value="best-selling">Best Selling</option>
                                    <option value="trending">Trending</option>
                                    <option value="popularity">Popularity</option>
                                    <option value="new-arrivals">New Arrivals</option>
                                </select>
                            </div>
                        </form>

                        <!-- Dynamic Product Options -->
                        @foreach ($productOptions as $productOption)
                        <form method="GET" action="{{ route('category.details', $category->slug) }}"
                            class="filter-dropdown group">
                            <label for="attribute-{{ $productOption->name }}" class="sr-only">{{ $productOption->name }}</label>
                            <div class="filter-dropdown-inner">
                                <select name="{{ strtolower($productOption->name) }}"
                                    id="attribute-{{ $productOption->name }}" onchange="this.form.submit()"
                                    class="filter-select">
                                    <option value="all"
                                        {{ request(strtolower($productOption->name)) == 'all' ? 'selected' : '' }}>
                                        {{ $productOption->name }}
                                    </option>
                                    @foreach ($productOption->options as $option)
                                    <option value="{{ $option->value }}"
                                        {{ request(strtolower($productOption->name)) == $option->value ? 'selected' : '' }}>
                                        {{ strtoupper($option->value) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        @endforeach
                    </div>

                    <!-- Secondary Filters Row -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Review -->
                        <form class="filter-dropdown group">
                            <label for="review-filter" class="sr-only">Review</label>
                            <div class="filter-dropdown-inner">
                                <select id="review-filter" class="filter-select">
                                    <option selected>Review</option>
                                    <option value="highest-rated">Highest Rated</option>
                                    <option value="most-reviewed">Most Reviewed</option>
                                    <option value="top-feedback">Top Feedback</option>
                                    <option value="verified-reviews">Verified Reviews</option>
                                </select>
                            </div>
                        </form>

                        <!-- Recommended -->
                        <form class="filter-dropdown group">
                            <label for="recommended-filter" class="sr-only">Recommended</label>
                            <div class="filter-dropdown-inner">
                                <select id="recommended-filter" class="filter-select">
                                    <option selected>Recommended</option>
                                    <option value="best-sellers">Best Sellers</option>
                                    <option value="editor-pick">Editor's Pick</option>
                                    <option value="customers-choice">Customers' Choice</option>
                                    <option value="staff-recommended">Staff Recommended</option>
                                </select>
                            </div>
                        </form>

                        <!-- Desktop All Filters Button -->
                        <button data-drawer-target="all-filters-drawer" data-drawer-show="all-filters-drawer"
                            aria-controls="all-filters-drawer"
                            class="hidden items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg md:flex bg-primary hover:bg-theme-dark">
                            <span>All Filters</span>
                            <i class="fa-solid fa-filter text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div id="product-list"
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:gap-8">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>

            @if ($products->count() >= 8)
            <!-- Load More Button -->
            <div class="mt-12 text-center load-more-btn">
                <button data-page="1" data-url="{{ route('category.details', $category->slug) }}" id="loadMoreBtn"
                    class="inline-flex items-center gap-2 px-6 py-3 text-base font-medium text-white transition-colors rounded-lg bg-theme-teal hover:bg-aqua-deep"
                    type="button">
                    <span>Load More</span>
                    <i class="text-sm fa-solid fa-chevron-down"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- Product Card's Wrapper -->
        <div id="product-list"
            class="grid items-start grid-cols-1 gap-5 p-2 xsm:grid-cols-2 md:grid-cols-4 xl:gap-8 lg:p-0">
            @include('frontend.partials.product-card-load', ['products' => $products])
        </div>

        @if ($products->count() >= 8)
        <!-- Load More Btn -->
        <div class="mt-10 text-center load-more-btn">
            <button data-page="1" data-url="{{ route('category.details', $category->slug) }}" id="loadMoreBtn"
                class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                type="button">
                <span>Load More</span>
                <i class="text-sm fa-solid fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </section>
    <!-- Page Main Content Ended -->
</main>

@push('scripts')
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

                    // ✅ Parse and register new quick view data from JSON script tags
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
                            console.error('Invalid QuickView JSON for product modal', e);
                        }
                    });

                    // ✅ Re-initialize QuickView modal JS for new elements
                    if (typeof initQuickViewModals === 'function') {
                        initQuickViewModals();
                    }

                    // (Optional) Re-init other components (e.g., tooltips, sliders)
                    if (typeof initFlowbite === 'function') {
                        initFlowbite();
                    }
                } else {
                    button.hide(); // No more products
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