<div class="w-full max-w-3xl mx-auto mt-6 rounded-lg  p-4 space-y-4 shadow-sm">
    <input type="hidden" id="productBasePrice" value="{{ $product['price'] }}">
    <input type="hidden" id="productDiscountedPrice" value="{{ $product['discounted_price'] }}">
    <form id="variantForm{{ $product['id'] }}" class="space-y-4" data-id="{{ $product['id'] }}" data-slug="{{ $product['slug'] }}">
        @php
        $defaultVariant = collect($product['variants'])->firstWhere('is_default', 1);
        $defaultValueIds = $defaultVariant['value_ids'] ?? [];
        @endphp

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

        <div class="flex items-center gap-4 flex-wrap sm:flex-nowrap">
            <label class="w-20 text-sm font-medium text-gray-700">Quantity:</label>
            <div class="flex items-center gap-3">
                <div class="flex items-center border border-gray-300 rounded-md overflow-hidden w-max">
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
            </div>
        </div>

        <!-- <div class="flex flex-wrap items-center gap-3 pt-2">
            <button data-id="{{ $product['id'] }}" type="button" class="addToCartBtn bg-primary text-white px-5 py-2 text-sm rounded-md hover:bg-primary/90 transition">
                Add to Cart
            </button>
            <button class="bg-black text-white px-5 py-2 text-sm rounded-md hover:bg-gray-800 transition">
                Buy Now
            </button>
        </div> -->
        <input type="hidden" name="quantity" class="qtyInputValue" value="">
        <input type="hidden" class="variantId" name="variant_id" value="">

        <div class="flex gap-3 pt-2">
            <button data-id="{{ $product['id'] }}" type="button" class="addToCartBtn flex-1 bg-primary hover:bg-orange-500 text-white py-3 px-4 rounded-md font-medium transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
            </button>
            <button class="w-12 h-12 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 transition-colors">
                <i class="far fa-heart text-gray-600"></i>
            </button>
        </div>
    </form>
</div>
