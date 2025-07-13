@extends('frontend.layouts.app')
@section('title', $campaign->title ?? 'Campaign')

@section('content')
    <section class="container py-10">
        <div class="relative rounded-xl overflow-hidden mb-10">
            <!-- Background Image -->
            <div class="h-60 md:h-80 w-full bg-cover bg-center flex items-center justify-center"
                style="background-image: url('{{ storage_url($campaign->image ?? '') }}');">
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/60"></div>

                <!-- Campaign Content -->
                <div class="relative z-10 text-center text-white px-4">
                    <h1 class="text-3xl md:text-5xl font-bold mb-2">{{ $campaign->title ?? 'Campaign Details' }}</h1>

                    <p class="text-sm md:text-lg">
                        Ends in: <span class="countdown-timer" data-end-time="{{ $campaign->end_date }}">
                            Loading...
                        </span>
                    </p>
                </div>
            </div>
        </div>


        <!-- Products Grid -->
        @if (!empty($products))
            <div class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>
        @else
            <p class="text-gray-500">No products in this campaign yet.</p>
        @endif
    </section>

    @push('scripts')
        <script>
            function startCampaignCountdown() {
                document.querySelectorAll('.countdown-timer').forEach(timer => {
                    const endTime = new Date(timer.dataset.endTime).getTime();

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const diff = endTime - now;

                        if (diff <= 0) {
                            timer.innerText = "Expired";
                            return;
                        }

                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        timer.innerText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                });
            }

            document.addEventListener('DOMContentLoaded', startCampaignCountdown);
        </script>
    @endpush
@endsection
