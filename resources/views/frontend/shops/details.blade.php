@extends('frontend.layouts.app')
@section('title', $seller->business_name)

@section('content')
    <div class="max-w-[1400px] mx-auto px-2 sm:px-4">
        {{-- Banner --}}
        <div class="relative h-44 sm:h-56 rounded-sm overflow-hidden bg-[#F5F5F5]">
            @if ($seller->cover_image)
                <img src="{{ storage_url($seller->cover_image) }}" alt="{{ $seller->business_name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-[#F85606]/10 to-[#C43D00]/10 flex items-center justify-center">
                    <i class="fas fa-store text-[#F85606]/20 text-6xl sm:text-8xl"></i>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>

            {{-- Logo overlay --}}
            <div class="absolute bottom-4 sm:bottom-5 left-4 sm:left-6 flex items-end gap-3 sm:gap-4">
                <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full border-[3px] border-white shadow-md bg-white overflow-hidden flex-shrink-0">
                    <img src="{{ $seller->business_logo ? storage_url($seller->business_logo) : asset('assets/frontend/images/placeholder.png') }}"
                        alt="{{ $seller->business_name }}" class="w-full h-full object-cover">
                </div>
                <div class="pb-1 hidden sm:block">
                    <h1 class="text-white text-xl font-bold drop-shadow-sm">{{ $seller->business_name }}</h1>
                    <div class="flex items-center gap-2 text-white/80 text-xs mt-0.5">
                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-star text-yellow-300 text-[11px]"></i>
                            <span class="font-semibold text-white">{{ number_format($seller->rating, 1) }}</span>
                            <span>({{ number_shorten_format($seller->rating_count) }})</span>
                        </div>
                        <span class="w-px h-3 bg-white/30"></span>
                        <span class="followers-count font-semibold text-white">{{ number_shorten_format($seller->total_followers) }}</span>
                        <span>Followers</span>
                        <span class="w-px h-3 bg-white/30"></span>
                        <span>{{ $totalItem }} Products</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile shop name + stats --}}
        <div class="sm:hidden pt-3 pb-3 text-center">
            <div class="flex items-center justify-center gap-1.5">
                <h1 class="text-base font-bold text-[#191919]">{{ $seller->business_name }}</h1>
                <i class="fa-solid fa-circle-check text-blue-500 text-sm" title="Verified Seller"></i>
            </div>
            <div class="flex items-center justify-center gap-2 mt-1 text-xs text-[#767676]">
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-star text-yellow-400 text-[11px]"></i>
                    <span class="font-semibold text-[#191919]">{{ number_format($seller->rating, 1) }}</span>
                    <span>({{ number_shorten_format($seller->rating_count) }})</span>
                </div>
                <span class="w-px h-3 bg-[#E5E5E5]"></span>
                <span class="followers-count font-semibold text-[#191919]">{{ number_shorten_format($seller->total_followers) }}</span>
                <span>Followers</span>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="flex items-center gap-2 mb-5 sm:mb-5">
            @auth
                <button data-url="{{ route('sellers.follow', $seller->username) }}"
                    class="follow-btn h-9 px-5 rounded-sm text-xs font-semibold transition-colors duration-100 flex items-center gap-1.5 overflow-hidden
                    {{ $alreadyFollowed ? 'bg-[#F5F5F5] text-[#595959] border border-[#E5E5E5] hover:bg-[#F85606] hover:text-white hover:border-[#F85606]' : 'bg-[#F85606] text-white hover:bg-[#C43D00]' }}">
                    <span class="btn-text">{{ $alreadyFollowed ? 'Unfollow' : 'Follow' }}</span>
                    <span class="btn-loader hidden">
                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>
                    </span>
                    <i class="fa-solid fa-plus text-[11px] {{ $alreadyFollowed ? 'hidden' : '' }}"></i>
                </button>
            @endauth
        </div>

        {{-- Tabs --}}
        <div class="border-b border-[#E5E5E5] mb-5">
            <nav class="flex gap-0 -mb-px overflow-x-auto no-scrollbar" id="shop-tabs">
                <a href="#products" data-target="products"
                    class="tab-link px-4 py-2.5 text-xs font-semibold border-b-2 transition-colors duration-100 whitespace-nowrap border-[#F85606] text-[#F85606]">
                    Products
                </a>
                <a href="#reviews" data-target="reviews"
                    class="tab-link px-4 py-2.5 text-xs font-semibold border-b-2 transition-colors duration-100 whitespace-nowrap border-transparent text-[#767676] hover:text-[#191919]">
                    Reviews
                </a>
                <a href="#about" data-target="about"
                    class="tab-link px-4 py-2.5 text-xs font-semibold border-b-2 transition-colors duration-100 whitespace-nowrap border-transparent text-[#767676] hover:text-[#191919]">
                    About Shop
                </a>
            </nav>
        </div>

        {{-- PRODUCTS TAB --}}
        <div id="products-content" class="tab-content">
            <div class="bg-white border border-[#E5E5E5] rounded-sm mb-6">
                <div class="p-3 sm:p-4 border-b border-[#E5E5E5] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-semibold text-[#191919]">All Products</h2>
                        <span class="bg-[#F5F5F5] text-[#767676] text-[10px] font-semibold px-1.5 py-0.5 rounded-xs">{{ $totalItem }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="GET" action="{{ route('sellers.shop', $seller->username) }}">
                            <select name="sortBy" onchange="this.form.submit()"
                                class="h-8 text-xs border border-[#E5E5E5] rounded-sm px-2 pr-6 focus:outline-none focus:border-[#F85606] text-[#595959] bg-white cursor-pointer appearance-none">
                                <option value="new-arrivals" {{ request('sortBy', 'new-arrivals') === 'new-arrivals' ? 'selected' : '' }}>Newest</option>
                                <option value="popular" {{ request('sortBy') === 'popular' ? 'selected' : '' }}>Popular</option>
                                <option value="low-to-high" {{ request('sortBy') === 'low-to-high' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="high-to-low" {{ request('sortBy') === 'high-to-low' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="p-3 sm:p-4">
                    @if ($products->count() > 0)
                        <div id="product-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                            @include('frontend.partials.product-card-load', ['products' => $products])
                        </div>
                        @if ($totalItem >= 48)
                            <div class="mt-6 text-center">
                                <button id="loadMoreBtn" data-page="1"
                                    data-url="{{ route('sellers.shop', $seller->username) }}"
                                    class="inline-flex items-center gap-2 px-6 py-2 border border-[#F85606] text-[#F85606] text-xs font-semibold rounded-sm hover:bg-[#F85606] hover:text-white transition-colors duration-100"
                                    type="button">
                                    <span>Load More</span>
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="py-12 text-center">
                            <div class="w-12 h-12 rounded-full bg-[#F5F5F5] flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-box-open text-[#C7C7C7] text-lg"></i>
                            </div>
                            <p class="text-sm font-medium text-[#191919]">No products found</p>
                            <p class="text-xs text-[#767676] mt-0.5">Try adjusting your search or filter.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- REVIEWS TAB --}}
        <div id="reviews-content" class="tab-content hidden">
            <div class="bg-white border border-[#E5E5E5] rounded-sm p-4 sm:p-5 mb-6">
                <h2 class="text-sm font-semibold text-[#191919] mb-4">Customer Reviews</h2>
                <div class="flex items-center gap-4 p-3 bg-[#FAFAFA] border border-[#E5E5E5] rounded-sm mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#191919]">{{ number_format($seller->rating, 1) }}</div>
                        <div class="flex text-yellow-400 text-[10px] mt-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($seller->rating))
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-[10px] text-[#767676] mt-0.5">{{ $seller->rating_count }} reviews</div>
                    </div>
                </div>

                @if ($seller->reviews && $seller->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach ($seller->reviews as $review)
                            <div class="border-b border-[#E5E5E5] pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-6 h-6 rounded-full bg-[#F5F5F5] flex items-center justify-center overflow-hidden">
                                        @if ($review->user && $review->user->image)
                                            <img src="{{ storage_url($review->user->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-user text-[10px] text-[#C7C7C7]"></i>
                                        @endif
                                    </div>
                                    <span class="text-xs font-medium text-[#191919]">{{ $review->user->name ?? 'Anonymous' }}</span>
                                    <span class="text-[10px] text-[#767676]">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex text-yellow-400 text-[10px] mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-xs text-[#595959] leading-relaxed">{{ $review->description }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-[#767676] text-center py-6">No reviews yet.</p>
                @endif
            </div>
        </div>

        {{-- ABOUT TAB --}}
        <div id="about-content" class="tab-content hidden">
            <div class="bg-white border border-[#E5E5E5] rounded-sm p-4 sm:p-5 mb-6">
                <h2 class="text-sm font-semibold text-[#191919] mb-3">About {{ $seller->business_name }}</h2>
                    <p class="text-xs text-[#595959] border-l-[3px] border-[#F85606] pl-3 italic leading-relaxed">
                    {{ $seller->business_description ?? 'This seller has not provided a business description yet.' }}
                </p>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-[#595959]">
                    <div class="flex items-center gap-2 p-2 bg-[#FAFAFA] rounded-sm">
                        <i class="fa-regular fa-calendar text-[#F85606]"></i>
                        <span>Joined <span class="font-medium text-[#191919]">{{ $seller->created_at->format('M d, Y') }}</span></span>
                    </div>
                    <div class="flex items-center gap-2 p-2 bg-[#FAFAFA] rounded-sm">
                        <i class="fa-solid fa-location-dot text-[#F85606]"></i>
                        <span>{{ optional($seller->district)->name }}{{ $seller->district && $seller->division ? ', ' : '' }}{{ optional($seller->division)->name }}</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 bg-[#FAFAFA] rounded-sm">
                        <i class="fa-solid fa-box text-[#F85606]"></i>
                        <span><span class="font-medium text-[#191919]">{{ $totalItem }}</span> Products</span>
                    </div>
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
                    data: { _token: '{{ csrf_token() }}' },
                    beforeSend() {
                        btn.prop('disabled', true);
                        text.addClass('opacity-0');
                        loader.removeClass('hidden');
                    },
                    success(res) {
                        if (res.data.following) {
                            text.text('Unfollow');
                            btn.removeClass('bg-[#F85606] text-white hover:bg-[#C43D00]')
                                .addClass('bg-[#F5F5F5] text-[#595959] border border-[#E5E5E5] hover:bg-[#F85606] hover:text-white hover:border-[#F85606]');
                        } else {
                            text.text('Follow');
                            btn.removeClass('bg-[#F5F5F5] text-[#595959] border border-[#E5E5E5] hover:bg-[#F85606] hover:text-white hover:border-[#F85606]')
                                .addClass('bg-[#F85606] text-white hover:bg-[#C43D00]');
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

            // Load More
            $('#loadMoreBtn').on('click', function() {
                let button = $(this);
                let page = parseInt(button.data('page')) + 1;
                let url = button.data('url');

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: { page: page },
                    beforeSend: function() {
                        button.prop('disabled', true).html(
                            '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                        );
                    },
                    success: function(response) {
                        if (response.trim() !== '') {
                            $('#product-list').append(response);
                            button.data('page', page);
                            button.prop('disabled', false).html(
                                '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                            );
                            if (typeof initProductSwipers === 'function') initProductSwipers();
                        } else {
                            button.hide();
                        }
                    },
                    error: function() {
                        button.prop('disabled', false).html(
                            '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                        );
                    }
                });
            });

            // Tabs
            document.getElementById('shop-tabs').addEventListener('click', function(e) {
                const link = e.target.closest('.tab-link');
                if (!link) return;
                e.preventDefault();

                document.querySelectorAll('.tab-link').forEach(t => {
                    t.classList.remove('border-[#F85606]', 'text-[#F85606]');
                    t.classList.add('border-transparent', 'text-[#767676]', 'hover:text-[#191919]');
                });
                link.classList.add('border-[#F85606]', 'text-[#F85606]');
                link.classList.remove('border-transparent', 'text-[#767676]', 'hover:text-[#191919]');

                document.querySelectorAll('.tab-content').forEach(p => p.classList.add('hidden'));
                const target = document.getElementById(link.getAttribute('data-target') + '-content');
                if (target) target.classList.remove('hidden');

                history.pushState(null, '', '#' + link.getAttribute('data-target'));
            });
        });
    </script>
@endpush
