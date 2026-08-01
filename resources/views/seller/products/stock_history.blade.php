@php
    use App\Domain\Product\Enums\StockType;

    $totalCount = $stockHistories->total();
    $addedCount = $stockHistories->where('type', StockType::ADD_STOCK)->sum('quantity');
    $removedCount = $stockHistories->where('type', StockType::REMOVE_STOCK)->sum(fn($h) => abs($h->quantity));
    $setCount = $stockHistories->where('type', StockType::SET_EXACT_STOCK)->count();
@endphp
@extends('seller.layouts.app')
@section('title', 'Stock History')

@section('content')

    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #06b6d4, #38bdf8, #7dd3fc);">
        </div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="package" class="text-feedback-info" style="width:12px;height:12px;"></i>
                        <span>Workspace</span>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">Stock History</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">Stock History</h1>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#06b6d4]/15 text-[#06b6d4]">
                            <i data-lucide="history" style="width:11px;height:11px;" class="me-1"></i> Inventory Log
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Audit trail of every inventory adjustment.</p>
                </div>
                <div class="shrink-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#stockUpdateModal">
                        <i data-lucide="package" style="width:15px;height:15px;"></i> Update Stock
                    </button>
                </div>
            </div>
        </div>
    </section>

    @php
        $tiles = [
            [
                'key' => '_total',
                'label' => 'Total Entries',
                'top' => '#06b6d4',
                'text' => 'text-[#06b6d4]',
                'icon' => 'history',
                'value' => $totalCount,
            ],
            [
                'key' => '_added',
                'label' => 'Stock Added',
                'top' => '#10b981',
                'text' => 'text-feedback-success',
                'icon' => 'plus',
                'value' => $addedCount,
                'prefix' => '+',
            ],
            [
                'key' => '_removed',
                'label' => 'Stock Removed',
                'top' => '#ef4444',
                'text' => 'text-feedback-danger',
                'icon' => 'minus',
                'value' => $removedCount,
                'prefix' => '-',
            ],
            [
                'key' => '_set',
                'label' => 'Set Exact',
                'top' => '#B7791A',
                'text' => 'text-feedback-warning',
                'icon' => 'pencil',
                'value' => $setCount,
            ],
        ];
    @endphp
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
        @foreach ($tiles as $tile)
            <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
                <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
                <div class="p-4 pt-5">
                    <div class="flex items-center justify-between mb-1">
                        <span
                            class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                        <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                    </div>
                    <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">
                        {{ ($tile['prefix'] ?? '') . number_format($tile['value']) }}</h3>
                </div>
            </article>
        @endforeach
    </section>

    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3">
        <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
            <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
            <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
            <div class="grow"></div>
            @if (request('q') || request('type') || request('from') || request('to'))
                <a href="{{ route('seller.stock.index') }}"
                    class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                    <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
                </a>
            @endif
        </div>
        <div class="p-4 border-t border-border">
            <form method="GET" action="{{ route('seller.stock.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-2">
                <div class="md:col-span-4 relative">
                    <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary"
                        style="width:14px;height:14px; left: 10px;"></i>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Search product, SKU, or variant…"
                        class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                </div>
                <div class="md:col-span-2">
                    <select name="type"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="">All Types</option>
                        @foreach (StockType::cases() as $type)
                            <option value="{{ $type->value }}" @selected((string) request('type') === (string) $type->value)>{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <input type="date" name="from" value="{{ request('from') }}"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                </div>
                <div class="md:col-span-2">
                    <input type="date" name="to" value="{{ request('to') }}"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="btn btn-primary w-full">
                        <i data-lucide="search" style="width:14px;height:14px;"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
            Showing <span class="text-ink-emphasis font-semibold">{{ $stockHistories->firstItem() ?? 0 }}</span>
            – <span class="text-ink-emphasis font-semibold">{{ $stockHistories->lastItem() ?? 0 }}</span>
            of <span class="text-ink-emphasis font-semibold">{{ $stockHistories->total() }}</span> entries
        </div>

        <div class="overflow-x-auto px-4 pb-4">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Product /
                            Variant</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">SKU</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Type</th>
                        <th
                            class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">
                            Quantity</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Note</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockHistories as $history)
                        @php
                            $iconBg = match ($history->type) {
                                StockType::ADD_STOCK => 'bg-feedback-success/15 text-feedback-success',
                                StockType::REMOVE_STOCK => 'bg-feedback-danger/15 text-feedback-danger',
                                StockType::SET_EXACT_STOCK => 'bg-feedback-warning/15 text-feedback-warning',
                                default => 'bg-surface-muted text-ink-tertiary',
                            };
                            $icon = match ($history->type) {
                                StockType::ADD_STOCK => 'arrow-up',
                                StockType::REMOVE_STOCK => 'arrow-down',
                                StockType::SET_EXACT_STOCK => 'pencil',
                                default => 'circle',
                            };
                            $pillBg = match ($history->type) {
                                StockType::ADD_STOCK => 'bg-feedback-success/15 text-feedback-success',
                                StockType::REMOVE_STOCK => 'bg-feedback-danger/15 text-feedback-danger',
                                StockType::SET_EXACT_STOCK => 'bg-feedback-warning/15 text-feedback-warning',
                                default => 'bg-surface-muted text-ink-tertiary',
                            };
                        @endphp
                        <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-full {{ $iconBg }} flex items-center justify-center shrink-0">
                                        <i data-lucide="{{ $icon }}" style="width:14px;height:14px;"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-ink-emphasis text-sm truncate max-w-[200px]">
                                            {{ $history->product?->name ?? '—' }}</div>
                                        <small
                                            class="text-ink-tertiary">{{ $history->variant?->label ?: 'Simple product' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <code
                                    class="px-1.5 py-0.5 rounded-xs bg-surface-muted text-ink-secondary">{{ $history->variant?->sku ?? ($history->product?->sku ?? '—') }}</code>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                    {{ $history->type->value === StockType::ADD_STOCK->value
                                        ? 'Add'
                                        : ($history->type->value === StockType::REMOVE_STOCK->value
                                            ? 'Remove'
                                            : ($history->type->value === StockType::SET_EXACT_STOCK->value
                                                ? 'Set'
                                                : '—')) }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-3 text-center font-semibold
                            {{ $history->type === StockType::ADD_STOCK
                                ? 'text-feedback-success'
                                : ($history->type === StockType::REMOVE_STOCK
                                    ? 'text-feedback-danger'
                                    : 'text-ink-emphasis') }}">
                                {{ $history->type === StockType::ADD_STOCK
                                    ? '+' . $history->quantity
                                    : ($history->type === StockType::REMOVE_STOCK
                                        ? '-' . abs($history->quantity)
                                        : $history->quantity) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-ink-secondary" style="max-width:240px;">
                                <div class="truncate">{{ $history->note ?: '—' }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-ink-secondary whitespace-nowrap">
                                <i data-lucide="calendar" style="width:11px;height:11px;"
                                    class="me-1 align-text-bottom text-ink-tertiary"></i>
                                {{ $history->created_at->format('d M Y · H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="py-10 text-center">
                                    <i data-lucide="history" class="text-ink-tertiary mx-auto mb-2"
                                        style="width:36px;height:36px;"></i>
                                    <p class="text-ink-soft font-semibold mb-1">No stock history yet</p>
                                    <p class="text-ink-tertiary text-xs">Click <strong>Update Stock</strong> to record your
                                        first adjustment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end p-4 border-t border-border">
            {{ $stockHistories->links() }}
        </div>
    </section>

    <div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="stockForm" action="{{ route('seller.stock.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <div>
                            <h4 class="modal-title font-bold">Update Product Stock</h4>
                            <small class="text-ink-tertiary">Search and pick a product — variants appear
                                automatically.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productSelect"
                                class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Select
                                Product </label>
                            <select id="productSelect" name="product_id" class="form-select" required>
                                <option></option>
                            </select>
                            <small class="text-ink-tertiary">Start typing to search by product name or SKU.</small>
                        </div>
                        <div class="mb-3 d-none" id="variantContainer">
                            <label for="variantSelect"
                                class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Select
                                Variant </label>
                            <select id="variantSelect" name="variant_id" class="form-select">
                                <option></option>
                            </select>
                            <small class="text-ink-tertiary text-warning" id="variantNotice"></small>
                        </div>
                        <div class="mb-3" id="currentStockBox" style="display:none">
                            <div class="p-3 rounded-sm bg-surface-muted flex items-center justify-between">
                                <div>
                                    <div class="text-[11px] text-ink-tertiary uppercase tracking-wider font-semibold mb-1">
                                        Current stock</div>
                                    <div class="text-xl font-bold text-ink-emphasis" id="currentStockValue">0</div>
                                </div>
                                <i data-lucide="package-2" class="text-ink-tertiary" style="width:32px;height:32px;"></i>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <div>
                                <label for="stockQuantity"
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Quantity
                                </label>
                                <input type="number" id="stockQuantity" name="quantity" min="1" required
                                    class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[11px] font-semibold text-ink-tertiary mb-2 uppercase tracking-wider">Stock
                                    Action </label>
                                <div class="flex gap-3 flex-wrap p-2 rounded-sm bg-surface-muted">
                                    <div class="flex items-center gap-2">
                                        <input
                                            class="h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep"
                                            type="radio" name="stock_action" id="addStock"
                                            value="{{ StockType::ADD_STOCK->value }}" checked>
                                        <label class="flex items-center gap-1 text-sm text-ink-emphasis cursor-pointer"
                                            for="addStock"><span
                                                class="inline-block w-2 h-2 rounded-full bg-feedback-success"></span>
                                            {{ StockType::ADD_STOCK->label() }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input
                                            class="h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep"
                                            type="radio" name="stock_action" id="removeStock"
                                            value="{{ StockType::REMOVE_STOCK->value }}">
                                        <label class="flex items-center gap-1 text-sm text-ink-emphasis cursor-pointer"
                                            for="removeStock"><span
                                                class="inline-block w-2 h-2 rounded-full bg-feedback-danger"></span>
                                            {{ StockType::REMOVE_STOCK->label() }}</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input
                                            class="h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep"
                                            type="radio" name="stock_action" id="setStock"
                                            value="{{ StockType::SET_EXACT_STOCK->value }}">
                                        <label class="flex items-center gap-1 text-sm text-ink-emphasis cursor-pointer"
                                            for="setStock"><span
                                                class="inline-block w-2 h-2 rounded-full bg-feedback-warning"></span>
                                            {{ StockType::SET_EXACT_STOCK->label() }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="stockNote"
                                class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Note
                                <span class="font-normal normal-case">(Optional)</span></label>
                            <input type="text" id="stockNote" name="note" maxlength="255"
                                placeholder="e.g. Received shipment, damaged goods, count correction…"
                                class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        </div>
                        <div id="stockAlert" class="mt-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitStockBtn"><i data-lucide="save"
                                style="width:14px;height:14px;"></i> Update Stock</button>
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
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
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
                        data: function(params) {
                            return {
                                q: params.term || ''
                            };
                        },
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
                    templateSelection: function(p) {
                        return p.text || p.name || '';
                    }
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
                        data: function(params) {
                            return {
                                product_id: productSelect.val(),
                                q: params.term || ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: (data.variants || []).map(v => ({
                                    id: v.id,
                                    text: v.name,
                                    name: v.name,
                                    sku: v.sku,
                                    current_stock: v.current_stock
                                }))
                            };
                        }
                    },
                    templateResult: formatVariant,
                    templateSelection: function(v) {
                        return v.text || v.name || '';
                    }
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
                    const wrapper = $('<div>').addClass('p-3 rounded-sm text-sm d-flex align-items-start gap-2');
                    if (type === 'success') wrapper.addClass('bg-feedback-success/10 text-feedback-success');
                    else if (type === 'error') wrapper.addClass('bg-feedback-danger/10 text-feedback-danger');
                    else wrapper.addClass('bg-feedback-warning/10 text-feedback-warning');
                    wrapper.html('<i data-lucide="info" style="width:16px;height:16px;"></i><div>' + message +
                    '</div>');
                    stockAlert.html(wrapper);
                    window.renderIcons && window.renderIcons();
                }

                function clearAlert() {
                    stockAlert.html('');
                }

                stockForm.on('submit', function(e) {
                    e.preventDefault();
                    if (!productSelect.val()) {
                        highlightAlert('error', 'Please select a product.');
                        return;
                    }
                    if (variantContainer.is(':visible') && !variantSelect.val()) {
                        highlightAlert('error',
                            'This product has variants. Please pick the variant you want to update.');
                        return;
                    }
                    clearAlert();
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Updating…');

                    $.ajax({
                        url: stockForm.attr('action'),
                        method: 'POST',
                        data: stockForm.serialize(),
                        success: function(response) {
                            if (response && response.success) {
                                highlightAlert('success', response.message ||
                                    'Stock updated successfully.');
                                setTimeout(() => window.location.reload(), 900);
                            } else {
                                highlightAlert('error', (response && response.message) ||
                                    'Failed to update stock.');
                                submitBtn.prop('disabled', false).html(
                                    '<i data-lucide="save" style="width:14px;height:14px;"></i> Update Stock'
                                    );
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
                            submitBtn.prop('disabled', false).html(
                                '<i data-lucide="save" style="width:14px;height:14px;"></i> Update Stock'
                                );
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
