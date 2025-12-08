<?php

use App\Models\Banner;

$heroBanners = $banners[Banner::SECTION_HERO] ?? [];
$categoryBanners = [];
if (isset($banners[Banner::SECTION_CATEGORY_TOP])) {
    $categoryBanners = $banners[Banner::SECTION_CATEGORY_TOP]->take(2);
}
?>

<section class="container mx-auto">
    <div
        class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-6 auto-rows-[minmax(200px,auto)] h-auto lg:h-[500px]">
        <!-- HERO SLIDER -->
        <div
            class="col-span-1 lg:col-span-3 relative rounded-md overflow-hidden shadow-lg group bg-gray-900 h-[200px] sm:h-[320px] md:h-[400px] lg:h-full"
            id="hero-slider">
            @foreach($heroBanners as $index => $banner)
            <div
                class="absolute inset-0 transition-opacity duration-700 ease-in-out hero-slide {{ $index === 0 ? 'opacity-100 z-20' : 'opacity-0 z-10' }}"
                data-index="{{ $index }}">
                <img
                    src="{{ storage_url($banner->image) }}"
                    alt="{{ $banner->title }}"
                    class="w-full h-full object-cover">
                {{-- overlay/text intentionally commented out --}}
            </div>
            @endforeach

            @if(count($heroBanners) > 1)
            <div
                class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-40">
                @foreach($heroBanners as $index => $banner)
                <button
                    onclick="goToSlide({{ $index }})"
                    class="slider-dot transition-all duration-300 rounded-full {{ $index === 0 ? 'bg-primary-500 w-6 h-3' : 'bg-white/40 hover:bg-white/70 w-3 h-3' }}"></button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- CATEGORY BANNERS (hidden on smaller screens) -->
        <div class="hidden lg:flex flex-col gap-6 h-full">
            @foreach($categoryBanners as $banner)
            <div
                class="relative rounded-md overflow-hidden h-1/2 shadow-md group">
                <img
                    src="{{ asset('storage/' . $banner->image) }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                    alt="{{ $banner->title }}">
                {{-- overlay/text intentionally commented out --}}
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-slide.opacity-100 .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .hero-slide.opacity-0 .animate-fade-in-up {
        opacity: 0;
    }

    /* Slider Dot Fix */
    .slider-dot {
        border: none;
        outline: none;
        cursor: pointer;
    }

    .slider-dot.w-6 {
        transition-property: all;
    }

    .slider-dot:hover {
        transform: scale(1.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.slider-dot');
        let currentSlide = 0;
        let slideInterval;

        function showSlide(index) {
            slides[currentSlide].classList.remove('opacity-100', 'z-20');
            slides[currentSlide].classList.add('opacity-0', 'z-10');

            if (dots[currentSlide]) {
                dots[currentSlide].classList.remove('bg-primary-500', 'w-6', 'h-3');
                dots[currentSlide].classList.add('bg-white/40', 'w-3', 'h-3');
            }

            currentSlide = index;

            slides[currentSlide].classList.remove('opacity-0', 'z-10');
            slides[currentSlide].classList.add('opacity-100', 'z-20');

            if (dots[currentSlide]) {
                dots[currentSlide].classList.remove('bg-white/40', 'w-3', 'h-3');
                dots[currentSlide].classList.add('bg-primary-500', 'w-6', 'h-3');
            }
        }

        function nextSlide() {
            showSlide((currentSlide + 1) % slides.length);
        }

        window.goToSlide = function(index) {
            clearInterval(slideInterval);
            showSlide(index);
            startSlider();
        };

        function startSlider() {
            if (slides.length > 1) {
                slideInterval = setInterval(nextSlide, 5000);
            }
        }

        if (slides.length > 0) startSlider();
    });
</script>