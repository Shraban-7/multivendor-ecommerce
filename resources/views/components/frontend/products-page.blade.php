<div
    class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
    <p></p>
    <div class="flex items-center gap-3 w-full sm:w-auto">
        <!-- Sort Select -->
        <div class="relative flex-1 sm:flex-none group">
            <select name="sort"
                class="sort-filter w-full sm:w-48 appearance-none bg-gray-50 border border-gray-200
                           text-gray-700 text-sm rounded-lg focus:ring-primary-500
                           focus:border-primary-500 block p-2.5 pr-8 cursor-pointer
                           hover:border-primary-300 transition">

                <option value="">Sort by Popularity</option>
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                    Newest Arrivals
                </option>
                <option value="low_high" {{ request('sort') === 'low_high' ? 'selected' : '' }}>
                    Price: Low to High
                </option>
                <option value="high_low" {{ request('sort') === 'high_low' ? 'selected' : '' }}>
                    Price: High to Low
                </option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>

        <!-- View Toggle -->
        <div class="flex bg-gray-100 p-1 rounded-lg shrink-0">
            <button id="gridViewBtn"
                class="w-8 h-8 rounded flex items-center justify-center
                           bg-white text-primary-600 shadow-sm transition">
                <i class="fas fa-th-large"></i>
            </button>
            <button id="listViewBtn"
                class="w-8 h-8 rounded flex items-center justify-center
                           text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-list"></i>
            </button>
        </div>

    </div>
</div>

<!-- Active Filters -->
@if (!empty($selectedCategories) || !empty($selectedBrands) || !empty($selectedPrice) || !empty($productOptionFilters))
    <div class="flex flex-wrap gap-2 mb-6" id="active-filters">

        {{-- Categories --}}
        @foreach ($selectedCategories ?? [] as $slug)
            @php $category = $categories->firstWhere('slug', $slug); @endphp
            @if ($category)
                <span
                    class="bg-white border border-gray-200 text-gray-600
                               px-3 py-1 rounded-full text-xs font-medium
                               flex items-center gap-2">
                    {{ $category->name }}
                    <button class="remove-filter text-gray-400 hover:text-red-500" data-type="category"
                        data-slug="{{ $slug }}">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
        @endforeach

        {{-- Brands --}}
        @foreach ($selectedBrands ?? [] as $slug)
            @php $brand = $brands->firstWhere('slug', $slug); @endphp
            @if ($brand)
                <span
                    class="bg-white border border-gray-200 text-gray-600
                               px-3 py-1 rounded-full text-xs font-medium
                               flex items-center gap-2">
                    {{ $brand->name }}
                    <button class="remove-filter text-gray-400 hover:text-red-500" data-type="brand"
                        data-slug="{{ $slug }}">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
        @endforeach

        {{-- Price --}}
        @if (!empty($selectedPrice))
            <span
                class="bg-white border border-gray-200 text-gray-600
                           px-3 py-1 rounded-full text-xs font-medium
                           flex items-center gap-2">
                Price: ৳{{ $selectedPrice['min'] ?? 0 }} - {{ $selectedPrice['max'] ?? '∞' }}
                <button class="remove-filter text-gray-400 hover:text-red-500" data-type="price">
                    <i class="fas fa-times"></i>
                </button>
            </span>
        @endif

        {{-- Product Options --}}
        @foreach ($productOptionFilters ?? [] as $optionKey => $values)
            @php
                $valueIds = is_array($values) ? $values : explode(',', $values);

                $optionName = ucwords(str_replace('_', ' ', $optionKey));

                $optionValues = collect($productOptions[$optionName] ?? []);
            @endphp

            @foreach ($valueIds as $valueId)
                @php
                    $value = $optionValues->firstWhere('id', (int) $valueId);
                @endphp

                @if ($value)
                    <span
                        class="bg-white border border-gray-200 text-gray-600
                       px-3 py-1 rounded-full text-xs font-medium
                       flex items-center gap-2">
                        {{ $optionName }}: {{ $value['value'] }}

                        <button class="remove-filter text-gray-400 hover:text-red-500" data-type="attribute"
                            data-slug="{{ $optionKey }}|{{ $valueId }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
            @endforeach
        @endforeach


        <button type="button" onclick="clearAll()"
            class="text-xs text-red-500 hover:text-red-700
                       hover:underline font-medium ml-2 transition">
            Clear All
        </button>

    </div>
@endif

<!-- Products Grid -->
<div id="productsContainer" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 lg:gap-4">

    @foreach ($products as $product)
        <x-frontend.product-card :product="$product" />
    @endforeach

</div>

<!-- Pagination -->

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
