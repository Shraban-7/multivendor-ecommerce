@extends('seller.layouts.app')
@section('title', 'Create Shipment')

@section('content')
<div class="w-full px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="font-bold mb-0 text-ink">Create Shipment</h4>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-primary">#{{ $order->invoice_id }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5 class="font-semibold mb-0">Shipment Information</h5>
                </div>
                <form method="POST" action="{{ route('seller.shipping.shipments.store') }}">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Carrier <span class="text-feedback-danger">*</span></label>
                                <select name="shipping_carrier_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                                    <option value="">Select carrier...</option>
                                    @foreach ($carriers as $carrier)
                                        <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Tracking Number <span class="text-feedback-danger">*</span></label>
                                <input type="text" name="tracking_number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g., STF123456789" required>
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Weight (kg)</label>
                                <input type="number" step="0.01" min="0" name="weight" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Shipping Cost (৳)</label>
                                <input type="number" step="0.01" min="0" name="shipping_cost" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">COD Amount (৳)</label>
                                <input type="number" step="0.01" min="0" name="cod_amount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div class="col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Notes</label>
                                <textarea name="notes" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-border bg-white text-right">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="package" style="width: 16px; height: 16px;"></i> Create Shipment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
