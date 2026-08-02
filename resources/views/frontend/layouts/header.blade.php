@php
    $categories = dropdown_categories();
    $settings = settings();
    $appName = app_name();
@endphp

<header class="sticky top-0 z-50 bg-white shadow-sm border-b border-[#E5E5E5]">
    <div class="max-w-[1400px] mx-auto px-2 sm:px-4">
        <div class="flex items-center h-14 sm:h-16">

            <!-- Left: Hamburger + Logo -->
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                <button type="button" class="lg:hidden p-2 -ml-2 rounded hover:bg-[#F5F5F5] eq" aria-label="Open menu"
                    onclick="document.getElementById('mobile-drawer').classList.remove('-translate-x-full'); document.getElementById('mobile-drawer-overlay').classList.remove('hidden')">
                    <svg class="w-6 h-6 text-[#191919]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ url('/') }}" class="flex items-center gap-1" aria-label="{{ $appName }} home">
                    @if (! empty($settings?->logo_white))
                        <img src="{{ storage_url($settings->logo) }}" alt="{{ $appName }}" class="h-8 sm:h-10 w-auto">
                    @else
                        <span class="text-xl sm:text-2xl font-bold text-[#F85606]">{{ $appName }}</span>
                    @endif
                </a>
            </div>

            <!-- Center: Search Bar -->
            <div class="flex-1 flex justify-center px-2 lg:px-4">
                <div class="hidden sm:block w-full max-w-[600px]">
                    <div class="relative">
                        <form action="{{ route('products.index') }}" method="GET" class="flex">
                            <input type="text" name="q" id="searchInput" placeholder="Search in {{ $appName }}..." autocomplete="off" value="{{ request('q') }}"
                                class="w-full h-10 pl-4 pr-10 text-sm border-2 border-[#F85606] rounded-l focus:outline-none focus:border-[#C43D00] text-[#191919] placeholder-[#767676]"
                                aria-label="Search products">
                            <button type="submit" class="h-10 px-5 bg-[#F85606] hover:bg-[#C43D00] eq text-white font-medium text-sm rounded-r flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden lg:inline">Search</span>
                            </button>
                        </form>
                        <div id="suggestionsBox" class="hidden absolute top-full left-0 right-0 bg-white border border-[#E5E5E5] rounded-b shadow-lg z-50 max-h-96 overflow-y-auto"></div>
                    </div>
                </div>
            </div>

            <!-- Right: Wishlist + Cart -->
            <div class="flex items-center flex-shrink-0">
                <a href="{{ route('wishlist.index') }}" class="relative p-2 rounded hover:bg-[#FFF1EA] eq" aria-label="Wishlist">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#F85606]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 bg-[#F85606] text-white text-[10px] font-bold min-w-[16px] h-[16px] flex items-center justify-center rounded-full {{ $wishlistCount > 0 ? '' : 'hidden' }}" aria-live="polite" id="wishlistCount">{{ $wishlistCount }}</span>
                </a>
                <a href="{{ route('cart.details') }}" class="relative p-2 rounded hover:bg-[#FFF1EA] eq" aria-label="Shopping cart">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[#F85606]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 bg-[#F85606] text-white text-[10px] font-bold min-w-[16px] h-[16px] flex items-center justify-center rounded-full {{ $cartCount > 0 ? '' : 'hidden' }}" aria-live="polite" id="cartCount">{{ $cartCount }}</span>
                </a>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div class="sm:hidden pb-2">
            <form action="{{ route('products.index') }}" method="GET" class="flex">
                <input type="text" name="q" id="searchInputMobile" placeholder="Search in {{ $appName }}..." autocomplete="off" value="{{ request('q') }}"
                    class="flex-1 h-9 px-3 text-sm border-2 border-[#F85606] rounded-l focus:outline-none text-[#191919] placeholder-[#767676]"
                    aria-label="Search products">
                <button type="submit" class="h-9 px-3 bg-[#F85606] hover:bg-[#C43D00] eq text-white rounded-r">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </form>
            <div id="suggestionsBoxMobile" class="hidden absolute left-0 right-0 bg-white border border-[#E5E5E5] rounded-b shadow-lg z-50 max-h-96 overflow-y-auto"></div>
        </div>
    </div>

    <!-- Mega Menu / Category Nav (Desktop) -->
    <nav class="hidden lg:block bg-white border-t border-[#E5E5E5]" aria-label="Category navigation">
        <div class="max-w-[1400px] mx-auto px-4 flex items-stretch">
            <!-- Browse Categories Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-2 px-5 h-11 text-sm font-medium text-white bg-[#F85606] hover:bg-[#C43D00] eq" aria-haspopup="true" aria-expanded="false">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span>Categories</span>
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="absolute left-0 top-full w-[220px] bg-white border border-[#E5E5E5] shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible eq z-50">
                    @foreach ($categories as $category)
                        <div class="relative group/sub">
                            <a href="{{ route('category.details', $category->slug) }}"
                               class="flex items-center justify-between px-4 py-2.5 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq"
                               onmouseenter="showSubmenu(this)">
                                @if ($category->icon)
                                    <img src="{{ storage_url($category->icon) }}" alt="" class="w-5 h-5 mr-2" loading="lazy">
                                @endif
                                <span class="flex-1">{{ $category->name }}</span>
                                @if ($category->children && $category->children->isNotEmpty())
                                    <svg class="w-3 h-3 text-[#767676]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                @endif
                            </a>
                            @if ($category->children && $category->children->isNotEmpty())
                                <div class="submenu absolute left-full top-0 w-[600px] bg-white border border-[#E5E5E5] shadow-lg p-4 hidden z-50" style="min-height: 100%;">
                                    <div class="grid grid-cols-3 gap-4">
                                        @foreach ($category->children as $sub)
                                            <div>
                                                <a href="{{ route('category.details', $sub->slug) }}" class="font-semibold text-sm text-[#191919] hover:text-[#F85606] block mb-1">{{ $sub->name }}</a>
                                                @if ($sub->children && $sub->children->isNotEmpty())
                                                    <ul class="space-y-0.5">
                                                        @foreach ($sub->children->take(5) as $child)
                                                            <li>
                                                                <a href="{{ route('category.details', $child->slug) }}" class="text-xs text-[#595959] hover:text-[#F85606]">{{ $child->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Static Nav Links with Daraz underline hover -->
            <div class="flex items-center ml-3">
                <a href="{{ url('/') }}"
                   class="nav-link{{ request()->is('/') ? ' active' : '' }}"
                   aria-current="{{ request()->is('/') ? 'page' : '' }}">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="nav-link{{ request()->routeIs('products.*') ? ' active' : '' }}">
                    Products
                </a>
                <a href="{{ route('sellers.index') }}" class="nav-link{{ request()->routeIs('sellers.*') ? ' active' : '' }}">
                    Vendors
                </a>
                <a href="{{ route('flashSales.index') }}" class="nav-link @if (flash_sale_is_active()) text-[#D93025] font-semibold animate-pulse @endif{{ request()->routeIs('flashSales.*') ? ' active' : '' }}">
                    @if (flash_sale_is_active())🔥 @endif Flash Sale
                </a>
            </div>
        </div>
    </nav>
</header>

<style>
    .nav-link {
        position: relative;
        padding: 0 0.75rem;
        height: 2.75rem;
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: #595959;
        transition: all 0.2s ease-in-out;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: #F85606;
        transform: scaleX(0);
        transition: transform 0.2s ease-in-out;
    }
    .nav-link:hover { color: #F85606; }
    .nav-link:hover::after,
    .nav-link.active::after { transform: scaleX(1); }
</style>

<script>
    function showSubmenu(el) {
        const submenu = el.nextElementSibling;
        document.querySelectorAll('.submenu').forEach(s => s !== submenu && s.classList.add('hidden'));
        if (submenu) submenu.classList.remove('hidden');
    }
    document.addEventListener('mouseover', function(e) {
        const submenu = e.target.closest('.submenu');
        if (!submenu) {
            document.querySelectorAll('.submenu').forEach(s => s.classList.add('hidden'));
        }
    });
</script>
