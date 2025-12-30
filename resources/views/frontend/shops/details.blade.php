@extends('frontend.layouts.app')
@section('title', $seller->business_name)

@section('content')

    {{-- <div class="bg-gray-50 min-h-screen pb-12 font-sans">
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="relative w-full h-48 md:h-64 bg-gray-300 overflow-hidden group">
            @if ($seller->banner_images->isNotEmpty())
            <img src="{{ storage_url($seller->banner_images->first()->image) }}"
alt="Banner"
class="w-full h-full object-cover">
@else
<div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
    <i class="fas fa-store text-gray-600 text-6xl opacity-30"></i>
</div>
@endif
</div>

<div class="container mx-auto px-4">
    <div class="relative flex flex-col md:flex-row items-start md:items-end gap-6 pb-4">
        <div class="relative -mt-12 md:-mt-16 flex-shrink-0 z-10 mx-auto md:mx-0">
            <div class="w-24 h-24 md:w-36 md:h-36 rounded-xl border-4 border-white bg-white shadow-lg overflow-hidden">
                <img src="{{ storage_url($seller->business_logo) }}"
                    alt="Logo"
                    class="w-full h-full object-contain">
            </div>
        </div>

        <div class="flex-1 text-center md:text-left w-full pt-2 md:pt-6 md:pb-2">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        {{ $seller->business_name }}
                    </h1>
                    <div class="flex items-center justify-center md:justify-start gap-4 mt-2 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="font-bold text-gray-900">{{ number_format($avgRating, 2) }}</span>
                            <span class="hidden sm:inline">Rating</span>
                        </div>
                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                        <span><span class="font-bold text-gray-900">{{ number_shorten_format($seller->totalReviews) }}</span> Reviews</span>
                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                        <span><span class="font-bold text-gray-900">{{ number_shorten_format($seller->total_followers) }}</span> Followers</span>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-3">
                    <button class="bg-primary-600 text-white px-6 py-2 rounded-full font-medium hover:bg-primary-700 transition shadow-sm text-sm">
                        Follow
                    </button>
                    <button class="border border-gray-300 text-gray-700 px-6 py-2 rounded-full font-medium hover:bg-gray-50 transition text-sm">
                        Message
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 border-t border-gray-100">
        <nav class="flex gap-6 overflow-x-auto no-scrollbar">
            <a href="{{ route('sellers.shop', $seller->username) }}"
                class="py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap
                       {{ request()->routeIs('sellers.shop') ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                Products
            </a>
            <a href="{{ route('sellers.reviews', $seller->username) }}"
                class="py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap
                       {{ request()->routeIs('sellers.reviews') ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                Reviews
            </a>
            <a href="#" class="py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition-colors whitespace-nowrap">
                About Shop
            </a>
        </nav>
    </div>
</div>
</div>

<div class="container mx-auto mt-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <h2 class="font-bold text-gray-800">All Products</h2>
                <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $totalItem }}</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" placeholder="Search products..."
                        class="w-full sm:w-64 pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                </div>
                <form method="GET" action="{{ route('sellers.shop', $seller->username) }}">
                    <select name="sortBy" onchange="this.form.submit()"
                        class="w-full sm:w-auto pl-3 pr-8 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500 cursor-pointer text-gray-700">
                        <option value="" disabled {{ request('sortBy') == '' ? 'selected' : '' }}>Sort By</option>
                        <option value="relevance" {{ request('sortBy') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                        <option value="new-arrivals" {{ request('sortBy') == 'new-arrivals' ? 'selected' : '' }}>Newest</option>
                        <option value="best-selling" {{ request('sortBy') == 'best-selling' ? 'selected' : '' }}>Best Selling</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="p-4 md:p-6">
            @if ($products->count() > 0)
            <div id="product-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>

            <div class="mt-8 text-center">
                <button id="loadMoreBtn"
                    data-page="1"
                    data-url="{{ route('sellers.shop', $seller->username) }}?sortBy={{ request()->sortBy }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-primary-600 bg-white border border-primary-200 rounded-full hover:bg-primary-50 transition-colors">
                    <span>Load More</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>
            @else
            <div class="py-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                    <i class="fas fa-box-open text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No products found</h3>
                <p class="text-gray-500">Try adjusting your search query.</p>
            </div>
            @endif
        </div>
    </div>
</div>
</div> --}}

    <div class="bg-gray-50 min-h-screen pb-12 font-sans">
        <div class="bg-white shadow-sm border-b border-gray-200">
            {{-- Banner Image --}}
            <div class="relative w-full h-48 md:h-64 bg-gray-300 overflow-hidden group">
                @if ($seller->cover_image)
                    <img src="{{ storage_url($seller->cover_image) }}" alt="{{ $seller->name }} Banner"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center">
                        <i class="fas fa-store text-gray-600 text-6xl opacity-30"></i>
                    </div>
                @endif
            </div>

            <div class="container mx-auto px-4">
                <div class="relative flex flex-col md:flex-row items-start md:items-end gap-6 pb-4">
                    <div class="relative -mt-12 md:-mt-16 flex-shrink-0 z-10 mx-auto md:mx-0">
                        <div
                            class="w-24 h-24 md:w-36 md:h-36 rounded-xl border-4 border-white bg-white shadow-lg overflow-hidden">
                            <img src="{{ storage_url($seller->business_logo) }}" alt="Logo"
                                class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left w-full pt-2 md:pt-6 md:pb-2">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                                    {{ $seller->business_name }}
                                </h1>
                                <div
                                    class="flex items-center justify-center md:justify-start gap-4 mt-2 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-star text-yellow-400"></i>
                                        <span class="font-bold text-gray-900">{{ number_format($seller->rating, 2) }}</span>
                                        <span class="hidden sm:inline">Rating</span>
                                    </div>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span><span
                                            class="font-bold text-gray-900">{{ number_shorten_format($seller->rating_count) }}</span>
                                        Reviews</span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span><span
                                            class="followers-count font-bold text-gray-900">{{ number_shorten_format($seller->total_followers) }}</span>
                                        Followers</span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center justify-center gap-3">
                                @auth
                                    <button data-url="{{ route('sellers.follow', $seller->username) }}"
                                        class="follow-btn relative bg-primary-600 text-white px-6 py-2 rounded-full font-medium transition shadow-sm text-sm overflow-hidden">

                                        <span class="btn-text">
                                            {{ $alreadyFollowed ? 'Unfollow' : 'Follow' }}
                                        </span>

                                        <span class="btn-loader hidden absolute inset-0 flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" fill="none" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                            </svg>
                                        </span>
                                    </button>
                                @endauth
                                <button
                                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-full font-medium hover:bg-gray-50 transition text-sm">
                                    Message
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-100">
                    <nav class="flex gap-6 overflow-x-auto no-scrollbar" id="shop-tabs">
                        {{-- Products Tab (Default Active) --}}
                        <a href="#products" data-target="products"
                            class="tab-link py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap 
                        border-primary-600 text-primary-600">
                            Products
                        </a>

                        {{-- Reviews Tab --}}
                        <a href="#reviews" data-target="reviews"
                            class="tab-link py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap 
                        border-transparent text-gray-500 hover:text-gray-800">
                            Reviews
                        </a>

                        {{-- About Shop Tab --}}
                        <a href="#about" data-target="about"
                            class="tab-link py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition-colors whitespace-nowrap">
                            About Shop
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA --}}
        <div class="container mx-auto mt-6">

            {{-- PRODUCTS CONTENT (Default Visible) --}}
            <div id="products-content" class="tab-content bg-white rounded-xl shadow-sm border border-gray-200">

                {{-- Toolbar (Search + Sort) --}}
                <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold text-gray-800">All Products</h2>
                        <span
                            class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $totalItem }}</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative">
                            <input type="text" placeholder="Search products..."
                                class="w-full sm:w-64 pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        </div>
                        {{-- Note: This form submission WILL cause a reload. You need JS to handle filtering without reload --}}
                        <form method="GET" action="{{ route('sellers.shop', $seller->username) }}">
                            <select name="sortBy" onchange="this.form.submit()"
                                class="w-full sm:w-auto pl-3 pr-8 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg
                                    focus:border-primary-500 focus:ring-1 focus:ring-primary-500 cursor-pointer text-gray-700">

                                <option value="new-arrivals"
                                    {{ request('sortBy', 'new-arrivals') === 'new-arrivals' ? 'selected' : '' }}>
                                    Newest
                                </option>

                                <option value="popular" {{ request('sortBy') === 'popular' ? 'selected' : '' }}>
                                    Popular
                                </option>

                                <option value="low-to-high" {{ request('sortBy') === 'low-to-high' ? 'selected' : '' }}>
                                    Price (Low to High)
                                </option>

                                <option value="high-to-low" {{ request('sortBy') === 'high-to-low' ? 'selected' : '' }}>
                                    Price (High to Low)
                                </option>
                            </select>
                        </form>

                    </div>
                </div>

                {{-- Product Grid --}}
                <div class="p-4 md:p-6">
                    @if ($products->count() > 0)
                        <div id="product-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @include('frontend.partials.product-card-load', ['products' => $products])
                        </div>

                        <div class="mt-8 text-center">
                            <button id="loadMoreBtn" data-page="1"
                                data-url="{{ route('sellers.shop', $seller->username) }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-primary-600 bg-white border border-primary-200 rounded-full hover:bg-primary-50 transition-colors">
                                <span>Load More</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                <i class="fas fa-box-open text-gray-300 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">No products found</h3>
                            <p class="text-gray-500">Try adjusting your search query.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- REVIEWS CONTENT (Hidden by default) --}}
            <div id="reviews-content" class="tab-content hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Customer Reviews</h2>
                <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg">
                    <p class="text-gray-700">This section displays customer reviews, filters, and summary charts.</p>
                    <p class="mt-2 text-sm text-gray-500">Total Reviews: <span
                            class="font-semibold">{{ $seller->rating_count }}</span> | Average Rating: <span
                            class="font-semibold">{{ number_format($seller->rating, 2) }}</span></p>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach ($seller->reviews as $review)
                        <div class="border-b pb-4">
                            <p class="font-semibold text-gray-900">{{ $review->user->name }}</p>

                            {{-- Dynamic Stars --}}
                            <div class="text-yellow-500 text-sm">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>

                            <p class="text-gray-700 text-sm">{{ $review->description }}</p>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- ABOUT SHOP CONTENT --}}
            <div id="about-content" class="tab-content hidden bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">About {{ $seller->business_name }}</h2>
                <div class="space-y-4 text-gray-700">
                    <p class="text-lg font-medium border-l-4 border-primary-500 pl-3 italic text-gray-600">
                        {{ $seller->business_description ?? 'This seller has not provided a business description yet.' }}
                    </p>
                    <ul class="list-disc list-inside space-y-1 pt-4 text-gray-700">
                        <li>**Joined:** <span class="font-medium">{{ $seller->created_at->format('M d, Y') }}</span></li>
                        <li>**Location:** <span class="font-medium">{{ optional($seller->district)->name }} ,
                                {{ optional($seller->division)->name }}</span></li>
                        <li>**Total Items:** <span class="font-medium">{{ $totalItem }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.follow-btn', function() {
                let btn = $(this);
                let url = btn.data('url');

                let text = btn.find('.btn-text');
                let loader = btn.find('.btn-loader');

                if (btn.prop('disabled')) return;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend() {
                        btn.prop('disabled', true);
                        text.addClass('opacity-0');
                        loader.removeClass('hidden');
                    },
                    success(res) {
                        if (res.data.following) {
                            text.text('Unfollow');
                            btn.removeClass('bg-primary-600')
                                .addClass('bg-gray-600');
                        } else {
                            text.text('Follow');
                            btn.removeClass('bg-gray-600')
                                .addClass('bg-primary-600');
                        }

                        $('.followers-count').text(res.data.total_followers);
                    },
                    error(xhr) {
                        alert(xhr.responseJSON?.message ?? 'Something went wrong');
                    },
                    complete() {
                        loader.addClass('hidden');
                        text.removeClass('opacity-0');
                        btn.prop('disabled', false);
                    }
                });
            });

            $('.more-toggle').on('click', function(e) {
                e.preventDefault();
                $(this).next('.dropdown-menu').toggle();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.more-dropdown-wrapper').length) {
                    $('.dropdown-menu').hide();
                }
            });
        });
    </script>

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
                        '<i class="fa fa-spinner fa-spin"></i> Loading...'
                    );
                },
                success: function(response) {
                    if (response.trim() !== '') {
                        $('#product-list').append(response);

                        button.data('page', page);
                        button.prop('disabled', false).html(
                            '<span>Load More</span> <i class="fa-solid fa-chevron-down text-sm"></i>'
                        );

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
                                console.error('Invalid quickview JSON format:', e);
                            }
                        });

                        if (typeof initFlowbite === 'function') {
                            initFlowbite();
                        }

                        if (typeof initQuickViewModals === 'function') {
                            initQuickViewModals();
                        }

                        if (typeof initProductSwipers === 'function') {
                            initProductSwipers();
                        }
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabsContainer = document.getElementById('shop-tabs');
            const tabLinks = tabsContainer.querySelectorAll('.tab-link');
            const contentPanels = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('data-target');

                    // 1. Deactivate all tabs visually
                    tabLinks.forEach(tab => {
                        // Remove active styling
                        tab.classList.remove('border-primary-600', 'text-primary-600');
                        // Add inactive styling
                        tab.classList.add('border-transparent', 'text-gray-500',
                            'hover:text-gray-800');
                    });

                    // 2. Activate the clicked tab visually
                    this.classList.add('border-primary-600', 'text-primary-600');
                    this.classList.remove('border-transparent', 'text-gray-500',
                        'hover:text-gray-800');

                    // 3. Hide all content panels
                    contentPanels.forEach(panel => {
                        panel.classList.add('hidden');
                    });

                    // 4. Show the target panel
                    const targetPanel = document.getElementById(targetId + '-content');
                    if (targetPanel) {
                        targetPanel.classList.remove('hidden');
                    }

                    // Optional: Update URL hash without causing a page reload
                    history.pushState(null, '', '#' + targetId);
                });
            });

            // Handle initial load based on URL hash (if refreshing on a specific tab)
            const initialHash = window.location.hash.substring(1) || 'products';
            const initialTab = document.querySelector(`.tab-link[data-target="${initialHash}"]`);

            if (initialTab) {
                // Manually trigger click behavior to set initial state correctly
                initialTab.click();
            }
        });
    </script>
@endpush
