@extends('seller.layouts.app')
@section('title', 'Order Details | ' . $order->invoice_id)
@section('content')

    <div class="mb-2">
        <div class="flex justify-between items-center">
            <h4 class="font-bold mb-0 text-ink">Order Details</h4>
        </div>
    </div>

    <div class="grid grid-cols-1">
        <div class="lg:col-span-1 mb-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-3" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white flex justify-between items-center">
                    <h5 class="font-semibold mb-0">Summary</h5>
                    <div class="flex">
                        <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors border btn-sm inline-flex items-center gap-1 me-1"
                            onclick="printReceipt('{{ route('invoice', $order->invoice_id) }}')">
                            <i data-feather="download" class="icon-xs"></i>Invoice
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <ul class="flex flex-col ">
                        <li class="flex items-center px-0 py-2 border-b border-border flex justify-between px-0">
                            <span>Invoice ID:</span>
                            <span class="font-medium">{{ $order->invoice_id }}</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border flex justify-between px-0">
                            <span>Date:</span>
                            <div class="text-right">
                                <span class="block">{{ $order->created_at->format('d/m/Y h:i A') }}</span>

                                @if ($order->created_at != $order->updated_at)
                                    <span class="text-sm text-gray-500">
                                        Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}
                                    </span>
                                @endif
                            </div>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border flex items-center justify-between px-0">
                            <span>Status:</span>
                            <div class="flex items-center gap-2">
                                @php $label = $order->status->label(); @endphp
                                @if ($label === 'pending')
                                    <span class="badge badge-soft-warning">Pending</span>
                                @elseif ($label === 'accepted')
                                    <span class="badge badge-soft-secondary">Accepted</span>
                                @elseif ($label === 'shipped')
                                    <span class="badge badge-soft-primary">Shipped</span>
                                @elseif ($label === 'cancelled')
                                    <span class="badge badge-soft-danger">Cancelled</span>
                                @elseif ($label === 'delivered')
                                    <span class="badge badge-soft-success">Delivered</span>
                                @elseif ($label === 'returned')
                                    <span class="badge badge-soft-secondary">Returned</span>
                                @elseif ($label === 'refunded')
                                    <span class="badge badge-soft-info">Refunded</span>
                                @elseif ($label === 'completed')
                                    <span class="badge badge-soft-success">Completed</span>
                                @elseif ($label === 'return_requested')
                                    <span class="badge badge-soft-warning">Return Requested</span>
                                @elseif ($label === 'return_approved')
                                    <span class="badge badge-soft-info">Return Approved</span>
                                @else
                                    <span class="badge badge-soft-secondary">{{ $order->status->title() }}</span>
                                @endif

                                <button class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors border inline-flex items-center gap-1"
                                    data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                    <i class="bi bi-arrow-repeat text-ink-secondary"></i>
                                    Update
                                </button>
                            </div>
                        </li>

                        <li class="flex items-center px-0 py-2 border-b border-border flex justify-between px-0">
                            <span>Payment Method:</span>
                            <span class="font-medium">{{ $order->payment_method_name ?? ($order->payment?->gateway ?? 'N/A') }}</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border flex items-center justify-between px-0">
                            <span>Payment Status:</span>
                            @if ($order->due == $order->payable)
                                <span class="badge badge-soft-danger">Unpaid</span>
                            @elseif ($order->due > 0)
                                <span class="badge badge-soft-warning">Partially Paid</span>
                            @else
                                <span class="badge badge-soft-success">Paid</span>
                            @endif
                        </li>

                    </ul>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-3" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white">
                    <h5 class="font-semibold mb-0">Customer Information</h5>
                </div>
                <div class="p-5">

                    @if ($order->user || $order->customer)
                        @php
                            $createdAt = null;

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
                        <h6 class="font-bold">{{ $userName }}</h6>
                        <p class="mb-1"><i data-feather="phone" class="icon-xs me-1"></i>
                            {{ $userPhone }}
                        </p>
                        <p class="mb-0">
                            <i data-feather="user" class="icon-xs me-1"></i>
                            Customer since {{ $createdAt }}
                        </p>
                    @endif

                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-3" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white flex justify-between items-center">
                    <h5 class="font-semibold mb-0">Shipping Details</h5>
                    <div class="flex gap-2">
                        <a href="{{ route('seller.shipping.shipments.create', $order) }}"
                           class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs border border-brand text-brand hover:bg-brand hover:text-white transition-colors inline-flex items-center gap-1">
                            <i data-feather="package" style="width: 14px; height: 14px;"></i> Create Shipment
                        </a>
                        <a href="{{ route('seller.orders.tracking', $order) }}"
                           class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium rounded-xs border border-border text-ink-secondary hover:bg-surface-muted transition-colors inline-flex items-center gap-1">
                            <i data-feather="truck" style="width: 14px; height: 14px;"></i> Add Tracking
                        </a>
                    </div>
                </div>
                <div class="p-5">
                    <address class="mb-0">
                        <h6 class="font-bold">{{ $order->billing_address->customer_name }}</h6>
                        <p class="mb-1"><i data-feather="phone" class="icon-xs me-1"></i>
                            {{ $order->billing_address->customer_phone }}
                        </p>
                        <p class="mb-1"><i data-feather="home" class="icon-xs me-1"></i>
                            {{ $order->billing_address->address }}
                        </p>
                    </address>

                    @if ($order->trackings->count() > 0)
                        <hr>
                        <h6 class="font-semibold mb-2">Tracking Info</h6>
                        @foreach ($order->trackings as $tracking)
                            <div class="flex items-center gap-2 mb-1">
                                <i data-feather="package" style="width: 14px; height: 14px;" class="text-brand"></i>
                                <span class="small">
                                    <strong>{{ $tracking->carrier->name ?? $tracking->courier_name ?? 'Carrier' }}:</strong>
                                    <code>{{ $tracking->tracking_number }}</code>
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white">
                    <h5 class="font-semibold mb-0">Order Items</h5>
                </div>
                <div class="p-5 p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse mb-0">
                            <thead class="bg-white">
                                <tr>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Product</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-center">Price</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-center">Discount</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                @php
                                                    $imageUrl = null;

                                                    if ($item->variant && $item->variant->image) {
                                                        $imageUrl = storage_url($item->variant->image);
                                                    } elseif (isset($item->product->thumbnail)) {
                                                        $imageUrl = storage_url($item->product->thumbnail);
                                                    }
                                                @endphp

                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}"
                                                        class="rounded me-3" width="60" height="60">
                                                @else
                                                    <div class="bg-white rounded me-3" style="width: 50px; height: 50px;">
                                                    </div>
                                                @endif

                                                <div>
                                                    <div class="flex items-center mb-2">
                                                        <h6 class="mb-0 me-2">{{ $item->product->name }}</h6>
                                                        <span class="badge badge-soft-primary" style="border-radius: 50px;">x
                                                            {{ $item->quantity }}</span>
                                                    </div>

                                                    <div class="text-ink-tertiary text-sm mt-1">{{ $item->variant?->label ?? $item->variant_name }}</div>

                                                    @if (isset($item->variant))
                                                        <small class="text-ink-tertiary block">SKU:
                                                            {{ $item->variant->sku }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ money($item->price) }}</td>
                                        <td class="text-center">{{ money($item->discount) }}</td>
                                        <td class="text-right">{{ money($item->total) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-ink-tertiary py-4">No items in this order.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-white">
                                <tr>
                                    <th colspan="3" class="text-right">Subtotal:</th>
                                    <td class="text-right"><span class="font-bold">{{ money($order->sub_total) }}</span></td>
                                </tr>
                                @if (isset($order->discount) && $order->discount > 0)
                                    <tr>
                                        <th colspan="3" class="text-right">Discount:</th>
                                        <td class="text-right">-{{ money($order->discount) }}</td>
                                    </tr>
                                @endif
                                @if ($order->shipping_fee)
                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td class="text-right">{{ money($order->shipping_fee) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th colspan="3" class="text-right">Total:</th>
                                    <td class="text-right font-bold">{{ money($order->total) }}</td>
                                </tr>
                                @if ($order->due > 0)
                                    <tr>
                                        <th colspan="3" class="text-right">Paid:</th>
                                        <td class="text-right">{{ money($order->paid) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Due:</th>
                                        <td class="text-right text-feedback-danger font-bold">{{ money($order->due) }}</td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if ($order->review)
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mt-4" style="border-radius: 12px;">
                    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white">
                        <h5 class="font-semibold mb-0">Order Review</h5>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between">
                            <span class="font-medium">Rating:</span>
                            <span class="text-feedback-warning">
                                @for ($i = 1; $i <= $order->review->rating; $i++)
                                    <i data-feather="star" class="text-feedback-warning"></i>
                                @endfor
                                @for ($i = $order->review->rating + 1; $i <= 5; $i++)
                                    <i data-feather="star" class="text-ink-tertiary"></i>
                                @endfor
                            </span>
                        </div>
                        <div class="mt-3">
                            <p class="mb-0 mt-2"><span
                                    class="font-medium me-2">Review:</span>{{ $order->review->description }}</p>
                        </div>
                        <div class="text-ink-tertiary mt-3">
                            <span>Reviewed on:
                                {{ \Carbon\Carbon::parse($order->review->created_at)->format('d-m-Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-ink-tertiary mb-0">No review provided.</p>
            @endif
        </div>

        <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeStatusModalLabel">Update Order Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Change Order Status</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border bg-surface-muted">
                                        {{ ucfirst($order->status->title()) }}
                                    </span>
                                    <select name="new_status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
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
                            <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors inline-flex items-center gap-1">Update</button>
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
