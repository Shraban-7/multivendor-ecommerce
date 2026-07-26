<div id="mobile-drawer" class="fixed top-0 left-0 w-[85%] max-w-[300px] h-full bg-white z-[60] transform -translate-x-full shadow-2xl flex flex-col transition-transform duration-300 ease-in-out">
    <div class="bg-[#F85606] text-white px-4 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fas fa-user text-sm"></i>
            </div>
            @auth
                <div>
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-white/70">{{ auth()->user()->email }}</p>
                </div>
            @else
                <div>
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:underline">Login / Register</a>
                </div>
            @endauth
        </div>
        <button type="button" onclick="document.getElementById('mobile-drawer').classList.add('-translate-x-full'); document.getElementById('mobile-drawer-overlay').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20 eq">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <div class="py-2">
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Home
            </a>
        </div>

        <div class="border-t border-[#E5E5E5] pt-2">
            <p class="px-4 py-2 text-[11px] font-semibold text-[#767676] uppercase tracking-wider">Categories</p>
            @foreach (dropdown_categories() as $cat)
                <div class="cat-accordion">
                    <button type="button" class="cat-accordion-btn w-full flex items-center justify-between px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                        <div class="flex items-center gap-3">
                            @if ($cat->icon)
                                <img src="{{ storage_url($cat->icon) }}" alt="" class="w-5 h-5" loading="lazy">
                            @endif
                            <span>{{ $cat->name }}</span>
                        </div>
                        <svg class="cat-accordion-arrow w-3 h-3 text-[#767676] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="cat-accordion-body hidden bg-[#FAFAFA]">
                        <a href="{{ route('category.details', $cat->slug) }}"
                           class="block px-4 py-2.5 pl-12 text-sm text-[#595959] hover:text-[#F85606] hover:bg-[#FFF1EA] eq font-medium">
                            {{ $cat->name }}
                        </a>
                        @if ($cat->children && $cat->children->isNotEmpty())
                            @foreach ($cat->children as $sub)
                                <a href="{{ route('category.details', $sub->slug) }}"
                                   class="block px-4 py-2 pl-14 text-sm text-[#767676] hover:text-[#F85606] hover:bg-[#FFF1EA] eq">
                                    {{ $sub->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border-t border-[#E5E5E5] pt-2">
            <p class="px-4 py-2 text-[11px] font-semibold text-[#767676] uppercase tracking-wider">Quick Links</p>
            <a href="{{ route('flashSales.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                <i class="fas fa-bolt w-5 text-center text-[#F85606]"></i>
                Flash Sale
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                All Products
            </a>
            <a href="{{ route('sellers.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                <i class="fas fa-store w-5 text-center text-[#595959]"></i>
                Top Sellers
            </a>
            @auth
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Track Order
                </a>
            @endauth
        </div>

        @auth
            <div class="border-t border-[#E5E5E5] pt-2">
                <p class="px-4 py-2 text-[11px] font-semibold text-[#767676] uppercase tracking-wider">My Account</p>
                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                    <i class="far fa-user w-5 text-center text-[#595959]"></i>
                    My Profile
                </a>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                    <i class="fas fa-box w-5 text-center text-[#595959]"></i>
                    My Orders
                </a>
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                    <i class="far fa-heart w-5 text-center text-[#595959]"></i>
                    Wishlist
                </a>
                @if (auth()->user()->isAffiliate())
                    <a href="{{ route('affiliator.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-[#191919] hover:bg-[#FFF1EA] hover:text-[#F85606] eq">
                        <i class="fas fa-link w-5 text-center text-[#595959]"></i>
                        Affiliate Dashboard
                    </a>
                @endif
            </div>
        @endauth
    </div>

    @auth
        <div class="border-t border-[#E5E5E5] p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-[#D93025] bg-[#FFF1EA] hover:bg-[#D93025] hover:text-white eq rounded">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    @else
        <div class="border-t border-[#E5E5E5] p-4">
            <a href="{{ route('seller.signup') }}"
               class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-white bg-[#F85606] hover:bg-[#C43D00] eq rounded mb-2">
                <i class="fas fa-store"></i>
                Become a Seller
            </a>
        </div>
    @endauth
</div>

<div id="mobile-drawer-overlay" class="fixed inset-0 bg-black/50 z-50 hidden" onclick="document.getElementById('mobile-drawer').classList.add('-translate-x-full'); this.classList.add('hidden')"></div>

<script>
$(document).ready(function() {
    $('.cat-accordion-btn').on('click', function() {
        const body = $(this).next('.cat-accordion-body');
        const arrow = $(this).find('.cat-accordion-arrow');
        body.slideToggle(200);
        arrow.toggleClass('rotate-180');
    });
});
</script>
