<?php
$reviewCount = $product['reviews']->count();
$rating = $product['rating'];
$seller = $product['seller'];
$settings = settings();
$hasVariants = count($product['variants']) > 0;
$publicProduct = [
    'name' => $product['name'],
    'price' => $product['price'],
    'compare_price' => $product['compare_price'],
    'sku' => $product['sku'],
    'stock' => $product['stock'],
    'slider' => $product['slider'],
    'variants' => $product['variants'],
    'options' => $product['options'],
    'has_variants' => $hasVariants,
];
$breadCrumbs = [['label' => $product['category']], ['label' => $product['subcategory']]];

$showProductDiscount = isset($product['compare_price']) && $product['compare_price'] !== null && $product['compare_price'] < $product['price'];
?>

<div id="product-wrapper{{ $product['id'] }}" class="product-contents flex flex-col gap-5 md:flex-row"
    data-id="{{ $product['id'] }}" data-product='@json($publicProduct)'>

    <div class="md:w-[50%] w-full flex flex-col gap-3 bg-white border border-[#E5E5E5] rounded-sm py-5">
        <div class="w-full flex justify-center">
            <div class="relative w-full md:w-[360px] xl:w-[460px] aspect-[3/4]">
                <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}"
                    class="absolute inset-0 w-full h-full object-contain transition-all duration-300 main-product-image"
                    onerror="this.onerror=null;this.src='{{ asset('assets/frontend/images/default.png') }}';" />
            </div>
        </div>
        @if (count($product['slider']) > 0)
            <div class="order-2 w-full flex justify-center mt-4 px-5">
                <div class="single-product-thumbnails thumbnailWrapper flex gap-2 overflow-x-auto overflow-y-hidden scroll-smooth px-2"
                    style="scrollbar-width: thin; scrollbar-color: #d1d5db transparent;">
                    @foreach ($product['slider'] as $index => $img)
                        <div class="slide-thumb aspect-square w-16 sm:w-20 rounded cursor-pointer border overflow-hidden shrink-0 
                        {{ $index === 0 ? 'border-[#F85606]' : 'border-[#E5E5E5]' }}">
                            <img src="{{ $img }}" class="w-full h-full object-contain thumb-img"
                                data-image="{{ $img }}" data-full="{{ $img }}"
                                alt="{{ $product['name'] }} image {{ $index + 1 }}"
                                onerror="this.onerror=null;this.src='{{ asset('assets/frontend/images/default.png') }}';" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Product Details Section -->
    <div class="lg:w-[50%] md:w-[50%] w-full bg-white border border-[#E5E5E5] rounded-sm py-5 px-3 sm:px-8">
        <x-frontend.breadcrumb :items="$breadCrumbs" />
        <div class="w-full space-y-2">
            <h1 class="text-xl sm:text-2xl font-semibold text-[#191919]">
                {{ $product['name'] }}
            </h1>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($hasVariants)
                        <h3 class="font-bold text-[#191919] text-lg product-price">
                            Select options to see price
                        </h3>
                        <h6 class="text-[#767676] line-through text-sm original-price hidden"></h6>
                    @elseif ($showProductDiscount)
                        <h3 class="font-bold text-[#F85606] text-lg product-price">
                            {{ money($product['compare_price']) }}
                        </h3>
                        <h6 class="text-[#767676] line-through text-sm original-price">
                            {{ money($product['price']) }}
                        </h6>
                    @elseif ($product['price'])
                        <h3 class="font-bold text-[#191919] text-lg product-price">
                            {{ money($product['price']) }}
                        </h3>
                        <h6 class="text-[#767676] line-through text-sm original-price hidden"></h6>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700">
                    @if (auth()->check() && auth()->user()->isAffiliate())
                        <button
                            onclick="copyReferralLink(this, '{{ auth()->user()->referral_code }}', '{{ route('products.details', $product['slug']) }}')"
                            class="relative px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 transition"
                            type="button">
                            Refer Link
                            <span
                                class="tooltip-text absolute -top-6 left-1/2 -translate-x-1/2 bg-black text-white text-xs rounded px-2 py-1 opacity-0 pointer-events-none transition-opacity"
                                style="white-space: nowrap;">
                                Copied!
                            </span>
                        </button>
                    @endif

                </div>
            </div>

            @if ($hasVariants)
                <div class="variant-error hidden mt-4 p-4 rounded-md bg-red-100 text-red-700 text-sm font-medium">
                    Please select all available options before adding to cart.
                </div>
            @endif
        </div>

        <x-frontend.variant-selection-card :product="$product" />

        @isset($seller)
            <div class="border-y border-gray-100 py-4 my-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ storage_url($seller['business_logo']) }}"
                        class="w-10 h-10 rounded-full border border-gray-200 object-cover">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-semibold text-gray-800">{{ $seller['business_name'] }}</span>
                            <i class="fas fa-check-circle text-blue-500 text-[10px]" title="Verified Seller"></i>
                        </div>
                        <div class="flex items-center gap-2 text-[10px] text-gray-500">
                            <span><i class="fas fa-star text-yellow-400"></i> {{ $seller['rating'] }} Rating</span>
                            <span>•</span>
                            <span>{{ $seller['total_followers'] }} followers</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('sellers.shop', $seller['username']) }}"
                    class="text-xs font-medium text-primary-600 border border-primary-100 bg-primary-50 px-3 py-1.5 rounded hover:bg-primary-500 hover:text-white hover:border-primary-500 transition-colors">
                    Visit Store
                </a>
            </div>
        @endisset
    </div>
</div>
