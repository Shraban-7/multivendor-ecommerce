<?php
$settings = settings();
?>

<nav class="container mx-auto px-4 py-3">
    <div class="flex flex-col gap-3 md:hidden">
        <div class="flex items-center justify-between">
            <div>
                <a href="/">
                    <img src="{{ storage_url($settings->logo) }}" class="h-8" alt="Logo" />
                </a>
            </div>

            @if (!auth('web')->check() && !auth()->guard('seller')->check())
                <a href="{{ route('login') }}" class="text-sm hover:text-light-yellow flex items-center gap-1">
                    <i class="fa-regular fa-user"></i> <span>Sign In</span>
                </a>
            @else
                <a href="{{ route('profile') }}" class="flex items-center gap-1 text-sm hover:text-light-yellow">
                    <i class="fa-regular fa-user"></i>
                </a>
            @endif
        </div>

        <div class="w-full">
            <div class="relative w-full">
                <input type="text" placeholder="Search Everything at {{ $settings->app_name }}"
                    class="w-full py-2 pl-4 pr-10 text-sm rounded-full border border-gray-300 focus:outline-none focus:border-primary focus:ring-1 focus:ring-light-yellow" />
                <button class="absolute top-1/2 right-1 transform -translate-y-1/2 bg-light-yellow p-2 rounded-full">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="hidden md:grid md:grid-cols-3 md:items-center md:gap-6">
        <div>
            <a href="/">
                <img src="{{ storage_url($settings->logo) }}" class="h-8 md:h-10 lg:h-12 w-auto" alt="Logo" />
            </a>
        </div>
        <div class="flex justify-center">
            <div class="relative w-full max-w-md">
                <input type="text" placeholder="Search Dresses, Sneakers, Gift Items etc..."
                    class="w-full py-2 pl-4 pr-10 text-sm md:text-base rounded-full border border-gray-300 focus:outline-none focus:border-primary focus:ring-1 focus:ring-light-yellow" />
                <button class="absolute top-1/2 right-1 transform -translate-y-1/2 bg-light-yellow p-2 rounded-full">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>

        <div class="flex justify-end items-center gap-4">
            @if (!auth('web')->check() && !auth()->guard('seller')->check())
                <a href="{{ route('login') }}" class="flex items-center gap-1 hover:text-light-yellow">
                    <i class="fa-regular fa-user"></i>
                    <span class="text-sm lg:text-base">Sign In</span>
                </a>
            @else
                <div class="relative group inline-block">
                    <!-- Button -->
                    <button type="button" class="flex items-center gap-2 hover:text-light-yellow focus:outline-none">
                        <i class="fa-regular fa-user text-lg"></i>
                        <span class="text-sm lg:text-base">
                            {{ auth('web')->user()->name ?? auth('seller')->user()->name }}
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div
                        class="absolute right-0 z-50 hidden group-hover:block bg-white shadow-lg rounded-md w-40 top-full">
                        <ul class="py-2 text-gray-700">
                            @if (auth('web')->user())
                                <li>
                                    <a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-gray-100">
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 hover:bg-gray-100">
                                        Orders
                                    </a>
                                </li>
                                <li class="border-t">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            @endif
                            @if (auth('seller')->user())
                                <li>
                                    <a href="{{ route('seller.dashboard') }}"
                                        class="block px-4 py-2 hover:bg-gray-100">
                                        Dashboard
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Notification -->
                <div class="relative group inline-block">

                    @php
                        $notifications = notifications(10);
                        $notificationCount = notificationCount();
                    @endphp
                    <!-- Bell Icon -->
                    <button type="button"
                        class="relative flex items-center hover:text-light-yellow focus:outline-none">
                        <i class="fa-regular fa-bell text-lg"></i>
                        @if ($notificationCount > 0)
                            <span
                                class="absolute -top-2 -end-2 w-4 h-4 bg-white text-persian-red text-[10px] font-bold rounded-full flex items-center justify-center">
                                {{ $notificationCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div
                        class="absolute right-0 z-50 hidden group-hover:block bg-white shadow-lg rounded-md w-64 top-full mt-2 overflow-hidden">
                        <div class="p-3 text-gray-800 text-sm font-semibold border-b">Notifications</div>
                        <ul class="max-h-60 overflow-y-auto text-sm text-gray-700 divide-y">
                            @forelse ($notifications as $notification)
                                <li class="px-4 py-2 hover:bg-gray-100">
                                    <a href="{{ $notification->link ?? '#' }}" class="block">
                                        {{ $notification->message }}
                                    </a>
                                </li>
                            @empty
                                <li class="px-4 py-2 text-gray-500">No notifications</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Cart -->
                <a href="{{ route('cart.details') }}"
                    class="flex flex-col items-center leading-none hover:text-light-yellow eq">
                    <span class="block relative">
                        <i class="fa-solid fa-cart-arrow-down"></i>
                        <span id="cartCount"
                            class="absolute flex {{ $cartCount > 0 ? '' : 'hidden' }} items-center justify-center w-5 h-5 bg-theme-light text-light-yellow rounded-full -top-3 -end-4 font-[arial] font-bold text-[10px]">
                            {{ $cartCount }}
                        </span>
                    </span>
                    <span class="lg:text-base text-sm font-medium" id="totalPrice">{{ money($totalPrice) }}</span>
                </a>
            @endif
        </div>
    </div>
</nav>
