<div class="w-full max-w-3xl mx-auto mt-6 rounded-lg p-4 space-y-4 shadow-sm">
    @php
        $defaultVariant = $product['variants'];
        $defaultPrice = $defaultVariant['selling_price'] ?? '';
        $defaultDiscounted = $defaultVariant['discounted_price'] ?? '';
        $defaultValueIds = $defaultVariant['value_ids'] ?? [];
    @endphp

    <!-- Use default variant price -->
    <input type="hidden" id="productBasePrice" value="{{ $defaultPrice }}">
    <input type="hidden" id="productDiscountedPrice" value="{{ $defaultDiscounted }}">

    <form id="variantForm{{ $product['id'] }}" class="space-y-4" data-id="{{ $product['id'] }}"
        data-slug="{{ $product['slug'] }}">

        @foreach ($product['options'] as $option)
            <div class="flex items-start gap-4 flex-wrap sm:flex-nowrap" data-option-id="{{ $option['id'] }}">
                <label class="w-20 text-sm font-medium text-gray-700 pt-1">{{ $option['name'] }}:</label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($option['values'] as $value)
                        @php $isActive = in_array($value['id'], $defaultValueIds); @endphp
                        <button type="button"
                            class="option-value-btn text-sm font-medium px-3 py-1 rounded-md border transition
                                {{ $isActive
                                    ? 'bg-primary/10 text-primary border-primary'
                                    : 'bg-gray-50 text-gray-700 border-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                            data-option-id="{{ $option['id'] }}" data-value-id="{{ $value['id'] }}">
                            {{ $value['value'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex items-center gap-2 text-sm text-gray-600">
            <span>Stock: <span class="stock-text">{{ $defaultVariant['stock'] ?? $product['stock'] }}</span></span>
            {{-- <span class="text-xs text-muted font-medium almost-sold-out hidden">(Almost Sold Out)</span> --}}
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-start">
            <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                <button type="button"
                    class="decreaseBtn w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                    <i class="fa-solid fa-minus text-xs"></i>
                </button>
                <input readonly type="number" min="1" value="1"
                    class="quantity w-16 text-center text-sm font-semibold text-gray-800 border-0 focus:ring-0" />
                <button type="button"
                    class="increaseBtn w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                    <i class="fa-solid fa-plus text-xs"></i>
                </button>
            </div>

            <div class="flex flex-1 flex-col sm:flex-row gap-2 w-full">
                <button data-id="{{ $product['id'] }}" type="button"
                    class="addToCartBtn flex-1 w-full bg-primary hover:bg-orange-500 text-white py-2 px-3 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-1">
                    <i class="fas fa-shopping-cart text-xs"></i> Add to Cart
                </button>

                <button
                    class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 transition-colors text-sm shrink-0">
                    <i class="far fa-heart text-gray-600 text-xs"></i>
                </button>
            </div>
        </div>

    </form>
</div>
