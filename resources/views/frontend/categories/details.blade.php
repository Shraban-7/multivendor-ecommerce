@extends('frontend.layouts.app')
@section('title', $category->name)

@section('content')
    <div class="mt-2 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <aside class="hidden md:block md:col-span-1">
                <div class="bg-white shadow rounded-lg p-5 space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 border-b pb-3">Filters</h2>

                    <label for="subcategory" class="block text-sm font-medium text-gray-700">Subcategories</label>
                    <select name="subcategory" id="subcategorySelect"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option value="all" {{ request('subcategory') == 'all' ? 'selected' : '' }}>All Categories
                        </option>
                        @foreach ($category->subcategories as $subcategory)
                            <option value="{{ $subcategory->slug }}"
                                {{ request('subcategory') == $subcategory->slug ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="space-y-6" id="filterOptions">
                        @foreach ($productOptions as $productOption)
                            <div class="rounded-lg border border-gray-200 bg-white p-4">
                                <h3 class="mb-3 text-sm font-semibold text-gray-800">
                                    {{ $productOption->name }}
                                </h3>
                                <div class="space-y-2"
                                    style="max-height: 200px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                                    <label class="group flex cursor-pointer items-center space-x-3">
                                        <input type="checkbox"
                                            class="filter-checkbox h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-offset-0"
                                            value="all" data-attribute="{{ Str::lower($productOption->name) }}" />
                                        <span
                                            class="text-sm text-gray-700 transition-colors duration-150 group-hover:text-primary">
                                            All {{ $productOption->name }}
                                        </span>
                                    </label>

                                    @foreach ($productOption->option_values as $value)
                                        <label class="group flex cursor-pointer items-center space-x-3">
                                            <input type="checkbox"
                                                class="filter-checkbox h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-offset-0"
                                                value="{{ $value->value }}"
                                                data-attribute="{{ Str::lower($productOption->name) }}" />
                                            <span
                                                class="text-sm text-gray-700 transition-colors duration-150 group-hover:text-primary">
                                                {{ ucwords($value->value) }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
            <section class="md:col-span-3">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
                    <h2 class="text-2xl font-semibold text-gray-800">{{ strtoupper($category->name) }}</h2>
                    <button data-drawer-target="filters-drawer" data-drawer-show="filters-drawer"
                        aria-controls="filters-drawer"
                        class="md:hidden flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-theme-dark transition">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filters</span>
                    </button>
                    <form class="w-full md:w-auto">
                        <select id="sort-by"
                            class="w-full md:w-auto border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                            <option value="">Sort By: Relevance</option>
                            <option value="best-selling">Best Selling</option>
                            <option value="trending">Trending</option>
                            <option value="popularity">Popularity</option>
                            <option value="new-arrivals">New Arrivals</option>
                        </select>

                    </form>
                </div>
                <div id="product-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @include('frontend.partials.product-card-load', ['products' => $products])
                </div>

                @if ($products->count() >= 16)
                    <div class="mt-5 text-center">
                        <button data-page="1" data-url="{{ route('category.details', $category->slug) }}" id="loadMoreBtn"
                            class="inline-flex items-center gap-2 px-6 py-3 text-sm md:text-base font-medium text-white bg-primary-500 rounded-lg shadow hover:bg-theme-dark transition">
                            <span>Load More</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                    </div>
                @endif
            </section>
        </div>
    </div>

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

        <div class="space-y-6">
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

            @foreach ($productOptions as $productOption)
                <form method="GET" action="{{ route('category.details', $category->slug) }}" class="space-y-3">
                    <label for="attribute-mobile-{{ $productOption->name }}"
                        class="block text-sm font-medium text-gray-700">{{ $productOption->name }}</label>
                    <select name="{{ strtolower($productOption->name) }}" id="attribute-mobile-{{ $productOption->name }}"
                        onchange="this.form.submit()"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option value="all" {{ request(strtolower($productOption->name)) == 'all' ? 'selected' : '' }}>
                            All {{ $productOption->name }}
                        </option>
                        @if (!empty($productOption->options))
                            @foreach ($productOption->options as $option)
                                <option value="{{ $option->value }}"
                                    {{ request(strtolower($productOption->name)) == $option->value ? 'selected' : '' }}>
                                    {{ strtoupper($option->value) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </form>
            @endforeach

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

    @push('scripts')
        <script>
            let filterState = {
                subcategory: $('#subcategorySelect').val(),
                sortBy: $('#sort-by').val(),
                attributes: {}
            };

            $('#subcategorySelect').on('change', function() {
                filterState.subcategory = $(this).val();
                updateFilters();
            });

            $('#sort-by').on('change', function() {
                filterState.sortBy = $(this).val();
                updateFilters();
            });

            $(document).on('change', '.filter-checkbox', function() {
                const attr = $(this).data('attribute');
                const value = $(this).val();
                const isChecked = $(this).is(':checked');

                if (!filterState.attributes[attr]) {
                    filterState.attributes[attr] = [];
                }

                if (isChecked) {
                    if (value === 'all') {
                        // select only "all", deselect others
                        filterState.attributes[attr] = ['all'];
                        $(`.filter-checkbox[data-attribute="${attr}"]`).not(this).prop('checked', false);
                    } else {
                        // remove "all" if present
                        filterState.attributes[attr] = filterState.attributes[attr].filter(v => v !== 'all');
                        if (!filterState.attributes[attr].includes(value)) {
                            filterState.attributes[attr].push(value);
                        }
                        $(`.filter-checkbox[data-attribute="${attr}"][value="all"]`).prop('checked', false);
                    }
                } else {
                    // remove unchecked value
                    filterState.attributes[attr] = filterState.attributes[attr].filter(v => v !== value);
                    if (filterState.attributes[attr].length === 0) {
                        delete filterState.attributes[attr];
                    }
                }

                updateFilters();
            });

            function updateFilters() {
                let serializedData = {
                    subcategory: filterState.subcategory,
                    sortBy: filterState.sortBy,
                };

                $.each(filterState.attributes, function(attr, values) {
                    values.forEach(function(value) {
                        serializedData[`attributes[${attr}][]`] = serializedData[`attributes[${attr}][]`] || [];
                        serializedData[`attributes[${attr}][]`].push(value);
                    });
                });

                const queryString = $.param(serializedData, true);
                const urlBase = "{{ route('category.details', $category->slug) }}";
                const fullUrl = queryString ? `${urlBase}?${queryString}` : urlBase;

                window.history.pushState({}, '', fullUrl);

                $.ajax({
                    url: fullUrl,
                    method: 'GET',
                    beforeSend: function() {
                        $('#product-list').html(`
                        <div class="col-span-full text-center py-10">
                            <i class="fa fa-spinner fa-spin text-2xl text-primary"></i>
                            <p class="text-gray-600 mt-2 text-sm">Loading products...</p>
                        </div>
                    `);
                    },
                    success: function(response) {
                        $('#product-list').html(response);
                        if (typeof initQuickViewModals === 'function') initQuickViewModals();
                        if (typeof initFlowbite === 'function') initFlowbite();
                    },
                    error: function() {
                        $('#product-list').html(`
                        <div class="col-span-full text-center text-red-600 py-10">
                            Something went wrong. Please try again.
                        </div>
                    `);
                    }
                });
            }

            $('#loadMoreBtn').on('click', function() {
                let button = $(this);
                let page = parseInt(button.data('page')) + 1;
                let url = button.data('url');

                let data = {
                    page: page,
                    subcategory: filterState.subcategory,
                    sortBy: filterState.sortBy
                };

                $.each(filterState.attributes, function(attr, values) {
                    data[`attributes[${attr}][]`] = values;
                });

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: data,
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
                            if (typeof initQuickViewModals === 'function') initQuickViewModals();
                            if (typeof initFlowbite === 'function') initFlowbite();
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
