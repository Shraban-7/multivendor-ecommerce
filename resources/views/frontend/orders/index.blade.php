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
                                                OrderStatus::RETURN_REQUESTED->label() => 'bg-orange-50 text-orange-700',
                                                OrderStatus::RETURN_APPROVED->label() => 'bg-amber-50 text-amber-700',
                                                OrderStatus::RETURNED->label() => 'bg-gray-100 text-gray-700',
                                                OrderStatus::REFUNDED->label() => 'bg-cyan-50 text-cyan-700',
                                            ];
                                            $class = $statusClasses[$order->status->label()] ?? 'bg-gray-50 text-gray-700';
                                        @endphp
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-sm {{ $class }}">
                                            {{ $order->status->title() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($order->payment?->status == Payment::SUCCESSFUL || $order->paid > 0)
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-sm bg-green-50 text-green-700">Paid</span>
                                            @if ($order->refund_amount > 0)
                                                <span class="text-xs font-semibold px-2.5 py-1 rounded-sm bg-cyan-50 text-cyan-700 ml-1">Refunded {{ money($order->refund_amount) }}</span>
                                            @endif
                                        @elseif ($order->status->value === OrderStatus::REFUNDED->value)
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-sm bg-cyan-50 text-cyan-700">Refunded {{ money($order->refund_amount ?? $order->payable) }}</span>
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
                                            @if ($order->status->value === OrderStatus::DELIVERED->value && !$order->returnRequest)
                                                <button type="button" data-order="{{ $order->id }}" data-invoice="{{ $order->invoice_id }}"
                                                    class="open-return-modal inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#F85606] border border-[#F85606]/30 rounded-sm hover:bg-[#FFF8F5] transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Return
                                                </button>
                                            @endif
                                            @if ($order->returnRequest)
                                                <a href="{{ route('returns.index') }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-amber-700 border border-amber-300 rounded-sm hover:bg-amber-50 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Return {{ $order->returnRequest->label() }}
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
    {{-- Return Request Modal --}}
    <div id="return-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-sm shadow-lg w-full max-w-xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="px-5 py-3.5 border-b border-[#E5E5E5] flex items-center justify-between sticky top-0 bg-white">
                <h2 class="text-base font-semibold text-[#191919]">Request Return</h2>
                <button type="button" id="close-return-modal" class="text-[#A0A0A0] hover:text-[#191919] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <p class="text-sm text-[#595959] mb-4">Order: <span id="return-invoice" class="font-semibold text-[#191919]"></span></p>

                <form action="{{ route('returns.store') }}" method="POST" class="space-y-4" id="return-form">
                    @csrf
                    <input type="hidden" name="order_id" id="return-order-id" value="">

                    {{-- Return Type --}}
                    <div>
                        <label class="text-sm font-medium text-[#191919] block mb-2">Return Type</label>
                        <div class="flex flex-wrap gap-3">
                            <label class="flex items-center gap-2 px-3 py-2 border border-[#E5E5E5] rounded-sm cursor-pointer hover:border-[#F85606] has-[:checked]:border-[#F85606] has-[:checked]:bg-[#FFF8F5] transition-colors">
                                <input type="radio" name="type" value="full" checked class="accent-[#F85606]">
                                <span class="text-sm text-[#191919]">Full Refund</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 border border-[#E5E5E5] rounded-sm cursor-pointer hover:border-[#F85606] has-[:checked]:border-[#F85606] has-[:checked]:bg-[#FFF8F5] transition-colors">
                                <input type="radio" name="type" value="partial" class="accent-[#F85606]">
                                <span class="text-sm text-[#191919]">Partial Refund</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 border border-[#E5E5E5] rounded-sm cursor-pointer hover:border-[#F85606] has-[:checked]:border-[#F85606] has-[:checked]:bg-[#FFF8F5] transition-colors">
                                <input type="radio" name="type" value="exchange" class="accent-[#F85606]">
                                <span class="text-sm text-[#191919]">Exchange</span>
                            </label>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div id="return-items-section" class="hidden space-y-2">
                        <label class="text-sm font-medium text-[#191919] block">Select Items</label>
                        <div id="return-items-list" class="space-y-2 max-h-48 overflow-y-auto border border-[#E5E5E5] rounded-sm p-3">
                        </div>
                    </div>

                    {{-- Exchange Note --}}
                    <div id="exchange-note-section" class="hidden space-y-1.5">
                        <label for="exchange_note" class="text-sm font-medium text-[#191919]">What do you want instead?</label>
                        <textarea name="exchange_note" id="exchange_note" rows="2"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors resize-none"
                            placeholder="Describe the product, size, color, or variant you want in exchange..."></textarea>
                    </div>

                    {{-- Reason --}}
                    <div class="space-y-1.5">
                        <label for="reason" class="text-sm font-medium text-[#191919]">Reason for Return</label>
                        <textarea name="reason" id="reason" rows="3" required
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors resize-none"
                            placeholder="Tell us why you want to return this order..."></textarea>
                    </div>

                    <div class="flex gap-3 justify-end pt-2">
                        <button type="button" id="cancel-return"
                            class="px-4 py-2 text-sm font-medium text-[#595959] border border-[#E5E5E5] rounded-sm hover:bg-[#F5F5F5] transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-[#F85606] rounded-sm hover:bg-[#E04D05] transition-colors">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(function () {
            const $modal = $('#return-modal');
            const $typeRadios = $('input[name="type"]');
            const $itemsSection = $('#return-items-section');
            const $itemsList = $('#return-items-list');
            const $exchangeNoteSection = $('#exchange-note-section');
            const $exchangeNote = $('#exchange_note');

            $typeRadios.on('change', function () {
                const val = $(this).val();
                if (val === 'full') {
                    $itemsSection.addClass('hidden');
                    $itemsList.find('input, select').prop('disabled', true);
                    $exchangeNoteSection.addClass('hidden');
                    $exchangeNote.prop('disabled', true);
                } else {
                    $itemsSection.removeClass('hidden');
                    $itemsList.find('input, select').prop('disabled', false);
                    if (val === 'exchange') {
                        $exchangeNoteSection.removeClass('hidden');
                        $exchangeNote.prop('disabled', false);
                    } else {
                        $exchangeNoteSection.addClass('hidden');
                        $exchangeNote.prop('disabled', true);
                    }
                }
            });

            $(document).on('click', '.open-return-modal', function () {
                const orderId = $(this).data('order');
                const invoice = $(this).data('invoice');

                $('#return-order-id').val(orderId);
                $('#return-invoice').text(invoice);
                $('#return-form')[0].reset();
                $('input[name="type"][value="full"]').prop('checked', true);
                $itemsSection.addClass('hidden');
                $itemsList.find('input, select').prop('disabled', true);
                $exchangeNoteSection.addClass('hidden');
                $exchangeNote.prop('disabled', true);

                $.get('/orders/data?order_id=' + orderId, function (res) {
                    $itemsList.empty();
                    if (res.items) {
                        res.items.forEach(function (item) {
                            $itemsList.append(`
                                <label class="flex items-center gap-3 p-2 border border-[#E5E5E5] rounded-sm hover:bg-[#FAFAFA] cursor-pointer">
                                    <input type="checkbox" name="items[${item.id}][id]" value="${item.id}" disabled class="item-checkbox accent-[#F85606]">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-[#191919] truncate">${item.product_name}</p>
                                        <p class="text-xs text-[#767676]">${item.variant_name ?? ''} × Qty: ${item.quantity} — ${item.total_formatted}</p>
                                    </div>
                                    <select name="items[${item.id}][quantity]" class="text-xs border border-[#E5E5E5] rounded-sm px-1 py-0.5">
                                        ${Array.from({length: item.quantity}, (_, i) => `<option value="${i + 1}">${i + 1}</option>`).join('')}
                                    </select>
                                </label>
                            `);
                        });
                    }
                });

                $modal.removeClass('hidden');
            });

            $('#close-return-modal, #cancel-return').on('click', function () {
                $modal.addClass('hidden');
            });
            $modal.on('click', function (e) {
                if (e.target === this) $modal.addClass('hidden');
            });
        });
    </script>
    @endpush
@endsection
