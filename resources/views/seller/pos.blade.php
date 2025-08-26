@extends('seller.layouts.app')
@section('title', 'POS')

<style>
    .order-items table td {
        padding: 0.5rem;
    }

    .hover-card {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }
</style>

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Products/Search Section -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0">Products</h4>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Search products..." aria-label="Search products">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap mb-3">
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2">All</button>
                        @foreach ($categories as $category)
                        <button class="btn btn-outline-secondary btn-sm me-2 mb-2">{{ $category->name }}</button>
                        @endforeach
                    </div>

                    <div class="row">
                        @foreach ($products as $product)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mb-3" role="button">
                            <div class="card product-card border-0 shadow-sm h-100 text-center"
                                data-bs-toggle="modal" data-bs-target="#variantModal-{{ $product->id }}">
                                <div class="ratio ratio-1x1">
                                    <img src="{{ storage_url($product->thumbnail) }}"
                                        alt="{{ $product->name }}"
                                        class="w-100 h-100"
                                        style="object-fit: cover; border-top-left-radius:.5rem; border-top-right-radius:.5rem;">
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title text-truncate mb-1" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ $product->variants->count() }} variants
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="variantModal-{{ $product->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ $product->name }} – Variants</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Variant</th>
                                                        <th>SKU</th>
                                                        <th>Stock</th>
                                                        <th>Price</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($product->variants as $variant)
                                                    <tr>
                                                        <td class="fw-bold">{{ $variant->fullName }}</td>
                                                        <td>{{ $variant->sku }}</td>
                                                        <td>{{ $variant->stock_in - $variant->stock_out }}</td>
                                                        <td>{{ money($variant->selling_price) }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> Add</button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart & Checkout Section -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header py-3">
                    <h5 class="mb-0">Current Order</h5>
                </div>
                <div class="card-body p-0">
                    <!-- Customer Info -->
                    <div class="p-3 border-bottom">
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text">Customer</span>
                            <input type="text" class="form-control" placeholder="Search customer...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="taxableSwitch">
                            <label class="form-check-label small" for="taxableSwitch">Taxable</label>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead class="small">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="small">Product Name</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-secondary">-</button>
                                            <button class="btn btn-outline-secondary disabled">1</button>
                                            <button class="btn btn-outline-secondary">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end">$19.99</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-link text-danger p-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="small">Another Product</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-secondary">-</button>
                                            <button class="btn btn-outline-secondary disabled">2</button>
                                            <button class="btn btn-outline-secondary">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end">$49.98</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-link text-danger p-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Summary -->
                    <div class="p-3 border-top">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span>Subtotal:</span>
                            <span>$69.97</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small">
                            <span>Tax (8%):</span>
                            <span>$5.60</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small">
                            <span>Discount:</span>
                            <span>$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 fw-bold">
                            <span>Total:</span>
                            <span>$75.57</span>
                        </div>

                        <!-- Payment Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-success">
                                <i class="fas fa-money-bill-wave me-2"></i>Cash Payment
                            </button>
                            <button class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i>Card Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection