<section class="featured-videos-section py-8">
  <div class="container mx-auto px-4">
    <!-- Section Title -->
    <div class="relative flex items-center justify-between mb-6">
      <h2 class="text-theme-dark text-xl sm:text-2xl md:text-3xl xl:text-4xl font-semibold uppercase">
        Featured In Videos
      </h2>
      <a href="#" class="theme-btn theme-outline-btn">View All</a>
    </div>

    <!-- Featured Video Swiper Slider -->
    <div class="swiper featuredVideoSwiper">
      <div class="swiper-wrapper">
        @foreach ($featured_products as $product)
          <div class="swiper-slide py-3 group/featured-videos-pro-card eq">
            <div class="relative overflow-hidden border border-jet-gray/30 rounded-t-lg rounded-b-sm hover:shadow-lg eq">
              <!-- Video Section -->
              <div class="relative w-full h-96 sm:h-[30rem] overflow-hidden">
                {{-- <video controls muted loop class="object-cover w-full h-full cursor-pointer"
                  poster="{{ storage_url($product->thumbnail) }}">
                  <!-- <source src="{{ storage_url($product->video) }}" type="video/mp4" /> -->
                </video> --}}
                <!-- Overlay Controls -->

                <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->slug }}" class="object-cover w-full h-full cursor-pointer" loading="lazy">
                <div class="absolute bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8 w-1/3">
                  <a href="#" class="block w-full text-white font-light truncate hover:text-light-yellow eq">
                    @jesikaperker07854
                  </a>
                </div>
                <div class="absolute bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 flex gap-2 md:gap-3">
                  <button class="w-8 h-8 sm:w-10 sm:h-10 p-2 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white play-btn">
                    <i class="fa-solid fa-play"></i>
                  </button>
                  <button class="w-8 h-8 sm:w-10 sm:h-10 p-2 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white mute-btn">
                    <i class="fa-solid fa-volume-high"></i>
                  </button>
                </div>
              </div>
              <!-- Product Info -->
              <div class="flex items-start gap-3 px-2 py-4 sm:px-3 md:px-6">
                <div class="w-15 h-15 overflow-hidden">
                  <a href="#">
                    <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                      class="w-12 h-auto object-cover" />
                  </a>
                </div>
                <div class="flex-1">
                  <p class="font-semibold">{{ money($product->price) }}</p>
                  <p class="text-xs text-gray-400 line-clamp-2">
                    {{ $product->name }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
