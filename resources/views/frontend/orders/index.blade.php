@extends('frontend.layouts.app')
@section('title', 'My Orders')

@section('dashboard')
    <?php
    use App\Enums\OrderStatus;
    use App\Models\Payment;
    ?>

    <main>
        @if ($orders->isNotEmpty())
            <section class="orders-section container mx-auto">
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
                                    <th scope="col" class="px-6 py-3">Payment Status</th>
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
                                        <td class="px-6 py-4">

                                            @if (!is_null($order->payment_id) && $order->payment->status == Payment::SUCCESSFUL)
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                    Paid
                                                </span>                                                
                                            @else
                                                <form action="{{ route('orders.payNow', $order->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-primary text-white px-3 py-2 rounded text-xs hover:opacity-90 transition">
                                                        Pay Now
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-semibold">{{ money($order->total) }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('orders.details', $order->invoice_id) }}"
                                                    class="bg-primary text-white px-3 py-2 rounded text-xs hover:opacity-90 transition">
                                                    <i class="fa-solid fa-eye mr-1"></i> View
                                                </a>
                                                <a href="{{ route('invoice', $order->invoice_id) }}" target="__blank"
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
        @if ($orders->isEmpty())
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
                    </div>
                </div>
            </section>
        @endif

        <section class="explore-interest section-padding">
            <div class="container">
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-medium text-jet-gray mb-5">
                    Explore Your Interest
                </h2>
                <div class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-4 gap-5 lg:p-0 p-2 items-start">
                    @include('frontend.partials.product-card-load', ['products' => $products])
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            if (document.getElementById("order-table") &&
                typeof simpleDatatables !== 'undefined' &&
                typeof simpleDatatables.DataTable !== 'undefined') {

                const dataTable = new simpleDatatables.DataTable("#order-table", {
                    searchable: true,
                    sortable: true,
                    perPage: 10,
                    data: {
                        headings: null,
                        data: null
                    },
                    columns: [{
                        select: 1,
                        sort: "desc"
                    }]
                });
            }
        </script>
    @endpush
@endsection
