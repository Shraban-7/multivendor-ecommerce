<?php

use App\Domain\Product\Models\Banner;
$midBanner = null;
if (isset($banners[Banner::SECTION_MID_PROMO]) && $banners[Banner::SECTION_MID_PROMO]->count() > 0) {
    $midBanner = $banners[Banner::SECTION_MID_PROMO]->first();
}
?>

@if($midBanner)
<section class="container mx-auto pb-5">
    <div class="relative rounded-2xl overflow-hidden shadow-xl h-44 md:h-80 flex items-center group">
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
            style="background-image: url('{{ storage_url($midBanner->image) }}');">
        </div>

        @if(!empty($midBanner->title))
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent"></div>
        @endif
        
        @if(!empty($midBanner->title))
        <div class="relative z-10 px-8 md:px-16 py-6 max-w-2xl">
            @if($midBanner->subtitle)
            <span class="text-primary-500 font-semibold tracking-widest text-sm uppercase mb-3 block">
                {{ $midBanner->subtitle }}
            </span>
            @endif

            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight drop-shadow-lg">
                {!! nl2br(e($midBanner->title)) !!}
            </h2>

            @if($midBanner->button_link)
            <a href="{{ $midBanner->button_link }}"
                class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-full font-bold transition shadow-lg shadow-primary-600/40 inline-flex items-center gap-2 transform hover:-translate-y-1">
                {{ $midBanner->button_text ?? 'Check Offers' }}
                <i class="fas fa-arrow-right"></i>
            </a>
            @endif
        </div>
        @endif
    </div>
</section>
@endif