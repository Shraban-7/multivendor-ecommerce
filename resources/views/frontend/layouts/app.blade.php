<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | eCommerce Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/libs/toastr/css/toastr.min.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* Utility for hiding elements with JS */
        .hidden-custom {
            display: none !important;
        }
    </style>

    @stack('header')
</head>

<?php

use Illuminate\Support\Facades\View;

$settings = settings();
$notificationCount = notificationCount();
$isDashboard = View::hasSection('dashboard');
?>

<body class="bg-gray-50 font-sans min-h-screen text-gray-800 antialiased">
    <div id="promoPopup" class="hidden opacity-0 fixed inset-0 z-[60] flex items-center justify-center bg-black/70 backdrop-blur-sm transition-opacity duration-300">
        <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl max-w-2xl w-[90%] md:flex">
            <button id="closePromoBtn" class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow hover:text-primary-600 transition">
                <i class="fa-solid fa-times"></i>
            </button>
            <div class="w-full md:w-1/2 h-64 md:h-auto bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1607083206968-13611e3d76db?q=80&w=600&auto=format&fit=crop');"></div>
            <div class="w-full md:w-1/2 p-8 text-center flex flex-col justify-center bg-gradient-to-br from-white to-orange-50">
                <span class="text-primary-600 font-bold tracking-wider text-sm mb-2">LIMITED TIME OFFER</span>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Winter <span class="text-primary-600">Sale</span></h2>
                <p class="text-gray-600 mb-6 text-sm">Get flat <span class="font-bold text-gray-900">30% OFF</span> on your first order. Use code: <span class="bg-gray-200 px-2 py-1 rounded text-primary-600 font-mono">NEW30</span></p>
                <div class="space-y-3">
                    <button class="close-promo-trigger w-full bg-primary-600 text-white font-semibold py-3 rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/30">Shop Now</button>
                    <button class="close-promo-trigger text-gray-400 text-xs underline">No thanks, I'll pay full price</button>
                </div>
            </div>
        </div>
    </div>

    <div id="quickViewModalMain" class="hidden-custom fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
        <!-- Modal Overlay Click Handler attached in JS -->
        <div id="quickViewContent" class="bg-white rounded-2xl w-[95%] max-w-4xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[90vh]">
            <!-- Image Side -->
            <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-4 relative">
                <button class="close-quickview absolute top-4 left-4 md:hidden w-8 h-8 flex items-center justify-center bg-white rounded-full shadow"><i class="fa-solid fa-times"></i></button>
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=600&auto=format&fit=crop" alt="Product" class="max-h-[300px] md:max-h-[400px] object-contain mix-blend-multiply">
            </div>
            <!-- Details Side -->
            <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col overflow-y-auto">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">In Stock</span>
                        <h2 class="text-2xl font-bold text-gray-900 mt-2">Nike Air Premium Runner</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-500 text-sm">(124 Reviews)</span>
                        </div>
                    </div>
                    <button class="close-quickview hidden md:block text-gray-400 hover:text-red-500 text-xl"><i class="fa-solid fa-times"></i></button>
                </div>

                <div class="mt-4 border-b border-gray-100 pb-4">
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-primary-600">৳ 4,500</span>
                        <span class="text-gray-400 line-through mb-1">৳ 6,200</span>
                        <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold mb-1">-27%</span>
                    </div>
                    <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                        Authentic premium running shoes designed for maximum comfort and durability. Perfect for daily wear or sports activities. Imported directly.
                    </p>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <span class="block text-sm font-semibold text-gray-700 mb-2">Color</span>
                        <div class="flex gap-2">
                            <button class="w-8 h-8 rounded-full bg-red-500 ring-2 ring-offset-2 ring-gray-300 focus:ring-primary-500"></button>
                            <button class="w-8 h-8 rounded-full bg-blue-500"></button>
                            <button class="w-8 h-8 rounded-full bg-black"></button>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <div class="flex border border-gray-300 rounded-lg overflow-hidden w-24">
                            <button class="px-3 bg-gray-50 hover:bg-gray-100">-</button>
                            <input type="text" value="1" class="w-full text-center border-none focus:ring-0 text-sm">
                            <button class="px-3 bg-gray-50 hover:bg-gray-100">+</button>
                        </div>
                        <button class="flex-1 bg-primary-600 text-white font-semibold py-2.5 rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/30">
                            <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                        </button>
                        <button class="w-12 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-300 transition">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-auto pt-4 text-xs text-gray-500 flex gap-4">
                    <span>SKU: NIK-001</span>
                    <span>Category: Shoes</span>
                </div>
            </div>
        </div>
    </div>

    <div id="quickViewModal"
        class="hidden-custom fixed inset-0 z-[60] flex items-center justify-center 
            bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">

        <!-- Modal Box -->
        <div class="relative bg-white rounded-2xl w-[95%] max-w-4xl 
                overflow-hidden shadow-2xl flex flex-col">

            <!-- Close Button -->
            <button id="quickViewCloseBtn"
                class="absolute top-3 right-3 w-9 h-9 flex items-center justify-center 
                   rounded-full bg-white shadow hover:bg-gray-200 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Scroll Container -->
            <div id="quickViewContent"
                class="quickview-content overflow-y-auto max-h-[80vh]">
                <!-- AJAX will inject here -->
            </div>

        </div>
    </div>


    <div class="bg-gray-900 text-white text-xs py-2 hidden md:block">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex gap-4">
                <span><i class="fas fa-phone-alt mr-1 text-primary-500"></i> {{ $settings->phone }}</span>
                <span><i class="fas fa-envelope mr-1 text-primary-500"></i> {{ $settings->email }}</span>
            </div>
            <div class="flex gap-4 items-center">
                <span><i class="fas fa-truck mr-1 text-primary-500"></i> Free Shipping over ৳2000</span>
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
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-4 lg:gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <div class="bg-primary-500 text-white p-2 rounded-lg group-hover:rotate-3 transition duration-300">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold tracking-tight text-gray-800 leading-none">Slash<span class="text-primary-600">Mart</span></span>
                        <span class="text-[10px] font-medium text-gray-500 tracking-widest uppercase">Premium Store</span>
                    </div>
                </a>

                <!-- Search Bar (Hidden on mobile) -->
                <div class="hidden md:flex flex-1 max-w-2xl relative">
                    <div class="flex w-full border-2 border-primary-100 rounded-full overflow-hidden hover:border-primary-300 transition-colors focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-primary-200">
                        <button class="px-4 bg-gray-50 text-gray-600 text-sm font-medium border-r border-gray-200 flex items-center gap-2 hover:bg-gray-100">
                            All Categories <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <input type="text" placeholder="Search for products, brands or shops..." class="w-full px-4 py-2.5 outline-none text-gray-700 placeholder-gray-400">
                        <button class="bg-primary-500 hover:bg-primary-600 text-white px-6 font-medium transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Icons -->
                <div class="flex items-center gap-4 lg:gap-6">
                    @if(request()->routeIs('products.index'))
                    <button id="openMobileFilter" class="lg:hidden text-gray-600 hover:text-primary-600">
                        <i class="fas fa-filter text-xl"></i>
                    </button>
                    @endif
                    @if (!auth('web')->check() && !auth()->guard('seller')->check())
                    <a href="{{ route('login') }}" class="hidden md:flex flex-col items-center group">
                        <i class="far fa-user text-xl text-gray-600 group-hover:text-primary-600 transition"></i>
                        <span class="text-[10px] font-medium text-gray-500 mt-1">Login</span>
                    </a>

                    <a href="{{ route('seller.signup') }}" class="hidden lg:block bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-lg shadow-gray-500/20">
                        Become a Seller
                    </a>

                    @else
                    <a href="#" class="hidden md:flex flex-col items-center group relative">
                        <div class="relative">
                            <i class="far fa-heart text-xl text-gray-600 group-hover:text-primary-600 transition"></i>
                            <span class="absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">2</span>
                        </div>
                        <span class="text-[10px] font-medium text-gray-500 mt-1">Wishlist</span>
                    </a>

                    <a href="{{ route('cart.details') }}" class="flex flex-col items-center group relative">
                        <div class="relative">
                            <i class="fas fa-shopping-cart text-xl text-gray-600 group-hover:text-primary-600 transition"></i>
                            @if ($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium text-gray-500 mt-1 hidden md:block">Cart</span>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="hidden md:flex flex-col items-center group relative">
                        <div class="relative">
                            <i class="fa-regular fa-bell text-xl text-gray-700 group-hover:text-primary-600 transition"></i>
                            @if ($notificationCount > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-primary-600 text-white text-[10px] min-w-[16px] px-1 h-4 
                                        rounded-full flex items-center justify-center leading-none font-bold shadow">
                                {{ $notificationCount }}
                            </span>
                            @endif
                        </div>
                        <span class="text-[11px] font-medium text-gray-500 mt-1">Notifications</span>
                    </a>

                    <a href="{{ auth('web')->check() ? route('orders.index') : route('seller.dashboard') }}" class="hidden lg:block bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-lg shadow-gray-500/20">
                        Dashboard
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    @if (!$isDashboard)
    <div class="container mx-auto px-4 py-4">
        @yield('content')
    </div>
    @endif

    @if ($isDashboard)
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-10" id="main">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
            @include('frontend.layouts.sidebar')
            <section class="md:col-span-3 space-y-6">
                @yield('dashboard')
            </section>
        </div>
    </main>
    @endif

    <footer class="bg-white pt-16 border-t border-gray-200">
        <div class="container mx-auto px-4 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <a href="#" class="flex items-center gap-2 mb-4">
                        <i class="fas fa-shopping-bag text-primary-600 text-2xl"></i>
                        <span class="text-2xl font-bold text-gray-900">Slash<span class="text-primary-600">Mart</span></span>
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
                <div>
                    <h4 class="text-gray-900 font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition">About Us</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Blog</a></li>
                        <li><a href="shop.html" class="hover:text-primary-600 transition">Flash Sales</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-gray-900 font-bold mb-4">Customer Care</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary-600 transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Returns & Refunds</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-primary-600 transition">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-gray-900 font-bold mb-4">Contact Us</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li class="flex gap-3"><i class="fas fa-map-marker-alt text-primary-500 mt-1"></i><span>{{ $settings->address }}</span></li>
                        <li class="flex gap-3"><i class="fas fa-envelope text-primary-500 mt-1"></i><span>{{ $settings->email }}</span></li>
                        <li class="flex gap-3"><i class="fas fa-phone text-primary-500 mt-1"></i><span>{{ $settings->phone }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-100 mt-10 pt-6 text-center text-sm text-gray-400">
                <p>&copy; 2025 SlashMart. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ==================== MOBILE STICKY BOTTOM NAVIGATION ==================== -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t border-gray-100 z-40 px-6 py-3 flex justify-between items-center text-gray-400">
        <a href="#" class="flex flex-col items-center gap-1 text-primary-600">
            <i class="fas fa-home text-lg"></i>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="shop.html" class="flex flex-col items-center gap-1 hover:text-primary-600">
            <i class="fas fa-th-large text-lg"></i>
            <span class="text-[10px] font-medium">Shop</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary-600 relative">
            <div class="relative">
                <i class="fas fa-shopping-cart text-lg"></i>
                <span class="absolute -top-2 -right-2 bg-primary-600 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center">5</span>
            </div>
            <span class="text-[10px] font-medium">Cart</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 hover:text-primary-600">
            <i class="far fa-user text-lg"></i>
            <span class="text-[10px] font-medium">Account</span>
        </a>
    </div>

    <button
        id="backToTop"
        class="hidden-custom fixed bottom-20 md:bottom-8 right-4 md:right-8 bg-primary-600 text-white w-10 h-10 md:w-12 md:h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-700 transition z-40 opacity-0 translate-y-10 transition-all duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="{{ asset('assets/libs/toastr/js/toastr.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const SHOW_PROMO_MODAL = "{{ request()-> routeIs('home') ? 1 : 0 }}";
            const promoPopup = document.getElementById('promoPopup');
            const closePromoBtns = document.querySelectorAll('#closePromoBtn, .close-promo-trigger');
            if (SHOW_PROMO_MODAL == 1 && promoPopup) {
                promoPopup.classList.remove('hidden');
                setTimeout(() => promoPopup.style.opacity = '1', 10);
            }

            if (promoPopup) {
                closePromoBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        promoPopup.style.opacity = '0';
                        setTimeout(() => promoPopup.remove(), 300);
                    });
                });
            }

            const quickViewModal = document.getElementById('quickViewModal');
            const openQuickViewBtns = document.querySelectorAll('.btn-quickview');
            const closeQuickViewBtns = document.querySelectorAll('.close-quickview');
            const quickViewContent = document.getElementById('quickViewContent');

            function toggleQuickView(show) {
                if (show) {
                    quickViewModal.classList.remove('hidden-custom');
                    // Small delay to allow display:block to apply before changing opacity for transition
                    setTimeout(() => quickViewModal.style.opacity = '1', 10);
                } else {
                    quickViewModal.style.opacity = '0';
                    setTimeout(() => quickViewModal.classList.add('hidden-custom'), 300);
                }
            }

            $(document).on('click', '.btn-quickview', function() {
                const slug = $(this).data('slug');
                $('#quickViewModal .quickview-content').html('');
                $.ajax({
                    url: "/products/" + slug + "/quick-view",
                    type: "GET",
                    success: function(response) {
                        $('#quickViewModal .quickview-content').html(response);
                        toggleQuickView(true);
                    }
                });
            });

            // openQuickViewBtns.forEach(btn => {
            //     btn.addEventListener('click', (e) => {
            //         e.preventDefault();
            //         e.stopPropagation();
            //         toggleQuickView(true);
            //     });
            // });

            closeQuickViewBtns.forEach(btn => {
                btn.addEventListener('click', () => toggleQuickView(false));
            });

            // Close on click outside
            quickViewModal.addEventListener('click', (e) => {
                if (e.target === quickViewModal) {
                    toggleQuickView(false);
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('#quickViewCloseBtn')) {
                    toggleQuickView(false);
                }
            });

            // --- Back to Top Button ---
            const backToTopBtn = document.getElementById('backToTop');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 100) {
                    backToTopBtn.classList.remove('hidden-custom');
                    setTimeout(() => {
                        backToTopBtn.classList.remove('opacity-0', 'translate-y-10');
                    }, 10);
                } else {
                    backToTopBtn.classList.add('opacity-0', 'translate-y-10');
                    // Wait for transition to finish before hiding
                    setTimeout(() => backToTopBtn.classList.add('hidden-custom'), 300);
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <script>
        function debounce(func, delay) {
            let timer;
            return function(...args) {
                const context = this;
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(context, args), delay);
            };
        }
        $(function() {
            function refreshCsrfToken() {
                return $.get("{{ route('refresh.csrf') }}").then(function(data) {
                    const newToken = data.token;
                    $('meta[name="csrf-token"]').attr('content', newToken);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': newToken
                        }
                    });

                    return newToken;
                });
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const $sidebarToggle = $("#sidebar-toggle");
            const $mobileSidebar = $("#mobile-sidebar");
            const $sidebarBackdrop = $("#sidebar-backdrop");

            $sidebarToggle.on("click", function() {
                const isOpen = !$mobileSidebar.hasClass("-translate-x-full");
                if (isOpen) {
                    $mobileSidebar.addClass("-translate-x-full");
                    $sidebarBackdrop.addClass("hidden");
                } else {
                    $mobileSidebar.removeClass("-translate-x-full");
                    $sidebarBackdrop.removeClass("hidden");
                }
            });

            $sidebarBackdrop.on("click", function() {
                $mobileSidebar.addClass("-translate-x-full");
                $sidebarBackdrop.addClass("hidden");
            });

            window.togglePassword = function(inputId, button) {
                const $input = $("#" + inputId);
                const $button = $(button);
                const $eye = $button.find(".fa-eye");
                const $eyeSlash = $button.find(".fa-eye-slash");

                if ($input.attr("type") === "password") {
                    $input.attr("type", "text");
                    $eye.hide();
                    $eyeSlash.show();
                } else {
                    $input.attr("type", "password");
                    $eye.show();
                    $eyeSlash.hide();
                }
            };

            $('body').on('click', '.addToCartBtn', function() {
                var $btn = $(this);
                var originalText = $btn.html();
                $btn.html(
                    `<svg class="animate-spin h-4 w-4 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg> Adding...`
                ).prop('disabled', true);

                var product_id = $btn.data('id');
                var $product_content = $btn.closest("[id^='product-wrapper']");
                var product = $product_content.data("product");

                if (!product) {
                    toastr.error("Product data not found!");
                    $btn.html(originalText).prop('disabled', false);
                    return;
                }

                const selectedOptions = collectSelectedOptions($product_content);
                const variant = getSelectedVariant(product, selectedOptions);
                var variantId = variant ? variant.id : null;

                var product_price_text = $product_content.find('.product-price').text().replace(/[^0-9.]/g,
                    '');
                var product_price = parseFloat(product_price_text);
                var qtyInput = $product_content.find('.quantity').val() || 1;

                function addToCartRequest() {
                    return $.ajax({
                        url: "{{ route('cart.add') }}",
                        type: "POST",
                        data: {
                            product_id: product_id,
                            variant_id: variantId,
                            quantity: qtyInput,
                            price: product_price,
                        },
                        success: function(data) {
                            if (data.success) {
                                toastr.success(data.message);
                                updateCartData();

                                if ("{{ Route::currentRouteName() }}" === 'cart.details' &&
                                    data.action === 'add_to_cart') {
                                    window.location.reload();
                                }
                            } else if (data.error) {
                                toastr.error(data.error);
                            } else {
                                toastr.error('Unexpected response!');
                            }
                        },
                        error: async function(xhr) {
                            if (xhr.status === 419) {
                                await refreshCsrfToken();
                                addToCartRequest();
                            } else if (xhr.status === 401) {
                                toastr.warning(xhr.responseJSON.error);
                                setTimeout(() => {
                                    window.location.href = "{{ route('login') }}";
                                }, 1000);
                            } else if (xhr.status === 403) {
                                toastr.warning(xhr.responseJSON.error);
                            } else {
                                toastr.error('Something went wrong!');
                            }
                        },
                        complete: function() {
                            $btn.html(originalText).prop('disabled', false);
                        }
                    });
                }

                addToCartRequest();
            });

            $('.buyNowBtn').click(function() {
                var product_id = $(this).data('id');
                var seller_id = $(this).data('seller');
                var wishlistId = $(this).data('wishlist-id');
                var variantSku = $('#variantSku').val();
                var product_price_text = $('.product-price').text().replace(/[^0-9.]/g, '');
                var product_price = parseFloat(product_price_text);
                var $row = $(this).closest('.grid');

                let selectedOptionIds = [];

                $('.variant-option:checked').each(function() {
                    selectedOptionIds.push($(this).val());
                });

                if (!product_id) {
                    alert("No Product Selected!");
                    return;
                }
                var qtyInput = $('#qtyInput' + product_id).val();

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        product_id: product_id,
                        seller_id: seller_id,
                        variant_sku: variantSku,
                        quantity: qtyInput,
                        price: product_price,
                        option_ids: selectedOptionIds,
                    },
                    success: function(data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('login') }}";
                        } else if (data.success) {
                            $('button[data-modal-hide="quick-view-modal-' + product_id + '"]')
                                .trigger('click');
                            $row.fadeOut(300, function() {
                                $(this).remove();
                            });
                            toastr.success(data.message);
                            updateCartData();

                            window.location.href = "{{ route('orders.checkout') }}" +
                                "?seller_id=" + seller_id;
                        } else {
                            toastr.error(data.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        } else {
                            toastr.error('Something went wrong!');
                        }
                    }
                });
            });

            $('.wishlistBtn').click(function() {
                var product_id = $(this).data('id');
                if (!product_id) {
                    alert("No Product Selected!");
                    return;
                }

                $.ajax({
                    url: "{{ route('wishlist.store') }}",
                    type: "POST",
                    data: {
                        product_id: product_id,
                    },
                    success: function(data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('login') }}";
                        } else if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        } else {
                            toastr.error('Something went wrong!');
                        }
                    }
                });
            });

            function updateCartData() {
                $.ajax({
                    url: "{{ route('cart.data') }}",
                    type: "GET",
                    success: function(data) {
                        if (data.cartCount > 0) {
                            $('#cartCount').removeClass('hidden')
                            $('#totalPrice').removeClass('hidden')
                        }
                        $('#cartCount').text(data.cartCount);
                        $('#totalPrice').text(data.totalPrice);
                    },
                    error: function() {
                        toastr.error('Failed to update cart data.');
                    }
                });
            }

            $("[id^='product-wrapper']").each(function() {
                initDefaultVariant($(this));
            });

            function storageURL(fileName) {
                return "{{ url('/') }}" + '/storage/' + fileName;
            }

            function formatPrice(price, quantity) {
                const total = Math.round(price * quantity * 100) / 100;
                return total.toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateProductUI($wrapper, variant = null, quantity, isInitialLoad = false) {
                const $qtyEl = $wrapper.find("input.quantity");
                const $mainImage = $wrapper.find(".main-product-image");
                const $priceEl = $wrapper.find(".product-price");
                const $originalPriceEl = $wrapper.find(".original-price");
                const $skuEl = $wrapper.find(".sku-text");
                const $stockEl = $wrapper.find(".stock-text");
                const $availability = $wrapper.find(".availability-text");
                const $variantError = $wrapper.find(".variant-error");
                const $addToCartBtn = $wrapper.find(".addToCartBtn");
                const $variantIdInput = $wrapper.find("input.variantId");
                const product = $wrapper.data("product");

                if (variant) {
                    const basePrice = parseFloat(variant.price) || 0;
                    const discounted = variant.discounted_price !== null ? parseFloat(variant.discounted_price) :
                        null;

                    const price = discounted && discounted > 0 ? discounted : basePrice;

                    if (!discounted || discounted == 0) {
                        $originalPriceEl.addClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    } else {
                        $originalPriceEl.removeClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(discounted, quantity)}`);
                        $originalPriceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    }

                    $skuEl.text(variant.sku);
                    $stockEl.text(variant.stock);
                    $availability.text(variant.stock > 0 ? "In Stock" : "Out of Stock");
                    $variantIdInput.val(variant.id);
                    $qtyEl.val(quantity);
                    $qtyEl.attr('value', parseInt($qtyEl.val()));
                    $variantError.addClass("hidden");

                    $addToCartBtn.prop("disabled", variant.stock <= 0).toggleClass("opacity-50 cursor-not-allowed",
                        variant.stock <= 0);

                    if (!isInitialLoad && variant.image) {
                        const imageUrl = storageURL(variant.image);
                        $mainImage.attr('src', imageUrl);
                    }

                } else {
                    const basePrice = parseFloat(product.price) || 0;
                    const discounted = product.discounted_price !== null ? parseFloat(product.discounted_price) :
                        null;
                    const price = discounted && discounted > 0 ? discounted : basePrice;

                    if (!discounted || discounted == 0) {
                        $originalPriceEl.addClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    } else {
                        $originalPriceEl.removeClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(discounted, quantity)}`);
                        $originalPriceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    }

                    $skuEl.text(product.sku || "N/A");
                    $stockEl.text(product.stock || 0);
                    $availability.text((product.stock || 0) > 0 ? "In Stock" : "Out of Stock");
                    $qtyEl.val(quantity);
                    $qtyEl.attr('value', parseInt($qtyEl.val()));
                    $variantIdInput.val("");
                    $variantError.addClass("hidden");
                    $addToCartBtn.prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                }
            }

            function getSelectedVariant(product, selectedOptions) {
                const selectedIds = Object.values(selectedOptions).map(Number).sort();
                return (product.variants || []).find(v =>
                    JSON.stringify([...v.value_ids].sort()) === JSON.stringify(selectedIds)
                );
            }

            function collectSelectedOptions($wrapper) {
                const selectedOptions = {};
                $wrapper.find(".option-value-btn.bg-primary\\/10")
                    .each(function() {
                        const $btn = $(this);
                        const optId = $btn.data("option-id");
                        const valId = $btn.data("value-id");
                        selectedOptions[optId] = parseInt(valId);
                    });
                return selectedOptions;
            }

            $(document).on("click", ".option-value-btn", function() {
                const $btn = $(this);
                const $wrapper = $btn.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                const optId = $btn.data("option-id");
                const valId = $btn.data("value-id");

                $wrapper.find(`.option-value-btn[data-option-id="${optId}"]`).removeClass(
                    "bg-primary/10 text-primary border-primary"
                ).addClass("bg-white text-gray-800 border-gray-300");

                $btn.removeClass("bg-white text-gray-800 border-gray-300").addClass(
                    "bg-primary/10 text-primary border-primary"
                );

                const selectedOptions = collectSelectedOptions($wrapper);

                const variant = getSelectedVariant(product, selectedOptions);

                const quantity = parseInt($wrapper.find(".quantity").val()) || 1;

                updateProductUI($wrapper, variant, quantity);
            });

            $(document).on("click", ".thumb-img", function() {
                const $img = $(this);
                const full = $img.data("full");
                const $wrapper = $img.closest("[id^='product-wrapper']");
                const $mainImage = $wrapper.find(".main-product-image");
                const $thumbWrapper = $wrapper.find(".thumbnailWrapper");

                $mainImage.attr("src", full);
                $thumbWrapper.find(".slide-thumb").removeClass("border-primary").addClass(
                    "border-gray-200");
                $img.closest(".slide-thumb").addClass("border-primary").removeClass("border-gray-200");
            });

            $(document).on("click", ".increaseBtn, .decreaseBtn", debounce(function() {
                const $btn = $(this);
                const $wrapper = $btn.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                const $qtyInput = $wrapper.find("input.quantity");
                let quantity = parseInt($qtyInput.val()) || 1;

                const selectedOptions = collectSelectedOptions($wrapper);
                const variant = getSelectedVariant(product, selectedOptions);

                const availableStock = variant ? variant.stock : product.stock;

                if ($btn.hasClass("increaseBtn")) {
                    if (quantity < availableStock) quantity += 1;
                    else {
                        quantity = availableStock;
                        toastr.warning("Not enough stock!");
                    }
                } else {
                    quantity -= 1;
                    if (quantity < 1) quantity = 1;
                }

                updateProductUI($wrapper, variant, quantity);
            }, 300));

            $(document).on("input", ".quantity", function() {
                const $input = $(this);
                const $wrapper = $input.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                let quantity = parseInt($input.val()) || 1;
                quantity = quantity > 0 ? quantity : 1;

                const selectedOptions = collectSelectedOptions($wrapper);
                const variant = getSelectedVariant(product, selectedOptions);

                updateProductUI($wrapper, variant, quantity);
            });

            function initDefaultVariant($wrapper) {
                if ($wrapper.data('variant-initialized')) return;

                const product = $wrapper.data("product");
                if (!product?.variants?.length) return;

                const defaultVariant = product.variants.find(v => v.is_default);
                if (!defaultVariant) return;

                defaultVariant.value_ids.forEach(valId => {
                    const $btn = $wrapper.find(`.option-value-btn[data-value-id="${valId}"]`);
                    const optId = $btn.data("option-id");

                    $wrapper.find(`.option-value-btn[data-option-id="${optId}"]`)
                        .removeClass("bg-primary/10 text-primary border-primary")
                        .addClass("bg-white text-gray-800 border-gray-300");

                    $btn.removeClass("bg-white text-gray-800 border-gray-300")
                        .addClass("bg-primary/10 text-primary border-primary");
                });

                const quantity = parseInt($wrapper.find(".quantity").val()) || 1;
                updateProductUI($wrapper, defaultVariant, quantity, true);

                $wrapper.data('variant-initialized', true);
            }

            document.addEventListener('modal:open', function(event) {
                const modalEl = event.detail;
                const $modal = $(modalEl);

                $modal.find("[id^='product-wrapper']").each(function() {
                    initDefaultVariant($(this));
                });
            });

            function onLoadMoreProducts() {
                $("[id^='product-wrapper']").each(function() {
                    initDefaultVariant($(this));
                });
            }
        });
    </script>

    @if (auth()->check() && auth()->user()->isAffiliate())
    <script>
        function copyReferralLink(button, referralCode, productUrl) {
            // Append ?ref=referralCode to the product URL
            const referralUrl = `${productUrl}?ref=${referralCode}`;

            navigator.clipboard.writeText(referralUrl).then(() => {
                const tooltip = button.querySelector('.tooltip-text');
                tooltip.classList.remove('opacity-0');
                tooltip.classList.add('opacity-100');

                setTimeout(() => {
                    tooltip.classList.remove('opacity-100');
                    tooltip.classList.add('opacity-0');
                }, 2000);
            });
        }
    </script>
    @endif

    @stack('scripts')
</body>

</html>