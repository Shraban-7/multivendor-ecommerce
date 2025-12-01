<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | eCommerce Marketplace</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for Interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316', // Orange-500
                            600: '#ea580c', // Orange-600
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }

        /* Hide scrollbar for smooth sliders but keep functionality */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased" x-data="{
    quickViewOpen: false,
    promoPopupOpen: true,
    scrolled: false,
    selectedProduct: null
}"
    @scroll.window="scrolled = (window.pageYOffset > 100)">

    <?php
    $settings = settings();
    $notificationCount = notificationCount();
    $searchPlaceholder = 'Search for products or shops..';
    ?>

    <!-- ==================== 4. PROMOTIONAL POPUP MODAL ==================== -->
    <div x-data="promoPopup()" x-init="checkPopup()">

        <!-- Your Popup -->
        <div x-show="promoPopupOpen" x-transition.opacity
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm" x-cloak>

            <!-- Popup content -->
            <div
                class="relative bg-white rounded-2xl overflow-hidden shadow-2xl max-w-2xl w-[90%] md:flex animate-fade-in-up">

                <button @click="closePopup"
                    class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow hover:text-primary-600 transition">
                    <i class="fa-solid fa-times"></i>
                </button>

                <div class="w-full md:w-1/2 h-64 md:h-auto bg-cover bg-center"
                    style="background-image: url('https://images.unsplash.com/photo-1607083206968-13611e3d76db?q=80&w=600&auto=format&fit=crop');">
                </div>

                <div
                    class="w-full md:w-1/2 p-8 text-center flex flex-col justify-center bg-gradient-to-br from-white to-orange-50">
                    <span class="text-primary-600 font-bold tracking-wider text-sm mb-2">LIMITED TIME OFFER</span>

                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">
                        Winter <span class="text-primary-600">Sale</span>
                    </h2>

                    <p class="text-gray-600 mb-6 text-sm">
                        Get flat <span class="font-bold text-gray-900">30% OFF</span> on your first order.
                        Use code: <span class="bg-gray-200 px-2 py-1 rounded text-primary-600 font-mono">NEW30</span>
                    </p>

                    <div class="space-y-3">
                        <button @click="closePopup"
                            class="w-full bg-primary-600 text-white font-semibold py-3 rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/30">
                            Shop Now
                        </button>

                        <button @click="closePopup" class="text-gray-400 text-xs underline">
                            No thanks, I'll pay full price
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- ==================== 1. TOP NOTIFICATION BAR ==================== -->
    <div class="bg-gray-900 text-white text-xs py-2 hidden md:block">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex gap-4">
                <span><i class="fas fa-phone-alt mr-1 text-primary-500"></i> {{ $settings->phone }}</span>
                <span><i class="fas fa-envelope mr-1 text-primary-500"></i> {{ $settings->email }}</span>
            </div>
            <div class="flex gap-4 items-center">
                <span><i class="fas fa-truck mr-1 text-primary-500"></i> Free Shipping over ৳2000</span>
                <span class="h-3 w-[1px] bg-gray-700"></span>
                <a href="#" class="hover:text-primary-500 transition">Sell on {{ $settings->app_name }}</a>
                <span class="h-3 w-[1px] bg-gray-700"></span>
                <div class="flex gap-1 cursor-pointer hover:text-primary-500">
                    <span>English</span>
                    <i class="fas fa-chevron-down mt-0.5"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== 2. HEADER & NAVIGATION ==================== -->
    <header class="bg-white sticky top-0 z-40 shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-4 lg:gap-8">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="bg-primary-500 text-white p-2 rounded-lg group-hover:rotate-3 transition duration-300">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold tracking-tight text-gray-800 leading-none">Slash<span
                                class="text-primary-600">Mart</span></span>
                        <span class="text-[10px] font-medium text-gray-500 tracking-widest uppercase">Premium
                            Store</span>
                    </div>
                </a>

                <!-- Search Bar (Hidden on mobile) -->

                <div class="hidden md:flex flex-1 max-w-2xl relative">
                    <div
                        class="flex w-full border-2 border-primary-100 rounded-full overflow-hidden 
                            hover:border-primary-300 transition-colors focus-within:border-primary-500 
                            focus-within:ring-2 focus-within:ring-primary-200">

                        <!-- Category Select -->
                        <div class="relative">
                            <select id="categorySelect"
                                class="px-4 py-2.5 pr-10 bg-gray-50 text-gray-600 text-sm font-medium 
                                    border-r border-gray-200 appearance-none cursor-pointer
                                    focus:outline-none focus:ring-0 focus:border-transparent">

                                <option value="">All Categories</option>

                                @foreach (all_categories() as $category)
                                    <option value="{{ route('category.details', $category->slug) }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Dropdown Icon -->
                            <i
                                class="fas fa-chevron-down text-xs absolute right-3 top-1/2 
                                    -translate-y-1/2 text-gray-600 pointer-events-none"></i>
                        </div>

                        <!-- Search Input -->
                        <input type="text" placeholder="Search for products, brands or shops..."
                            class="w-full px-4 py-2.5 outline-none text-gray-700 placeholder-gray-400">

                        <!-- Search Button -->
                        <button class="bg-primary-500 hover:bg-primary-600 text-white px-6 font-medium transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Icons -->
                <div class="flex items-center gap-4 lg:gap-6">

                    @if (!auth('web')->check() && !auth()->guard('seller')->check())

                        <!-- Account -->
                        <a href="{{ route('login') }}" class="hidden md:flex flex-col items-center group">
                            <i class="far fa-user text-xl text-gray-700 group-hover:text-primary-600 transition"></i>
                            <span class="text-[11px] font-medium text-gray-500 mt-1">Login</span>
                        </a>

                        <!-- Vendor Button -->
                        <a href="{{ route('seller.signup') }}"
                            class="hidden lg:block bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium 
            hover:bg-gray-800 transition shadow-md shadow-gray-600/20">
                            Become a Seller
                        </a>
                    @else
                        <!-- Dashboard Button (Cleaner UI) -->
                        <a href="{{ auth('web')->check() ? route('orders.index') : route('seller.dashboard') }}"
                            class="hidden md:flex flex-col items-center group">
                            <i
                                class="fa-solid fa-gauge text-xl text-gray-700 group-hover:text-primary-600 transition"></i>
                            <span class="text-[11px] font-medium text-gray-500 mt-1">Dashboard</span>
                        </a>

                        <!-- Notifications (Better Badge + Icon UI) -->
                        <a href="{{ route('notifications.index') }}"
                            class="hidden md:flex flex-col items-center group relative">
                            <div class="relative">
                                <i
                                    class="fa-regular fa-bell text-xl text-gray-700 group-hover:text-primary-600 transition"></i>

                                @if ($notificationCount > 0)
                                    <span
                                        class="absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] min-w-[16px] px-1 h-4 
                                        rounded-full flex items-center justify-center leading-none font-bold shadow">
                                        {{ $notificationCount }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-[11px] font-medium text-gray-500 mt-1">Alerts</span>
                        </a>

                        <!-- Wishlist -->
                        <a href="#" class="hidden md:flex flex-col items-center group relative">
                            <div class="relative">
                                <i
                                    class="far fa-heart text-xl text-gray-700 group-hover:text-primary-600 transition"></i>

                                <span
                                    class="absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] min-w-[16px] px-1 h-4 
                                        rounded-full flex items-center justify-center leading-none font-bold shadow">
                                    2
                                </span>
                            </div>
                            <span class="text-[11px] font-medium text-gray-500 mt-1">Wishlist</span>
                        </a>

                        <!-- Cart -->
                        <a href="{{ route('cart.details') }}" class="flex flex-col items-center group relative">
                            <div class="relative">
                                <i
                                    class="fas fa-shopping-cart text-xl text-gray-700 group-hover:text-primary-600 transition"></i>
                                @if ($cartCount > 0)
                                    <span
                                        class="absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] min-w-[16px] px-1 h-4 
                                        rounded-full flex items-center justify-center leading-none font-bold shadow">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-[11px] font-medium text-gray-500 mt-1 hidden md:block">Cart</span>
                        </a>

                    @endif
                </div>

            </div>
        </div>
    </header>

    @yield('content')

    <!-- ==================== 19. FOOTER ==================== -->
    <footer class="bg-white pt-16 border-t border-gray-200">
        <div class="container mx-auto px-4 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <a href="#" class="flex items-center gap-2 mb-4">
                        <i class="fas fa-shopping-bag text-primary-600 text-2xl"></i>
                        <span class="text-2xl font-bold text-gray-900">Slash<span
                                class="text-primary-600">Mart</span></span>
                    </a>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                        {{ $settings->footer_text }}
                    </p>

                    <div class="flex gap-4">
                        @foreach (social_links() as $socialLink)
                            @php
                                $color = $socialLink->color;
                                $bg = "bg-{$color}-100";
                                $text = "text-{$color}-600";
                                $hoverBg = "hover:bg-{$color}-600";
                                $hoverText = 'hover:text-white';
                            @endphp

                            <a href="{{ $socialLink->link }}"
                                class="w-9 h-9 rounded-full flex items-center justify-center transition {{ $bg }} {{ $text }} {{ $hoverBg }} {{ $hoverText }}">
                                <i class="fa-brands {{ $socialLink->icon_name }}"></i>
                            </a>
                        @endforeach
                    </div>

                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-gray-900 font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition">About Us</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Blog</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Flash Sales</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Vendor Registration</a></li>
                    </ul>
                </div>

                <!-- Customer Care -->
                <div>
                    <h4 class="text-gray-900 font-bold mb-4">Customer Care</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">How to Buy</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Returns & Refunds</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-gray-900 font-bold mb-4">Contact Us</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li class="flex gap-3">
                            <i class="fas fa-map-marker-alt text-primary-500 mt-1"></i>
                            <span>{{ $settings->address }}</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-envelope text-primary-500 mt-1"></i>
                            <span>{{ $settings->email }}</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-phone text-primary-500 mt-1"></i>
                            <span>{{ $settings->phone }}</span>
                        </li>
                    </ul>
                    <!-- Payment Methods (Placeholder Icons) -->
                    <div class="mt-6">
                        <h5 class="text-xs font-bold text-gray-900 mb-2">We Accept</h5>
                        <div class="flex gap-2 text-2xl text-gray-400">
                            <i class="fab fa-cc-visa hover:text-blue-900"></i>
                            <i class="fab fa-cc-mastercard hover:text-red-600"></i>
                            <i class="fab fa-cc-amex hover:text-blue-500"></i>
                            <i class="fas fa-money-bill-wave hover:text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-10 pt-6 text-center text-sm text-gray-400">
                <p>&copy; 2025 SlashMart. All Rights Reserved. Designed for Bangladesh.</p>
            </div>
        </div>
    </footer>

    <!-- ==================== 20. MOBILE STICKY BOTTOM NAVIGATION ==================== -->
    <div
        class="md:hidden fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t border-gray-100 z-40 px-6 py-3 flex justify-between items-center text-gray-400">
        <a href="#" class="flex flex-col items-center gap-1 text-primary-600">
            <i class="fas fa-home text-lg"></i>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary-600">
            <i class="fas fa-th-large text-lg"></i>
            <span class="text-[10px] font-medium">Cats</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary-600 relative">
            <div class="relative">
                <i class="fas fa-shopping-cart text-lg"></i>
                <span
                    class="absolute -top-2 -right-2 bg-primary-600 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center">5</span>
            </div>
            <span class="text-[10px] font-medium">Cart</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary-600">
            <i class="far fa-user text-lg"></i>
            <span class="text-[10px] font-medium">Account</span>
        </a>
    </div>

    <!-- ==================== 21. BACK TO TOP BUTTON ==================== -->
    <button x-show="scrolled" @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-20 md:bottom-8 right-4 md:right-8 bg-primary-600 text-white w-10 h-10 md:w-12 md:h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-700 transition z-40"
        x-cloak>
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        document.getElementById('categorySelect').addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        });

        function promoPopup() {
            return {
                promoPopupOpen: false,

                checkPopup() {
                    const alreadyShown = localStorage.getItem("promo_shown");

                    if (window.location.pathname === "/" && !alreadyShown) {
                        this.promoPopupOpen = true;
                    }
                },

                closePopup() {
                    this.promoPopupOpen = false;
                    localStorage.setItem("promo_shown", "yes");
                }
            }
        }
    </script>

</body>

</html>
