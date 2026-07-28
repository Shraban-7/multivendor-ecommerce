@extends('seller.layouts.app')
@section('title', 'Edit Bundle')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0 text-dark">Edit Bundle: {{ $bundle->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('seller.bundles.show', $bundle) }}" class="btn btn-light btn-sm border">
            <i data-feather="eye" class="icon-xs me-1"></i> View
        </a>
        <a href="{{ route('seller.bundles.index') }}" class="btn btn-light btn-sm border">
            <i data-feather="arrow-left" class="icon-xs me-1"></i> Back
        </a>
    </div>
</div>

<form action="{{ route('seller.bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Bundle Details</h5>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bundle Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $bundle->name) }}" required maxlength="255">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">SKU</label>
                            <input type="text" class="form-control" value="{{ $bundle->sku }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Barcode</label>
                            <input type="text" name="barcode"
                                class="form-control @error('barcode') is-invalid @enderror"
                                value="{{ old('barcode', $bundle->barcode) }}">
                            @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Visibility</label>
                            <div class="form-check form-switch mt-2">
                                <input type="hidden" name="is_visible" value="0">
                                <input type="checkbox" name="is_visible" class="form-check-input" role="switch"
                                    value="1" id="isVisible" {{ $bundle->is_visible ? 'checked' : '' }}>
                                <label class="form-check-label small" for="isVisible">Visible on storefront</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Short Description</label>
                        <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror"
                            rows="2" maxlength="500">{{ old('short_description', $bundle->short_description) }}</textarea>
                        @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="4">{{ old('description', $bundle->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Bundle Items</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addItemBtn">
                            <i data-feather="plus" class="icon-xs"></i> Add Product
                        </button>
                    </div>
                    @error('items')<div class="alert alert-danger py-2 small">{!! $message !!}</div>@enderror

                    <div id="itemsContainer">
                        @foreach($bundle->items as $item)
                        <div class="item-row border rounded p-3 mb-2 position-relative" data-index="{{ $loop->index }}">
                            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-item"></button>
                            <div class="row g-2 align-items-end">
                                <div class="col-md-6">
                                    <label class="small fw-semibold">Product *</label>
                                    <select name="items[{{ $loop->index }}][product_id]"
                                        class="form-select form-select-sm product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $p)
                                        @php $stock = (int) $p->stock_in - (int) $p->stock_out @endphp
                                        <option value="{{ $p->id }}" {{ $item->product_id == $p->id ? 'selected' : '' }}
                                            data-price="{{ $p->price }}" data-stock="{{ $stock }}">
                                            {{ $p->name }} ({{ $p->sku }}) - {{ $stock }} in stock
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="small fw-semibold">Qty</label>
                                    <input type="number" name="items[{{ $loop->index }}][quantity]"
                                        class="form-control form-control-sm" value="{{ $item->quantity }}"
                                        min="1" max="999" required>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" name="items[{{ $loop->index }}][is_optional]"
                                            class="form-check-input" value="1"
                                            id="opt{{ $loop->index }}" {{ $item->is_optional ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="opt{{ $loop->index }}">Optional</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="small text-muted pt-2">
                                        <span class="item-price">৳{{ number_format($item->product?->price ?? 0, 2) }}</span>
                                        <br><span class="item-stock small"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div id="noItemsMsg" class="text-center text-muted py-4 border rounded"
                        style="display:{{ $bundle->items->isEmpty() ? 'block' : 'none' }}">
                        <i data-feather="package" style="width:32px;height:32px;"></i>
                        <p class="mt-2 mb-0">Click "Add Product" to add items</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bundle Type</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="fixed"
                                    id="typeFixed" {{ $bundle->type === 'fixed' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeFixed">Fixed Bundle</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="mix_match"
                                    id="typeMixMatch" {{ $bundle->type === 'mix_match' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeMixMatch">Mix & Match</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Pricing</h5>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Price Type</label>
                        <select name="price_type" class="form-select" id="priceType">
                            <option value="auto" {{ $bundle->price_type === 'auto' ? 'selected' : '' }}>Auto Calculate</option>
                            <option value="manual" {{ $bundle->price_type === 'manual' ? 'selected' : '' }}>Manual Price</option>
                        </select>
                    </div>

                    <div class="mb-3" id="manualPriceGroup"
                        style="display:{{ $bundle->price_type === 'manual' ? 'block' : 'none' }}">
                        <label class="form-label small fw-semibold">Bundle Price</label>
                        <input type="number" step="0.01" min="0" name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $bundle->price) }}">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Compare Price</label>
                        <input type="number" step="0.01" min="0" name="compare_price"
                            class="form-control @error('compare_price') is-invalid @enderror"
                            value="{{ old('compare_price', $bundle->compare_price) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bundle Discount</label>
                        <div class="row g-2">
                            <div class="col-5">
                                <select name="discount_type" class="form-select">
                                    <option value="">No Discount</option>
                                    <option value="percentage" {{ $bundle->discount_type === 'percentage' ? 'selected' : '' }}>%</option>
                                    <option value="fixed" {{ $bundle->discount_type === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                </select>
                            </div>
                            <div class="col-7">
                                <input type="number" step="0.01" min="0" name="discount_value"
                                    class="form-control" value="{{ old('discount_value', $bundle->discount_value) }}">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info small py-2">
                        Subtotal: <strong>{{ money($bundle->calculateOriginalTotal()) }}</strong><br>
                        @if($bundle->discount_type)
                            After discount: <strong>{{ money($bundle->calculatePrice()) }}</strong>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Thumbnail</h5>
                    <div class="upload-zone border border-2 border-dashed rounded-3 p-4 text-center"
                        style="cursor:pointer;background:#f8f9fa;" id="thumbZone">
                        @if($bundle->thumbnail)
                            <img src="{{ $bundle->thumbnail_url }}" class="img-fluid rounded mb-2" style="max-height:120px;">
                            <p class="small mb-0">Click to replace</p>
                        @else
                            <i data-feather="image" style="width:32px;height:32px;color:var(--bs-primary)"></i>
                            <p class="mt-2 mb-0 small">Click to upload thumbnail</p>
                        @endif
                        <input type="file" name="thumbnail" id="thumbInput" class="d-none" accept="image/*">
                    </div>
                    <div id="thumbPreview" class="d-none mt-2">
                        <img src="" class="img-fluid rounded" style="max-height:150px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('seller.bundles.index') }}" class="btn btn-light border px-4">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
            <i data-feather="save" class="icon-xs me-1"></i> Update Bundle
        </button>
    </div>
</form>

@push('scripts')
<script>
    const allProducts = @json($products);
    let itemIndex = {{ $bundle->items->count() }};

    document.getElementById('addItemBtn').addEventListener('click', addItemRow);
    document.getElementById('priceType').addEventListener('change', function() {
        document.getElementById('manualPriceGroup').style.display = this.value === 'manual' ? 'block' : 'none';
    });

    function addItemRow() {
        const container = document.getElementById('itemsContainer');
        const index = itemIndex++;

        const div = document.createElement('div');
        div.className = 'item-row border rounded p-3 mb-2 position-relative';
        div.dataset.index = index;

        let options = '<option value="">Select Product</option>';
        allProducts.forEach(p => {
            const stock = parseInt(p.stock_in) - parseInt(p.stock_out);
            options += `<option value="${p.id}" data-price="${p.price}" data-stock="${stock}">
                ${p.name} (${p.sku}) - ${stock} in stock
            </option>`;
        });

        div.innerHTML = `
            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-item"></button>
            <div class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="small fw-semibold">Product *</label>
                    <select name="items[${index}][product_id]" class="form-select form-select-sm product-select" required>
                        ${options}
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-semibold">Qty</label>
                    <input type="number" name="items[${index}][quantity]" class="form-control form-control-sm"
                        value="1" min="1" max="999" required>
                </div>
                <div class="col-md-2">
                    <div class="form-check mt-3">
                        <input type="checkbox" name="items[${index}][is_optional]" class="form-check-input" value="1" id="opt${index}">
                        <label class="form-check-label small" for="opt${index}">Optional</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="small text-muted pt-2">
                        <span class="item-price">৳0</span>
                        <br><span class="item-stock small"></span>
                    </div>
                </div>
            </div>
        `;

        div.querySelector('.product-select').addEventListener('change', updateItemInfo);
        div.querySelector('.remove-item').addEventListener('click', () => {
            div.remove();
            if (document.querySelectorAll('.item-row').length === 0) {
                document.getElementById('noItemsMsg').style.display = 'block';
            }
        });

        container.appendChild(div);
        document.getElementById('noItemsMsg').style.display = 'none';
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

    document.querySelectorAll('.product-select').forEach(el => {
        el.addEventListener('change', updateItemInfo);
        if (el.value) updateItemInfo.call(el);
    });
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.item-row').remove();
            if (document.querySelectorAll('.item-row').length === 0) {
                document.getElementById('noItemsMsg').style.display = 'block';
            }
        });
    });

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
