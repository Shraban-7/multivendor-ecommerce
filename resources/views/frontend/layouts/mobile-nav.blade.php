<div class="md:hidden fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] border-t border-gray-100 z-40 px-6 py-3 flex justify-between items-center text-gray-400">
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-primary-600">
        <i class="fas fa-home text-lg"></i>
        <span class="text-[10px] font-medium">Home</span>
    </a>
    <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-1 hover:text-primary-600">
        <i class="fas fa-th-large text-lg"></i>
        <span class="text-[10px] font-medium">Shop</span>
    </a>
    <a href="#" class="flex flex-col items-center gap-1 hover:text-primary-600 relative">
        <div class="relative">
            <i class="fas fa-shopping-cart text-lg"></i>
            <span id="cartCount"
                class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -top-2 -right-2 bg-primary-600 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
        </div>
        <span class="text-[10px] font-medium">Cart</span>
    </a>
    <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 hover:text-primary-600">
        <i class="far fa-user text-lg"></i>
        <span class="text-[10px] font-medium">Account</span>
    </a>
</div>