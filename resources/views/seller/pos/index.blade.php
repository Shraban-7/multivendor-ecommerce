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

    #product-row {
        max-height: calc(100vh - 250px);
        overflow-y: scroll;
    }

    .dropdown-menu {
        display: none;
        max-height: 200px;
        overflow-y: auto;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
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
        $product->total_stock += $variant->availableStock;
    }
}
$products = $products->sortByDesc('total_stock');
?>

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Products/Search Section -->
            <div class="col-md-8">
                <div class="card mb-4 ">
                    <div class="card-header bg-white py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-0">Products</h4>
                            </div>
                            <div class="col-md-4 d-flex justify-content-end gap-2">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="skuSearch" class="form-control" placeholder="Barcode/SKU">
                                </div>
                                <button id="sales" class="border btn btn-sm btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#salesModal">
                                    Sales
                                </button>
                            </div>
                        </div>

                        <div class="modal fade" id="salesModal" tabindex="-1" aria-labelledby="salesModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="salesModalLabel">Today's Sales</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body" id="salesContent">
                                        @if ($orders->isEmpty())
                                            <p class="text-center text-muted">No sales today.</p>
                                        @else
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice Id</th>
                                                        <th>Customer</th>
                                                        <th>Total</th>
                                                        <th>Time</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orders as $index => $order)
                                                        <tr>
                                                            <td>{{ $order->invoice_id }}</td>
                                                            <td>{{ $order->customer->name ?? '' }}</td>
                                                            <td>{{ money($order->total) }}</td>
                                                            <td>{{ $order->created_at->format('h:i A') }}</td>
                                                            <td>
                                                                <div class="d-flex gap-1 overflow-auto">
                                                                    <a href="{{ route('seller.orders.details', $order->invoice_id) }}"
                                                                        target="__blank"
                                                                        class="btn btn-light border btn-sm d-flex align-items-center">
                                                                        <i data-feather="clipboard"
                                                                            class="icon-xs me-1"></i> Details
                                                                    </a>
                                                                    <a href="{{ route('seller.pos.index', ['order_id' => $order->id]) }}"
                                                                        target="__blank"
                                                                        class="btn btn-light border btn-sm d-flex align-items-center">
                                                                        <i data-feather="edit" class="icon-xs me-1"></i>
                                                                        Edit
                                                                    </a>
                                                                    <a href="{{ route('invoice', $order->invoice_id) }}"
                                                                        target="_blank"
                                                                        class="btn btn-light border btn-sm d-flex align-items-center">
                                                                        <i data-feather="download" class="icon-xs me-1"></i>
                                                                        Invoice
                                                                    </a>
                                                                    <a href="{{ route('receipt', $order->invoice_id) }}"
                                                                        target="_blank"
                                                                        class="btn btn-light border btn-sm d-flex align-items-center">
                                                                        <i data-feather="printer" class="icon-xs me-1"></i>
                                                                        Receipt
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap mb-3 category-filters">
                            <button class="btn btn-outline-primary btn-sm me-2 mb-2 filter-btn active" data-category="all">
                                All
                            </button>
                            @foreach ($categories as $category)
                                <button class="btn btn-outline-secondary btn-sm me-2 mb-2 filter-btn"
                                    data-category="{{ $category->id }}">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>

                        <div class="row row-cols-2 row-cols-lg-5 g-2 bg-light py-2 product-list" id="product-row">
                            @foreach ($products as $product)
                                <div class="col product-card-wrapper" data-product-id="{{ $product->id }}"
                                    data-category="{{ $product->category_id }}">
                                    <div class="card product-card h-100" role="button" data-bs-toggle="modal"
                                        data-bs-target="#variantModal-{{ $product->id }}">
                                        <div class="d-flex p-2">
                                            <div style="width: 48px; height: 48px; flex-shrink: 0;">
                                                <img src="{{ storage_url($product->thumbnail) }}"
                                                    alt="{{ $product->name }}" class="img-fluid rounded"
                                                    style="object-fit: cover; width: 100%; height: 100%;">
                                            </div>
                                            <div class="ms-2 flex-grow-1 overflow-hidden">
                                                <h6 class="mb-1 text-truncate" title="{{ $product->name }}">
                                                    {{ $product->name }}
                                                </h6>
                                                <div class="small">
                                                    <span class="text-muted">{{ $product->variants->count() }}
                                                        variants</span>
                                                    @if ($product->total_stock > 0)
                                                        <span class="text-muted stock">({{ $product->total_stock }}
                                                            {{ $product->unit->short_name }})</span>
                                                    @else
                                                        <span class="text-danger">(stock out)</span>
                                                    @endif
                                                </div>
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
                                                                    <td class="fw-bold small">{{ $variant->fullName }}
                                                                    </td>
                                                                    <td class="small">{{ $variant->sku }}</td>
                                                                    <td class="small">
                                                                        {{ $variant->availableStock }}</td>
                                                                    <td class="small">
                                                                        {{ money($variant->discounted_price ?? $variant->selling_price) }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($variant->availableStock > 0)
                                                                            <button
                                                                                class="btn btn-sm btn-primary add-to-cart-btn"
                                                                                data-variant-id="{{ $variant->id }}"
                                                                                data-quantity="1">
                                                                                <span class="btn-text"><i
                                                                                        class="bi bi-plus"></i> Add</span>
                                                                                <span
                                                                                    class="spinner-border spinner-border-sm d-none"
                                                                                    role="status"
                                                                                    aria-hidden="true"></span>
                                                                            </button>
                                                                        @else
                                                                            <button
                                                                                class="btn btn-sm btn-secondary disabled">Out
                                                                                of stock </button>
                                                                        @endif
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
                            <h5>Customer</h5>
                            <div class="row">
                                <div class="col-md-6 mb-2 position-relative">
                                    <input type="text" autocomplete="off" class="form-control form-control-sm"
                                        name="customer_name" id="customerName" placeholder="Name">
                                    <div class="dropdown-menu w-100" id="customerNameDropdown"></div>
                                </div>
                                <div class="col-md-6 mb-2 position-relative">
                                    <input type="text" autocomplete="off" class="form-control form-control-sm"
                                        name="customer_phone" id="customerPhone" placeholder="Phone">
                                    <div class="dropdown-menu w-100" id="customerPhoneDropdown"></div>
                                </div>
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
                                    @if (request()->has('order_id'))
                                        @include('components.seller.pos-order-items', [
                                            'orderItems' => $orderItems,
                                        ])
                                    @else
                                        @include('components.seller.pos-cart-items', [
                                            'cartItems' => $cartItems,
                                        ])
                                    @endif
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
                                @if (request()->has('order_id'))
                                    <button id="updateOrderBtn" class="btn btn-success">
                                        <i class="bi bi-arrow-repeat me-2"></i> Update Order
                                    </button>
                                @else
                                    <button id="placeOrderBtn" class="btn btn-success">
                                        <i class="bi bi-cart me-2"></i> Checkout
                                    </button>
                                @endif
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

    <div class="modal fade" id="deleteOrderConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this item from the order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteOrderBtn">Delete</button>
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
            const orderId = "{{ request('order_id', 0) }}";

            var variantSkuList = @json($variantSkuList);

            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    const context = this;
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(context, args), delay);
                };
            }

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

                $(document).on('click', '.add-to-cart-btn', debounce(function() {
                    let button = $(this);
                    let variantId = $(this).data('variant-id');
                    let quantity = $(this).data('quantity') || 1;

                    addToCart(variantId, quantity, orderId, button);
                }, 1000));

                function addToCart(variantId, quantity, orderId = 0, button) {
                    let url = orderId && orderId > 0 ?
                        "{{ route('seller.pos.sales.item_add') }}" :
                        "{{ route('seller.pos.cart_add') }}";

                    let btnText = button.find('.btn-text');
                    let spinner = button.find('.spinner-border');
                    btnText.addClass('d-none');
                    spinner.removeClass('d-none');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            variant_id: variantId,
                            quantity: quantity,
                            order_id: orderId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                toastr.success("Item added successfully!");

                                if (orderId && orderId > 0) {
                                    $('.order-items tbody').html(response.data.html);
                                } else {
                                    $('.order-items tbody').html(response.data.html);
                                }

                                resetOrderSummary(response.data);

                                if (typeof feather !== 'undefined') {
                                    feather.replace();
                                }
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || "Something went wrong");
                        },
                        complete: function() {
                            btnText.removeClass('d-none');
                            spinner.addClass('d-none');
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
                                toastr.success("Cart update successfully!");
                                $('.order-items tbody').html(response.data.html);
                                summery = response.data;
                                resetOrderSummary(summery);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            toastr.error(message);
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
                                toastr.success("Cart item deleted successfully!");
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
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            toastr.error(message);
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
                                toastr.success(response.message);
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
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            toastr.error(message);
                        }
                    });
                });

                $('#placeOrderBtn').on('click', function() {
                    let button = $(this);
                    let originalText = button.text();
                    let name = $('#customerName').val().trim();
                    let phone = $('#customerPhone').val().trim();

                    if (name && !phone) {
                        toastr.error("Phone is required when Name is provided.");
                        return;
                    }
                    if (phone && !name) {
                        toastr.error("Name is required when Phone is provided.");
                        return;
                    }

                    button.prop('disabled', true).text('Processing...');

                    $.ajax({
                        url: "{{ route('seller.pos.place_order') }}",
                        method: 'POST',
                        data: {
                            customer_name: name,
                            customer_phone: phone,
                            new_customer: !customerExists,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                button.prop('disabled', false).text(originalText);
                                toastr.success("Order placed successfully!");
                                $('#customerName').val('');
                                $('#customerPhone').val('');
                                customerExists = false

                                $('.order-items tbody').html(`
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No items in cart</td>
                                    </tr>
                                `);

                                summery = response;
                                resetOrderSummary(summery);

                                response.data.variants.forEach(v => {
                                    $(`[data-variant-id="${v.id}"]`)
                                        .closest("tr")
                                        .find("td:nth-child(3)")
                                        .text(v.availableStock);
                                    if (v.availableStock <= 0) {
                                        $(`[data-variant-id="${v.id}"]`)
                                            .replaceWith(
                                                '<button class="btn btn-sm btn-secondary disabled">Out of stock</button>'
                                            );
                                    }
                                });

                                if (response.data.invoice_id) {
                                    let receiptUrl = "{{ route('receipt', ':invoice_id') }}"
                                        .replace(':invoice_id', response.data.invoice_id);
                                    $('<a>', {
                                        href: receiptUrl,
                                        target: '_blank'
                                    })[0].click();
                                }

                                $('#customerForm')[0].reset();



                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            var message = xhr.responseJSON.message;
                            toastr.error(message);
                        }
                    });
                });

                $(document).on('click', '.update-order-qty-btn', debounce(function() {
                    let itemId = $(this).data('id');
                    let action = $(this).data('action');

                    $.ajax({
                        url: "{{ route('seller.pos.sales.item_update') }}",
                        method: 'POST',
                        data: {
                            id: itemId,
                            action: action,
                            order_id: "{{ request('order_id') }}",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                toastr.success("Order item updated successfully!");
                                $('.order-items tbody').html(response.data.html);
                                resetOrderSummary(response.data);

                                if (typeof feather !== 'undefined') {
                                    feather.replace();
                                }
                            } else {
                                toastr.error(response.message);
                            }
                        },

                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || "Something went wrong");
                        }
                    });
                }, 500));

                let deleteOrderItemId = null;

                $(document).on('click', '.delete-order-item-btn', function() {
                    deleteOrderItemId = $(this).data('id');
                });

                $('#confirmDeleteOrderBtn').on('click', function() {
                    if (!deleteOrderItemId) return;

                    $.ajax({
                        url: "{{ route('seller.pos.sales.item_remove') }}",
                        method: 'POST',
                        data: {
                            id: deleteOrderItemId,
                            order_id: "{{ request('order_id') }}",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                toastr.success("Order item removed successfully!");
                                $('.order-item-' + deleteOrderItemId).remove();
                                summery = response.data;
                                resetOrderSummary(summery);

                                const deleteModalEl = document.getElementById(
                                    'deleteOrderConfirmModal');
                                const modal = bootstrap.Modal.getInstance(deleteModalEl);
                                modal.hide();

                                deleteOrderItemId = null;

                                if (response.data && response.data.redirect) {
                                    window.location.href = response.data.redirect;
                                }

                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || "Something went wrong");
                        }
                    });
                });

                $('#updateOrderBtn').on('click', function() {
                    let button = $(this);
                    let originalText = button.text();
                    button.prop('disabled', true).text('Processing...');
                    $.ajax({
                        url: "{{ route('seller.pos.sales.update') }}",
                        method: 'POST',
                        data: {
                            order_id: "{{ request('order_id') }}",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status) {
                                button.prop('disabled', false).text(originalText);
                                toastr.success("Order updated successfully!");

                                $('.order-items tbody').html(response.data.html);
                                resetOrderSummary(response.data);

                                response.data.variants.forEach(v => {
                                    $(`[data-variant-id="${v.id}"]`).closest('tr')
                                        .find('td:nth-child(3)').text(v.availableStock);
                                    if (v.availableStock <= 0) {
                                        $(`[data-variant-id="${v.id}"]`).replaceWith(
                                            '<button class="btn btn-sm btn-secondary disabled">Out of stock</button>'
                                        );
                                    }
                                });

                                if (response.data.invoice_id) {
                                    let receiptUrl = "{{ route('receipt', ':invoice_id') }}"
                                        .replace(':invoice_id', response.data.invoice_id);
                                    let receiptWindow = window.open(receiptUrl, "_blank",
                                        "width=800,height=600");

                                    let timer = setInterval(function() {
                                        if (receiptWindow.closed) {
                                            clearInterval(timer);
                                            location.reload();
                                        }
                                    }, 500);
                                }
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || "Something went wrong");
                        }
                    });
                });

                var customerExists = false;

                function setupDropdown($input, $dropdown, type) {
                    var fetchCustomers = debounce(function() {
                        var val = $input.val().trim();
                        $dropdown.empty().removeClass('show');

                        if (val.length < 2) {
                            customerExists = false;
                            return;
                        }

                        $.ajax({
                            url: "{{ route('seller.pos.customers.search') }}",
                            data: {
                                term: val
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (!data.length) return;

                                $.each(data, function(i, c) {
                                    var displayText = type === 'name' ?
                                        c.value + ' (' + c.phone + ')' :
                                        c.phone + ' (' + c.value + ')';

                                    var $item = $(
                                            '<button class="dropdown-item py-1 px-2" type="button">'
                                        )
                                        .text(displayText)
                                        .data('name', c.value)
                                        .data('phone', c.phone)
                                        .on('click', function() {
                                            if (type === 'name') {
                                                $('#customerName').val($(this).data(
                                                    'name'));
                                                $('#customerPhone').val($(this).data(
                                                    'phone'));
                                            } else {
                                                $('#customerPhone').val($(this).data(
                                                    'phone'));
                                                $('#customerName').val($(this).data(
                                                    'name'));
                                            }
                                            customerExists = true;
                                            $dropdown.removeClass('show');
                                        });

                                    $dropdown.append($item);
                                });

                                $dropdown.addClass('show');
                            }
                        });

                    }, 300);

                    $input.on('input', fetchCustomers);

                    $(document).on('click', function(e) {
                        if (!$(e.target).closest($input).length && !$(e.target).closest($dropdown).length) {
                            $dropdown.removeClass('show');
                        }
                    });
                }

                setupDropdown($('#customerName'), $('#customerNameDropdown'), 'name');
                setupDropdown($('#customerPhone'), $('#customerPhoneDropdown'), 'phone');

                function resetOrderSummary(summery) {
                    $('#summary-subtotal').text(summery.subtotal || "{{ money(0) }}");
                    $('#summary-vat').text(summery.vat_amount || "{{ money(0) }}");
                    $('#summary-discount').text(summery.discount || "{{ money(0) }}");
                    $('#summary-total').text(summery.total || "{{ money(0) }}");
                }

                $(document).on('click', '.filter-btn', function() {
                    let category = $(this).data('category');
                    $('.filter-btn')
                        .removeClass('btn-primary active')
                        .addClass('btn-outline-secondary');
                    $(this)
                        .removeClass('btn-outline-secondary')
                        .addClass('btn-primary active');

                    if (category === 'all') {
                        $('.product-card-wrapper').show();
                    } else {
                        $('.product-card-wrapper').hide();
                        $(`.product-card-wrapper[data-category="${category}"]`).show();
                    }
                });

            });
        </script>
    @endpush

@endsection
