@extends('seller.layouts.app')
@section('title', 'Shipments')

@section('content')
<div class="w-full px-0">
    <div class="flex flex-wrap justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">Shipments</h4>
        <div class="flex gap-2">
            <form method="GET" class="flex gap-2 items-center">
                <select name="status" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" style="width:auto;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach (\App\Domain\Shipping\Models\Shipment::statuses() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="text" name="tracking_number" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" style="width:160px;" placeholder="Tracking #" value="{{ request('tracking_number') }}">
                <button class="btn btn-primary btn-sm"><i data-lucide="search" style="width:14px;height:14px;"></i></button>
            </form>
        </div>
    </div>

    @if ($shipments->count() > 0)
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0" style="border-radius: 12px;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-hover mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="text-sm font-semibold text-ink-tertiary">#</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Order</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Carrier</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Tracking</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Status</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Date</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->id }}</td>
                                <td>
                                    <a href="{{ route('seller.orders.details', $shipment->order?->invoice_id) }}" class="no-underline">
                                        #{{ $shipment->order?->invoice_id ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $shipment->carrier?->name ?? 'N/A' }}</td>
                                <td><code>{{ $shipment->tracking_number ?? 'N/A' }}</code></td>
                                <td>
                                    @php $label = $shipment->status; @endphp
                                    @if ($label === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-warning">Pending</span>
                                    @elseif ($label === 'picked_up')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-info">Picked Up</span>
                                    @elseif ($label === 'in_transit')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-primary">In Transit</span>
                                    @elseif ($label === 'out_for_delivery')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-warning">Out for Delivery</span>
                                    @elseif ($label === 'delivered')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-success">Delivered</span>
                                    @elseif ($label === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-danger">Failed</span>
                                    @elseif ($label === 'returned')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-secondary">Returned</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-secondary">{{ $label }}</span>
                                    @endif
                                </td>
                                <td>{{ $shipment->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('seller.shipping.shipments.show', $shipment->id) }}"
                                       class="btn btn-light btn-sm">
                                        <i data-lucide="eye" style="width:14px;height:14px;"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if ($shipments->hasPages())
            <div class="mt-3 flex justify-end">{{ $shipments->links() }}</div>
        @endif
    @else
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0" style="border-radius: 12px;">
            <div class="p-5 text-center py-5">
                <i data-lucide="package" style="width: 64px; height: 64px;" class="text-ink-tertiary mb-3"></i>
                <h5 class="font-semibold mb-2">No Shipments</h5>
                <p class="text-ink-tertiary mb-0">Shipments will appear here after you create them from order details.</p>
            </div>
        </div>
    @endif
</div>
@endsection
