@extends('seller.layouts.app')
@section('title', 'Tracking - '.$order->invoice_id)

@section('content')
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="truck" class="text-brand-deep" style="width:12px;height:12px;"></i>
                    <span>Orders</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Tracking</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Shipping & Tracking</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700">
                        <i data-lucide="receipt-text" style="width:11px;height:11px;" class="me-1"></i> #{{ $order->invoice_id }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Attach courier details and monitor fulfilment progress.</p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
    <div class="lg:col-span-2">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-border bg-surface-muted">
                <h5 class="text-sm font-semibold text-ink mb-0">Add Tracking Info</h5>
            </div>
            <form method="POST" action="{{ route('seller.orders.tracking.store', $order) }}">
                @csrf
                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Carrier <span class="text-red-500">*</span></label>
                        <select name="carrier_id" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            <option value="">Select carrier...</option>
                            @foreach ($carriers as $carrier)
                                <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Tracking Number <span class="text-red-500">*</span></label>
                        <input type="text" name="tracking_number" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g., STF123456789" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Notes (Optional)</label>
                        <textarea name="notes" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3" placeholder="Additional shipping notes..."></textarea>
                    </div>
                </div>
                <div class="px-4 py-3 border-t border-border">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="truck" style="width: 16px; height: 16px;"></i> Add Tracking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-border bg-surface-muted">
                <h5 class="text-sm font-semibold text-ink mb-0">Tracking History</h5>
            </div>
            <div>
                @if ($trackings->count() > 0)
                    <div class="divide-y divide-border">
                        @foreach ($trackings as $tracking)
                            <div class="px-4 py-3 flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-sm">{{ $tracking->carrier->name ?? 'Unknown Carrier' }}</span>
                                        <code class="text-xs bg-surface-muted px-1.5 py-0.5 rounded">{{ $tracking->tracking_number }}</code>
                                    </div>
                                    @if ($tracking->notes)
                                        <p class="text-sm text-ink-tertiary mb-0">{{ $tracking->notes }}</p>
                                    @endif
                                </div>
                                <small class="text-ink-tertiary whitespace-nowrap ml-3">{{ $tracking->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-ink-tertiary">
                        <i data-lucide="package" style="width: 48px; height: 48px;" class="mb-3"></i>
                        <p class="mb-0">No tracking information added yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection