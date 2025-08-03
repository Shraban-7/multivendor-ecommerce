<section class="hero-section">
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
</section>
