@extends('frontend.layouts.app')
@section('title', 'Flash Sales')

@section('content')
    <!-- ==================== 1. PAGE HEADER ==================== -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="container mx-auto px-4 py-8">
            <nav class="flex text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-primary-600 transition">
                            <i class="fas fa-home mr-2"></i>Home
                        </a>
                    </li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="font-medium text-gray-900" aria-current="page">Flash Sales</li>
                </ol>
            </nav>
            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Flash <span class="text-primary-600">Campaigns</span></h1>
                    <p class="text-gray-500 mt-2">Grab the best deals before time runs out!</p>
                </div>
                <!-- Filter/Sort -->
                <div class="flex gap-2">
                    <button class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md shadow-primary-500/20">Active Now</button>
                    <button class="bg-white text-gray-600 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Upcoming</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== 2. CAMPAIGNS GRID ==================== -->
    <div class="container mx-auto px-4 py-12">
        
        @if($flashSales->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($flashSales as $sale)
                    @php
                        $now = \Carbon\Carbon::now();
                        $startTime = \Carbon\Carbon::parse($sale->start_time);
                        $endTime = \Carbon\Carbon::parse($sale->end_time);
                        
                        // Determine Status
                        if ($now->between($startTime, $endTime)) {
                            $status = 'active';
                            $statusLabel = 'Live Now';
                            $statusColor = 'bg-red-600';
                        } elseif ($now->lt($startTime)) {
                            $status = 'upcoming';
                            $statusLabel = 'Starts Soon';
                            $statusColor = 'bg-blue-600';
                        } else {
                            $status = 'expired';
                            $statusLabel = 'Ended';
                            $statusColor = 'bg-gray-500';
                        }
                    @endphp

                    <!-- Campaign Card -->
                    <a href="{{ route('flashSales.show', $sale->id) }}" class="group block bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl hover:border-primary-200 transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">
                        
                        <!-- Image Container -->
                        <div class="relative h-48 sm:h-56 w-full overflow-hidden bg-gray-100">
                            <!-- Background Image -->
                            <img src="{{ asset('storage/' . $sale->image) }}" 
                                 alt="{{ $sale->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent opacity-80 group-hover:opacity-60 transition duration-300"></div>
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4 z-10">
                                <span class="{{ $statusColor }} text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg flex items-center gap-1.5">
                                    @if($status === 'active') <span class="block w-2 h-2 rounded-full bg-white animate-pulse"></span> @endif
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <!-- Timer (Only for Active/Upcoming) -->
                            @if($status !== 'expired')
                            <div class="absolute bottom-4 left-4 right-4 z-10">
                                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg p-3 text-white">
                                    <div class="flex justify-between items-center text-xs mb-1 text-gray-200">
                                        <span>Ends in:</span>
                                    </div>
                                    <div class="countdown-timer flex justify-between gap-2 text-center font-mono font-bold" data-endtime="{{ $endTime }}">
                                        <!-- JS will populate this -->
                                        <div><span class="text-lg">00</span><span class="text-[10px] block font-sans font-normal opacity-70">Days</span></div>
                                        <div><span class="text-lg">:</span></div>
                                        <div><span class="text-lg">00</span><span class="text-[10px] block font-sans font-normal opacity-70">Hrs</span></div>
                                        <div><span class="text-lg">:</span></div>
                                        <div><span class="text-lg">00</span><span class="text-[10px] block font-sans font-normal opacity-70">Min</span></div>
                                        <div><span class="text-lg">:</span></div>
                                        <div><span class="text-lg text-primary-400">00</span><span class="text-[10px] block font-sans font-normal opacity-70">Sec</span></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex flex-col flex-1">
                            <h2 class="text-xl font-bold text-gray-900 group-hover:text-primary-600 transition mb-2 line-clamp-1">
                                {{ $sale->title }}
                            </h2>
                            
                            {{-- Snippet of description, strip tags to remove HTML --}}
                            <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">
                                {{ Str::limit(strip_tags($sale->description), 100) }}
                            </p>

                            <!-- Progress/Details Footer -->
                            <div class="mt-auto border-t border-gray-50 pt-4 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">
                                    <i class="fas fa-cubes text-primary-500 mr-1"></i> Products: 
                                    {{-- Assuming you have a count appended --}}
                                    {{ $sale->products_count ?? '25+' }} Items
                                </span>
                                <span class="text-primary-600 text-sm font-bold flex items-center gap-1 group-hover:translate-x-1 transition">
                                    View Sale <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $flashSales->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-bolt text-4xl text-primary-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">No Active Campaigns</h3>
                <p class="text-gray-500 mb-6">There are no flash sales running at the moment. Check back later!</p>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition shadow-lg">Back to Home</a>
            </div>
        @endif
    </div>

    <!-- ==================== JS FOR MULTIPLE TIMERS ==================== -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const timerContainers = document.querySelectorAll('.countdown-timer');
            
            timerContainers.forEach(container => {
                const endTimeStr = container.getAttribute('data-endtime');
                const endTime = new Date(endTimeStr).getTime();
                
                // Get the spans inside this specific container
                // Assuming structure: div > span(num) ...
                // A simpler way to target spans by index:
                const timeBlocks = container.querySelectorAll('div > span.text-lg'); 
                // Index 0: Days, 2: Hours, 4: Min, 6: Sec (because of separators)
                
                // OR specific selectors if you add classes to spans. 
                // Let's use a simpler robust approach for the loop:
                
                const update = () => {
                    const now = new Date().getTime();
                    const distance = endTime - now;

                    if (distance < 0) {
                        container.innerHTML = '<span class="text-xs font-bold text-gray-300 uppercase tracking-widest">Ended</span>';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // We are targeting the text content of the spans
                    if(timeBlocks.length >= 4) {
                       timeBlocks[0].innerText = days < 10 ? "0" + days : days;
                       timeBlocks[2].innerText = hours < 10 ? "0" + hours : hours;
                       timeBlocks[4].innerText = minutes < 10 ? "0" + minutes : minutes;
                       timeBlocks[6].innerText = seconds < 10 ? "0" + seconds : seconds;
                    }
                };

                setInterval(update, 1000);
                update();
            });
        });
    </script>
    @endpush
@endsection