<div
    class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq">
    <div class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg">
        <a href="{{ route('products.details', $product['slug']) }}" class="block w-full h-full">
            <img src="{{ storage_url($product['thumbnail']) }}" alt="{{ $product['name'] }}"
                class="w-full h-full object-cover" />
        </a>

        <!-- Quick View Toggle -->
        <button type="button" data-modal-target="quick-view-modal-{{ $product['id'] }}"
            data-modal-toggle="quick-view-modal-{{ $product['id'] }}"
            class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
            <i class="fa-regular fa-eye"></i>
            Quick View
        </button>
    </div>

    <div class="p-4 xsm:p-2 lg:p-5">
        <h3 class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14">
            <a href="{{ route('products.details', $product['slug']) }}"
                class="hover:text-primary eq">{{ $product['name'] }}</a>
        </h3>
        @if ($product['almost_sold_out'])
            <span class="text-xs text-leaf-green">Almost Sold Out</span>
        @endif

        <div class="flex flex-wrap items-center gap-x-1">
            @if ($product['rating'] > 0)
                <div class="flex items-center flex-no-wrap gap-x-1 text-light-yellow">
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
                    <img src="{{ asset('assets/frontend/images/fire-icon.png') }}" class="w-8 h-auto" alt="Fire Icon" />
                </div>
            @endif
            @if ($product['sold_out'] > 0)
                <span class="text-jet-gray">{{ number_shorten_format($product['sold_out']) }}+ Sold</span>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
            <span class="text-primary/80">Final Hours</span>
            <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                <div class="price flex items-center gap-1 flex-no-wrap">
                    <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                    <h3 class="font-bold text-primary">{{ money($product['price']) }}</h3>
                </div>
                @auth
                    <div>
                        <input type="hidden" name="quantity" class="qtyInputValue" value=""
                            id="qtyInput{{ $product['id'] }}">
                        <button data-id="{{ $product['id'] }}" type="button"
                            class="cartBtn text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
