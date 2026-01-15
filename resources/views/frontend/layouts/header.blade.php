<header class="bg-white sticky top-0 z-40 shadow-sm border-b border-gray-100">
    <div class="container mx-auto max-w-7xl px-4 py-4">
        <div class="flex items-center justify-between gap-4 lg:gap-8">
            <!-- Mobile Menu Toggle (Visible on Mobile) -->
            <button id="mobileMenuBtn" class="lg:hidden text-2xl text-gray-700">
                <i class="fas fa-bars"></i>
            </button>

            <a href="/" class="flex items-center gap-1 group">
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

    <div class="hidden lg:block border-t border-gray-100 bg-white">
        <div class="container mx-auto max-w-7xl px-4">
            <div class="flex items-center gap-8 relative">

                <!-- MEGA MENU DROPDOWN PARENT -->
                <div class="relative group py-3">
                    <button class="flex items-center gap-2 font-bold text-gray-800 hover:text-primary-600 transition">
                        <i class="fas fa-bars text-primary-600"></i> Browse Categories <i
                            class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>

                    <!-- MEGA MENU CONTENT -->
                    <div
                        class="invisible group-hover:visible opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 absolute top-full left-0 w-[800px] bg-white shadow-2xl rounded-b-xl border border-gray-100 z-50 flex overflow-hidden">

                        <!-- Sidebar (Main Categories) -->
                        <div class="w-2/5 bg-gray-50 py-2 border-r border-gray-100">
                            <ul class="text-sm">
                                @foreach (dropdown_categories() as $category)
                                    <li class="menu-item-hover hover:bg-white hover:text-primary-600 px-4 py-2 cursor-pointer flex justify-between items-center font-medium text-gray-700"
                                        onmouseover="showSubmenu('{{ $category->slug }}')">
                                        <a>
                                            @if (!empty($category->icon))
                                                <i class="{{ $category->icon }} w-5 text-center mr-2"></i>
                                            @endif {{ $category->name }}
                                        </a>
                                        <i class="fas fa-chevron-right text-[10px]"></i>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                        <!-- Subcategories Content Area -->
                        <div class="w-3/4 p-6 bg-white min-h-[350px]">

                            <!-- Electronics Content (Default) -->
                            @foreach (dropdown_categories() as $index => $category)
                                <div id="{{ $category->slug }}"
                                    class="{{ $index !== 0 ? 'hidden' : '' }} submenu-content grid grid-cols-3 gap-6">
                                    <div>
                                        <a href="{{ route('products.index',['category'=>$category->slug]) }}">
                                            <h4 class="font-bold text-gray-900 mb-3 border-b border-gray-100 pb-1">
                                                {{ $category->name }}</h4>
                                        </a>
                                        <ul class="space-y-2 text-sm text-gray-500">
                                            @foreach ($category->subcategories as $subcategory)
                                                <li><a href="{{ route('products.index',['subcategory'=>$subcategory->slug]) }}"
                                                        class="hover:text-primary-600">{{ $subcategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    {{-- <div class="col-span-1">
                                        <img src="https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?q=80&w=300"
                                            class="rounded-lg mb-3 h-32 w-full object-cover">
                                        <h5 class="font-bold text-primary-600">New Arrivals</h5>
                                        <p class="text-xs text-gray-500">Check out the latest gadgets.</p>
                                    </div> --}}
                                </div>
                            @endforeach
                            <!-- Fashion Content (Hidden by default) -->
                            <div id="fashion" class="submenu-content hidden grid grid-cols-3 gap-6">
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 border-b border-gray-100 pb-1">Men's
                                        Fashion</h4>
                                    <ul class="space-y-2 text-sm text-gray-500">
                                        <li><a href="#" class="hover:text-primary-600">T-Shirts</a></li>
                                        <li><a href="#" class="hover:text-primary-600">Jeans</a></li>
                                        <li><a href="#" class="hover:text-primary-600">Watches</a></li>
                                        <li><a href="#" class="hover:text-primary-600">Shoes</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-3 border-b border-gray-100 pb-1">Women's
                                        Fashion</h4>
                                    <ul class="space-y-2 text-sm text-gray-500">
                                        <li><a href="#" class="hover:text-primary-600">Sarees</a></li>
                                        <li><a href="#" class="hover:text-primary-600">Kurtis</a></li>
                                        <li><a href="#" class="hover:text-primary-600">Jewelry</a></li>
                                        <li><a href="#" class="hover:text-primary-600">Handbags</a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Other contents (Hidden) -->
                            <div id="home" class="submenu-content hidden">
                                <h4 class="font-bold text-gray-900">Home & Living</h4>
                                <p class="text-sm text-gray-500 mt-2">Bedding, Furniture, Decor...</p>
                            </div>
                            <div id="beauty" class="submenu-content hidden">
                                <h4 class="font-bold text-gray-900">Beauty & Health</h4>
                                <p class="text-sm text-gray-500 mt-2">Makeup, Skincare, Haircare...</p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Standard Nav Links -->
                <nav class="flex items-center gap-6 text-sm font-medium text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-primary-600 transition">Home</a>
                    <a href="{{ route('products.index') }}" class="hover:text-primary-600 transition">Shop</a>
                    <a href="{{ route('sellers.index') }}" class="hover:text-primary-600 transition">Vendors</a>
                    {{-- <a href="#" class="hover:text-primary-600 transition flex items-center gap-1">Offers <span
                            class="bg-red-500 text-white text-[9px] px-1.5 rounded-sm">HOT</span></a> --}}
                    {{-- <a href="#" class="hover:text-primary-600 transition">Contact</a> --}}
                </nav>

                {{-- <div class="ml-auto text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-bolt text-primary-500"></i> Black Friday Deals
                </div> --}}
            </div>
        </div>
    </div>
</header>

<script>
    // --- Mega Menu Interaction (Desktop) ---
    function showSubmenu(id) {
        // Hide all submenus
        document.querySelectorAll('.submenu-content').forEach(el => {
            el.classList.add('hidden');
        });
        // Show specific submenu
        const target = document.getElementById(id);
        if (target) target.classList.remove('hidden');
    }

    // --- Mobile Menu Logic ---
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenuDrawer = document.getElementById('mobileMenuDrawer');

    function toggleMobileMenu(show) {
        if (show) {
            mobileMenuOverlay.classList.remove('hidden');
            setTimeout(() => mobileMenuOverlay.classList.remove('opacity-0'), 10);
            mobileMenuDrawer.classList.remove('-translate-x-full');
        } else {
            mobileMenuOverlay.classList.add('opacity-0');
            mobileMenuDrawer.classList.add('-translate-x-full');
            setTimeout(() => mobileMenuOverlay.classList.add('hidden'), 300);
        }
    }

    mobileMenuBtn?.addEventListener('click', () => toggleMobileMenu(true));
    closeMobileMenu?.addEventListener('click', () => toggleMobileMenu(false));
    mobileMenuOverlay?.addEventListener('click', () => toggleMobileMenu(false));

    // --- Mobile Accordion Logic ---
    function toggleMobileSubmenu(id) {
        const submenu = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);

        if (submenu.classList.contains('open')) {
            submenu.classList.remove('open');
            icon.style.transform = 'rotate(0deg)';
        } else {
            submenu.classList.add('open');
            icon.style.transform = 'rotate(180deg)';
        }
    }
</script>
