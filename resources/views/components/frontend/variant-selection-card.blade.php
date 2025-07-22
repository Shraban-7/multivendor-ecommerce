<div class="w-full max-w-3xl mx-auto mt-6 rounded-lg  p-4 space-y-4 shadow-sm">
    <form id="variantForm" class="space-y-4" data-id="{{ $product['id'] }}" data-slug="{{ $product['slug'] ?? '' }}">
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
                    data-option-id="{{ $option['id'] }}"
                    data-value-id="{{ $value['id'] }}">
                    {{ $value['value'] }}
                </button>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- <div class="flex items-center gap-4 flex-wrap sm:flex-nowrap">
            <label class="w-20 text-sm font-medium text-gray-700">Quantity:</label>
            <div class="flex items-center gap-3">
                <div class="flex items-center border border-gray-300 rounded-md overflow-hidden w-max">
                    <button id="decreaseBtn" type="button"
                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                        <i class="fa-solid fa-minus text-xs"></i>
                    </button>
                    <input readonly id="quantity" type="number" min="1"
                        class="w-10 text-center text-sm font-semibold text-gray-800 border-0 focus:ring-0" />
                    <button id="increaseBtn" type="button"
                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>
                <span class="text-xs text-gray-500">In Stock</span>
            </div>
        </div> -->
        
        <!-- <div class="flex flex-wrap items-center gap-3 pt-2">
            <button class="bg-primary text-white px-5 py-2 text-sm rounded-md hover:bg-primary/90 transition">
                Add to Cart
            </button>
            <button class="bg-black text-white px-5 py-2 text-sm rounded-md hover:bg-gray-800 transition">
                Buy Now
            </button>
        </div> -->
    </form>
</div>