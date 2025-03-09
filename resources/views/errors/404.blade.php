{{-- @extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found')) --}}

@extends('frontend.layouts.app')

@section('title', 'No Order')

@section('content')
    <main class="not-found-page">
      <!-- Page Promotion Banner Starts -->
      <section class="container py-5 page-promotion md:w-full">
        <div
          class="promo-wrapper md:container bg-[#5C62D6] grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden"
        >
          <div
            class="flex flex-col items-start justify-center order-2 gap-3 p-5 md:order-1 promo-content sm:gap-5 md:p-10 lg:p-14 2xl:p-20"
          >
            <h2
              class="text-xl font-bold text-white lg:text-3xl md:text-2xl md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2"
            >
              Your Satisfaction, Our Priority - Reach Out Today
            </h2>
            <p class="text-xs text-white md:pr-7 lg:pr-14 2xl:pr-20">
              Our team is ready to assist you. Reach out to us via email, phone,
              or live chat, and we'll get back to you as soon as possible.
            </p>
            <a
              href="#"
              class="theme-btn bg-[#5A422A] px-5 py-2 lg:px-7 lg:py-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm"
              >Learn More</a
            >
          </div>
          <div class="order-1 promo-image">
            <div class="w-full img-wrap">
              <div
                class="w-full h-40 overflow-hidden rounded-lg lg:h-96 md:h-80 md:rounded-3xl"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="{{ asset('assets/frontend/images/promo-banner-image.png') }}"
                    alt="A man viewing a large size Laptop"
                    class="object-cover w-full h-full"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Page Promotion Banner Ended -->

      <!-- Page Main Content Starts -->
      <section class="container not-found-section section-padding">
        <div
          class="flex flex-col items-center justify-center gap-3 py-5 mx-auto lg:max-w-4xl sm:flex-row xsm:gap-4 md:gap-5 lg:gap-10 lg:py-10"
        >
          <!-- image -->
          <div
            class="w-40 h-48 overflow-hidden not-found-image md:w-56 md:h-64 lg:w-64 lg:h-72 xl:w-72 xl:h-80"
          >
            <img
              src="{{ asset('assets/frontend/images/assistant-robot.png') }}"
              alt="Neon Color Assistant Robot with Headphone"
              class="object-contain w-full h-full"
            />
          </div>

          <!-- content -->
          <div
            class="flex flex-col items-center gap-3 text-center md:gap-5 sm:items-start sm:text-left"
          >
            <h1
              class="text-7xl lg:text-8xl font-bold bg-gradient-to-b from-[#B2EBF2] from-5% via-[#D1C4E9] via-60% to-[#F8BBD0] to-90% bg-clip-text text-transparent"
            >
              404
            </h1>
            <h2
              class="text-2xl font-medium md:text-3xl xl:text-4xl text-theme-dark"
            >
              This page could not be found
            </h2>
            <p class="md:text-xl lg:text-2xl text-theme-dark">
              You can either stay and chill here, or go back to the beginning.
            </p>
          </div>
        </div>
      </section>
      <!-- Page Main Content Ended -->
    </main>
@endsection
