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

<div id="product-wrapper{{ $product['id'] }}" class="product-contents grid grid-cols-1 lg:grid-cols-2 gap-5"
    data-id="{{ $product['id'] }}" data-product='@json($publicProduct)'>

    {{-- ============================================================ --}}
    {{-- IMAGE GALLERY --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col gap-3">
        {{-- Main Image --}}
        <div class="relative w-full bg-ds-surface-base border border-ds-border-default rounded-md overflow-hidden"
             style="aspect-ratio: 1 / 1;">
            <img src="{{ $product['thumbnail'] }}" alt="{{ $product['name'] }}"
                class="absolute inset-0 w-full h-full object-contain ds-gallery-main main-product-image"
                onerror="this.onerror=null;this.src='{{ asset('assets/frontend/images/default.png') }}';" />
            @if ($showProductDiscount)
                @php
                    $discountPct = round((($product['price'] - $product['compare_price']) / $product['price']) * 100);
                @endphp
                <span class="absolute top-3 left-3 z-10 bg-ds-feedback-danger text-white text-xs font-bold px-2 py-0.5 rounded-xs">
                    -{{ $discountPct }}%
                </span>
            @endif
        </div>

        {{-- Thumbnail Strip --}}
        @if (count($product['slider']) > 0)
            <div class="single-product-thumbnails thumbnailWrapper ds-thumb-strip flex gap-2 overflow-x-auto pb-1 scroll-smooth px-1">
                @foreach ($product['slider'] as $index => $img)
                    <button type="button"
                        class="slide-thumb flex-shrink-0 w-16 h-16 rounded-sm overflow-hidden border-2 transition-colors duration-100 {{ $index === 0 ? 'border-brand' : 'border-ds-border-default hover:border-ds-border-strong' }}"
                        data-full="{{ $img }}"
                        aria-label="View image {{ $index + 1 }} of {{ count($product['slider']) }}">
                        <img src="{{ $img }}" class="w-full h-full object-contain thumb-img pointer-events-none"
                            data-image="{{ $img }}" data-full="{{ $img }}"
                            alt="{{ $product['name'] }} image {{ $index + 1 }}"
                            onerror="this.onerror=null;this.src='{{ asset('assets/frontend/images/default.png') }}';" />
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ============================================================ --}}
    {{-- PRODUCT INFO --}}
    {{-- ============================================================ --}}
    <div class="flex flex-col bg-ds-surface-base border border-ds-border-default rounded-md px-4 sm:px-6 py-5">

        <x-frontend.breadcrumb :items="$breadCrumbs" />

        {{-- Title --}}
        <h1 class="text-lg sm:text-xl font-semibold text-ds-text-primary leading-snug">
            {{ $product['name'] }}
        </h1>

        {{-- Rating & Sold --}}
        <div class="flex items-center gap-3 mt-2 text-xs text-ds-text-secondary">
            <div class="flex items-center gap-1">
                <div class="flex text-ds-star">
                    @php $starCount = (int) round($rating); @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $starCount)
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @else
                            <svg class="w-3.5 h-3.5 fill-ds-border-default" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endif
                    @endfor
                </div>
                <span class="text-ds-text-tertiary">({{ $product['total_reviews'] }})</span>
            </div>
            <span class="text-ds-border-strong">|</span>
            <span>{{ number_shorten_format($product['sold_out'] ?? 0) }} sold</span>
        </div>

        {{-- Price Block --}}
        <div class="mt-4 p-3 bg-ds-surface-muted rounded-sm">
            <div class="flex flex-wrap items-baseline gap-2">
                @if ($hasVariants)
                    <h3 class="font-bold text-ds-text-primary text-xl product-price">
                        Select options to see price
                    </h3>
                    <h6 class="text-ds-text-tertiary text-sm line-through original-price hidden" aria-label="Original price"></h6>
                @elseif ($showProductDiscount)
                    <h3 class="font-bold text-ds-feedback-danger text-xl product-price">
                        {{ money($product['compare_price']) }}
                    </h3>
                    <h6 class="text-ds-text-tertiary text-sm line-through original-price" aria-label="Original price">
                        {{ money($product['price']) }}
                    </h6>
                    @php $discountPct = round((($product['price'] - $product['compare_price']) / $product['price']) * 100); @endphp
                    <span class="bg-ds-feedback-danger text-white text-xs font-bold px-2 py-0.5 rounded-xs">
                        -{{ $discountPct }}%
                    </span>
                @elseif ($product['price'])
                    <h3 class="font-bold text-ds-text-primary text-xl product-price">
                        {{ money($product['price']) }}
                    </h3>
                    <h6 class="text-ds-text-tertiary text-sm line-through original-price hidden" aria-label="Original price"></h6>
                @endif
            </div>
            @if (!$hasVariants)
                @if (($product['stock'] ?? 0) > 0)
                    <div class="flex items-center gap-1.5 mt-2 text-xs text-ds-feedback-success">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        In Stock
                    </div>
                @else
                    <div class="flex items-center gap-1.5 mt-2 text-xs text-ds-feedback-danger">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Out of Stock
                    </div>
                @endif
            @endif
        </div>

        {{-- Affiliate Referral Link --}}
        @if (auth()->check() && auth()->user()->isAffiliate())
            <div class="mt-3">
                <button
                    onclick="copyReferralLink(this, '{{ auth()->user()->referral_code }}', '{{ route('products.details', $product['slug']) }}')"
                    class="relative inline-flex items-center gap-1.5 px-3 py-1.5 border border-ds-border-default text-xs font-medium text-ds-text-secondary rounded-sm hover:bg-ds-surface-muted hover:text-ds-text-primary transition-colors duration-100"
                    type="button"
                    aria-label="Copy referral link">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Refer Link
                    <span
                        class="tooltip-text absolute -top-8 left-1/2 -translate-x-1/2 bg-ds-surface-strong text-ds-text-inverse text-xs rounded-xs px-2 py-1 opacity-0 pointer-events-none transition-opacity duration-200 whitespace-nowrap">
                        Copied!
                    </span>
                </button>
            </div>
        @endif

        {{-- Variant Error --}}
        @if ($hasVariants)
            <div class="variant-error hidden mt-3 p-3 rounded-sm bg-red-50 border border-ds-feedback-danger/20 text-ds-feedback-danger text-xs font-medium" role="alert">
                Please select all available options before adding to cart.
            </div>
        @endif

        {{-- Variant Selector --}}
        <x-frontend.variant-selection-card :product="$product" />

        {{-- Divider --}}
        <div class="border-t border-ds-border-default my-4"></div>

        {{-- Seller Info Card --}}
        @isset($seller)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ storage_url($seller['business_logo']) }}"
                        class="w-10 h-10 rounded-full border border-ds-border-default object-cover"
                        alt="{{ $seller['business_name'] }}">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-semibold text-ds-text-primary">{{ $seller['business_name'] }}</span>
                            <svg class="w-3.5 h-3.5 text-ds-feedback-info" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2 text-[11px] text-ds-text-tertiary mt-0.5">
                            <span class="flex items-center gap-0.5">
                                <svg class="w-3 h-3 text-ds-star fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $seller['rating'] }}
                            </span>
                            <span class="text-ds-border-strong">|</span>
                            <span>{{ $seller['total_followers'] }} followers</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('sellers.shop', $seller['username']) }}"
                    class="text-xs font-medium text-brand-deep border border-brand/20 bg-brand-tint px-3 py-1.5 rounded-sm hover:bg-brand hover:text-white hover:border-brand transition-colors duration-100">
                    Visit Store
                </a>
            </div>
        @endisset

        {{-- Delivery & Protection Info --}}
        <div class="border-t border-ds-border-default pt-4 mt-4 grid grid-cols-3 gap-2">
            <div class="bg-ds-surface-muted rounded-sm p-3 text-center">
                <i class="fas fa-truck text-ds-feedback-success text-base mb-1.5"></i>
                <p class="text-[11px] font-semibold text-ds-text-primary leading-tight">Fast delivery</p>
                <p class="text-[10px] text-ds-text-tertiary leading-tight mt-0.5">Usually in 27–28 days</p>
            </div>
            <div class="bg-ds-surface-muted rounded-sm p-3 text-center">
                <i class="fas fa-undo text-ds-feedback-info text-base mb-1.5"></i>
                <p class="text-[11px] font-semibold text-ds-text-primary leading-tight">7-day returns</p>
                <p class="text-[10px] text-ds-text-tertiary leading-tight mt-0.5">Easy return policy</p>
            </div>
            <div class="bg-ds-surface-muted rounded-sm p-3 text-center">
                <i class="fas fa-money-bill-wave text-brand text-base mb-1.5"></i>
                <p class="text-[11px] font-semibold text-ds-text-primary leading-tight">Cash on Delivery</p>
                <p class="text-[10px] text-ds-text-tertiary leading-tight mt-0.5">Pay at your door</p>
            </div>
        </div>
    </div>
</div>
