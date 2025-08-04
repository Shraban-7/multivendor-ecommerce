@extends('frontend.layouts.app')
@section('title', 'A Multi-Vendor E-Commerce platform')

@section('content')
    <main class="home-page">
        @include('frontend.partials.home.hero-section')

        {{-- @include('frontend.partials.home.light-deal-section') --}}

        @include('frontend.partials.home.categories')
        
        {{-- @include('frontend.partials.home.new-arrival-section') --}}
        
        <x-frontend.home.products-section-slider section="New Arrival" :products="$new_arrival_products" />
        <x-frontend.home.products-section-slider section="Trending" :products="$trending_products" />
        <x-frontend.home.products-section-slider section="Best Selling" :products="$bestselling_products" :slider="false" />
        <x-frontend.home.products-section-slider section="Featured Products" :products="$featured_products" :slider="false" />

        {{-- @include('frontend.partials.home.feature-gallery-section') --}}

        {{-- @include('frontend.partials.home.promotional-header-section') --}}

        {{-- @include('frontend.partials.home.community-product-section') --}}

        {{-- @include('frontend.partials.home.sessional-promotion-thumbnail-section') --}}

        {{-- @include('frontend.partials.home.halloween-product-section') --}}

        {{-- @include('frontend.partials.home.feature-video-section') --}}
    </main>

    @push('scripts')
        <script>
            function initializeCountdownTimers() {
                const timers = document.querySelectorAll('.countdown-timer');

                timers.forEach(timer => {
                    const endTime = new Date(timer.dataset.endTime).getTime();

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = endTime - now;

                        if (distance < 0) {
                            timer.innerText = "Expired";
                            return;
                        }

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        timer.innerText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                });
            }

            document.addEventListener('DOMContentLoaded', initializeCountdownTimers);
        </script>
    @endpush
@endsection
