@extends('frontend.layouts.app')
@section('title', 'Slash Mart')

@section('content')

    <?php
    $promoBanner = $banners->get(\App\Domain\Product\Models\Banner::SECTION_PROMO_MODAL)?->first();
        ?>
    @if ($promoBanner)
        <x-frontend.promoModal :banner="$promoBanner" />
    @endif

    @include('frontend.partials.hero-banners')

    <!-- ==================== VALUE PROPS STRIP ==================== -->
    <section class="pb-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 bg-white p-4 sm:p-5 border border-[#E5E5E5] rounded-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#FFF1EA] flex items-center justify-center text-[#F85606] flex-shrink-0">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[#191919]">Fast Delivery</p>
                    <p class="text-xs text-[#767676]">All over Bangladesh</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#FFF1EA] flex items-center justify-center text-[#F85606] flex-shrink-0">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[#191919]">Secure Payment</p>
                    <p class="text-xs text-[#767676]">100% Safe Transaction</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#FFF1EA] flex items-center justify-center text-[#F85606] flex-shrink-0">
                    <i class="fas fa-undo"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[#191919]">Easy Return</p>
                    <p class="text-xs text-[#767676]">7 Days Return Policy</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#FFF1EA] flex items-center justify-center text-[#F85606] flex-shrink-0">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-[#191919]">24/7 Support</p>
                    <p class="text-xs text-[#767676]">Dedicated Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FLASH SALE ==================== -->
    @if ($flash_sales)
        @foreach ($flash_sales as $flash_sale)
            <section class="mb-4">
                <div class="bg-white border border-[#E5E5E5] rounded-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-[#F85606] to-[#E04800] px-4 sm:px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                                <i class="fas fa-bolt"></i>
                                <span>{{ $flash_sale->title }}</span>
                            </h2>
                            <div class="hidden sm:flex items-center gap-1.5 text-white text-xs font-bold" id="countdown-{{ $flash_sale->id }}">
                                <div class="flex flex-col items-center">
                                    <span class="bg-white/20 backdrop-blur px-2 py-1 min-w-[2rem] text-center rounded" id="days-{{ $flash_sale->id }}">00</span>
                                    <span class="text-[9px] mt-0.5 text-white/70">Days</span>
                                </div>
                                <span class="text-white/80 font-normal mt-[-1rem]">:</span>
                                <div class="flex flex-col items-center">
                                    <span class="bg-white/20 backdrop-blur px-2 py-1 min-w-[2rem] text-center rounded" id="hours-{{ $flash_sale->id }}">00</span>
                                    <span class="text-[9px] mt-0.5 text-white/70">Hours</span>
                                </div>
                                <span class="text-white/80 font-normal mt-[-1rem]">:</span>
                                <div class="flex flex-col items-center">
                                    <span class="bg-white/20 backdrop-blur px-2 py-1 min-w-[2rem] text-center rounded" id="mins-{{ $flash_sale->id }}">00</span>
                                    <span class="text-[9px] mt-0.5 text-white/70">Min</span>
                                </div>
                                <span class="text-white/80 font-normal mt-[-1rem]">:</span>
                                <div class="flex flex-col items-center">
                                    <span class="bg-white/20 backdrop-blur px-2 py-1 min-w-[2rem] text-center rounded" id="secs-{{ $flash_sale->id }}">00</span>
                                    <span class="text-[9px] mt-0.5 text-white/70">Sec</span>
                                </div>
                            </div>
                            <div class="sm:hidden flex items-center gap-1 text-white text-xs font-bold" id="countdown-mobile-{{ $flash_sale->id }}">
                                <span class="bg-white/20 backdrop-blur px-1.5 py-0.5 rounded" id="days-m-{{ $flash_sale->id }}">00</span>
                                <span class="text-white/60">:</span>
                                <span class="bg-white/20 backdrop-blur px-1.5 py-0.5 rounded" id="hours-m-{{ $flash_sale->id }}">00</span>
                                <span class="text-white/60">:</span>
                                <span class="bg-white/20 backdrop-blur px-1.5 py-0.5 rounded" id="mins-m-{{ $flash_sale->id }}">00</span>
                                <span class="text-white/60">:</span>
                                <span class="bg-white/20 backdrop-blur px-1.5 py-0.5 rounded" id="secs-m-{{ $flash_sale->id }}">00</span>
                            </div>
                        </div>
                        <a href="{{ route('flashSales.show', $flash_sale->id) }}"
                            class="text-xs font-medium text-white border border-white/40 px-3 py-1.5 rounded hover:bg-white hover:text-[#F85606] eq flex-shrink-0">See All</a>
                    </div>

                    <div class="p-4 sm:p-5 relative group/slider">
                        <button type="button" class="flash-slider-prev absolute left-1 top-1/2 -translate-y-1/2 z-20 w-9 h-9 flex items-center justify-center bg-white rounded-full shadow-md border border-[#E5E5E5] hover:bg-[#F85606] hover:text-white eq opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" class="flash-slider-next absolute right-1 top-1/2 -translate-y-1/2 z-20 w-9 h-9 flex items-center justify-center bg-white rounded-full shadow-md border border-[#E5E5E5] hover:bg-[#F85606] hover:text-white eq opacity-0 group-hover/slider:opacity-100 disabled:opacity-30 disabled:cursor-not-allowed transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div class="flex gap-3 overflow-hidden snap-x snap-mandatory scroll-smooth flash-slider-track">
                            @foreach ($flash_sale->approveProducts as $productItem)
                                <div class="snap-start flex-shrink-0">
                                    <x-frontend.flash-sale-card :product="$productItem->product" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    <!-- ==================== CATEGORY GRID ==================== -->
    <section class="mb-4">
        <div class="bg-white p-4 sm:p-5 border border-[#E5E5E5] rounded-sm">
            <div class="flex items-center gap-2 mb-5">
                <h2 class="text-base font-semibold text-[#191919]">Shop by Category</h2>
            </div>
            <div class="flex flex-wrap justify-between md:justify-center gap-y-5 gap-x-4 sm:gap-x-7">
                @foreach ($categories as $category)
                    <a href="{{ route('category.details', $category->slug) }}"
                        class="group flex flex-col items-center gap-2 w-16 sm:w-20">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#F5F5F5] border border-[#E5E5E5] group-hover:border-[#F85606] eq flex items-center justify-center p-3 overflow-hidden">
                            @if ($category->image)
                                <img src="{{ storage_url($category->image) }}" alt="{{ $category->name }}"
                                    class="max-w-full max-h-full object-contain group-hover:scale-110 eq">
                            @else
                                <i class="fas fa-tag text-[#C7C7C7] text-sm"></i>
                            @endif
                        </div>
                        <span class="text-[11px] sm:text-xs text-[#595959] text-center line-clamp-1 group-hover:text-[#F85606] eq">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==================== TOP SELLERS ==================== -->
    <section class="mb-4">
        <div class="bg-white p-4 sm:p-5 border border-[#E5E5E5] rounded-sm">
            <div class="flex items-center justify-between mb-4 px-1">
                <h2 class="text-base font-semibold text-[#191919]">Top Sellers</h2>
                <a href="{{ route('sellers.index') }}" class="text-xs font-medium text-[#F85606] hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 sm:gap-4">
                @foreach ($sellers as $seller)
                    <div class="group flex flex-col items-center bg-white border border-[#E5E5E5] rounded-sm p-3 sm:p-4 eq hover:border-[#F85606]">
                        <div class="relative w-16 h-16 rounded-full bg-[#F5F5F5] flex items-center justify-center p-2 mb-3">
                            <img src="{{ $seller->business_logo ? storage_url($seller->business_logo) : asset('assets/frontend/images/provider-logo-2.png') }}"
                                alt="{{ $seller->business_name }}" class="max-w-full max-h-full object-contain">
                            @if ($seller->is_best_seller)
                                <div class="absolute -bottom-1 -right-0.5 bg-white rounded-full">
                                    <svg class="w-4 h-4 text-[#FFA000]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="text-center w-full mb-3">
                            <h3 class="text-sm font-semibold text-[#191919] line-clamp-1 mb-1">{{ $seller->business_name }}</h3>
                            <div class="flex items-center justify-center gap-2 text-[10px] text-[#595959]">
                                <span class="flex items-center text-[#FFA000]">
                                    <i class="fas fa-star mr-1"></i> {{ number_format($seller->rating, 1) }}
                                </span>
                                <span class="text-[#E5E5E5]">|</span>
                                <span>{{ number_format($seller->followers_count ?? 0) }} Followers</span>
                            </div>
                        </div>

                        <a href="{{ route('sellers.shop', $seller->username) }}"
                            class="w-full text-center py-1.5 bg-[#FFF1EA] text-[#F85606] text-[11px] font-medium rounded hover:bg-[#F85606] hover:text-white eq">
                            Visit Shop
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==================== FEATURED PRODUCTS ==================== -->
    <section class="mb-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base sm:text-lg font-bold text-[#191919]">Featured <span class="text-[#F85606]">Products</span></h2>
            <a href="{{ route('products.index') }}" class="text-sm font-medium text-[#F85606] hover:underline">View All</a>
        </div>

        <div id="product-wrapper" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            @foreach ($products as $product)
                <x-frontend.product-card :product="$product" />
            @endforeach
        </div>

        @if ($products->count() >= 8)
            <div class="mt-6 text-center load-more-btn">
                <button data-page="1" data-url="{{ url()->current() }}" id="loadMoreProducts"
                    class="btn btn-secondary btn-sm eq" type="button">
                    <span>Load More</span>
                    <i class="text-xs fa-solid fa-chevron-down ml-1"></i>
                </button>
            </div>
        @endif
    </section>

    <!-- ==================== POPULAR BRANDS ==================== -->
    <section class="mb-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base sm:text-lg font-bold text-[#191919]">Popular <span class="text-[#F85606]">Brands</span></h2>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
            @foreach ($brands as $brand)
                <div class="border border-[#E5E5E5] bg-white p-4 flex flex-col items-center hover:shadow-sm eq">
                    <img src="{{ $brand->image ? storage_url($brand->image) : asset('assets/frontend/images/placeholder.png') }}"
                        alt="{{ $brand->name }}" class="h-10 mb-3 object-contain">
                    <span class="text-xs text-[#595959] font-medium">{{ $brand->name }}</span>
                </div>
            @endforeach
        </div>
    </section>



@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Promo popup
            const promoPopup = document.getElementById('promoPopup');
            const closePromoBtns = document.querySelectorAll('#closePromoBtn, .close-promo-trigger');

            if (!sessionStorage.getItem('promoShown')) {
                if (promoPopup) {
                    promoPopup.classList.remove('hidden');
                    setTimeout(() => promoPopup.style.opacity = '1', 10);
                    sessionStorage.setItem('promoShown', 'true');
                }
            }

            if (promoPopup) {
                closePromoBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        promoPopup.style.opacity = '0';
                        setTimeout(() => promoPopup.remove(), 300);
                    });
                });
            }

            // Flash sale slider
            document.querySelectorAll('.flash-slider-track').forEach(track => {
                const wrapper = track.parentElement;
                const prev = wrapper.querySelector('.flash-slider-prev');
                const next = wrapper.querySelector('.flash-slider-next');
                const scrollAmount = () => {
                    const card = track.querySelector('.flex-shrink-0');
                    return card ? card.offsetWidth + 12 : 200;
                };
                const updateBtns = () => {
                    if (prev) prev.disabled = track.scrollLeft <= 0;
                    if (next) next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
                };
                if (prev) prev.addEventListener('click', () => { track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' }); setTimeout(updateBtns, 100); });
                if (next) next.addEventListener('click', () => { track.scrollBy({ left: scrollAmount(), behavior: 'smooth' }); setTimeout(updateBtns, 100); });
                track.addEventListener('scroll', updateBtns);
                setTimeout(updateBtns, 200);

                // Auto-play
                let autoplay = setInterval(() => {
                    if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2) {
                        track.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
                    }
                }, 1500);
                wrapper.addEventListener('mouseenter', () => clearInterval(autoplay));
                wrapper.addEventListener('mouseleave', () => {
                    autoplay = setInterval(() => {
                        if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2) {
                            track.scrollTo({ left: 0, behavior: 'smooth' });
                        } else {
                            track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
                        }
                    }, 1500);
                });
            });

            // Flash sale countdown
            @if ($flash_sales)
                @foreach ($flash_sales as $flash_sale)
                    (function() {
                        const endTime = new Date('{{ $flash_sale->end_time }}').getTime();
                        function pad(n) { return String(n).padStart(2, '0'); }
                        function updateCountdown() {
                            const now = new Date().getTime();
                            const diff = endTime - now;
                            if (diff <= 0) {
                                const ended = '<span class="text-white/70 text-xs font-medium">Ended</span>';
                                const el = document.getElementById('countdown-{{ $flash_sale->id }}');
                                const elM = document.getElementById('countdown-mobile-{{ $flash_sale->id }}');
                                if (el) el.innerHTML = ended;
                                if (elM) elM.innerHTML = ended;
                                return;
                            }
                            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            const secs = Math.floor((diff % (1000 * 60)) / 1000);
                            const ids = ['days', 'hours', 'mins', 'secs'];
                            const vals = [days, hours, mins, secs];
                            ids.forEach((id, i) => {
                                const el = document.getElementById(id + '-{{ $flash_sale->id }}');
                                const elM = document.getElementById(id + '-m-{{ $flash_sale->id }}');
                                const v = pad(vals[i]);
                                if (el) el.textContent = v;
                                if (elM) elM.textContent = v;
                            });
                        }
                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    })();
                @endforeach
            @endif

            // Load More Products
            $(document).on('click', '#loadMoreProducts', function () {
                const button = $(this);
                let page = parseInt(button.data('page')) + 1;
                const url = button.data('url');

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: { page: page },
                    beforeSend: function () {
                        button.prop('disabled', true).html(
                            '<i class="fa fa-spinner fa-spin"></i> Loading...'
                        );
                    },
                    success: function (response) {
                        if ($.trim(response) !== '') {
                            $('#product-wrapper').append(response);
                            button.data('page', page);
                            button.prop('disabled', false).html(
                                '<span>Load More</span> <i class="fa-solid fa-chevron-down text-sm"></i>'
                            );
                        } else {
                            button.hide();
                        }
                    },
                    error: function () {
                        button.prop('disabled', false).text('Load More');
                        showErrorToast('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
