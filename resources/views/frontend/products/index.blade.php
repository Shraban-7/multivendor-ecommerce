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
                <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 lg:hidden hidden"></div>

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

            <main class="flex-1" id="products-container">
                @include('components.frontend.products-page')
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
                    sidebarOverlay.classList.remove('hidden');

                    sidebarContent.classList.remove('sticky', 'top-24');
                    sidebarContent.classList.add('relative', 'w-80', 'max-w-[80%]', 'h-full', 'bg-white',
                        'shadow-2xl', 'p-6');
                } else {
                    sidebar.classList.add('hidden');
                    sidebar.classList.remove('fixed', 'inset-0', 'flex', 'z-50'); // Remove z-50
                    sidebarOverlay.classList.add('hidden');

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
                        if (hoverActions) hoverActions.classList.remove('hidden');
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
                        if (hoverActions) hoverActions.classList.add('hidden');
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
                    quickViewModal.classList.remove('hidden');
                    setTimeout(() => quickViewModal.style.opacity = '1', 10);
                } else {
                    quickViewModal.style.opacity = '0';
                    setTimeout(() => quickViewModal.classList.add('hidden'), 300);
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
                    backToTopBtn.classList.remove('hidden');
                    setTimeout(() => {
                        backToTopBtn.classList.remove('opacity-0', 'translate-y-10');
                    }, 10);
                } else {
                    backToTopBtn.classList.add('opacity-0', 'translate-y-10');
                    setTimeout(() => backToTopBtn.classList.add('hidden'), 300);
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

        });

        function buildQueryParams() {
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

            return params;
        }


        function updateUrl() {
            const params = buildQueryParams();
            const queryString = params.toString();
            const url = queryString ? `/products?${queryString}` : '/products';

            // Update browser URL (without reload)
            window.history.pushState({}, '', url);

            // Fetch filtered products
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('products-container').innerHTML = data.html;
            })
            .catch(error => {
                console.error('Filter error:', error);
            });

            return;

            // let params = new URLSearchParams();

            // // Category
            // let selectedCategories = [];
            // document.querySelectorAll('.category-filter.active').forEach(el => {
            //     selectedCategories.push(el.dataset.slug);
            // });
            // if (selectedCategories.length) {
            //     params.append("category", selectedCategories.join(','));
            // }

            // // Brand
            // let selectedBrands = [];
            // document.querySelectorAll('.brand-filter:checked').forEach(el => {
            //     selectedBrands.push(el.value);
            // });
            // if (selectedBrands.length) {
            //     params.append("brand", selectedBrands.join(','));
            // }

            // // Sort
            // let sortValue = document.querySelector('.sort-filter')?.value;
            // if (sortValue) {
            //     params.append("sort", sortValue);
            // }

            // // Price
            // let min = document.getElementById("priceMin").value;
            // let max = document.getElementById("priceMax").value;

            // if (min) params.append("price_min", min);
            // if (max) params.append("price_max", max);

            // let query = params.toString();
            // window.location.href = query ? "/products?" + query : "/products";
        }

        /* ---------- PRICE HANDLERS ---------- */
        const priceMin = document.getElementById("priceMin");
        const priceMax = document.getElementById("priceMax");
        const priceRange = document.getElementById("priceRange");

        priceRange.addEventListener("input", function() {
            priceMax.value = this.value;
        });

        [priceMin, priceMax, priceRange].forEach(el => {
            el.addEventListener("change", updateUrl);
        });

        document.querySelectorAll('.category-filter').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.category-filter').forEach(cat => cat.classList.remove(
                    'active'));

                this.classList.add('active');

                updateUrl();
            });
        });

        document.querySelectorAll('.brand-filter').forEach(el => {
            el.addEventListener('change', function() {
                updateUrl();
            });
        });

        document.querySelector('.sort-filter').addEventListener('change', function() {
            updateUrl();
        });

        document.querySelectorAll('.remove-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                const slug = this.dataset.slug;

                const url = new URL(window.location.href);
                const params = url.searchParams;

                if (type === 'category') {
                    params.delete('category');
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

        document.getElementById('clear-all-filters')?.addEventListener('click', function() {
            window.location.href = '/products';
        });
    </script>
@endpush
