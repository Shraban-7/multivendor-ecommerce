<div class="relative h-48 md:h-64 rounded">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="container mx-auto px-4 relative h-full flex items-end pb-6">
        <div class="flex items-center">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                <img src="{{ storage_url($seller->business_logo) }}" alt="Shop Logo" class="w-full h-full object-cover">
            </div>
            <div class="ml-4 text-white">
                <h1 class="text-2xl md:text-3xl font-bold">{{ $seller->business_name }}</h1>
                <p class="flex items-center mt-1">
                    <i class="fas fa-star text-yellow-300 mr-1"></i>
                    <span class="font-semibold">{{ number_format($avgRating, 2) }}</span>
                    <span class="mx-2">|</span>
                    <span>{{ number_shorten_format($seller->totalReviews) }} Reviews</span>
                    <span class="mx-2">|</span>
                    <span>{{ number_shorten_format($seller->total_followers) }} Followers</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Shop Links -->
<div class="shop-links mb-8">
    <!-- Navigation -->
    <nav class="flex flex-wrap items-center text-jet-gray gap-x-6 gap-y-3 border-b border-gray-200 py-4">
        <a href="{{ route('sellers.shop', $seller->username) }}"
            class="pb-1 font-medium transition-colors {{ request()->routeIs('sellers.shop') ? 'text-primary border-b-2 border-primary' : 'hover:text-primary' }}">Products</a>
        <a href="#" class="hover:text-primary transition-colors font-medium">About</a>
        <a href="{{ route('sellers.reviews', $seller->username) }}"
            class="pb-1 font-medium transition-colors {{ request()->routeIs('sellers.reviews') ? 'text-primary border-b-2 border-primary' : 'hover:text-primary' }}">
            Reviews
            <span class="bg-primary/10 text-primary px-2 py-1 rounded-full text-xs font-medium">
                {{ number_format($avgRating, 2) }} <i class="fa-solid fa-star text-xs"></i>
            </span>
        </a>

        <!-- shop product search -->
        <div class="w-full md:w-auto mt-4 md:mt-0 ml-auto">
            <div class="relative">
                <input type="text" placeholder="Search all {{ $totalItem }} items"
                    class="text-sm w-full py-2.5 pl-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-700 placeholder-gray-400 transition-all" />
                <button
                    class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-primary hover:bg-primary-dark p-2 rounded-full text-white transition-colors">
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
