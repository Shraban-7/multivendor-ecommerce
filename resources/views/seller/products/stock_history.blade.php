@extends('seller.layouts.app')
@section('title', 'Stock History')

@section('content')
    <div class="flex justify-between items-end mb-4">
        <div>
            <h4 class="font-bold mb-0">Stock History</h4>
            <small class="text-ink-tertiary">Audit trail of inventory adjustments</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#stockUpdateModal">
            <i data-lucide="package" style="width:16px;height:16px;"></i> Update Stock
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        @php
            $totalCount = $stockHistories->total();
            $addedCount = $stockHistories->where('type', \App\Domain\Product\Enums\StockType::ADD_STOCK)->sum('quantity');
            $removedCount = $stockHistories->where('type', \App\Domain\Product\Enums\StockType::REMOVE_STOCK)->sum(fn($h) => abs($h->quantity));
            $setCount = $stockHistories->where('type', \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK)->count();
        @endphp
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Total Entries</div>
            <div class="text-xl font-bold text-ink">{{ number_format($totalCount) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Stock Added</div>
            <div class="text-xl font-bold" style="color: #059669">+{{ number_format($addedCount) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Stock Removed</div>
            <div class="text-xl font-bold" style="color: #dc2626">-{{ number_format($removedCount) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Set Exact</div>
            <div class="text-xl font-bold" style="color: #d97706">{{ number_format($setCount) }}</div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('seller.stock.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Search Product / SKU</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search product name, SKU or variant label…">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Type</label>
                        <select name="type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="">All Types</option>
                            @foreach(\App\Domain\Product\Enums\StockType::cases() as $type)
                                <option value="{{ $type->value }}" {{ (string)request('type') === (string)$type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">From</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">To</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                    </div>
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i data-lucide="search" style="width:14px;height:14px;"></i> Search
                        </button>
                        @if(request('q') || request('type') || request('from') || request('to'))
                            <a href="{{ route('seller.stock.index') }}" class="btn btn-light btn-sm">Clear</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="px-4 py-2.5">Product / Variant</th>
                        <th class="px-4 py-2.5">SKU</th>
                        <th class="px-4 py-2.5">Type</th>
                        <th class="px-4 py-2.5">Quantity</th>
                        <th class="px-4 py-2.5">Note</th>
                        <th class="px-4 py-2.5">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($stockHistories as $history)
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($history->type === \App\Domain\Product\Enums\StockType::ADD_STOCK)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 shrink-0">
                                            <i data-lucide="plus" style="width:14px;height:14px;"></i>
                                        </span>
                                    @elseif($history->type === \App\Domain\Product\Enums\StockType::REMOVE_STOCK)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-50 text-red-600 shrink-0">
                                            <i data-lucide="minus" style="width:14px;height:14px;"></i>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-50 text-amber-600 shrink-0">
                                            <i data-lucide="pencil" style="width:14px;height:14px;"></i>
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold mb-0 truncate">{{ $history->product?->name ?? '—' }}</p>
                                        <small class="text-ink-tertiary">{{ $history->variant?->label ?: 'Simple product' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3"><span class="text-sm">{{ $history->variant?->sku ?? $history->product?->sku ?? '—' }}</span></td>
                            <td class="px-4 py-3">
                                @switch($history->type)
                                    @case(\App\Domain\Product\Enums\StockType::ADD_STOCK)
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white">Add</span>
                                    @break
                                    @case(\App\Domain\Product\Enums\StockType::REMOVE_STOCK)
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-500 text-white">Remove</span>
                                    @break
                                    @case(\App\Domain\Product\Enums\StockType::SET_EXACT_STOCK)
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-amber-500 text-white">Set</span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">
                                @switch($history->type)
                                    @case(\App\Domain\Product\Enums\StockType::ADD_STOCK)
                                        <span class="text-feedback-success font-semibold">+{{ $history->quantity }}</span>
                                    @break
                                    @case(\App\Domain\Product\Enums\StockType::REMOVE_STOCK)
                                        <span class="text-feedback-danger font-semibold">-{{ abs($history->quantity) }}</span>
                                    @break
                                    @case(\App\Domain\Product\Enums\StockType::SET_EXACT_STOCK)
                                        <span class="font-semibold">{{ $history->quantity }}</span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-4 py-3">{{ $history->note ?: '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $history->created_at->format('d/m/y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-8 text-ink-tertiary">No stock history yet. Click "Update Stock" to make your first adjustment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end px-4 py-3 border-t border-border">
            {{ $stockHistories->links() }}
        </div>
    </div>

    <div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form id="stockForm" action="{{ route('seller.stock.update') }}" method="POST">
                    @csrf
                    <div class="modal-header border-b border-border">
                        <div>
                            <h4 class="modal-title font-semibold">Update Product Stock</h4>
                            <small class="text-ink-tertiary">Search and pick a product — variants appear automatically.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productSelect" class="block text-xs font-medium text-ink-secondary mb-1">Select Product <span class="text-feedback-danger">*</span></label>
                            <select id="productSelect" name="product_id" class="form-select" required>
                                <option></option>
                            </select>
                            <small class="text-ink-tertiary">Start typing to search by product name or SKU.</small>
                        </div>
                        <div class="mb-3 d-none" id="variantContainer">
                            <label for="variantSelect" class="block text-xs font-medium text-ink-secondary mb-1">Select Variant <span class="text-feedback-danger">*</span></label>
                            <select id="variantSelect" name="variant_id" class="form-select">
                                <option></option>
                            </select>
                            <small class="text-ink-tertiary text-warning" id="variantNotice"></small>
                        </div>
                        <div class="mb-3" id="currentStockBox" style="display:none">
                            <div class="p-3 rounded-sm bg-surface-muted border border-border flex items-center justify-between">
                                <div>
                                    <div class="text-xs text-ink-tertiary uppercase tracking-wider font-semibold">Current stock</div>
                                    <div class="text-xl font-bold text-ink" id="currentStockValue">0</div>
                                </div>
                                <i data-lucide="package-2" class="text-ink-tertiary" style="width:32px;height:32px;"></i>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-1">
                                <label for="stockQuantity" class="block text-xs font-medium text-ink-secondary mb-1">Quantity <span class="text-feedback-danger">*</span></label>
                                <input type="number" id="stockQuantity" name="quantity" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" min="1" required />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-ink-secondary mb-2">Stock Action <span class="text-feedback-danger">*</span></label>
                                <div class="flex gap-3 flex-wrap p-2 rounded-sm border border-border bg-surface-muted">
                                    <div class="flex items-center gap-2">
                                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="stock_action" id="addStock" value="{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->value }}" checked>
                                        <label class="text-sm text-ink d-flex align-items-center gap-1" for="addStock"><span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> {{ \App\Domain\Product\Enums\StockType::ADD_STOCK->label() }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="stock_action" id="removeStock" value="{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->value }}">
                                        <label class="text-sm text-ink d-flex align-items-center gap-1" for="removeStock"><span class="inline-block w-2 h-2 rounded-full bg-red-500"></span> {{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->label() }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="stock_action" id="setStock" value="{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->value }}">
                                        <label class="text-sm text-ink d-flex align-items-center gap-1" for="setStock"><span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span> {{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->label() }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="stockNote" class="block text-xs font-medium text-ink-secondary mb-1">Note (Optional)</label>
                            <input type="text" id="stockNote" name="note" maxlength="255" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="e.g. Received shipment, damaged goods, count correction…" />
                        </div>
                        <div id="stockAlert" class="mt-2"></div>
                    </div>
                    <div class="modal-footer border-t border-border">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitStockBtn"><i data-lucide="save" style="width:14px;height:14px;"></i> Update Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const productSelect = $('#productSelect');
                const variantSelect = $('#variantSelect');
                const variantContainer = $('#variantContainer');
                const variantNotice = $('#variantNotice');
                const currentStockBox = $('#currentStockBox');
                const currentStockValue = $('#currentStockValue');
                const stockForm = $('#stockForm');
                const submitBtn = $('#submitStockBtn');
                const stockAlert = $('#stockAlert');

                function formatProduct(product) {
                    if (!product.id) return product.text;
                    const variantsTxt = product.variants_count > 0 ? ' · ' + product.variants_count + ' variants' : '';
                    return $('<span>').append(
                        '<strong>' + escapeHtml(product.name) + '</strong>' +
                        '<br><small class="text-ink-tertiary">SKU: ' + escapeHtml(product.sku || '—') +
                        ' · Stock: ' + product.current_stock + variantsTxt + '</small>'
                    );
                }

                function formatVariant(variant) {
                    if (!variant.id) return variant.text;
                    return $('<span>').append(
                        '<strong>' + escapeHtml(variant.name) + '</strong>' +
                        '<br><small class="text-ink-tertiary">SKU: ' + escapeHtml(variant.sku || '—') +
                        ' · Stock: ' + variant.current_stock + '</small>'
                    );
                }

                function escapeHtml(s) {
                    if (s === null || s === undefined) return '';
                    return String(s).replace(/[&<>"']/g, c => ({
                        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                    })[c]);
                }

                productSelect.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Search product by name or SKU…',
                    allowClear: true,
                    dropdownParent: $('#stockUpdateModal'),
                    minimumInputLength: 0,
                    ajax: {
                        url: '{{ route('seller.stock.products') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) { return { q: params.term || '' }; },
                        processResults: function(data) {
                            return {
                                results: (data.products || []).map(p => ({
                                    id: p.id,
                                    text: p.name,
                                    name: p.name,
                                    sku: p.sku,
                                    current_stock: p.current_stock,
                                    has_variants: p.has_variants,
                                    variants_count: p.variants_count
                                }))
                            };
                        }
                    },
                    templateResult: formatProduct,
                    templateSelection: function(p) { return p.text || p.name || ''; }
                });

                variantSelect.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'First select a product',
                    allowClear: true,
                    dropdownParent: $('#stockUpdateModal'),
                    ajax: {
                        url: '{{ route('seller.stock.variants') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) { return { product_id: productSelect.val(), q: params.term || '' }; },
                        processResults: function(data) {
                            return {
                                results: (data.variants || []).map(v => ({
                                    id: v.id, text: v.name, name: v.name,
                                    sku: v.sku, current_stock: v.current_stock
                                }))
                            };
                        }
                    },
                    templateResult: formatVariant,
                    templateSelection: function(v) { return v.text || v.name || ''; }
                });

                productSelect.on('select2:select', function(e) {
                    const data = e.params.data;
                    variantSelect.empty().trigger('change');
                    variantContainer.addClass('d-none');
                    currentStockBox.hide();
                    variantSelect.prop('required', false);
                    variantNotice.text('');
                    if (data.has_variants) {
                        variantContainer.removeClass('d-none');
                        variantSelect.prop('required', true);
                        currentStockBox.show();
                        currentStockValue.text(data.current_stock);
                    } else {
                        currentStockBox.show();
                        currentStockValue.text(data.current_stock);
                    }
                });

                productSelect.on('select2:clear', function() {
                    variantSelect.empty().trigger('change');
                    variantContainer.addClass('d-none');
                    currentStockBox.hide();
                    variantNotice.text('');
                    variantSelect.prop('required', false);
                });

                variantSelect.on('select2:select', function(e) {
                    currentStockValue.text(e.params.data.current_stock);
                });

                $('#stockUpdateModal').on('show.bs.modal', function() {
                    if (!productSelect.val()) {
                        currentStockBox.hide();
                        variantContainer.addClass('d-none');
                    }
                });

                function highlightAlert(type, message) {
                    const wrapper = $('<div>').addClass('p-3 rounded-sm border text-sm d-flex align-items-start gap-2');
                    if (type === 'success') wrapper.addClass('bg-emerald-50 border-emerald-200 text-emerald-700');
                    else if (type === 'error') wrapper.addClass('bg-red-50 border-red-200 text-red-700');
                    else wrapper.addClass('bg-amber-50 border-amber-200 text-amber-700');
                    wrapper.html('<i data-lucide="info" style="width:16px;height:16px;"></i><div>' + message + '</div>');
                    stockAlert.html(wrapper);
                    window.renderIcons && window.renderIcons();
                }

                function clearAlert() { stockAlert.html(''); }

                stockForm.on('submit', function(e) {
                    e.preventDefault();
                    if (!productSelect.val()) {
                        highlightAlert('error', 'Please select a product.');
                        return;
                    }
                    if (variantContainer.is(':visible') && !variantSelect.val()) {
                        highlightAlert('error', 'This product has variants. Please pick the variant you want to update.');
                        return;
                    }
                    clearAlert();
                    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Updating…');

                    $.ajax({
                        url: stockForm.attr('action'),
                        method: 'POST',
                        data: stockForm.serialize(),
                        success: function(response) {
                            if (response && response.success) {
                                highlightAlert('success', response.message || 'Stock updated successfully.');
                                setTimeout(() => window.location.reload(), 900);
                            } else {
                                highlightAlert('error', (response && response.message) || 'Failed to update stock.');
                                submitBtn.prop('disabled', false).html('<i data-lucide="save" style="width:14px;height:14px;"></i> Update Stock');
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Failed to update stock.';
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                                msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            highlightAlert('error', msg);
                            submitBtn.prop('disabled', false).html('<i data-lucide="save" style="width:14px;height:14px;"></i> Update Stock');
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection