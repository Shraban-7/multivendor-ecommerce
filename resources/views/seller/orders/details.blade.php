@extends('seller.layouts.app')
@section('title', 'Order Details | ' . $order->invoice_id)
@section('content')

    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Order Details</h1>
            <p class="text-sm text-ink-secondary mt-1">#{{ $order->invoice_id }}</p>
        </div>
        <button type="button" class="btn btn-light btn-sm"
            onclick="printReceipt('{{ route('invoice', $order->invoice_id) }}')">
            <i data-lucide="download" class="icon-xs"></i> Invoice
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-border bg-surface-muted">
                    <h5 class="text-sm font-semibold text-ink mb-0">Summary</h5>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-ink-tertiary">Invoice ID:</span>
                        <span class="font-medium">{{ $order->invoice_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-ink-tertiary">Date:</span>
                        <div class="text-right">
                            <span class="block">{{ $order->created_at->format('d/m/Y h:i A') }}</span>
                            @if ($order->created_at != $order->updated_at)
                                <span class="text-xs text-ink-tertiary">Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-ink-tertiary">Status:</span>
                        <div class="flex items-center gap-2">
                            @php
                                $label = $order->status->label();
                                $colors = [
                                    'pending' => 'text-white bg-blue-500',
                                    'accepted' => 'text-ink-tertiary bg-surface-muted',
                                    'shipped' => 'text-white bg-indigo-500',
                                    'delivered' => 'text-white bg-green-500',
                                    'completed' => 'text-white bg-green-500',
                                    'cancelled' => 'text-white bg-red-500',
                                    'return_requested' => 'text-ink bg-yellow-400',
                                    'return_approved' => 'text-white bg-blue-500',
                                    'returned' => 'text-ink-tertiary bg-surface-muted',
                                    'refunded' => 'text-white bg-indigo-500',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $colors[$label] ?? 'text-ink-tertiary bg-surface-muted' }}">{{ $order->status->title() }}</span>
                            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                <i data-lucide="refresh-cw" class="icon-xs"></i> Update
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-ink-tertiary">Payment Method:</span>
                        <span class="font-medium">{{ $order->payment_method_name ?? ($order->payment?->gateway ?? 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-ink-tertiary">Payment Status:</span>
                        @if ($order->due == $order->payable)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-red-500 rounded-full">Unpaid</span>
                        @elseif ($order->due > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink bg-yellow-400 rounded-full">Partially Paid</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">Paid</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-border bg-surface-muted">
                    <h5 class="text-sm font-semibold text-ink mb-0">Customer Information</h5>
                </div>
                <div class="p-4">
                    @if ($order->user || $order->customer)
                        @php
                            if ($order->user) {
                                $userName = $order->user->name;
                                $userPhone = $order->user->phone;
                                $createdAt = \Carbon\Carbon::parse($order->user->created_at)->format('M Y');
                            } elseif ($order->customer) {
                                $createdAt = \Carbon\Carbon::parse($order->customer->created_at)->format('M Y');
                                $userName = $order->customer->name;
                                $userPhone = $order->customer->phone;
                            }
                        @endphp
                        <h6 class="font-bold text-ink">{{ $userName }}</h6>
                        <p class="text-sm text-ink-secondary mb-1"><i data-lucide="phone" class="icon-xs me-1"></i>{{ $userPhone }}</p>
                        <p class="text-sm text-ink-secondary mb-0"><i data-lucide="user" class="icon-xs me-1"></i>Customer since {{ $createdAt }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
                    <h5 class="text-sm font-semibold text-ink mb-0">Shipping Details</h5>
                    <div class="flex gap-2">
                        <a href="{{ route('seller.shipping.shipments.create', $order) }}" class="btn btn-outline-primary btn-sm">
                            <i data-lucide="package" style="width: 14px; height: 14px;"></i> Create Shipment
                        </a>
                        <a href="{{ route('seller.orders.tracking', $order) }}" class="btn btn-light btn-sm">
                            <i data-lucide="truck" style="width: 14px; height: 14px;"></i> Add Tracking
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    <address class="mb-0 not-italic">
                        <h6 class="font-bold text-ink">{{ $order->billing_address->customer_name }}</h6>
                        <p class="text-sm text-ink-secondary mb-1"><i data-lucide="phone" class="icon-xs me-1"></i>{{ $order->billing_address->customer_phone }}</p>
                        <p class="text-sm text-ink-secondary mb-1"><i data-lucide="home" class="icon-xs me-1"></i>{{ $order->billing_address->address }}</p>
                    </address>

                    @if ($order->trackings->count() > 0)
                        <hr class="my-3">
                        <h6 class="text-sm font-semibold text-ink mb-2">Tracking Info</h6>
                        @foreach ($order->trackings as $tracking)
                            <div class="flex items-center gap-2 mb-1 text-sm">
                                <i data-lucide="package" style="width: 14px; height: 14px;" class="text-brand"></i>
                                <span><strong>{{ $tracking->carrier->name ?? $tracking->courier_name ?? 'Carrier' }}:</strong> <code>{{ $tracking->tracking_number }}</code></span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-border bg-surface-muted">
                    <h5 class="text-sm font-semibold text-ink mb-0">Order Items</h5>
                </div>
                <div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col" class="text-center">Price</th>
                                    <th scope="col" class="text-center">Discount</th>
                                    <th scope="col" class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $imageUrl = null;
                                                    if ($item->variant && $item->variant->image) {
                                                        $imageUrl = storage_url($item->variant->image);
                                                    } elseif (isset($item->product->thumbnail)) {
                                                        $imageUrl = storage_url($item->product->thumbnail);
                                                    }
                                                @endphp
                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}" class="rounded border" width="50" height="50" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-surface-muted rounded" style="width: 50px; height: 50px;"></div>
                                                @endif
                                                <div>
                                                    <div class="font-medium text-ink">{{ $item->product->name }}</div>
                                                    <div class="text-xs text-ink-tertiary mt-1">
                                                        Qty: {{ $item->quantity }}
                                                        @if ($item->variant?->label ?? $item->variant_name)
                                                            | {{ $item->variant?->label ?? $item->variant_name }}
                                                        @endif
                                                        @if (isset($item->variant))
                                                            | SKU: {{ $item->variant->sku }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ money($item->price) }}</td>
                                        <td class="text-center">{{ money($item->discount) }}</td>
                                        <td class="text-right">{{ money($item->total) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-ink-tertiary">No items in this order.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-border p-4 space-y-1">
                        <div class="flex justify-between text-sm">
                            <span class="text-ink-tertiary">Subtotal:</span>
                            <span class="font-semibold">{{ money($order->sub_total) }}</span>
                        </div>
                        @if (isset($order->discount) && $order->discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-tertiary">Discount:</span>
                                <span class="text-red-600">-{{ money($order->discount) }}</span>
                            </div>
                        @endif
                        @if ($order->shipping_fee)
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-tertiary">Shipping:</span>
                                <span>{{ money($order->shipping_fee) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-sm font-bold border-t border-border pt-1">
                            <span>Total:</span>
                            <span>{{ money($order->total) }}</span>
                        </div>
                        @if ($order->due > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-tertiary">Paid:</span>
                                <span>{{ money($order->paid) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-tertiary">Due:</span>
                                <span class="text-red-600 font-bold">{{ money($order->due) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($order->review)
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-border bg-surface-muted">
                        <h5 class="text-sm font-semibold text-ink mb-0">Order Review</h5>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium">Rating:</span>
                            <span class="text-amber-500">
                                @for ($i = 1; $i <= $order->review->rating; $i++)
                                    <i data-lucide="star" class="text-amber-500" style="width: 16px; height: 16px;"></i>
                                @endfor
                                @for ($i = $order->review->rating + 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="text-ink-tertiary" style="width: 16px; height: 16px;"></i>
                                @endfor
                            </span>
                        </div>
                        <p class="text-sm text-ink mb-2">{{ $order->review->description }}</p>
                        <p class="text-xs text-ink-tertiary mb-0">Reviewed on: {{ \Carbon\Carbon::parse($order->review->created_at)->format('d-m-Y h:i A') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeStatusModalLabel">Update Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Change Order Status</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border rounded-l-xs">{{ ucfirst($order->status->title()) }}</span>
                                <select name="new_status" class="flex-1 px-3 py-2 text-sm text-ink bg-white border border-border rounded-r-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                    <option value="">-- Select Status --</option>
                                    @foreach (\App\Domain\Order\Enums\OrderStatus::cases() as $status)
                                        <option value="{{ $status->value }}"
                                            {{ $order->status->value === $status->value ? 'selected' : '' }}>
                                            {{ ucfirst($status->title()) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Remarks (optional)</label>
                            <textarea name="remarks" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="changed_by" value="{{ auth()->user()->role ?? 'admin' }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function printReceipt(url) {
            let printWindow = window.open(url, '_blank', 'width=800,height=600');
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.onafterprint = function() {
                    printWindow.close();
                };
            };
        }
    </script>
@endpush