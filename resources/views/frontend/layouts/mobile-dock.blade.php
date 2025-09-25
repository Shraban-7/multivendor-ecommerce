<div class="fixed bottom-0 left-1/2 -translate-x-1/2 z-50 bg-white border border-gray-200 shadow-lg w-full" style="padding: 10px;">
    <div class="grid grid-cols-4 gap-4">
        <a href="{{ route('home') }}"
            class="flex flex-col items-center justify-center 
           {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i data-feather="home"></i>
            <span class="text-xs font-medium">Home</span>
        </a>
        <a href="#"
            class="flex flex-col items-center justify-center 
           {{ request()->routeIs('shop.*') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i data-feather="grid"></i>
            <span class="text-xs font-medium">Shop</span>
        </a>
        <a href="{{ route('cart.details') }}"
            class="flex flex-col items-center justify-center 
           {{ request()->routeIs('cart.*') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i data-feather="shopping-cart"></i>
            <span class="text-xs font-medium">Cart</span>
        </a>
        <a href="{{ route('profile') }}"
            class="flex flex-col items-center justify-center 
           {{ request()->routeIs('profile.*') ? 'text-primary' : 'text-gray-500 hover:text-primary' }}">
            <i data-feather="user"></i>
            <span class="text-xs font-medium">Profile</span>
        </a>
    </div>
</div>