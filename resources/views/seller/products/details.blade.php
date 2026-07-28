@extends('seller.layouts.app')
@section('title', $product->name)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .section-card { border-radius: 12px; border: 0; box-shadow: 0 1px 3px rgba(0,0,0,.08); margin-bottom: 1.25rem; }
    .section-card .card-header { background: #fff; border-bottom: 1px solid #e9ecef; padding: .85rem 1.25rem; }
    .section-card .card-header h5 { font-size: .95rem; font-weight: 600; margin: 0; }
    .section-card .card-body { padding: 1.25rem; }
    .detail-label { color: #6c757d; font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; }
    .detail-value { font-size: .95rem; }
    .thumbnail-gallery-img { width: 52px; height: 52px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: border-color .15s; }
    .thumbnail-gallery-img:hover, .thumbnail-gallery-img.active { border-color: #0d6efd; }
</style>
@endpush

@section('content')
<?php
    $variantCount = $product->variants->count();
    $totalStock = $product->variants->sum('stock_in') - $product->variants->sum('stock_out');
    $margin = $product->price - $product->cost_price;
    $marginPercent = $product->cost_price > 0 ? round(($margin / $product->cost_price) * 100, 1) : 0;
    $seo = $product->seo;
?>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1 text-dark">{{ $product->name }}</h4>
        <div class="d-flex align-items-center gap-2 small text-muted">
            <span>SKU: <strong>{{ $product->sku }}</strong></span>
            <span class="text-muted">|</span>
            <span>Added: {{ $product->created_at->format('d M, Y h:i A') }}</span>
            <span class="text-muted">|</span>
            <span>
                @if ($product->status == $product::STATUS_ACTIVE)
                <span class="badge badge-soft-success">Active</span>
                @elseif ($product->status == $product::STATUS_PENDING_APPROVAL)
                <span class="badge badge-soft-warning">Waiting for Approval</span>
                @elseif ($product->status == $product::STATUS_INACTIVE)
                <span class="badge badge-soft-secondary">Inactive</span>
                @elseif ($product->status == $product::STATUS_DELETED)
                <span class="badge badge-soft-danger">Deleted</span>
                @endif
                @if ($product->is_visible)
                <span class="badge badge-soft-success ms-1">Visible</span>
                @else
                <span class="badge badge-soft-secondary ms-1">Hidden</span>
                @endif
            </span>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('seller.products.edit', $product->slug) }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="edit" class="icon-xs"></i> Edit Product
        </a>
        <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="layers" class="icon-xs"></i> Manage Variants
        </a>
        <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1"
            data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $product->id }}">
            <i data-feather="trash-2" class="icon-xs"></i> Delete
        </button>
    </div>
</div>

<div class="row">
    {{-- LEFT COLUMN: Image gallery + info --}}
    <div class="col-lg-7">
        <div class="card section-card">
            <div class="card-body">
                <div class="row g-4">
                    {{-- Main image --}}
                    <div class="col-md-5 text-center">
                        <div class="border rounded bg-light d-flex align-items-center justify-content-center overflow-hidden"
                            style="height:300px;">
                            <img id="mainProductImage"
                                src="{{ $product->imageUrl }}"
                                alt="{{ $product->name }}"
                                class="img-fluid"
                                style="max-height:100%; object-fit:contain;" />
                        </div>
                        @if($product->images->count() > 0)
                        <div class="d-flex flex-wrap gap-1 mt-2 justify-content-center">
                            <img src="{{ $product->imageUrl }}"
                                class="thumbnail-gallery-img active"
                                onclick="switchImage(this, '{{ $product->imageUrl }}')"
                                alt="Thumbnail">
                            @foreach($product->images as $image)
                            <img src="{{ storage_url($image->image) }}"
                                class="thumbnail-gallery-img"
                                onclick="switchImage(this, '{{ storage_url($image->image) }}')"
                                alt="Gallery image">
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Product details --}}
                    <div class="col-md-7">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="detail-label" style="width:110px;">Category</td>
                                <td class="detail-value">{{ $product->category->name ?? '—' }}
                                    @if($product->subcategory) › {{ $product->subcategory->name }} @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="detail-label">Brand</td>
                                <td class="detail-value">{{ $product->brand->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Unit</td>
                                <td class="detail-value">{{ $product->unit_value ?? '' }} {{ $product->unit->short_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Payment</td>
                                <td class="detail-value">{{ ucfirst($product->payment_type->title()) }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Tags</td>
                                <td class="detail-value">
                                    @forelse($product->tags as $tag)
                                    <span class="badge bg-light text-dark me-1">{{ $tag->name }}</span>
                                    @empty
                                    <span class="text-muted">—</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <td class="detail-label">Variants</td>
                                <td class="detail-value">
                                    <span class="badge bg-primary">{{ $variantCount }}</span>
                                    <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="small ms-2">Manage</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="detail-label">Total Stock</td>
                                <td class="detail-value">
                                    <span class="fw-semibold {{ $totalStock <= $product->low_stock_quantity ? 'text-danger' : '' }}">
                                        {{ $totalStock }} {{ $product->unit->short_name ?? 'pcs' }}
                                    </span>
                                    @if($totalStock <= $product->low_stock_quantity)
                                    <span class="badge badge-soft-danger ms-1">Low Stock</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Pricing + profit summary --}}
    <div class="col-lg-5">
        <div class="card section-card h-100">
            <div class="card-header">
                <h5><i class="fas fa-chart-bar me-2 text-primary"></i>Pricing Summary</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-light">
                            <div class="detail-label mb-1">Cost Price</div>
                            <div class="fs-5 fw-bold text-secondary">{{ money($product->cost_price) }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-light">
                            <div class="detail-label mb-1">Selling Price</div>
                            <div class="fs-5 fw-bold text-primary">{{ money($product->price) }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3 bg-light">
                            <div class="detail-label mb-1">Compare Price</div>
                            <div class="fs-5 fw-bold {{ $product->compare_price ? 'text-success' : 'text-muted' }}">
                                {{ $product->compare_price ? money($product->compare_price) : '—' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-3">
                        <div class="p-3 rounded-3 border">
                            <div class="detail-label mb-1">Profit Margin</div>
                            <div class="fs-5 fw-bold {{ $margin > 0 ? 'text-success' : 'text-danger' }}">
                                {{ money($margin) }} ({{ $marginPercent }}%)
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mt-3">
                        <div class="p-3 rounded-3 border">
                            <div class="detail-label mb-1">Low Stock Threshold</div>
                            <div class="fs-5 fw-bold">{{ $product->low_stock_quantity }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabs / Sections --}}
<ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button" role="tab">
            <i class="fas fa-layer-group me-1"></i>Variants ({{ $variantCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab">
            <i class="fas fa-boxes me-1"></i>Stock History
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
            <i class="fas fa-align-left me-1"></i>Description
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">
            <i class="fas fa-truck me-1"></i>Shipping
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
            <i class="fas fa-search me-1"></i>SEO
        </button>
    </li>
</ul>

<div class="tab-content" id="productTabsContent">
    {{-- TAB: Variants --}}
    <div class="tab-pane fade show active" id="variants" role="tabpanel">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-layer-group me-2 text-primary"></i>All Variants</h5>
                <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                    <i class="fas fa-plus"></i> Add Variant
                </a>
            </div>
            <div class="card-body p-0">
                @if($variantCount > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small fw-semibold text-muted">Image</th>
                                <th class="small fw-semibold text-muted">SKU</th>
                                <th class="small fw-semibold text-muted">Barcode</th>
                                <th class="small fw-semibold text-muted">Options</th>
                                <th class="small fw-semibold text-muted text-end">Cost</th>
                                <th class="small fw-semibold text-muted text-end">Price</th>
                                <th class="small fw-semibold text-muted text-end">Compare</th>
                                <th class="small fw-semibold text-muted text-end">Weight</th>
                                <th class="small fw-semibold text-muted text-center">Stock</th>
                                <th class="small fw-semibold text-muted text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $variant)
                            <tr>
                                <td><img src="{{ $variant->imageUrl }}" class="rounded" style="width:36px;height:36px;object-fit:cover;"></td>
                                <td class="text-monospace small">{{ $variant->sku }}</td>
                                <td class="small text-muted">{{ $variant->barcode ?? '—' }}</td>
                                <td><span class="badge badge-soft-secondary">{{ $variant->label }}</span></td>
                                <td class="small text-end">{{ money($variant->cost_price) }}</td>
                                <td class="text-end">{{ money($variant->price) }}</td>
                                <td class="text-end">{{ $variant->compare_price ? money($variant->compare_price) : '—' }}</td>
                                <td class="small text-end">{{ $variant->weight ? $variant->weight.' kg' : '—' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $variant->availableStock > 0 ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $variant->availableStock }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($variant->status)
                                    <span class="badge badge-soft-success">Active</span>
                                    @else
                                    <span class="badge badge-soft-secondary">Disabled</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <p class="mb-1">No variants configured for this product.</p>
                    <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Variants
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- TAB: Stock History --}}
    <div class="tab-pane fade" id="stock" role="tabpanel">
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-boxes me-2 text-primary"></i>Stock History</h5>
                <button type="button" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#stockUpdateModal">
                    <i class="fas fa-plus-circle"></i> Update Stock
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height:400px; overflow-y:auto;">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="small fw-semibold text-muted">Date</th>
                                @if($variantCount > 0)
                                <th class="small fw-semibold text-muted">Variant</th>
                                @endif
                                <th class="small fw-semibold text-muted text-center">Qty</th>
                                <th class="small fw-semibold text-muted text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->stock_history as $history)
                            <tr>
                                <td class="text-nowrap small">{{ $history->created_at->format('d/m/y h:i A') }}</td>
                                @if($variantCount > 0)
                                <td class="text-nowrap small">{{ $history->variant?->label ?? 'Default' }}</td>
                                @endif
                                <td class="text-center small">{{ abs($history->quantity ?? 0) }}</td>
                                <td class="text-center small">
                                    @switch($history->type)
                                    @case(\App\Domain\Product\Enums\StockType::ADD_STOCK)
                                    <span class="badge badge-soft-success">Added</span>
                                    @break
                                    @case(\App\Domain\Product\Enums\StockType::REMOVE_STOCK)
                                    <span class="badge badge-soft-danger">Removed</span>
                                    @break
                                    @case(\App\Domain\Product\Enums\StockType::SET_EXACT_STOCK)
                                    <span class="badge badge-soft-warning">Set Exact</span>
                                    @break
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $variantCount > 0 ? 4 : 3 }}" class="text-center text-muted py-4">No stock history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: Description --}}
    <div class="tab-pane fade" id="description" role="tabpanel">
        <div class="card section-card">
            <div class="card-header">
                <h5><i class="fas fa-align-left me-2 text-primary"></i>Description & Specifications</h5>
            </div>
            <div class="card-body">
                <h6 class="fw-semibold text-muted small text-uppercase">Short Description</h6>
                <p class="mb-4">{{ $product->short_description ?? '—' }}</p>

                <h6 class="fw-semibold text-muted small text-uppercase">Full Description</h6>
                <div class="mb-4">{!! nl2br(e($product->description ?? '—')) !!}</div>

                @if($product->specifications)
                <h6 class="fw-semibold text-muted small text-uppercase">Specifications</h6>
                <table class="table table-sm table-borderless" style="max-width:400px;">
                    @foreach($product->specifications as $key => $value)
                    <tr>
                        <td class="fw-semibold text-muted" style="width:140px;">{{ $key }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </div>
        </div>
    </div>

    {{-- TAB: Shipping --}}
    <div class="tab-pane fade" id="shipping" role="tabpanel">
        <div class="card section-card">
            <div class="card-header">
                <h5><i class="fas fa-truck me-2 text-primary"></i>Shipping & Manufacturer</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="detail-label" style="width:140px;">Weight</td>
                                <td class="detail-value">{{ $product->weight ? $product->weight.' kg' : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Dimensions</td>
                                <td class="detail-value">
                                    {{ $product->height ? $product->height.' × '.$product->width.' × '.$product->length.' cm' : '—' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="detail-label">Country</td>
                                <td class="detail-value">{{ $product->country_of_origin ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="detail-label" style="width:140px;">Manufacturer</td>
                                <td class="detail-value">{{ $product->manufacturer_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Details</td>
                                <td class="detail-value">{{ $product->manufacturer_details ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: SEO --}}
    <div class="tab-pane fade" id="seo" role="tabpanel">
        <div class="card section-card">
            <div class="card-header">
                <h5><i class="fas fa-search me-2 text-primary"></i>SEO & Social Share</h5>
            </div>
            <div class="card-body">
                @if($seo)
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted small text-uppercase">Meta Title</h6>
                        <p>{{ $seo->meta_title ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted small text-uppercase">Meta Keywords</h6>
                        <p>{{ $seo->meta_keywords ?? '—' }}</p>
                    </div>
                    <div class="col-12">
                        <h6 class="fw-semibold text-muted small text-uppercase">Meta Description</h6>
                        <p>{{ $seo->meta_description ?? '—' }}</p>
                    </div>
                    <hr>
                    <h6 class="fw-semibold">Open Graph</h6>
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted small text-uppercase">OG Title</h6>
                        <p>{{ $seo->og_title ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted small text-uppercase">OG Image</h6>
                        @if(!empty($seo->og_image))
                        <img src="{{ storage_url($seo->og_image) }}" class="img-thumbnail" style="max-width:150px;">
                        @else
                        <p class="text-muted">—</p>
                        @endif
                    </div>
                    <div class="col-12">
                        <h6 class="fw-semibold text-muted small text-uppercase">OG Description</h6>
                        <p>{{ $seo->og_description ?? '—' }}</p>
                    </div>
                </div>
                @else
                <p class="text-muted mb-0">No SEO data configured.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $product->id }}">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center modal-body">
                <div class="alert alert-warning d-flex" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 text-danger" style="font-size: 1.5rem;"></i>
                    <p class="mt-1 text-secondary">Are you sure you want to delete this Product?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('seller.products.delete', $product->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-1">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Stock Update Modal --}}
<div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true" data-id="{{ $product->id }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <form action="{{ route('seller.products.stockUpdate', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title fw-bold">Update Inventory</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button class="btn btn-outline-info btn-sm d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="collapse"
                            data-bs-target="#stockInstruction" aria-expanded="false" aria-controls="stockInstruction">
                            ℹ️ Stock update instructions
                        </button>
                        <div class="collapse mt-2" id="stockInstruction">
                            <div class="alert alert-info mb-0">
                                <ul class="mb-0 small">
                                    <li><strong>Add Stock</strong> – Increase stock quantity.</li>
                                    <li><strong>Remove Stock</strong> – Decrease stock.</li>
                                    <li><strong>Set Exact Stock</strong> – Set to a precise number.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($variantCount > 0)
                    @foreach($product->variants as $variant)
                    <h6 class="fw-semibold">{{ $variant->label ?? 'Default' }}</h6>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-2">
                            <select class="form-select form-select-sm" name="stock_action[{{ $variant->id }}]">
                                <option value="{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->label() }}</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Qty ({{ $variant->availableStock }})</span>
                                <input type="number" class="form-control" name="stock_quantity[{{ $variant->id }}]" min="1">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Note</span>
                                <input type="text" class="form-control" name="stock_note[{{ $variant->id }}]">
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)<hr>@endif
                    @endforeach
                    @else
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <select class="form-select form-select-sm" name="stock_action_product">
                                <option value="{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->label() }}</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Qty ({{ $product->stock_in - $product->stock_out }})</span>
                                <input type="number" class="form-control" name="stock_quantity_product" min="1">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Note</span>
                                <input type="text" class="form-control" name="stock_note_product">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">Update Stocks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="alertBox"></div>
@endsection

@push('scripts')
<script>
    function switchImage(el, url) {
        document.querySelectorAll('.thumbnail-gallery-img').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('mainProductImage').src = url;
    }
</script>
@endpush