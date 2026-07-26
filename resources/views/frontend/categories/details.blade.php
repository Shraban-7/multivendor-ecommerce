@extends('frontend.layouts.app')
@section('title', $category->name)

@section('content')
    <section class="container mx-auto pb-20 lg:pb-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- SIDEBAR --}}
            <aside id="sidebar" class="hidden lg:block lg:w-64 shrink-0 transition-all duration-300 z-30">
                <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 lg:hidden hidden"></div>

                <div id="sidebarContent"
                    class="bg-white border border-ds-border-default rounded-sm p-4 sticky top-24 h-full lg:h-auto overflow-y-auto">

                    {{-- Mobile Header --}}
                    <div class="flex items-center justify-between mb-5 lg:hidden">
                        <h2 class="text-base font-bold text-ds-text-primary">Filters</h2>
                        <button id="closeMobileFilter" class="text-ds-text-tertiary hover:text-ds-feedback-danger"><i class="fas fa-times text-lg"></i></button>
                    </div>

                    {{-- Subcategories --}}
                    <div class="mb-5 border-b border-ds-border-default pb-4">
                        <h3 class="font-semibold text-ds-text-primary mb-3 text-xs uppercase tracking-wider">Subcategories</h3>
                        <select id="subcategorySelect"
                            class="w-full appearance-none bg-ds-surface-muted border border-ds-border-default text-ds-text-secondary text-xs rounded-sm p-2.5 pr-8 cursor-pointer focus:ring-brand focus:border-brand">
                            <option value="all" {{ $selectedSubcategory === 'all' ? 'selected' : '' }}>All</option>
                            @foreach ($category->subcategories as $subcategory)
                                <option value="{{ $subcategory->slug }}" {{ $selectedSubcategory === $subcategory->slug ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div class="mb-5 border-b border-ds-border-default pb-4">
                        <h3 class="font-semibold text-ds-text-primary mb-3 text-xs uppercase tracking-wider">Price Range</h3>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="priceMin" type="number" placeholder="Min" value="{{ request('price_min', '') }}"
                                    class="w-full bg-ds-surface-muted border border-ds-border-default rounded-sm px-2.5 py-1.5 text-xs text-ds-text-primary focus:ring-brand focus:border-brand">
                                <span class="text-ds-text-tertiary text-xs">-</span>
                                <input id="priceMax" type="number" placeholder="Max" value="{{ request('price_max', '') }}"
                                    class="w-full bg-ds-surface-muted border border-ds-border-default rounded-sm px-2.5 py-1.5 text-xs text-ds-text-primary focus:ring-brand focus:border-brand">
                            </div>
                        </div>
                    </div>

                    {{-- Brands --}}
                    <div class="mb-5 border-b border-ds-border-default pb-4">
                        <h3 class="font-semibold text-ds-text-primary mb-3 text-xs uppercase tracking-wider">Brands</h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2">
                            @foreach ($brands as $brand)
                                @if ($brand->products_count > 0)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" value="{{ $brand->slug }}" class="brand-filter w-3.5 h-3.5 rounded border-ds-border-default text-brand focus:ring-brand"
                                            @if (in_array($brand->slug, $selectedBrands)) checked @endif>
                                        <span class="text-xs text-ds-text-secondary">{{ $brand->name }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Product Options --}}
                    <div class="space-y-4" id="attribute-filters">
                        @foreach ($productOptions as $optionName => $values)
                            @if (count($values) > 0)
                                <div class="border-b border-ds-border-default pb-3">
                                    <h3 class="font-semibold text-ds-text-primary mb-3 text-xs uppercase tracking-wider option-btn">
                                        {{ $optionName }}
                                    </h3>
                                    <div class="space-y-2 max-h-40 overflow-y-auto pr-2">
                                        @foreach ($values as $value)
                                            @php
                                                $key = strtolower(str_replace(' ', '_', $optionName));
                                                $checkedValues = isset($productOptionFilters[$key])
                                                    ? (is_array($productOptionFilters[$key]) ? $productOptionFilters[$key] : explode(',', $productOptionFilters[$key]))
                                                    : [];
                                            @endphp
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="checkbox" value="{{ $value['id'] }}"
                                                    class="attribute-filter w-3.5 h-3.5 rounded border-ds-border-default text-brand focus:ring-brand"
                                                    @if (in_array((string) $value['id'], array_map('strval', $checkedValues))) checked @endif>
                                                <span class="text-xs text-ds-text-secondary">{{ $value['value'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <button id="applyFiltersBtn"
                        class="w-full bg-brand text-white py-2.5 rounded-sm font-bold text-xs lg:hidden mt-4 hover:bg-brand-deep transition">
                        Apply Filters
                    </button>
                </div>
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="flex-1" id="products-container">
                @include('components.frontend.category-products-page')
            </main>
        </div>
    </section>

    {{-- Mobile Filter Drawer --}}
    <div id="filters-drawer"
        class="fixed top-0 left-0 z-50 w-80 h-screen p-6 overflow-y-auto transition-transform -translate-x-full bg-white shadow-lg"
        tabindex="-1" aria-labelledby="filters-drawer-label">
        <div class="flex items-center justify-between mb-4">
            <h5 id="filters-drawer-label" class="text-lg font-semibold text-ds-text-primary">Filters</h5>
            <button type="button" data-drawer-hide="filters-drawer" aria-controls="filters-drawer"
                class="text-ds-text-tertiary hover:text-ds-text-primary">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="space-y-6">
            {{-- Subcategories --}}
            <div>
                <label class="block text-xs font-semibold text-ds-text-primary mb-2 uppercase tracking-wider">Subcategories</label>
                <select id="subcategoryMobile"
                    class="w-full appearance-none bg-ds-surface-muted border border-ds-border-default text-ds-text-secondary text-xs rounded-sm p-2.5 focus:ring-brand focus:border-brand">
                    <option value="all" {{ $selectedSubcategory === 'all' ? 'selected' : '' }}>All</option>
                    @foreach ($category->subcategories as $subcategory)
                        <option value="{{ $subcategory->slug }}" {{ $selectedSubcategory === $subcategory->slug ? 'selected' : '' }}>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Brands --}}
            <div>
                <label class="block text-xs font-semibold text-ds-text-primary mb-2 uppercase tracking-wider">Brands</label>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    @foreach ($brands as $brand)
                        @if ($brand->products_count > 0)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" value="{{ $brand->slug }}" class="brand-filter-mobile w-3.5 h-3.5 rounded border-ds-border-default text-brand focus:ring-brand"
                                    @if (in_array($brand->slug, $selectedBrands)) checked @endif>
                                <span class="text-xs text-ds-text-secondary">{{ $brand->name }}</span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Product Options --}}
            @foreach ($productOptions as $optionName => $values)
                @if (count($values) > 0)
                    <div>
                        <label class="block text-xs font-semibold text-ds-text-primary mb-2 uppercase tracking-wider">{{ $optionName }}</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach ($values as $value)
                                @php
                                    $key = strtolower(str_replace(' ', '_', $optionName));
                                    $checkedValues = isset($productOptionFilters[$key])
                                        ? (is_array($productOptionFilters[$key]) ? $productOptionFilters[$key] : explode(',', $productOptionFilters[$key]))
                                        : [];
                                @endphp
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $value['id'] }}"
                                        class="attribute-filter-mobile w-3.5 h-3.5 rounded border-ds-border-default text-brand focus:ring-brand"
                                        data-option="{{ $key }}"
                                        @if (in_array((string) $value['id'], array_map('strval', $checkedValues))) checked @endif>
                                    <span class="text-xs text-ds-text-secondary">{{ $value['value'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <button id="applyMobileFilters"
                class="w-full bg-brand text-white py-2.5 rounded-sm font-bold text-xs hover:bg-brand-deep transition">
                Apply Filters
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // --- Mobile Filter Sidebar ---
                const openFilterBtn = document.getElementById('openMobileFilter');
                const closeFilterBtn = document.getElementById('closeMobileFilter');
                const applyFiltersBtn = document.getElementById('applyFiltersBtn');
                const applyMobileFilters = document.getElementById('applyMobileFilters');
                const sidebar = document.getElementById('sidebar');
                const sidebarContent = document.getElementById('sidebarContent');
                const sidebarOverlay = document.getElementById('sidebarOverlay');

                function toggleFilter(show) {
                    if (show) {
                        sidebar.classList.remove('hidden');
                        sidebar.classList.add('fixed', 'inset-0', 'flex', 'z-50');
                        sidebarOverlay.classList.remove('hidden');
                        sidebarContent.classList.remove('sticky', 'top-24');
                        sidebarContent.classList.add('relative', 'w-80', 'max-w-[80%]', 'h-full', 'bg-white', 'shadow-2xl', 'p-6');
                    } else {
                        sidebar.classList.add('hidden');
                        sidebar.classList.remove('fixed', 'inset-0', 'flex', 'z-50');
                        sidebarOverlay.classList.add('hidden');
                        sidebarContent.classList.add('sticky', 'top-24');
                        sidebarContent.classList.remove('relative', 'w-80', 'max-w-[80%]', 'h-full', 'bg-white', 'shadow-2xl', 'p-6');
                    }
                }

                if (openFilterBtn) openFilterBtn.addEventListener('click', () => toggleFilter(true));
                if (closeFilterBtn) closeFilterBtn.addEventListener('click', () => toggleFilter(false));
                if (applyFiltersBtn) applyFiltersBtn.addEventListener('click', () => toggleFilter(false));
                if (applyMobileFilters) applyMobileFilters.addEventListener('click', () => {
                    // Sync mobile checkboxes to desktop
                    document.querySelectorAll('.brand-filter-mobile').forEach(mob => {
                        const desk = document.querySelector(`.brand-filter[value="${mob.value}"]`);
                        if (desk) desk.checked = mob.checked;
                    });
                    document.querySelectorAll('.attribute-filter-mobile').forEach(mob => {
                        const desk = document.querySelector(`.attribute-filter[value="${mob.value}"]`);
                        if (desk) desk.checked = mob.checked;
                    });
                    const mobSub = document.getElementById('subcategoryMobile');
                    const deskSub = document.getElementById('subcategorySelect');
                    if (mobSub && deskSub) deskSub.value = mobSub.value;
                    toggleFilter(false);
                    updateFilters();
                });
                if (sidebarOverlay) sidebarOverlay.addEventListener('click', () => toggleFilter(false));
            });

            // --- Filter Logic ---
            function buildQueryParams() {
                const params = new URLSearchParams();

                const sub = document.getElementById('subcategorySelect')?.value;
                if (sub && sub !== 'all') params.append('subcategory', sub);

                const selectedBrands = [];
                document.querySelectorAll('.brand-filter:checked').forEach(el => selectedBrands.push(el.value));
                if (selectedBrands.length) params.append('brand', selectedBrands.join(','));

                const sortValue = document.querySelector('.sort-filter')?.value;
                if (sortValue) params.append('sort', sortValue);

                let min = document.getElementById('priceMin')?.value;
                let max = document.getElementById('priceMax')?.value;
                if (min) params.append('price_min', min);
                if (max) params.append('price_max', max);

                const selectedOptions = {};
                document.querySelectorAll('.attribute-filter:checked').forEach(el => {
                    const container = el.closest('.border-b');
                    if (!container) return;
                    const nameEl = container.querySelector('.option-btn');
                    if (!nameEl) return;
                    const name = nameEl.textContent.trim();
                    if (!selectedOptions[name]) selectedOptions[name] = [];
                    selectedOptions[name].push(el.value);
                });
                for (const [name, values] of Object.entries(selectedOptions)) {
                    if (values.length) params.append(name.toLowerCase().replace(/\s+/g, '_'), values.join(','));
                }

                return params;
            }

            function updateFilters() {
                const params = buildQueryParams();
                const urlBase = "{{ route('category.details', $category->slug) }}";
                const qs = params.toString();
                const fullUrl = qs ? `${urlBase}?${qs}` : urlBase;

                window.history.pushState({}, '', fullUrl);

                $.ajax({
                    url: fullUrl,
                    method: 'GET',
                    beforeSend: function () {
                        $('#products-container').html(`
                            <div class="col-span-full text-center py-16">
                                <svg class="animate-spin h-6 w-6 text-brand mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <p class="text-ds-text-tertiary mt-2 text-xs">Loading products...</p>
                            </div>
                        `);
                    },
                    success: function (response) {
                        $('#products-container').html(response.html);
                    },
                    error: function () {
                        $('#products-container').html(`
                            <div class="col-span-full text-center text-ds-feedback-danger py-16 text-xs">
                                Something went wrong. Please try again.
                            </div>
                        `);
                    }
                });
            }

            // Event listeners
            document.getElementById('subcategorySelect')?.addEventListener('change', updateFilters);
            document.querySelector('.sort-filter')?.addEventListener('change', updateFilters);
            document.querySelectorAll('.brand-filter').forEach(el => el.addEventListener('change', updateFilters));
            document.querySelectorAll('.attribute-filter').forEach(el => el.addEventListener('change', updateFilters));

            let priceTimer = null;
            ['priceMin', 'priceMax'].forEach(id => {
                document.getElementById(id)?.addEventListener('keyup', () => {
                    clearTimeout(priceTimer);
                    priceTimer = setTimeout(updateFilters, 500);
                });
            });

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.remove-filter');
                if (!btn) return;

                const type = btn.dataset.type;
                const slug = btn.dataset.slug;

                if (type === 'subcategory') {
                    document.getElementById('subcategorySelect').value = 'all';
                }
                if (type === 'brand') {
                    document.querySelectorAll('.brand-filter').forEach(el => {
                        if (el.value === slug) el.checked = false;
                    });
                }
                if (type === 'attribute') {
                    const [, valueId] = slug.split('|');
                    document.querySelectorAll('.attribute-filter').forEach(el => {
                        if (el.value === valueId) el.checked = false;
                    });
                }

                updateFilters();
            });

            function clearAll() {
                document.getElementById('subcategorySelect').value = 'all';
                document.querySelectorAll('.brand-filter').forEach(el => el.checked = false);
                document.querySelectorAll('.attribute-filter').forEach(el => el.checked = false);
                document.getElementById('priceMin').value = '';
                document.getElementById('priceMax').value = '';
                const sort = document.querySelector('.sort-filter');
                if (sort) sort.value = '';
                updateFilters();
            }

            // Load More
            $(document).on('click', '#loadMoreProducts', function () {
                const button = $(this);
                let page = parseInt(button.data('page')) + 1;
                const url = button.data('url');
                const params = buildQueryParams();
                params.append('page', page);
                params.append('load_more', '1');
                const fullUrl = url + '?' + params.toString();

                $.ajax({
                    url: fullUrl,
                    method: 'GET',
                    beforeSend: function () {
                        button.prop('disabled', true).html(
                            '<svg class="animate-spin h-4 w-4 text-brand" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                        );
                    },
                    success: function (response) {
                        if ($.trim(response) !== '') {
                            $('#productsContainer').append(response);
                            button.data('page', page);
                            button.prop('disabled', false).html(
                                '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                            );
                        } else {
                            button.hide();
                        }
                    },
                    error: function () {
                        button.prop('disabled', false).html(
                            '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                        );
                    }
                });
            });
        </script>
    @endpush
@endsection
