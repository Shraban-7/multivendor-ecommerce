@extends('seller.layouts.app')
@section('title', 'Create Bundle')
@section('content')

<div class="flex justify-between items-center mb-3">
    <h4 class="font-bold mb-0 text-ink">Create Bundle</h4>
    <a href="{{ route('seller.bundles.index') }}" class="btn btn-light btn-sm">
        <i data-lucide="arrow-left" class="icon-xs me-1"></i> Back
    </a>
</div>

<form action="{{ route('seller.bundles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                <div class="p-5 p-4">
                    <h5 class="text-lg font-semibold mb-3">Bundle Details</h5>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Bundle Name *</label>
                        <input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required maxlength="255">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 mb-3">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">SKU</label>
                            <input type="text" name="sku" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('sku') is-invalid @enderror"
                                value="{{ old('sku') }}" placeholder="Auto-generated if empty">
                            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Barcode</label>
                            <input type="text" name="barcode" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('barcode') is-invalid @enderror"
                                value="{{ old('barcode') }}">
                            @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Short Description</label>
                        <textarea name="short_description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('short_description') is-invalid @enderror"
                            rows="2" maxlength="500">{{ old('short_description') }}</textarea>
                        @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Description</label>
                        <x-textarea-input name="description" :value="old('description')" />
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mt-4">
                <div class="p-5 p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="text-lg font-semibold mb-0">Bundle Items</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addItemBtn">
                            <i data-lucide="plus" class="icon-xs"></i> Add Product
                        </button>
                    </div>
                    @error('items')<div class="p-4 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm flex items-start gap-3 py-2 text-sm">{!! $message !!}</div>@enderror

                    <div id="itemsContainer">
                    </div>

                    <div id="noItemsMsg" class="text-center text-ink-tertiary py-4 border rounded">
                        <i data-lucide="package" style="width:32px;height:32px;"></i>
                        <p class="mt-2 mb-0">Click "Add Product" to add items to this bundle</p>
                        <p class="text-sm mb-0">A bundle must contain at least 2 products</p>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Bundle Type</label>
                        <div class="flex gap-3">
                            <div class="flex items-center gap-2">
                                <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="type" value="fixed"
                                    id="typeFixed" checked>
                                <label class="text-sm text-ink" for="typeFixed">Fixed Bundle</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="radio" name="type" value="mix_match"
                                    id="typeMixMatch">
                                <label class="text-sm text-ink" for="typeMixMatch">Mix & Match</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                <div class="p-5 p-4">
                    <h5 class="text-lg font-semibold mb-3">Pricing</h5>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Price Type</label>
                        <select name="price_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="priceType">
                            <option value="auto">Auto Calculate</option>
                            <option value="manual">Manual Price</option>
                        </select>
                    </div>

                    <div class="mb-3" id="manualPriceGroup" style="display:none;">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Bundle Price</label>
                        <input type="number" step="0.01" min="0" name="price"
                            class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('price') is-invalid @enderror" value="{{ old('price') }}">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Compare Price</label>
                        <input type="number" step="0.01" min="0" name="compare_price"
                            class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('compare_price') is-invalid @enderror"
                            value="{{ old('compare_price') }}" placeholder="Show savings">
                        @error('compare_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm font-semibold">Bundle Discount</label>
                        <div class="grid grid-cols-1 gap-2">
                            <div class="col-span-5">
                                <select name="discount_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                    <option value="">No Discount</option>
                                    <option value="percentage">Percentage %</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                            </div>
                            <div class="col-span-7">
                                <input type="number" step="0.01" min="0" name="discount_value"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('discount_value') }}"
                                    placeholder="Value">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-sm bg-blue-50 border border-blue-200 text-feedback-info text-sm flex items-start gap-3 text-sm py-2 mb-0" id="autoPricePreview">
                        Price will be calculated automatically based on items
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mt-4">
                <div class="p-5 p-4">
                    <h5 class="text-lg font-semibold mb-3">Thumbnail</h5>
                    <div class="upload-zone border border-2 border-dashed rounded-md p-4 text-center"
                        style="cursor:pointer;background:#f8f9fa;" id="thumbZone">
                        <i data-lucide="image" style="width:32px;height:32px;color:#F85606"></i>
                        <p class="mt-2 mb-0 text-sm">Click to upload thumbnail</p>
                        <input type="file" name="thumbnail" id="thumbInput" class="d-none" accept="image/*">
                    </div>
                    <div id="thumbPreview" class="d-none mt-2">
                        <img src="" class="img-fluid rounded" style="max-height:150px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-2 mt-4">
        <a href="{{ route('seller.bundles.index') }}" class="btn btn-light">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i data-lucide="check" class="icon-xs me-1"></i> Create Bundle
        </button>
    </div>
</form>

@push('scripts')
<script>
    const allProducts = @json($products);
    let itemIndex = 0;

    document.getElementById('addItemBtn').addEventListener('click', addItemRow);
    document.getElementById('priceType').addEventListener('change', function() {
        document.getElementById('manualPriceGroup').style.display = this.value === 'manual' ? 'block' : 'none';
        document.getElementById('autoPricePreview').style.display = this.value === 'auto' ? 'block' : 'none';
    });

    function addItemRow(data) {
        const container = document.getElementById('itemsContainer');
        const index = itemIndex++;
        const productId = data?.product_id ?? '';
        const quantity = data?.quantity ?? 1;
        const isOptional = data?.is_optional ?? false;

        const div = document.createElement('div');
        div.className = 'item-row border rounded p-3 mb-2 relative';
        div.dataset.index = index;

        let options = '<option value="">Select Product</option>';
        allProducts.forEach(p => {
            const stock = parseInt(p.stock_in) - parseInt(p.stock_out);
            options += `<option value="${p.id}" ${String(p.id) === String(productId) ? 'selected' : ''}
                data-price="${p.price}" data-stock="${stock}">
                ${p.name} (${p.sku}) - ${stock} in stock
            </option>`;
        });

        div.innerHTML = `
            <button type="button" class="btn-close absolute top-0 right-0 mt-2 me-2 remove-item"></button>
            <div class="grid grid-cols-1 gap-2 items-end">
                <div class="md:col-span-1">
                    <label class="text-sm font-semibold">Product *</label>
                    <select name="items[${index}][product_id]" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors-sm product-select" required>
                        ${options}
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="text-sm font-semibold">Qty</label>
                    <input type="number" name="items[${index}][quantity]" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                        value="${quantity}" min="1" max="999" required>
                </div>
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mt-3">
                        <input type="checkbox" name="items[${index}][is_optional]" class="h-4 w-4 rounded border-border text-brand focus:ring-brand"
                            value="1" id="opt${index}" ${isOptional ? 'checked' : ''}>
                        <label class="text-sm text-ink text-sm" for="opt${index}">Optional</label>
                    </div>
                </div>
                <div class="md:col-span-1">
                    <div class="text-sm text-ink-tertiary pt-2">
                        <span class="item-price">৳0</span>
                        <br><span class="item-stock text-sm"></span>
                    </div>
                </div>
            </div>
        `;

        div.querySelector('.product-select').addEventListener('change', updateItemInfo);
        div.querySelector('.remove-item').addEventListener('click', () => {
            div.remove();
            checkItemsEmpty();
        });

        container.appendChild(div);
        document.getElementById('noItemsMsg').style.display = 'none';

        if (div.querySelector('.product-select').value) {
            updateItemInfo.call(div.querySelector('.product-select'));
        }
    }

    function updateItemInfo() {
        const select = this;
        const row = select.closest('.item-row');
        const opt = select.options[select.selectedIndex];
        const price = parseFloat(opt.dataset.price || 0);
        const stock = parseInt(opt.dataset.stock || 0);
        row.querySelector('.item-price').textContent = '৳' + price.toFixed(2);
        row.querySelector('.item-stock').textContent = 'Stock: ' + stock;
    }

    function checkItemsEmpty() {
        if (document.querySelectorAll('.item-row').length === 0) {
            document.getElementById('noItemsMsg').style.display = 'block';
        }
    }

    // Thumbnail preview
    document.getElementById('thumbZone').addEventListener('click', () => {
        document.getElementById('thumbInput').click();
    });
    document.getElementById('thumbInput').addEventListener('change', function() {
        if (this.files.length) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('thumbPreview');
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
@endsection
