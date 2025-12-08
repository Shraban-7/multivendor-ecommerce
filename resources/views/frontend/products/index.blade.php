@extends('frontend.layouts.app')
@section('title', 'Products')

@push('header')
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }

        .hidden-custom {
            display: none !important;
        }

        /* Custom Range Slider Styling */
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 16px;
            width: 16px;
            border-radius: 50%;
            background: #ea580c;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            margin-top: -6px;
        }

        input[type=range]::-webkit-slider-runnable-track {
            width: 100%;
            height: 4px;
            cursor: pointer;
            background: #e5e7eb;
            border-radius: 2px;
        }

        /* Grid/List Toggle Classes */
        .list-view-item {
            flex-direction: row;
            height: 14rem;
            /* h-56 */
            align-items: stretch;
        }

        .list-view-image {
            width: 33.333333%;
            /* w-1/3 */
            flex-shrink: 0;
            border-right: 1px solid #f9fafb;
            /* border-gray-50 */
            border-bottom: 0px;
        }

        .category-filter.active {
            /* soft orange tint (orange-50) */
            color: #ea580c;
            /* orange-600 */
        }

        /* hover effect on the main text */
        .category-filter:hover span:first-child {
            color: #ea580c;
        }

        /* hover effect on badge */
        .category-filter:hover .count-badge {
            color: #ea580c;
        }



        .category-filter.active span {
            color: #ea580c;
            /* orange-600 */
        }

        .category-filter.active .count-badge {
            /* background-color: #ffedd5; */
            /* orange-100 */
            color: #ea580c;
            /* orange-600 */
        }

        @media (min-width: 640px) {
            .list-view-image {
                width: 14rem;
            }
        }
    </style>
@endpush

@section('breadcrumbs')
    <div class="bg-white border-b border-gray-200 py-3">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-primary-600 transition">
                            <i class="fas fa-home mr-1"></i> Home
                        </a>
                    </li>
                    <li><i class="fas fa-chevron-right text-[10px] text-gray-400"></i></li>
                    <li class="font-medium text-gray-900" aria-current="page">Products</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <section class="container mx-auto pb-20 lg:pb-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- ==================== SIDEBAR FILTERS ==================== -->
            <aside id="sidebar" class="hidden lg:block lg:w-64 shrink-0 transition-all duration-300 z-30">

                <!-- Overlay for Mobile -->
                <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 lg:hidden hidden-custom"></div>

                <!-- Sidebar Content -->
                <div id="sidebarContent"
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sticky top-24 h-full lg:h-auto overflow-y-auto">

                    <!-- Mobile Header -->
                    <div class="flex items-center justify-between mb-6 lg:hidden">
                        <h2 class="text-xl font-bold text-gray-900">Filters</h2>
                        <button id="closeMobileFilter" class="text-gray-400 hover:text-red-500"><i
                                class="fas fa-times text-xl"></i></button>
                    </div>

                    <!-- Categories -->
                    <div class="mb-6 border-b border-gray-100 pb-5">
                        <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wider">Categories</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            @foreach ($categories as $category)
                                <li>
                                    @if (in_array($category->slug, $selectedCategories))
                                        <a href="#"
                                            class="category-filter hover:category-filter-hover flex justify-between items-center group font-medium text-primary-600">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-xs bg-primary-100 text-primary-600 px-2 py-0.5 rounded-full">
                                                {{ $category->products_count }}</span>
                                        </a>
                                    @else
                                        <a href="#" data-slug="{{ $category->slug }}"
                                            class="category-filter hover:category-filter-hover flex justify-between items-center group">
                                            <span>{{ $category->name }}</span>
                                            <span
                                                class="text-xs bg-gray-100 px-2 py-0.5 rounded-full group-hover:text-orange-600">{{ $category->products_count }}</span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6 border-b border-gray-100 pb-5">
                        <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Price Range</h3>

                        <div class="px-2">

                            <!-- RANGE SLIDER -->
                            <input id="priceRange" type="range" min="0" max="50000"
                                value="{{ request('price_max', 50000) }}"
                                class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer mb-4">

                            <div class="flex items-center justify-between gap-2">
                                <!-- MIN -->
                                <div class="border border-gray-200 rounded px-3 py-1 bg-gray-50 w-24">
                                    <span class="text-xs text-gray-500 block">Min</span>
                                    <input id="priceMin" type="number" value="{{ request('price_min', 0) }}"
                                        class="w-full bg-transparent text-sm font-bold text-gray-800 outline-none p-0 border-none">
                                </div>

                                <span class="text-gray-400">-</span>

                                <!-- MAX -->
                                <div class="border border-gray-200 rounded px-3 py-1 bg-gray-50 w-24">
                                    <span class="text-xs text-gray-500 block">Max</span>
                                    <input id="priceMax" type="number" value="{{ request('price_max', 50000) }}"
                                        class="w-full bg-transparent text-sm font-bold text-gray-800 outline-none p-0 border-none">
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Brands -->
                    <div class="mb-6 border-b border-gray-100 pb-5">
                        <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wider">Brands</h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2">
                            @foreach ($brands as $brand)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" value="{{ $brand->slug }}" class="brand-filter w-4 h-4"
                                        @if (in_array($brand->slug, $selectedBrands)) checked @endif>
                                    <span class="text-sm text-gray-600">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Colors -->
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wider">Color</h3>
                        <div class="flex flex-wrap gap-2">
                            <button
                                class="w-6 h-6 rounded-full bg-black ring-2 ring-offset-2 ring-gray-300 hover:ring-primary-500 transition"></button>
                            <button
                                class="w-6 h-6 rounded-full bg-blue-600 hover:ring-2 hover:ring-offset-2 hover:ring-primary-500 transition"></button>
                            <button
                                class="w-6 h-6 rounded-full bg-red-500 hover:ring-2 hover:ring-offset-2 hover:ring-primary-500 transition"></button>
                            <button
                                class="w-6 h-6 rounded-full bg-gray-200 hover:ring-2 hover:ring-offset-2 hover:ring-primary-500 transition"></button>
                            <button
                                class="w-6 h-6 rounded-full bg-white border border-gray-300 hover:border-gray-400 hover:ring-2 hover:ring-offset-2 hover:ring-primary-500 transition"></button>
                        </div>
                    </div>

                    <!-- Mobile Apply Button -->
                    <button id="applyFiltersBtn"
                        class="w-full bg-primary-600 text-white py-3 rounded-lg font-bold lg:hidden mt-4 hover:bg-primary-700">Apply
                        Filters</button>
                </div>
            </aside>

            <!-- ==================== PRODUCT GRID ==================== -->
            <main class="flex-1">

                <!-- Toolbar -->
                <div
                    class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p></p>
                    {{--<p class="text-sm text-gray-500">
                        Showing
                        <span class="font-bold text-gray-900">{{ $products->firstItem() }}</span>
                        -
                        <span class="font-bold text-gray-900">{{ $products->lastItem() }}</span>
                        of
                        <span class="font-bold text-gray-900">{{ $products->total() }}</span>
                        results
                    </p>--}}


                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <!-- Sort Select -->
                        <div class="relative flex-1 sm:flex-none group">
                            <select name="sort"
                                class="sort-filter w-full sm:w-48 appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2.5 pr-8 cursor-pointer hover:border-primary-300 transition">

                                <option value="" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Sort by
                                    Popularity</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals
                                </option>
                                <option value="low_high" {{ request('sort') == 'low_high' ? 'selected' : '' }}>Price: Low
                                    to
                                    High</option>
                                <option value="high_low" {{ request('sort') == 'high_low' ? 'selected' : '' }}>Price: High
                                    to Low</option>
                            </select>

                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>


                        <!-- View Toggles -->
                        <div class="flex bg-gray-100 p-1 rounded-lg shrink-0">
                            <button id="gridViewBtn"
                                class="w-8 h-8 rounded flex items-center justify-center transition bg-white text-primary-600 shadow-sm"><i
                                    class="fas fa-th-large"></i></button>
                            <button id="listViewBtn"
                                class="w-8 h-8 rounded flex items-center justify-center transition text-gray-400 hover:text-gray-600"><i
                                    class="fas fa-list"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Tags -->
                @if (!empty($selectedCategories))
                    <div class="flex flex-wrap gap-2 mb-6" id="active-filters">

                        @foreach ($selectedCategories as $slug)
                            @php
                                $category = $categories->firstWhere('slug', $slug);
                            @endphp
                            @if ($category)
                                <span
                                    class="bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2 transition">
                                    {{ $category->name }}
                                    <button class="remove-filter text-gray-400 hover:text-red-500" data-type="category"
                                        data-slug="{{ $slug }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                        @endforeach
                @endif

                @if (!empty($selectedBrands))
                    @foreach ($selectedBrands as $slug)
                        @php
                            $brand = $brands->firstWhere('slug', $slug);
                        @endphp
                        @if ($brand)
                            <span
                                class="bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2 transition">
                                {{ $brand->name }}
                                <button class="remove-filter text-gray-400 hover:text-red-500" data-type="brand"
                                    data-slug="{{ $slug }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                    @endforeach
                @endif

                @if (!empty($selectedPrice ?? null))
                    <span
                        class="bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2 transition">
                        Price: ৳{{ $selectedPrice['min'] ?? '0' }}-{{ $selectedPrice['max'] ?? '∞' }}
                        <button class="remove-filter text-gray-400 hover:text-red-500" data-type="price">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif

                @if (!empty($selectedCategories) || !empty($selectedBrands) || !empty($selectedPrice ?? null))
                    <button id="clear-all-filters"
                        class="text-xs text-red-500 hover:text-red-700 hover:underline font-medium ml-2 transition">
                        Clear All
                    </button>
        </div>
        @endif

        <div id="productsContainer" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 lg:gap-4">

            @foreach ($products as $product)
                <div
                    class="product-card bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col h-full relative">
                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                        <span
                            class="bg-primary-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">SALE</span>
                    </div>
                    <div
                        class="product-image-container h-48 w-full border-b border-gray-50 relative bg-white p-4 flex items-center justify-center overflow-hidden">
                        <img src="{{ storage_url($product->thumbnail) }}"
                            class="max-h-full max-w-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">
                        <!-- Hover Actions (Grid) -->
                        <div
                            class="grid-hover-actions absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2 backdrop-blur-[1px]">
                            <button data-slug="{{ $product->slug }}"
                                class="btn-quickview w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-75"><i
                                    class="far fa-eye"></i></button>
                            <button
                                class="w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-red-500 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-100"><i
                                    class="far fa-heart"></i></button>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 flex flex-col flex-1">
                        <span
                            class="text-[10px] text-gray-400 uppercase tracking-wide mb-1 font-medium">{{ $product->category->name }}</span>
                        <h3
                            class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 hover:text-primary-600 transition cursor-pointer">
                            {{ $product->name }}
                        </h3>

                        <div class="flex items-center gap-1 mb-2">
                            <div class="flex text-yellow-400 text-[10px] sm:text-xs">
                                @php
                                    $avg = $product->avg_rating ?? 0;
                                    $fullStars = floor($avg);
                                    $halfStar = $avg - $fullStars >= 0.5 ? 1 : 0;
                                    $emptyStars = 5 - ($fullStars + $halfStar);
                                @endphp

                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor

                                @if ($halfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star"></i>
                                @endfor

                            </div>

                            <span class="text-[10px] text-gray-400">({{ $product->rating_count }})</span>
                        </div>
                        <!-- List View Desc -->
                        <p class="list-view-desc hidden text-xs text-gray-500 mb-4 line-clamp-2 leading-relaxed">

                        </p>
                        <div class="mt-auto pt-2 flex items-end justify-between">
                            <div class="flex flex-col">
                                @if ($product->discounted_price)
                                    <span
                                        class="text-[10px] sm:text-xs text-gray-400 line-through">{{ money($product->selling_price) }}</span>
                                    <span
                                        class="text-primary-600 font-bold text-base sm:text-lg">{{ money($product->discounted_price) }}</span>
                                @else
                                    <span
                                        class="text-primary-600 font-bold text-base sm:text-lg">{{ money($product->selling_price) }}</span>
                                @endif
                            </div>
                            <!-- Grid Button -->
                            <button
                                class="grid-view-btn w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center hover:bg-primary-600 hover:text-white transition shadow-sm">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                            <!-- List Buttons -->
                            <div class="list-view-btns hidden flex gap-2">
                                <button
                                    class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center hover:border-red-300 hover:bg-red-50 hover:text-red-500 transition"><i
                                        class="far fa-heart"></i></button>
                                <button
                                    class="px-4 py-2 bg-primary-600 text-white text-xs font-bold rounded-lg hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition flex items-center gap-2">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="mt-5 flex flex-col items-center">

                <div class="flex justify-center gap-2">
                    <a href="{{ $products->previousPageUrl() }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 
                                    hover:border-primary-500 hover:text-primary-600 transition
                                    {{ $products->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    @foreach ($products->links()->elements[0] ?? [] as $page => $url)
                        @if ($page == $products->currentPage())
                            <span
                                class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary-600 text-white font-semibold shadow-lg shadow-primary-500/30">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 
                                            hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition font-medium">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                    <a href="{{ $products->nextPageUrl() }}"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 
                                    hover:border-primary-500 hover:text-primary-600 transition
                                    {{ !$products->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        @endif
        </main>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // --- Mobile Filter Sidebar ---
            const openFilterBtn = document.getElementById('openMobileFilter');
            const closeFilterBtn = document.getElementById('closeMobileFilter');
            const applyFiltersBtn = document.getElementById('applyFiltersBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarContent = document.getElementById('sidebarContent');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function toggleFilter(show) {
                if (show) {
                    sidebar.classList.remove('hidden');
                    sidebar.classList.add('fixed', 'inset-0', 'flex', 'z-50'); // Add z-50 here for mobile
                    sidebarOverlay.classList.remove('hidden-custom');

                    sidebarContent.classList.remove('sticky', 'top-24');
                    sidebarContent.classList.add('relative', 'w-80', 'max-w-[80%]', 'h-full', 'bg-white',
                        'shadow-2xl', 'p-6');
                } else {
                    sidebar.classList.add('hidden');
                    sidebar.classList.remove('fixed', 'inset-0', 'flex', 'z-50'); // Remove z-50
                    sidebarOverlay.classList.add('hidden-custom');

                    sidebarContent.classList.add('sticky', 'top-24');
                    sidebarContent.classList.remove('relative', 'w-80', 'max-w-[80%]', 'h-full', 'bg-white',
                        'shadow-2xl', 'p-6');
                }
            }

            // Only attach events if elements exist (in mobile view logic primarily)
            if (openFilterBtn) openFilterBtn.addEventListener('click', () => toggleFilter(true));
            if (closeFilterBtn) closeFilterBtn.addEventListener('click', () => toggleFilter(false));
            if (applyFiltersBtn) applyFiltersBtn.addEventListener('click', () => toggleFilter(false));
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', () => toggleFilter(false));


            // --- Grid / List View Toggle ---
            const gridViewBtn = document.getElementById('gridViewBtn');
            const listViewBtn = document.getElementById('listViewBtn');
            const productsContainer = document.getElementById('productsContainer');
            const productCards = document.querySelectorAll('.product-card');

            if (gridViewBtn && listViewBtn && productsContainer) {
                gridViewBtn.addEventListener('click', () => {
                    // Update buttons
                    gridViewBtn.classList.add('bg-white', 'text-primary-600', 'shadow-sm');
                    gridViewBtn.classList.remove('text-gray-400', 'hover:text-gray-600');
                    listViewBtn.classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
                    listViewBtn.classList.add('text-gray-400', 'hover:text-gray-600');

                    // Container layout
                    productsContainer.className =
                        "grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4";

                    // Card Styling
                    productCards.forEach(card => {
                        card.className =
                            "product-card bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col h-full relative";
                        // Image container
                        const imgCont = card.querySelector('.product-image-container');
                        imgCont.className =
                            "product-image-container h-48 w-full border-b border-gray-50 relative bg-white p-4 flex items-center justify-center overflow-hidden";
                        // Actions hover
                        const hoverActions = card.querySelector('.grid-hover-actions');
                        if (hoverActions) hoverActions.classList.remove('hidden-custom');
                        // Desc toggle
                        const desc = card.querySelector('.list-view-desc');
                        if (desc) desc.classList.add('hidden');
                        // Btns toggle
                        const gridBtn = card.querySelector('.grid-view-btn');
                        if (gridBtn) gridBtn.classList.remove('hidden');
                        const listBtns = card.querySelector('.list-view-btns');
                        if (listBtns) listBtns.classList.add('hidden');
                    });
                });

                listViewBtn.addEventListener('click', () => {
                    // Update buttons
                    listViewBtn.classList.add('bg-white', 'text-primary-600', 'shadow-sm');
                    listViewBtn.classList.remove('text-gray-400', 'hover:text-gray-600');
                    gridViewBtn.classList.remove('bg-white', 'text-primary-600', 'shadow-sm');
                    gridViewBtn.classList.add('text-gray-400', 'hover:text-gray-600');

                    // Container layout
                    productsContainer.className = "flex flex-col gap-4";

                    // Card Styling
                    productCards.forEach(card => {
                        card.className =
                            "product-card bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group overflow-hidden flex list-view-item relative";
                        // Image container
                        const imgCont = card.querySelector('.product-image-container');
                        imgCont.className =
                            "product-image-container list-view-image relative bg-white p-4 flex items-center justify-center overflow-hidden";
                        // Actions hover (hide in list view)
                        const hoverActions = card.querySelector('.grid-hover-actions');
                        if (hoverActions) hoverActions.classList.add('hidden-custom');
                        // Desc toggle
                        const desc = card.querySelector('.list-view-desc');
                        if (desc) desc.classList.remove('hidden', 'sm:block'); // Ensure it shows
                        if (desc) desc.classList.add('block');
                        // Btns toggle
                        const gridBtn = card.querySelector('.grid-view-btn');
                        if (gridBtn) gridBtn.classList.add('hidden');
                        const listBtns = card.querySelector('.list-view-btns');
                        if (listBtns) listBtns.classList.remove('hidden');
                    });
                });
            }


            // --- Quick View Modal (Duplicate logic from index but necessary for standalone file) ---
            const quickViewModal = document.getElementById('quickViewModal');
            const openQuickViewBtns = document.querySelectorAll('.open-quickview');
            const closeQuickViewBtns = document.querySelectorAll('.close-quickview');
            const quickViewContent = document.getElementById('quickViewContent');

            function toggleQuickView(show) {
                if (show) {
                    quickViewModal.classList.remove('hidden-custom');
                    setTimeout(() => quickViewModal.style.opacity = '1', 10);
                } else {
                    quickViewModal.style.opacity = '0';
                    setTimeout(() => quickViewModal.classList.add('hidden-custom'), 300);
                }
            }

            openQuickViewBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleQuickView(true);
                });
            });

            closeQuickViewBtns.forEach(btn => {
                btn.addEventListener('click', () => toggleQuickView(false));
            });

            if (quickViewModal) {
                quickViewModal.addEventListener('click', (e) => {
                    if (!quickViewContent.contains(e.target)) {
                        toggleQuickView(false);
                    }
                });
            }

            // --- Back to Top Button ---
            const backToTopBtn = document.getElementById('backToTop');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 100) {
                    backToTopBtn.classList.remove('hidden-custom');
                    setTimeout(() => {
                        backToTopBtn.classList.remove('opacity-0', 'translate-y-10');
                    }, 10);
                } else {
                    backToTopBtn.classList.add('opacity-0', 'translate-y-10');
                    setTimeout(() => backToTopBtn.classList.add('hidden-custom'), 300);
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

        });

        function updateUrl() {
            let params = new URLSearchParams();

            // Category
            let selectedCategories = [];
            document.querySelectorAll('.category-filter.active').forEach(el => {
                selectedCategories.push(el.dataset.slug);
            });
            if (selectedCategories.length) {
                params.append("category", selectedCategories.join(','));
            }

            // Brand
            let selectedBrands = [];
            document.querySelectorAll('.brand-filter:checked').forEach(el => {
                selectedBrands.push(el.value);
            });
            if (selectedBrands.length) {
                params.append("brand", selectedBrands.join(','));
            }

            // Sort
            let sortValue = document.querySelector('.sort-filter')?.value;
            if (sortValue) {
                params.append("sort", sortValue);
            }

            // Price
            let min = document.getElementById("priceMin").value;
            let max = document.getElementById("priceMax").value;

            if (min) params.append("price_min", min);
            if (max) params.append("price_max", max);

            let query = params.toString();
            window.location.href = query ? "/products?" + query : "/products";
        }

        /* ---------- PRICE HANDLERS ---------- */
        const priceMin = document.getElementById("priceMin");
        const priceMax = document.getElementById("priceMax");
        const priceRange = document.getElementById("priceRange");

        // Slider updates max input
        priceRange.addEventListener("input", function() {
            priceMax.value = this.value;
        });

        // Apply filter when price inputs change
        [priceMin, priceMax, priceRange].forEach(el => {
            el.addEventListener("change", updateUrl);
        });


        // Category click — only one active at a time
        document.querySelectorAll('.category-filter').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active from all categories
                document.querySelectorAll('.category-filter').forEach(cat => cat.classList.remove(
                    'active'));

                // Add active to clicked category
                this.classList.add('active');

                updateUrl();
            });
        });

        // Brand checkbox change (multi-select allowed)
        document.querySelectorAll('.brand-filter').forEach(el => {
            el.addEventListener('change', function() {
                updateUrl();
            });
        });

        document.querySelector('.sort-filter').addEventListener('change', function() {
            updateUrl();
        });


        // Remove single filter
        document.querySelectorAll('.remove-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                const slug = this.dataset.slug;

                const url = new URL(window.location.href);
                const params = url.searchParams;

                if (type === 'category') {
                    params.delete('category'); // only one category allowed
                } else if (type === 'brand') {
                    let brands = params.get('brand')?.split(',') || [];
                    brands = brands.filter(b => b !== slug);
                    if (brands.length > 0) {
                        params.set('brand', brands.join(','));
                    } else {
                        params.delete('brand');
                    }
                } else if (type === 'price') {
                    params.delete('price');
                }

                window.location.href = url.pathname + '?' + params.toString();
            });
        });

        // Clear All
        document.getElementById('clear-all-filters')?.addEventListener('click', function() {
            window.location.href = '/products';
        });
    </script>
@endpush
