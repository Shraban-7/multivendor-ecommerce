@extends('frontend.layouts.app')
@section('title', 'Order Details')

@section('content')
    <main class="checkout-page pb-5 sm:pb-10">
        <!-- Page Breadcrumb -->
        <section class="page-breadcrumb-links bg-jet-gray/10 py-4 md:py-6">
            <nav class="flex container" aria-label="Breadcrumb">
                <ol class="inline-flex flex-wrap items-center gap-x-1 gap-y-2 md:gap-x-2 rtl:gap-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            Order History
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            Order Details
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-butterfly-blue md:ms-2">Completed</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Order Details Main Section Starts -->
        <section class="order-details-section container section-padding">
            <div class="order-details-head">
                <h2>Order Detail</h2>

                <div class="order-details-menus"></div>
            </div>
        </section>
        <!-- Checkout Main Section Ended -->
    </main>

    @push('scripts')
    @endpush
@endsection
