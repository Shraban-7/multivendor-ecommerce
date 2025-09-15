<div
    class="fixed bottom-0 left-1/2 -translate-x-1/2 z-50 
            bg-white border border-gray-200 shadow-lg 
            w-full max-w-md px-4 py-2">
    <div class="grid grid-cols-4 gap-4">

        <!-- Home -->
        <a href="{{ route('home') }}" 
           class="flex flex-col items-center justify-center 
           {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i class="fa-solid fa-house"></i>
            <span class="text-xs font-medium">Home</span>
        </a>

        <!-- Shop -->
        <a href="#" 
           class="flex flex-col items-center justify-center 
           {{ request()->routeIs('shop.*') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i class="fa-solid fa-shop"></i>
            <span class="text-xs font-medium">Shop</span>
        </a>

        <!-- Cart -->
        <a href="{{ route('cart.details') }}" 
           class="flex flex-col items-center justify-center 
           {{ request()->routeIs('cart.*') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="text-xs font-medium">Cart</span>
        </a>

        <!-- Profile -->
        <a href="{{ route('profile') }}" 
           class="flex flex-col items-center justify-center 
           {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i class="fa-solid fa-user"></i>
            <span class="text-xs font-medium">Profile</span>
        </a>

    </div>
</div>
