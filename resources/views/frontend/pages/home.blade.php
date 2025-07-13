@extends('frontend.layouts.app')
@section('title', 'A Multi-Vendor E-Commerce platform')

@section('content')
    <main class="home-page">
        <!-- Hero Section Starts -->
        @include('frontend.partials.home.hero-section')
        <!-- Hero Section Ended -->

        <!-- Light Deals Section Starts -->
        @include('frontend.partials.home.light-deal-section')
        <!-- Light Deals Section Ended -->

        <!-- Interest Section Starts -->
        @include('frontend.partials.home.interest-section')
        <!-- Interest Section Ended -->

        <!-- Feature Gallery Section Starts -->
        @include('frontend.partials.home.feature-gallery-section')
        <!-- Feature Gallery Section Starts -->

        <!-- Promotional Header Section -->
        @include('frontend.partials.home.promotional-header-section')
        <!-- Promotional Header -->

        <!-- New Arrivals Section Start -->
        @include('frontend.partials.home.new-arrival-section')
        <!-- New Arrivals Section Ended -->

        <!-- Community Product Section Starts -->
        @include('frontend.partials.home.community-product-section')
        <!-- Community Product Section Ended -->

        <!-- Sessional Promotion Thumbnail Section Starts -->
        @include('frontend.partials.home.sessional-promotion-thumbnail-section')
        <!-- Sessional Promotion Thumbnail Section Ended -->

        <!-- Halloween Product Section Starts -->
        {{-- @include('frontend.partials.home.halloween-product-section') --}}
        <!-- Halloween Product Section Ended -->

        <!-- Featured Videos Section Starts -->
        @include('frontend.partials.home.feature-video-section')
        <!-- Featured Videos Section Ended -->
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
