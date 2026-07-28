@php $isEdit = isset($product) && $product->id; @endphp
@php $variantCount = $isEdit ? $product->variants->count() : 0; @endphp

<div id="variantSection" class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-semibold mb-0">
            <i class="fas fa-layer-group me-2 text-primary"></i>Variants
            @if($isEdit && $variantCount > 0)
                <span class="badge bg-secondary ms-2">{{ $variantCount }} existing</span>
            @endif
        </h5>
        @if($isEdit)
            <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#addVariantModal">
                <i class="fas fa-plus"></i> Add Variants
            </button>
        @endif
    </div>
    <div class="card-body">
        @if($isEdit && $variantCount > 0)
            {{-- Existing variants table --}}
            <div class="table-responsive mb-4">
                <table class="table table-sm table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-semibold text-muted">Image</th>
                            <th class="small fw-semibold text-muted">SKU</th>
                            <th class="small fw-semibold text-muted">Barcode</th>
                            <th class="small fw-semibold text-muted">Options</th>
                            <th class="small fw-semibold text-muted">Cost</th>
                            <th class="small fw-semibold text-muted">Price</th>
                            <th class="small fw-semibold text-muted">Compare</th>
                            <th class="small fw-semibold text-muted">Weight</th>
                            <th class="small fw-semibold text-muted">Stock</th>
                            <th class="small fw-semibold text-muted">Status</th>
                            <th class="small fw-semibold text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr>
                            <td><img src="{{ $variant->imageUrl }}" class="img-thumbnail" style="width:40px;height:40px;object-fit:cover;"></td>
                            <td class="text-monospace small">{{ $variant->sku }}</td>
                            <td class="small">{{ $variant->barcode ?? '—' }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $variant->label }}</span></td>
                            <td class="small">{{ money($variant->cost_price) }}</td>
                            <td class="small">{{ money($variant->price) }}</td>
                            <td class="small">{{ $variant->compare_price ? money($variant->compare_price) : '—' }}</td>
                            <td class="small">{{ $variant->weight ? $variant->weight.' kg' : '—' }}</td>
                            <td class="small">{{ $variant->availableStock }}</td>
                            <td>
                                @if($variant->status)
                                    <span class="badge badge-soft-success">Active</span>
                                @else
                                    <span class="badge badge-soft-secondary">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-light border btn-sm d-inline-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#editVariantModal{{ $variant->id }}">
                                        <i data-feather="edit" class="icon-xs"></i>
                                    </button>
                                    <form action="{{ route('seller.productVariants.toggleStatus', $variant->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning border btn-sm d-inline-flex align-items-center"
                                            title="{{ $variant->status ? 'Disable' : 'Enable' }}">
                                            <i data-feather="{{ $variant->status ? 'eye-off' : 'eye' }}" class="icon-xs"></i>
                                        </button>
                                    </form>
                                    @if($variant->stock_out <= 0)
                                    <button class="btn btn-danger border btn-sm d-inline-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#deleteVariantModal{{ $variant->id }}">
                                        <i data-feather="trash" class="icon-xs"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Edit variant modals --}}
            @foreach($product->variants as $variant)
            <div class="modal fade" id="editVariantModal{{ $variant->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Variant ({{ $variant->label }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('seller.productVariants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label">SKU</label>
                                        <input type="text" class="form-control" value="{{ $variant->sku }}" disabled>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Barcode</label>
                                        <input type="text" class="form-control" name="barcode" value="{{ $variant->barcode }}" placeholder="Optional barcode">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Cost Price</label>
                                        <input type="number" class="form-control" name="cost_price" step="0.01" value="{{ $variant->cost_price }}" required>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price" step="0.01" value="{{ $variant->price }}" required>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Compare Price</label>
                                        <input name="compare_price" type="number" step="0.01" min="0" value="{{ $variant->compare_price }}" class="form-control" placeholder="Optional sale price">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Weight (kg)</label>
                                        <input name="weight" type="number" step="0.01" min="0" value="{{ $variant->weight }}" class="form-control" placeholder="0.00">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label">Low Stock Quantity</label>
                                        <input name="low_stock_quantity" type="number" value="{{ $variant->low_stock_quantity }}" class="form-control">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label d-block">Status</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="status" role="switch" value="1" {{ $variant->status ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $variant->status ? 'Active' : 'Disabled' }}</label>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <x-image-input name="image" :image="$variant->imageUrl" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteVariantModal{{ $variant->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="text-center modal-body">
                            <div class="alert alert-warning d-flex align-items-center justify-content-center" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 text-danger" style="font-size: 1.5rem;"></i>
                                <p class="mb-0 text-secondary">Are you sure you want to delete variant <strong>{{ $variant->sku }}</strong>?</p>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('seller.productVariants.delete', $variant->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-1">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @elseif($isEdit)
            <p class="text-muted mb-0">No variants yet. Click "Add Variants" to create some.</p>
        @endif

        {{-- Variant Generator --}}
        <div id="variantGeneratorSection">
            @include('seller.products.variant-generator')
        </div>
    </div>
</div>

{{-- Add variant modal (edit page) --}}
@if($isEdit)
<div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Add Variants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="variantAlert"></div>
                <form id="variantForm">
                    @include('seller.products.variant-generator')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1" id="saveVariantsBtn">Save Variants</button>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    @if($isEdit)
    $('#saveVariantsBtn').click(function(e) {
        const variants = collectVariantsData();
        let formData = new FormData();
        formData.append('variants', JSON.stringify(variants));
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('product_id', "{{ $product->id }}");

        $('#variantAlert').html('');
        $('#saveVariantsBtn').attr('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('seller.productVariants.store', $product->id) }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#variantAlert').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">Variant added successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
                setTimeout(function() {
                    $('#addVariantModal').modal('hide');
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                $('#saveVariantsBtn').attr('disabled', false).text('Save Variants');
                let msg = xhr.responseJSON?.message || 'Something went wrong.';
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    msg = Object.values(errors).map(item => `<div>${item[0]}</div>`).join('');
                }
                $('#variantAlert').html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">${msg}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`);
            }
        });
    });
    @endif

    function collectVariantsData() {
        const variantBody = document.getElementById("variantsTableBody");
        if (!variantBody) return [];
        const variantRows = variantBody.querySelectorAll("tr");
        const variants = [];
        variantRows.forEach((row) => {
            const colorId = row.dataset.colorId || '';
            const sizeId = row.dataset.sizeId || '';
            const skuInput = row.querySelector('td:nth-child(2) input');
            const barcodeInput = row.querySelector('td:nth-child(3) input');
            const costPriceInput = row.querySelector('input[name="cost_price"]');
            const priceInput = row.querySelector('input[name="price"]');
            const comparePriceInput = row.querySelector('input[name="compare_price"]');
            const weightInput = row.querySelector('input[name="weight"]');
            const imageInput = row.querySelector('input[type="file"]');
            variants.push({
                color_id: colorId ? parseInt(colorId) : null,
                size_id: sizeId ? parseInt(sizeId) : null,
                sku: skuInput?.value?.trim() || '',
                barcode: barcodeInput?.value?.trim() || '',
                cost_price: costPriceInput?.value || '',
                price: priceInput?.value || '',
                compare_price: comparePriceInput?.value || '',
                weight: weightInput?.value || '',
                image: imageInput?.files?.[0] || null,
            });
        });
        return variants;
    }
</script>
@endpush
