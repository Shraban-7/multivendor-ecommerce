@extends('seller.layouts.app')
@section('title', 'Product Details')
@section('content')
    <div class="content-header mb-3">
        <div class="container-fluid">
            <div class="row gy-3 align-items-start align-items-lg-center justify-content-between">
                <div class="col-12 col-lg-6">
                    <h4 class="m-0 text-dark">{{ $product->name }}</h4>
                    <ol class="breadcrumb mt-2">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Product Details</li>
                    </ol>
                </div>

                <div class="col-12 col-lg-6 text-lg-end">
                    <a href="{{ route('seller.products.edit', $product->slug) }}" class="btn btn-primary w-100 w-lg-auto">
                        <i data-feather="edit" class="icon-xs me-1"></i> Edit Product
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Product Overview Card -->
                <div class="col-lg-8">
                    <div class="mb-4 shadow-sm card">
                        <div class="bg-white card-header d-flex justify-content-between">
                            <h5 class="mb-0 card-title">Product Overview</h5>
                            <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2">
                                <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#addOptionModal">
                                    <i data-feather="plus" class="icon-xs"></i> Add Option
                                </button>

                                <button class="btn btn-outline-danger btn-sm" title="Delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal-{{ $product->id }}">
                                    <i data-feather="trash-2" class="icon-xs"></i> Delete Product
                                </button>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Product Images -->
                                <div class="col-12 col-md-5 mb-3">
                                    <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                        class="border rounded shadow-sm img-fluid w-100"
                                        style="height: 250px; object-fit: contain;">
                                </div>
                                <!-- Product Details -->
                                <div class="col-12 col-md-7">
                                    <div
                                        class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-3">
                                        <h4 class="mb-0">{{ $product->name }}</h4>
                                        <span class="text-muted small mt-2 mt-sm-0">(ID: {{ $product->id }})</span>
                                    </div>

                                    <div class="mb-3">
                                        <span class="badge bg-info rounded-pill me-1">{{ $product->category->name }}</span>
                                        @if ($product['subcategory'])
                                            <span
                                                class="badge bg-secondary rounded-pill me-1">{{ $product->subcategory->name }}</span>
                                        @endif
                                        <span class="badge bg-primary rounded-pill">{{ $product->brand->name }}</span>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0 table-sm product-info">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold pe-3" style="width: 30%;">SKU</td>
                                                    <td>{{ $product->sku }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold pe-3">Product Collecting Price</td>
                                                    <td>{{ money($product->buying_price) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold pe-3">Selling Price</td>
                                                    <td>{{ money($product->selling_price) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold pe-3">Profit Margin</td>
                                                    <td>
                                                        <span
                                                            class="badge
                                                                {{ $product->profit_percent >= 30 ? 'bg-success' : ($product->profit_percent >= 15 ? 'bg-warning' : 'bg-danger') }}">
                                                            {{ number_format($product->profit_percent, 1) }}%
                                                        </span>
                                                        <small class="d-block text-muted">
                                                            Profit: {{ money($product->profit_amount) }}
                                                        </small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold pe-3">Created On</td>
                                                    <td>{{ $product['created_at']->format('M d, Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold pe-3">Last Updated</td>
                                                    <td>{{ $product['updated_at']->format('M d, Y') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-4 col-md-12">
                                    <div class="row">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="text-muted fw-bold small mb-0">Product Variants</h5>
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addVariantModal">
                                                <i data-feather="plus" class="icon-xs"></i> Add Variant
                                            </button>
                                        </div>

                                        @foreach ($product->variants as $variant)
                                            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                                                <div class="card h-100 shadow-sm"
                                                    style="width: 18rem; position: relative; overflow: hidden;">

                                                    <div class="position-relative">
                                                        @if ($variant['image'])
                                                            <img src="{{ storage_url($variant->image) }}"
                                                                class="card-img-top" alt="Variant Image"
                                                                style="height: 180px; object-fit: cover;">
                                                        @endif

                                                        @if ($variant['is_default'])
                                                            <span
                                                                class="badge bg-success position-absolute top-0 start-0 m-2">Default</span>
                                                        @endif
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="card-title mb-0">SKU: {{ $variant->sku }}</h6>
                                                            <div class="d-flex">
                                                                <button class="btn btn-light border btn-sm me-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editVariantModal{{ $loop->iteration }}">
                                                                    <i data-feather="edit" class="icon-xs"></i>
                                                                </button>
                                                                <button class="btn btn-danger border btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteVariantModal{{ $loop->iteration }}">
                                                                    <i data-feather="trash" class="icon-xs"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        @foreach ($variant->option_values as $option_value)
                                                            <p class="mb-1 small text-muted">
                                                                {{ $option_value->option->name }}:
                                                                {{ $option_value->value }}</p>
                                                        @endforeach
                                                        <hr>
                                                        <div class="d-flex flex-wrap small text-muted mt-3">
                                                            <div class="me-3">Stock:
                                                                <strong>{{ $variant->stock }}</strong>
                                                            </div>
                                                            <div class="me-3">Price:
                                                                <strong>{{ money($variant->selling_price) }}</strong>
                                                            </div>
                                                            <div>Discount:
                                                                <strong>{{ money($variant->discounted_price) }}</strong>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="editVariantModal{{ $loop->iteration }}"
                                                tabindex="-1"
                                                aria-labelledby="editVariantModalLabel{{ $loop->iteration }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editVariantModalLabel{{ $loop->iteration }}">Edit
                                                                Variant</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form
                                                            action="{{ route('seller.productVariants.update', [$product->id, $variant->id]) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="mb-3 col-6">
                                                                        <label class="form-label">Buying Price</label>
                                                                        <div class="input-group">
                                                                            <span
                                                                                class="input-group-text">{{ currency() }}</span>
                                                                            <input type="number" class="form-control"
                                                                                name="buying_price" step="0.01"
                                                                                value="{{ $variant->buying_price }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3 col-6">
                                                                        <label class="form-label">Selling Price</label>
                                                                        <div class="input-group">
                                                                            <span
                                                                                class="input-group-text">{{ currency() }}</span>
                                                                            <input type="number" class="form-control"
                                                                                name="selling_price" step="0.01"
                                                                                value="{{ $variant->selling_price }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label class="form-label">Discount Type</label>
                                                                        <select name="discount_type"
                                                                            class="form-select w-100" id=""
                                                                            required>
                                                                            <option value="" selected disabled>
                                                                                --Choose--</option>
                                                                            <option
                                                                                value="{{ \App\Enums\DiscountType::FLAT->value }}"
                                                                                {{ \App\Enums\DiscountType::FLAT->value == $variant->discount_type ? 'selected' : '' }}>
                                                                                {{ ucfirst(\App\Enums\DiscountType::FLAT->label()) }}
                                                                            </option>
                                                                            <option
                                                                                value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}"
                                                                                {{ \App\Enums\DiscountType::PERCENTAGE->value == $variant->discount_type ? 'selected' : '' }}>
                                                                                {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE->label()) }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3 col-md-6">
                                                                        <label class="form-label">Discount Value</label>
                                                                        <input name="discount_value" type="number"
                                                                            value="{{ $variant->discount_value }}"
                                                                            class="form-control" required>
                                                                    </div>
                                                                    <div class="mb-3 col-md-12">
                                                                        <label class="form-label">Low Stock
                                                                            Quantity</label>
                                                                        <input name="low_stock_quantity" type="number"
                                                                            value="{{ $variant->low_stock_quantity }}"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="mb-3 col-12">
                                                                        <label class="form-label">Image</label>
                                                                        <x-image-input name="image" :image="storage_url($variant->image)" />
                                                                    </div>

                                                                    <div class="mb-3 col-12">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" id="is_default"
                                                                                name="is_default" value="1"
                                                                                {{ $variant->is_default ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="is_default">
                                                                                Set this item as default variant
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light border"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit"
                                                                    class="btn btn-success">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- variant delete modal -->
                                            <div class="modal fade" id="deleteVariantModal{{ $loop->iteration }}"
                                                tabindex="-1" aria-labelledby="deleteModalLabel-{{ $variant->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteModalLabel-{{ $variant->id }}">
                                                                Confirm
                                                                Delete</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="text-center modal-body">
                                                            <div class="alert alert-warning d-flex" role="alert">
                                                                <i class="bi bi-exclamation-circle-fill me-2 text-danger"
                                                                    style="font-size: 1.5rem;"></i>
                                                                <p class="mt-1 text-secondary">
                                                                    Are you sure you want to delete this Product
                                                                    {{ $variant->id }}
                                                                    Variant?
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <form
                                                                action="{{ route('seller.productVariants.delete', $variant->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!empty($product->images))
                        <div class="card card-body">
                            <div class="product-gallery">
                                <h6 class="mb-2 text-muted fw-bold small">Gallery Images</h6>
                                <div class="row g-2">
                                    @foreach ($product->images as $image)
                                        <div class="col-4 col-sm-3 col-md-2">
                                            <img src="{{ storage_url($image->image) }}" alt="Gallery image"
                                                class="img-fluid border rounded"
                                                style="object-fit: cover; width: 100%; height: 100px;">
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    @endif

                    @if (!empty($product->description))
                        <div class="mt-4 shadow-sm card">
                            <div class="bg-white card-header">
                                <h5 class="mb-0 card-title">Product Description</h5>
                            </div>
                            <div class="card-body">
                                <div class="product-description">
                                    {!! $product->description !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <!-- Stock Status Card -->
                    <div class="mb-4 shadow-sm card">
                        <div class="bg-white card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Inventory Status</h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#stockUpdateModal">
                                    <i class="fas fa-plus-circle me-1"></i> Update Stock
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Header -->
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                        <h6 class="text-muted fw-bold small mb-2 mb-md-0">
                                            <i class="fas fa-boxes-stacked me-1 text-primary"></i> Stock History
                                        </h6>
                                        <div
                                            class="alert alert-light d-flex align-items-start gap-2 py-2 px-3 mb-0  border-4 border-secondary">
                                            <i class="fas fa-exclamation-triangle text-warning mt-1"></i>
                                            <div class="small text-dark">
                                                @if ($product->variants && $product->variants->count() > 0)
                                                    @foreach ($product->variants as $variant)
                                                        @php
                                                            $variantOptions = [];
                                                            foreach ($variant->option_values as $optionValue) {
                                                                $variantOptions[] =
                                                                    $optionValue->option->name .
                                                                    ': ' .
                                                                    $optionValue->value;
                                                            }
                                                            $variantStock =
                                                                ($variant->stock_in ?? 0) - ($variant->stock_out ?? 0);
                                                        @endphp
                                                        <div>
                                                            <strong>{{ implode(', ', $variantOptions) }}:</strong> In Stock
                                                            ({{ $variantStock }})
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div>
                                                        <strong>Main Product:</strong> In Stock
                                                        ({{ ($product->stock_in ?? 0) - ($product->stock_out ?? 0) }})
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered align-middle">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Variant</th>
                                                    <th>Quantity</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($product->stock_history as $history)
                                                    <tr>
                                                        <td class="text-nowrap">
                                                            {{ $history->created_at?->format('M d, Y h:i A') ?? '-' }}
                                                        </td>
                                                        <td>
                                                            @if ($history->variant)
                                                                @php
                                                                    $variantOptions = [];
                                                                    foreach (
                                                                        $history->variant->option_values
                                                                        as $optionValue
                                                                    ) {
                                                                        $variantOptions[] =
                                                                            $optionValue->option->name .
                                                                            ': ' .
                                                                            $optionValue->value;
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="badge bg-light text-dark text-wrap">{{ implode(', ', $variantOptions) }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">Main Product</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center fw-bold">
                                                            {{ abs($history->quantity ?? 0) }}
                                                        </td>
                                                        <td class="text-center">
                                                            @switch($history->type)
                                                                @case(\App\Enums\StockType::ADD_STOCK)
                                                                    <span class="badge bg-success">Added</span>
                                                                @break

                                                                @case(\App\Enums\StockType::REMOVE_STOCK)
                                                                    <span class="badge bg-danger">Removed</span>
                                                                @break

                                                                @case(\App\Enums\StockType::SET_EXACT_STOCK)
                                                                    <span class="badge bg-warning text-dark">Set Exact</span>
                                                                @break

                                                                @default
                                                                    <span class="badge bg-secondary">Unknown</span>
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted">No stock history
                                                                available</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- product delete modal -->
            <div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1"
                aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel-{{ $product->id }}">Confirm
                                Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="text-center modal-body">
                            <div class="alert alert-warning d-flex" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 text-danger" style="font-size: 1.5rem;"></i>
                                <p class="mt-1 text-secondary">
                                    Are you sure you want to delete this Product?
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('seller.products.delete', $product->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Update Modal -->
            <div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true" data-id="{{ $product->id }}">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('seller.products.stockUpdate', $product->id) }}" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Inventory</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Variants</label>
                                    <select class="form-select" id="variant" name="product_variant_id">
                                        <option value="">--Select Variant--</option>
                                        @foreach ($product->variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->sku }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Action</label>
                                    <select class="form-select" id="stockAction" name="stock_action">
                                        <option value="{{ \App\Enums\StockType::ADD_STOCK->value }}">
                                            {{ \App\Enums\StockType::ADD_STOCK->label() }}
                                        </option>
                                        <option value="{{ \App\Enums\StockType::REMOVE_STOCK->value }}">
                                            {{ \App\Enums\StockType::REMOVE_STOCK->label() }}
                                        </option>
                                        <option value="{{ \App\Enums\StockType::SET_EXACT_STOCK->value }}">
                                            {{ \App\Enums\StockType::SET_EXACT_STOCK->label() }}
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="stockQuantity" name="stock_quantity"
                                        min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Note (Optional)</label>
                                    <textarea class="form-control" id="stockNote" name="stock_note" rows="2"
                                        placeholder="Reason for this inventory change"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Variant Add Modal -->
            <div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Variant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="variantAlert"></div>
                            <form id="variantForm" action="{{ route('seller.productVariants.store', $product->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-12">
                                        <label class="form-label">SKU</label>
                                        <input type="text" class="form-control" name="sku" id="skuInput"
                                            placeholder="Enter SKU (Optional)" value="{{ strtoupper(uniqid()) }}">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Buying Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ currency() }}</span>
                                            <input type="number" class="form-control" name="buying_price" step="0.01"
                                                placeholder="Enter Price" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Selling Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ currency() }}</span>
                                            <input type="number" class="form-control" name="selling_price" step="0.01"
                                                placeholder="Enter Price" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Discount Type</label>
                                        <select name="discount_type" class="form-select w-100" id="" required>
                                            <option value="" selected disabled>--Choose--</option>
                                            <option value="{{ \App\Enums\DiscountType::FLAT->value }}">
                                                {{ ucfirst(\App\Enums\DiscountType::FLAT->label()) }}
                                            </option>
                                            <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">
                                                {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE->label()) }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Discount Value</label>
                                        <input name="discount_value" type="number" value="" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Low Stock Quantity</label>
                                        <input name="low_stock_quantity" type="number" value="" class="form-control">
                                    </div>
                                    <div>
                                        <label class="form-label">Options</label>
                                        @foreach ($product_options as $option)
                                            <div class="input-group mb-3 col-6">
                                                <label class="input-group-text">{{ $option->name }}</label>
                                                <select name="option_values[]" class="form-select">
                                                    <option value="">Choose...</option>
                                                    @foreach ($option->options as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mb-3 col-12">
                                        <label class="form-label">Image</label>
                                        <x-image-input name="image" />
                                    </div>

                                    <div class="mb-3 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_default"
                                                name="is_default" value="1">
                                            <label class="form-check-label" for="is_default">Set this item as default
                                                variant</label>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveVariant">Save Variant</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addOptionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('seller.options.store', $product->id) }}">
                            @csrf
                            <div class="modal-header bg-white text-dark">
                                <h5 class="modal-title" id="addOptionModalLabel">Add Product Option</h5>
                                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-4">
                                    <label for="attribute_name" class="form-label fw-bold">Select Existing Option</label>
                                    <select class="form-select" id="attribute_name" name="option_id">
                                        <option value="" disabled selected>Select an option</option>
                                        @foreach ($product_options as $option)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-center mb-3 fw-semibold text-muted">— or create new —</div>
                                <div class="mb-3">
                                    <label for="new_attribute_name" class="form-label fw-bold">New Option Name</label>
                                    <input type="text" class="form-control" id="new_attribute_name" name="name"
                                        placeholder="Enter new attribute name">
                                </div>
                                <div class="mb-3">
                                    <label for="attribute_value" class="form-label fw-bold">Value <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="attribute_value" name="value"
                                        placeholder="e.g., Red, XL" required>
                                </div>
                            </div>

                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            @push('scripts')
                <script>
                    $('#generateSkuBtn').on('click', function() {
                        let skuParts = [];

                        let productId = $('input[name="product_id"]').val();
                        if (productId) {
                            skuParts.push('PID' + productId);
                        }

                        $('select[name^="attributes"]').each(function() {
                            let name = $(this).attr('name').match(/\[(.*?)\]/)[1];
                            let value = $(this).val();
                            if (value) {
                                let formatted = name.toUpperCase().replace(/\s+/g, '') + '-' + value.toUpperCase()
                                    .replace(/\s+/g, '');
                                skuParts.push(formatted);
                            }
                        });

                        let timestamp = Date.now();
                        skuParts.push('TS' + timestamp);

                        let generatedSku = skuParts.join('_');

                        $('#skuInput').val(generatedSku);
                    });
                </script>
                <script>
                    $(document).ready(function() {
                        $('#attributeSelect').on('change', function() {
                            let attributeId = $(this).val();

                            if (attributeId) {
                                $.ajax({
                                    url: `/seller/products/get-options/${attributeId}`,
                                    type: 'GET',
                                    success: function(data) {
                                        $('#optionSelect').empty();
                                        $('#optionSelect').append(
                                            '<option disabled selected>Select Attribute Option</option>'
                                        );

                                        $.each(data, function(key, option) {
                                            $('#optionSelect').append(
                                                `<option value="${option.id}">${option.value}</option>`
                                            );
                                        });
                                    }
                                });
                            } else {
                                $('#optionSelect').empty();
                                $('#optionSelect').append('<option disabled selected>Select Attribute Option</option>');
                            }
                        });
                    });
                </script>
                <script>
                    $('#saveVariant').click(function(e) {
                        e.preventDefault();

                        let form = $('#variantForm')[0];
                        let formData = new FormData(form);

                        $('#variantAlert').html('');
                        $('#saveVariant').attr('disabled', true).text('Saving...');

                        $.ajax({
                            url: "{{ route('seller.productVariants.store', $product->id) }}",
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#variantAlert').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Variant added successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);

                                setTimeout(function() {
                                    $('#addVariantModal').modal('hide');
                                    location.reload();
                                }, 1000);
                            },
                            error: function(xhr) {
                                $('#saveVariant').attr('disabled', false).text('Save Variant');

                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let messages = Object.values(errors).map(item => `<div>${item[0]}</div>`).join(
                                        '');
                                    $('#variantAlert').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${messages}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                                } else {
                                    $('#variantAlert').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Something went wrong. Please try again.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                                }
                            }
                        });
                    });
                </script>
            @endpush

        @endsection
