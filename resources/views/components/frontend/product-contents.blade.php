<?php
$reviewCount = $product['reviews']->count();
$rating = $product['rating'];
$seller = $product['seller'];
$settings = settings();
$publicProduct = [
    'name' => $product['name'],
    'price' => $product['default_variant']->selling_price ?? $product['selling_price'],
    'discounted_price' => $product['default_variant']->discounted_price ?? $product['discounted_price'],
    'sku' => $product['default_variant']['sku'] ?? $product['sku'],
    'stock' => $product['stock'],
    'slider' => $product['slider'],
    'variants' => $product['variants'],
];
$breadCrumbs = [['label' => $product['category']], ['label' => $product['subcategory']]];

$defaultVariant = $product['default_variant'] ?? null;
$variantDiscountedPrice = $defaultVariant ? (is_array($defaultVariant) ? $defaultVariant['discounted_price'] ?? null : $defaultVariant->discounted_price ?? null) : null;
$variantPrice = $defaultVariant ? (is_array($defaultVariant) ? $defaultVariant['selling_price'] ?? null : $defaultVariant->selling_price ?? null) : null;
$showVariantDiscount = $variantDiscountedPrice !== null && $variantDiscountedPrice < $variantPrice;
$showProductDiscount = isset($product['discounted_price'], $product['price']) && $product['discounted_price'] !== null && $product['discounted_price'] < $product['price'];
?>

<div id="product-wrapper{{ $product['id'] }}" class="product-contents flex flex-col gap-5 md:flex-row"
    data-id="{{ $product['id'] }}" data-product='@json($publicProduct)'>

    <div class="md:w-[50%] w-full flex flex-col gap-3 bg-white rounded border-1 border-gray-200 py-5">
        <div class="w-full flex justify-center ">
            <div class="relative w-full md:w-[360px] xl:w-[460px] aspect-[3/4] rounded-md">
                <img src="{{ $product['thumbnail'] }}" alt="Thumbnail"
                    class="absolute inset-0 w-full h-full object-contain transition-all duration-300 main-product-image" />
            </div>
        </div>
        @if (count($product['slider']) > 1)
            <div class="order-2 w-full flex justify-center mt-4 px-5">
                <div class="single-product-thumbnails thumbnailWrapper flex gap-2 overflow-x-auto overflow-y-hidden scroll-smooth px-2"
                    style="scrollbar-width: thin; scrollbar-color: #d1d5db transparent; ">
                    @foreach ($product['slider'] as $index => $img)
                        <div
                            class="slide-thumb aspect-square w-16 sm:w-20 rounded-md cursor-pointer border overflow-hidden shrink-0 
                        {{ $index === 0 ? 'border-primary' : 'border-gray-200' }}">
                            <img src="{{ storage_url($img) }}" class="w-full h-full object-contain thumb-img"
                                data-image="{{ storage_url($img) }}" data-full="{{ storage_url($img) }}" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Product Details Section -->
    <div class="lg:w-[50%] md:w-[50%] w-full bg-white rounded border-1 border-gray-200 py-5 px-3 sm:px-8">
        <x-frontend.breadcrumb :items="$breadCrumbs" />
        <div class="w-full space-y-2">
            <h1 class="text-2xl font-semibold">
                {{ $product['name'] }}
            </h1>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($showVariantDiscount || $product['discounted_price'])
                        <h3 class="font-bold text-gray-900  text-lg product-price">
                            {{ $showVariantDiscount ? money($variantDiscountedPrice) : money($product['discounted_price']) }}
                        </h3>
                        <h6 class="text-jet-gray line-through text-sm original-price">
                            {{ $showVariantDiscount ? money($variantPrice) : money($product['selling_price']) }}
                        </h6>
                    @elseif ($variantPrice || $product['selling_price'])
                        <h3 class="font-bold text-gray-900 text-lg product-price">
                            {{ $variantPrice ? money($variantPrice) : money($product['selling_price']) }}
                        </h3>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700">
                    <div><strong>SKU:</strong> <span class="sku-text">{{ $defaultVariant->sku ?? $product['sku'] }}</span></div>

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

            @if (count($product['variants']) > 0)
                <div class="variant-error hidden mt-4 p-4 rounded-md bg-red-100 text-red-700 text-sm font-medium">
                    Not Found.
                </div>
            @endif
        </div>

        <x-frontend.variant-selection-card :product="$product" />

        @isset($seller)
            <div class="mt-3 p-4 bg-gray-50 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('sellers.shop', $seller['username']) }}"
                            class="flex-shrink-0 w-12 h-12 overflow-hidden rounded-full">
                            <img src="{{ storage_url($seller['business_logo']) }}" alt="{{ $seller['username'] }}"
                                class="object-cover w-full h-full" />
                        </a>
                        <div>
                            <h3 class="font-semibold text-lg">
                                <a href="{{ route('sellers.shop', $seller['username']) }}"
                                    class="hover:text-primary transition-colors mr-2">{{ $seller['business_name'] }}</a>
                                @if ($product['seller']['best_seller'])
                                    <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best
                                        Seller</span>
                                @endif
                            </h3>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span>{{ $seller['total_followers'] }}+ followers</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-star text-yellow-400"></i>
                                    {{ $seller['rating'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- <button class="text-primary hover:text-primary-dark transition-colors">
                        <i class="fa-regular fa-comment-dots text-xl"></i>
                    </button> --}}
                </div>
                <div class="flex gap-2 mb-4">
                    <button
                        class="flex-1 py-2 px-4 border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition-all text-sm font-medium">
                        <i class="fa-solid fa-store mr-2"></i>
                        Follow
                    </button>
                    <a href="{{ route('sellers.shop', $seller['username']) }}"
                        class="flex-1 py-2 px-4 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-center text-sm font-medium">
                        Shop All Items
                    </a>
                </div>
            </div>
        @endisset

        <div class="mt-3 p-4 bg-gray-50 rounded-xl shadow-sm">
            <span class="font-semibold text-gray-800">Our Commitments</span>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <h4 class="text-leaf-green font-medium mb-2 text-sm">Security & Privacy</h4>
                    <ul class="space-y-1 text-xs text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-check text-leaf-green text-xs"></i>
                            <span>Safe Payments</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-check text-leaf-green text-xs"></i>
                            <span>Secure Privacy</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg ">
                    <h4 class="text-leaf-green font-medium mb-2 text-sm">Delivery Guarantee</h4>
                    <ul class="space-y-1 text-xs text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-check text-leaf-green text-xs"></i>
                            <span>Return Item If Damaged</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-check text-leaf-green text-xs"></i>
                            <span>15 Days No Update Refund</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
