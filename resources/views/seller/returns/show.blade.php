@extends('seller.layouts.app')
@section('title', 'Return #'.$return->rma_number)
@section('content')

    <div class="flex justify-between items-center mb-3">
        <div>
            <h4 class="font-bold mb-0">Return #{{ $return->rma_number }}</h4>
            <small class="text-ink-tertiary">Order #{{ $return->order->invoice_id }}</small>
        </div>
        <a href="{{ route('seller.returns.index') }}" class="btn btn-light btn-sm">← Back</a>
    </div>

    @if (session('success'))
        <div class="px-4 py-2 rounded-sm bg-feedback-success/10 border border-feedback-success/20 text-feedback-success text-sm alert-dismissible fade show">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 border border-feedback-danger/20 text-feedback-danger text-sm alert-dismissible fade show">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Return Details</h5>
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <tr><td class="font-semibold" style="width: 160px;">RMA</td><td>{{ $return->rma_number }}</td></tr>
                        <tr><td class="font-semibold">Status</td><td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $return->statusColor() }}">{{ $return->label() }}</span></td></tr>
                        <tr><td class="font-semibold">Type</td><td>{{ $return->typeLabel() }}</td></tr>
                        <tr><td class="font-semibold">Reason</td><td>{{ $return->reason }}</td></tr>
                        @if ($return->exchange_note)
                            <tr><td class="font-semibold">Exchange Note</td><td>{{ $return->exchange_note }}</td></tr>
                        @endif
                        <tr><td class="font-semibold">Customer</td><td>{{ $return->user?->name ?? 'N/A' }} ({{ $return->user?->phone ?? 'N/A' }})</td></tr>
                        <tr><td class="font-semibold">Requested</td><td>{{ $return->created_at->format('d/m/Y h:i A') }}</td></tr>
                        @if ($return->approved_at)
                            <tr><td class="font-semibold">Approved</td><td>{{ $return->approved_at->format('d/m/Y h:i A') }}</td></tr>
                        @endif
                        @if ($return->rejected_at)
                            <tr><td class="font-semibold">Rejected</td>
                                <td>{{ $return->rejected_at->format('d/m/Y h:i A') }}<br>
                                    <small class="text-feedback-danger">{{ $return->rejection_reason }}</small>
                                </td>
                            </tr>
                        @endif
                        @if ($return->refunded_at)
                            <tr><td class="font-semibold">Refunded</td>
                                <td>{{ $return->refunded_at->format('d/m/Y h:i A') }}<br>
                                    <small class="text-ink-tertiary">{{ number_format($return->refunded_amount ?? 0, 2) }} via {{ $return->refund_method ?? 'manual' }} ({{ $return->refund_reference }})</small>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Items</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse">
                            <thead class="bg-surface-muted">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th class="text-right">Refund Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($return->items as $item)
                                    <tr>
                                        <td>{{ $item->orderItem?->product?->name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-right">{{ number_format($item->refund_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-bold">
                                    <td colspan="2">Total Refund</td>
                                    <td class="text-right">{{ number_format($return->totalRefundAmount(), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Activity Timeline</h5>
                    <ul class="list-none pl-0 mb-0">
                        @forelse ($return->events as $event)
                            <li class="flex items-start mb-2 border-b pb-2">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $event->type->color() ?? 'secondary' }}">{{ \App\Domain\Order\Enums\ReturnEventType::from($event->type->value)->label() }}</span>
                                    <div class="text-sm text-ink-tertiary">{{ $event->created_at->format('d/m/Y h:i A') }} · {{ ucfirst($event->actor_type) }}</div>
                                    @if ($event->note)<div class="text-sm">{{ $event->note }}</div>@endif
                                </div>
                            </li>
                        @empty
                            <li class="text-ink-tertiary text-sm">No events recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            @if ($return->is_disputed && $return->dispute)
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4 border-l-4 border-feedback-danger">
                    <div class="p-5">
                        <h5 class="font-bold text-feedback-danger mb-3">Dispute</h5>
                        <p class="font-semibold">Reason: {{ $return->dispute->reason }}</p>
                        @if ($return->dispute->description)<p>{{ $return->dispute->description }}</p>@endif
                        <p class="text-sm text-ink-tertiary">Raised by: {{ $return->dispute->raisedBy?->name ?? 'N/A' }} · Status: {{ $return->dispute->status->label() ?? ucfirst($return->dispute->status) }}</p>

                        @if ($return->dispute->hasSellerResponse())
                            <div class="px-4 py-2 rounded-sm bg-surface-muted border border-border text-ink text-sm mb-2">
                                <div class="font-semibold text-sm">Your response ({{ $return->dispute->seller_responded_at?->format('d/m/Y h:i A') }}):</div>
                                <p class="mb-0 text-sm">{{ $return->dispute->seller_response }}</p>
                            </div>
                        @endif

                        @if ($return->dispute->isOpen())
                            <form method="POST" action="{{ route('seller.returns.disputeRespond', $return->dispute) }}" class="mt-2">
                                @csrf
                                <label class="block text-sm font-medium text-ink-secondary mb-1">Submit / update your response</label>
                                <textarea name="response" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" required>{{ old('response', $return->dispute->seller_response) }}</textarea>
                                <button class="btn btn-primary btn-sm mt-2">Send Response</button>
                            </form>
                        @endif

                        @if ($return->dispute->isResolved())
                            <div class="px-4 py-2 rounded-sm bg-feedback-info/10 border border-feedback-info/20 text-feedback-info text-sm mb-0">
                                Resolved: {{ ucfirst($return->dispute->resolution?->value ?? $return->dispute->resolution) }}
                                @if ($return->dispute->admin_note)<br><small>{{ $return->dispute->admin_note }}</small>@endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Workflow</h5>
                    @if ($return->isPending())
                        <form method="POST" action="{{ route('seller.returns.approve', $return) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-success w-full">Approve Return</button>
                        </form>
                        <form method="POST" action="{{ route('seller.returns.reject', $return) }}">
                            @csrf
                            <div class="mb-2"><textarea name="rejection_reason" rows="2" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Reason for rejection" required></textarea></div>
                            <button class="btn btn-danger w-full">Reject Return</button>
                        </form>
                    @elseif ($return->status === \App\Domain\Order\Enums\ReturnStatus::APPROVED)
                        <form method="POST" action="{{ route('seller.returns.recordShipment', $return) }}" class="mb-2">
                            @csrf
                            <div class="mb-2">
                                <label class="text-sm text-ink-tertiary">Carrier</label>
                                <input type="text" name="carrier" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. Pathao, Steadfast" value="Customer-arranged">
                            </div>
                            <div class="mb-2">
                                <label class="text-sm text-ink-tertiary">Tracking #</label>
                                <input type="text" name="tracking_number" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="(optional)">
                            </div>
                            <div class="mb-2">
                                <label class="text-sm text-ink-tertiary">Notes</label>
                                <textarea name="notes" rows="2" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></textarea>
                            </div>
                            <button class="btn btn-primary btn-sm w-full">Mark Awaiting Shipment</button>
                        </form>
                    @elseif ($return->status === \App\Domain\Order\Enums\ReturnStatus::AWAITING_SHIPMENT)
                        <form method="POST" action="{{ route('seller.returns.markReceived', $return) }}" class="mb-2">
                            @csrf
                            <div class="mb-2"><textarea name="note" rows="2" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Inspection notes"></textarea></div>
                            <button class="btn btn-success w-full">Confirm Item Received</button>
                        </form>
                        @if ($return->status === \App\Domain\Order\Enums\ReturnStatus::AWAITING_SHIPMENT)
                            <small class="text-ink-tertiary block mt-2">Stock will be restored and refund initiated automatically.</small>
                        @endif
                    @else
                        <div class="text-ink-tertiary text-sm">No further action required from your side.</div>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Order Summary</h5>
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <tr><td>Invoice</td><td class="font-semibold">#{{ $return->order->invoice_id }}</td></tr>
                        <tr><td>Subtotal</td><td>{{ number_format($return->order->sub_total ?? $return->order->total, 2) }}</td></tr>
                        <tr><td>Payable</td><td>{{ number_format($return->order->payable, 2) }}</td></tr>
                        <tr><td>Seller Earning</td><td>{{ number_format($return->order->seller_earnings, 2) }}</td></tr>
                        <tr><td>Paid</td><td>{{ number_format($return->order->paid, 2) }}</td></tr>
                        <tr><td>Due</td><td>{{ number_format($return->order->due, 2) }}</td></tr>
                        <tr><td>Refund Amount</td><td>{{ number_format($return->order->refund_amount ?? 0, 2) }}</td></tr>
                        <tr><td>Status</td><td>{{ $return->order->status?->title() ?? 'N/A' }}</td></tr>
                    </table>
                </div>
            </div>

            @if ($return->refundTransactions->isNotEmpty())
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0">
                    <div class="p-5">
                        <h5 class="font-bold mb-3">Refund History</h5>
                        <ul class="list-none pl-0 mb-0">
                            @foreach ($return->refundTransactions as $refund)
                                <li class="border-b pb-2 mb-2">
                                    <div class="flex justify-between">
                                        <span class="font-semibold">{{ number_format($refund->amount, 2) }}</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $refund->status === 'success' ? 'success' : ($refund->status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($refund->status) }}</span>
                                    </div>
                                    <div class="text-sm text-ink-tertiary">Method: {{ $refund->method }} · {{ $refund->created_at->format('d/m/Y h:i A') }}</div>
                                    @if ($refund->gateway_reference)<div class="text-sm">Ref: {{ $refund->gateway_reference }}</div>@endif
                                    @if ($refund->failure_reason)<div class="text-sm text-feedback-danger">{{ $refund->failure_reason }}</div>@endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
