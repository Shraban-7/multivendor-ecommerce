@php
    $isCustomer = auth('web')->check();
    $isSeller = auth('seller')->check();
    $isAdmin = auth('admin')->check();
    $isGuest = ! $isCustomer && ! $isSeller && ! $isAdmin;
@endphp

<div class="hidden lg:block bg-[#191919] text-white text-xs">
    <div class="max-w-[1400px] mx-auto px-4 flex items-center justify-end h-9 gap-5">
        @if ($isCustomer)
            <a href="{{ route('profile') }}" class="hover:text-[#F85606] eq">My Account</a>
            <span class="w-[1px] h-3 bg-[#2A2A2A]"></span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="hover:text-[#F85606] eq">Logout</button>
            </form>
        @endif

        @if ($isSeller)
            <a href="{{ route('seller.dashboard') }}" class="hover:text-[#F85606] eq font-medium">Seller Panel</a>
            <span class="w-[1px] h-3 bg-[#2A2A2A]"></span>
            <form method="POST" action="{{ route('seller.logout') }}" class="inline">
                @csrf
                <button type="submit" class="hover:text-[#F85606] eq">Logout</button>
            </form>
        @endif

        @if ($isAdmin)
            <a href="{{ route('admin.dashboard') }}" class="hover:text-[#F85606] eq font-medium">Admin Panel</a>
            <span class="w-[1px] h-3 bg-[#2A2A2A]"></span>
            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                @csrf
                <button type="submit" class="hover:text-[#F85606] eq">Logout</button>
            </form>
        @endif

        @if ($isGuest)
            <a href="javascript:void(0)" class="auth-btn hover:text-[#F85606] eq">Login</a>
            <span class="w-[1px] h-3 bg-[#2A2A2A]"></span>
            <a href="{{ route('seller.signup') }}" class="hover:text-[#F85606] eq font-medium">Become a Seller</a>
        @elseif (! $isSeller && ! $isAdmin)
            <span class="w-[1px] h-3 bg-[#2A2A2A]"></span>
            <a href="{{ route('seller.signup') }}" class="hover:text-[#F85606] eq font-medium">Become a Seller</a>
        @endif

        <span class="w-[1px] h-3 bg-[#2A2A2A]"></span>
        <a href="{{ route('pages.show', 'help-center') }}" class="hover:text-[#F85606] eq">Help & Support</a>
    </div>
</div>

@include('frontend.layouts.header')

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
