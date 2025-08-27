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
                                    <input type="text" class="form-control" placeholder="Search products..."
                                        aria-label="Search products">
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
                                            <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
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
                                                            @foreach ($product->variants as $variant)
                                                                <tr>
                                                                    <td class="fw-bold small">{{ $variant->fullName }}</td>
                                                                    <td class="small">{{ $variant->sku }}</td>
                                                                    <td class="small">{{ $variant->stock_in - $variant->stock_out }}</td>
                                                                    <td class="small">{{ money($variant->discounted_price ?? $variant->selling_price) }}
                                                                    </td>
                                                                    <td>
                                                                        <button
                                                                            class="btn btn-sm btn-primary d-flex align-items-center add-to-cart-btn"
                                                                            data-variant-id="{{ $variant->id }}"
                                                                            data-quantity="1">
                                                                            <i class="bi bi-plus"></i> Add
                                                                        </button>
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
                        <div class="order-items" style="max-height: 450px; overflow-y: auto;">
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
                                    @include('components.seller.pos-cart-items', [
                                        'cartItems' => $cartItems,
                                    ])
                                </tbody>
                            </table>
                        </div>

                        <!-- Order Summary -->
                        <div class="p-3 border-top">
                            <div class="d-flex justify-content-between mb-1 small">
                                <span>Subtotal:</span>
                                <span id="summary-subtotal">{{ money($subtotal) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span>Vat:</span>
                                <span id="summary-vat">{{ money($vat_amount) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 small">
                                <span>Discount:</span>
                                <span id="summary-discount">{{ money($discount) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 fw-bold">
                                <span>Total:</span>
                                <span id="summary-total">{{ money($total) }}</span>
                            </div>

                            <!-- Payment Buttons -->
                            <div class="d-grid gap-2">
                                <button id="placeOrderBtn" class="btn btn-success">
                                    <i class="bi bi-cash me-2"></i>Cash Payment
                                </button>
                                <button class="btn btn-primary">
                                    <i class="bi bi-credit-card me-2"></i>Card Payment
                                </button>
                                <button id="clearCartBtn" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#clearCartModal">
                                    <i class="bi bi-trash me-2"></i> Clear Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this item from the cart?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clearCartModalLabel">Confirm Clear Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to clear the entire cart? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmClearCartBtn">Yes, Clear Cart</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.add-to-cart-btn').on('click', function() {
                    var variantId = $(this).data('variant-id');
                    var quantity = $(this).data('quantity');

                    $.ajax({
                        url: '{{ route('seller.pos.cart_add') }}',
                        method: 'POST',
                        data: {
                            variant_id: variantId,
                            quantity: quantity,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.order-items tbody').html(response.html);
                                $('#summary-subtotal').text(response.subtotal);
                                $('#summary-vat').text(response.vat_amount);
                                $('#summary-discount').text(response.discount);
                                $('#summary-total').text(response.total);
                            } else {
                                alert("An error occurred. Please try again.")
                            }
                        },
                        error: function(xhr) {
                            alert(xhr)
                        }
                    });
                });
            });

            $(document).on('click', '.update-qty-btn', function() {
                var itemId = $(this).data('id');
                var action = $(this).data('action');

                $.ajax({
                    url: '{{ route('seller.pos.cart_update') }}',
                    method: 'POST',
                    data: {
                        id: itemId,
                        action: action,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.order-items tbody').html(response.html);
                            $('#summary-subtotal').text(response.subtotal);
                            $('#summary-vat').text(response.vat_amount);
                            $('#summary-discount').text(response.discount);
                            $('#summary-total').text(response.total);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                });
            });


            let deleteCartItemId = null;

            $(document).ready(function() {
                $(document).on('click', '.delete-cart-item-btn', function() {
                    deleteCartItemId = $(this).data('id');
                });

                $('#confirmDeleteBtn').on('click', function() {
                    if (!deleteCartItemId) return;

                    $.ajax({
                        url: '{{ route('seller.pos.remove_cart_item') }}',
                        method: 'POST',
                        data: {
                            id: deleteCartItemId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {

                                $('.cart-item-' + deleteCartItemId).remove();
                                $('#summary-subtotal').text(response.subtotal);
                                $('#summary-vat').text(response.vat_amount);
                                $('#summary-discount').text(response.discount);
                                $('#summary-total').text(response.total);

                                const deleteModalEl = document.getElementById('deleteConfirmModal');
                                const modal = bootstrap.Modal.getInstance(deleteModalEl);
                                modal.hide();

                                deleteCartItemId = null;

                                if ($('.order-items tbody tr').length === 0) {
                                    $('.order-items tbody').html(`
                            <tr>
                                <td colspan="4" class="text-center text-muted">No items in cart</td>
                            </tr>
                        `);
                                }
                            } else {
                                alert('Failed to delete item.');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('An error occurred.');
                        }
                    });
                });
            });

            $(document).ready(function() {
                $('#confirmClearCartBtn').on('click', function() {
                    $.ajax({
                        url: '{{ route('seller.pos.cart_clear') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.order-items tbody').html(response.html);
                                $('#summary-subtotal').text(response.subtotal);
                                $('#summary-vat').text(response.vat_amount);
                                $('#summary-discount').text(response.discount);
                                $('#summary-total').text(response.total);

                                const clearModalEl = document.getElementById('clearCartModal');
                                const modal = bootstrap.Modal.getInstance(clearModalEl);
                                modal.hide();
                            } else {
                                alert('Failed to clear cart.');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('An error occurred while clearing the cart.');
                        }
                    });
                });
            });

            $(document).ready(function() {
                $('#placeOrderBtn').on('click', function() {
                    $.ajax({
                        url: '{{ route('seller.pos.place_order') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                               

                                $('.order-items tbody').html(`
                        <tr>
                            <td colspan="4" class="text-center text-muted">No items in cart</td>
                        </tr>
                    `);

                                $('#summary-subtotal').text('0.00');
                                $('#summary-vat').text('0.00');
                                $('#summary-discount').text('0.00');
                                $('#summary-total').text('0.00');

                            } else {
                                alert('Failed to place order.');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('An error occurred while placing the order.');
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
