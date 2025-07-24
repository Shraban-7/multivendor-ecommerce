<div class="group relative rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
    <button type="button" class="absolute top-3 left-3 z-10 text-gray-400 hover:text-red-500 transition duration-200">
        <i class="fa-regular fa-heart text-xl"></i>
    </button>

    @php
        $original = $product['price'];
        $discounted = $product['discounted_price'];
        $discountPercent = round((($original - $discounted) / $original) * 100);
    @endphp

    @if ($discountPercent > 0)
        <span class="absolute top-3 right-3 z-10 bg-primary text-white text-xs px-2 py-0.5 rounded-full shadow-sm">
            {{ $discountPercent }}% OFF
        </span>
    @endif

    <div class="relative aspect-[4/3] overflow-hidden">
        <a href="{{ route('products.details', $product['slug']) }}">
            <img src="{{ storage_url($product['thumbnail']) }}" alt="{{ $product['name'] }}"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
        </a>
        <button type="button" data-modal-target="quick-view-modal-{{ $product['id'] }}"
            data-modal-toggle="quick-view-modal-{{ $product['id'] }}" data-action="quick-view"
            class="absolute bottom-3 right-3 px-3 py-1.5 text-xs text-gray-700 bg-white bg-opacity-90 rounded-full shadow-md
                   hover:bg-primary hover:text-white transition duration-200">
            <i class="fa-regular fa-eye mr-1"></i> Quick View
        </button>
    </div>

    <div class="p-4 flex flex-col justify-between h-full">
        <h3 class="text-sm sm:text-base font-semibold text-gray-800 line-clamp-2">
            <a href="{{ route('products.details', $product['slug']) }}"
                class="hover:text-primary transition-colors duration-200">
                {{ $product['name'] }}
            </a>
        </h3>

        @if ($product['almost_sold_out'])
            <span class="mt-1 inline-block text-xs text-leaf-green font-medium">Almost Sold Out</span>
        @endif

        <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
            @if ($product['rating'] > 0)
                <div class="flex items-center gap-0.5 text-sm">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($product['rating']))
                            <!-- Full star -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20">
                                <path fill="#facc15" d="M10 15l-5.878 3.09 1.122-6.545L.489 6.91l6.564-.955L10 0
                                    l2.947 5.955 6.564.955-4.755 4.635 1.122 6.545z" />
                            </svg>
                        @elseif ($i - $product['rating'] < 1)
                            <!-- Half star -->
                            <div class="relative w-4 h-4">
                                <svg class="absolute w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill="#d1d5db" d="M10 15l-5.878 3.09 1.122-6.545L.489 6.91l6.564-.955L10 0
                                        l2.947 5.955 6.564.955-4.755 4.635 1.122 6.545z" />
                                </svg>
                                <svg class="absolute h-4 overflow-hidden" style="width: 50%"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill="#facc15" d="M10 15l-5.878 3.09 1.122-6.545L.489 6.91l6.564-.955L10 0
                                        l2.947 5.955 6.564.955-4.755 4.635 1.122 6.545z" />
                                </svg>
                            </div>
                        @else
                            <!-- Empty star -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20">
                                <path fill="#d1d5db" d="M10 15l-5.878 3.09 1.122-6.545L.489 6.91l6.564-.955L10 0
                                    l2.947 5.955 6.564.955-4.755 4.635 1.122 6.545z" />
                            </svg>
                        @endif
                    @endfor
                </div>
            @endif

            @if ($product['sold_out'] > 0)
                <span>{{ number_shorten_format($product['sold_out']) }}+ Sold</span>
            @endif
        </div>

        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-baseline gap-2">
                @if ($product['discounted_price'] != null || $product['discounted_price'] != 0)
                    <span class="text-primary font-bold text-lg">
                        {{ money($product['discounted_price']) }}
                    </span>
                    <span class="text-sm text-gray-400 line-through">
                        {{ money($product['price']) }}
                    </span>
                @else
                    <span class="text-primary font-bold text-lg">
                        {{ money($product['price']) }}
                    </span>
                @endif
            </div>
            @auth
                <div>
                    <input type="hidden" name="quantity" id="qtyInput{{ $product['id'] }}" class="qtyInputValue"
                        value="">
                    <button type="button" data-id="{{ $product['id'] }}"
                        data-modal-target="quick-view-modal-{{ $product['id'] }}"
                        data-modal-toggle="quick-view-modal-{{ $product['id'] }}" data-action="add-to-cart"
                        class="btn-add-cart w-9 h-9 flex items-center justify-center bg-primary hover:bg-theme-dark text-white text-sm rounded-full transition duration-200">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                </div>
            @endauth
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).on('click', '[data-modal-toggle^="quick-view-modal-"]', function() {
            const action = $(this).data('action');
            const productId = $(this).data('id') || $(this).closest('[data-id]').data('id');

            if (action === 'add-to-cart') {
                const modal = $('#quick-view-modal-' + productId);
                modal.find('#quantity' + productId).val('01');
                modal.find('.qtyInputValue').val(1);

                modal.find('.addToCartBtn').get(0)?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
@endpush
