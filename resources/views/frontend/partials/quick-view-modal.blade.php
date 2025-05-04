<div id="quick-view-modal-{{ $product->id }}" tabindex="-1"
    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full hidden"
    aria-hidden="true">
    <div class="relative container max-h-full">
        <!-- Modal content -->
        <div class="relative shadow-lg bg-white rounded-2xl md:rounded-3xl">
            <!-- Modal Close Triger -->
            <button type="button"
                class="text-white bg-theme-dark hover:bg-theme-dark/80 rounded-full lg:w-10 lg:h-10 w-7 h-7 inline-flex justify-center items-center md:text-2xl text-lg absolute right-4 top-4 z-10"
                data-modal-hide="quick-view-modal-{{ $product->id }}">
                <svg class="svg-inline--fa fa-xmark" aria-hidden="true" focusable="false" data-prefix="fas"
                    data-icon="xmark" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"
                    data-fa-i2svg="">
                    <path fill="currentColor"
                        d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z">
                    </path>
                </svg>
                <!-- <i class="fa-solid fa-xmark"></i> Font Awesome fontawesome.com -->
                <span class="sr-only">Close modal</span>
            </button>
            <!-- Modal body -->
            <div class="p-4 md:p-10">
                <div class="flex flex-col md:flex-row gap-5">
                    <!-- Product Images Section -->
                    <div class="lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5">
                        <!-- Thumbnails -->
                        <div class="lg:w-2/12 xl:w-1/6 w-full order-2 lg:order-1">
                            <div class="modal-product-thumbnails overflow-hidden lg:h-[34rem] xl:h-[32rem] h-auto">
                                <div class="swiper-wrapper">
                                    @foreach ($product->images as $thumb)
                                        <!-- thumb 1 -->
                                        <div class="swiper-slide">
                                            <div
                                                class="modal-slide-thumb w-full xl:h-24 sm:h-24 h-16 rounded-xl md:rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary overflow-hidden">
                                                <img src="{{ storage_url($thumb->image) }}" alt=""
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
                                class="modal-product-swiper w-full h-80 sm:h-[28rem] md:h-[37rem] lg:h-[34rem] xl:h-[32rem] rounded-2xl overflow-hidden relative">
                                <div class="swiper-wrapper">
                                    @foreach ($product->images as $slider)
                                        <!-- product image 1 -->
                                        <div class="swiper-slide h-full rounded-2xl overflow-hidden">
                                            <img src="{{ storage_url($slider->image) }}" alt=""
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
                                class="text-sm justify-center lg:text-base text-rustic-red bg-[#FEEFE1] px-4 py-3 flex flex-wrap flex-col xsm:flex-row md:flex-col lg:flex-row xsm:justify-between items-center">
                                <div class="flex items-center gap-2 text-center">
                                    <svg class="svg-inline--fa fa-check text-theme-teal" aria-hidden="true"
                                        focusable="false" data-prefix="fas" data-icon="check" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z">
                                        </path>
                                    </svg>
                                    <!-- <i class="fa-solid fa-check text-theme-teal"></i> Font Awesome fontawesome.com -->
                                    <span>Free shipping special for you</span>
                                </div>
                                <span class="text-jet-gray font-light">Exclusive offer</span>
                            </div>
                            <h1 class="lg:text-base text-rustic-red text-sm lg:pr-5 xl:pr-16">
                                <a href="{{ route('products.details', $product->slug) }}" class="hover:text-primary eq">
                                    {{ $product->name }}
                                </a>
                            </h1>
                            <div
                                class="flex flex-wrap items-center gap-2 xsm:gap-x-5 sm:gap-x-10 md:gap-2 lg:gap-x-10 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-jet-gray border-r border-gray-400 pr-2 text-nowrap">486
                                        sold</span>
                                    <div class="flex flex-wrap items-center gap-x-2 text-davy-gray">
                                        <span>Provided By</span>
                                        <a href="{{ route('sellers.shop', $product->seller->username) }}"
                                            class="inline-block provider-icon w-6 h-6 overflow-hidden rounded-full">
                                            <img src="{{ storage_url($product->seller->business_logo) }}"
                                                alt="Louis Vuitton" class="h-full w-full object-contain">
                                        </a>
                                        <span>({{ number_shorten_format($product->stock_out) }}+ sold)</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $averageRating = $product->reviews->avg('rating');
                                        $averageRating = number_format($averageRating, 2); // Format to 2 decimal places
                                        $fullStars = floor($averageRating);
                                        $halfStar = $averageRating - $fullStars >= 0.5 ? true : false;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    @endphp

                                    <span class="text-xs">{{ $averageRating }} Star</span>

                                    <!-- Star display -->
                                    <span class="text-yellow-500 text-sm">
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            ★
                                        @endfor

                                        @if ($halfStar)
                                            <span class="opacity-50">★</span>
                                        @endif

                                        @for ($i = 0; $i < $emptyStars; $i++)
                                            <span class="text-gray-300">★</span>
                                        @endfor
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best
                                    Seller</span>
                                <p class="text-davy-gray text-sm">
                                    From this provider
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <div class="new-price flex items-center gap-1 flex-no-wrap">
                                    <svg class="svg-inline--fa fa-bolt text-[#ffa755]" aria-hidden="true"
                                        focusable="false" data-prefix="fas" data-icon="bolt" role="img"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                        <path fill="currentColor"
                                            d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288l111.5 0L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7l-111.5 0L349.4 44.6z">
                                        </path>
                                    </svg>


                                    <span class="align-center text-sm text-[#ffa755]">{{ currency() }}</span>
                                    <h3 class="current-price font-bold text-primary">
                                        {{ $product->discounted_price }}
                                    </h3>
                                </div>
                                <h6 class="old-price text-jet-gray line-through">
                                    {{ money($product->selling_price) }}
                                </h6>
                                <span class="text-xs px-2.5 py-0.5 rounded-lg border border-primary">-69% last 2
                                    days</span>
                                <span class="text-leaf-green text-xs">Almost Sold Out</span>
                            </div>
                        </div>
                        <div
                            class="user-action rounded-lg border-primary border-2 overflow-hidden mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5">
                            <!-- Special Sale Banner -->
                            <div
                                class="bg-primary text-sm md:text-base text-white px-4 py-1 flex justify-between items-center">
                                <span>Special Sale | Two Days Left</span>
                                <svg class="svg-inline--fa fa-arrow-right" aria-hidden="true" focusable="false"
                                    data-prefix="fas" data-icon="arrow-right" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z">
                                    </path>
                                </svg>
                            </div>
                            <div class="clr-size-qty p-4">
                                <!-- Color Selection -->

                                <!-- Quantity -->
                                <div class="quantity mt-3">
                                    <div class="text-davy-gray flex items-center gap-2">
                                        <h6 class="sm:text-lg">Quantity :</h6>
                                        <div class="flex items-center border rounded p-1">
                                            <button id="decreaseBtn-{{ $product->id }}"
                                                class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input readonly id="quantity-{{ $product->id }}" type="number"
                                                min="1"
                                                class="text-center text-persian-blue w-12 h-5 text-sm font-medium border-0 focus:ring-0" />
                                            <button id="increaseBtn-{{ $product->id }}"
                                                class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                        <span class="text-davy-gray text-xs">In Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Action Button -->
                        @auth
                            <div class="flex gap-4 mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5">
                                <input type="hidden" name="quantity" class="qtyInputValue" value=""
                                    id="qtyInput{{ $product->id }}">
                                <button data-id="{{ $product->id }}" type="button"
                                    class="cartBtn text-sm md:text-base font-medium flex-1 px-6 py-2.5 bg-primary text-white rounded-full hover:bg-theme-dark eq">
                                    Add To Cart
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
            $('.quantity').each(function() {
                const $container = $(this);
                const quantityInput = $container.find('input[type="number"]');
                const increaseBtn = $container.find('button[id^="increaseBtn"]');
                const decreaseBtn = $container.find('button[id^="decreaseBtn"]');

                let quantity = 1;

                const updateQuantity = () => {
                    quantityInput.val(quantity.toString().padStart(2, '0'));
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

                quantityInput.on('input', function() {
                    const newQuantity = parseInt($(this).val());
                    quantity = newQuantity > 0 ? newQuantity : 1;
                    updateQuantity();
                });

                updateQuantity();
            });
        });
    </script>
@endpush
