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

    .small-table>tbody {
        font-size: 14px;
    }

    .small-table tbody>tr>td {
        padding: 4px 8px;
    }
</style>

<?php
$variantSkuList = [];
$productSkuList = [];
foreach ($products as $product) {
    $productStock = $product->stock_in - $product->stock_out;
    foreach ($product->variants as $variant) {
        $variantSkuList[] = [
            'variant_id' => $variant->id,
            'sku' => $variant->sku,
            'product_id' => $product->id,
        ];
        $product->total_stock += $variant->availableStock;
    }
    $productSkuList[] = [
        'sku' => $product->sku,
        'product_id' => $product->id,
    ];
    $product->total_stock += $productStock;
}
$products = $products->sortByDesc('total_stock');
$productCounts = $products->groupBy('category_id')->map(function ($categoryProducts) {
    return $categoryProducts->count();
});
foreach ($categories as $cat) {
    $cat->product_count = $productCounts->get($cat->id, 0);
}
?>

@section('container-fluid')
    <div class="row">
        <input type="hidden" id="draft_id" value="{{ request('draft_id') }}">
        <!-- Products/Search Section -->
        <div class="col-md-7">
            <div class="card mb-4 ">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">Products</h4>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end gap-2">
                            <div class="input-group input-group-sm">
                                <input type="text" id="skuSearch" class="form-control" placeholder="Barcode/SKU">
                                <button id="sales" class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#salesModal">
                                    <i class="bi bi-receipt me-1"></i> Recent Sales
                                </button>
                                <button id="draftCartsBtn" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#draftCartsModal">
                                    <i class="bi bi-archive me-1"></i> Drafts
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Draft Carts Modal -->
                    <div class="modal fade" id="draftCartsModal" tabindex="-1" aria-labelledby="draftCartsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header text-dark">
                                    <h5 class="modal-title" id="draftCartsModalLabel">
                                        Draft Carts</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body" id="draftCartsContent">
                                    @if ($draftCarts->isEmpty())
                                        <p class="text-center text-muted">No draft carts found.</p>
                                    @else
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Created At</th>
                                                    <th>Items</th>
                                                    <th>Total Qty</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($draftCarts as $cart)
                                                    <tr>
                                                        <td class="small">{{ $cart->created_at->format('d/m/Y, h:i A') }}
                                                        </td>
                                                        <td>
                                                            <ul class="mb-0 ps-3">
                                                                @foreach ($cart->items as $item)
                                                                    <li class="mb-2">
                                                                        <p class="fw-bold mb-0">{{ $item->product->name }} -
                                                                            {{ $item->quantity }}</p>
                                                                        @if ($item->variant)
                                                                            <span
                                                                                class="text-muted small">({{ $item->variant->fullName }})</span>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td class="text-center">{{ $cart->items->sum('quantity') }}</td>
                                                        <td>
                                                            <div class="d-flex gap-1 overflow-auto">
                                                                <a href="{{ route('seller.pos.index', ['draft_id' => $cart->id]) }}"
                                                                    target="__blank"
                                                                    class="btn btn-light border btn-sm d-flex align-items-center">
                                                                    <i data-feather="edit" class="icon-xs me-1"></i> Edit
                                                                </a>
                                                                <button
                                                                    class="btn btn-danger border btn-sm d-flex align-items-center clear-draft-btn"
                                                                    data-id="{{ $cart->id }}"
                                                                    data-url="{{ route('seller.pos.draft_clear', $cart->id) }}">
                                                                    <i data-feather="trash-2" class="icon-xs me-1"></i>
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>

                            </div>
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
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Order Id</th>
                                                    <th>Customer</th>
                                                    <th>Total</th>
                                                    <th>Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $index => $order)
                                                    <tr>
                                                        <td><a href="{{ route('seller.orders.details', $order->invoice_id) }}"
                                                                target="__blank"># {{ $order->invoice_id }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $order->customer->name ?? '' }}</td>
                                                        <td>{{ money($order->total) }}</td>
                                                        <td>{{ $order->created_at->format('h:i A') }}</td>
                                                        <td>
                                                            <div class="d-flex gap-1 overflow-auto">
                                                                <a href="{{ route('seller.pos.index', ['order_id' => $order->invoice_id]) }}"
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap mb-3 category-filters">
                        <button class="btn btn-outline-primary btn-sm me-2 mb-2 filter-btn active" data-category="all">
                            All ({{ $products->count() }})
                        </button>
                        @foreach ($categories as $category)
                            @if ($category->product_count > 0)
                                <button class="btn btn-outline-secondary btn-sm me-2 mb-2 filter-btn"
                                    data-category="{{ $category->id }}">
                                    {{ $category->name }} ({{ $category->product_count }})
                                </button>
                            @endif
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
                                            <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}"
                                                class="img-fluid rounded"
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
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $product->name }} – Variants</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive">
                                                <table class="table align-middle table-bordered small-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>SKU</th>
                                                            <th>Variant</th>
                                                            <th>Stock</th>
                                                            <th>Price</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($product->variants->count() > 0)
                                                            @foreach ($product->variants as $variant)
                                                                <tr>
                                                                    <td>{{ $variant->sku }}</td>
                                                                    <td class="fw-bold">{{ $variant->fullName }}</td>
                                                                    <td class="text-center">{{ $variant->availableStock }}
                                                                        {{ $product->unit->short_name }}</td>
                                                                    <td class="text-center">
                                                                        {{ money($variant->discounted_price ?? $variant->selling_price) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($variant->availableStock > 0)
                                                                            <button
                                                                                class="btn btn-sm btn-primary add-to-cart-btn"
                                                                                data-product-id="{{ $product->id }}"
                                                                                data-variant-id="{{ $variant->id }}"
                                                                                data-quantity="1">
                                                                                <span class="btn-text">
                                                                                    <i class="bi bi-plus"></i> Add to Cart
                                                                                </span>
                                                                                <span
                                                                                    class="spinner-border spinner-border-sm d-none"></span>
                                                                            </button>
                                                                        @else
                                                                            <button
                                                                                class="btn btn-sm btn-secondary disabled">
                                                                                <i class="bi bi-exclamation-circle"></i>
                                                                                Stock Out
                                                                            </button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            @php
                                                                $productStock =
                                                                    $product->stock_in - $product->stock_out;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $product->sku }}</td>
                                                                <td class="fw-bold">{{ $product->name }}</td>
                                                                <td class="text-center">{{ $productStock }}
                                                                    {{ $product->unit->short_name }}</td>
                                                                <td class="text-center">
                                                                    {{ money($product->discounted_price ?? $product->selling_price) }}
                                                                </td>
                                                                <td class="text-center">

                                                                    @if ($productStock > 0)
                                                                        <button
                                                                            class="btn btn-sm btn-primary add-to-cart-btn"
                                                                            data-product-id="{{ $product->id }}"
                                                                            data-quantity="1">
                                                                            <span class="btn-text">
                                                                                <i class="bi bi-plus"></i> Add to Cart
                                                                            </span>
                                                                            <span
                                                                                class="spinner-border spinner-border-sm d-none"></span>
                                                                        </button>
                                                                    @else
                                                                        <button class="btn btn-sm btn-secondary disabled">
                                                                            <i class="bi bi-exclamation-circle"></i> Stock
                                                                            Out
                                                                        </button>
                                                                    @endif
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart & Checkout Section -->
        <div class="col-md-5">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Summery @if (request()->has('order_id'))
                            ({{ request('order_id') }})
                        @endif
                    </h5>
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
                                    name="customer_name" id="customerName" value="{{ $customer_name }}"
                                    placeholder="Name">
                                <div class="dropdown-menu w-100" id="customerNameDropdown"></div>
                            </div>
                            <div class="col-md-6 mb-2 position-relative">
                                <input type="text" autocomplete="off" class="form-control form-control-sm"
                                    name="customer_phone" id="customerPhone" value="{{ $customer_phone }}"
                                    placeholder="Phone">
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
                            <span id="summary-subtotal">{{ $subtotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small">
                            <span>Vat:</span>
                            <span id="summary-vat">{{ $vat_amount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small">
                            <span>Discount:</span>
                            <span id="summary-discount" data-base="{{ $discount }}">{{ $discount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 fw-bold">
                            <span>Total:</span>
                            <span id="summary-total" data-total="{{ $total }}">{{ $total }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 fw-bold">
                            <span>Due:</span>
                            <span id="due-amount" data-due="{{ request()->has('order_id') ? $due : $total }}">
                                {{ request()->has('order_id') ? $due : $total }}
                            </span>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <input type="number" min="0" step="0.01" class="form-control"
                                        id="discount-amount" style="width: 70%;" placeholder="Enter Discount"
                                        value="{{ $additionalDiscount }}">
                                    <select class="form-select" id="discount-type">
                                        <option value="flat">Flat</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="paid-amount"
                                        value="{{ $paid }}" placeholder="Enter Paid Amount">
                                    <input type="hidden" class="form-control" id="previous-paid"
                                        value="{{ $previousPaid }}">
                                    <button class="btn btn-light border" type="button" id="set-full-paid"><i
                                            class="bi bi-hand-index-thumb"></i> Full Paid</button>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Buttons -->
                        <div class="d-grid gap-2">
                            @if (request()->has('order_id'))
                                <button id="updateOrderBtn" class="btn btn-success">
                                    <i class="bi bi-arrow-repeat me-2"></i> Update Order
                                </button>
                                <a href="{{ route('seller.pos.index') }}" class="btn btn-secondary">Cancel</a>
                            @elseif(request()->has('draft_id'))
                                <button id="placeOrderBtn" class="btn btn-success">
                                    <i class="bi bi-cart me-2"></i> Checkout
                                </button>
                            @else
                                <button id="placeOrderBtn" class="btn btn-success">
                                    <i class="bi bi-cart me-2"></i> Checkout
                                </button>
                                <button id="saveDraftBtn" class="btn btn-info">
                                    <i class="bi bi-save me-2"></i> Save As Draft
                                </button>
                            @endif
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
@endsection

@push('scripts')
    <script>
        const orderId = "{{ request('order_id', 0) }}";

        var variantSkuList = @json($variantSkuList);
        var productSkuList = @json($productSkuList);

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
                var query = $(this).val().trim().toLowerCase();

                if (query === "") {
                    $('.product-card-wrapper').show();
                    return;
                }

                var matchedVariant = variantSkuList.find(v => v.sku && v.sku.toLowerCase() === query);

                var matchedProduct = productSkuList.find(p => p.sku && p.sku.toLowerCase() === query);

                console.log(matchedProduct);

                $('.product-card-wrapper').hide();

                if (matchedVariant) {
                    $('[data-product-id="' + matchedVariant.product_id + '"]').show();

                    addToCart(matchedVariant.product_id, matchedVariant.variant_id, 1);

                    $('#skuSearch').val('');
                    $('.product-card-wrapper').show();
                    return;
                }

                if (matchedProduct) {
                    $('[data-product-id="' + matchedProduct.id + '"]').show();

                    addToCart(matchedProduct.product_id, null, 1);

                    $('#skuSearch').val('');
                    $('.product-card-wrapper').show();
                }
            });


            $(document).on('click', '.add-to-cart-btn', function() {
                let button = $(this);
                let productId = $(this).data('product-id');
                let variantId = $(this).data('variant-id') || null;
                let quantity = $(this).data('quantity') || 1;
                let btnText = button.find('.btn-text');
                let spinner = button.find('.spinner-border');
                btnText.addClass('d-none');
                spinner.removeClass('d-none');

                addToCart(productId, variantId, quantity, button);
            });

            function addToCart(productId, variantId, quantity, button = null) {
                let url = orderId == 0 ? "{{ route('seller.pos.cart_add') }}" :
                    "{{ route('seller.pos.sales.item_add') }}";
                let btnText, spinner;
                if (button != null) {
                    btnText = button.find('.btn-text');
                    spinner = button.find('.spinner-border');
                    button.prop('disabled', true);
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        product_id: productId,
                        variant_id: variantId,
                        quantity: quantity,
                        order_id: orderId,
                        draft_id: $("#draft_id").val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status) {
                            if (button != null) {
                                button.prop('disabled', false)
                            }

                            showSuccess("Item added successfully!");

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
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        showError(xhr.responseJSON?.message || "Something went wrong");
                    },
                    complete: function() {
                        if (button != null) {
                            button.prop('disabled', false);
                            btnText.removeClass('d-none');
                            spinner.addClass('d-none');
                        }
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
                            showSuccess("Cart update successfully!");
                            $('.order-items tbody').html(response.data.html);
                            if (typeof feather !== 'undefined') {
                                feather.replace();
                            }
                            summery = response.data;
                            resetOrderSummary(summery);
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON.message;
                        showError(message);
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
                            showSuccess("Cart item deleted successfully!");
                            $('#cart-item-' + deleteCartItemId).remove();
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
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON.message;
                        showError(message);
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
                            showSuccess(response.message);
                            $('.order-items tbody').html(`
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No items in cart</td>
                                </tr>
                            `);

                            $('#summary-discount')
                                .data('base', 0)
                                .attr('data-base', 0)
                                .text('0.00');

                            $('#discount-amount').val('');
                            $('#discount-type').val('flat');

                            summery = response;
                            resetOrderSummary(summery);
                            const clearModalEl = document.getElementById('clearCartModal');
                            const modal = bootstrap.Modal.getInstance(clearModalEl);
                            modal.hide();
                        } else {
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON.message;
                        showError(message);
                    }
                });
            });

            $(document).on('click', '.clear-draft-btn', function() {
                let draftId = $(this).data('id');
                let url = $(this).data('url');
                if (!confirm("Are you sure you want to clear this draft?")) return;

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        id: draftId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.status) {
                            showSuccess("Draft cleared successfully!!");

                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('seller.pos.index') }}";
                            }, 500);
                        }
                    },
                    error: function(xhr) {

                        let message = "Something went wrong!";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let firstError = Object.values(xhr.responseJSON.errors)[0][0];
                            message = firstError;
                        }

                        if (xhr.status === 500) {
                            message = "Server error (500): Please check logs.";
                        }

                        showError(message);
                    }

                });
            });

            $('#placeOrderBtn').on('click', function() {
                let button = $(this);
                let originalText = button.text();
                let name = $('#customerName').val().trim();
                let phone = $('#customerPhone').val().trim();
                let paid = parseFloat($('#paid-amount').val()) || 0;
                let due = parseFloat($('#due-amount').text().replace(/[^0-9.-]+/g, "")) || 0;

                if (paid < 0) {
                    showError("Paid amount cannot be negative.");
                    return;
                }

                if (0 > due) {
                    showError("Paid amount cannot be greater than Due.");
                    return;
                }

                if (paid == null || paid == 0) {
                    showError("Enter paid amount!");
                    $('#paid-amount').focus();
                    return;
                }

                if (name && !phone) {
                    showError("Phone is required when Name is provided.");
                    return;
                }
                if (phone && !name) {
                    showError("Name is required when Phone is provided.");
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
                        paid: paid,
                        due: due,
                        discount: getTotalDiscount(),
                        items: getItemsFromCart(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status) {
                            button.prop('disabled', false).text(originalText);
                            showSuccess("Order placed successfully!");
                            $('#customerName').val('');
                            $('#customerPhone').val('');
                            $('#paid-amount').val('');
                            $('#summary-discount').data('base', 0).attr('data-base', 0).text(
                                '0.00');
                            $('#discount-amount').val('');
                            $('#discount-type').val('flat');

                            customerExists = false

                            $('.order-items tbody').html(
                                `<tr><td colspan="4" class="text-center text-muted">No items in cart</td></tr>`
                            );

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
                                let receiptWindow = window.open(receiptUrl, "_blank",
                                    "width=800,height=600");

                                let timer = setInterval(function() {
                                    if (receiptWindow.closed) {
                                        clearInterval(timer);
                                        window.location.href =
                                            "{{ route('seller.pos.index') }}";
                                    }
                                }, 500);
                            }
                        } else {
                            showError(response.message);
                            if (response.status == false) {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON.message;
                        showError(message);
                    }
                });
            });

            function getItemsFromCart() {
                const items = [];
                $('.cart-item').each(function() {
                    let row = $(this);
                    items.push({
                        id: row.data('id'),
                        price: parseFloat(row.find('.price-input').val()) || 0
                    });
                });

                return items;
            }

            $(document).on("click", "#saveDraftBtn", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('seller.pos.save_draft') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        $("#saveDraftBtn").prop('disabled', true).text('Saving...');
                    },
                    success: function(res) {
                        if (res.status) {
                            showSuccess(res.message);

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showError(res.message);
                        }
                    },
                    error: function() {
                        showError("Failed to save draft!");
                    },
                    complete: function() {
                        $("#saveDraftBtn").prop('disabled', false)
                            .html('<i class="bi bi-save me-2"></i> Save Draft');
                    }
                });
            });

            $(document).on('click', '.update-order-qty-btn', debounce(function() {
                const row = $(this).closest('.order-item');
                let qtyEl = row.find('.quantity');
                let quantity = parseInt(qtyEl.text());
                const action = $(this).data('action');

                if (action === 'increase') quantity++;
                else if (action === 'decrease' && quantity > 0) quantity--;

                qtyEl.text(quantity);

                calculateSummaryPrice();
            }, 300));

            let deleteOrderItemId = null;

            $(document).on('click', '.delete-order-item-btn', function() {
                deleteOrderItemId = $(this).data('id');
            });

            $(document).on('click', '#confirmDeleteOrderBtn', function() {
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
                            $('#order-item-' + deleteOrderItemId).remove();

                            showSuccess("Order item removed successfully!");
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
                            showError(response.message);
                        }
                    },
                    error: function(xhr) {
                        showError(xhr.responseJSON?.message || "Something went wrong");
                    }
                });
            });


            $('#updateOrderBtn').on('click', function() {
                let button = $(this);
                let originalText = button.text();

                let name = $('#customerName').val().trim();
                let phone = $('#customerPhone').val().trim();

                let subtotal = parseFloat($('#summary-subtotal').attr('data-subtotal')) ||
                    parseFloat($('#summary-subtotal').text()) || 0;
                let vat = parseFloat($('#summary-vat').attr('data-vat')) ||
                    parseFloat($('#summary-vat').text()) || 0;
                let discount = parseFloat($('#summary-discount').attr('data-base')) || 0;
                let total = parseFloat($('#summary-total').attr('data-total')) || 0;
                let due = parseFloat($('#due-amount').attr('data-due')) || 0;
                let paidInput = parseFloat($('#paid-amount').val()) || 0;

                if (paidInput < 0) {
                    showError("Paid amount cannot be negative.");
                    return;
                }
                if (paidInput > total) {
                    showError("Paid amount cannot be greater than total.");
                    return;
                }

                const allItems = [];
                $('.cart-item, .order-item').each(function() {
                    let row = $(this);
                    allItems.push({
                        id: row.data('id'),
                        product_id: row.data('product-id'),
                        product_variant_id: row.data('product-variant-id'),
                        price: parseFloat(row.find('.price-input').val()) || 0,
                        quantity: parseFloat(row.find('.quantity').text().trim()) || 0
                    });
                });

                button.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('seller.pos.sales.update') }}",
                    method: 'POST',
                    data: {
                        order_id: "{{ request('order_id') }}",
                        customer_name: name,
                        customer_phone: phone,
                        paid: paidInput,
                        due: due,
                        subtotal: subtotal,
                        vat: vat,
                        discount: discount,
                        total: total,
                        items: allItems,
                        additional_discount: getAdditionalDiscount(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        button.prop('disabled', false).text(originalText);

                        if (response.status) {
                            showSuccess("Order updated successfully!");
                            $('.order-items tbody').html(response.data.html);
                            resetOrderSummary(response.data);
                            $('#paid-amount').val('');

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
                            showError(response.message || "Something went wrong");
                        }
                    },
                    error: function(xhr) {
                        button.prop('disabled', false).text(originalText);
                        showError(xhr.responseJSON?.message || "Something went wrong");
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

            function resetOrderSummary(summary) {
                let subtotal = 0;
                let totalDiscount = 0;
                let vat = 0;
                let total = 0;
                let due = 0;

                subtotal = parseFloat(summary.subtotal) || 0;
                totalDiscount = parseFloat(summary.discount) || 0;
                vat = parseFloat(summary.vat_amount) || 0;
                total = parseFloat(summary.total) || 0;
                due = parseFloat(summary.due) || total;

                $('#summary-subtotal').text(subtotal.toFixed(2));
                $('#summary-vat').text(vat.toFixed(2));

                $('#summary-discount')
                    .data('base', totalDiscount)
                    .attr('data-base', totalDiscount)
                    .text(totalDiscount.toFixed(2));

                $('#summary-total')
                    .data('base', total)
                    .attr('data-base', total)
                    .data('total', total)
                    .attr('data-total', total)
                    .text(total.toFixed(2));

                $('#due-amount')
                    .data('base', due)
                    .attr('data-base', due)
                    .data('due', due)
                    .attr('data-due', due)
                    .text(due.toFixed(2));
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

            let fullPaidActive = false;

            $(document).on("click", "#set-full-paid", function() {
                let fullPaid = 0;
                const orderId = "{{ request('order_id') }}";
                const total = parseFloat($('#summary-total').attr('data-total')) || 0;
                fullPaid = total;

                $('#paid-amount').val(fullPaid.toFixed(2));
                $('#due-amount').attr('data-due', 0).text('0.00');
            });


            function calculateDiscount() {
                let type = $('#discount-type').val();
                let discountInput = parseFloat($('#discount-amount').val()) || 0;
                let productDiscount = parseFloat($('#summary-discount').data('base')) || 0;
                let originalTotal = parseFloat($('#summary-total').data('total')) || 0;
                let previousPaid = parseFloat($('#previous-paid').val()) || 0;

                let calculatedDiscount = 0;
                if (type === 'flat') calculatedDiscount = discountInput;
                else if (type === 'percentage') {
                    if (discountInput > 100) discountInput = 100;
                    calculatedDiscount = (originalTotal * discountInput) / 100;
                }

                if (calculatedDiscount > originalTotal) calculatedDiscount = originalTotal;

                let totalDiscount = productDiscount + calculatedDiscount;
                let newTotal = originalTotal - calculatedDiscount;
                if (newTotal < 0) newTotal = 0;

                let paidInput = parseFloat($('#paid-amount').val()) || 0;
                if (fullPaidActive) {
                    paidInput = newTotal;

                    $('#paid-amount').val(paidInput.toFixed(2));
                }

                let newDue = newTotal - paidInput;
                if (newDue < 0) newDue = 0;

                $('#summary-total').attr('data-total', newTotal).text(newTotal.toFixed(2));
                $('#summary-discount').text(totalDiscount.toFixed(2));
                $('#due-amount').attr('data-due', newDue).text(newDue.toFixed(2));
            }

            function getTotalDiscount() {
                let discount = parseFloat($('#discount-amount').val());
                let productDiscount = parseFloat($('#summary-discount').data('base'));

                if (!discount) {
                    return 0;
                }

                let type = $('#discount-type').val();
                let calculatedDiscount = 0;

                let total = parseFloat($('#summary-total').data('total'));

                if (type === 'flat') {
                    calculatedDiscount = discount;
                } else if (type === 'percentage') {
                    if (discount > 100) discount = 100;
                    calculatedDiscount = (total * discount) / 100;
                }

                return calculatedDiscount;
            }

            function getAdditionalDiscount() {
                let discount = parseFloat($('#discount-amount').val());
                if (!discount) {
                    return 0;
                }

                let type = $('#discount-type').val();
                let calculatedDiscount = 0;

                let total = parseFloat($('#summary-total').data('total'));

                if (type === 'flat') {
                    calculatedDiscount = discount;
                } else if (type === 'percentage') {
                    if (discount > 100) discount = 100;
                    calculatedDiscount = (total * discount) / 100;
                }

                return calculatedDiscount;
            }

            function formatMoney(amount) {
                return '৳ ' + parseFloat(amount || 0).toFixed(0);
            }

            function parseMoney(str) {
                if (!str) return 0;
                return parseFloat(str.toString().replace(/[^\d.-]/g, ''));
            }

            $(document).on("input change", ".price-input, #discount-amount, #discount-type, #paid-amount",
                function() {
                    calculateSummaryPrice();
                });

            function calculateSummaryPrice() {
                let subtotal = 0;
                let total = 0;
                let vatAmount = parseFloat($('#summary-vat').text().trim()) || 0;

                $('.cart-item, .order-item').each(function() {
                    let row = $(this);
                    let quantity = parseFloat(row.find('.quantity').text().trim()) || 0;
                    let originalPrice = parseFloat(row.find('.price-input').data('price')) || 0;

                    let currentPrice = parseFloat(row.find('.price-input').val()) || 0;

                    subtotal += originalPrice * quantity;
                    total += currentPrice * quantity;
                });

                let discountValue = parseFloat($('#discount-amount').val()) || 0;
                let discountType = $('#discount-type').val();
                let discountAmount = 0;

                if (discountType === 'percentage') {
                    discountAmount = total * (discountValue / 100);
                } else {
                    discountAmount = discountValue;
                }

                let grandTotal = total + vatAmount - discountAmount;

                let paid = parseFloat($('#paid-amount').val()) || 0;
                let due = Math.max(grandTotal - paid, 0);
                let discount = 0;
                if (subtotal > total) {
                    discount = subtotal - grandTotal;
                }

                $('#summary-subtotal').text(subtotal.toFixed(2));
                $('#summary-vat').text(vatAmount.toFixed(2));
                $('#summary-discount').text(discount.toFixed(2)).data('base', discount.toFixed(2));
                $('#summary-total').text(grandTotal.toFixed(2)).attr('data-total', grandTotal.toFixed(2));
                $('#due-amount').text(due.toFixed(2)).data('due', due.toFixed(2));
            }
        });
    </script>
@endpush
