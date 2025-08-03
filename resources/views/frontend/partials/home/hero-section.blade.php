{{-- <section class="hero-section hidden">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <!-- Left big banner -->
            <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
                <a href="#" class="block h-full">
                    <img src="{{ storage_url($hero_grid_one->image) }}" alt="{{ $hero_grid_one->title }}"
class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500"
loading="lazy" />
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6 md:p-8">
    <div>
        <h2 class="text-2xl md:text-4xl font-bold text-white mb-2">{{ $hero_grid_one->title }}</h2>
        <p class="text-white/90 mb-4">{{ $hero_grid_one->subtitle ?? 'Shop Now' }}</p>
        <button class="bg-white text-gray-900 px-6 py-2 rounded-full font-medium hover:bg-gray-100 transition">
            Explore
        </button>
    </div>
</div>
</a>
</div>

<!-- Right side smaller banners -->
<div class="grid grid-cols-2 gap-4 md:gap-6">
    @foreach ([$hero_grid_two, $hero_grid_three, $hero_grid_four, $hero_grid_five] as $grid)
    <div class="relative group overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
        <a href="#" class="block h-full">
            <img src="{{ storage_url($grid->image) }}" alt="{{ $grid->title }}"
                class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500"
                loading="lazy" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-4">
                <h3 class="text-lg font-semibold text-white">{{ $grid->title }}</h3>
            </div>
        </a>
    </div>
    @endforeach
</div>
</div>
</div>
</section> --}}

<!-- <div class="max-w-7xl mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="w-full md:w-[65%] rounded-lg overflow-hidden">
            <div class="swiper hero-swiper h-72 md:h-100 rounded-lg">
                <div class="swiper-wrapper">
                    @foreach ($hero_banners as $hero_banner)
                    <div class="swiper-slide">
                        <a href="{{ $hero_banner->button_link ?? '#' }}">
                            <img src="{{ storage_url($hero_banner->image) }}" alt="{{ $hero_banner->alt_text ?? 'Slider Image' }}" class="w-full h-72 md:h-100 object-cover" />
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-button-next !text-white"></div>
                <div class="swiper-button-prev !text-white"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="w-full md:w-[35%] flex flex-row flex-wrap md:flex-col gap-4">
            @foreach ($hero_blocks as $hero_block)
            <div class="w-[calc(50%-8px)] md:w-full h-36 md:h-48 rounded-lg overflow-hidden">
                <a href="{{ $hero_block->button_link ?? '#' }}">
                    <img src="{{ storage_url($hero_block->image) }}" alt="{{ $hero_block->alt_text ?? 'Promotional Block' }}" class="w-full h-full object-cover rounded-lg" />
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div> -->

<div class="max-w-7xl mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="w-full md:w-[65%] rounded-lg overflow-hidden">
            <div class="swiper hero-swiper aspect-[4/3] md:aspect-[4/3] rounded-lg">
                <div class="swiper-wrapper">
                    @foreach ($hero_banners as $hero_banner)
                    <div class="swiper-slide">
                        <a href="{{ $hero_banner->button_link ?? '#' }}">
                            <img
                                src="{{ storage_url($hero_banner->image) }}"
                                alt="{{ $hero_banner->alt_text ?? 'Slider Image' }}"
                                class="w-full h-full object-cover rounded-lg" />
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-button-next !text-white"></div>
                <div class="swiper-button-prev !text-white"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- <div class="w-full md:w-[35%] flex flex-wrap md:flex-col gap-4 md:gap-0">
            @foreach ($hero_blocks as $hero_block)
            <div class="w-[calc(50%-8px)] md:w-full md:flex-1 overflow-hidden">
                <a href="{{ $hero_block->button_link ?? '#' }}">
                    <img
                        src="{{ storage_url($hero_block->image) }}"
                        alt="{{ $hero_block->alt_text ?? 'Promotional Block' }}"
                        class="w-full h-full object-cover rounded-lg" />
                </a>
            </div>
            @endforeach
        </div> -->
        <div class="w-full md:w-[35%] flex flex-row flex-wrap md:flex-col gap-6">
            @foreach ($hero_blocks as $hero_block)
            <div class="w-[calc(50%-8px)] md:w-full h-36 md:h-72 rounded-lg overflow-hidden">
                <a href="{{ $hero_block->button_link ?? '#' }}">
                    <img src="{{ storage_url($hero_block->image) }}" alt="{{ $hero_block->alt_text ?? 'Promotional Block' }}" class="w-full h-full object-cover rounded-lg" />
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.hero-swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });
    });
</script>