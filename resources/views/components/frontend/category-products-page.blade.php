<div
    class="bg-white p-4 rounded-sm border border-ds-border-default mb-6">

    {{-- Subcategory Pills --}}
    @if ($category->subcategories->count())
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('category.details', $category->slug) }}"
                class="px-3 py-1.5 text-xs font-semibold rounded-full transition-colors duration-100 {{ $selectedSubcategory === 'all' ? 'bg-brand text-white' : 'bg-ds-surface-muted text-ds-text-secondary hover:bg-brand/10 hover:text-brand border border-ds-border-default' }}">
                All
            </a>
            @foreach ($category->subcategories as $subcategory)
                <a href="{{ route('category.details', $category->slug) }}?subcategory={{ $subcategory->slug }}"
                    class="px-3 py-1.5 text-xs font-semibold rounded-full transition-colors duration-100 {{ $selectedSubcategory === $subcategory->slug ? 'bg-brand text-white' : 'bg-ds-surface-muted text-ds-text-secondary hover:bg-brand/10 hover:text-brand border border-ds-border-default' }}">
                    {{ $subcategory->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Sort --}}
    <div class="flex items-center gap-3">
        <div class="relative flex-1 sm:flex-none group">
            <select name="sort"
                class="sort-filter w-full sm:w-48 appearance-none bg-ds-surface-muted border border-ds-border-default
                       text-ds-text-secondary text-xs rounded-sm focus:ring-brand focus:border-brand block p-2.5 pr-8 cursor-pointer
                       hover:border-brand/50 transition">

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

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-ds-text-tertiary">
                <i class="fas fa-chevron-down text-[10px]"></i>
            </div>
        </div>

        <span class="text-[11px] text-ds-text-tertiary hidden sm:block">{{ $products->total() }} items</span>
    </div>
</div>

{{-- Active Filters --}}
@if (!empty($selectedBrands) || !empty($productOptionFilters) || ($selectedSubcategory ?? 'all') !== 'all')
    <div class="flex flex-wrap gap-2 mb-4" id="active-filters">

        @if (($selectedSubcategory ?? 'all') !== 'all')
            @php $sub = $category->subcategories->firstWhere('slug', $selectedSubcategory); @endphp
            @if ($sub)
                <span
                    class="bg-white border border-ds-border-default text-ds-text-secondary px-3 py-1 rounded-full text-[11px] font-medium flex items-center gap-2">
                    {{ $sub->name }}
                    <button class="remove-filter text-ds-text-tertiary hover:text-ds-feedback-danger" data-type="subcategory"
                        data-slug="{{ $selectedSubcategory }}">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
        @endif

        @foreach ($selectedBrands as $slug)
            @php $brand = $brands->firstWhere('slug', $slug); @endphp
            @if ($brand)
                <span
                    class="bg-white border border-ds-border-default text-ds-text-secondary px-3 py-1 rounded-full text-[11px] font-medium flex items-center gap-2">
                    {{ $brand->name }}
                    <button class="remove-filter text-ds-text-tertiary hover:text-ds-feedback-danger" data-type="brand"
                        data-slug="{{ $slug }}">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
            @endif
        @endforeach

        @foreach ($productOptionFilters as $optionKey => $values)
            @php
                $valueIds = is_array($values) ? $values : explode(',', $values);
                $optionName = ucwords(str_replace('_', ' ', $optionKey));
                $optionValues = collect($productOptions[$optionName] ?? []);
            @endphp
            @foreach ($valueIds as $valueId)
                @php $value = $optionValues->firstWhere('id', (int) $valueId); @endphp
                @if ($value)
                    <span
                        class="bg-white border border-ds-border-default text-ds-text-secondary px-3 py-1 rounded-full text-[11px] font-medium flex items-center gap-2">
                        {{ $optionName }}: {{ $value['value'] }}
                        <button class="remove-filter text-ds-text-tertiary hover:text-ds-feedback-danger" data-type="attribute"
                            data-slug="{{ $optionKey }}|{{ $valueId }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endif
            @endforeach
        @endforeach

        <button type="button" onclick="clearAll()"
            class="text-[11px] text-ds-feedback-danger hover:underline font-medium ml-1 transition">
            Clear All
        </button>
    </div>
@endif

{{-- Products Grid --}}
<div id="productsContainer" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 lg:gap-4">
    @foreach ($products as $product)
        <x-frontend.product-card :product="$product" />
    @endforeach
</div>

{{-- Load More --}}
@if ($products->hasMorePages())
    <div class="mt-5 text-center">
        <button id="loadMoreProducts" data-page="{{ $products->currentPage() }}" data-url="{{ url()->current() }}"
            class="inline-flex items-center gap-2 px-6 py-2 border border-brand text-brand text-xs font-semibold rounded-sm hover:bg-brand hover:text-white transition-colors duration-100"
            type="button">
            <span>Load More</span>
            <i class="fas fa-chevron-down text-[10px]"></i>
        </button>
    </div>
@endif
