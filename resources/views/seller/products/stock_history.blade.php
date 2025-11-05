@extends('seller.layouts.app')
@section('title', 'Stock History')

@section('content')
    <div class="mb-2 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Stock History</h4>
        <div>
            {{-- <a href="{{ route('seller.products.index') }}" class="btn btn-secondary btn-sm">
                <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to Products
            </a> --}}
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#stockUpdateModal">
                Update Stock
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle bg-white" id="stock-history-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Note</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockHistories as $history)
                    <tr>
                        <td>
                            <p class="fw-bold mb-0">{{ $history->product->name }}</p>
                            <span class="small text-muted">{{ $history->variant->full_name ?? 'default' }}</span>
                        </td>
                        <td>
                            @switch($history->type)
                                @case(\App\Enums\StockType::ADD_STOCK)
                                    <span class="badge bg-success">+{{ $history->quantity }}
                                        {{ $history->product->unit->short_name }}</span>
                                @break

                                @case(\App\Enums\StockType::REMOVE_STOCK)
                                    <span class="badge bg-danger">-{{ $history->quantity }}
                                        {{ $history->product->unit->short_name }}</span>
                                @break

                                @case(\App\Enums\StockType::SET_EXACT_STOCK)
                                    <span class="badge bg-warning text-dark">Adjusted: {{ $history->quantity }}
                                        {{ $history->product->unit->short_name }}</span>
                                @break
                            @endswitch
                        </td>
                        <td>{{ $history->note ?? '-' }}</td>
                        <td>{{ $history->created_at->format('d/m/y, h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $stockHistories->links() }}

    <!-- Stock Update Modal -->
    <div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="stockForm" action="{{ route('seller.stock.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Update Product Stock</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Product Dropdown -->
                        <div class="mb-3">
                            <label for="productSelect" class="form-label">Select Product</label>
                            <select id="productSelect" name="product_id" class="form-select">
                                <option value="">-- Select Product --</option>
                            </select>
                        </div>

                        <!-- Variant Dropdown -->
                        <div class="mb-3 d-none" id="variantContainer">
                            <label for="variantSelect" class="form-label">Select Variant</label>
                            <select id="variantSelect" name="variant_id" class="form-select">
                                <option value="">-- Select Variant --</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-4">
                                <label for="stockQuantity" class="form-label">Quantity</label>
                                <input type="number" id="stockQuantity" name="quantity" class="form-control" min="1"
                                    required />
                            </div>

                            <div class="mb-3 col-8">
                                <label class="form-label d-block">Stock Action</label>
                                <div class="d-flex gap-3 flex-wrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="stock_action" id="addStock"
                                            value="{{ \App\Enums\StockType::ADD_STOCK->value }}" checked>
                                        <label class="form-check-label" for="addStock">{{ \App\Enums\StockType::ADD_STOCK->label() }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="stock_action" id="removeStock"
                                            value="{{ \App\Enums\StockType::REMOVE_STOCK->value }}">
                                        <label class="form-check-label" for="removeStock">{{ \App\Enums\StockType::REMOVE_STOCK->label() }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="stock_action" id="setStock"
                                            value="{{ \App\Enums\StockType::SET_EXACT_STOCK->value }}">
                                        <label class="form-check-label" for="setStock">{{ \App\Enums\StockType::SET_EXACT_STOCK->label() }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stockNote" class="form-label">Note (Optional)</label>
                            <input type="text" id="stockNote" name="note" class="form-control"
                                placeholder="Add any note..." />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const $productSelect = $('#productSelect');
                const $variantContainer = $('#variantContainer');
                const $variantSelect = $('#variantSelect');
                const $stockForm = $('#stockForm');

                function loadProducts() {
                    $.ajax({
                        url: "{{ route('seller.stock.products') }}", 
                        type: "GET",
                        success: function(response) {
                            $productSelect.html('<option value="">-- Select Product --</option>');
                            $.each(response.products, function(i, product) {
                                $productSelect.append(`<option value="${product.id}">
                                    ${product.name} | SKU: ${product.sku} | Stock: ${product.current_stock}
                                </option>`);
                            });
                        },
                        error: function() {
                            alert("Failed to load products.");
                        }
                    });
                }

                $('#stockUpdateModal').on('show.bs.modal', function() {
                    loadProducts();
                });

                $productSelect.on('change', function() {
                    const productId = $(this).val();
                    if (!productId) {
                        $variantContainer.addClass('d-none');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('seller.stock.variants') }}", 
                        type: "GET",
                        data: {
                            product_id: productId
                        },
                        success: function(response) {
                            if (response.variants && response.variants.length > 0) {
                                $variantContainer.removeClass('d-none');
                                $variantSelect.html(
                                    '<option value="">-- Select Variant --</option>');
                                $.each(response.variants, function(i, variant) {
                                    $variantSelect.append(`<option value="${variant.id}">
                                        ${variant.name} | SKU: ${variant.sku} | Stock: ${variant.current_stock}
                                        </option>`);
                                });
                            } else {
                                $variantContainer.addClass('d-none');
                            }
                        },
                        error: function() {
                            alert("Failed to load variants.");
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
