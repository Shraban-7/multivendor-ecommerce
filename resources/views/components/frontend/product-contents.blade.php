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
@endphp

<div id="product-wrapper{{ $product['id'] }}" class="product-contents flex flex-col gap-5 md:flex-row"
    data-id="{{ $product['id'] }}" data-product='@json($publicProduct)'>

    <!-- Product Images Section -->
    <div class="lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5">
        <!-- Thumbnails -->
        <div class="order-2 w-full lg:w-1/6 lg:order-1">
            <div
                class="single-product-thumbnails thumbnailWrapper flex flex-col space-y-3 max-h-[21rem] overflow-y-auto sm:max-h-none sm:overflow-y-visible lg:h-[41rem] lg:overflow-hidden">
                @foreach ($product['slider'] as $index => $img)
                    <div
                        class="slide-thumb w-full h-20 lg:h-28 xl:h-24 rounded-2xl cursor-pointer border-2 overflow-hidden {{ $index === 0 ? 'border-primary' : 'border-transparent' }}">
                        <img src="{{ storage_url($img) }}" alt="{{ $product['name'] ?? 'Product Image' }}"
                            class="w-full h-full object-cover thumb-img" data-image="{{ storage_url($img) }}"
                            data-full="{{ storage_url($img) }}" />
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Image -->
        <div class="relative order-1 w-full lg:w-5/6 lg:order-2">
            <div class="overflow-hidden w-full h-96 md:h-[37rem] xl:h-[37rem] lg:h-[41rem] rounded-2xl relative">
                <img src="{{ storage_url($product['slider'][0] ?? '') }}"
                    alt="{{ $product['name'] ?? 'Product Image' }}"
                    class="w-full h-full object-cover rounded-2xl transition-all duration-300 main-product-image" />
            </div>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="lg:w-[45%] md:w-[50%] w-full md:px-2 xl:px-3">
        <div class="w-full space-y-2">
            <!-- Free Shipping Banner -->
            <div
                class="flex flex-col xsm:flex-row flex-wrap items-center justify-between gap-2 px-4 py-3 text-sm lg:text-base bg-[#FEEFE1] text-rustic-red">
                <div class="flex items-center gap-2 text-center">
                    <i class="fa-solid fa-check text-theme-teal"></i>
                    <span>Free shipping special for you</span>
                </div>
                <span class="font-light text-jet-gray">Exclusive offer</span>
            </div>
            <h1 class="text-sm lg:text-base text-rustic-red lg:pr-5 xl:pr-16">
                {{ $product['name'] }}
            </h1>
            <div class="flex flex-wrap items-center gap-2 text-sm xsm:gap-5 sm:10 md:gap-2 lg:gap-10">
                <div class="flex items-center gap-2 text-davy-gray">
                    <span>Seller: </span>
                    <a href="{{ route('sellers.shop', $product['seller']['username']) }}" class="inline-block">
                        <span class="text-blue-500 font-bold">{{ $product['seller']['business_name'] }}</span>
                    </a>
                    <span class="border-r border-gray-400 h-4"></span>

                    @if ($product['sold_out'] > 0)
                        <span class="pl-2 text-jet-gray">
                            {{ number_shorten_format($product['sold_out']) }} sold
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs">{{ $product['rating'] }} Star</span>
                    <span class="flex text-yellow-400 text-sm">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($product['rating']))
                                ★
                            @elseif ($i - $product['rating'] < 1)
                                <span class="relative -mx-0.5">★<span class="absolute inset-0 overflow-hidden"
                                        style="width: 50%">★</span></span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if ($product['seller']['best_seller'])
                    <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best Seller</span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-4">
                @php
                    $defaultVariant = $product['defaultVariant'] ?? null;
                    $variantDiscountedPrice = $defaultVariant['discounted_price'] ?? null;
                    $variantPrice = $defaultVariant['selling_price'] ?? null;
                    $showVariantDiscount = $variantDiscountedPrice !== null && $variantDiscountedPrice < $variantPrice;
                    $showProductDiscount =
                        $product['discounted_price'] !== null && $product['discounted_price'] < $product['price'];
                @endphp

                <div class="flex flex-wrap items-center gap-2">
                    @if ($showVariantDiscount)
                        <h3 class="font-bold text-primary text-lg product-price">{{ money($variantDiscountedPrice) }}
                        </h3>
                        <h6 class="text-jet-gray line-through text-sm original-price">{{ money($variantPrice) }}</h6>
                    @elseif ($showProductDiscount)
                        <h3 class="font-bold text-primary text-lg product-price">
                            {{ money($product['discounted_price']) }}</h3>
                        <h6 class="text-jet-gray line-through text-sm original-price">{{ money($product['price']) }}
                        </h6>
                    @elseif ($variantPrice)
                        <h3 class="font-bold text-primary text-lg product-price">{{ money($variantPrice) }}</h3>
                    @else
                        <h3 class="font-bold text-primary text-lg product-price">{{ money($product['price']) }}</h3>
                    @endif

                    @if ($product['almost_sold_out'])
                        <span class="text-xs text-leaf-green">Almost Sold Out</span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-700">
                    <div><strong>SKU:</strong> <span
                            class="sku-text">{{ $firstVariant['sku'] ?? $product['sku'] }}</span></div>
                    <div><strong>Stock:</strong> <span
                            class="stock-text">{{ $firstVariant['stock'] ?? $product['stock'] }}</span></div>
                </div>
            </div>

            @if (count($product['variants']) > 0)
                <div class="variant-error hidden mt-4 p-4 rounded-md bg-red-100 text-red-700 text-sm font-medium">
                    Not Found.
                </div>
            @endif
        </div>

        <x-frontend.variant-selection-card :product="$product" />
    </div>
</div>
