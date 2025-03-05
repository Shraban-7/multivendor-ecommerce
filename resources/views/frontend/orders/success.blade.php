@extends('frontend.layouts.app')

@section('title', 'Order Successful')

@section('content')
    <main class="order-success-page pb-5 sm:pb-10">
        <!-- Promotional Header Starts -->
        <section>
            <a href="#" class="block promo-header bg-light-yellow text-white py-3 sm:py-4">
                <div class="container flex flex-wrap justify-center xsm:justify-start items-center gap-x-2">
                    <i class="fa-solid fa-truck-fast text-lg"></i>
                    <h3 class="text-sm">Free Shipping Special For You</h3>
                    <p class="text-xs ml-2 xsm:ml-3">Limited Offer</p>
                </div>
            </a>
        </section>
        <!-- Promotional Header Ended -->

        <!-- Page Breadcrumb -->
        <section class="page-breadcrumb-links bg-jet-gray/10 py-4 md:py-6">
            <nav class="flex container" aria-label="Breadcrumb">
                <ol class="inline-flex flex-wrap items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
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
                            Shopping Cart
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            Checkout
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-butterfly-blue md:ms-2">Order Success</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Order Sucessfull Main Section Starts -->
        <section class="order-success-section container section-padding my-16">
            <div class="flex flex-col gap-2 md:gap-4 items-center text-center">
                <span
                    class="w-14 xsm:w-16 h-14 xsm:h-16 bg-leaf-green/20 border-leaf-green border-2 xsm:border-[5px] text-leaf-green text-3xl xsm:text-4xl flex items-center justify-center rounded-full mb-3"><i
                        class="fa-solid fa-check"></i></span>
                <h2 class="text-lg xsm:text-xl md:text-2xl text-theme-dark font-semibold">
                    Your order is successfully place
                </h2>
                <p class="text-davy-gray text-sm w-10/12 sm:w-2/3 md:w-1/2 lg:w-5/12 xl:w-1/3">
                    Your order has been successfully placed. A confirmation has been
                    sent, and we will process your order shortly. Thank you for shopping
                    with us.
                </p>
                <div class="flex items-center gap-2 xsm:gap-5 text-xs xsm:text-sm mt-3">
                    <a href="{{ route('orders.tracking',$order->tracking_id) }}"
                        class="inline-flex items-center gap-1 xsm:gap-2 text-primary sm:py-2.5 sm:px-5 py-1.5 px-4 border-2 border-primary/30 hover:bg-primary text-primary hover:text-white rounded-sm font-bold uppercase eq">
                        <span><svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_592_207)">
                                    <path d="M2.5 13.75L10 18.125L17.5 13.75" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M2.5 6.25L10 10.625L17.5 6.25L10 1.875L2.5 6.25Z" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_592_207">
                                        <rect width="20" height="20" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </span>
                        Track Order
                    </a>
                    <a href="{{ route('orders.details',$order->id) }}"
                        class="sm:py-2.5 sm:px-5 py-1.5 px-4 bg-primary text-white hover:bg-theme-dark rounded-sm font-bold uppercase eq border-2 border-primary hover:border-theme-dark">
                        View Order
                    </a>
                </div>
            </div>
        </section>
        <!-- Order Sucessfull Main Section Ended -->
    </main>
@endsection
