@extends('seller.layouts.app')
@section('title', 'Stock History')

@section('content')
    <div class="flex justify-between items-end mb-3">
        <h4 class="font-bold mb-0 text-ink">Stock History</h4>
        <div>
            <button type="button" class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1" data-bs-toggle="modal" data-bs-target="#stockUpdateModal">
                <i data-feather="package" style="width:16px;height:16px;"></i> Update Stock
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover align-middle bg-white" id="stock-history-table">
            <thead class="bg-surface-muted">
                <tr>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Product</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">SKU</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Type</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Quantity</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Note</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockHistories as $history)
                    <tr>
                        <td>
                            <p class="font-bold mb-0">{{ $history->product?->name ?? '—' }}</p>
                            <span class="text-sm text-ink-tertiary">{{ $history->variant?->label ?: 'Simple product' }}</span>
                        </td>
                        <td><span class="text-sm">{{ $history->variant?->sku ?? $history->product?->sku ?? '—' }}</span></td>
                        <td>
                            @switch($history->type)
                                @case(\App\Domain\Product\Enums\StockType::ADD_STOCK)
                                    <span class="badge badge-soft-success">Add</span>
                                @break
                                @case(\App\Domain\Product\Enums\StockType::REMOVE_STOCK)
                                    <span class="badge badge-soft-danger">Remove</span>
                                @break
                                @case(\App\Domain\Product\Enums\StockType::SET_EXACT_STOCK)
                                    <span class="badge badge-soft-warning">Set</span>
                                @break
                            @endswitch
                        </td>
                        <td>
                            @switch($history->type)
                                @case(\App\Domain\Product\Enums\StockType::ADD_STOCK)
                                    <span class="text-feedback-success font-semibold">+{{ $history->quantity }}</span>
                                @break
                                @case(\App\Domain\Product\Enums\StockType::REMOVE_STOCK)
                                    <span class="text-feedback-danger font-semibold">-{{ $history->quantity }}</span>
                                @break
                                @case(\App\Domain\Product\Enums\StockType::SET_EXACT_STOCK)
                                    <span class="font-semibold">{{ $history->quantity }}</span>
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

    <div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <form id="stockForm" action="{{ route('seller.stock.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title font-semibold">Update Product Stock</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productSelect" class="block text-xs font-medium text-ink-secondary mb-1">Select Product</label>
                            <select id="productSelect" name="product_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                <option value="">-- Select Product --</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="variantContainer">
                            <label for="variantSelect" class="block text-xs font-medium text-ink-secondary mb-1">Select Variant</label>
                            <select id="variantSelect" name="variant_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                <option value="">-- Select Variant --</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-3">
                            <div class="mb-3 col-span-1">
                                <label for="stockQuantity" class="block text-xs font-medium text-ink-secondary mb-1">Quantity</label>
                                <input type="number" id="stockQuantity" name="quantity" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" min="1" required />
                            </div>
                            <div class="mb-3 col-span-2">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Stock Action</label>
                                <div class="flex gap-3 flex-wrap">
                                    <div class="flex items-center gap-2">
                                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="stock_action" id="addStock" value="{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->value }}" checked>
                                        <label class="text-sm text-ink" for="addStock">{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->label() }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="stock_action" id="removeStock" value="{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->value }}">
                                        <label class="text-sm text-ink" for="removeStock">{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->label() }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="stock_action" id="setStock" value="{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->value }}">
                                        <label class="text-sm text-ink" for="setStock">{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->label() }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stockNote" class="block text-xs font-medium text-ink-secondary mb-1">Note (Optional)</label>
                            <input type="text" id="stockNote" name="note" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Add any note..." />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Update Stock</button>
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
                                $productSelect.append(`<option value="${product.id}">${product.name} | SKU: ${product.sku} | Stock: ${product.current_stock}</option>`);
                            });
                        },
                        error: function() { alert("Failed to load products."); }
                    });
                }

                $('#stockUpdateModal').on('show.bs.modal', function() { loadProducts(); });

                $productSelect.on('change', function() {
                    const productId = $(this).val();
                    if (!productId) { $variantContainer.addClass('d-none'); $variantSelect.prop('required', false).val(''); return; }
                    $.ajax({
                        url: "{{ route('seller.stock.variants') }}",
                        type: "GET",
                        data: { product_id: productId },
                        success: function(response) {
                            if (response.variants && response.variants.length > 0) {
                                $variantContainer.removeClass('d-none');
                                $variantSelect.prop('required', true);
                                $variantSelect.html('<option value="">-- Select Variant --</option>');
                                $.each(response.variants, function(i, variant) {
                                    $variantSelect.append(`<option value="${variant.id}">${variant.name} | SKU: ${variant.sku} | Stock: ${variant.current_stock}</option>`);
                                });
                            } else { $variantContainer.addClass('d-none'); $variantSelect.prop('required', false).val(''); }
                        },
                        error: function() { alert("Failed to load variants."); }
                    });
                });
            });
        </script>
    @endpush

@endsection
