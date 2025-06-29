@extends('frontend.layouts.app')

@section('title', 'My Orders')

@section('content')
    <?php
    use App\Enums\OrderStatus;
    ?>
    <main class="orders-page">
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
                            Account
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-butterfly-blue md:ms-2">My Orders</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        @if ($orders->isNotEmpty())
            <!-- Orders Main Section Starts -->
            <section class="orders-section container mx-auto py-10">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-jet-gray/30">
                        <h1 class="text-xl font-semibold text-gray-800">My Orders</h1>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <table id="order-table" class="w-full text-sm text-left text-gray-700">
                            <thead class="bg-gray-100 text-xs uppercase font-medium text-gray-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Invoice #</th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Total</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($orders as $order)
                                    <tr class="bg-white hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-semibold text-gray-900">#{{ $order->invoice_id }}</td>
                                        <td class="px-6 py-4">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                        <td class="px-6 py-4">
                                            @if ($order->status->label() == OrderStatus::PENDING->label())
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    {{ OrderStatus::PENDING->label() }}
                                                </span>
                                            @elseif ($order->status->label() == OrderStatus::PACKAGING->label())
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    {{ OrderStatus::PACKAGING->label() }}
                                                </span>
                                            @elseif ($order->status->label() == OrderStatus::SHIPPED->label())
                                                <span
                                                    class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    {{ OrderStatus::SHIPPED->label() }}
                                                </span>
                                            @elseif ($order->status->label() == OrderStatus::DELIVERED->label())
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    {{ OrderStatus::DELIVERED->label() }}
                                                </span>
                                            @elseif ($order->status->label() == OrderStatus::CANCELLED->label())
                                                <span
                                                    class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    {{ OrderStatus::CANCELLED->label() }}
                                                </span>
                                            @elseif ($order->status->label() == OrderStatus::RETURNED->label())
                                                <span
                                                    class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    {{ OrderStatus::RETURNED->label() }}
                                                </span>
                                            @elseif ($order->status->label() == OrderStatus::REFUNDED->label())
                                                <span
                                                    class="bg-cyan-100 text-cyan-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    {{ OrderStatus::REFUNDED->label() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-semibold">{{ money($order->total) }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('orders.details', $order->id) }}"
                                                    class="bg-primary text-white px-3 py-2 rounded text-xs hover:opacity-90 transition">
                                                    <i class="fa-solid fa-eye mr-1"></i> View
                                                </a>
                                                <a href="{{ route('invoice', $order->id) }}"
                                                    class="bg-theme-light text-black px-3 py-2 rounded text-xs hover:opacity-90 transition">
                                                    <i class="fa-solid fa-download mr-1"></i> Invoice
                                                </a>
                                                @if (in_array($order->status->label(), [OrderStatus::PACKAGING->label(), OrderStatus::SHIPPED->label()]))
                                                    <a href="{{ route('orders.tracking', $order->id) }}"
                                                        class="bg-orange-500 text-white px-3 py-2 rounded text-xs hover:opacity-90 transition">
                                                        <i class="fa-solid fa-truck-fast mr-1"></i> Track
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
        <!-- Orders Main Section Ended -->
        @if ($orders->isEmpty())
            <!-- No Order Section Starts -->
            <section>
                <div class="no-order-contents flex flex-col gap-5 md:gap-8 items-center text-center section-padding">
                    <div class="no-order-img w-1/4 md:w-3/12 lg:w-2/12">
                        <img src="{{ asset('assets/frontend/images/no-order.png') }}"
                            alt="A Empty Cart Image with Red Rounded Crosh Icon" class="object-contain" />
                    </div>

                    <div class="info space-y-2 md:space-y-4">
                        <h2 class="text-xl md:text-2xl font-medium text-theme-dark">
                            No Orders in this account
                        </h2>
                        <p class="md:text-lg text-jet-gray">
                            If you remember ordering before,
                            <a href="#" class="text-butterfly-blue hover:text-primary eq">switch account</a>
                            or
                            <a href="#" class="text-butterfly-blue hover:text-primary eq">Q & A</a>
                        </p>
                    </div>
                </div>
            </section>
            <!-- No Order Section Ended -->
        @endif

        <!-- Explore Interest Section Start  -->
        <section class="explore-interest section-padding">
            <div class="container">
                <!-- Section Tittle -->
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-medium text-jet-gray mb-5 md:mb-8 lg:mb-10">
                    Explore Your Interest
                </h1>

                <div class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-4 gap-5 xl:gap-8 lg:p-0 p-2 items-start">
                    @include('frontend.partials.product-card-load', ['products' => $products])
                </div>
            </div>
        </section>
        <!-- Explore Interest Section Ended  -->
    </main>

    @push('scripts')
        <script>
            if (document.getElementById("order-table") && typeof simpleDatatables !== 'undefined' && typeof simpleDatatables
                .DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#order-table", {
                    searchable: true,
                    sortable: true,
                    perPage: 10
                });
            }
        </script>
    @endpush
@endsection
