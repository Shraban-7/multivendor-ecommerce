<div id="quick-view-modal-{{ $product['id'] }}" tabindex="-1"
    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full hidden"
    aria-hidden="true" inert>
    <div class="relative container max-h-full">
        <!-- Modal content -->
        <div class="relative shadow-lg bg-white rounded-2xl md:rounded-3xl">
            <!-- Modal Close Triger -->
            <button type="button"
                class="close-modal-btn text-white bg-theme-dark hover:bg-theme-dark/80 rounded-full lg:w-10 lg:h-10 w-7 h-7 inline-flex justify-center items-center md:text-2xl text-lg absolute right-4 top-4 z-10"
                data-modal-hide="quick-view-modal-{{ $product['id'] }}">
                <i class="fas fa-x"></i>
                <!-- <i class="fa-solid fa-xmark"></i> Font Awesome fontawesome.com -->
                <span class="sr-only">Close modal</span>
            </button>
            <!-- Modal body -->
            <div class="p-4 md:p-10">
                <div class="flex flex-col md:flex-row gap-5">
                    <!-- Product Images Section -->
                    <div class="lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5">
                        <!-- Thumbnails -->
                        <div class="order-2 w-full lg:w-1/6 lg:order-1">
                            <div id="thumbnailWrapper{{ $product['id'] }}"
                                class="single-product-thumbnails flex flex-col space-y-3 max-h-[21rem] overflow-y-auto sm:max-h-none sm:overflow-y-visible lg:h-[41rem] lg:overflow-hidden">
                                @foreach ($product['slider'] as $index => $img)
                                    <div
                                        class="slide-thumb w-full h-20 lg:h-28 xl:h-24 rounded-2xl cursor-pointer border-2 overflow-hidden {{ $index === 0 ? 'border-primary' : 'border-transparent' }}">
                                        <img src="{{ storage_url($img) }}"
                                            alt="{{ $product['name'] ?? 'Product Image' }}"
                                            class="w-full h-full object-cover thumb-img"
                                            data-full="{{ storage_url($img) }}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Main Image -->
                        <div class="relative order-1 w-full lg:w-5/6 lg:order-2">
                            <div
                                class="overflow-hidden w-full h-96 md:h-[37rem] xl:h-[37rem] lg:h-[41rem] rounded-2xl relative">
                                <img id="main-product-image{{ $product['id'] }}"
                                    src="{{ storage_url($product['slider'][0] ?? '') }}"
                                    alt="{{ $product['name'] ?? 'Product Image' }}"
                                    class="w-full h-full object-cover rounded-2xl transition-all duration-300" />
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

                            <h1 class="lg:text-base text-rustic-red text-sm lg:pr-5 xl:pr-16">
                                <a href="{{ route('products.details', $product['slug']) }}"
                                    class="hover:text-primary eq">
                                    {{ $product['name'] }}
                                </a>
                            </h1>
                            <div
                                class="flex flex-wrap items-center gap-2 xsm:gap-x-5 sm:gap-x-10 md:gap-2 lg:gap-x-10 text-sm">
                                <div class="flex items-center gap-2">
                                    @if ($product['sold_out'] > 0)
                                        <span
                                            class="text-jet-gray border-r border-gray-400 pr-2 whitespace-nowrap">{{ number_format($product['sold_out']) }}
                                            sold</span>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-x-2 text-davy-gray">
                                        <a href="{{ route('sellers.shop', $product['seller']['username']) }}"
                                            class="inline-block provider-icon w-10 h-10 overflow-hidden rounded-full">
                                            <img src="{{ storage_url($product['seller']['business_logo']) }}"
                                                alt="{{ $product['seller']['business_name'] }}"
                                                class="h-full w-full object-contain">
                                        </a>
                                        <a href="{{ route('sellers.shop', $product['seller']['username']) }}"
                                            class="inline-block ">
                                            <span class="font-bold">{{ $product['seller']['business_name'] }}</span>
                                        </a>

                                        @if ($product['sold_out'] > 0)
                                            <span>({{ number_shorten_format($product['sold_out']) }}+ sold)</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if ($product['rating'] > 0)
                                        <span class="flex text-yellow-400 text-sm">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($product['rating']))
                                                    <span class="mx-0.5">★</span>
                                                @elseif ($i - $product['rating'] < 1)
                                                    <span class="mx-0.5 relative text-gray-300">
                                                        ★
                                                        <span class="absolute inset-0 overflow-hidden text-yellow-400"
                                                            style="width: 50%">★</span>
                                                    </span>
                                                @else
                                                    <span class="mx-0.5 text-gray-300">★</span>
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="text-muted text-sm">({{ $product['total_reviews'] }})</span>
                                    @endif


                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($product['seller']['best_seller'])
                                    <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best
                                        Seller</span>
                                @endif

                            </div>

                            <div class="flex flex-wrap items-center gap-4">
                                @php
                                    $hasVariants =
                                        isset($product['variants']) &&
                                        is_array($product['variants']) &&
                                        count($product['variants']) > 0;

                                    $defaultVariant = $product['defaultVariant'] ?? null;

                                    $variantDiscountedPrice = $defaultVariant['discounted_price'] ?? null;
                                    $variantPrice = $defaultVariant['selling_price'] ?? null;

                                    $showVariantDiscount =
                                        $variantDiscountedPrice !== null && $variantDiscountedPrice < $variantPrice;
                                    $showProductDiscount =
                                        $product['discounted_price'] !== null &&
                                        $product['discounted_price'] < $product['price'];
                                @endphp


                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($showVariantDiscount)
                                        <div class="flex items-center gap-1">
                                            <h3 id="discounted_price{{ $product['id'] }}"
                                                class="font-bold text-primary text-lg product-price">
                                                {{ money($variantDiscountedPrice) }}
                                            </h3>
                                            <h6 id="price{{ $product['id'] }}"
                                                class="text-jet-gray line-through text-sm">
                                                {{ money($variantPrice) }}
                                            </h6>
                                        </div>
                                    @elseif ($showProductDiscount)
                                        <div class="flex items-center gap-1">
                                            <h3 id="discounted_price{{ $product['id'] }}"
                                                class="font-bold text-primary text-lg product-price">
                                                {{ money($product['discounted_price']) }}
                                            </h3>
                                            <h6 id="price{{ $product['id'] }}"
                                                class="text-jet-gray line-through text-sm">
                                                {{ money($product['price']) }}
                                            </h6>
                                        </div>
                                    @elseif($variantPrice)
                                        <div class="flex items-center gap-1">
                                            <h3 id="price{{ $product['id'] }}"
                                                class="font-bold text-primary text-lg product-price">
                                                {{ money($variantPrice) }}
                                            </h3>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1">
                                            <h3 id="price{{ $product['id'] }}"
                                                class="font-bold text-primary text-lg product-price">
                                                {{ money($product['price']) }}
                                            </h3>
                                        </div>
                                    @endif

                                    @if ($product['almost_sold_out'])
                                        <span class="text-xs text-leaf-green">Almost Sold Out</span>
                                    @endif
                                </div>

                                <div id="variantInfo{{ $product['id'] }}"
                                    class="flex flex-wrap items-center gap-3 text-sm text-gray-700">
                                    <div>
                                        <strong>SKU:</strong> <span
                                            id="sku{{ $product['id'] }}">{{ $firstVariant['sku'] ?? $product['sku'] }}</span>
                                    </div>
                                    <div>
                                        <strong>Stock:</strong> <span
                                            id="stock{{ $product['id'] }}">{{ $firstVariant['stock'] ?? $product['stock'] }}</span>
                                    </div>
                                </div>
                            </div>

                            @if (count($product['variants']) > 0)
                                <div id="variantNotFound{{ $product['id'] }}"
                                    class="hidden mt-4 p-4 rounded-md bg-red-100 text-red-700 text-sm font-medium">
                                    Variant not found for selected options.
                                </div>
                            @endif
                        </div>
                        <div
                            class="user-action rounded-lg border-primary border-2 overflow-hidden mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <div class="px-4 py-2 clr-size-qty">
                                <div id="product-attributes{{ $product['id'] }}">
                                    <input type="hidden" id="productBasePrice{{ $product['id'] }}"
                                        value="{{ $product['price'] }}">
                                    <input type="hidden" id="productDiscountedPrice{{ $product['id'] }}"
                                        value="{{ $product['discounted_price'] }}">

                                    <!-- Variant Form -->
                                    <form id="variantForm{{ $product['id'] }}"
                                        data-slug="{{ $product['slug'] ?? '' }}" class="flex flex-wrap flex-col"
                                        data-id="{{ $product['id'] ?? '' }}">

                                        @php
                                            $defaultVariant = collect($product['variants'])->firstWhere(
                                                'is_default',
                                                1,
                                            );
                                            $defaultValueIds = $defaultVariant['value_ids'] ?? [];
                                        @endphp

                                        @foreach ($product['options'] as $option)
                                            <div class="mb-4 flex gap-1 items-center"
                                                data-option-id="{{ $option['id'] }}">
                                                <strong class="text-gray-700 text-base">
                                                    {{ $option['name'] }} :</strong>
                                                <div class="flex flex-wrap gap-3">
                                                    @foreach ($option['values'] as $value)
                                                        @php $isActive = in_array($value['id'], $defaultValueIds); @endphp
                                                        <button type="button"
                                                            class="option-value-btn-modal px-4 py-2 text-sm border rounded-md transition-all duration-200
                                                            {{ $isActive ? 'bg-primary text-white border-primary' : 'bg-white text-gray-800 border-gray-300 hover:bg-primary/90' }}"
                                                            data-option-id="{{ $option['id'] }}"
                                                            data-value-id="{{ $value['id'] }}">
                                                            {{ $value['value'] }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </form>

                                </div>

                                <!-- Quantity -->
                                <div class="quantity mt-3">
                                    <div class="text-davy-gray flex items-center gap-2">
                                        <h6 class="sm:text-lg">Quantity :</h6>
                                        <div class="flex items-center border border-jet-gray/30 rounded p-1">
                                            <button id="decreaseBtn{{ $product['id'] }}"
                                                class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input readonly id="quantity{{ $product['id'] }}" type="number"
                                                min="1"
                                                class="text-center text-persian-blue w-16 h-5 text-sm font-medium border-0 focus:ring-0" />

                                            <button id="increaseBtn{{ $product['id'] }}"
                                                class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                        <span class="text-davy-gray text-xs">In Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="flex flex-wrap w-full gap-3 mt-5 xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <input type="hidden" name="quantity" class="qtyInputValue" value=""
                                id="qtyInput{{ $product['id'] }}">

                            <input type="hidden" id="variantSku{{ $product['id'] }}" value="">

                            @if ($product['stock'] > 0 || collect($product['variants'])->sum('stock') > 0)
                                <button data-id="{{ $product['id'] }}" data-modal="{{ true }}"
                                    type="button" id="addToCartBtn{{ $product['id'] }}"
                                    class="cartBtn text-sm md:text-base font-medium flex-1 px-6 py-2 border border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-all">
                                    Add To Cart
                                </button>
                            @else
                                <button data-id="{{ $product['id'] }}" type="button"
                                    class="wishlistBtn text-sm md:text-base font-medium flex-1 px-6 py-2 border border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-all">
                                    <i class="fa-regular fa-heart"></i>
                                    <span>Wishlist</span>
                                </button>
                            @endif

                            <button data-id="{{ $product['id'] }}" data-seller="{{ $product['seller']['id'] }}"
                                class="buyNowBtn text-sm md:text-base font-medium flex-1 px-6 py-2 bg-primary text-white rounded-full hover:bg-theme-dark transition-all">
                                Buy Now
                                <span class="block text-xs font-light">Faster Dispatch</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.quickViewData = window.quickViewData || {};
        window.quickViewData[{{ $product['id'] }}] = {
            product: @json($product),
            defaultVariant: @json($defaultVariant)
        };

        function initQuickViewModals() {
            $(document).on('click', '.thumb-img', function() {
                const fullImageUrl = $(this).data('full');
                const wrapper = $(this).closest('[id^="quick-view-modal-"]');
                const productId = wrapper.attr('id').replace('quick-view-modal-', '');
                wrapper.find('#main-product-image' + productId).attr('src', fullImageUrl);
                wrapper.find('.slide-thumb').removeClass('border-primary').addClass('border-transparent');
                $(this).closest('.slide-thumb').addClass('border-primary').removeClass('border-transparent');
            });

            $('[id^="quick-view-modal-"]').each(function() {
                const modal = $(this);
                const productId = this.id.replace('quick-view-modal-', '');

                if (modal.data('initialized')) return;
                modal.data('initialized', true);

                const product = window.quickViewData[productId]?.product || {};
                const defaultVariant = window.quickViewData[productId]?.defaultVariant || null;
                const variants = product.variants || [];

                let quantity = 1;
                let currentVariant = defaultVariant ? {
                    ...defaultVariant
                } : null;

                const quantityElement = modal.find('#quantity' + productId);
                const decreaseBtn = modal.find('#decreaseBtn' + productId);
                const increaseBtn = modal.find('#increaseBtn' + productId);
                const hiddenInput = modal.find('#qtyInput' + productId);
                const variantSku = modal.find('#variantSku' + productId);

                const selectedOptions = {};
                const valueToOptionMap = {};

                const updateQuantity = () => {
                    quantityElement.val(quantity.toString().padStart(2, "0"));
                    hiddenInput.val(quantity);

                    if (!currentVariant) return;

                    modal.find('#sku' + productId).text(currentVariant.sku || 'N/A');
                    modal.find('#stock' + productId).text(currentVariant.stock || '0');

                    const basePrice = parseFloat(currentVariant.price) || 0;
                    const baseDiscountedPrice = currentVariant.discounted_price !== null ?
                        parseFloat(currentVariant.discounted_price) : null;

                    const total = basePrice * quantity;
                    const totalDiscounted = baseDiscountedPrice !== null ? baseDiscountedPrice * quantity :
                    null;

                    const priceText = `৳ ${total.toFixed(2)}`;
                    const discountedText = totalDiscounted !== null ? `৳ ${totalDiscounted.toFixed(2)}` :
                        priceText;

                    if (baseDiscountedPrice && baseDiscountedPrice < basePrice) {
                        modal.find('#discounted_price' + productId).text(discountedText).removeClass('hidden');
                        modal.find('#price' + productId).text(priceText).addClass('line-through');
                    } else {
                        modal.find('#price' + productId).text(priceText).removeClass('line-through');
                        modal.find('#discounted_price' + productId).text(priceText).removeClass('hidden');
                    }
                };

                increaseBtn.on('click', function() {
                    quantity++;
                    updateQuantity();
                });

                decreaseBtn.on('click', function() {
                    if (quantity > 1) {
                        quantity--;
                        updateQuantity();
                    }
                });

                quantityElement.on('input', function() {
                    const newQuantity = parseInt($(this).val()) || 1;
                    quantity = newQuantity < 1 ? 1 : newQuantity;
                    updateQuantity();
                });

                (product.options || []).forEach(option => {
                    option.values.forEach(value => {
                        valueToOptionMap[value.id] = option.id;
                    });
                });

                if (defaultVariant?.value_ids?.length) {
                    defaultVariant.value_ids.forEach(valueId => {
                        const optionId = valueToOptionMap[valueId];
                        selectedOptions[optionId] = valueId;

                        modal.find(`.option-value-btn-modal[data-option-id="${optionId}"]`).removeClass(
                                'bg-primary text-white border-primary')
                            .addClass('bg-white text-gray-800 border-gray-300');

                        modal.find(
                                `.option-value-btn-modal[data-option-id="${optionId}"][data-value-id="${valueId}"]`
                            )
                            .addClass('bg-primary text-white border-primary')
                            .removeClass('bg-white text-gray-800 border-gray-300');
                    });
                }

                updateQuantity();

                modal.find('.option-value-btn-modal').on('click', function() {
                    const optionId = $(this).data('option-id');
                    const valueId = $(this).data('value-id');
                    selectedOptions[optionId] = valueId;

                    modal.find(`.option-value-btn-modal[data-option-id="${optionId}"]`)
                        .removeClass('bg-primary text-white border-primary')
                        .addClass('bg-white text-gray-800 border-gray-300');

                    $(this).addClass('bg-primary text-white border-primary')
                        .removeClass('bg-white text-gray-800 border-gray-300');

                    const selectedIds = Object.values(selectedOptions).map(Number).sort();
                    const matchingVariant = variants.find(variant => {
                        return JSON.stringify([...variant.value_ids].sort()) === JSON.stringify(
                            selectedIds);
                    });

                    if (matchingVariant) {
                        currentVariant = {
                            ...matchingVariant
                        };
                        variantSku.val(currentVariant.sku);
                        modal.find('#variantNotFound' + productId).addClass('hidden');
                        modal.find('#variantInfo' + productId).removeClass('hidden');
                        $('#addToCartBtn' + productId).prop('disabled', false).removeClass(
                            'opacity-50 cursor-not-allowed');

                        if (currentVariant.image) {
                            const imageUrl = `{{ storage_url('') }}` + currentVariant.image;
                            modal.find('#main-product-image' + productId).attr('src', imageUrl);

                            const thumbImg = modal.find(`.thumb-img[data-full="${imageUrl}"]`);
                            if (thumbImg.length) {
                                modal.find('.slide-thumb').removeClass('border-primary').addClass(
                                    'border-transparent');
                                thumbImg.closest('.slide-thumb').addClass('border-primary').removeClass(
                                    'border-transparent');
                            } else {
                                modal.find('.slide-thumb').removeClass('border-primary').addClass(
                                    'border-transparent');
                            }
                        }

                        updateQuantity();

                    } else {
                        currentVariant = null;
                        variantSku.val('');
                        modal.find('#sku' + productId).text('N/A');
                        modal.find('#price' + productId).text('৳ 0.00').removeClass('line-through');
                        modal.find('#discounted_price' + productId).text('৳ 0.00');
                        modal.find('#stock' + productId).text('0');
                        modal.find('#variantInfo' + productId).addClass('hidden');
                        modal.find('#variantNotFound' + productId).removeClass('hidden');
                        $('#addToCartBtn' + productId).prop('disabled', true).addClass(
                            'opacity-50 cursor-not-allowed');
                    }
                });

                if (!variants.length) {
                    currentVariant = {
                        sku: product.sku || 'N/A',
                        stock: product.stock || 0,
                        price: product.price || 0,
                        discounted_price: product.discounted_price || null,
                        image: product.thumbnail || null
                    };

                    variantSku.val(currentVariant.sku);
                    modal.find('#sku' + productId).text(currentVariant.sku);
                    modal.find('#stock' + productId).text(currentVariant.stock);
                    modal.find('#variantNotFound' + productId).addClass('hidden');
                    modal.find('#variantInfo' + productId).removeClass('hidden');
                    $('#addToCartBtn' + productId).prop('disabled', false).removeClass(
                        'opacity-50 cursor-not-allowed');

                    const basePrice = parseFloat(currentVariant.price);
                    const baseDiscountedPrice = currentVariant.discounted_price !== null ? parseFloat(currentVariant
                        .discounted_price) : null;

                    const priceText = `৳ ${basePrice.toFixed(2)}`;
                    const discountedText = baseDiscountedPrice !== null ? `৳ ${baseDiscountedPrice.toFixed(2)}` :
                        priceText;

                    if (baseDiscountedPrice && baseDiscountedPrice < basePrice) {
                        modal.find('#discounted_price' + productId).text(discountedText).removeClass('hidden');
                        modal.find('#price' + productId).text(priceText).addClass('line-through');
                    } else {
                        modal.find('#price' + productId).text(priceText).removeClass('line-through');
                        modal.find('#discounted_price' + productId).text(priceText).removeClass('hidden');
                    }

                    if (currentVariant.image) {
                        const imageUrl = `{{ storage_url('') }}` + currentVariant.image;
                        modal.find('#main-product-image' + productId).attr('src', imageUrl);
                    }
                } else {
                    $('#addToCartBtn' + productId).prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                }
            });
        }

        $(document).ready(function() {
            initQuickViewModals();
        });

        function openModal(modal) {
            modal.removeAttribute('inert');
            modal.setAttribute('aria-hidden', 'false');
            modal.classList.remove('hidden');
            const closeBtn = modal.querySelector('[data-modal-hide]');
            closeBtn?.focus();
        }

        function closeModal(modal) {
            modal.setAttribute('inert', '');
            modal.setAttribute('aria-hidden', 'true');
            modal.classList.add('hidden');
        }

        $(document).on('click', '[data-modal-toggle]', function() {
            const id = $(this).data('modal-target') || $(this).data('modal-toggle');
            const modal = document.getElementById(id);
            if (modal) openModal(modal);
        });

        $(document).on('click', '[data-modal-hide]', function() {
            const id = $(this).data('modal-hide');
            const modal = document.getElementById(id);
            if (modal) closeModal(modal);
        });
    </script>
@endpush
