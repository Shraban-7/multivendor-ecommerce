@extends('seller.layouts.app')
@section('title', $bundle->name)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">{{ $bundle->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('seller.bundles.edit', $bundle) }}" class="btn btn-primary btn-sm">
            <i data-feather="edit" class="icon-xs me-1"></i> Edit
        </a>
        <a href="{{ route('seller.bundles.index') }}" class="btn btn-light btn-sm border">
            <i data-feather="arrow-left" class="icon-xs me-1"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <img src="{{ $bundle->thumbnail_url }}" class="rounded" style="width:100px;height:100px;object-fit:cover;">
                    <div>
                        <h5 class="mb-1">{{ $bundle->name }}</h5>
                        <p class="text-muted small mb-2">SKU: {{ $bundle->sku }}</p>
                        @if($bundle->short_description)
                            <p class="mb-0">{{ $bundle->short_description }}</p>
                        @endif
                    </div>
                </div>

                @if($bundle->description)
                    <hr>
                    <div class="mt-3">
                        <h6 class="fw-semibold">Description</h6>
                        <p class="mb-0">{{ $bundle->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Bundle Items ({{ $bundle->items->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle bg-white mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small fw-semibold text-muted">Product</th>
                                <th class="small fw-semibold text-muted">SKU</th>
                                <th class="small fw-semibold text-muted">Price</th>
                                <th class="small fw-semibold text-muted">Qty</th>
                                <th class="small fw-semibold text-muted">Subtotal</th>
                                <th class="small fw-semibold text-muted">Type</th>
                                <th class="small fw-semibold text-muted">Stock</th>
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
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $product?->image_url ?? asset('assets/frontend/images/placeholder-img.jpg') }}"
                                            class="rounded me-2" style="width:40px;height:40px;object-fit:cover;">
                                        <span class="small">{{ $product?->name ?? 'Product deleted' }}</span>
                                    </div>
                                </td>
                                <td class="small">{{ $product?->sku ?? '-' }}</td>
                                <td class="small">{{ $product ? money($product->price) : '-' }}</td>
                                <td class="text-center small">{{ $item->quantity }}</td>
                                <td class="small">{{ money($itemSubtotal) }}</td>
                                <td>
                                    @if($item->is_optional)
                                        <span class="badge bg-light text-dark border">Optional</span>
                                    @else
                                        <span class="badge badge-soft-primary">Required</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2 py-1 rounded-3
                                        @if($available <= 0) badge-soft-danger
                                        @elseif($available <= 5) badge-soft-warning
                                        @else badge-soft-secondary @endif">
                                        {{ $available }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end small fw-semibold">Totals:</td>
                                <td class="small fw-bold">{{ money($subtotal) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($bundle->images->count() > 0)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Gallery</h5>
                <div class="row g-2">
                    @foreach($bundle->images as $image)
                    <div class="col-3">
                        <img src="{{ $image->image_url }}" class="img-fluid rounded" style="height:120px;width:100%;object-fit:cover;">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Summary</h5>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="small text-muted">Status</td>
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
                            <a href="#" class="small ms-1" data-bs-toggle="modal" data-bs-target="#statusModal">Change</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Visibility</td>
                        <td>
                            @if($bundle->is_visible)
                                <span class="badge badge-soft-success">Visible</span>
                            @else
                                <span class="badge badge-soft-secondary">Hidden</span>
                            @endif
                            <form action="{{ route('seller.bundles.toggleVisibility', $bundle) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link p-0 small ms-1">Toggle</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Type</td>
                        <td><span class="badge bg-light text-dark border text-capitalize">{{ $bundle->type }}</span></td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Price Type</td>
                        <td class="small text-capitalize">{{ $bundle->price_type }}</td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Original Total</td>
                        <td class="small">{{ money($subtotal) }}</td>
                    </tr>
                    @if($savings > 0)
                    <tr>
                        <td class="small text-muted">You Save</td>
                        <td class="small text-success fw-bold">{{ money($savings) }} ({{ $savingsPercent }}%)</td>
                    </tr>
                    @endif
                    <tr class="border-top">
                        <td class="fw-semibold">Bundle Price</td>
                        <td class="fw-bold fs-5 text-primary">{{ money($calculatedPrice) }}</td>
                    </tr>
                    <tr>
                        <td class="small text-muted">Stock Status</td>
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
                <div class="d-grid gap-2">
                    <form action="{{ route('seller.bundles.duplicate', $bundle) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light border w-100 btn-sm">
                            <i data-feather="copy" class="icon-xs me-1"></i> Duplicate Bundle
                        </button>
                    </form>
                    <form action="{{ route('seller.bundles.destroy', $bundle) }}" method="POST"
                        onsubmit="return confirm('Delete this bundle permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                            <i data-feather="trash-2" class="icon-xs me-1"></i> Delete Bundle
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
                    <select name="status" class="form-select">
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
