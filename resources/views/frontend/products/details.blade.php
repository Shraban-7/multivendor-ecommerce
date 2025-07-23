@extends('frontend.layouts.app')
@section('title', $product['name'])

@section('content')
    @php
        $settings = settings();
    @endphp
    <main class="product-details-page">
        <section class="container page-breadcrumb-links">
            <!-- Page Breadcrumb -->
            <nav class="container flex my-7" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 rtl:rotate-180 text-davy-gray" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#"
                                class="text-sm ms-1 text-davy-gray hover:text-primary eq md:ms-2">{{ $product['category'] }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 rtl:rotate-180 text-davy-gray" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="text-sm ms-1 text-davy-gray md:ms-2">{{ $product['subcategory'] }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Product Main Section -->
        <section class="product-main-sec">
            <div class="container">
                <!-- Product Contents  -->
                <x-frontend.product-contents :product="$product" />

                <!-- Rating Overview Section -->
                <div class="flex flex-col items-start gap-5 py-8 md:flex-row md:py-10">
                    <!-- Left Column -->
                    <div class="order-2 md:order-1 lg:w-[55%] md:w-[50%] w-full">
                        <!-- Overall Rating -->
                        <div class="flex items-start gap-4">
                            <div class="font-[arial] space-y-1">
                                <div class="text-4xl md:text-5xl text-persian-blue">
                                    {{ $product['rating'] . '%' }}
                                </div>

                                @if ($product['total_reviews'] > 0)
                                    <div class="flex text-3xl text-yellow-400 md:text-4xl">
                                        @php
                                            $average = round($product['rating']);
                                        @endphp
                                        {!! str_repeat('★', $average) . str_repeat('☆', 5 - $average) !!}
                                    </div>
                                @else
                                    <div class="flex text-3xl text-yellow-400 md:text-4xl">
                                        {!! str_repeat('☆', 5) !!}
                                    </div>
                                @endif
                                <div class="text-xs text-davy-gray sm:text-sm">
                                    (Positive reviews)
                                    <span class="font-semibold text-primary/80 lg:pl-4">Top</span>
                                </div>
                            </div>

                            <!-- Rating Bars -->
                            @php
                                $total = $totalReviews ?: 1;
                            @endphp

                            <div class="w-full space-y-1 ratings-wrap sm:w-2/4 md:w-3/4 2xl:w-1/2 lg:w-2/3 md:space-y-2">
                                @foreach ($ratings->sortDesc() as $star => $count)
                                    @php
                                        $percentage = round(($count / $total) * 100);
                                    @endphp
                                    <div class="flex items-center w-full gap-2 md:gap-5">
                                        <div class="w-1/2 sm:w-5/12 md:w-7/12 lg:w-1/2 2xl:w-7/12">
                                            <div class="w-full bg-gray-200 rounded-full h-2 lg:h-2.5">
                                                <div class="bg-yellow-400 h-2 rounded-full lg:h-2.5"
                                                    style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                        <span class="text-xs sm:text-sm text-persian-blue">
                                            {{ $count }} ({{ str_pad($star, 2, '0', STR_PAD_LEFT) }} star)
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Average Rating -->
                        <div class="flex flex-wrap items-center gap-3 my-3 md:my-5">
                            @if ($totalReviews > 0)
                                <span class="text-xl font-medium text-davy-gray sm:text-2xl">
                                    {{ number_format($averageRating, 1) }}
                                </span>
                                <div class="flex gap-1 text-xs flex-nowrap md:text-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($averageRating))
                                            <i class="fa-solid fa-star text-yellow-400"></i>
                                        @elseif ($i - $averageRating < 1)
                                            <i class="fa-solid fa-star-half-stroke text-yellow-400"></i>
                                        @else
                                            <i class="fa-solid fa-star text-gray-400"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm sm:text-base text-jet-gray">
                                    ({{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }})
                                </span>
                            @endif
                        </div>

                        <!-- Review Section -->
                        <div class="text-sm comments-tags lg:text-base text-davy-gray">
                            <h4>Item Reviews</h4>
                            <!-- review tags -->
                            {{-- <div class="flex flex-wrap gap-2 my-3 font-medium review-tags lg:gap-3 md:my-5">
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray gap-2">
                                    <span class="w-auto h-5 flag-wrap lg:h-7"><img class="object-contain w-auto h-full"
                                            src="{{ asset('assets/frontend/images/us-flag.png') }}"
                                            alt="Flag of USA" /></span>
                                    <span>(800)</span>
                                </button>
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray">
                                    Gift (90)
                                </button>
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray">
                                    Adorable (250)
                                </button>
                                <button
                                    class="inline-flex items-center lg:px-4 lg:py-1.5 px-3 py-1 rounded-full border border-jet-gray">
                                    Beautiful (250)
                                </button>
                            </div> --}}

                            <!-- User Reviews -->
                            <div class="pt-5 space-y-5 reviews-wrapper">
                                @include('frontend.partials.review-card', [
                                    'reviews' => $product['reviews'],
                                ])

                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="order-1 md:order-2 lg:w-[45%] md:w-[50%] w-full md:px-2 xl:px-3">
                        <div class="flex items-center gap-2 mb-5">
                            <span
                                class="bg-leaf-green inline-flex items-center gap-2 text-white text-xs px-2.5 py-1 rounded-full"><img
                                    src="{{ asset('assets/frontend/images/top-rated-icon.png') }}" alt="Top rated icon"
                                    class="object-contain w-3 h-3" />
                                #Top Rated</span>
                            {{-- <p class="text-sm text-davy-gray">In Men's Iteams</p> --}}
                        </div>
                        <!-- Shipping Info -->
                        {{-- <div class="flex items-center gap-2 mb-5">
                            <img src="{{ asset('assets/frontend/images/carbon_delivery.png') }}" alt="Shipping"
                                class="object-contain w-7 h-7" />
                            <span class="font-medium text-davy-gray">Ships From Tesco</span>
                        </div> --}}

                        <!-- Shipping Options -->
                        {{-- <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2">
                            <!-- Standard Shipping -->
                            <div class="bg-[#F5F5F5] p-4 rounded-lg text-davy-gray lg:space-y-2 space-y-1">
                                <h4 class="text-sm font-semibold text-black">
                                    Standard <span class="text-leaf-green">: Free</span>
                                </h4>
                                <p class="text-xs">
                                    <span class="text-sm">Delivery :</span> Fastest Delivery in
                                    5 Business days
                                </p>
                                <div class="flex items-center gap-1 text-xs">
                                    <span class="text-sm">Courier Company :</span>
                                    <img src="{{ asset('assets/frontend/images/dhl-logo.png') }}" alt="DHL"
                                        class="object-contain w-auto h-4" />
                                    <span>DHL</span>
                                    <img src="{{ asset('assets/frontend/images/ups-logo.png') }}" alt="UPS"
                                        class="object-contain w-auto h-4" />
                                    <span>UPS</span>
                                </div>
                            </div>

                            <!-- Express Shipping -->
                            <div class="bg-[#F5F5F5] p-4 rounded-lg text-davy-gray lg:space-y-2 space-y-1">
                                <h4 class="text-sm font-semibold text-black">
                                    Express <span class="text-leaf-green">: $12.00</span>
                                </h4>
                                <p class="text-xs">
                                    <span class="text-sm">Delivery :</span> Fastest Delivery in
                                    3 Business days
                                </p>
                                <div class="flex items-center gap-1 text-xs">
                                    <span class="text-sm">Courier Company :</span>
                                    <img src="{{ asset('assets/frontend/images/dhl-logo.png') }}" alt="DHL"
                                        class="object-contain w-auto h-4" />
                                    <span>DHL</span>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Commitments -->
                        <div class="mt-5">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-16 h-16 overflow-hidden tesko-icon">
                                    <img src="{{ storage_url($settings->logo) }}" alt="{{ $settings->app_name }}"
                                        class="object-contain w-full h-full" />
                                </div>
                                <span class="font-medium text-davy-gray">Our Commitments</span>
                            </div>

                            <div
                                class="grid w-full grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 xsm:w-4/5 md:w-11/12 lg:w-4/6 xl:w-11/12 2xl:w-4/5">
                                <!-- Security & Privacy -->
                                <div class="bg-[#F5F5F5] p-4 rounded-lg">
                                    <h3 class="mb-2 text-leaf-green">Security & Privacy</h3>
                                    <ul class="space-y-1 text-sm lg:space-y-2 text-davy-gray">
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span> Safe Payments </span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span> Secure Privacy </span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Delivery Guarantee -->
                                <div class="bg-[#F5F5F5] p-4 rounded-lg">
                                    <h3 class="mb-2 text-leaf-green">Delivery Guarantee</h3>
                                    <ul class="space-y-1 text-sm lg:space-y-2 text-davy-gray">
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span> Return Item If Damaged</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <i class="fa-solid fa-check text-leaf-green"></i>
                                            <span>15 Days No Update Refund </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($product['reviews']->count() > 2)
                    <!-- Load More Comment Button -->
                    <div class="pb-10 text-center border-b-2 border-gray-400 border-dashed load-more-btn">
                        <button id="loadMoreReviews" data-offset="2" data-type="reviews" data-url="{{ request()->url() }}"
                            class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                            type="button">
                            <span>Load More</span>
                            <i class="text-sm fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <!-- Product Provider Section Starts -->
        <section class="py-5 text-sm product-provider-sec md:py-8 md:text-base xl:text-lg text-davy-gray">
            <div class="container">
                <!-- Header -->
                <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                    <a href="{{ route('sellers.shop', $seller['username']) }}"
                        class="inline-block w-10 h-10 overflow-hidden rounded-full provider-dp lg:w-14 lg:h-14">
                        <img src="{{ storage_url($seller['shop_logo']) }}" alt="Louis Vuitton Logo"
                            class="object-contain w-full h-full" />
                    </a>
                    <div class="provider-info">
                        <h2 class="flex items-center gap-2 text-lg font-medium md:text-xl lg:text-2xl md:gap-5">
                            <a href="{{ route('sellers.shop', $seller['username']) }}"
                                class="hover:text-butterfly-blue eq">{{ $seller['shop_name'] }}</a>
                            <p class="flex items-center gap-2 text-sm font-light md:text-base xl:text-lg">
                                <button class="hover:text-primary eq">
                                    <i class="fa-regular fa-comment-dots"></i>
                                </button>
                                <span>Contact With Provider</span>
                            </p>
                        </h2>

                        <!-- Metrics -->
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span>{{ $seller['total_followers'] }}+ Followers .</span>
                            {{-- <span>{{ $seller['total_sell'] }} Sold .</span> --}}
                            <span class="flex items-center gap-1">
                                <span>{{ $seller['rating'] }}</span>
                                <i class="fa-solid fa-star text-theme-dark"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Buttons -->
                <div class="flex flex-wrap items-center gap-2 mt-4 font-medium md:gap-4">
                    <button
                        class="inline-flex items-center py-1.5 px-5 xsm:px-8 lg:px-10 lg:py-2.5 border border-jet-gray theme-btn gap-2 hover:bg-leaf-green hover:text-white hover:border-transparent eq text-sm md:text-base lg:text-xl font-inherit">
                        <i class="fa-solid fa-store"></i>
                        Follow
                    </button>
                    <a href="{{ route('sellers.shop', $seller['username']) }}"
                        class="inline-flex items-center py-1.5 px-5 xsm:px-8 lg:px-10 lg:py-2.5 border border-jet-gray theme-btn gap-2 hover:bg-primary hover:text-white hover:border-transparent eq text-sm md:text-base lg:text-xl font-inherit">
                        <span>Shop All Items</span>
                        ({{ count($products) }})
                    </a>
                </div>

                <div class="w-full shop-decriptions md:w-2/3 lg:w-1/2">
                    <!-- Description -->
                    <div class="mt-5">
                        <h2>Description:</h2>
                        <p class="mt-2">
                            {{ $product['description'] }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Product Provider Section Ended -->

        <!-- Explore Interest Section Start  -->
        <section class="explore-interest section-padding">
            <div class="container">
                <!-- Section Tittle -->
                <h1 class="mb-5 text-xl font-medium sm:text-2xl lg:text-3xl text-jet-gray md:mb-8 lg:mb-10">
                    Explore Your Interest
                </h1>

                <div id="product-wrapper"
                    class="grid items-start grid-cols-1 gap-5 p-2 xsm:grid-cols-2 md:grid-cols-4 xl:gap-8 lg:p-0">
                    @include('frontend.partials.product-card-load', ['products' => $products])
                </div>

                @if ($products->count() >= 8)
                    <!-- Load More Btn -->
                    <div class="mt-10 text-center load-more-btn">
                        <button data-page="1" data-type="products" data-url="{{ request()->url() }}"
                            id="loadMoreProducts"
                            class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
                            type="button">
                            <span>Load More</span>
                            <i class="text-sm fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                @endif
            </div>
        </section>
        <!-- Explore Interest Section Ended  -->
    </main>

    @push('scripts')
        {{-- <script>
            $(document).on('click', '.thumb-img', function() {
                const fullImageUrl = $(this).data('full');
                $('#main-product-image').attr('src', fullImageUrl);
                $('.slide-thumb').removeClass('border-primary').addClass('border-transparent');
                $(this).closest('.slide-thumb').addClass('border-primary').removeClass('border-transparent');
            });

            const product = @json($product);
            const productId = product.id;
            const variants = product.variants;
            const defaultVariant = @json($defaultVariant);
            const selectedOptions = {};
            const valueToOptionMap = {};
            let currentVariant = defaultVariant;

            function initProductCard(wrapper) {
                let quantity = 1;
                const wrapper = $('#product-details-wrapper');
                const quantityElement = $('#quantity' + productId);
                const decreaseBtn = $('#decreaseBtn' + productId);
                const increaseBtn = $('#increaseBtn' + productId);
                const hiddenInput = $('.qtyInputValue' + productId);
                const addToCartBtn = wrapper.find('#addToCartBtn' + productId);

                const updateQuantity = () => {
                    quantityElement.val(quantity.toString().padStart(1, ""));
                    hiddenInput.val(quantity);

                    if (currentVariant) {
                        wrapper.find('#variantId' + productId).val(currentVariant.id);

                        const basePrice = Number(currentVariant.price ?? 0);
                        const discountedPrice = currentVariant.discounted_price !== null ? Number(currentVariant
                            .discounted_price) : null;

                        const total = basePrice * quantity;
                        const totalPrice = Number.isInteger(total) ? total.toString() : total.toFixed(2);
                        const totalDiscounted = discountedPrice !== null ? discountedPrice * quantity : null;
                        const totalDiscountedPrice = totalDiscounted !== null ?
                            (Number.isInteger(totalDiscounted) ? totalDiscounted.toString() : totalDiscounted.toFixed(2)) :
                            null;

                        wrapper.find('#sku' + productId).text(currentVariant.sku);
                        wrapper.find('#stock' + productId).text(currentVariant.stock);
                        wrapper.find('#availability').text(currentVariant.stock > 0 ? 'In Stock' : 'Out of Stock');

                        if (discountedPrice !== null && discountedPrice > 0) {
                            wrapper.find('#discounted_price' + productId).text(`৳ ${totalDiscountedPrice}`).removeClass(
                                'hidden');
                            wrapper.find('#price' + productId).text(`৳ ${totalPrice}`).removeClass('hidden').addClass(
                                'line-through');
                        } else {
                            wrapper.find('#price' + productId).text(`৳ ${totalPrice}`);
                            wrapper.find('#discounted_price' + productId).text(`৳ ${totalPrice}`).removeClass('hidden');
                        }
                    }
                };

                increaseBtn.on('click', function() {
                    quantity++;
                    // console.log(quantity++);
                    updateQuantity();
                });

                decreaseBtn.on('click', function() {
                    if (quantity > 1) {
                        quantity--;
                        updateQuantity();
                    }
                });

                quantityElement.on('input', function() {
                    const newQuantity = parseInt($(this).val()) || 1;
                    quantity = newQuantity < 1 ? 1 : newQuantity;
                    updateQuantity();
                });

                product.options.forEach(option => {
                    option.values.forEach(value => {
                        valueToOptionMap[value.id] = option.id;
                    });
                });

                if (defaultVariant?.value_ids?.length) {
                    defaultVariant.value_ids.forEach(valueId => {
                        const optionId = valueToOptionMap[valueId];
                        if (optionId) {
                            selectedOptions[optionId] = valueId;
                            $(`.option-value-btn${productId}[data-option-id="${optionId}"]`).removeClass(
                                'bg-primary/10 text-primary border-primary').addClass(
                                'bg-white text-gray-800 border-gray-300');
                            $(`.option-value-btn${productId}[data-option-id="${optionId}"][data-value-id="${valueId}"]`)
                                .removeClass(
                                    'bg-white text-gray-800 border-gray-300').addClass(
                                    'bg-primary/10 text-primary border-primary');
                        }
                    });
                }

                updateQuantity();

                $(document).ready(function() {
                    $('.option-value-btn' + productId).on('click', function() {
                        const optionId = $(this).data('option-id');
                        const valueId = $(this).data('value-id');
                        selectedOptions[optionId] = valueId;

                        $(`.option-value-btn${productId}[data-option-id="${optionId}"]`).removeClass(
                            'bg-primary/10 text-primary border-primary').addClass(
                            'bg-white text-gray-800 border-gray-300');
                        $(this).removeClass('bg-white text-gray-800 border-gray-300').addClass(
                            'bg-primary/10 text-primary border-primary');

                        const selectedIds = Object.values(selectedOptions).map(Number).sort();
                        const matchingVariant = variants.find(variant => {
                            const sorted = variant.value_ids.slice().sort();
                            return JSON.stringify(sorted) === JSON.stringify(selectedIds);
                        });

                        if (matchingVariant) {
                            currentVariant = matchingVariant;

                            if (matchingVariant.image) {
                                const imageUrl = `/storage/${matchingVariant.image}`;
                                wrapper.find('#main-product-image').attr('src', imageUrl);
                                wrapper.find('.slide-thumb').removeClass('border-primary').addClass(
                                    'border-transparent');
                                wrapper.find(`.thumb-img[data-full="${imageUrl}"]`).closest('.slide-thumb')
                                    .addClass('border-primary').removeClass('border-transparent');
                            }

                            wrapper.find('#variantId' + productId).val(currentVariant.id);
                            wrapper.find('#sku' + productId).text(currentVariant.sku);
                            wrapper.find('#stock' + productId).text(currentVariant.stock);
                            const basePrice = parseFloat(currentVariant.price) || 0;
                            const baseDiscountedPrice = currentVariant.discounted_price !== null ? parseFloat(
                                currentVariant.discounted_price) : null;

                            const rawTotalPrice = basePrice * quantity;
                            const totalPrice = Number.isInteger(rawTotalPrice) ? rawTotalPrice.toString() :
                                rawTotalPrice.toFixed(2);

                            const rawTotalDiscountedPrice = baseDiscountedPrice !== null ? baseDiscountedPrice *
                                quantity : rawTotalPrice;
                            const totalDiscountedPrice = Number.isInteger(rawTotalDiscountedPrice) ?
                                rawTotalDiscountedPrice.toString() : rawTotalDiscountedPrice.toFixed(2);

                            if (baseDiscountedPrice && baseDiscountedPrice > 0) {
                                wrapper.find('#discounted_price' + productId).text(`৳ ${totalDiscountedPrice}`)
                                    .removeClass(
                                        'hidden');
                                wrapper.find('#price' + productId).text(`৳ ${totalPrice}`).removeClass('hidden')
                                    .addClass(
                                        'line-through');
                            } else {
                                wrapper.find('#price' + productId).text(`৳ ${totalPrice}`).removeClass(
                                    'line-through');
                                wrapper.find('#discounted_price' + productId).text(`৳ ${totalPrice}`)
                                    .removeClass(
                                        'hidden');
                            }

                            wrapper.find('#availability' + productId).text(currentVariant.stock > 0 ?
                                'In Stock' :
                                'Out of Stock');
                            wrapper.find('#variantInfo' + productId).removeClass('hidden');
                            wrapper.find('#variantNotFound' + productId).addClass('hidden');
                            addToCartBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                        } else {
                            currentVariant = null;
                            wrapper.find('#variantId' + productId).val('');
                            wrapper.find('#sku' + productId).text('N/A');
                            wrapper.find('#stock' + productId).text('0');
                            wrapper.find('#price' + productId).text('৳ 0.00').removeClass('line-through');
                            wrapper.find('#discounted_price' + productId).text('৳ 0.00');
                            wrapper.find('#availability' + productId).text('Not Available');

                            let allThumbs = '';
                            product.slider.forEach((img, i) => {
                                const full = `/storage/${img}`;
                                const border = i === 0 ? 'border-primary' : 'border-transparent';
                                allThumbs += `<div class="slide-thumb w-full xl:h-24 md:h-22 lg:h-28 h-20 rounded-2xl cursor-pointer border-2 ${border} overflow-hidden">
                        <img src="${full}" class="w-full h-full object-cover thumb-img" data-full="${full}" />
                    </div>`;
                            });
                            wrapper.find('#thumbnailWrapper').html(allThumbs);
                            wrapper.find('#main-product-image').attr('src',
                                `/storage/${product.slider[0] ?? ''}`);

                            wrapper.find('#variantInfo' + productId).addClass('hidden');
                            wrapper.find('#variantNotFound' + productId).removeClass('hidden');
                            addToCartBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                        }
                    });
                });
            }


        </script> --}}

        {{-- <script>
            window.products = window.products || {};
            window.products[{{ $product['id'] }}] = @json($product);
        </script>

        <script>
            $(function() {
                $("[id^='product-wrapper']").each(function() {
                    const $wrapper = $(this);
                    const productId = $wrapper.data("id");
                    const product = $wrapper.data("product") || (window.products?.[productId]);

                    if (!product) return;

                    const variants = product.variants || [];
                    const defaultVariant = product.defaultVariant || {};
                    const slider = product.slider || [];
                    const options = product.options || [];

                    let selectedOptions = {};
                    let currentVariant = defaultVariant;
                    let quantity = 1;

                    const $mainImage = $wrapper.find(".main-product-image");
                    const $thumbWrapper = $wrapper.find("#thumbnailWrapper");
                    const $priceEl = $wrapper.find(`#price${productId}`);
                    const $discountedPriceEl = $wrapper.find(`#discounted_price${productId}`);
                    const $skuEl = $wrapper.find(`#sku${productId}`);
                    const $stockEl = $wrapper.find(`#stock${productId}`);
                    const $availability = $wrapper.find(`#availability${productId}`);
                    const $variantInfo = $wrapper.find(`#variantInfo${productId}`);
                    const $variantError = $wrapper.find(`#variantNotFound${productId}`);
                    const $addToCartBtn = $wrapper.find(`#addToCartBtn${productId}`);
                    const $variantIdInput = $wrapper.find(`#variantId${productId}`);
                    const $qtyInput = $wrapper.find(`.qtyInputValue${productId}`);
                    const $increaseBtn = $wrapper.find(`#increaseBtn${productId}`);
                    const $decreaseBtn = $wrapper.find(`#decreaseBtn${productId}`);
                    const $quantityInput = $wrapper.find(`#quantity${productId}`);
                    const $optionBtns = $wrapper.find(`.option-value-btn${productId}`);

                    // Build value-to-option map
                    const valueToOptionMap = {};
                    options.forEach(opt => {
                        opt.values.forEach(val => {
                            valueToOptionMap[val.id] = opt.id;
                        });
                    });

                    // Initialize selectedOptions from default variant
                    if (defaultVariant?.value_ids?.length) {
                        defaultVariant.value_ids.forEach(valId => {
                            const optId = valueToOptionMap[valId];
                            if (optId) selectedOptions[optId] = valId;
                        });
                    }

                    function updateUI() {
                        if (!currentVariant) return;

                        const basePrice = parseFloat(currentVariant.price) || 0;
                        const discounted = currentVariant.discounted_price !== null ?
                            parseFloat(currentVariant.discounted_price) :
                            null;

                        const total = (basePrice * quantity).toFixed(2);
                        const discountTotal = discounted !== null ? (discounted * quantity).toFixed(2) : null;

                        $skuEl.text(currentVariant.sku);
                        $stockEl.text(currentVariant.stock);
                        $availability.text(currentVariant.stock > 0 ? "In Stock" : "Out of Stock");

                        if (discounted && discounted > 0) {
                            $discountedPriceEl.text(`৳ ${discountTotal}`).removeClass("hidden");
                            $priceEl.text(`৳ ${total}`).addClass("line-through");
                        } else {
                            $priceEl.text(`৳ ${total}`).removeClass("line-through");
                            $discountedPriceEl.text(`৳ ${total}`).removeClass("hidden");
                        }

                        $variantIdInput.val(currentVariant.id);
                        $qtyInput.val(quantity);
                    }

                    function resetUI() {
                        $skuEl.text("N/A");
                        $stockEl.text("0");
                        $availability.text("Not Available");
                        $priceEl.text("৳ 0.00").removeClass("line-through");
                        $discountedPriceEl.text("৳ 0.00");
                        $variantIdInput.val("");
                    }

                    function updateVariantSelection() {
                        const selectedIds = Object.values(selectedOptions).map(Number).sort();
                        const found = variants.find(v =>
                            JSON.stringify([...v.value_ids].sort()) === JSON.stringify(selectedIds)
                        );

                        if (found) {
                            currentVariant = found;
                            updateUI();

                            if (found.image) {
                                const imageUrl = `/storage/${found.image}`;
                                $mainImage.attr("src", imageUrl);
                                $thumbWrapper.find(".slide-thumb").removeClass("border-primary");
                                $thumbWrapper.find(`.thumb-img[data-full="${imageUrl}"]`)
                                    .closest(".slide-thumb").addClass("border-primary");
                            }

                            $variantInfo.removeClass("hidden");
                            $variantError.addClass("hidden");
                            $addToCartBtn.prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                        } else {
                            currentVariant = null;
                            resetUI();

                            $variantInfo.addClass("hidden");
                            $variantError.removeClass("hidden");
                            $addToCartBtn.prop("disabled", true).addClass("opacity-50 cursor-not-allowed");

                            // Reset thumbnails
                            let html = '';
                            slider.forEach((img, idx) => {
                                const full = `/storage/${img}`;
                                html += `<div class="slide-thumb w-full h-20 rounded-2xl cursor-pointer border-2 ${idx === 0 ? 'border-primary' : 'border-transparent'} overflow-hidden">
                            <img src="${full}" class="w-full h-full object-cover thumb-img" data-full="${full}" />
                        </div>`;
                            });
                            $thumbWrapper.html(html);
                            $mainImage.attr("src", `/storage/${slider[0]}`);
                        }
                    }

                    // Variant option button click
                    $optionBtns.on("click", function() {
                        const $this = $(this);
                        const optId = $this.data("option-id");
                        const valId = $this.data("value-id");

                        selectedOptions[optId] = parseInt(valId);

                        $optionBtns.filter(`[data-option-id="${optId}"]`)
                            .removeClass("bg-primary/10 text-primary border-primary")
                            .addClass("bg-white text-gray-800 border-gray-300");

                        $this.removeClass("bg-white text-gray-800 border-gray-300")
                            .addClass("bg-primary/10 text-primary border-primary");

                        updateVariantSelection();
                    });

                    // Quantity handling
                    $increaseBtn.on("click", () => {
                        quantity++;
                        updateUI();
                    });

                    $decreaseBtn.on("click", () => {
                        if (quantity > 1) {
                            quantity--;
                            updateUI();
                        }
                    });

                    $quantityInput.on("input", () => {
                        const val = parseInt($quantityInput.val()) || 1;
                        quantity = val > 0 ? val : 1;
                        updateUI();
                    });

                    // Thumbnail switching
                    $wrapper.on("click", ".thumb-img", function() {
                        const full = $(this).data("full");
                        $mainImage.attr("src", full);
                        $thumbWrapper.find(".slide-thumb").removeClass("border-primary");
                        $(this).closest(".slide-thumb").addClass("border-primary");
                    });

                    updateUI();
                });
            });
        </script> --}}

    



        <script>
            $(document).ready(function() {
                $('#loadMoreReviews').on('click', function() {
                    var $button = $(this);
                    var offset = parseInt($button.data('offset'));
                    var url = $button.data('url');
                    var type = $button.data('type');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            offset: offset,
                            type: type
                        },
                        success: function(response) {
                            if ($.trim(response) === '') {

                                $button.hide();
                            } else {
                                $('#reviews-wrapper').append(response);
                                $button.data('offset', offset + 2);
                            }
                        },
                        error: function() {
                            console.error('Failed to load more reviews.');
                        }
                    });
                });
            });
        </script>

        <script>
            $('#loadMoreProducts').on('click', function() {
                let button = $(this);
                let page = parseInt(button.data('page')) + 1;
                let url = button.data('url');

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        page: page,
                        type: 'products'
                    },
                    beforeSend: function() {
                        button.prop('disabled', true).html(
                            '<i class="fa fa-spinner fa-spin"></i> Loading...'
                        );
                    },
                    success: function(response) {
                        if (response.trim() !== '') {
                            $('#product-wrapper').append(response);

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
                                    console.error('Invalid quick view JSON format', e);
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
    @endpush
@endsection
