@extends('frontend.layouts.app')
@section('title', $category->name)

@section('content')
    <main class="grocery-essentials-page">
        <!-- Page Promotion Banner Starts -->
        <section class="container py-5 page-promotion md:w-full">
            <div
                class="promo-wrapper md:container bg-[{{ $category->cover_bg_color }}] grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden">
                <div
                    class="flex flex-col items-start justify-center order-2 gap-3 p-5 md:order-1 promo-content sm:gap-5 md:p-10 lg:p-14 2xl:p-20">
                    <h2
                        class="lg:text-3xl md:text-2xl text-xl text-[{{ $category->cover_text_color }}] font-bold md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2">
                        {{ $category->cover_title }}
                    </h2>
                    <p class="text-xs text-[{{ $category->cover_text_color }}] md:pr-7 lg:pr-14 2xl:pr-20">
                        {{ $category->cover_description }}
                    </p>
                    <a href="#"
                        class="theme-btn bg-[{{ $category->cover_button_color }}] px-5 py-2 lg:px-7 lg:px-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm">Learn
                        More</a>
                </div>
                <div class="order-1 promo-image">
                    <div class="w-full img-wrap">
                        <div class="w-full h-40 overflow-hidden rounded-lg lg:h-96 md:h-80 md:rounded-3xl">
                            <a href="#" class="block w-full h-full">
                                <img src="{{ asset('assets/' . $category->cover_image) }}" alt="{{ $category->name }}"
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
            <form action="#" class="space-y-5">
                <!-- Categories -->
                <div>
                    <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                        Categories
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="category" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">Frozen Snacks</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="category" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">Gluten Free</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="category" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">Meat Style</span>
                        </label>
                    </div>
                </div>

                <!-- Brand -->
                <div>
                    <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                        Brand
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="brand" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">Gardein</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="brand" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">Maggie</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="brand" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">Kroger</span>
                        </label>
                    </div>
                </div>

                <!-- Review -->
                <div>
                    <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                        Review
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="review" class="w-4 h-4 text-primary focus:ring-primary" />
                            <div class="flex items-center ml-2">
                                <div class="flex text-light-yellow">★★★★★</div>
                                <span class="ml-1 text-sm text-jet-gray">5 Star</span>
                            </div>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="review" class="w-4 h-4 text-primary focus:ring-primary" />
                            <div class="flex items-center ml-2">
                                <div class="flex text-light-yellow">
                                    ★★★★<span class="text-gray-300">★</span>
                                </div>
                                <span class="ml-1 text-sm text-jet-gray">4 Star</span>
                            </div>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="review" class="w-4 h-4 text-primary focus:ring-primary" />
                            <div class="flex items-center ml-2">
                                <div class="flex text-light-yellow">
                                    ★★★<span class="text-gray-300">★★</span>
                                </div>
                                <span class="ml-1 text-sm text-jet-gray">3 Star</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Price -->
                <div>
                    <h3 class="pb-2 mb-3 text-lg border-b border-dashed border-jet-gray">
                        Price
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="price" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">Under $ 23</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">$25-$50</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm">$50-$100</span>
                        </label>
                    </div>

                    <div class="flex gap-2 mt-5">
                        <div class="inline-flex items-center">
                            <input id="min" type="radio" value="min" name="price" class="sr-only peer" />
                            <label for="min"
                                class="px-5 border rounded-3xl py-1 text-base text-jet-gray border-gray-300 peer-checked:ring-primary peer-checked:ring-[1px] peer-checked:!border-primary peer-checked:text-primary">$
                                Min</label>
                        </div>
                        <div class="inline-flex items-center">
                            <input id="max" type="radio" value="max" name="price" class="sr-only peer" />
                            <label for="max"
                                class="px-5 border rounded-3xl py-1 text-base text-jet-gray border-gray-300 peer-checked:ring-primary peer-checked:ring-[1px] peer-checked:!border-primary peer-checked:text-primary">$
                                Max</label>
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
                            <input type="radio" name="shipping" class="w-4 h-4 text-primary focus:ring-primary" />
                            <span class="ml-2 text-sm text-gray-600">Local Area (2 miles)</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-start gap-3">
                    <button type="reset"
                        class="px-5 py-2 text-sm text-gray-600 border-2 rounded-full border-theme-dark hover:bg-persian-red hover:text-theme-light eq">
                        Reset
                    </button>
                    <button class="flex-1 px-4 py-2 text-sm text-white rounded-full bg-primary hover:bg-theme-dark eq">
                        Show 150 Result
                    </button>
                </div>
            </form>
        </section>
        <!-- All Filterts Sidebar Ended-->

        <!-- Page Main Content Starts -->
        <section class="container products-section section-padding">
            <!-- Page Title -->
            <div class="mb-8 md:mb-11">
                <h1 class="mb-5 text-xl font-medium sm:text-2xl text-jet-gray md:mb-10">
                    {{ strtoupper($category->name) }}/ALL CATEGORIES
                </h1>

                <!-- Filters action btns -->
                <div class="flex items-start justify-between flex-nowrap">
                    <div class="flex flex-wrap items-center w-10/12 gap-2 sm:gap-4 xl:w-auto lg:w-9/12 lg:w-auto">
                        <!-- All Categories -->
                        <form method="GET" action="{{ route('category_details', $category->slug) }}"
                            class="flex items-center gap-1 rounded-3xl bg-aqua-deep hover:bg-rangoon-green eq sm:text-sm text-xs md:text-base sm:pl-5 pl-3 sm:!pr-2 !pr-1 py-2.5 sm:py-3 inline-flex text-white cursor-pointer">
                            <label for="sort-by" class="block sr-only whitespace-nowrap">All Categories</label>
                            <select name="subcategory" id="sort-by" onchange="this.form.submit()"
                                class="block w-full border-0 appearance-none cursor-pointer bg-inherit focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                <option value="all" {{ request('subcategory') == 'all' ? 'selected' : '' }}>All
                                    Categories</option>
                                @foreach ($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->slug }}"
                                        {{ request('subcategory') == $subcategory->slug ? 'selected' : '' }}>
                                        {{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="hidden">Search</button>
                        </form>

                        <!-- Relevance -->
                        <form
                            class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray">
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

                        <!-- Color -->
                        {{-- <div class="flex items-center gap-4">
                            <!-- Dropdown Menu -->
                            <div class="relative">
                                <button id="colorSortButton" data-dropdown-toggle="colorSortDropdown"
                                    class="bg-theme-light/90 hover:bg-aqua-deep/10 eq rounded-3xl text-xs sm:text-sm px-3 sm:px-5 sm:py-3 py-2.5 text-center inline-flex text-jet-gray items-center"
                                    type="button">
                                    Color
                                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Content -->
                                <div id="colorSortDropdown"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-44">
                                    <ul class="py-1 text-sm text-gray-700" aria-labelledby="colorSortButton">
                                        <li>
                                            <button class="flex items-center w-full gap-2 px-4 py-2 hover:bg-gray-100">
                                                <span class="w-4 h-4 bg-red-500 rounded-full"></span>
                                                Red
                                            </button>
                                        </li>
                                        <li>
                                            <button class="flex items-center w-full gap-2 px-4 py-2 hover:bg-gray-100">
                                                <span class="w-4 h-4 bg-blue-500 rounded-full"></span>
                                                Blue
                                            </button>
                                        </li>
                                        <li>
                                            <button class="flex items-center w-full gap-2 px-4 py-2 hover:bg-gray-100">
                                                <span class="w-4 h-4 bg-green-500 rounded-full"></span>
                                                Green
                                            </button>
                                        </li>
                                        <li>
                                            <button class="flex items-center w-full gap-2 px-4 py-2 hover:bg-gray-100">
                                                <span class="w-4 h-4 bg-yellow-500 rounded-full"></span>
                                                Yellow
                                            </button>
                                        </li>
                                        <li>
                                            <button class="flex items-center w-full gap-2 px-4 py-2 hover:bg-gray-100">
                                                <span class="w-4 h-4 bg-purple-500 rounded-full"></span>
                                                Purple
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Material -->

                        @foreach ($productAttributes as $productAttribute)
                            <form method="GET" action="{{ route('category_details', $category->slug) }}"
                                class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray">
                                <label for="attribute-{{ $productAttribute->name }}"
                                    class="block sr-only whitespace-nowrap">{{ $productAttribute->name }}</label>
                                <select name="{{ strtolower($productAttribute->name) }}"
                                    id="attribute-{{ $productAttribute->name }}" onchange="this.form.submit()"
                                    class="block w-full bg-transparent border-0 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                    <option value="all"
                                        {{ request(strtolower($productAttribute->name)) == 'all' ? 'selected' : '' }}>
                                        {{ $productAttribute->name }}</option>
                                    @foreach ($productAttribute->options as $option)
                                        <option value="{{ $option->value }}"
                                            {{ request(strtolower($productAttribute->name)) == $option->value ? 'selected' : '' }}>
                                            {{ strtoupper($option->value) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @endforeach

                        <!-- Review -->
                        <form
                            class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray">
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
                            class="flex items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray">
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
                            class="flex items-center justify-center w-10 h-10 gap-1 ml-auto text-sm text-white rounded-full md:w-auto md:rounded-3xl bg-primary hover:bg-theme-dark eq md:px-5 md:py-3">
                            <span class="hidden md:block">All Filters</span>

                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" stroke="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.029 3.98803C10.503 3.7507 10.7405 3.63203 10.87 3.44047C11 3.24936 11 3.01869 11 2.55735V2.25068C11 1.66134 11 1.36623 10.78 1.18311C10.561 1 10.2075 1 9.5 1H2.5C1.793 1 1.4395 1 1.22 1.18311C1.0005 1.36623 1 1.66134 1 2.25112V2.55779C1 3.01869 1 3.24936 1.13 3.44047C1.26 3.63158 1.4965 3.7507 1.971 3.98803L3.4275 4.71693C3.7455 4.87604 3.905 4.9556 4.019 5.0436C4.256 5.22627 4.402 5.44138 4.468 5.70583C4.5 5.83205 4.5 5.9805 4.5 6.27695V7.46363C4.5 7.86763 4.5 8.06985 4.626 8.22719C4.752 8.38497 4.976 8.46275 5.423 8.61831C6.3625 8.94453 6.832 9.10764 7.166 8.92186C7.5 8.73608 7.5 8.31208 7.5 7.46318V6.2765C7.5 5.9805 7.5 5.83205 7.532 5.70538C7.59479 5.44634 7.75297 5.21331 7.9815 5.04316"
                                    stroke="currentColor" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card's Wrapper -->
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6">
                <!-- Product Card 1 -->
                @foreach ($products as $product)
                    <div
                        class="relative p-3 bg-white shadow group/product-card rounded-xl sm:rounded-2xl hover:shadow-lg eq sm:p-8">
                        <!-- product image -->
                        <a href="{{ route('product.details', $product->slug) }}" class="block h-32 prod-image sm:h-40">
                            <img src="{{ storage_url($product->thumbnail) }}" alt="Italian Avocado"
                                class="object-contain w-full h-full" />
                        </a>
                        <!-- product contents -->
                        <div class="flex flex-col items-center text-black prod-details">
                            <div class="z-20 flex flex-col items-center gap-1">
                                <h3 class="text-sm font-medium sm:text-xl xsm:text-lg line-clamp-1">
                                    <a href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-sm sm:text-base">(local shop)</p>
                                <p class="text-jet-gray">{{ $product->quantity }} {{ $product->unit->short_name }}.</p>
                                <h4 class="text-sm sm:text-sm">
                                    {{-- 14.<sup class="text-xs align-middle">29$</sup> --}}
                                    {{ currency($product->selling_price) }}
                                </h4>
                            </div>
                            <!-- user action btns -->
                            <div
                                class="action-btn bg-theme-light group-hover/product-card:bg-slime-green w-full after:content-[''] after:block after:w-full after:h-32 after:absolute after:bottom-[20%] after:left-0 after:rounded-[35%] sm:after:rounded-[45%] after:!z-[0] z-10 after:bg-white -mt-5 sm:rounded-b-xl rounded-b-lg eq">
                                <p class="flex items-center justify-center gap-3 pb-3 mt-8 xsm:gap-5">
                                    <button
                                        class="flex items-center justify-center w-5 h-5 text-base border border-black rounded-full sm:w-7 sm:h-7 sm:text-lg hover:bg-black hover:text-white eq">
                                        —
                                    </button>
                                    <span class="text-lg font-medium sm:text-xl">01</span>
                                    <button
                                        class="flex items-center justify-center w-5 h-5 text-base border border-black rounded-full sm:w-7 sm:h-7 sm:text-lg hover:bg-black hover:text-white eq">
                                        +
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Load More Btn -->
            <div class="mt-10 text-center load-more-btn">
                <button
                    class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                    type="button">
                    <span>Load More</span>
                    <i class="text-sm fa-solid fa-chevron-down"></i>
                </button>
            </div>
        </section>
        <!-- Page Main Content Ended -->
    </main>
@endsection
