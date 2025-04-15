<div
    class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq">
    <div class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg">
        <a href="#" class="block w-full h-full">
            <img src="{{ storage_url($product->thumbnail) }}"
                alt="{{ $product->name }}" class="w-full h-full object-cover" />
        </a>

        <!-- Quick View Toggle -->
        <button type="button" data-modal-target="quick-view-modal-{{ $product->id }}" data-modal-toggle="quick-view-modal-{{ $product->id }}"
            class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
            <i class="fa-regular fa-eye"></i>
            Quick View
        </button>
    </div>

    <div class="p-4 xsm:p-2 lg:p-5">
        <h3 class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14">
            <a href="#" class="hover:text-primary eq">{{ $product->name }}</a>
        </h3>
        <p class="text-leaf-green">Almost sold Out</p>

        <div class="flex flex-wrap items-center gap-x-1">
            <div class="flex items-center flex-no-wrap gap-x-1 text-light-yellow">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <img src="{{ asset('assets/frontend/images/fire-icon.png') }}" class="w-8 h-auto" alt="Fire Icon" />
            </div>

            <span class="text-jet-gray">{{ number_shorten_format($product->stock_out) }}+ Sold</span>
        </div>

        <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
            <span class="text-primary/80">Final Hours</span>
            <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                <div class="price flex items-center gap-1 flex-no-wrap">
                    <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                    <span class="align-center text-sm text-[#ffa755]">{{ currency() }}</span>
                    <h3 class="font-bold text-primary">{{ number_format($product->selling_price,2) }}</h3>
                </div>
                <div>
                    <button
                        class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


