<?php

use App\Domain\Product\Models\Banner;

$heroBanners = $banners[Banner::SECTION_HERO] ?? [];
$categoryBanners = [];
if (isset($banners[Banner::SECTION_CATEGORY_TOP])) {
    $categoryBanners = $banners[Banner::SECTION_CATEGORY_TOP]->take(2);
}
$heroCount = is_countable($heroBanners) ? count($heroBanners) : 0;
?>

<section class="mb-4">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 h-auto lg:h-[400px]">
        <!-- HERO SLIDER -->
        <div class="col-span-1 lg:col-span-3 relative rounded-sm overflow-hidden bg-[#F5F5F5] h-[200px] sm:h-[300px] md:h-[360px] lg:h-full">
            <div class="swiper hero-swiper w-full h-full">
                <div class="swiper-wrapper">
                    @forelse ($heroBanners as $banner)
                        <div class="swiper-slide relative h-full">
                            @if ($banner->button_link)
                                <a href="{{ $banner->button_link }}" aria-label="{{ $banner->title }}" class="block w-full h-full">
                            @endif
                            <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}"
                                class="w-full h-full object-cover">
                            @if ($banner->title || $banner->subtitle || $banner->button_text)
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none">
                                    <div class="absolute bottom-6 left-6 right-6 text-white">
                                        @if ($banner->subtitle)
                                            <p class="text-xs sm:text-sm font-medium text-[#F85606] mb-1">{{ $banner->subtitle }}</p>
                                        @endif
                                        @if ($banner->title)
                                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold leading-tight">{{ $banner->title }}</h2>
                                        @endif
                                        @if ($banner->button_text)
                                            <span class="inline-block mt-2 px-4 py-1.5 bg-[#F85606] text-white text-xs sm:text-sm font-medium rounded">{{ $banner->button_text }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if ($banner->button_link)
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="swiper-slide flex items-center justify-center bg-[#F5F5F5] text-[#C7C7C7] text-sm h-full">
                            No hero banners
                        </div>
                    @endforelse
                </div>

                @if ($heroCount > 1)
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                @endif
            </div>
        </div>

        <!-- CATEGORY QUICK PANELS -->
        <div class="hidden lg:flex flex-col gap-3 h-full">
            @forelse ($categoryBanners as $banner)
                <div class="relative rounded-sm overflow-hidden flex-1 bg-[#F5F5F5] group/panel">
                    @if ($banner->button_link)
                        <a href="{{ $banner->button_link }}">
                    @endif
                    <img src="{{ storage_url($banner->image) }}" class="w-full h-full object-cover group-hover/panel:scale-105 eq"
                        alt="{{ $banner->title }}">
                    @if ($banner->title)
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent">
                            <div class="absolute bottom-3 left-3 right-3">
                                <h3 class="text-white text-sm font-semibold">{{ $banner->title }}</h3>
                            </div>
                        </div>
                    @endif
                    @if ($banner->button_link)
                        </a>
                    @endif
                </div>
            @empty
                <div class="flex-1 rounded-sm bg-[#F5F5F5] flex items-center justify-center text-[#C7C7C7] text-sm border border-dashed border-[#E5E5E5]">
                    Category Banner
                </div>
                <div class="flex-1 rounded-sm bg-[#F5F5F5] flex items-center justify-center text-[#C7C7C7] text-sm border border-dashed border-[#E5E5E5]">
                    Category Banner
                </div>
            @endforelse
        </div>
    </div>
</section>
