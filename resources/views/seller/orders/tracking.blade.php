@extends('seller.layouts.app')
@section('title', 'Tracking - '.$order->invoice_id)

@section('content')
<div class="container-fluid px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-light btn-sm">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="font-bold mb-0 text-ink">Shipping & Tracking</h4>
        <span class="badge badge-soft-primary">#{{ $order->invoice_id }}</span>
    </div>

    <div class="grid grid-cols-1 gap-3">
        <div class="lg:col-span-5">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b">
                    <h5 class="font-semibold mb-0">Add Tracking Info</h5>
                </div>
                <form method="POST" action="{{ route('seller.orders.tracking.store', $order) }}">
                    @csrf
                    <div class="p-5">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Carrier <span class="text-feedback-danger">*</span></label>
                            <select name="carrier_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                <option value="">Select carrier...</option>
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Tracking Number <span class="text-feedback-danger">*</span></label>
                            <input type="text" name="tracking_number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g., STF123456789" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Notes (Optional)</label>
                            <textarea name="notes" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="Additional shipping notes..."></textarea>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t text-right">
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="truck" style="width: 16px; height: 16px;"></i> Add Tracking
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-7">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b">
                    <h5 class="font-semibold mb-0">Tracking History</h5>
                </div>
                <div class="p-5 p-0">
                    @if ($trackings->count() > 0)
                        <div class="flex flex-col ">
                            @foreach ($trackings as $tracking)
                                <div class="flex items-center px-0 py-2 border-b border-border px-4 py-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-semibold">{{ $tracking->carrier->name ?? 'Unknown Carrier' }}</span>
                                                <code class="small">{{ $tracking->tracking_number }}</code>
                                            </div>
                                            @if ($tracking->notes)
                                                <p class="text-ink-tertiary text-sm mb-0">{{ $tracking->notes }}</p>
                                            @endif
                                        </div>
                                        <small class="text-ink-tertiary">{{ $tracking->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-ink-tertiary">
                            <i data-feather="package" style="width: 48px; height: 48px;" class="mb-3"></i>
                            <p class="mb-0">No tracking information added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
