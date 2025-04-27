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
                        <a href="{{ route('products.details', $light_deal->slug) }}"
                            class="block w-full p-3 rounded-lg product-card hover:shadow-lg eq group">
                            {{-- {{ route('products.details', $light_deal->slug) }} --}}
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
                                    $sold_out_progress =
                                        ($light_deal->stock_out / ($light_deal->stock_out + $light_deal->stock_in)) *
                                        100;
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
        </div>
    </div>
</section>
