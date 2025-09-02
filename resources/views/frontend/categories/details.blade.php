@extends('frontend.layouts.app')
@section('title', $category->name)

@section('content')

<div class="container mx-auto px-4 py-8">
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
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">
                    {{ strtoupper($category->name) }}
                </h2>

                <!-- Mobile Filter Button -->
                <button data-drawer-target="filters-drawer" data-drawer-show="filters-drawer"
                    aria-controls="filters-drawer"
                    class="md:hidden flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-theme-dark transition">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filters</span>
                </button>

                <!-- Sort Dropdown -->
                <form>
                    <select id="sort-by"
                        class="border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option selected>Sort By: Relevance</option>
                        <option value="best-selling">Best Selling</option>
                        <option value="trending">Trending</option>
                        <option value="popularity">Popularity</option>
                        <option value="new-arrivals">New Arrivals</option>
                    </select>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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