<div id="quick-view-modal-{{ $product['id'] }}" tabindex="-1"
    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full hidden"
    aria-hidden="true">
    <div class="relative container max-h-full">
        <!-- Modal content -->
        <div class="relative shadow-lg bg-white rounded-2xl md:rounded-3xl">
            <!-- Modal Close Triger -->
            <button type="button"
                class="text-white bg-theme-dark hover:bg-theme-dark/80 rounded-full lg:w-10 lg:h-10 w-7 h-7 inline-flex justify-center items-center md:text-2xl text-lg absolute right-4 top-4 z-10"
                data-modal-hide="quick-view-modal-{{ $product['id'] }}">
                <i class="fas fa-x"></i>
                <!-- <i class="fa-solid fa-xmark"></i> Font Awesome fontawesome.com -->
                <span class="sr-only">Close modal</span>
            </button>
            <!-- Modal body -->
            <div class="p-4 md:p-10">
                <div class="flex flex-col md:flex-row gap-5">
                    <!-- Product Images Section -->
                    <div
                        class="product-multi-slider-container lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5">
                        <!-- Thumbnails -->
                        <div class="lg:w-2/12 xl:w-1/6 w-full order-2 lg:order-1">
                            <div class="product-thumbnails overflow-hidden lg:h-[34rem] xl:h-[32rem] h-auto">
                                <div class="swiper-wrapper">
                                    @php
                                        $thumbnail = $product['thumbnail'] ?? null;
                                        $images = $product['images'] ?? [];

                                        $allImages = collect([$thumbnail])
                                            ->filter()
                                            ->concat($images)
                                            ->values();
                                    @endphp

                                    @foreach ($allImages as $img)
                                        <div class="swiper-slide">
                                            <div
                                                class="slide-thumb w-full xl:h-24 md:h-22 lg:h-28 h-20 rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary-500 overflow-hidden">
                                                <img src="{{ storage_url($img) }}"
                                                    alt="{{ $product->name ?? 'Product Image' }}"
                                                    class="w-full h-full object-cover" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- Main Image Slider -->
                        <div class="lg:w-10/12 xl:w-5/6 w-full relative order-1 lg:order-2">
                            <div
                                class="product-swiper w-full h-80 sm:h-[28rem] md:h-[37rem] lg:h-[34rem] xl:h-[32rem] rounded-2xl overflow-hidden relative">
                                <div class="swiper-wrapper">
                                    @php
                                        $thumbnail = $product['thumbnail'] ?? null;
                                        $images = $product['images'] ?? [];

                                        $allImages = collect([$thumbnail])
                                            ->filter()
                                            ->concat($images)
                                            ->values();
                                    @endphp

                                    @foreach ($allImages as $img)
                                        <div class="swiper-slide h-full aspect-[4/3] rounded-2xl overflow-hidden">
                                            <img src="{{ storage_url($img) }}"
                                                alt="{{ $product['name'] ?? 'Product Image' }}"
                                                class="w-full h-full object-cover" />
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Navigation Buttons -->

                                <div class="swiper-button-prev text-theme-light"></div>
                                <div class="swiper-button-next text-theme-light"></div>
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
                                                alt="Louis Vuitton" class="h-full w-full object-contain">
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
                            <div class="flex flex-wrap items-center gap-2">
                                <div class="new-price flex items-center gap-1 flex-nowrap">
                                    <svg class="svg-inline--fa fa-bolt text-[#ffa755]" aria-hidden="true"
                                        focusable="false" data-prefix="fas" data-icon="bolt" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288l111.5 0L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7l-111.5 0L349.4 44.6z">
                                        </path>
                                    </svg>
                                    <h3 id="current-price{{ $product['id'] }}"
                                        class="font-bold text-primary text-nowrap">
                                        {{ $product['discount_price'] }}
                                    </h3>
                                </div>
                                <h6 id="old-price{{ $product['id'] }}" class=" text-jet-gray line-through">
                                    {{ money($product['price']) }}
                                </h6>
                                @if ($product['almost_sold_out'])
                                    <span class="text-xs text-leaf-green">Almost Sold Out</span>
                                @endif
                            </div>
                        </div>
                        <div
                            class="user-action rounded-lg border-primary border-2 overflow-hidden mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <!-- Special Sale Banner -->
                            <div
                                class="bg-primary text-sm md:text-base text-white px-4 py-1 flex justify-between items-center">
                                <span>Special Sale | Two Days Left</span>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="clr-size-qty p-4">
                                <div id="product-attributes">
                                    <input type="hidden" id="productBasePrice{{ $product['id'] }}"
                                        value="{{ $product['price'] }}">
                                    <input type="hidden" id="productDiscountedPrice{{ $product['id'] }}"
                                        value="{{ $product['discount_price'] }}">

                                    <form data-slug="{{ $product['slug'] }}"
                                        class="flex flex-wrap flex-col variantForm{{ $product['id'] }}"
                                        data-id="{{ $product['id'] }}">
                                        @foreach ($product['product_attributes'] as $attribute)
                                            <div class="mt-2">
                                                <h6 class="text-davy-gray sm:text-lg">{{ $attribute['name'] }} :</h6>
                                                <div class="flex flex-wrap items-center gap-4 sm:gap-5">
                                                    @foreach ($attribute['options'] as $option)
                                                        @php
                                                            $inputId =
                                                                strtolower($attribute['name']) .
                                                                '-' .
                                                                strtolower($option['value']);
                                                            $inputName = 'option_' . $attribute['id'];
                                                        @endphp

                                                        <div class="form-ctrl flex flex-col gap-2 items-center">
                                                            <input id="{{ $inputId }}" type="radio"
                                                                value="{{ $option['variant_id'] }}"
                                                                data-id="{{ $option['id'] }}"
                                                                data-attribute="{{ $attribute['id'] }}"
                                                                data-variant-id="{{ $option['variant_id'] }}"
                                                                data-sku="{{ $option['sku'] }}"
                                                                data-price="{{ $option['price'] }}"
                                                                data-product-price="{{ $product['price'] }}"
                                                                data-discounted-price="{{ $product['discount_price'] }}"
                                                                data-image="{{ storage_url($option['image']) }}"
                                                                name="{{ $inputName }}"
                                                                class="hidden peer variant-option" />

                                                            @if (strtolower($attribute['name']) === 'color')
                                                                <label
                                                                    style="background-color: {{ strtolower($option['value']) }}"
                                                                    for="{{ $inputId }}"
                                                                    class="w-6 h-6 sm:w-8 sm:h-8 block peer-checked:ring peer-checked:ring-{{ strtolower($option['value']) }}-800  rounded-full peer-checked:border-2 peer-checked:border-jet-gray/30 sm:peer-checked:border-4 border border-jet-gray/30 peer-checked:border-primary cursor-pointer">
                                                                </label>
                                                            @else
                                                                <label for="{{ $inputId }}"
                                                                    class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border border-jet-gray/30 peer-checked:border-primary peer-checked:text-primary cursor-pointer">
                                                                    {{ $option['value'] }}
                                                                </label>
                                                            @endif

                                                            @if (strtolower($attribute['name']) === 'color')
                                                                <label for="{{ $inputId }}"
                                                                    class="block cursor-pointer text-davy-gray text-sm sm:text-base">
                                                                    {{ $option['value'] }}
                                                                </label>
                                                            @endif
                                                        </div>
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

                        @auth
                            <div class="flex flex-wrap w-full gap-3 mt-5 xsm:w-4/5 md:w-11/12 lg:w-4/5">
                                <input type="hidden" name="quantity" class="qtyInputValue" value=""
                                    id="qtyInput{{ $product['id'] }}">

                                <input type="hidden" id="variantSku{{ $product['id'] }}" value="">

                                @if ($product['in_stock'] > 0)
                                    <button data-id="{{ $product['id'] }}" data-modal="{{ true }}" type="button"
                                        class="cartBtn text-sm md:text-base font-medium flex-1 px-6 py-2 border border-primary text-primary rounded-full hover:bg-primary hover:text-white transition-all">
                                        Add To Cart
                                        <span class="block text-xs font-light">{{ $product['discount']['percent'] }}%
                                            Discount</span>
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
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            // Loop through all modals by their ID pattern
            $('[id^="quick-view-modal-"]').each(function() {
                const modal = $(this);
                const productId = this.id.replace('quick-view-modal-', '');
                let quantity = 1;

                const quantityElement = $('#quantity' + productId);
                const decreaseBtn = $('#decreaseBtn' + productId);
                const increaseBtn = $('#increaseBtn' + productId);
                const hiddenInput = $('.qtyInputValue' + productId);


                const updateQuantity = () => {
                    quantityElement.val(quantity.toString().padStart(2, "0"));
                    hiddenInput.val(quantity);
                    updatePriceAndSku();
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

                function updatePriceAndSku() {
                    const checkedOptions = $('.variant-option:checked');
                    const quantityVal = parseInt($('#quantity' + productId).val()) || 1;

                    let basePrice, baseDiscountedPrice, selectedSku = '';
                    let totalAdditionalPrice = 0;

                    if (checkedOptions.length > 0) {
                        basePrice = parseFloat(checkedOptions.first().data('product-price')) || 0;
                        baseDiscountedPrice = parseFloat(checkedOptions.first().data('discounted-price')) ||
                            0;

                        checkedOptions.each(function() {
                            const selected = $(this);
                            const additionalPrice = parseFloat(selected.data('price')) || 0;
                            totalAdditionalPrice += additionalPrice;

                            selectedSku = selected.data('sku');
                        });
                    } else {
                        basePrice = parseFloat($('#productBasePrice' + productId).val()) || 0;
                        baseDiscountedPrice = parseFloat($('#productDiscountedPrice' + productId).val()) ||
                            0;
                    }

                    const finalPrice = basePrice + totalAdditionalPrice;
                    const finalDiscountedPrice = baseDiscountedPrice + totalAdditionalPrice;

                    $('#current-price' + productId).text('৳ ' + (finalDiscountedPrice * quantityVal)
                        .toFixed(2));
                    $('#old-price' + productId).text('৳ ' + (finalPrice * quantityVal).toFixed(2));
                    $('#variantSku' + productId).val(selectedSku);
                }

                function updateSliderThumbnailActive(selectedImageUrl) {
                    const normalizedUrl = selectedImageUrl.trim().toLowerCase();
                    $('.swiper-slide').removeClass('swiper-slide-active swiper-slide-thumb-active');

                    $('.swiper-slide').each(function() {
                        const slide = $(this);
                        const img = $(this).find('img').attr('src')?.trim().toLowerCase();

                        if (img === selectedImageUrl) {
                            console.log("variant image match");
                            slide.addClass('swiper-slide-active swiper-slide-thumb-active');
                            return false;
                        }
                    });

                    console.log($('.swiper-slide-active'))

                    $('.main').removeClass('swiper-slide-active');

                    $('.main').each(function(index) {
                        const mainSlide = $(this)
                        const imgSrc = $(this).find('img').attr('src')?.trim().toLowerCase();
                        if (imgSrc === normalizedUrl) {
                            mainSlide.addClass('swiper-slide-active');
                            return false;
                        }
                    });
                }

                const seenAttributes = new Set();

                $('.variantForm .variant-option').each(function() {
                    const attrId = $(this).data('attribute');

                    if (!seenAttributes.has(attrId)) {
                        seenAttributes.add(attrId);
                        $(this).prop('checked', true);
                    }
                });

                $('.variantForm').on('change', '.variant-option', function() {
                    const changedInput = $(this);
                    const newVariantId = changedInput.data('variant-id');
                    const attributeId = changedInput.data('attribute');
                    const productId = $(this).closest('.variantForm').data('id');
                    const imageUrl = $(this).data('image');

                    $('.variantForm .variant-option').each(function() {
                        const input = $(this);
                        const inputAttributeId = input.data('attribute');

                        if (input.data('variant-id') === newVariantId) {
                            input.prop('checked', true);
                        } else if (inputAttributeId === attributeId) {
                            input.prop('checked', false);
                        }
                    });

                    if (imageUrl) {
                        updateSliderThumbnailActive(imageUrl);
                    }

                    updatePriceAndSku(productId);
                });

                updateQuantity(productId);

            });
        });
    </script>
@endpush
