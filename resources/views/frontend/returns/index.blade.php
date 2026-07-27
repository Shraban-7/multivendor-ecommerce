@extends('frontend.layouts.app')
@section('title', 'My Returns')

@section('dashboard')
    <div class="space-y-6">
        @if ($returns && $returns->isNotEmpty())
            <div class="bg-white rounded-sm border border-[#E5E5E5]">
                <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                    <h2 class="text-base font-semibold text-[#191919]">My Returns</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#F5F5F5] text-xs font-semibold text-[#767676] uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Order</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Items</th>
                                <th class="px-5 py-3">Exchange Note</th>
                                <th class="px-5 py-3">Reason</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            @foreach ($returns as $return)
                                <tr class="hover:bg-[#FAFAFA] transition-colors">
                                    <td class="px-5 py-4 font-semibold text-[#191919]">#{{ $return->order->invoice_id }}</td>
                                    <td class="px-5 py-4 text-[#595959]">{{ $return->created_at->format('M d, Y') }}</td>
                                    <td class="px-5 py-4">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-sm
                                            @switch($return->type)
                                                @case('full') bg-purple-50 text-purple-700 @break
                                                @case('partial') bg-blue-50 text-blue-700 @break
                                                @case('exchange') bg-teal-50 text-teal-700 @break
                                                @default bg-gray-50 text-gray-700
                                            @endswitch">
                                            {{ $return->typeLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-[#595959]">
                                        @foreach ($return->items as $ri)
                                            <div class="text-xs">{{ $ri->orderItem->product_name }} × {{ $ri->quantity }}</div>
                                        @endforeach
                                    </td>
                                    <td class="px-5 py-4 text-[#595959] max-w-[200px] truncate">{{ $return->exchange_note ?? '-' }}</td>
                                    <td class="px-5 py-4 text-[#595959] max-w-[200px] truncate">{{ $return->reason }}</td>
                                    <td class="px-5 py-4">
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-sm
                                            @switch($return->status)
                                                @case('pending') bg-yellow-50 text-yellow-700 @break
                                                @case('approved') bg-green-50 text-green-700 @break
                                                @case('rejected') bg-red-50 text-red-700 @break
                                                @case('refunded') bg-blue-50 text-blue-700 @break
                                                @default bg-gray-50 text-gray-700
                                            @endswitch">
                                            {{ $return->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($returns->hasPages())
                    <div class="px-5 py-3 border-t border-[#E5E5E5]">
                        {{ $returns->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-sm border border-[#E5E5E5]">
                <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                    <h2 class="text-base font-semibold text-[#191919]">My Returns</h2>
                </div>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 mb-4 bg-[#F5F5F5] rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#A0A0A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-[#191919]">No return requests</h3>
                    <p class="text-sm text-[#767676] mt-1">You haven't requested any returns yet.</p>
                    <a href="{{ route('orders.index') }}"
                        class="mt-4 inline-flex items-center gap-2 px-5 py-2 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                        View Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
