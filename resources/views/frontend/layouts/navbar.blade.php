<div class="bg-gray-900 text-white text-xs py-2 hidden md:block">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <div class="flex gap-4">
            @if ($settings->phone)
                <span><i class="fas fa-phone-alt mr-1 text-primary-500"></i> {{ $settings->phone }}</span>
            @endif
            @if ($settings->email)
                <span><i class="fas fa-envelope mr-1 text-primary-500"></i> {{ $settings->email }}</span>
            @endif
        </div>
        <div class="flex gap-4 items-center">
            @php
            $flash_sales =  \App\Models\FlashSale::active()
                ->withCount("approveProducts")
                ->having("approve_products_count", ">", 0)
                ->with("approveProducts")
                ->get();
            @endphp
            @if($flash_sales)
            <span><i class="fas fa-bolt text-primary-500"></i> <a href="{{ route('flashSales.index') }}" class="hover:text-primary-500 transition">Flash Sale</a></span>
            @endif
            {{-- <span><i class="fas fa-truck mr-1 text-primary-500"></i> Free Shipping over ৳2000</span> --}}
            <span class="h-3 w-[1px] bg-gray-700"></span>
            <a href="#" class="hover:text-primary-500 transition">Sell on SlashMart</a>
            <span class="h-3 w-[1px] bg-gray-700"></span>
            <div class="flex gap-1 cursor-pointer hover:text-primary-500">
                <span>English</span>
                <i class="fas fa-chevron-down mt-0.5"></i>
            </div>
        </div>
    </div>
</div>

<header class="bg-white sticky top-0 z-40 shadow-sm border-b border-gray-100">
    <div class="container mx-auto max-w-7xl px-4 py-4">
        <div class="flex items-center justify-between gap-4 lg:gap-8">
            <a href="/" class="flex items-center gap-2 group">
                <div>
                    <img src="{{ asset('assets/frontend/images/sm-icon.png') }}" alt="" style="height: 36px;">
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-extrabold tracking-tight text-gray-800 leading-none">Slash<span
                            class="text-primary-600">Mart</span></span>
                    <!-- <span class="text-[10px] font-medium text-gray-500 tracking-widest uppercase">Premium
                        Store</span> -->
                </div>
            </a>

            <!-- Search Bar (Hidden on mobile) -->
            <div class="hidden md:flex flex-1 max-w-2xl relative">
                <div
                    class="flex w-full border-2 border-primary-100 rounded-full overflow-hidden hover:border-primary-300 transition-colors focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-200">
                    <!-- <button
                        class="px-4 bg-gray-50 text-gray-600 text-sm font-medium border-r border-gray-200 flex items-center gap-2 hover:bg-gray-100">
                        All Categories <i class="fas fa-chevron-down text-xs"></i>
                    </button> -->
                    <input type="text" id="searchInput" placeholder="Search for products, brands or shops..."
                        class="w-full px-4 py-2.5 outline-none text-gray-700 placeholder-gray-400">
                    <button class="bg-primary-500 hover:bg-primary-600 text-white px-6 font-medium transition">
                        <i class="fas fa-search"></i>
                    </button>
                    <!-- Suggestions -->
                    <div id="suggestionsBox"
                        class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-md shadow-lg hidden max-h-80 overflow-y-auto z-50">
                    </div>
                </div>
            </div>


            <!-- Right Icons -->
            <div class="flex items-center gap-4 lg:gap-6">
                @if (request()->routeIs('products.index'))
                    <button id="openMobileFilter" class="lg:hidden text-gray-600 hover:text-primary-600">
                        <i class="fas fa-filter text-xl"></i>
                    </button>
                @endif
                @if (!auth('web')->check() && !auth()->guard('seller')->check() && !auth()->guard('admin')->check())
                    <a href="javascript:void(0)" class="auth-btn hidden md:flex flex-col items-center group">
                        <i class="far fa-user text-xl text-gray-600 group-hover:text-primary-600 transition"></i>
                        <span class="text-[10px] font-medium text-gray-500 mt-1">Login</span>
                    </a>

                    <a href="{{ route('seller.signup') }}"
                        class="hidden lg:block bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-lg shadow-gray-500/20">
                        Become a Seller
                    </a>
                @else
                    <a href="{{ route('wishlist.index') }}" class="flex flex-col items-center group relative">
                        <div class="relative">
                            <i class="far fa-heart text-xl text-gray-600 group-hover:text-primary-600 transition"></i>
                            <span id="wishlistCount"
                                class="{{ $wishlistCount > 0 ? '' : 'hidden' }} absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $wishlistCount }}</span>
                        </div>
                        <span class="text-[10px] font-medium text-gray-500 mt-1 hidden md:block">Wishlist</span>
                    </a>

                    <a href="{{ route('cart.details') }}"
                        class="hidden md:flex flex flex-col items-center group relative">
                        <div class="relative">
                            <i
                                class="fas fa-shopping-cart text-xl text-gray-600 group-hover:text-primary-600 transition"></i>

                            <span id="cartCount"
                                class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>

                        </div>
                        <span class="text-[10px] font-medium text-gray-500 mt-1 hidden md:block">Cart</span>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="flex flex-col items-center group relative">
                        <div class="relative">
                            <i
                                class="fa-regular fa-bell text-xl text-gray-600 group-hover:text-primary-600 transition"></i>
                            @if ($notificationCount > 0)
                                <span
                                    class="absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                                    {{ $notificationCount }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium text-gray-500 mt-1 hidden md:block">Notifications</span>
                    </a>

                    <a href="
                                @if (auth('web')->check()) {{ route('orders.index') }}
                                @elseif(auth('seller')->check())
                                    {{ route('seller.dashboard') }}
                                @elseif(auth('admin')->check())
                                    {{ route('admin.dashboard') }}
                                @else
                                    {{ route('login') }} @endif
                            "
                        class="hidden lg:block bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-lg shadow-gray-500/20">
                        Dashboard
                    </a>

                @endif
            </div>
        </div>
    </div>
</header>

<script>
    function setupSearch(inputId, boxId) {
        const input = document.getElementById(inputId);
        const box = document.getElementById(boxId);
        let timer;

        input.addEventListener('input', function() {
            clearTimeout(timer);
            const query = this.value.trim();
            console.log(query);

            if (query.length < 2) {
                box.classList.add('hidden');
                box.innerHTML = '';
                return;
            }

            timer = setTimeout(() => {
                fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.html.trim() !== '') {
                            box.innerHTML = data.html;
                            box.classList.remove('hidden');
                        } else {
                            box.classList.add('hidden');
                            box.innerHTML = '';
                        }
                    });
            }, 300);
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest(`#${inputId}`) &&
                !event.target.closest(`#${boxId}`)) {
                box.classList.add('hidden');
            }
        });
    }
    setupSearch('searchInput', 'suggestionsBox');
    setupSearch('searchInputMobile', 'suggestionsBoxMobile');
</script>
