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
            @include('frontend.partials.home.halloween-product-section')
        <!-- Halloween Product Section Ended -->

        <!-- Featured Videos Section Starts -->
            @include('frontend.partials.home.feature-video-section')
        <!-- Featured Videos Section Ended -->
    </main>

    @push('scripts')
        <!-- quick view-->
    @endpush
@endsection
