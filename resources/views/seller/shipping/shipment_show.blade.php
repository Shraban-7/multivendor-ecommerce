@extends('seller.layouts.app')
@section('title', 'Shipment #'.$shipment->id)

@section('content')
<div class="w-full px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.shipping.shipments') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="font-bold mb-0 text-ink">Shipment #{{ $shipment->id }}</h4>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-primary">#{{ $shipment->order?->invoice_id ?? 'N/A' }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5 class="font-semibold mb-0">Shipment Details</h5>
                </div>
                <div class="p-5">
                    <ul class="flex flex-col">
                        <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                            <span class="text-ink-tertiary">Status</span>
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
                            @endif
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                            <span class="text-ink-tertiary">Carrier</span>
                            <span class="font-medium">{{ $shipment->carrier?->name ?? 'N/A' }}</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                            <span class="text-ink-tertiary">Tracking #</span>
                            <code>{{ $shipment->tracking_number ?? 'N/A' }}</code>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                            <span class="text-ink-tertiary">Weight</span>
                            <span>{{ $shipment->weight ? $shipment->weight.' kg' : 'N/A' }}</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                            <span class="text-ink-tertiary">Shipping Cost</span>
                            <span>{{ $shipment->shipping_cost ? money($shipment->shipping_cost) : 'N/A' }}</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                            <span class="text-ink-tertiary">COD Amount</span>
                            <span>{{ $shipment->cod_amount ? money($shipment->cod_amount) : 'N/A' }}</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                            <span class="text-ink-tertiary">Created</span>
                            <span>{{ $shipment->created_at->format('d/m/Y h:i A') }}</span>
                        </li>
                        @if ($shipment->shipped_at)
                            <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                                <span class="text-ink-tertiary">Shipped</span>
                                <span>{{ $shipment->shipped_at->format('d/m/Y h:i A') }}</span>
                            </li>
                        @endif
                        @if ($shipment->delivered_at)
                            <li class="flex items-center px-0 py-2 border-b border-border justify-between">
                                <span class="text-ink-tertiary">Delivered</span>
                                <span>{{ $shipment->delivered_at->format('d/m/Y h:i A') }}</span>
                            </li>
                        @endif
                    </ul>

                    @if ($shipment->notes)
                        <hr>
                        <p class="text-sm mb-0">{{ $shipment->notes }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mt-3" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5 class="font-semibold mb-0">Update Status</h5>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('seller.shipping.shipments.update-status', $shipment->id) }}">
                        @csrf
                        <div class="mb-2">
                            <select name="status" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                @foreach (\App\Domain\Shipping\Models\Shipment::statuses() as $value => $label)
                                    <option value="{{ $value }}" {{ $shipment->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="location" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Location (optional)">
                        </div>
                        <div class="mb-2">
                            <textarea name="description" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="2" placeholder="Description (optional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-full">Update</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5 class="font-semibold mb-0">Tracking Timeline</h5>
                </div>
                <div class="p-5">
                    @if ($shipment->trackingLogs->count() > 0)
                        <div class="timeline">
                            @foreach ($shipment->trackingLogs->sortByDesc('logged_at') as $log)
                                <div class="flex gap-3 mb-3">
                                    <div class="flex flex-col items-center" style="width: 24px;">
                                        <div class="rounded-full bg-brand-deep" style="width: 12px; height: 12px;"></div>
                                        @if (!$loop->last)
                                            <div class="grow" style="width: 2px; background: #e0e0e0;"></div>
                                        @endif
                                    </div>
                                    <div class="grow pb-3">
                                        <div class="flex justify-between">
                                            <span class="font-semibold text-sm">{{ \App\Domain\Shipping\Models\Shipment::statuses()[$log->status] ?? $log->status }}</span>
                                            <small class="text-ink-tertiary">{{ $log->logged_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                        @if ($log->location)
                                            <small class="text-ink-tertiary block">{{ $log->location }}</small>
                                        @endif
                                        @if ($log->description)
                                            <p class="text-sm mb-0 mt-1">{{ $log->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-ink-tertiary text-center py-3 mb-0">No tracking logs yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
