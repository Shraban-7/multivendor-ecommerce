@extends('frontend.layouts.app')
@section('title', 'Order Details')

@section('content')
    <main class="order-details-page pb-5 sm:pb-10">
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
                <h2 class="sm:text-2xl text-xl">Order Detail</h2>

                <div class="order-details-menus pt-3 md:pt-5 pb-5 md:pb-8 border-b">
                    <ul class="flex flex-wrap gap-3">
                        <li>
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Active</a>
                        </li>
                        <li aria-current="page">
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Completed</a>
                        </li>
                        <li>
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Cancelled</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="order-details-body py-5 md:py-8">
                <!-- Order ID and Status -->
                <div class="flex flex-wrap items-start gap-5 xsm:gap-10 md:gap-16 mb-2">
                    <div>
                        <p class="font-medium">Order ID : #{{ $order->id }}</p>
                        <p class="text-xs text-davy-gray">Order Placed on: {{ \Carbon\Carbon::parse($order->created_at)->format('F d Y') }}</p>
                    </div>
                    <span class="inline-block bg-leaf-green text-white px-3.5 py-1.5 rounded-full text-sm">
                        Delivered
                    </span>
                </div>

                <!-- Order More Info -->
                <div class="lg:w-10/12 pt-5 md:pt-8">
                    <div class="flex flex-col sm:flex-row items-start gap-5">
                        <div class="space-y-5 w-full sm:w-1/2">
                            <!-- Shipping Address -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Shipping Address
                                    </h3>

                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray space-y-1 pt-3 md:space-y-1.5 md:pt-5">
                                    <p>{{ $order->customer_name }}</p>
                                    <p>{{ $order->customer_address }}</p>
                                    <div class="flex gap-2 items-center pt-2">
                                        <i class="fa-solid fa-phone"></i>
                                        <span>{{ $order->customer_phone }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Payment Details
                                    </h3>
                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray md:space-y-2 md:pt-5 space-y-1 pt-3">
                                    <div class="flex gap-2">
                                        <img src="{{ asset('assets/frontend/images/payment-method-visa.png') }}"
                                            alt="Visa Card" class="w-10 md:w-14 object-contain" />

                                        <h4 class="text-aqua-deep font-medium md:text-lg">
                                            ### 2355
                                        </h4>
                                    </div>
                                    <p class="font-medium">
                                        Expires : <span class="text-aqua-deep">06/24</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5 w-full sm:w-1/2">
                            <!-- Items Included -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Items Included
                                    </h3>
                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray space-y-1.5 divide-y-2 divide-davy-gray/10">
                                    <!-- Item 1 -->
                                    @foreach ($order->items as $item)
                                        <div class="flex gap-2 md:gap-4 py-3 md:py-5">
                                            <div class="w-16 h-20 md:w-20 md:h-24 flex-shrink-0 rounded-xl overflow-hidden">
                                                <img src="{{ asset('assets/'.$item->product->thumbnail) }}"
                                                    alt="YC Washable Wool-Blend Jumper" />
                                            </div>

                                            <div class="flex-grow space-y-1 md:space-y-2">
                                                <p class="font-medium text-sm md:text-base">
                                                    {{ $item->product->name }}
                                                </p>
                                                <p class="text-sm text-jet-gray">Quantity: {{ $item->quantity }}</p>
                                                <p class="flex items-center gap-1 text-aqua-deep mt-1">
                                                    <span class="text-lg md:text-2xl font-medium">{{ currency($item->unit_price) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Order Summary
                                    </h3>
                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray md:space-y-3 md:pt-5 space-y-2 pt-3">
                                    <p class="flex justify-between">
                                        <span>Subtotal</span>
                                        <span>{{ currency($order->sub_total) }}</span>
                                    </p>
                                    <p class="flex justify-between">
                                        <span>Tax</span>
                                        <span>{{ currency($order->tax) }}</span>
                                    </p>
                                    <p class="flex justify-between">
                                        <span class="">Delivery</span>
                                        <span class="text-leaf-green">Free</span>
                                    </p>
                                    <h2
                                        class="flex md:text-lg justify-between font-medium border-t-2 border-davy-gray/10 pt-2 md:pt-3">
                                        <span>Total</span>
                                        <span>{{ currency($order->total) }}</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-actions space-y-3 md:space-y-5 mt-8 md:mt-12">
                        <a href="#"
                            class="inline-block capitalize bg-primary text-theme-light md:text-lg lg:text-xl w-full py-2.5 md:py-3 lg:py-4 text-center rounded-full hover:bg-theme-dark eq">Return
                            or refund</a>
                        <a href="{{ route('orders.review',$order->id) }}"
                            class="inline-block capitalize border border-theme-dark text-theme-dark md:text-lg lg:text-xl w-full py-2.5 md:py-3 lg:py-4 text-center rounded-full hover:bg-theme-dark hover:text-theme-light eq">Leave
                            a feedback</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Checkout Main Section Ended -->
    </main>

    @push('scripts')
    @endpush
@endsection
