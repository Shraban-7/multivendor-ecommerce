@extends('frontend.layouts.app')
@section('title', $category->name)

@section('content')
<div class="container mx-auto px-4 mt-2 mb-8">
    <!-- promo banner -->
    <div class="page-promotion md:w-full mb-6">
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
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Filters Section (Desktop) -->
        <aside class="hidden md:block md:col-span-1">
            <div class="bg-white shadow rounded-lg p-5 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 border-b pb-3">Filters</h2>

                <!-- Category Filter -->
                <form method="GET" action="{{ route('category.details', $category->slug) }}" class="space-y-3">
                    <label for="subcategory" class="block text-sm font-medium text-gray-700">Subcategories</label>
                    <select name="subcategory" id="subcategory" onchange="this.form.submit()"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option value="all" {{ request('subcategory') == 'all' ? 'selected' : '' }}>All Categories</option>
                        @foreach ($category->subcategories as $subcategory)
                        <option value="{{ $subcategory->slug }}"
                            {{ request('subcategory') == $subcategory->slug ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                        @endforeach
                    </select>
                </form>

                <!-- Dynamic Product Options -->
                @foreach ($productOptions as $productOption)
                <form method="GET" action="{{ route('category.details', $category->slug) }}" class="space-y-3">
                    <label for="attribute-{{ $productOption->name }}"
                        class="block text-sm font-medium text-gray-700">{{ $productOption->name }}</label>
                    <select name="{{ strtolower($productOption->name) }}"
                        id="attribute-{{ $productOption->name }}" onchange="this.form.submit()"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option value="all"
                            {{ request(strtolower($productOption->name)) == 'all' ? 'selected' : '' }}>
                            All {{ $productOption->name }}
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

                <!-- Review Filter -->
                <form class="space-y-3">
                    <label for="review-filter" class="block text-sm font-medium text-gray-700">Review</label>
                    <select id="review-filter"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option selected>All Reviews</option>
                        <option value="highest-rated">Highest Rated</option>
                        <option value="most-reviewed">Most Reviewed</option>
                        <option value="verified-reviews">Verified Reviews</option>
                    </select>
                </form>

                <!-- Recommended Filter -->
                <form class="space-y-3">
                    <label for="recommended-filter" class="block text-sm font-medium text-gray-700">Recommended</label>
                    <select id="recommended-filter"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option selected>All</option>
                        <option value="best-sellers">Best Sellers</option>
                        <option value="editor-pick">Editor's Pick</option>
                        <option value="customers-choice">Customers' Choice</option>
                    </select>
                </form>
            </div>
        </aside>

        <!-- Products Section -->
        <section class="md:col-span-3">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <!-- Category Title -->
                <h2 class="text-2xl font-semibold text-gray-900">
                    {{ strtoupper($category->name) }}
                </h2>

                <!-- Mobile Filter Button -->
                <button data-drawer-target="filters-drawer" data-drawer-show="filters-drawer"
                    aria-controls="filters-drawer"
                    class="md:hidden flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-theme-dark transition">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filters</span>
                </button>

                <!-- Sort Dropdown -->
                <form class="w-full md:w-auto">
                    <select id="sort-by"
                        class="w-full md:w-auto border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option selected>Sort By: Relevance</option>
                        <option value="best-selling">Best Selling</option>
                        <option value="trending">Trending</option>
                        <option value="popularity">Popularity</option>
                        <option value="new-arrivals">New Arrivals</option>
                    </select>
                </form>
            </div>

            <!-- Products Grid -->
            <div id="product-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>

            @if ($products->count() >= 16)
            <div class="mt-10 text-center">
                <button data-page="1"
                    data-url="{{ route('category.details', $category->slug) }}"
                    id="loadMoreBtn"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm md:text-base font-medium text-white bg-primary rounded-lg shadow hover:bg-theme-dark transition">
                    <span>Load More</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
            </div>
            @endif
        </section>
    </div>
</div>

<!-- Offcanvas Filters (Mobile) -->
<div id="filters-drawer"
    class="fixed top-0 left-0 z-50 w-80 h-screen p-6 overflow-y-auto transition-transform -translate-x-full bg-white shadow-lg"
    tabindex="-1" aria-labelledby="filters-drawer-label">
    <div class="flex items-center justify-between mb-4">
        <h5 id="filters-drawer-label" class="text-lg font-semibold text-gray-900">Filters</h5>
        <button type="button" data-drawer-hide="filters-drawer" aria-controls="filters-drawer"
            class="text-gray-500 hover:text-gray-900">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <!-- Copy Filters Content Here -->
    <div class="space-y-6">
        <!-- Subcategories -->
        <form method="GET" action="{{ route('category.details', $category->slug) }}" class="space-y-3">
            <label for="subcategory-mobile" class="block text-sm font-medium text-gray-700">Subcategories</label>
            <select name="subcategory" id="subcategory-mobile" onchange="this.form.submit()"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                <option value="all" {{ request('subcategory') == 'all' ? 'selected' : '' }}>All Categories</option>
                @foreach ($category->subcategories as $subcategory)
                <option value="{{ $subcategory->slug }}"
                    {{ request('subcategory') == $subcategory->slug ? 'selected' : '' }}>
                    {{ $subcategory->name }}
                </option>
                @endforeach
            </select>
        </form>

        <!-- Dynamic Product Options -->
        @foreach ($productOptions as $productOption)
        <form method="GET" action="{{ route('category.details', $category->slug) }}" class="space-y-3">
            <label for="attribute-mobile-{{ $productOption->name }}"
                class="block text-sm font-medium text-gray-700">{{ $productOption->name }}</label>
            <select name="{{ strtolower($productOption->name) }}"
                id="attribute-mobile-{{ $productOption->name }}" onchange="this.form.submit()"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                <option value="all"
                    {{ request(strtolower($productOption->name)) == 'all' ? 'selected' : '' }}>
                    All {{ $productOption->name }}
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

        <!-- Review Filter -->
        <form class="space-y-3">
            <label for="review-filter-mobile" class="block text-sm font-medium text-gray-700">Review</label>
            <select id="review-filter-mobile"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                <option selected>All Reviews</option>
                <option value="highest-rated">Highest Rated</option>
                <option value="most-reviewed">Most Reviewed</option>
                <option value="verified-reviews">Verified Reviews</option>
            </select>
        </form>

        <!-- Recommended Filter -->
        <form class="space-y-3">
            <label for="recommended-filter-mobile" class="block text-sm font-medium text-gray-700">Recommended</label>
            <select id="recommended-filter-mobile"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                <option selected>All</option>
                <option value="best-sellers">Best Sellers</option>
                <option value="editor-pick">Editor's Pick</option>
                <option value="customers-choice">Customers' Choice</option>
            </select>
        </form>
    </div>
</div>

@endsection

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