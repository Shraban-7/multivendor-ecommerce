@extends('seller.layouts.app')
@section('title', 'My Products')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">My Products</h4>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
        <i data-feather="plus" class="icon-xs"></i> Add Product
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white" id="product-table">
        <thead class="table-light">
            <tr>
                <th scope="col" class="small fw-semibold text-muted">Product</th>
                <th scope="col" class="small fw-semibold text-muted">SKU</th>
                <th scope="col" class="small fw-semibold text-muted">Price Range</th>
                <th scope="col" class="small fw-semibold text-muted">Stock</th>
                <th scope="col" class="small fw-semibold text-muted">Status</th>
                <th scope="col" class="small fw-semibold text-muted">Added</th>
                <th scope="col" class="small fw-semibold text-muted">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <?php
                    $variantCount = $product->variants->count();                    
                    $totalStockIn = $product->stock_in;
                    $totalStockOut = $product->stock_out;
                    if($variantCount > 0) {
                        $totalStockIn = $product->variants->sum('stock_in');
                        $totalStockOut = $product->variants->sum('stock_out');
                    }
                    $totalStock = $product->totalStock;
                    $minPrice = min($product->variants->min('price'), $product->price);
                    $maxPrice = max($product->variants->max('price'), $product->price);
                    $lowStockQty = $product->low_stock_quantity;
                ?>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $product->imageUrl }}" class="rounded me-2"
                            style="width:50px;height:50px;object-fit:cover">
                        <div>
                            <p class="mb-0 fw-bold">{{ $product->name }}</p>
                            @if ($variantCount > 0)
                            <a href="#" class="small text-muted text-decoration-underline"
                                data-bs-toggle="modal" data-bs-target="#variantsModal-{{ $product->id }}">
                                View {{ $variantCount }} variants
                            </a>
                            @endif
                        </div>
                    </div>
                </td>

                <td class="small">{{ $product->sku }}</td>

                <td>
                    @if($variantCount > 0)
                    <span>{{ money($minPrice) }}</span>
                    @if ($maxPrice != $minPrice)
                    - {{ money($maxPrice) }}
                    @endif
                    @else
                    {{ money($product->price) }}
                    @endif
                </td>
                
                <td class="text-center">
                    <span class="badge px-2 py-1 rounded-3
                        @if($totalStock <= $lowStockQty)
                            badge-soft-danger
                        @else
                            badge-soft-secondary
                        @endif">
                        {{ $totalStock }} {{ $product->unit->short_name }}
                    </span>
                </td>

                <td>
                    @if ($product->status == $product::STATUS_ACTIVE)
                    <span class="badge badge-soft-success">Active</span>
                    @elseif ($product->status == $product::STATUS_PENDING_APPROVAL)
                    <span class="badge badge-soft-warning">Waiting for Approval</span>
                    @elseif ($product->status == $product::STATUS_INACTIVE)
                    <span class="badge badge-soft-secondary">Inactive</span>
                    @elseif ($product->status == $product::STATUS_DRAFT)
                    <span class="badge badge-soft-info">Draft</span>
                    @elseif ($product->status == $product::STATUS_DELETED)
                    <span class="badge badge-soft-danger">Deleted</span>
                    @endif
                    @if ($product->is_visible && $product->status == $product::STATUS_ACTIVE)
                    <span class="badge badge-soft-success ms-1"><i data-feather="eye" class="icon-xs"></i></span>
                    @elseif (!$product->is_visible && $product->status != $product::STATUS_DELETED)
                    <span class="badge badge-soft-secondary ms-1"><i data-feather="eye-off" class="icon-xs"></i></span>
                    @endif
                </td>

                <td>{{ $product->created_at->format('d/m/Y h:ia') }}</td>

                <td>
                    <div class="d-flex text-nowrap">
                        <a href="{{ route('seller.products.show', $product->slug) }}" target="__blank" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 me-1 mb-1">
                            <i data-feather="eye" class="icon-xs"></i>Details
                        </a>
                        <a href="{{ route('seller.products.edit', $product->slug) }}"
                            class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 mb-1" target="__blank">
                            <i data-feather="edit" class="icon-xs"></i> Edit
                        </a>
                        @if ($product->status != $product::STATUS_DELETED)
                        <form action="{{ route('seller.products.duplicate', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 mb-1">
                                <i data-feather="copy" class="icon-xs"></i> Clone
                            </button>
                        </form>
                        <form action="{{ route('seller.products.toggleVisibility', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 mb-1">
                                @if ($product->is_visible)
                                <i data-feather="eye-off" class="icon-xs"></i> Hide
                                @else
                                <i data-feather="eye" class="icon-xs"></i> Show
                                @endif
                            </button>
                        </form>
                        @endif
                        <button type="button" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 mb-1 text-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $product->id }}">
                            <i data-feather="trash-2" class="icon-xs"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {{ $products->links() }}
</div>

@foreach ($products as $product)
<div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1"
    aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $product->id }}">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                <p class="text-danger small mb-0">This action cannot be undone. All variants, images, and stock history will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('seller.products.delete', $product) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="variantsModal-{{ $product->id }}" tabindex="-1"
    aria-labelledby="variantsModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="variantsModalLabel-{{ $product->id }}">
                    Variants – {{ $product->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @if ($product->variants->count())
                <table class="table table-sm table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="small fw-semibold text-muted">SKU</th>
                            <th scope="col" class="small fw-semibold text-muted">Name</th>
                            <th scope="col" class="small fw-semibold text-muted text-center">Price</th>
                            <th scope="col" class="small fw-semibold text-muted text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants as $variant)
                        <tr>
                            <td>{{ $variant->sku }}</td>
                            <td class="fw-bold">{{ $variant->label }}</td>
                            <td class="text-center">
                                {{ money($variant->compare_price ?? $variant->price) }}
                            </td>
                            <td class="text-center">{{ $variant->availableStock }}
                                {{ $product->unit->short_name }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-3">No variants found.</div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
