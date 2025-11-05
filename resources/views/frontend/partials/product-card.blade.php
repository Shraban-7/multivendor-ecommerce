<div class="group relative rounded-md bg-white shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col">

    <!-- Wishlist -->
    <button type="button"
        class="absolute top-3 left-3 z-10 text-gray-400 hover:text-red-500 transition duration-200">
        <i class="fa-regular fa-heart text-lg"></i>
    </button>

    @php
    $original_price = $product['default_variant']->selling_price ?? $product['selling_price'];
    $discounted_price = $product['default_variant']->discounted_price ?? $product['discounted_price'];
    $discountPercent = $discounted_price ? round((($original_price - $discounted_price) / $original_price) * 100) : null;
    @endphp

    <!-- Discount Badge -->
    @if ($discountPercent > 0)
    <span
        class="absolute top-3 right-3 z-10 bg-primary text-white text-[11px] px-2 py-0.5 rounded-full shadow-sm">
        -{{ $discountPercent }}%
    </span>
    @endif

    <!-- Product Image -->
    <div class="relative aspect-square overflow-hidden">
        <a href="{{ route('products.details', $product['slug']) }}">
            <img src="{{ storage_url($product['thumbnail']) }}" alt="{{ $product['name'] }}"
                class="w-full h-full object-cover object-top 
                    transition-transform duration-500 group-hover:scale-105" />
        </a>

        <!-- Quick View -->
        <button type="button" data-modal-target="quick-view-modal-{{ $product['id'] }}"
            data-modal-toggle="quick-view-modal-{{ $product['id'] }}" data-action="quick-view"
            class="absolute bottom-3 right-3 
           opacity-100 md:opacity-0 md:group-hover:opacity-100 
           translate-y-0 md:translate-y-2 md:group-hover:translate-y-0
           px-3 py-1.5 text-xs font-medium text-gray-700 bg-white/90 rounded-full shadow-md
           hover:bg-primary hover:text-white transition-all duration-300">
            <i class="fa-regular fa-eye mr-1"></i> Quick View
        </button>
    </div>

    <!-- Product Info -->
    <div class="p-3 flex flex-col">
        <!-- Name -->
        <h3 class="text-sm font-medium text-gray-700 line-clamp-2 leading-snug mb-1">
            <a href="{{ route('products.details', $product['slug']) }}"
                class="hover:text-primary transition-colors duration-200">
                {{ $product['name'] }}
            </a>
        </h3>

        <!-- Price -->
        <div class="flex items-center gap-2 mb-1">
            @if ($discounted_price != null && $discounted_price > 0)
            <span class="text-primary font-semibold text-base">
                {{ money($discounted_price) }}
            </span>
            <span class="text-xs text-gray-400 line-through">
                {{ money($original_price) }}
            </span>
            @else
            <span class="text-primary font-semibold text-base">
                {{ money($original_price) }}
            </span>
            @endif
        </div>

        <!-- Rating + Add to Cart -->
        <div class="flex items-center justify-between">
            @if ($product['rating'] > 0)
            <div class="flex items-center gap-0.5 text-yellow-400 text-xs">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa-solid fa-star {{ $i <= floor($product['rating']) ? '' : 'text-gray-300' }}"></i>
                    @endfor
            </div>
            @endif

            @auth
            <button type="button" data-id="{{ $product['id'] }}"
                data-modal-target="quick-view-modal-{{ $product['id'] }}"
                data-modal-toggle="quick-view-modal-{{ $product['id'] }}" data-action="add-to-cart"
                class="w-8 h-8 flex items-center justify-center bg-primary hover:bg-theme-dark text-white text-xs rounded-full transition duration-200">
                <i class="fa-solid fa-cart-plus"></i>
            </button>
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