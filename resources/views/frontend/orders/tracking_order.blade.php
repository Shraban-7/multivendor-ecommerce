@extends('frontend.layouts.app')

@section('title', 'Track Order | Account')

@section('content')
<main class="track-order-page pb-5 sm:pb-10">
      <!-- Promotional Header Starts -->
      <section>
        <a
          href="#"
          class="block promo-header bg-[#F85606] text-white py-3 sm:py-4"
        >
          <div
            class="container flex flex-wrap justify-center xsm:justify-start items-center gap-x-2"
          >
            <i class="fa-solid fa-truck-fast text-lg"></i>
            <h3 class="text-sm">Free Shipping Special For You</h3>
            <p class="text-xs ml-2 xsm:ml-3">Limited Offer</p>
          </div>
        </a>
      </section>
      <!-- Promotional Header Ended -->

      <!-- Page Breadcrumb -->
      <section class="page-breadcrumb-links bg-[#F5F5F5] py-4 md:py-6">
        <nav class="flex container" aria-label="Breadcrumb">
          <ol
            class="inline-flex flex-wrap items-center space-x-1 md:space-x-2 rtl:space-x-reverse"
          >
            <li class="inline-flex items-center">
              <a
                href="/"
                class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq"
              >
                <svg
                  class="w-3 h-3 me-2.5"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                  />
                </svg>
                Home
              </a>
            </li>
            <li class="inline-flex items-center">
              <a
                href="#"
                class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq"
              >
                <svg
                  class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 6 10"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m1 9 4-4-4-4"
                  />
                </svg>
                Account
              </a>
            </li>
            <li aria-current="page">
              <div class="flex items-center">
                <svg
                  class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1"
                  aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 6 10"
                >
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m1 9 4-4-4-4"
                  />
                </svg>
                <span class="ms-1 text-sm text-butterfly-blue md:ms-2"
                  >Track Order</span
                >
              </div>
            </li>
          </ol>
        </nav>
      </section>

      <!-- Track Order Main Section Starts -->
      <section class="track-order-section container section-padding my-16">
        <div class="flex flex-col gap-3 md:gap-5 items-start">
          <h2
            class="text-xl xsm:text-2xl md:text-3xl text-theme-dark font-semibold"
          >
            Track Order
          </h2>
          <p
            class="text-davy-gray text-sm md:text-base w-full sm:w-10/12 md:w-8/12 xl:w-7/12"
          >
            To track your order please enter your order ID in the input field
            below and press the “Track Order” button. this was given to you on
            your receipt and in the confirmation email you should have received.
          </p>
          <!-- Order Info -->
          <form action="/tracking.html" class="block w-full">
            <div
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 w-full"
            >
              <div class="space-y-2">
                <label class="block text-sm" for="order-id">Order ID</label>
                <input
                  required
                  id="order-id"
                  type="text"
                  class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                  placeholder="ID...."
                />
              </div>
              <div class="space-y-2">
                <label class="block text-sm" for="billing-email"
                  >Billing Email</label
                >
                <input
                  required
                  type="email"
                  id="billing-email"
                  placeholder="Email Address"
                  class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                />
              </div>
            </div>
            <p class="text-jet-gray mt-3 md:mt-5">
              <i class="fa-solid fa-circle-exclamation"></i> Order ID that we
              sended to your in your email address.
            </p>
            <button
              type="submit"
              class="sm:py-4 sm:px-8 py-3 px-6 bg-[#F85606] text-white hover:bg-[#C43D00] rounded-sm font-bold uppercase eq flex items-center gap-2 hover:gap-3 text-sm md:text-base mt-4 md:mt-6"
            >
              Track Order
              <i class="fa-solid fa-arrow-right sm:text-xl text-lg"></i>
            </button>
          </form>
        </div>
      </section>
      <!-- Track Order Main Section Ended -->
    </main>
@endsection
