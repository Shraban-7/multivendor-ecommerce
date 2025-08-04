<div class="max-w-7xl mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="w-full md:w-[65%] rounded-lg overflow-hidden">
            <div class="swiper hero-swiper aspect-[4/3] md:aspect-[4/3] rounded-lg">
                <div class="swiper-wrapper">
                    @foreach ($hero_banners as $hero_banner)
                    @if($hero_banner->is_slider)
                    <div class="swiper-slide">
                        <a href="{{ $hero_banner->button_link ?? '#' }}">
                            <img
                                src="{{ storage_url($hero_banner->image) }}"
                                alt="{{ $hero_banner->alt_text ?? 'Slider Image' }}"
                                class="w-full h-full object-cover rounded-lg" />
                        </a>
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="swiper-button-next !text-white"></div>
                <div class="swiper-button-prev !text-white"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="w-full md:w-[35%] flex flex-row flex-wrap md:flex-col gap-4">
            @foreach ($hero_banners as $hero_banner)
            @if(!$hero_banner->is_slider)
            <div class="w-[calc(50%-8px)] md:w-full h-36 md:h-72 rounded-lg overflow-hidden">
                <a href="{{ $hero_banner->button_link ?? '#' }}">
                    <img src="{{ storage_url($hero_banner->image) }}" alt="{{ $hero_banner->alt_text ?? 'Promotional Block' }}" class="w-full h-full object-cover rounded-lg" />
                </a>
            </div>
            @endif
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