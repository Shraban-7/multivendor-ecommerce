@extends('seller.layouts.app')
@section('title', 'Product Details')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="m-0 text-dark">{{ $product->name }}</h4>
                    <ol class="mt-1 breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Product Details</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-end">

                    <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Product
                    </a>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-1"></i> All Products
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
                    <div class="shadow-sm card">
                        <div class="bg-white card-header d-flex justify-content-between">
                            <h5 class="mb-0 card-title">Product Overview</h5>
                            <button class="btn btn-outline-danger btn-sm" title="Delete" data-bs-toggle="modal"
                                data-bs-target="#deleteModal-{{ $product->id }}">
                                <i data-feather="trash-2" class="icon-xs"></i> Delete
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Product Images -->
                                <div class="col-md-5">
                                    <div class="mb-3 product-primary-image">
                                        <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                            class="border rounded shadow-sm img-fluid"
                                            style="width: 100%; height: 250px; object-fit: contain;">
                                    </div>
                                    @if (count($product->images) > 0)
                                        <div class="product-gallery">
                                            <h6 class="mb-2 text-muted fw-bold small">Gallery Images</h6>
                                            <div class="row g-2">
                                                @foreach ($product->images as $image)
                                                    <div class="col-4">
                                                        <a href="{{ storage_url($image->image) }}"
                                                            data-lightbox="product-gallery"
                                                            data-title="{{ $product->name }}">
                                                            <img src="{{ storage_url($image->image) }}" alt="Gallery image"
                                                                class="border rounded img-fluid"
                                                                style="height: 80px; object-fit: cover; width: 100%;">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Product Details -->
                                <div class="col-md-7">
                                    <div class="mb-3 d-flex align-items-center">
                                        <h4 class="mb-0 me-2">{{ $product->name }}</h4>
                                        <span class="product-id text-muted small">(ID: {{ $product->id }})</span>
                                    </div>

                                    <div class="mb-3">
                                        <span
                                            class="px-3 py-2 badge bg-info rounded-pill">{{ $product->category->name }}</span>
                                        @if ($product->subcategory)
                                            <span
                                                class="px-3 py-2 badge bg-secondary rounded-pill">{{ $product->subcategory->name }}</span>
                                        @endif
                                        <span
                                            class="px-3 py-2 badge bg-primary rounded-pill">{{ $product->brand?->name }}</span>
                                    </div>

                                    <table class="table mb-0 table-sm product-info">
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold pe-3" style="width: 120px;">SKU</td>
                                                <td>{{ $product->sku }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold pe-3">Buying Price</td>
                                                <td>{{ money($product->buying_price, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold pe-3">Selling Price</td>
                                                <td>{{ money($product->selling_price) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold pe-3">Profit Margin</td>
                                                <td>
                                                    @php
                                                        $margin = $product->selling_price - $product->buying_price;
                                                        $marginPercent =
                                                            $product->buying_price > 0
                                                                ? ($margin / $product->buying_price) * 100
                                                                : 0;
                                                    @endphp
                                                    {{ money($margin) }}
                                                    <span
                                                        class="badge {{ $marginPercent >= 30 ? 'bg-success' : ($marginPercent >= 15 ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ number_format($marginPercent, 1) }}%
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold pe-3">Current Stock</td>
                                                <td>
                                                    <span
                                                        class="fw-bold {{ $product->stock_in > 20 ? 'text-success' : ($product->stock_in > 5 ? 'text-warning' : 'text-danger') }}">
                                                        {{ $product->stock_in }} units
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold pe-3">Created On</td>
                                                <td>{{ $product->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold pe-3">Last Updated</td>
                                                <td>{{ $product->updated_at->format('M d, Y') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Description Card (if applicable) -->
                    @if ($product->description)
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

                <!-- Right Column - Stats & Actions -->
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
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-3 text-muted fw-bold small">STOCK HISTORY</h6>
                                        <div class="d-flex align-items-center " >
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>In Stock ({{ $product->stock_in }} )</div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($stockHistory) && count($stockHistory) > 0)
                                                    @foreach ($stockHistory as $history)
                                                        <tr>
                                                            <td>{{ $history->created_at->format('M d, Y h:i A') }}</td>
                                                            <td class="text-center">{{ abs($history->quantity) }}</td>
                                                            <td class="text-center">
                                                                @if ($history->type == \App\Enums\StockType::ADD_STOCK)
                                                                    <span class="badge bg-success">Added</span>
                                                                @elseif ($history->type == \App\Enums\StockType::REMOVE_STOCK)
                                                                    <span class="badge bg-danger">Removed</span>
                                                                @elseif ($history->type == \App\Enums\StockType::SET_EXACT_STOCK)
                                                                    <span class="badge bg-warning">Set Exact Stock</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="3" class="text-center">No stock history available
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales & Performance Card -->
                    {{-- <div class="mb-4 shadow-sm card">
                        <div class="bg-white card-header">
                            <h5 class="mb-0 card-title">Sales Performance</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 text-center border rounded">
                                        <div class="text-muted small">Units Sold</div>
                                        <div class="fs-4 fw-bold">{{ $sold }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 text-center border rounded">
                                        <div class="text-muted small">Revenue</div>
                                        <div class="fs-4 fw-bold">{{ currency($revenue) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 text-center border rounded">
                                        <div class="text-muted small">Profit</div>
                                        <div class="fs-4 fw-bold">{{ currency($profit) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 text-center border rounded">
                                        <div class="text-muted small">Last Sale</div>
                                        <div class="fs-6 fw-bold">
                                            {{ $last_sale ? $last_sale->format('d-m-y h:i:A') : 'No sales yet' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
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
                <div class="modal-header">
                    <h5 class="modal-title">Update Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="stockUpdateForm">
                        <div class="mb-3">
                            <label class="form-label">Current Stock</label>
                            <input type="text" class="form-control-plaintext" readonly
                                value="{{ $product->stock_in }} units">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Action</label>
                            <select class="form-select" id="stockAction" name="stock_action">
                                <option value="{{ \App\Enums\StockType::ADD_STOCK->value }}">
                                    {{ \App\Enums\StockType::ADD_STOCK->label() }}</option>
                                <option value="{{ \App\Enums\StockType::REMOVE_STOCK->value }}">
                                    {{ \App\Enums\StockType::REMOVE_STOCK->label() }}</option>
                                <option value="{{ \App\Enums\StockType::SET_EXACT_STOCK->value }}">
                                    {{ \App\Enums\StockType::SET_EXACT_STOCK->label() }}</option>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveStockUpdate">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#saveStockUpdate').click(function(e) {
                e.preventDefault();

                var productId = $('#stockUpdateModal').data('id');
                var stockAction = $('#stockAction').val();
                var stockQuantity = $('#stockQuantity').val();
                var stockNote = $('#stockNote').val();

                if (stockQuantity <= 0) {
                    alert("Please enter a valid quantity.");
                    return;
                }

                var data = {
                    product_id: productId,
                    stock_action: stockAction,
                    stock_quantity: stockQuantity,
                    stock_note: stockNote,
                };

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('seller.products.stockUpdate', $product->id) }}',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status) {
                            $('#stockUpdateModal').modal('hide');
                            location.reload();
                        } else {
                            $('#stockUpdateModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#stockUpdateModal').modal('hide');
                        location.reload();
                        console.error("Error: " + error);
                    }
                });
            });
        </script>
    @endpush

@endsection
