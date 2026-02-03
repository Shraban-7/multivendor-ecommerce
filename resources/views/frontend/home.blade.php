@extends('frontend.layouts.app')
@section('title', 'Slash Mart')

@section('content')

    <?php
    $promoBanner = $banners->get(\App\Models\Banner::SECTION_PROMO_MODAL)?->first();
        ?>
    @if ($promoBanner)
        <x-frontend.promoModal :banner="$promoBanner" />
    @endif

    @include('frontend.partials.hero-banners')

    <!-- ==================== 15. WHY CHOOSE US ==================== -->
    <section class="py-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 p-2">
                <div class="bg-orange-50 w-12 h-12 rounded-full flex items-center justify-center text-primary-600 text-xl">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Fast Delivery</h4>
                    <p class="text-xs text-gray-500">All over Bangladesh</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-2">
                <div class="bg-blue-50 w-12 h-12 rounded-full flex items-center justify-center text-blue-600 text-xl"><i
                        class="fas fa-shield-alt"></i></div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Secure Payment</h4>
                    <p class="text-xs text-gray-500">100% Safe Transaction</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-2">
                <div class="bg-green-50 w-12 h-12 rounded-full flex items-center justify-center text-green-600 text-xl"><i
                        class="fas fa-undo"></i></div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Easy Return</h4>
                    <p class="text-xs text-gray-500">7 Days Return Policy</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-2">
                <div class="bg-purple-50 w-12 h-12 rounded-full flex items-center justify-center text-purple-600 text-xl"><i
                        class="fas fa-headset"></i></div>
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">24/7 Support</h4>
                    <p class="text-xs text-gray-500">Dedicated Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== 5. CATEGORY GRID SECTION ==================== -->
    <section class="mb-4">
        <div class="bg-white p-6 rounded-2xl border border-gray-100">
            <div class="flex justify-center items-center mb-6">
                <h2 class="text-lg font-medium text-gray-800 tracking-tight">Shop by Category</h2>
            </div>
            <div class="flex flex-wrap justify-between md:justify-center gap-y-6 gap-x-4 sm:gap-x-8">
                @foreach ($categories as $category)
                    <a href="{{ route('category.details', $category->slug) }}"
                        class="group flex flex-col items-center gap-2 w-16 sm:w-20">
                        <div
                            class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden bg-gray-50 border border-gray-100 group-hover:border-primary-400 transition-colors duration-300 flex items-center justify-center p-3">
                            @if ($category->image)
                                <img src="{{ storage_url($category->image) }}" alt="{{ $category->name }}"
                                    class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <i class="fas fa-tag text-gray-300 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <span
                            class="text-[11px] sm:text-xs font-normal text-gray-600 text-center line-clamp-1 group-hover:text-primary-600 transition-colors">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ==================== 6. FLASH SALE SECTION ==================== -->
    @if ($flash_sales)
        @foreach ($flash_sales as $flash_sale)
            <section class="pb-5">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <!-- Header -->
                    <div class="flex flex-col md:flex-row items-center justify-between border-b border-gray-100 pb-4 mb-6 gap-4">
                        <div class="flex items-center gap-4">
                            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-bolt text-primary-500"></i><span
                                    class="text-primary-600">{{ $flash_sale->title }}</span>
                            </h2>
                            <div class="flex gap-2 items-center text-white text-xs font-bold">
                                <span class="bg-gray-800 p-1.5 rounded">05</span> :
                                <span class="bg-primary-600 p-1.5 rounded">32</span> :
                                <span class="bg-gray-800 p-1.5 rounded">45</span>
                            </div>
                        </div>
                        <a href="{{ route('flashSales.show', $flash_sale->id) }}"
                            class="text-sm font-semibold text-primary-600 border border-primary-600 px-4 py-1.5 rounded-full hover:bg-primary-600 hover:text-white transition">See
                            All Products</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                        @foreach ($flash_sale->approveProducts as $productItem)
                            {{-- <div
                                class="min-w-[200px] md:min-w-[240px] snap-start bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group relative">
                                <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                                    <span class="bg-primary-600 text-white text-[10px] font-bold px-2 py-1 rounded">-40%</span>
                                </div>
                                <div
                                    class="relative h-48 w-full bg-gray-100 rounded-t-xl overflow-hidden p-4 flex items-center justify-center">
                                    <img src="{{ $item->product->imageUrl }}"
                                        class="max-h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">
                                    <div
                                        class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2">
                                        <button class="btn-quickview w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg
                                                            flex items-center justify-center hover:bg-primary-600 hover:text-white
                                                            transform translate-y-4 group-hover:translate-y-0 transition delay-75"
                                            data-slug="{{ $item->product->slug }}">

                                            <i class="far fa-eye icon"></i>

                                            <span
                                                class="spinner hidden w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin">
                                            </span>
                                        </button>

                                        <button data-id="{{ $item->product->id }}"
                                            class="wishlistBtn w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-100"><i
                                                class="far fa-heart"></i></button>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="{{ route('products.details', $item->product->slug) }}">
                                        <h3
                                            class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-primary-600 cursor-pointer mb-1">
                                            {{ $item->product->name }}
                                        </h3>
                                    </a>
                                    <div class="flex items-center gap-1 mb-2">
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        <span class="text-xs text-gray-400">({{ $item->product->avg_rating }})</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if ($item->product->discounted_price)
                                        <span class="text-primary-600 font-bold text-lg">{{ money($item->product->discounted_price)
                                            }}</span>
                                        <span class="text-gray-400 text-xs line-through">{{ money($item->product->selling_price)
                                            }}</span>
                                        @else
                                        <span class="text-primary-600 font-bold text-lg">{{ money($item->product->selling_price)
                                            }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-3 pt-0">
                                    <button data-slug="{{ $item->product->slug }}"
                                        class="btn-quickview w-full py-2 rounded-lg bg-gray-100 text-gray-800 text-xs font-bold hover:bg-primary-600 hover:text-white transition group-hover:bg-primary-600 group-hover:text-white">Add
                                        To Cart</button>
                                </div>
                            </div> --}}

                            <x-frontend.flash-sale-card :productItem="$productItem" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @endif


    {{-- <section class="py-4">
        <div class="relative rounded-2xl overflow-hidden shadow-lg h-40 md:h-64 flex items-center bg-gray-900">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1596462502278-27bfdd403348?q=80&w=1200')] bg-cover bg-center opacity-40">
            </div>
            <div class="relative z-10 p-8 md:pl-16">
                <span class="text-yellow-400 font-bold tracking-widest text-sm uppercase mb-2 block">Pohela Boishakh
                    Special</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Flat 50% Discount <br>On Traditional Wear
                </h2>
                <a href="shop.html"
                    class="bg-white text-gray-900 px-6 py-2.5 rounded-lg font-bold hover:bg-primary-500 hover:text-white transition shadow-lg">Check
                    Offers</a>
            </div>
        </div>
    </section> --}}


    <section class="mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-3 px-2">
                <h2 class="text-lg font-medium text-gray-800 tracking-tight">Top Sellers</h2>
                <a href="{{ route('sellers.index') }}" class="text-xs font-medium text-primary-600 hover:underline">
                    View All
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 sm:gap-6">
                @foreach ($sellers as $seller)
                    <div
                        class="group flex flex-col items-center bg-white border border-gray-100 rounded-xl p-4 transition-all duration-300 hover:border-primary-500">

                        <div
                            class="relative w-16 h-16 rounded-full bg-gray-50 border border-gray-50 flex items-center justify-center p-2 mb-3">
                            <img src="{{ storage_url($seller->business_logo) }}" alt="{{ $seller->business_name }}"
                                class="max-w-full max-h-full object-contain">

                            <div class="absolute -bottom-1 -right-0.5 text-blue-500 bg-white rounded-full">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div class="text-center w-full mb-4">
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-1 mb-1">
                                {{ $seller->business_name }}
                            </h3>

                            <div class="flex items-center justify-center gap-2 text-[10px] font-medium">
                                <span class="flex items-center text-yellow-500">
                                    <i class="fas fa-star mr-1"></i> {{ $seller->rating }}
                                </span>
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-500">
                                    {{ number_format($seller->followers_count ?? 0) }} Followers
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center w-full gap-2">
                            <a href="{{ route('sellers.shop', $seller->username) }}"
                                class="flex-1 text-center py-1.5 bg-primary-100 text-primary-500 text-[11px] font-medium rounded-md hover:bg-primary-500 hover:text-white transition-colors">
                                Visit Shop
                            </a>

                            <button title="Follow Store"
                                class="flex items-center justify-center w-8 h-8 rounded-md border border-gray-200 text-gray-500 hover:border-primary-500 hover:text-primary-600 transition-all">
                                <i class="fas fa-user-plus text-[10px]"></i>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ==================== 9. FEATURED PRODUCTS (GRID) ==================== -->
    <section class="py-5">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured <span class="text-primary-600">Products</span></h2>

        <div id="product-wrapper" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($products as $product)
                <x-frontend.product-card :product="$product" />
            @endforeach
        </div>

        <div class="text-center mt-10">
            @if ($products->count() >= 8)
                <div class="mt-10 text-center load-more-btn">
                    <button data-page="1" data-url="{{ url()->current() }}" id="loadMoreProducts"
                        class="text-sm font-semibold text-primary-600 border border-primary-600 px-4 py-1.5 rounded-full hover:bg-primary-600 hover:text-white transition"
                        type="button">
                        <span>Load More</span>
                        <i class="text-sm fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            @endif
        </div>
    </section>

    <!-- ==================== 14. POPULAR BRANDS ==================== -->
    <section class="pb-5">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Popular <span class="text-primary-600">Brands</span></h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            @foreach ($brands as $brand)
                <div
                    class="border  border-gray-100 bg-white p-6 flex flex-col items-center shadow-sm transition-shadow hover:shadow-md ">
                    <img src="{{ $brand->image ? storage_url($brand->image) : asset('assets/frontend/images/default.png') }}"
                        alt="{{ $brand->name }}" class="h-12 mb-4">
                    <span class="text-gray-700 font-medium">{{ $brand->name }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <!-- END HERE -->

    <!-- ==================== 16. TESTIMONIALS ==================== -->
    {{-- <section class="bg-orange-50 py-12">
        <div class="">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">What Our Customers Say</h2>
                <p class="text-gray-500">Thousands of happy customers across Bangladesh</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Review Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm relative">
                    <div class="text-primary-500 text-4xl opacity-20 absolute top-4 right-4"><i
                            class="fas fa-quote-right"></i></div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="text-yellow-400 text-sm"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <p class="text-gray-600 text-sm italic mb-6">"Fast delivery within Dhaka. The product quality was
                        exactly as described. Highly recommended for authentic gadgets."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Rahim Uddin</h4>
                            <span class="text-xs text-gray-500">Dhaka, Bangladesh</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm relative">
                    <div class="text-primary-500 text-4xl opacity-20 absolute top-4 right-4"><i
                            class="fas fa-quote-right"></i></div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="text-yellow-400 text-sm"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    </div>
                    <p class="text-gray-600 text-sm italic mb-6">"Best prices I found online. The packaging was secure and
                        customer service helped me track my order."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Fatema Begum</h4>
                            <span class="text-xs text-gray-500">Chittagong, Bangladesh</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm relative">
                    <div class="text-primary-500 text-4xl opacity-20 absolute top-4 right-4"><i
                            class="fas fa-quote-right"></i></div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="text-yellow-400 text-sm"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm italic mb-6">"Excellent collection of traditional wear. Bought a
                        Panjabi for Eid and the fabric is premium."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                            <img src="https://randomuser.me/api/portraits/men/85.jpg" alt="User">
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Tanvir Ahmed</h4>
                            <span class="text-xs text-gray-500">Sylhet, Bangladesh</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- ==================== 17 & 18. NEWSLETTER & APP DOWNLOAD ==================== -->
    <section class=" py-12">
        <div
            class="bg-gray-900 rounded-3xl p-8 md:p-12 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
            <!-- Decor Circles -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-primary-600 opacity-20 blur-3xl">
            </div>

            <div class="relative z-10 w-full md:w-1/2 text-center md:text-left">
                <span class="text-primary-500 font-bold tracking-widest text-sm uppercase">Mobile App</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-4">Shop Faster with our App</h2>
                <p class="text-gray-400 mb-6">Get exclusive app-only discounts and real-time order tracking. Available for
                    iOS and Android.</p>
                <div class="flex gap-4 justify-center md:justify-start">
                    <button
                        class="bg-gray-800 border border-gray-700 hover:border-white text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                        <i class="fab fa-apple text-2xl"></i>
                        <div class="text-left">
                            <div class="text-[10px] leading-none text-gray-400">Download on the</div>
                            <div class="text-sm font-bold leading-none">App Store</div>
                        </div>
                    </button>
                    <button
                        class="bg-gray-800 border border-gray-700 hover:border-white text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                        <i class="fab fa-google-play text-2xl text-green-500"></i>
                        <div class="text-left">
                            <div class="text-[10px] leading-none text-gray-400">Get it on</div>
                            <div class="text-sm font-bold leading-none">Google Play</div>
                        </div>
                    </button>
                </div>
            </div>

            <div class="w-full md:w-1/2 bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl relative z-10">
                <h3 class="text-white text-xl font-bold mb-2">Subscribe to our Newsletter</h3>
                <p class="text-gray-300 text-sm mb-4">Subscribe to the weekly newsletter for all the latest updates &
                    exclusive offers.</p>
                <form class="flex flex-col gap-3">
                    <input type="email" placeholder="Your Email Address"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-primary-500">
                    <button type="button"
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-lg transition shadow-lg shadow-primary-500/30">Subscribe
                        Now</button>
                </form>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
        });
        
        $(document).on('click', '#loadMoreProducts', function () {
            const button = $(this);
            let page = parseInt(button.data('page')) + 1;
            const url = button.data('url');

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    page: page,
                },
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

                        // Quickview JSON
                        const scriptTags = $(response).filter('script[data-quickview]');
                        scriptTags.each(function () {
                            try {
                                const data = JSON.parse($(this).html());
                                window.quickViewData = window.quickViewData || {};
                                window.quickViewData[data.id] = {
                                    product: data.product,
                                    defaultVariant: data.defaultVariant
                                };
                            } catch (e) {
                                console.error('Invalid quick view JSON', e);
                            }
                        });

                        if (typeof initFlowbite === 'function') initFlowbite();
                        if (typeof initQuickViewModals === 'function') initQuickViewModals();
                        if (typeof initProductSwipers === 'function') initProductSwipers();

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
    </script>
@endpush