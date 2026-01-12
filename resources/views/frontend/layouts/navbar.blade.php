<div class="bg-gray-900 text-white text-xs py-2 hidden md:block">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <div class="flex gap-4">
            @if ($settings->phone)
                <span><i class="fas fa-phone-alt mr-1 text-primary-500"></i> {{ $settings->phone }}</span>
            @endif
            @if ($settings->email)
                <span><i class="fas fa-envelope mr-1 text-primary-500"></i> {{ $settings->email }}</span>
            @endif
        </div>
        <div class="flex gap-4 items-center">
            @php
            $flash_sales =  \App\Models\FlashSale::active()
                ->withCount("approveProducts")
                ->having("approve_products_count", ">", 0)
                ->with("approveProducts")
                ->get();
            @endphp
            @if($flash_sales)
            <span><i class="fas fa-bolt text-primary-500"></i> <a href="{{ route('flashSales.index') }}" class="hover:text-primary-500 transition">Flash Sale</a></span>
            @endif
            {{-- <span><i class="fas fa-truck mr-1 text-primary-500"></i> Free Shipping over ৳2000</span> --}}
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
