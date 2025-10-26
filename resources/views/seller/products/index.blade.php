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
                        $minPrice = $product->variants->min('selling_price');
                        $maxPrice = $product->variants->max('selling_price');
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ storage_url($product->thumbnail) }}" class="rounded me-2"
                                    style="width:50px;height:50px;object-fit:cover">
                                <div>
                                    <a href="{{ route('seller.products.show', $product->slug) }}" target="__blank" class="fw-bold">{{ $product->name }}</a><br>
                                    @if ($product->variants->count() > 0)
                                        <a href="#" class="small text-muted text-decoration-underline"
                                            data-bs-toggle="modal" data-bs-target="#variantsModal-{{ $product->id }}">
                                            View variants ({{ $totalStock }} {{ $product->unit->short_name }})
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td><span>{{ money($minPrice) }}</span> @if($maxPrice != $minPrice) - money($maxPrice) @endif</td>

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

                        <td>{{ $product->created_at->format('d M h:ia') }}</td>

                        <td>
                            <div class="d-flex">
                                <a href="{{ route('seller.products.edit', $product->slug) }}"
                                    class="btn btn-light btn-sm border" target="__blank">
                                    <i data-feather="edit" class="icon-xs me-1"></i> Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                                                {{ money($variant->discounted_price ?? $variant->selling_price) }}</td>
                                            <td class="text-center">{{ $variant->availableStock }}
                                                {{ $product->unit->short_name }}</td>
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

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('seller.products.store') }}" method="post">
                @CSRF
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Category</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Subcategory</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>

                                <option value=""></option>

                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Brand</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Buying Price</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Selling Price</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Quantity</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Stock in</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-theme">Save Contest</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        new DataTable('#product-table', {
            order: [
                [3, 'asc']
            ]
        });
    </script>
@endpush
