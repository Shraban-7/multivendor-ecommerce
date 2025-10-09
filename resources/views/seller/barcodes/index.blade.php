@extends('seller.layouts.app')
@section('title', 'Print Barcode')
@section('content')

<h4>Print Barcode</h4>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form id="productForm">
                    <div class="mb-3">
                        <label for="product" class="form-label fw-bold">Select Product</label>
                        <select name="variant_id" class="select2 w-100" id="product" required>
                            <option value="" disabled selected>Select a product</option>
                            @foreach ($products as $product)
                            @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}"
                                data-name="{{ $product->name }}"
                                data-variant="{{ $variant->fullName }}"
                                data-sellingprice="{{ $variant->selling_price }}"
                                data-discountedprice="{{ $variant->discounted_price }}"
                                data-stock="{{ $variant->availableStock }}"
                                data-sku="{{ $variant->sku }}">
                                {{ $product->name }} | {{ $variant->fullName }} | {{ $variant->availableStock }} {{ $product->unit->short_name }}
                            </option>
                            @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" id="name" class="form-control" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Variant</label>
                            <input type="text" id="variant" class="form-control" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">SKU</label>
                            <input type="text" id="sku" class="form-control" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <input type="text" id="price" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Number of Labels</label>
                        <input type="number" class="form-control" name="quantity" id="qty" min="1" value="1" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" id="generate" class="btn btn-primary">
                            <i data-feather="eye" class="nav-icon icon-xs"></i> Preview Labels
                        </button>
                        <button type="button" id="printBtn" class="btn btn-dark disabled">
                            <i data-feather="printer" class="nav-icon icon-xs"></i>
                            Print
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="labelsContainer" class="d-flex flex-column" style="max-height: calc(100vh - 150px); overflow-y:scroll;"></div>
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
                skuInput.value = opt.data('sku');
                //const price = opt.data('discountedprice') && opt.data('discountedprice') !== '0' ? opt.data('discountedprice') : opt.data('sellingprice');
                const price = opt.data('sellingprice');
                priceInput.value = price;
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
                wrap.className = 'label-preview p-2 border text-center m-1 bg-white text-dark';
                wrap.style.width = '200px';
                wrap.innerHTML = `
                    <div class="small fw-bold">${sellerName}</div>
                    <div style="font-size:7pt;">${name}</div>
                    <div style="font-size:6pt;">${variant}</div>
                    <svg class="barcode"
                        jsbarcode-value="${sku}"
                        jsbarcode-width="1.2"
                        jsbarcode-height="40"
                        jsbarcode-fontSize="10"
                        jsbarcode-margin="0">
                    </svg>
                    <div class="small mt-1">Price: ${price}</div>
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