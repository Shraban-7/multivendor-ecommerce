@extends('seller.layouts.app')
@section('title', $bundle->name)
@section('content')

<div class="flex justify-between items-center mb-3">
    <h4 class="font-bold mb-0 text-ink">{{ $bundle->name }}</h4>
    <div class="flex gap-2">
        <a href="{{ route('seller.bundles.edit', $bundle) }}" class="btn btn-primary btn-sm">
            <i data-lucide="edit" class="icon-xs me-1"></i> Edit
        </a>
        <a href="{{ route('seller.bundles.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" class="icon-xs me-1"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
    <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 gap-4">
    <div class="lg:col-span-2">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
            <div class="p-5 p-4">
                <div class="flex items-start gap-3 mb-3">
                    <img src="{{ $bundle->thumbnail_url }}" class="rounded" style="width:100px;height:100px;object-fit:cover;">
                    <div>
                        <h5 class="mb-1">{{ $bundle->name }}</h5>
                        <p class="text-ink-tertiary text-sm mb-2">SKU: {{ $bundle->sku }}</p>
                        @if($bundle->short_description)
                            <p class="mb-0">{{ $bundle->short_description }}</p>
                        @endif
                    </div>
                </div>

                @if($bundle->description)
                    <hr>
                    <div class="mt-3">
                        <h6 class="font-semibold">Description</h6>
                        <p class="mb-0">{{ $bundle->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mt-4">
            <div class="p-5 p-4">
                <h5 class="text-lg font-semibold mb-3">Bundle Items ({{ $bundle->items->count() }})</h5>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-ink border-collapse table-bordered align-middle bg-white mb-0">
                        <thead class="bg-surface-muted">
                            <tr>
                                <th class="text-sm font-semibold text-ink-tertiary">Product</th>
                                <th class="text-sm font-semibold text-ink-tertiary">SKU</th>
                                <th class="text-sm font-semibold text-ink-tertiary">Price</th>
                                <th class="text-sm font-semibold text-ink-tertiary">Qty</th>
                                <th class="text-sm font-semibold text-ink-tertiary">Subtotal</th>
                                <th class="text-sm font-semibold text-ink-tertiary">Type</th>
                                <th class="text-sm font-semibold text-ink-tertiary">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bundle->items as $item)
                            @php
                                $product = $item->product;
                                $available = $product ? ((int) $product->stock_in - (int) $product->stock_out) : 0;
                                $itemSubtotal = $product ? (float) $product->price * (int) $item->quantity : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <img src="{{ $product?->image_url ?? asset('assets/frontend/images/placeholder-img.jpg') }}"
                                            class="rounded me-2" style="width:40px;height:40px;object-fit:cover;">
                                        <span class="small">{{ $product?->name ?? 'Product deleted' }}</span>
                                    </div>
                                </td>
                                <td class="small">{{ $product?->sku ?? '-' }}</td>
                                <td class="small">{{ $product ? money($product->price) : '-' }}</td>
                                <td class="text-center text-sm">{{ $item->quantity }}</td>
                                <td class="small">{{ money($itemSubtotal) }}</td>
                                <td>
                                    @if($item->is_optional)
                                        <span class="badge bg-surface-muted text-ink border">Optional</span>
                                    @else
                                        <span class="badge badge-soft-primary">Required</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2 py-1 rounded-md
                                        @if($available <= 0) badge-soft-danger
                                        @elseif($available <= 5) badge-soft-warning
                                        @else badge-soft-secondary @endif">
                                        {{ $available }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-surface-muted">
                            <tr>
                                <td colspan="4" class="text-right text-sm font-semibold">Totals:</td>
                                <td class="text-sm font-bold">{{ money($subtotal) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($bundle->images->count() > 0)
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mt-4">
            <div class="p-5 p-4">
                <h5 class="text-lg font-semibold mb-3">Gallery</h5>
                <div class="grid grid-cols-1 gap-2">
                    @foreach($bundle->images as $image)
                    <div class="col-span-1">
                        <img src="{{ $image->image_url }}" class="img-fluid rounded" style="height:120px;width:100%;object-fit:cover;">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
            <div class="p-5 p-4">
                <h5 class="text-lg font-semibold mb-3">Summary</h5>
                <table class="w-full text-left text-sm text-ink border-collapse text-sm border-0 mb-0">
                    <tr>
                        <td class="text-sm text-ink-tertiary">Status</td>
                        <td>
                            @if($bundle->status === $bundle::STATUS_ACTIVE)
                                <span class="badge badge-soft-success">Active</span>
                            @elseif($bundle->status === $bundle::STATUS_PENDING_APPROVAL)
                                <span class="badge badge-soft-warning">Pending</span>
                            @elseif($bundle->status === $bundle::STATUS_INACTIVE)
                                <span class="badge badge-soft-secondary">Inactive</span>
                            @elseif($bundle->status === $bundle::STATUS_DRAFT)
                                <span class="badge badge-soft-info">Draft</span>
                            @endif
                            <a href="#" class="text-sm ms-1" data-bs-toggle="modal" data-bs-target="#statusModal">Change</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm text-ink-tertiary">Visibility</td>
                        <td>
                            @if($bundle->is_visible)
                                <span class="badge badge-soft-success">Visible</span>
                            @else
                                <span class="badge badge-soft-secondary">Hidden</span>
                            @endif
                            <form action="{{ route('seller.bundles.toggleVisibility', $bundle) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center text-sm text-brand hover:text-brand-deep transition-colors p-0 text-sm ms-1">Toggle</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm text-ink-tertiary">Type</td>
                        <td><span class="badge bg-surface-muted text-ink border capitalize">{{ $bundle->type }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-sm text-ink-tertiary">Price Type</td>
                        <td class="text-sm capitalize">{{ $bundle->price_type }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm text-ink-tertiary">Original Total</td>
                        <td class="small">{{ money($subtotal) }}</td>
                    </tr>
                    @if($savings > 0)
                    <tr>
                        <td class="text-sm text-ink-tertiary">You Save</td>
                        <td class="text-sm text-feedback-success font-bold">{{ money($savings) }} ({{ $savingsPercent }}%)</td>
                    </tr>
                    @endif
                    <tr class="border-t">
                        <td class="font-semibold">Bundle Price</td>
                        <td class="font-bold text-base text-brand">{{ money($calculatedPrice) }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm text-ink-tertiary">Stock Status</td>
                        <td>
                            @if($stockStatus === 'out_of_stock')
                                <span class="badge badge-soft-danger">Out of Stock</span>
                            @elseif($stockStatus === 'low_stock')
                                <span class="badge badge-soft-warning">Low ({{ $stock }})</span>
                            @else
                                <span class="badge badge-soft-success">In Stock ({{ $stock }})</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <hr>
                <div class="grid gap-2">
                    <form action="{{ route('seller.bundles.duplicate', $bundle) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light w-full btn-sm">
                            <i data-lucide="copy" class="icon-xs me-1"></i> Duplicate Bundle
                        </button>
                    </form>
                    <form action="{{ route('seller.bundles.destroy', $bundle) }}" method="POST"
                        onsubmit="return confirm('Delete this bundle permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-full btn-sm">
                            <i data-lucide="trash-2" class="icon-xs me-1"></i> Delete Bundle
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form method="POST" action="{{ route('seller.bundles.updateStatus', $bundle) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Change Status</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="{{ $bundle::STATUS_DRAFT }}" {{ $bundle->status === $bundle::STATUS_DRAFT ? 'selected' : '' }}>Draft</option>
                        <option value="{{ $bundle::STATUS_PENDING_APPROVAL }}" {{ $bundle->status === $bundle::STATUS_PENDING_APPROVAL ? 'selected' : '' }}>Pending Approval</option>
                        <option value="{{ $bundle::STATUS_ACTIVE }}" {{ $bundle->status === $bundle::STATUS_ACTIVE ? 'selected' : '' }}>Active</option>
                        <option value="{{ $bundle::STATUS_INACTIVE }}" {{ $bundle->status === $bundle::STATUS_INACTIVE ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
