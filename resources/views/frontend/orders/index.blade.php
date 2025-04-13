@extends('frontend.layouts.app')

@section('title', 'Order History')

@section('content')
    <main class="order-history-page">
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

        <!-- Order History Section Starts -->
        <section>
            <div class="container section-padding">
                <h1 class="text-xl md:text-2xl font-medium text-theme-dark mb-6">
                    Your Order History
                </h1>

                <!-- Order Filters -->
                <div class="order-filters flex flex-wrap gap-4 mb-8">
                    <a href="{{ route('orders.index', ['status' => 'all']) }}">
                        <button
                            class="px-4 py-2 {{ $status == 'all' ? 'bg-primary text-white' : 'bg-white border border-gray-200' }} rounded-md">All
                            Orders</button>
                    </a>
                    <a href="{{ route('orders.index', ['status' => \App\Enums\OrderStatus::PENDING->label()]) }}">
                        <button
                            class="px-4 py-2 {{ $status == 'pending' ? 'bg-primary text-white' : 'bg-white border border-gray-200' }} rounded-md hover:bg-gray-50 eq">Pending</button>
                    </a>
                    <a href="{{ route('orders.index', ['status' => \App\Enums\OrderStatus::SHIPPED->label()]) }}">
                        <button
                            class="px-4 py-2 {{ $status == 'shipped' ? 'bg-primary text-white' : 'bg-white border border-gray-200' }} rounded-md hover:bg-gray-50 eq">Shipped</button>
                    </a>
                    <a href="{{ route('orders.index', ['status' => \App\Enums\OrderStatus::DELIVERED->label()]) }}">
                        <button
                            class="px-4 py-2 {{ $status == 'delivered' ? 'bg-primary text-white' : 'bg-white border border-gray-200' }} rounded-md hover:bg-gray-50 eq">Delivered</button>
                    </a>
                    <a href="{{ route('orders.index', ['status' => \App\Enums\OrderStatus::CANCELLED->label()]) }}">
                        <button
                            class="px-4 py-2 {{ $status == 'cancelled' ? 'bg-primary text-white' : 'bg-white border border-gray-200' }} rounded-md hover:bg-gray-50 eq">Canceled</button>
                    </a>
                </div>

                <!-- Orders List -->
                <div class="orders-list space-y-6">

                    @foreach ($orders as $order)
                        <!-- Order Item 1 -->
                        <div class="order-item border border-gray-200 rounded-lg overflow-hidden hover:shadow-md eq">
                            <div class="order-header bg-gray-50 p-4 flex flex-wrap justify-between items-center">
                                <div class="order-info space-y-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-medium">Order #{{ $order->id }}</h3>
                                        <!-- Dynamic status badge -->
                                        @if ($order->status == \App\Enums\OrderStatus::DELIVERED->value)
                                            <span
                                                class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Delivered</span>
                                        @elseif ($order->status == \App\Enums\OrderStatus::PENDING->value)
                                            <span
                                                class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pending</span>
                                        @elseif ($order->status == \App\Enums\OrderStatus::SHIPPED->value)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Shipped</span>
                                        @elseif ($order->status == \App\Enums\OrderStatus::CANCELLED->value)
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Cancelled</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">Placed on
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y') }}</p>
                                </div>
                                <div class="order-actions flex gap-2">
                                    <a href="{{ route('orders.tracking',$order->invoice_id) }}" class="text-butterfly-blue hover:text-primary eq text-sm">Track
                                        Order</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('orders.details',$order->id) }}" class="text-butterfly-blue hover:text-primary eq text-sm">View
                                        Details</a>
                                </div>
                            </div>
                            <div class="order-content p-4">
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="order-shop-name flex flex-wrap gap-4">
                                        <!-- Shop Name -->
                                        <p class="text-sm text-gray-700">Shop: {{ $order->seller->business_name }}</p>
                                    </div>
                                    <div class="order-summary md:ml-auto space-y-1">
                                        <p class="flex justify-between gap-8"><span class="text-gray-500">Total:</span>
                                            <span class="font-medium">{{ money($order->sub_total) }}</span>
                                        </p>
                                        <p class="flex justify-between gap-8"><span class="text-gray-500">Items:</span>
                                            <span>{{ $order->items_count }} items</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination flex justify-center mt-10">
                    <div class="flex items-center gap-2">
                        <!-- Previous Page Link -->
                        @if ($orders->onFirstPage())
                            <span
                                class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-md text-gray-300">
                                <i class="fa-solid fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}"
                                class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-md hover:bg-gray-50 eq">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif

                        <!-- Page Number Links -->
                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            @if ($page == $orders->currentPage())
                                <a href="{{ $url }}"
                                    class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-md">{{ $page }}</a>
                            @else
                                <a href="{{ $url }}"
                                    class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-md hover:bg-gray-50 eq">{{ $page }}</a>
                            @endif
                        @endforeach

                        <!-- Ellipsis -->
                        @if ($orders->lastPage() > $orders->currentPage() + 1)
                            <span class="px-2">...</span>
                        @endif

                        <!-- Next Page Link -->
                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}"
                                class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-md hover:bg-gray-50 eq">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @else
                            <span
                                class="w-10 h-10 flex items-center justify-center border border-gray-200 rounded-md text-gray-300">
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </section>
        <!-- Order History Section Ended -->

        <!-- Explore Interest Section Start  -->
        <section class="explore-interest section-padding">
            <div class="container">
                <!-- Section Tittle -->
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-medium text-jet-gray mb-5 md:mb-8 lg:mb-10">
                    Explore Your Interest
                </h1>

                <div class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-3 gap-5 xl:gap-8 lg:p-0 p-2 items-start">
                    @foreach ($products as $product)
                    <div
                        class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq">
                        <div
                            class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg">
                            <a href="{{ route('product.details',$product['slug']) }}" class="block w-full h-full">
                                <img src="{{ $product['image'] }}"
                                    alt="The Iconic Doeskin Blazer" class="w-full h-full object-cover" />
                            </a>
                            <button
                                class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq">
                                <i class="fa-regular fa-eye"></i>
                                Quick View
                            </button>
                        </div>

                        <div class="p-4 xsm:p-2 lg:p-5">
                            <h3 class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3">
                                <a href="{{ route('product.details',$product['slug']) }}" class="hover:text-primary eq">{{ $product['name']}}</a>
                            </h3>
                            <p class="text-leaf-green">Almost sold Out</p>

                            <div class="flex flex-wrap items-center gap-x-1">
                                <div class="flex items-center flex-no-wrap gap-x-1 text-light-yellow">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <img src="{{ asset('assets/frontend/images/fire-icon.png') }}" class="w-8 h-auto"
                                        alt="Fire Icon" />
                                </div>

                                <span class="text-jet-gray">{{ $product['sold_out'] }}+ Sold</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2">
                                <span class="text-primary/80">Final Hours</span>
                                <div class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8">
                                    <div class="price flex items-center gap-1 flex-no-wrap">
                                        <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                        <span class="align-center text-sm text-[#ffa755]">{{ currency() }}</span>
                                        <h3 class="font-bold text-primary">{{ $product['price'] }}</h3>
                                    </div>
                                    <div class="cartBtn" data-id="{{ $product['id'] }}">
                                        <input type="hidden" name="quantity" value="1"
                                                    id="qtyInput{{ $product['id'] }}">
                                        <button type="button"
                                            class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Load More Products Button -->
                <div class="load-more-btn text-center pt-10">
                    <button
                        class="theme-btn bg-theme-teal hover:bg-aqua-deep text-white px-5 py-2 xl:text-xl text-base md:text-lg inline-flex gap-2 items-center eq"
                        type="button">
                        <span>Load More</span>
                        <i class="fa-solid fa-caret-down"></i>
                    </button>
                </div>
            </div>
        </section>
        <!-- Explore Interest Section Ended  -->
    </main>
@endsection
