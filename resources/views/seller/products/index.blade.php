@extends('seller.layouts.app')
@section('title', 'My Products')
@section('content')

<div class="mb-3 d-flex justify-content-between align-items-end">
    <h4 class="mb-0">My Products</h4>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">
        <i data-feather="plus" class="icon-xs me-1"></i> Add Product
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white" id="product-table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product</th>
                <th>Price Range</th>
                <th>Status</th>
                <th>Added</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            @php
            $totalStockIn = $product->variants->sum('stock_in');
            $totalStockOut = $product->variants->sum('stock_out');
            $totalStock = $totalStockIn = $totalStockOut;
            $minPrice = min($product->variants->min('selling_price'), $product->selling_price);
            $maxPrice = max($product->variants->max('selling_price'), $product->selling_price);
            $variantCount = $product->variants->count();
            @endphp
            <tr>
                <td class="small">{{ $product->sku }}</td>
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

                <td><span>{{ money($minPrice) }}</span>
                    @if ($maxPrice != $minPrice)
                    - {{ money($maxPrice) }}
                    @endif
                </td>

                <td>
                    @if ($product->status == $product::STATUS_ACTIVE)
                    <span class="badge text-bg-success">Active</span>
                    @elseif ($product->status == $product::STATUS_PENDING_APPROVAL)
                    <span class="badge text-bg-warning">Waiting for Approval</span>
                    @elseif ($product->status == $product::STATUS_INACTIVE)
                    <span class="badge text-bg-secondary">Inactive</span>
                    @elseif ($product->status == $product::STATUS_DELETED)
                    <span class="badge text-bg-danger">Deleted</span>
                    @endif

                </td>

                <td class="small">{{ $product->created_at->format('d/m/Y h:ia') }}</td>

                <td>
                    <div class="d-flex text-nowrap">
                        <a href="{{ route('seller.products.show', $product->slug) }}" target="__blank" class="btn btn-light btn-sm border me-1 mb-1">
                            <i data-feather="eye" class="icon-xs me-1"></i>Details
                        </a>
                        <a href="{{ route('seller.products.edit', $product->slug) }}"
                            class="btn btn-light btn-sm border mb-1" target="__blank">
                            <i data-feather="edit" class="icon-xs me-1"></i> Edit
                        </a>
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
<div class="modal fade" id="variantsModal-{{ $product->id }}" tabindex="-1"
    aria-labelledby="variantsModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
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
                            <th>SKU</th>
                            <th>Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->variants as $variant)
                        <tr>
                            <td>{{ $variant->sku }}</td>
                            <td class="fw-bold">{{ $variant->fullName }}</td>
                            <td class="text-center">
                                {{ money($variant->discounted_price ?? $variant->selling_price) }}
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


