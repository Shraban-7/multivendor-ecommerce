@extends('frontend.layouts.app')
@section('title', $flashSale->title)

@section('content')
<!-- ==================== 1. HERO / BANNER SECTION ==================== -->
<div class="relative bg-gray-900 text-white overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('storage/' . $flashSale->image) }}" alt="{{ $flashSale->title }}" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-gray-900/50"></div>
    </div>

    <div class="relative container mx-auto px-4 py-16 md:py-24 text-center">
        <!-- Badge -->
        <span class="inline-block py-1 px-3 rounded-full bg-red-600 text-white text-xs font-bold tracking-wider uppercase mb-4 animate-pulse">
            Limited Time Offer
        </span>

        <!-- Title -->
        <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
            {{ $flashSale->title }}
        </h1>

        <!-- Countdown Timer -->
        <div class="flex justify-center items-center gap-4 mt-8" id="countdown-timer" data-endtime="{{ $flashSale->end_time }}">
            <!-- Days -->
            <div class="flex flex-col items-center p-3 bg-white/10 backdrop-blur-md rounded-lg border border-white/20 min-w-[70px] md:min-w-[90px]">
                <span class="text-2xl md:text-4xl font-bold text-white" id="days">00</span>
                <span class="text-[10px] md:text-xs text-gray-300 uppercase tracking-widest mt-1">Days</span>
            </div>
            <span class="text-2xl font-bold text-primary-500">:</span>
            <!-- Hours -->
            <div class="flex flex-col items-center p-3 bg-white/10 backdrop-blur-md rounded-lg border border-white/20 min-w-[70px] md:min-w-[90px]">
                <span class="text-2xl md:text-4xl font-bold text-white" id="hours">00</span>
                <span class="text-[10px] md:text-xs text-gray-300 uppercase tracking-widest mt-1">Hours</span>
            </div>
            <span class="text-2xl font-bold text-primary-500">:</span>
            <!-- Minutes -->
            <div class="flex flex-col items-center p-3 bg-white/10 backdrop-blur-md rounded-lg border border-white/20 min-w-[70px] md:min-w-[90px]">
                <span class="text-2xl md:text-4xl font-bold text-white" id="minutes">00</span>
                <span class="text-[10px] md:text-xs text-gray-300 uppercase tracking-widest mt-1">Mins</span>
            </div>
            <span class="text-2xl font-bold text-primary-500">:</span>
            <!-- Seconds -->
            <div class="flex flex-col items-center p-3 bg-primary-600 backdrop-blur-md rounded-lg shadow-lg shadow-primary-500/50 min-w-[70px] md:min-w-[90px]">
                <span class="text-2xl md:text-4xl font-bold text-white" id="seconds">00</span>
                <span class="text-[10px] md:text-xs text-white/80 uppercase tracking-widest mt-1">Secs</span>
            </div>
        </div>

        <p class="mt-6 text-gray-300 text-sm md:text-base">
            Hurry up! Offer ends on {{ \Carbon\Carbon::parse($flashSale->end_time)->format('d M, Y h:i A') }}
        </p>
    </div>
</div>

<div class="bg-gray-50 min-h-screen pb-12">
    <div class="container mx-auto px-4 -mt-8 relative z-10">

        <!-- ==================== 2. RICH TEXT DESCRIPTION ==================== -->
        @if($flashSale->description)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 prose prose-orange max-w-none">
            {{-- Use {!! !!} for rich text output. Ensure you sanitize this in the backend! --}}
            {!! $flashSale->description !!}
        </div>
        @endif

        <!-- ==================== 3. SEARCH & FILTERS ==================== -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">
                Flash <span class="text-primary-600">Items</span>
                <span class="text-sm font-normal text-gray-500 ml-2">({{ $products->count() }} items found)</span>
            </h2>

            <div class="flex w-full md:w-auto gap-3">
                <div class="relative w-full md:w-64">
                    <input type="text" placeholder="Search within sale..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none text-sm transition">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
                <select class="px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-600 focus:border-primary-500 outline-none bg-white">
                    <option>Sort By: Popularity</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Newest First</option>
                </select>
            </div>
        </div>

        <!-- ==================== 4. PRODUCT GRID ==================== -->
        @if($products->count() > 0)
        <div id="flash-products-grid" class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3">
            @foreach($products as $productItem)
                <x-frontend.flash-sale-card :product="$productItem->product" />
            @endforeach
        </div>

        @if ($products->hasMorePages())
        <div class="mt-6 text-center">
            <button id="loadMoreFlashProducts" data-page="1" data-url="{{ request()->url() }}"
                class="inline-flex items-center gap-2 px-6 py-2 border border-brand text-brand text-xs font-semibold rounded-sm hover:bg-brand hover:text-white transition-colors duration-100"
                type="button">
                <span>Load More</span>
                <i class="fas fa-chevron-down text-[10px]"></i>
            </button>
        </div>
        @endif

        @else
        <!-- ==================== EMPTY STATE ==================== -->
        <div class="flex flex-col items-center justify-center py-16 text-center bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400 text-3xl">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">No Items Found</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-6">It looks like the products for this campaign haven't been added yet or are currently out of stock.</p>
            <a href="{{ route('home') }}" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium">
                Continue Shopping
            </a>
        </div>
        @endif

    </div>
</div>

<!-- ==================== JS FOR TIMER ==================== -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const timerContainer = document.getElementById('countdown-timer');
        if (!timerContainer) return;

        const endTimeStr = timerContainer.getAttribute('data-endtime');
        const endTime = new Date(endTimeStr).getTime();

        const daysEl = document.getElementById('days');
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');

        const updateTimer = () => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(interval);
                timerContainer.innerHTML = '<span class="text-2xl font-bold text-red-500 bg-white px-4 py-2 rounded">Campaign Expired</span>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            daysEl.innerText = days < 10 ? "0" + days : days;
            hoursEl.innerText = hours < 10 ? "0" + hours : hours;
            minutesEl.innerText = minutes < 10 ? "0" + minutes : minutes;
            secondsEl.innerText = seconds < 10 ? "0" + seconds : seconds;
        };

        const interval = setInterval(updateTimer, 1000);
        updateTimer();
    });

    // Load More Flash Products
    $(document).on('click', '#loadMoreFlashProducts', function() {
        const button = $(this);
        let page = parseInt(button.data('page')) + 1;
        const url = button.data('url');

        $.ajax({
            url: url,
            method: 'GET',
            data: { page: page },
            beforeSend: function() {
                button.prop('disabled', true).html(
                    '<svg class="animate-spin h-4 w-4 text-brand" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                );
            },
            success: function(response) {
                if ($.trim(response) !== '') {
                    $('#flash-products-grid').append(response);
                    button.data('page', page);
                    button.prop('disabled', false).html(
                        '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                    );
                } else {
                    button.hide();
                }
            },
            error: function() {
                button.prop('disabled', false).html(
                    '<span>Load More</span> <i class="fas fa-chevron-down text-[10px]"></i>'
                );
            }
        });
    });
</script>
@endpush
@endsection
