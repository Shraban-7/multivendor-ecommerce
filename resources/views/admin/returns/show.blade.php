@extends('admin.layouts.app')
@section('title', 'Return #'.$return->rma_number)
@section('content')

    <div class="flex justify-between items-center mb-3">
        <div>
            <h4 class="font-bold mb-0">Return #{{ $return->rma_number }}</h4>
            <small class="text-ink-tertiary">Order #{{ $return->order->invoice_id }}</small>
        </div>
        <a href="{{ route('admin.returns.index') }}" class="btn btn-light btn-sm">← Back</a>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm flex items-start gap-3 mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm">Return Details</h5>
                </div>
                <div class="p-5">
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <tr><td class="font-semibold py-1.5" style="width: 150px;">RMA</td><td class="py-1.5">{{ $return->rma_number }}</td></tr>
                        <tr><td class="font-semibold py-1.5">Status</td>
                            <td class="py-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white" style="background-color: {{ $return->statusColor() }}">
                                    {{ $return->label() }}
                                </span>
                            </td>
                        </tr>
                        <tr><td class="font-semibold py-1.5">Type</td><td class="py-1.5">{{ $return->typeLabel() }}</td></tr>
                        <tr><td class="font-semibold py-1.5">Reason</td><td class="py-1.5">{{ $return->reason }}</td></tr>
                        @if ($return->exchange_note)
                            <tr><td class="font-semibold py-1.5">Exchange Note</td><td class="py-1.5">{{ $return->exchange_note }}</td></tr>
                        @endif
                        <tr><td class="font-semibold py-1.5">Customer</td><td class="py-1.5">{{ $return->user?->name ?? 'N/A' }} ({{ $return->user?->phone ?? 'N/A' }})</td></tr>
                        <tr><td class="font-semibold py-1.5">Seller</td><td class="py-1.5">{{ $return->order->seller?->business_name ?? 'N/A' }}</td></tr>
                        <tr><td class="font-semibold py-1.5">Requested</td><td class="py-1.5">{{ $return->created_at->format('d/m/Y h:i A') }}</td></tr>
                        @if ($return->approved_at)
                            <tr><td class="font-semibold py-1.5">Approved</td><td class="py-1.5">{{ $return->approved_at->format('d/m/Y h:i A') }}</td></tr>
                        @endif
                        @if ($return->rejected_at)
                            <tr><td class="font-semibold py-1.5">Rejected</td>
                                <td class="py-1.5">{{ $return->rejected_at->format('d/m/Y h:i A') }}<br>
                                    <small class="text-feedback-danger">Reason: {{ $return->rejection_reason }}</small>
                                </td>
                            </tr>
                        @endif
                        @if ($return->refunded_at)
                            <tr><td class="font-semibold py-1.5">Refunded</td>
                                <td class="py-1.5">{{ $return->refunded_at->format('d/m/Y h:i A') }}<br>
                                    <small class="text-ink-tertiary">{{ number_format($return->refunded_amount ?? 0, 2) }} via {{ $return->refund_method ?? 'manual' }} ({{ $return->refund_reference }})</small>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm">Items</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <thead class="bg-surface-muted">
                            <tr>
                                <th class="px-4 py-2.5">Product</th>
                                <th class="px-4 py-2.5">Qty</th>
                                <th class="px-4 py-2.5 text-right">Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($return->items as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $item->orderItem?->product?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($item->refund_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-bold bg-surface-muted">
                                <td colspan="2" class="px-4 py-2.5">Total Refund</td>
                                <td class="px-4 py-2.5 text-right">{{ number_format($return->totalRefundAmount(), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm">Activity</h5>
                </div>
                <div class="p-5">
                    <ul class="list-none mb-0 space-y-3">
                        @forelse ($return->events ?? [] as $event)
                            <li class="flex items-start gap-3 pb-3 border-b border-border last:border-0">
                                @php $eventType = \App\Domain\Order\Enums\ReturnEventType::tryFrom($event->type->value ?? $event->type); @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white shrink-0" style="background-color: {{ $eventType?->color() ?? '#6b7280' }}">
                                    {{ $eventType?->label() ?? ucfirst(str_replace('_',' ', $event->type)) }}
                                </span>
                                <div class="text-sm text-ink-tertiary">{{ $event->created_at->format('d/m/Y h:i A') }} · {{ ucfirst($event->actor_type) }}</div>
                                @if ($event->note)<div class="text-sm mt-1 text-ink">{{ $event->note }}</div>@endif
                            </li>
                        @empty
                            <li class="text-ink-tertiary text-sm">No events recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            @if ($return->is_disputed && $return->dispute)
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-l-4" style="border-left-color: #dc2626">
                    <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                        <h5 class="font-bold mb-0 text-sm text-feedback-danger">Dispute Details</h5>
                    </div>
                    <div class="p-5">
                        <p class="font-semibold">Reason: {{ $return->dispute->reason }}</p>
                        @if ($return->dispute->description)
                            <p>{{ $return->dispute->description }}</p>
                        @endif
                        <p class="text-sm text-ink-tertiary">Raised by: {{ $return->dispute->raisedBy?->name ?? 'N/A' }} | Status: {{ $return->dispute->status?->label() ?? ucfirst($return->dispute->status) }}</p>

                        @if ($return->dispute->hasSellerResponse())
                            <div class="p-4 rounded-sm bg-surface-muted text-ink text-sm mb-3">
                                <div class="font-semibold text-sm">Seller response ({{ $return->dispute->seller_responded_at?->format('d/m/Y h:i A') }}):</div>
                                <p class="mb-0 text-sm mt-1">{{ $return->dispute->seller_response }}</p>
                            </div>
                        @endif

                        @if ($return->dispute->isOpen())
                            <form method="POST" action="{{ route('admin.returns.resolveDispute', $return->dispute) }}" class="p-4 rounded-sm bg-surface-muted">
                                @csrf
                                <h6 class="font-bold mb-2">Resolve Dispute</h6>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Resolution</label>
                                    <select name="resolution" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                        <option value="">— Select —</option>
                                        <option value="approved">Approve Return (full refund, mark received & refund immediately)</option>
                                        <option value="partial_refund">Partial Refund</option>
                                        <option value="wallet_credit">Wallet Credit</option>
                                        <option value="rejected">Reject Return</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Resolution Amount (for partial refund)</label>
                                    <input type="number" name="resolution_amount" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" step="0.01" min="0">
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1">Admin Note</label>
                                    <textarea name="admin_note" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" rows="2"></textarea>
                                </div>
                                <button class="btn btn-primary btn-sm">Submit Resolution</button>
                            </form>
                        @else
                            <div class="p-3 rounded-sm bg-blue-50 border border-blue-200 text-feedback-info text-sm">
                                Resolved: {{ ucfirst($return->dispute->resolution?->value ?? $return->dispute->resolution) }}
                                @if ($return->dispute->admin_note)
                                    <br><small>{{ $return->dispute->admin_note }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm">Actions</h5>
                </div>
                <div class="p-5">
                    @if ($return->isPending())
                        <form method="POST" action="{{ route('admin.returns.approve', $return) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-success w-full">Approve Return</button>
                        </form>
                        <form method="POST" action="{{ route('admin.returns.reject', $return) }}">
                            @csrf
                            <div class="mb-2">
                                <textarea name="rejection_reason" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" rows="2" placeholder="Rejection reason..." required></textarea>
                            </div>
                            <button class="btn btn-danger w-full">Reject Return</button>
                        </form>
                    @elseif ($return->status === \App\Domain\Order\Enums\ReturnStatus::APPROVED || $return->status === \App\Domain\Order\Enums\ReturnStatus::AWAITING_SHIPMENT)
                        <form method="POST" action="{{ route('admin.returns.markReceived', $return) }}">
                            @csrf
                            <div class="mb-2"><textarea name="note" rows="2" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Inspection notes"></textarea></div>
                            <button class="btn btn-success w-full">Mark Item Received (trigger refund)</button>
                        </form>
                    @else
                        <div class="text-ink-tertiary text-sm">No actions available.</div>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm">Order Info</h5>
                </div>
                <div class="p-5">
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <tr><td class="py-1">Invoice</td><td class="py-1 font-semibold">#{{ $return->order->invoice_id }}</td></tr>
                        <tr><td class="py-1">Total</td><td class="py-1">{{ number_format($return->order->total, 2) }}</td></tr>
                        <tr><td class="py-1">Payable</td><td class="py-1">{{ number_format($return->order->payable, 2) }}</td></tr>
                        <tr><td class="py-1">Refund Amount</td><td class="py-1">{{ number_format($return->order->refund_amount ?? 0, 2) }}</td></tr>
                        <tr><td class="py-1">Status</td><td class="py-1">{{ $return->order->status?->title() ?? 'N/A' }}</td></tr>
                    </table>
                </div>
            </div>

            @if ($return->refundTransactions && $return->refundTransactions->isNotEmpty())
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                        <h5 class="font-bold mb-0 text-sm">Refund Attempts</h5>
                    </div>
                    <div class="p-5">
                        <ul class="list-none mb-0 space-y-3">
                            @foreach ($return->refundTransactions as $refund)
                                <li class="pb-3 border-b border-border last:border-0">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold">{{ number_format($refund->amount, 2) }}</span>
                                        @php
                                            $refundColor = match($refund->status) {
                                                'success' => '#059669',
                                                'failed' => '#dc2626',
                                                default => '#d97706',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white" style="background-color: {{ $refundColor }}">{{ ucfirst($refund->status) }}</span>
                                    </div>
                                    <div class="text-sm text-ink-tertiary mt-1">{{ $refund->method }} · {{ $refund->created_at->format('d/m/Y h:i A') }}</div>
                                    @if ($refund->gateway_reference)<div class="text-sm text-ink-tertiary mt-0.5">Ref: {{ $refund->gateway_reference }}</div>@endif
                                    @if ($refund->failure_reason)<div class="text-sm text-feedback-danger mt-0.5">{{ $refund->failure_reason }}</div>@endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection