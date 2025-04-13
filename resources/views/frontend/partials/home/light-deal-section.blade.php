<section class="light-deals-section">
    <!-- promotional header -->
    <div class="section-promo-header bg-[#FF4F4F]">
        <div class="container flex flex-col items-center justify-between gap-3 py-3 md:flex-row md:gap-0 md:py-5">
            <!-- star icon -->
            <span><svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M28 0C28.9506 15.0527 40.9472 27.0495 56 28C40.9472 28.9506 28.9506 40.9472 28 56C27.0495 40.9472 15.0527 28.9506 0 28C15.0527 27.0495 27.0495 15.0527 28 0Z"
                        fill="white" />
                </svg>
            </span>
            <!-- promo title -->
            <h2 class="flex flex-col items-center gap-2 text-3xl font-semibold md:flex-row md:gap-5 text-theme-light">
                <p>
                    <span><i class="fa-solid fa-bolt"></i></span>
                    Light deals
                </p>
                <p class="text-base font-medium">
                    Limited Time Offer
                    <span class="text-xs"><i class="fa-solid fa-chevron-right"></i></span>
                </p>
            </h2>
            <!-- star icon -->
            <span><svg width="56" height="56" viewBox="0 0 56 56" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M28 0C28.9506 15.0527 40.9472 27.0495 56 28C40.9472 28.9506 28.9506 40.9472 28 56C27.0495 40.9472 15.0527 28.9506 0 28C15.0527 27.0495 27.0495 15.0527 28 0Z"
                        fill="white" />
                </svg>
            </span>
        </div>
    </div>

    <!-- light deals swiper carousel -->
    <div class="container">
        <div class="swiper lightDealsSwiper">
            <div class="swiper-wrapper">
                <!-- slide 1 -->
                @foreach ($light_deals as $light_deal)
                    <div class="px-1 py-5 swiper-slide">
                        <a href="{{ route('product.details', $light_deal->slug) }}" class="block w-full p-3 rounded-lg product-card hover:shadow-lg eq group">
                            {{-- {{ route('product.details', $light_deal->slug) }} --}}
                            <!-- slide image -->
                            <div class="card-image h-[16.5rem] relative rounded-lg overflow-hidden">
                                <img src="{{ storage_url($light_deal->thumbnail) }}" alt="{{ $light_deal->name }}"
                                    class="object-cover w-full h-full group-hover:scale-125 eq" />
                                <span
                                    class="absolute block w-3/5 px-4 py-3 text-sm text-center -translate-x-1/2 bg-white rounded-full bottom-9 left-1/2">Almost
                                    Sold Out</span>
                                <!-- Quick View Toggle -->
                                {{-- <button type="button" data-modal-target="quick-view-modal-{{ $light_deal->id }}"
                                data-modal-toggle="quick-view-modal-{{ $light_deal->id }}"
                                class="absolute block w-3/5 px-4 py-3 text-sm text-center -translate-x-1/2 bg-white rounded-full bottom-9 left-1/2">
                                <i class="fa-regular fa-eye"></i>
                                <span class="text-sm">Quick View</span>
                            </button> --}}
                            </div>
                            <!-- Slide Content -->
                            <div class="mt-2 space-y-1 card-content">
                                <!-- price & sold info -->
                                <div class="flex items-center gap-2 price-sold-amount">
                                    <h2 class="text-2xl font-bold text-primary">
                                        <span><i class="fa-solid fa-bolt text-[#ffa755]"></i></span>
                                        <span class="align-middle text-xs text-[#ffa755]">{{ CURRENCY_SYMBOL }}</span>
                                        {{ number_format($light_deal->selling_price, 2) }}
                                    </h2>
                                    <p class="text-base">{{ number_shorten_format($light_deal->stock_out) }}+ Sold
                                        Out</p>
                                </div>
                                <!-- time -->
                                @php
                                    $sold_out_progress = ($light_deal->stock_out / ($light_deal->stock_out + $light_deal->stock_in)) * 100;
                                @endphp
                                <div class="flex flex-wrap items-center gap-2 time-progres">
                                    <div class="w-[60%] bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full progress bg-primary"
                                            style="width: {{ percentage($sold_out_progress) }}"></div>
                                    </div>
                                    <span
                                        class="w-[35%] due-time text-sm inline-flex flex-no-wrap gap-1 items-center"><i
                                            class="fa-regular fa-clock"></i>
                                        {{ datetime_format($light_deal->lightdeal_expired_at) }}</span>
                                </div>
                                <!-- rating -->
                                <div class="flex items-center gap-2">
                                    <div class="text-xs rating-stars text-light-yellow">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                    <span class="text-sm text-primary">Final Hours</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @foreach ($light_deals as $product)
                <!-- Quick View Modal -->
                <div id="quick-view-modal-{{ $product->id }}" tabindex="-1"
                    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full hidden"
                    aria-hidden="true">
                    <div class="relative container max-h-full">
                        <!-- Modal content -->
                        <div class="relative shadow-lg bg-white rounded-2xl md:rounded-3xl">
                            <!-- Modal Close Triger -->
                            <button type="button"
                                class="text-white bg-theme-dark hover:bg-theme-dark/80 rounded-full lg:w-10 lg:h-10 w-7 h-7 inline-flex justify-center items-center md:text-2xl text-lg absolute right-4 top-4 z-10"
                                data-modal-hide="quick-view-modal">
                                <svg class="svg-inline--fa fa-xmark" aria-hidden="true" focusable="false"
                                    data-prefix="fas" data-icon="xmark" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z">
                                    </path>
                                </svg>
                                <!-- <i class="fa-solid fa-xmark"></i> Font Awesome fontawesome.com -->
                                <span class="sr-only">Close modal</span>
                            </button>
                            <!-- Modal body -->
                            <div class="p-4 md:p-10">
                                <!-- Product Contents  -->
                                <div class="flex flex-col gap-5 md:flex-row">
                                    <!-- Product Images Section -->
                                    <div class="lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5">
                                        <!-- Thumbnails -->
                                        <div class="order-2 w-full space-y-3 lg:w-1/6 lg:order-1">
                                            <div
                                                class="product-thumbnails overflow-hidden xl:h-[37rem] lg:h-[41rem] h-auto">
                                                <div class="swiper-wrapper">
                                                    <!-- thumb 1 -->
                                                    @foreach ($product->images as $thumb)
                                                        <div class="swiper-slide">
                                                            <div
                                                                class="w-full h-20 overflow-hidden border-2 border-transparent cursor-pointer slide-thumb xl:h-24 md:h-22 lg:h-28 rounded-2xl hover:border-primary">
                                                                <img src="{{ storage_url($thumb->image) }}"
                                                                    alt="Product thumbnail of A Young boy wear a jacket with green T-Shirt & Short Pant"
                                                                    class="object-cover w-full h-full" />
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <!-- Repeat thumb for more thumbnails -->
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Main Image Slider -->
                                        <div class="relative order-1 w-full lg:w-5/6 lg:order-2">
                                            <div
                                                class="product-swiper overflow-hidden w-full h-96 md:h-[37rem] xl:h-[37rem] lg:h-[41rem] rounded-2xl overflow-hidden relative">
                                                <div class="swiper-wrapper">
                                                    <!-- product image 1 -->
                                                    @foreach ($product->images as $slider)
                                                        <div class="h-full overflow-hidden swiper-slide rounded-2xl">
                                                            <img src="{{ storage_url($slider->image) }}"
                                                                alt="A Young boy wear a jacket with green T-Shirt & Short Pant"
                                                                class="object-cover w-full h-full" />
                                                        </div>
                                                    @endforeach
                                                    <!-- Repeat product image for more slides -->
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
                                                class="text-sm justify-center lg:text-base text-rustic-red bg-[#FEEFE1] px-4 py-3 flex flex-wrap flex-col xsm:flex-row justify-between items-center">
                                                <div class="flex items-center gap-2 text-center">
                                                    <i class="fa-solid fa-check text-theme-teal"></i>
                                                    <span>Free shipping special for you</span>
                                                </div>
                                                <span class="font-light text-jet-gray">Exclusive offer</span>
                                            </div>

                                            <h1 class="text-sm lg:text-base text-rustic-red lg:pr-5 xl:pr-16">
                                                {{ $product->name }}
                                            </h1>

                                            <div
                                                class="flex flex-wrap items-center gap-2 text-sm xsm:gap-5 sm:10 md:gap-2 lg:gap-10">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="pr-2 border-r border-gray-400 text-jet-gray">{{ number_shorten_format($product->stock_out) }}
                                                        sold</span>
                                                    <div class="flex items-center gap-2 text-davy-gray">
                                                        <span>Provided By</span>
                                                        <a href="{{ route('seller.shop_details', $product->seller->username) }}"
                                                            class="inline-block w-6 h-6 overflow-hidden rounded-full provider-icon">
                                                            <img src="{{ asset('assets/' . $product->seller->business_logo) }}"
                                                                alt="Louis Vuitton"
                                                                class="object-contain w-full h-full" />
                                                        </a>
                                                        <span>({{ number_shorten_format($product->stock_out) }}+
                                                            sold)</span>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <!-- 5 star rating -->
                                                    <span class="text-xs">5.00 Star</span>
                                                    <!-- Repeat for 5 stars -->
                                                    <span>★★★★★</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full">Best
                                                    Seller</span>
                                                <p class="text-sm text-davy-gray">From this provider</p>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="flex flex-no-wrap items-center gap-1 new-price">
                                                    <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                                    {{-- <span class="align-center text-sm text-[#ffa755]">$</span> --}}
                                                    @php
                                                        if ($product->discount_type != null) {
                                                            if (
                                                                $product->discount_type == \App\Enums\DiscountType::FLAT
                                                            ) {
                                                                $price =
                                                                    $product->selling_price - $product->discount_amount;
                                                            } elseif (
                                                                $product->discount_type ==
                                                                \App\Enums\DiscountType::PERCENTAGE
                                                            ) {
                                                                $price =
                                                                    $product->selling_price -
                                                                    ($product->selling_price *
                                                                        $product->discount_amount) /
                                                                        100;
                                                            }
                                                        } else {
                                                            $price = $product->selling_price;
                                                        }
                                                    @endphp
                                                    <h3 id="current-price"
                                                        class="font-bold current-price text-primary">
                                                        {{ money($price) }}</h3>
                                                </div>
                                                <h6 class="line-through old-price text-jet-gray">
                                                    {{ money($product->selling_price) }}
                                                </h6>
                                                <span
                                                    class="text-xs px-2.5 py-0.5 rounded-lg border border-primary">-{{ money($product->discount_amount) }}
                                                    last 2
                                                    days</span>
                                                <span class="text-xs text-leaf-green">Almost Sold Out</span>
                                            </div>
                                        </div>

                                        <div
                                            class="w-full mt-5 overflow-hidden border-2 rounded-lg user-action border-primary xsm:w-4/5 md:w-11/12 lg:w-4/5">
                                            <!-- Special Sale Banner -->
                                            <div
                                                class="flex items-center justify-between px-4 py-1 text-sm text-white bg-primary md:text-base">
                                                <span>Special Sale | Two Days Left</span>
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </div>

                                            <div class="p-4 clr-size-qty">
                                                <!-- Color Selection -->

                                                <!-- Size Selection -->
                                                @foreach ($product->productAttributes as $productAttribute)
                                                    <div class="mt-3 size">
                                                        <div class="flex items-center gap-2 text-davy-gray">
                                                            <h6 class="sm:text-lg">{{ $productAttribute->name }} :
                                                            </h6>
                                                            {{-- <a href="#"
                                                        class="inline-flex items-center hover:text-violet-700 hover:underline eq">
                                                        <img src="{{ asset('assets/frontend/images/size-scale.png') }}"
                                                            alt="Size Chart" class="w-10 h-auto xsm:w-14" />
                                                        <span class="text-xs"> Size Chart</span>
                                                    </a> --}}
                                                            {{-- <a href="#"
                                                        class="ml-2 hover:text-light-yellow hover:underline eq xsm:ml-4">
                                                        <span class="text-xs"> What's My Size?</span>
                                                    </a> --}}
                                                        </div>
                                                        <form class="flex flex-wrap items-center gap-2 mt-2 text-xs">
                                                            @foreach ($productAttribute->options as $option)
                                                                <div class="form-ctrl">
                                                                    <input id="{{ $option->value }}" type="radio"
                                                                        value="{{ $option->value }}"
                                                                        data-additional-price="{{ $option->additional_price }}"
                                                                        name="product_attribute_{{ $productAttribute->id }}"
                                                                        class="hidden peer option-selector" />
                                                                    <label for="{{ $option->value }}"
                                                                        class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer">{{ strtoupper($option->value) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </form>

                                                    </div>
                                                @endforeach
                                                <!-- Quantity -->
                                                <div class="mt-3 quantity">
                                                    <div class="flex items-center gap-2 text-davy-gray">
                                                        <h6 class="sm:text-lg">Quantity :</h6>
                                                        <div class="flex items-center p-1 border rounded">
                                                            <button id="decreaseBtn"
                                                                class="flex items-center justify-center w-5 h-5 text-sm font-bold rounded text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary">
                                                                <i class="fa-solid fa-minus"></i>
                                                            </button>
                                                            <input readonly id="quantity" type="number"
                                                                min="1"
                                                                class="w-12 h-5 text-sm font-medium text-center border-0 text-persian-blue focus:ring-0" />
                                                            <button id="increaseBtn"
                                                                class="flex items-center justify-center w-5 h-5 text-sm font-bold rounded text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <span class="text-xs text-davy-gray">In Stock</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Action Buttons -->
                                        @php
                                            $discount = ($product->discount_amount / $product->selling_price) * 100;
                                        @endphp
                                        <div class="flex w-full gap-4 mt-5 xsm:w-4/5 md:w-11/12 lg:w-4/5">
                                            <input type="hidden" name="quantity" value="1"
                                                id="qtyInput{{ $product->id }}">
                                            <button data-id="{{ $product->id }}" type="button"
                                                class="cartBtn text-sm md:text-base font-medium flex-1 px-6 py-1.5 border border-primary text-primary rounded-full hover:bg-primary hover:text-white eq">
                                                Add To Cart
                                                <span class="block text-xs font-light">{{ percentage($discount) }} of
                                                    Discount</span>
                                            </button>
                                            <button
                                                class="text-sm md:text-base font-medium flex-1 px-6 py-1.5 bg-primary text-white rounded-full hover:bg-theme-dark eq">
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
            @endforeach
        </div>
    </div>
</section>
