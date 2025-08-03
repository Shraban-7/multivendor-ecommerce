<section class="interest-section section-padding">
    <div class="container">
        <!-- Section Heading -->
        <div class="sec-heading">
            <h2 class="font-semibold uppercase text-theme-dark text-center text-xl md:text-2xl lg:text-3xl xl:text-4xl">
                Featured Categories
            </h2>
        </div>

        <!-- Categories Slider -->
        <div class="mt-10 md:mt-16 swiper categoriesSwiper">
            <div class="swiper-wrapper">
                @foreach ($categories as $category)
                    <div class="swiper-slide group/categores eq min-h-[150px]">
                        <a href="{{ route('category.details', $category->slug) }}"
                           class="flex flex-col items-center w-full product-card">
                            <!-- Image -->
                            <div class="w-16 h-16 md:w-24 md:h-24 lg:w-28 lg:h-28">
                                <img src="{{ storage_url($category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="object-contain w-full h-full" />
                            </div>

                            <!-- Name -->
                            <div class="mt-3 lg:mt-5">
                                <span class="block text-sm md:text-lg lg:text-xl font-medium text-center text-black group-hover/categores:text-light-yellow eq">
                                    {{ $category->name }}
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
