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

<?php
$variantSkuList = [];
foreach ($products as $product) {
    foreach ($product->variants as $variant) {
        $variantSkuList[] = [
            'variant_id' => $variant->id,
            'sku' => $variant->sku,
            'product_id' => $product->id,
        ];
    }
}
?>

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
                                    <input type="text" id="skuSearch" class="form-control">
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
                                <div class="col-lg-4 col-sm-6 col-12 mb-2 product-card-wrapper"
                                    data-product-id="{{ $product->id }}">
                                    <div class="card product-card h-100" role="button" data-bs-toggle="modal"
                                        data-bs-target="#variantModal-{{ $product->id }}">
                                        <div class="d-flex align-items-center p-2">
                                            <div style="width: 60px; height: 60px; flex-shrink: 0;">
                                                <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                                    class="img-fluid rounded"
                                                    style="object-fit: cover; width: 100%; height: 100%;">
                                            </div>
                                            <div class="ms-2 flex-grow-1 overflow-hidden">
                                                <h6 class="mb-1 text-truncate" title="{{ $product->name }}">
                                                    {{ $product->name }}
                                                </h6>
                                                <small class="text-muted">{{ $product->variants->count() }} variants</small>
                                            </div>
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
                                                                    <td class="small">
                                                                        {{ $variant->stock_in - $variant->stock_out }}</td>
                                                                    <td class="small">
                                                                        {{ money($variant->discounted_price ?? $variant->selling_price) }}
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
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Current Order</h5>
                        <button id="clearCartBtn" class="border btn btn-sm btn-danger" data-bs-toggle="modal"
                            data-bs-target="#clearCartModal">
                            <i class="bi bi-trash me-1"></i> Clear Cart
                        </button>
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
                                    <i class="bi bi-cart me-2"></i>Checkout
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
            var variantSkuList = @json($variantSkuList);

            $(document).ready(function() {
                $('#skuSearch').on('input', function() {
                    var query = $(this).val().trim();

                    if (query === "") {
                        $('.product-card-wrapper').show();
                        return;
                    }

                    var matchedVariant = variantSkuList.find(v => v.sku.toLowerCase() === query.toLowerCase());

                    $('.product-card-wrapper').hide();

                    if (matchedVariant) {
                        $('[data-product-id="' + matchedVariant.product_id + '"]').show();

                        addToCart(matchedVariant.variant_id, 1);

                        $('#skuSearch').val('');
                        $('.product-card-wrapper').show();
                    }
                });

                function addToCart(variantId, quantity) {
                    $.ajax({
                        url: "{{ route('seller.pos.cart_add') }}",
                        method: 'POST',
                        data: {
                            variant_id: variantId,
                            quantity: quantity,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                $('.order-items tbody').html(response.data.html);
                                summery = response.data;
                                resetOrderSummary(summery);
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON?.message || "Something went wrong";
                            alert(message);
                        }
                    });
                }


                $(document).on('click', '.update-qty-btn', function() {
                    var itemId = $(this).data('id');
                    var action = $(this).data('action');

                    $.ajax({
                        url: "{{ route('seller.pos.cart_update') }}",
                        method: 'POST',
                        data: {
                            id: itemId,
                            action: action,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                $('.order-items tbody').html(response.data.html);
                                summery = response.data;
                                resetOrderSummary(summery);
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            alert(message);
                        }
                    });
                });


                let deleteCartItemId = null;


                $(document).on('click', '.delete-cart-item-btn', function() {
                    deleteCartItemId = $(this).data('id');
                });

                $('#confirmDeleteBtn').on('click', function() {
                    if (!deleteCartItemId) return;

                    $.ajax({
                        url: "{{ route('seller.pos.remove_cart_item') }}",
                        method: 'POST',
                        data: {
                            id: deleteCartItemId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {

                                $('.cart-item-' + deleteCartItemId).remove();
                                summery = response.data;
                                resetOrderSummary(summery);

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
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            alert(message);
                        }
                    });
                });

                $('#confirmClearCartBtn').on('click', function() {
                    $.ajax({
                        url: "{{ route('seller.pos.cart_clear') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                alert(response.message);
                                $('.order-items tbody').html(`
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No items in cart</td>
                                    </tr>
                                `);

                                summery = response;
                                resetOrderSummary(summery);
                                const clearModalEl = document.getElementById('clearCartModal');
                                const modal = bootstrap.Modal.getInstance(clearModalEl);
                                modal.hide();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            alert(message);
                        }
                    });
                });

                $('#placeOrderBtn').on('click', function() {
                    $.ajax({
                        url: "{{ route('seller.pos.place_order') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                $('.order-items tbody').html(`
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No items in cart</td>
                                    </tr>
                                `);

                                summery = response;
                                resetOrderSummary(summery);

                                if (response.data.invoice_id) {
                                    let receiptUrl = "{{ route('receipt', ':invoice_id') }}"
                                        .replace(':invoice_id', response.data.invoice_id);
                                    $('<a>', {
                                        href: receiptUrl,
                                        target: '_blank'
                                    })[0].click();
                                }

                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            alert(message);
                        }
                    });
                });

                function resetOrderSummary(summery) {
                    $('#summary-subtotal').text(summery.subtotal || "{{ money(0) }}");
                    $('#summary-vat').text(summery.vat_amount || "{{ money(0) }}");
                    $('#summary-discount').text(summery.discount || "{{ money(0) }}");
                    $('#summary-total').text(summery.total || "{{ money(0) }}");
                }
            });
        </script>
    @endpush

@endsection
