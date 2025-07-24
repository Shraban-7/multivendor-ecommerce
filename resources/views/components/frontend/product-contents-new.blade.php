@php
    $publicProduct = [
        'name' => $product['name'],
        'price' => $product['price'],
        'discounted_price' => $product['discounted_price'],
        'sku' => $product['sku'],
        'stock' => $product['stock'],
        'slider' => $product['slider'],
        'variants' => $product['variants'],
    ];

    $defaultVariant = $product['defaultVariant'] ?? null;
    $variantPrice = $defaultVariant['selling_price'] ?? null;
    $variantDiscountedPrice = $defaultVariant['discounted_price'] ?? null;
    $showVariantDiscount = $variantDiscountedPrice !== null && $variantDiscountedPrice < $variantPrice;
    $showProductDiscount = $product['discounted_price'] !== null && $product['discounted_price'] < $product['price'];
    $seller = $product['seller'];
@endphp


<div id="product-wrapper{{ $product['id'] }}" class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8"
    data-id="{{ $product['id'] }}" data-product='@json($publicProduct)'>

    <!-- Image Gallery -->
    <div class="flex flex-col-reverse md:flex-row gap-4">
        <!-- Thumbnails -->
        <div class="flex md:flex-col gap-2 overflow-x-auto md:overflow-x-visible md:overflow-y-auto md:max-h-[500px] pb-2 md:pb-0">
            @foreach ($product['slider'] as $index => $img)
            <div class="flex-shrink-0 w-16 h-16 md:w-20 md:h-20 rounded-md overflow-hidden border-2 cursor-pointer {{ $index === 0 ? 'border-blue-500' : 'border-transparent' }}">
                <img src="{{ storage_url($img) }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
            </div>
            @endforeach
        </div>

        <!-- Main Image -->
        <div class="flex-1 bg-gray-100 rounded-lg overflow-hidden aspect-square">
            <img src="{{ storage_url($product['slider'][0] ?? '') }}" alt="{{ $product['name'] }}"
                class="w-full h-full object-contain transition-opacity duration-300 main-product-image">
        </div>
    </div>

    <!-- Product Info -->
    <div class="space-y-4">
        <!-- Badges -->
        <div class="flex flex-wrap gap-2">
            @if ($product['seller']['best_seller'])
            <span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full flex items-center gap-1">
                <i class="fas fa-award"></i> Best Seller
            </span>
            @endif
            <span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-1 rounded-full flex items-center gap-1">
                <i class="fas fa-shipping-fast"></i> Free Shipping
            </span>
            @if ($product['almost_sold_out'])
            <span class="bg-red-100 text-red-800 text-xs px-2.5 py-1 rounded-full">
                Almost Sold Out
            </span>
            @endif
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-semibold">{{ $product['name'] }}</h1>

        <!-- Rating & Sales -->
        <div class="flex items-center gap-4 text-sm text-gray-600">
            <div class="flex items-center">
                <div class="flex text-yellow-400 mr-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <=floor($product['rating']/20))
                        ★
                        @elseif ($i - ($product['rating']/20) < 1)
                        <span class="relative -mx-0.5">★<span class="absolute inset-0 overflow-hidden" style="width: 50%">★</span></span>
                        @else
                        <span class="text-gray-300">★</span>
                        @endif
                        @endfor
                </div>
                <span class="ml-1">{{ $product['rating'] }}%</span>
            </div>
            <span>|</span>
            <span>{{ number_shorten_format($product['sold_out']) }} sold</span>
            <span>|</span>
            <span>SKU: {{ $firstVariant['sku'] ?? $product['sku'] }}</span>
        </div>

        <!-- Price -->
        <div class="my-4">
            @if ($showVariantDiscount || $showProductDiscount)
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold text-gray-900">
                    {{ money($variantDiscountedPrice ?? $product['discounted_price']) }}
                </span>
                <span class="text-lg text-gray-500 line-through">
                    {{ money($variantPrice ?? $product['price']) }}
                </span>
                @php
                $discount = 45;
                @endphp
                <span class="ml-2 bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full">
                    {{ $discount }}% OFF
                </span>
            </div>
            @else
            <span class="text-3xl font-bold text-gray-900">
                {{ money($variantPrice ?? $product['price']) }}
            </span>
            @endif
            <div class="text-sm text-green-600 mt-1">
                <i class="fas fa-check-circle"></i> In Stock: {{ $firstVariant['stock'] ?? $product['stock'] }}
            </div>
        </div>
        <x-frontend.variant-selection-card :product="$product" />       
    </div>
</div>