<?php

use App\Models\Banner;

$heroBanners = $banners[Banner::SECTION_HERO] ?? [];
$categoryBanners = [];
if (isset($banners[Banner::SECTION_CATEGORY_TOP])) {
    $categoryBanners = $banners[Banner::SECTION_CATEGORY_TOP]->take(2);
}
?>

<section class="container mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-[350px] md:h-[450px] lg:h-[500px]">
        <div class="lg:col-span-3 relative rounded-2xl overflow-hidden shadow-lg group bg-gray-900" id="hero-slider">

            @foreach($heroBanners as $index => $banner)
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out hero-slide {{ $index === 0 ? 'opacity-100 z-20' : 'opacity-0 z-10' }}" data-index="{{ $index }}">
                <img src="{{ storage_url($banner->image) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover opacity-80">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

                <div class="absolute inset-0 flex flex-col justify-center px-8 lg:px-16 z-30">
                    @if($banner->subtitle)
                    <span class="text-primary-500 font-bold tracking-[0.2em] uppercase mb-3 animate-fade-in-up">
                        {{ $banner->subtitle }}
                    </span>
                    @endif

                    @if($banner->title)
                    <h1 class="text-4xl lg:text-6xl font-extrabold text-white mb-4 leading-tight max-w-2xl animate-fade-in-up delay-100">
                        {!! nl2br(e($banner->title)) !!}
                    </h1>
                    @endif

                    @if($banner->description)
                    <p class="text-gray-200 mb-8 max-w-lg text-lg animate-fade-in-up delay-200">
                        {{ $banner->description }}
                    </p>
                    @endif

                    @if($banner->button_link)
                    <a href="{{ $banner->button_link }}" class="w-fit bg-primary-600 hover:bg-primary-700 text-white px-8 py-3.5 rounded-full font-bold transition shadow-lg shadow-primary-600/40 flex items-center gap-2 transform hover:-translate-y-1 animate-fade-in-up delay-300">
                        {{ $banner->button_text ?? 'Shop Now' }}
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach

            @if(count($heroBanners) > 1)
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex items-center gap-2 z-40">
                @foreach($heroBanners as $index => $banner)
                <button onclick="goToSlide({{ $index }})"
                    class="slider-dot transition-all duration-300 rounded-full {{ $index === 0 ? 'bg-primary-500 w-6 h-3' : 'bg-white/40 hover:bg-white/70 w-3 h-3' }}">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <div class="hidden lg:flex flex-col gap-6 h-full">
            @foreach($categoryBanners as $banner)
            <div class="relative rounded-2xl overflow-hidden h-1/2 shadow-md group">
                <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $banner->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent p-6 flex flex-col justify-end">
                    <h3 class="text-white text-xl font-bold mb-1">{{ $banner->title }}</h3>
                    @if($banner->subtitle)
                    <p class="text-gray-300 text-xs mb-2">{{ $banner->subtitle }}</p>
                    @endif
                    <a href="{{ $banner->button_link ?? '#' }}" class="text-primary-400 text-sm font-semibold hover:text-white transition flex items-center gap-1">
                        {{ $banner->button_text ?? 'Discover' }} <i class="fas fa-angle-right"></i>
                    </a>
                </div>
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