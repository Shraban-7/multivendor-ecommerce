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
        <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm flex items-start gap-3 alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Return Details</h5>
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <tr><td class="font-semibold" style="width: 150px;">RMA</td><td>{{ $return->rma_number }}</td></tr>
                        <tr><td class="font-semibold">Status</td>
                            <td><span class="badge bg-{{ $return->statusColor() }}">{{ $return->label() }}</span></td>
                        </tr>
                        <tr><td class="font-semibold">Type</td><td>{{ $return->typeLabel() }}</td></tr>
                        <tr><td class="font-semibold">Reason</td><td>{{ $return->reason }}</td></tr>
                        @if ($return->exchange_note)
                            <tr><td class="font-semibold">Exchange Note</td><td>{{ $return->exchange_note }}</td></tr>
                        @endif
                        <tr><td class="font-semibold">Customer</td><td>{{ $return->user?->name ?? 'N/A' }} ({{ $return->user?->phone ?? 'N/A' }})</td></tr>
                        <tr><td class="font-semibold">Seller</td><td>{{ $return->order->seller?->business_name ?? 'N/A' }}</td></tr>
                        <tr><td class="font-semibold">Requested</td><td>{{ $return->created_at->format('d/m/Y h:i A') }}</td></tr>
                        @if ($return->approved_at)
                            <tr><td class="font-semibold">Approved</td><td>{{ $return->approved_at->format('d/m/Y h:i A') }}</td></tr>
                        @endif
                        @if ($return->rejected_at)
                            <tr><td class="font-semibold">Rejected</td>
                                <td>{{ $return->rejected_at->format('d/m/Y h:i A') }}<br>
                                    <small class="text-feedback-danger">Reason: {{ $return->rejection_reason }}</small>
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

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4">
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

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Activity</h5>
                    <ul class="list-none mb-0">
                        @forelse ($return->events ?? [] as $event)
                            <li class="border-b pb-2 mb-2">
                                <span class="badge bg-surface-muted">{{ \App\Domain\Order\Enums\ReturnEventType::tryFrom($event->type->value ?? $event->type)?->label() ?? ucfirst(str_replace('_',' ', $event->type)) }}</span>
                                <span class="text-sm text-ink-tertiary ms-2">{{ $event->created_at->format('d/m/Y h:i A') }} · {{ ucfirst($event->actor_type) }}</span>
                                @if ($event->note)<div class="small">{{ $event->note }}</div>@endif
                            </li>
                        @empty
                            <li class="text-ink-tertiary text-sm">No events recorded yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            @if ($return->is_disputed && $return->dispute)
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4 border-l border-4 border-danger">
                    <div class="p-5">
                        <h5 class="font-bold text-feedback-danger mb-3">Dispute Details</h5>
                        <p class="font-semibold">Reason: {{ $return->dispute->reason }}</p>
                        @if ($return->dispute->description)
                            <p>{{ $return->dispute->description }}</p>
                        @endif
                        <p class="text-sm text-ink-tertiary">Raised by: {{ $return->dispute->raisedBy?->name ?? 'N/A' }} | Status: {{ $return->dispute->status?->label() ?? ucfirst($return->dispute->status) }}</p>

                        @if ($return->dispute->hasSellerResponse())
                            <div class="p-4 rounded-sm bg-surface-muted border border-border text-ink text-sm flex items-start gap-3 border">
                                <div class="font-semibold text-sm">Seller response ({{ $return->dispute->seller_responded_at?->format('d/m/Y h:i A') }}):</div>
                                <p class="mb-0 text-sm">{{ $return->dispute->seller_response }}</p>
                            </div>
                        @endif

                        @if ($return->dispute->isOpen())
                            <form method="POST" action="{{ route('admin.returns.resolveDispute', $return->dispute) }}" class="mt-3 p-3 bg-surface-muted rounded">
                                @csrf
                                <h6 class="font-bold">Resolve Dispute</h6>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm">Resolution</label>
                                    <select name="resolution" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                        <option value="">— Select —</option>
                                        <option value="approved">Approve Return (full refund, mark received & refund immediately)</option>
                                        <option value="partial_refund">Partial Refund</option>
                                        <option value="wallet_credit">Wallet Credit</option>
                                        <option value="rejected">Reject Return</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm">Resolution Amount (for partial refund)</label>
                                    <input type="number" name="resolution_amount" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" step="0.01" min="0">
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm">Admin Note</label>
                                    <textarea name="admin_note" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" rows="2"></textarea>
                                </div>
                                <button class="btn btn-primary btn-sm">Submit Resolution</button>
                            </form>
                        @else
                            <div class="p-4 rounded-sm bg-blue-50 border border-blue-200 text-feedback-info text-sm flex items-start gap-3 py-2 mb-0">
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

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Actions</h5>
                    @if ($return->isPending())
                        <form method="POST" action="{{ route('admin.returns.approve', $return) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-success">Approve Return</button>
                        </form>
                        <form method="POST" action="{{ route('admin.returns.reject', $return) }}">
                            @csrf
                            <div class="mb-2">
                                <textarea name="rejection_reason" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" rows="2" placeholder="Rejection reason..." required></textarea>
                            </div>
                            <button class="btn btn-danger">Reject Return</button>
                        </form>
                    @elseif ($return->status === \App\Domain\Order\Enums\ReturnStatus::APPROVED || $return->status === \App\Domain\Order\Enums\ReturnStatus::AWAITING_SHIPMENT)
                        <form method="POST" action="{{ route('admin.returns.markReceived', $return) }}" class="mb-2">
                            @csrf
                            <div class="mb-2"><textarea name="note" rows="2" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Inspection notes"></textarea></div>
                            <button class="btn btn-success">Mark Item Received (trigger refund)</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Order Info</h5>
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <tr><td>Invoice</td><td class="font-semibold">#{{ $return->order->invoice_id }}</td></tr>
                        <tr><td>Total</td><td>{{ number_format($return->order->total, 2) }}</td></tr>
                        <tr><td>Payable</td><td>{{ number_format($return->order->payable, 2) }}</td></tr>
                        <tr><td>Refund Amount</td><td>{{ number_format($return->order->refund_amount ?? 0, 2) }}</td></tr>
                        <tr><td>Status</td><td>{{ $return->order->status?->title() ?? 'N/A' }}</td></tr>
                    </table>
                </div>
            </div>

            @if ($return->refundTransactions && $return->refundTransactions->isNotEmpty())
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                    <div class="p-5">
                        <h5 class="font-bold mb-3">Refund Attempts</h5>
                        <ul class="list-none mb-0">
                            @foreach ($return->refundTransactions as $refund)
                                <li class="border-b pb-2 mb-2">
                                    <div class="flex justify-between">
                                        <span class="font-semibold">{{ number_format($refund->amount, 2) }}</span>
                                        <span class="badge bg-{{ $refund->status === 'success' ? 'success' : ($refund->status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($refund->status) }}</span>
                                    </div>
                                    <div class="text-sm text-ink-tertiary">{{ $refund->method }} · {{ $refund->created_at->format('d/m/Y h:i A') }}</div>
                                    @if ($refund->gateway_reference)<div class="small">Ref: {{ $refund->gateway_reference }}</div>@endif
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
