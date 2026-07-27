@extends('frontend.layouts.app')
@section('title', 'My Orders')

@section('dashboard')
    <?php
    use App\Domain\Order\Enums\OrderStatus;
    use App\Domain\Payment\Models\Payment;
    ?>

    <div class="space-y-6">
        @if ($orders && $orders->isNotEmpty())
            <div class="bg-white rounded-sm border border-[#E5E5E5]">
                <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                    <h2 class="text-base font-semibold text-[#191919]">My Orders</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#F5F5F5] text-xs font-semibold text-[#767676] uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Invoice #</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Payment</th>
                                <th class="px-5 py-3">Total</th>
                                <th class="px-5 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-[#FAFAFA] transition-colors">
                                    <td class="px-5 py-4 font-semibold text-[#191919]">#{{ $order->invoice_id }}</td>
                                    <td class="px-5 py-4 text-[#595959]">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="px-5 py-4">
                                        @php
                                            $statusClasses = [
                                                OrderStatus::PENDING->label() => 'bg-yellow-50 text-yellow-700',
                                                OrderStatus::ACCEPTED->label() => 'bg-blue-50 text-blue-700',
                                                OrderStatus::SHIPPED->label() => 'bg-purple-50 text-purple-700',
                                                OrderStatus::DELIVERED->label() => 'bg-green-50 text-green-700',
                                                OrderStatus::CANCELLED->label() => 'bg-red-50 text-red-700',
                                                OrderStatus::RETURNED->label() => 'bg-gray-100 text-gray-700',
                                                OrderStatus::REFUNDED->label() => 'bg-cyan-50 text-cyan-700',
                                            ];
                                            $class = $statusClasses[$order->status->label()] ?? 'bg-gray-50 text-gray-700';
                                        @endphp
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-sm {{ $class }}">
                                            {{ $order->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($order->payment?->status == Payment::SUCCESSFUL)
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-sm bg-green-50 text-green-700">Paid</span>
                                        @else
                                            <form action="{{ route('orders.payNow', $order->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-xs font-semibold px-3 py-1.5 bg-[#F85606] text-white rounded-sm hover:bg-[#E04D05] transition-colors">
                                                    Pay Now
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-[#191919]">{{ money($order->payable) }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('orders.details', $order->invoice_id) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#F85606] border border-[#F85606]/30 rounded-sm hover:bg-[#FFF8F5] transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                            <a href="{{ route('invoice', $order->invoice_id) }}" target="_blank"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#595959] border border-[#E5E5E5] rounded-sm hover:bg-[#F5F5F5] transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Invoice
                                            </a>
                                            @if (in_array($order->status->label(), [OrderStatus::SHIPPED->label()]))
                                                <a href="{{ route('orders.tracking', $order->invoice_id) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-700 border border-green-300 rounded-sm hover:bg-green-50 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    Track
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
        @else
            <div class="bg-white rounded-sm border border-[#E5E5E5]">
                <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                    <h2 class="text-base font-semibold text-[#191919]">My Orders</h2>
                </div>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 mb-4 bg-[#F5F5F5] rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#A0A0A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-[#191919]">No orders yet</h3>
                    <p class="text-sm text-[#767676] mt-1">Start shopping to see your orders here.</p>
                    <a href="{{ route('home') }}"
                        class="mt-4 inline-flex items-center gap-2 px-5 py-2 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                        Browse Products
                    </a>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        @if ($orders && $orders->isNotEmpty())
        <script>
            if (document.getElementById("order-table") &&
                typeof simpleDatatables !== 'undefined' &&
                typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#order-table", {
                    searchable: true,
                    sortable: true,
                    perPage: 10,
                    data: { headings: null, data: null },
                    columns: [{ select: 1, sort: false }]
                });
            }
        </script>
        @endif
    @endpush
@endsection
