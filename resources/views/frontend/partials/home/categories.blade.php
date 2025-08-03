<section class="interest-section section-padding">
    <div class="container">
        <div class="relative sec-heading">
            <h2
                class="font-semibold uppercase md:text-center sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                Featured Categories
            </h2>
            <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2">
                <a href="#" class="theme-btn theme-outline-btn">View All</a>
            </span>
        </div>
        
        <div class="mt-10 swiper categoriesSwiper md:mt-16">
            <div class="swiper-wrapper">
                @foreach ($categories as $category)
                <div class="swiper-slide group/categores eq">
                    <a href="{{ route('category.details', $category->slug) }}"
                        class="flex flex-col items-center block w-full product-card">
                        <div class="relative w-16 h-16 card-image lg:h-28 lg:w-28 md:w-24 md:h-24">
                            <img src="{{ storage_url($category->image) }}" alt="Grocery" class="object-contain w-full h-full" />
                        </div>
                        <div class="mt-3 card-content lg:mt-5">
                            <a href="#"
                                class="block text-sm font-medium text-center text-black group-hover/categores:text-light-yellow md:text-lg lg:text-xl eq">{{ $category->name }}
                            </a>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>