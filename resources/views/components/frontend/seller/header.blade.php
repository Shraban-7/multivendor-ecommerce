{{-- Shop Header --}}
<div class="relative h-40 md:h-64 rounded overflow-hidden">
    <!-- Background Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent"></div>

    <!-- Content -->
    <div class="container mx-auto px-4 relative h-full flex items-end pb-6">
        <div class="flex items-end md:items-center gap-4 md:gap-6">
            <!-- Logo -->
            <div
                class="w-20 h-20 md:w-32 md:h-32 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden flex-shrink-0">
                <img src="{{ storage_url($seller->business_logo) }}" alt="Shop Logo"
                    class="w-full h-full object-cover">
            </div>

            <!-- Shop Info -->
            <div class="text-white">
                <h1 class="text-xl md:text-3xl font-bold">{{ $seller->business_name }}</h1>
                <div class="mt-2 flex flex-col md:flex-row md:items-center md:gap-3 text-sm md:text-base text-gray-100">
                    <span class="flex items-center">
                        <i class="fas fa-star text-yellow-300 mr-1"></i>
                        <span class="font-semibold">{{ number_format($avgRating, 2) }}</span>
                    </span>
                    <span class="hidden md:inline">|</span>
                    <span>{{ number_shorten_format($seller->totalReviews) }} Reviews</span>
                    <span class="hidden md:inline">|</span>
                    <span>{{ number_shorten_format($seller->total_followers) }} Followers</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Shop Navigation --}}
<div class="shop-links mb-4 bg-white rounded shadow-sm mt-2">
    <nav
        class="container mx-auto px-4 flex flex-wrap items-center gap-x-6 gap-y-2 border-b border-gray-200 py-4 text-gray-700">

        <!-- Nav Links -->
        <a href="{{ route('sellers.shop', $seller->username) }}"
            class="pb-1 font-medium transition-colors {{ request()->routeIs('sellers.shop') ? 'text-primary border-b-2 border-primary' : 'hover:text-primary' }}">
            Products
        </a>
        <a href="#"
            class="pb-1 font-medium hover:text-primary transition-colors">
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