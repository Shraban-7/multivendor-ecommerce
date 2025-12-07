<?php
$settings = settings();
$notificationCount = notificationCount();
$searchPlaceholder = 'Search for products or shops..';
?>

<nav class="container mx-auto px-4 py-3 text-white">
    <!-- Mobile Navbar -->
    <div class="flex flex-col gap-2 md:hidden">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if (auth()->guard('web')->check() || auth()->guard('seller')->check())
                    <!-- Toggle Button -->
                    <button class="text-white p-3 focus:outline-none" id="sidebar-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                @endif

                <!-- Logo -->
                <a href="/">
                    <img src="{{ storage_url($settings->logo) }}" class="h-8" alt="Logo" />
                </a>
            </div>

            @if (!auth('web')->check() && !auth()->guard('seller')->check())
                <a href="{{ route('home') }}" class="text-sm hover:text-light-yellow flex items-center gap-1">
                    <i class="fa-regular fa-user"></i> <span>Sign In</span>
                </a>
            @else
                
            @endif
        </div>

        <!-- Search (Mobile) -->
        <div class="w-full md:hidden">
            <div class="relative">
                <input type="text" id="searchInputMobile" placeholder="{{ $searchPlaceholder }}"
                    class="w-full py-2 pl-4 pr-28 text-sm text-black rounded-md border border-gray-200 
             focus:border-light-yellow focus:ring-1 focus:ring-light-yellow outline-none" />
                <button
                    class="absolute top-1/2 right-2 -translate-y-1/2 flex items-center gap-1 bg-primary text-white 
             text-sm font-medium px-3 py-1.5 rounded-md hover:bg-orange-600 transition">
                    <i class="fa fa-search"></i>
                    <span>Search</span>
                </button>

                <!-- Suggestions -->
                <div id="suggestionsBoxMobile"
                    class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-md shadow-lg hidden max-h-80 overflow-y-auto z-50">
                </div>
            </div>
        </div>
    </div>


    <!-- Desktop Navbar -->
    <div class="hidden md:grid md:grid-cols-3 md:items-center md:gap-6">
        <div><a href="/"><img src="{{ storage_url($settings->logo) }}" class="h-8 md:h-10 lg:h-12 w-auto"
                    alt="Logo" /></a></div>

        <!-- Desktop Search -->
        <div class="flex justify-center">
            <div class="relative w-full max-w-md">
                <input type="text" id="searchInput" placeholder="{{ $searchPlaceholder }}"
                    class="w-full py-2 pl-4 pr-28 text-sm md:text-base text-black rounded-md border border-gray-200 
             focus:border-light-yellow focus:ring-1 focus:ring-light-yellow outline-none" />
                <button
                    class="absolute top-1/2 right-2 -translate-y-1/2 flex items-center gap-1 bg-primary text-white 
             text-sm md:text-base font-medium px-4 py-2 rounded-md hover:bg-orange-600 transition">
                    <i class="fa fa-search"></i>
                </button>
                <!-- Suggestions -->
                <div id="suggestionsBox"
                    class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-md shadow-lg hidden max-h-80 overflow-y-auto z-50">
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex justify-end items-center gap-5">
            @if (!auth('web')->check() && !auth()->guard('seller')->check())
                <!-- Sign In -->
                <a href="{{ route('home') }}" class="flex items-center gap-1 hover:text-light-yellow">
                    <i class="fa-regular fa-user"></i>
                    <span class="text-sm lg:text-base">Sign In</span>
                </a>
            @else
                <!-- Dashboard -->
                <a href="{{ auth('web')->check() ? route('orders.index') : (auth('seller')->check() ? route('seller.dashboard') : '#') }}"
                    class="px-3 py-1.5 text-xs bg-white text-black hover:text-light-yellow transition 
                        border border-gray-200 rounded-md flex items-center gap-2">
                    <span class="text-sm lg:text-base">Dashboard</span>
                </a>

                <!-- Notifications -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}"
                        class="relative flex items-center hover:text-light-yellow">
                        <i class="fa-regular fa-bell text-lg"></i>
                        @if ($notificationCount > 0)
                            <span
                                class="absolute -top-2 -end-2 w-4 h-4 bg-white text-persian-red text-[10px] font-bold rounded-full flex items-center justify-center">
                                {{ $notificationCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Cart -->
                <a href="{{ route('cart.details') }}"
                    class="flex flex-col items-center leading-none hover:text-light-yellow">
                    <span class="block relative">
                        <i class="fa-solid fa-cart-arrow-down"></i>
                        <span id="cartCount"
                            class="absolute flex {{ $cartCount > 0 ? '' : 'hidden' }} items-center justify-center w-5 h-5 bg-white text-primary rounded-full -top-3 -end-4 font-bold text-[10px]">
                            {{ $cartCount }}
                        </span>
                    </span>
                    <span class="lg:text-base text-sm font-medium {{ $cartCount > 0 ? '' : 'hidden' }}"
                        id="totalPrice">{{ money($totalPrice) }}</span>
                </a>
            @endif
        </div>
    </div>
</nav>

<script>
    function setupSearch(inputId, boxId) {
        const input = document.getElementById(inputId);
        const box = document.getElementById(boxId);
        let timer;

        input.addEventListener('input', function() {
            clearTimeout(timer);
            const query = this.value.trim();

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
