<div class="relative h-32 md:h-48 rounded overflow-hidden">
    <div class="absolute inset-0">
        @if ($seller->banner_images->isNotEmpty())
        <img src="{{ storage_url($seller->banner_images->first()->image) }}" alt="{{ $seller->business_name }} Banner"
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
        @endif
    </div>

    <div class="container mx-auto px-4 relative h-full flex items-end pb-4 md:pb-6">
        <div class="flex items-end md:items-center gap-3 md:gap-4 w-full">
            <div
                class="w-16 h-16 md:w-24 md:h-24 rounded-full border-3 border-white bg-white shadow-xl overflow-hidden flex-shrink-0 transform translate-y-2 md:translate-y-0">
                <img src="{{ storage_url($seller->business_logo) }}" alt="Shop Logo" class="w-full h-full object-cover">
            </div>

            <div class="text-white pt-2 overflow-hidden">
                <h1 class="text-lg md:text-2xl font-extrabold truncate">{{ $seller->business_name }}</h1>
                <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs md:text-sm text-gray-200">
                    <span class="flex items-center font-semibold">
                        <i class="fas fa-star text-yellow-400 mr-1 text-sm"></i>
                        <span>{{ number_format($avgRating, 2) }}</span>
                    </span>
                    <span class="text-white/50 hidden md:inline">|</span>
                    <span class="whitespace-nowrap">{{ number_shorten_format($seller->totalReviews) }} Reviews</span>
                    <span class="text-white/50 hidden md:inline">|</span>
                    <span class="whitespace-nowrap">{{ number_shorten_format($seller->total_followers) }} Followers</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-links mb-4 bg-white rounded shadow-sm mt-2">
    <nav
        class="container mx-auto px-4 flex flex-wrap items-center gap-x-6 gap-y-2 border-b border-gray-200 py-4 text-gray-700">

        <!-- Nav Links -->
        <a href="{{ route('sellers.shop', $seller->username) }}"
            class="pb-1 font-medium transition-colors {{ request()->routeIs('sellers.shop') ? 'text-primary border-b-2 border-primary' : 'hover:text-primary' }}">
            Products
        </a>
        <a href="#" class="pb-1 font-medium hover:text-primary transition-colors">
            About
        </a>
        <a href="{{ route('sellers.reviews', $seller->username) }}"
            class="pb-1 font-medium transition-colors flex items-center gap-1 {{ request()->routeIs('sellers.reviews') ? 'text-primary border-b-2 border-primary' : 'hover:text-primary' }}">
            Reviews
            <span
                class="bg-primary/10 text-primary px-2 py-0.5 rounded-full text-xs font-medium flex items-center gap-1">
                {{ number_format($avgRating, 2) }}
                <i class="fa-solid fa-star text-xs text-yellow-400"></i>
            </span>
        </a>

        <!-- Spacer -->
        <div class="flex-1"></div>

        <!-- Shop Item Search -->
        <div class="w-full md:w-auto mt-3 md:mt-0">
            <div class="relative">
                <input type="text" placeholder="Search all {{ $totalItem }} items"
                    class="w-full md:w-64 py-2 pl-4 pr-12 text-sm rounded-md border border-gray-300 focus:outline-none 
                           focus:ring-2 focus:ring-primary focus:border-transparent text-gray-700 placeholder-gray-400 transition" />
                <button
                    class="absolute top-1/2 right-2 -translate-y-1/2 flex items-center gap-1 bg-primary text-white px-3 py-1.5 rounded-md hover:bg-primary-dark transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>
</div>