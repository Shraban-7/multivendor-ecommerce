@php
    $isUser = auth('web')->check();
    $isSeller = auth('seller')->check();
    $isAdmin = auth('admin')->check();
@endphp
<div
    class="md:hidden fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t border-[#E5E5E5] z-40 px-6 py-3 flex justify-between items-center text-[#A0A0A0]">
    <a href="{{ route('home') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('home') ? 'text-[#F85606]' : '' }} hover:text-[#F85606] transition-colors">
        <i class="fas fa-home text-lg"></i>
        <span class="text-[10px] font-medium">Home</span>
    </a>
    <a href="{{ route('products.index') }}"
        class="flex flex-col items-center gap-1 {{ request()->routeIs('products.index') ? 'text-[#F85606]' : '' }} hover:text-[#F85606] transition-colors">
        <i class="fas fa-th-large text-lg"></i>
        <span class="text-[10px] font-medium">Shop</span>
    </a>
    @if ($isUser)
        <a href="{{ route('cart.details') }}"
            class="flex flex-col items-center gap-1 relative {{ request()->routeIs('cart.details') ? 'text-[#F85606]' : '' }} hover:text-[#F85606] transition-colors">
            <div class="relative">
                <i class="fas fa-shopping-cart text-lg"></i>
                <span id="cartCount"
                    class="absolute -top-2 -right-2 bg-[#F85606] text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center font-semibold">{{ $cartCount }}</span>
            </div>
            <span class="text-[10px] font-medium">Cart</span>
        </a>
    @else
        <a href="javascript:void(0)"
            class="auth-btn flex flex-col items-center gap-1 relative hover:text-[#F85606] transition-colors">
            <div class="relative">
                <i class="fas fa-shopping-cart text-lg"></i>
                <span
                    class="hidden absolute -top-2 -right-2 bg-[#F85606] text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center"></span>
            </div>
            <span class="text-[10px] font-medium">Cart</span>
        </a>
    @endif

    @if ($isUser || $isSeller || $isAdmin)
        <a href="
        @if ($isUser) {{ route('orders.index') }}
        @elseif ($isSeller)
            {{ route('seller.dashboard') }}
        @elseif ($isAdmin)
            {{ route('admin.dashboard') }} @endif
    "
            class="flex flex-col items-center gap-1 hover:text-[#F85606] transition-colors">
            <i class="far fa-user text-lg"></i>
            <span class="text-[10px] font-medium">Account</span>
        </a>
    @else
        <a href="javascript:void(0)" class="auth-btn flex flex-col items-center gap-1 hover:text-[#F85606] transition-colors">
            <i class="far fa-user text-lg"></i>
            <span class="text-[10px] font-medium">Account</span>
        </a>
    @endif
</div>
