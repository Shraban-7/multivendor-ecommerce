<?php

use App\Domain\Product\Models\Banner;

$heroBanners = $banners[Banner::SECTION_HERO] ?? [];
$categoryBanners = [];
if (isset($banners[Banner::SECTION_CATEGORY_TOP])) {
    $categoryBanners = $banners[Banner::SECTION_CATEGORY_TOP]->take(2);
}
?>

<section class="mb-4">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 h-auto lg:h-[400px]">
        <!-- HERO SLIDER -->
        <div class="col-span-1 lg:col-span-3 relative rounded-sm overflow-hidden bg-[#F5F5F5] h-[200px] sm:h-[300px] md:h-[360px] lg:h-full group/slider" id="hero-slider">
            @foreach($heroBanners as $index => $banner)
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out hero-slide {{ $index === 0 ? 'opacity-100 z-20' : 'opacity-0 z-10' }}" data-index="{{ $index }}">
                @if ($banner->button_link)
                    <a href="{{ $banner->button_link }}" aria-label="{{ $banner->title }}">
                @endif
                <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                @if ($banner->title || $banner->subtitle || $banner->button_text)
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent">
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        @if ($banner->subtitle)
                            <p class="text-xs sm:text-sm font-medium text-[#F85606] mb-1">{{ $banner->subtitle }}</p>
                        @endif
                        @if ($banner->title)
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold leading-tight">{{ $banner->title }}</h2>
                        @endif
                        @if ($banner->button_text)
                            <span class="inline-block mt-2 px-4 py-1.5 bg-[#F85606] text-white text-xs sm:text-sm font-medium rounded hover:bg-[#C43D00] eq">{{ $banner->button_text }}</span>
                        @endif
                    </div>
                </div>
                @endif
                @if ($banner->button_link)
                    </a>
                @endif
            </div>
            @endforeach

            <!-- Arrows -->
            @if (count($heroBanners) > 1)
            <button onclick="prevSlide()" class="absolute left-2 top-1/2 -translate-y-1/2 z-30 w-9 h-9 rounded-full bg-white/80 hover:bg-white eq flex items-center justify-center opacity-0 group-hover/slider:opacity-100 shadow-md" aria-label="Previous slide">
                <svg class="w-4 h-4 text-[#191919]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button onclick="nextSlide()" class="absolute right-2 top-1/2 -translate-y-1/2 z-30 w-9 h-9 rounded-full bg-white/80 hover:bg-white eq flex items-center justify-center opacity-0 group-hover/slider:opacity-100 shadow-md" aria-label="Next slide">
                <svg class="w-4 h-4 text-[#191919]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endif

            <!-- Dots -->
            @if (count($heroBanners) > 1)
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-30">
                @foreach($heroBanners as $index => $banner)
                <button onclick="goToSlide({{ $index }})" aria-label="Slide {{ $index + 1 }} of {{ count($heroBanners) }}"
                    class="slider-dot rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-[#F85606] w-6 h-2' : 'bg-white/70 hover:bg-white w-2 h-2' }}"></button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- CATEGORY QUICK PANELS -->
        <div class="hidden lg:flex flex-col gap-3 h-full">
            @forelse($categoryBanners as $banner)
            <div class="relative rounded-sm overflow-hidden flex-1 bg-[#F5F5F5] group/panel">
                @if ($banner->button_link)
                    <a href="{{ $banner->button_link }}">
                @endif
                <img src="{{ storage_url($banner->image) }}" class="w-full h-full object-cover group-hover/panel:scale-105 eq" alt="{{ $banner->title }}">
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

<style>
    .slider-dot { border: none; outline: none; cursor: pointer; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        if (!slides.length) return;

        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            slides[currentSlide].classList.remove('opacity-100', 'z-20');
            slides[currentSlide].classList.add('opacity-0', 'z-10');
            if (dots[currentSlide]) {
                dots[currentSlide].classList.remove('bg-[#F85606]', 'w-6', 'h-2');
                dots[currentSlide].classList.add('bg-white/70', 'w-2', 'h-2');
            }
            currentSlide = index;
            slides[currentSlide].classList.remove('opacity-0', 'z-10');
            slides[currentSlide].classList.add('opacity-100', 'z-20');
            if (dots[currentSlide]) {
                dots[currentSlide].classList.remove('bg-white/70', 'w-2', 'h-2');
                dots[currentSlide].classList.add('bg-[#F85606]', 'w-6', 'h-2');
            }
        }

        window.nextSlide = function() {
            clearInterval(slideInterval);
            showSlide((currentSlide + 1) % slides.length);
            startSlider();
        };

        window.prevSlide = function() {
            clearInterval(slideInterval);
            showSlide((currentSlide - 1 + slides.length) % slides.length);
            startSlider();
        };

        window.goToSlide = function(index) {
            clearInterval(slideInterval);
            showSlide(index);
            startSlider();
        };

        function startSlider() {
            if (slides.length > 1) {
                slideInterval = setInterval(() => showSlide((currentSlide + 1) % slides.length), 5000);
            }
        }

        startSlider();
    });
</script>
