@extends('seller.layouts.app')
@section('title', 'Print Barcode')
@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="barcode" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Products</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Barcode</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Print Barcode</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="barcode" style="width:11px;height:11px;" class="me-1"></i> Labels
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Select a product and generate printable barcode labels.</p>
            </div>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div class="md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="px-4 py-3 border-b border-border bg-surface-muted">
                <h5 class="text-sm font-semibold text-ink mb-0">Label Details</h5>
            </div>
            <div class="p-5">
                <form id="productForm">
                    <div class="mb-3">
                        <label for="product" class="block text-xs font-semibold text-ink-secondary mb-1">Select Product</label>
                        <select name="variant_id" class="select2 w-full" id="product" required>
                            <option value="" disabled selected>Select a product</option>
                            @foreach ($products as $product)
                                @if($product->variants->count() == 0)
                                    <option value="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-variant=""
                                        data-sellingprice="{{ $product->price }}"
                                        data-discountedprice="{{ $product->compare_price ?? $product->price }}"
                                        data-stock="{{ $product->availableStock }}"
                                        data-barcode="{{ $product->barcode ?? $product->sku }}"
                                        data-sku="{{ $product->sku }}">
                                        {{ $product->name }} | {{ $product->barcode ?? $product->sku }} | {{ $product->availableStock }} {{ $product->unit->short_name }}
                                    </option>
                                @else
                                    @foreach ($product->variants as $variant)
                                    <option value="{{ $variant->id }}"
                                        data-name="{{ $product->name }}"
                                        data-variant="{{ $variant->label }}"
                                        data-sellingprice="{{ $variant->price }}"
                                        data-discountedprice="{{ $variant->compare_price }}"
                                        data-stock="{{ $variant->availableStock }}"
                                        data-barcode="{{ $variant->barcode ?? $variant->sku }}"
                                        data-sku="{{ $variant->sku }}">
                                        {{ $product->name }} | {{ $variant->label }} | {{ $variant->barcode ?? $variant->sku }} | {{ $variant->availableStock }} {{ $product->unit->short_name }}
                                    </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-ink-secondary mb-1">Name</label>
                            <input type="text" id="name" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" readonly>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-ink-secondary mb-1">Variant</label>
                            <input type="text" id="variant" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" readonly>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-ink-secondary mb-1">SKU</label>
                            <input type="text" id="sku" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" readonly>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-semibold text-ink-secondary mb-1">Price</label>
                            <input type="text" id="price" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-ink-secondary mb-1">Number of Labels</label>
                        <input type="number" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="quantity" id="qty" min="1" value="1" required>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" id="generate" class="btn btn-primary">
                            <i data-lucide="eye" class="icon-xs"></i> Preview Labels
                        </button>
                        <button type="button" id="printBtn" class="btn btn-dark disabled">
                            <i data-lucide="printer" class="icon-xs"></i> Print
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="md:col-span-1">
        <div id="labelsContainer" class="flex flex-col" style="max-height: calc(100vh - 150px); overflow-y:scroll;"></div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    $(document).ready(function() {
        $('#product').select2();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const productSelect = document.getElementById('product');
        const nameInput = document.getElementById('name');
        const variantInput = document.getElementById('variant');
        const skuInput = document.getElementById('sku');
        const priceInput = document.getElementById('price');
        const qtyInput = document.getElementById('qty');
        const labelsDiv = document.getElementById('labelsContainer');
        const sellerName = "{{ $seller->business_name }}";

        $(productSelect).on('select2:select', function(e) {
            const selectedOption = e.params.data.element;
            const opt = selectedOption ? $(selectedOption) : null;
            if (opt) {
                nameInput.value = opt.data('name');
                variantInput.value = opt.data('variant');
                // Prefer the dedicated barcode field; fall back to the SKU.
                const barcode = opt.data('barcode') || opt.data('sku');
                skuInput.value = barcode;
                skuInput.dataset.originalSku = opt.data('sku');
                priceInput.value = opt.data('sellingprice');
                qtyInput.value = opt.data('stock');
            }
        });

        document.getElementById('generate').addEventListener('click', function() {
            labelsDiv.innerHTML = '';
            let qty = parseInt(qtyInput.value, 10);
            const sku = skuInput.value;
            const name = nameInput.value;
            const price = priceInput.value;
            const variant = variantInput.value;

            if (!qty || qty === 0) qty = 5;

            for (let i = 0; i < qty; i++) {
                const wrap = document.createElement('div');
                wrap.className = 'label-preview p-2 border text-center m-1 bg-white text-ink';
                wrap.style.width = '200px';
                wrap.innerHTML = `
                    <div class="text-sm font-bold">${sellerName}</div>
                    <div style="font-size:7pt;">${name}</div>
                    <div style="font-size:6pt;">${variant}</div>
                    <svg class="barcode"
                        jsbarcode-value="${sku}"
                        jsbarcode-width="1.2"
                        jsbarcode-height="40"
                        jsbarcode-fontSize="10"
                        jsbarcode-margin="0">
                    </svg>
                    <div class="text-sm mt-1">Price: ${price}</div>
                `;
                labelsDiv.appendChild(wrap);
            }
            JsBarcode(".barcode").init();

            $('#printBtn').removeClass('disabled');
        });

        $('#printBtn').on('click', function() {
            const quantity = qtyInput.value;
            if (!quantity > 0) {
                return;
            }

            const url = "{{ route('seller.products.printBarcodeLabels') }}?sku=" + skuInput.value + "&quantity=" + quantity;

            window.open(url, '_blank');

            resetForm();
        });

        function resetForm() {
            labelsDiv.innerHTML = '';
            $('#printBtn').addClass('disabled');
            $('#productForm')[0].reset();
            $('#productForm .select2').val(null).trigger('change');
        }
    });
</script>
@endpush