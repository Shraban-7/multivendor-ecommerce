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
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @foreach($products as $productItem)
            @php
                $product = $productItem->product;
            @endphp
            <div class="bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col h-full relative">
                <!-- Discount Badge -->
                <div class="absolute top-3 left-3 z-10">
                    @php
                    $discountPercent = $product->discount_amount > 0
                        ? round(($product->discount_amount / $product->selling_price) * 100)
                        : 0;
                    @endphp

                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                        -{{ $discountPercent }}%
                    </span>
                </div>

                <!-- Product Image -->
                <div class="relative h-48 w-full bg-gray-50 p-4 flex items-center justify-center overflow-hidden">
                    <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}" class="max-h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">

                    <!-- Hover Actions -->
                    <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2">
                        <button data-slug="{{ $product->slug }}" class="btn-quickview w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-75" title="Quick View">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-3 flex flex-col flex-1">
                    <span class="text-[10px] text-gray-500 uppercase tracking-wide mb-1 truncate">{{ $product->category->name ?? 'Category' }}</span>
                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 hover:text-primary-600 transition cursor-pointer">
                        <a href="{{ route('products.details', $product->slug) }}">{{ $product->name }}</a>
                    </h3>

                    <!-- Rating -->
                    <div class="flex items-center gap-1 mb-3">
                        @php
                            $rating = $product->avg_rating ?? 0;
                            $fullStars = floor($rating);
                            $hasHalfStar = ($rating - $fullStars) >= 0.5;
                        @endphp
                        
                        <div class="flex text-yellow-400 text-[10px]">
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        
                            @if ($hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @endif
                        
                            @for ($i = 0; $i < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>

                        <span class="text-[10px] text-gray-400">({{ $product->rating_count}})</span>
                    </div>

                    <!-- Price & Cart -->
                    <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex flex-col">
                            @if ($product->discount_price)
                            <span class="text-xs text-gray-400 line-through">{{ money($product->selling_price) }}</span>
                            <span class="text-primary-600 font-bold text-lg">{{ money($product->discount_price) }}</span>
                            @else
                            <span class="text-primary-600 font-bold text-lg"> {{ money($product->selling_price) }}</span>
                            @endif
                        </div>
                        <button data-slug="{{ $product->slug }}" class="btn-quickview  w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-primary-600 hover:text-white transition shadow-sm">
                            <i class="fas fa-shopping-cart text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Progress Bar (Optional: Shows stock left) -->
                <div class="px-3 pb-3">
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                        @php
                        $percentageSold = $productItem->stock_in > 0 ? $productItem->stock_out / $productItem->stock_in : 0;
                        @endphp
                        <div class="bg-gradient-to-r from-orange-400 to-red-500 h-1.5 rounded-full" style="width: {{ $percentageSold * 100 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] font-medium text-gray-500">
                        <span>Sold: {{ $productItem->stock_out }}</span>
                        <span class="text-red-500">Only {{ $productItem->stock_in - $productItem->stock_out }} left!</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>

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
        // Ensure format works for JS Date (ISO 8601 preferred)
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
        updateTimer(); // Initial call
    });
</script>
@endpush
@endsection
