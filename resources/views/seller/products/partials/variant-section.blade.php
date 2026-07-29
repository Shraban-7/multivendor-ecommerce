@php $isEdit = isset($product) && $product->id; @endphp
@php $variantCount = $isEdit ? $product->variants->count() : 0; @endphp

<div id="variantSection" class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="bg-surface-muted px-4 py-2.5 border-b border-border flex items-center justify-between">
        <h5 class="font-bold mb-0 text-sm">
            <i data-lucide="layers" class="me-2 text-brand" style="width:16px;height:16px;"></i>Variants
            @if($isEdit && $variantCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-secondary ms-2">{{ $variantCount }} existing</span>
            @endif
        </h5>
        @if($isEdit)
            <button type="button" class="btn btn-primary btn-sm"
                data-bs-toggle="modal" data-bs-target="#addVariantModal">
                <i data-lucide="plus"></i> Add Variants
            </button>
        @endif
    </div>
    <div class="p-5">
        @if($isEdit && $variantCount > 0)
            {{-- Existing variants table --}}
            <div class="overflow-x-auto mb-4">
                <table class="w-full text-left text-sm text-ink border-collapse">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="text-sm font-semibold text-ink-tertiary">Image</th>
                            <th class="text-sm font-semibold text-ink-tertiary">SKU</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Barcode</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Options</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Cost</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Price</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Compare</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Weight</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Stock</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Status</th>
                            <th class="text-sm font-semibold text-ink-tertiary">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr>
                            <td><img src="{{ $variant->imageUrl }}" class="border border-border rounded-xs p-1" style="width:40px;height:40px;object-fit:cover;"></td>
                            <td class="font-mono text-sm">{{ $variant->sku }}</td>
                            <td class="text-sm">{{ $variant->barcode ?? '—' }}</td>
                            <td><span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-secondary">{{ $variant->label }}</span></td>
                            <td class="text-sm">{{ money($variant->cost_price) }}</td>
                            <td class="text-sm">{{ money($variant->price) }}</td>
                            <td class="text-sm">{{ $variant->compare_price ? money($variant->compare_price) : '—' }}</td>
                            <td class="text-sm">{{ $variant->weight ? $variant->weight.' kg' : '—' }}</td>
                            <td class="text-sm">{{ $variant->availableStock }}</td>
                            <td>
                                @if($variant->status)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-gray-500 text-white">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-1">
                                    <button class="btn btn-light btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#editVariantModal{{ $variant->id }}">
                                        <i data-lucide="edit" class="icon-xs"></i>
                                    </button>
                                    <form action="{{ route('seller.productVariants.toggleStatus', $variant->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm"
                                            title="{{ $variant->status ? 'Disable' : 'Enable' }}">
                                            <i data-lucide="{{ $variant->status ? 'eye-off' : 'eye' }}" class="icon-xs"></i>
                                        </button>
                                    </form>
                                    @if($variant->stock_out <= 0)
                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#deleteVariantModal{{ $variant->id }}">
                                        <i data-lucide="trash" class="icon-xs"></i>
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
                                <div class="grid grid-cols-2">
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">SKU</label>
                                        <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $variant->sku }}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Barcode <span class="text-ink-tertiary text-xs">(auto-generated)</span></label>
                                        <div class="flex gap-2">
                                            <input type="text" class="flex-1 w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="barcode" value="{{ $variant->barcode }}" placeholder="Auto-generated if left blank">
                                            <button type="button" class="btn btn-light btn-sm flex items-center gap-1 regen-variant-barcode-btn"
                                                data-url="{{ route('seller.products.regenerate-variant-barcode', $variant) }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-input="input[name=barcode]"
                                                title="Generate new barcode">
                                                <i data-lucide="refresh-cw" style="width:14px;height:14px;"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Cost Price</label>
                                        <input type="number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="cost_price" step="0.01" value="{{ $variant->cost_price }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Price</label>
                                        <input type="number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="price" step="0.01" value="{{ $variant->price }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Compare Price</label>
                                        <input name="compare_price" type="number" step="0.01" min="0" value="{{ $variant->compare_price }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Optional sale price">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Weight (kg)</label>
                                        <input name="weight" type="number" step="0.01" min="0" value="{{ $variant->weight }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="0.00">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Low Stock Quantity</label>
                                        <input name="low_stock_quantity" type="number" value="{{ $variant->low_stock_quantity }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                                        <div class="flex items-center gap-2 mt-2">
                                            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="status" role="switch" value="1" {{ $variant->status ? 'checked' : '' }}>
                                            <label class="text-sm text-ink">{{ $variant->status ? 'Active' : 'Disabled' }}</label>
                                        </div>
                                    </div>
                                    <div class="col-span-full mb-3">
                                        <x-image-input name="image" :image="$variant->imageUrl" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Update</button>
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
                            <div class="alert p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-center justify-center gap-3" role="alert">
                                <i data-lucide="circle-alert" class="me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
                                <p class="mb-0 text-ink-secondary">Are you sure you want to delete variant <strong>{{ $variant->sku }}</strong>?</p>
                            </div>
                        </div>
                        <div class="modal-footer justify-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('seller.productVariants.delete', $variant->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @elseif($isEdit)
            <p class="text-ink-tertiary mb-0">No variants yet. Click "Add Variants" to create some.</p>
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
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveVariantsBtn">Save Variants</button>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Variant barcode regenerate handler.
    $(document).on('click', '.regen-variant-barcode-btn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const url = btn.data('url');
        const inputSelector = btn.data('input');
        if (!url) return;
        if (!confirm('Generate a new barcode for this variant? The old one will stop working.')) return;
        btn.prop('disabled', true).find('svg').css('animation', 'spin 0.7s linear infinite');
        $.ajax({
            url: url,
            method: 'POST',
            data: { _token: btn.data('csrf') },
            success: function(resp) {
                if (resp && resp.barcode) {
                    const input = document.querySelector(inputSelector);
                    if (input) input.value = resp.barcode;
                    showSuccessToast?.(resp.message || 'Variant barcode regenerated.');
                } else {
                    showErrorToast?.('Unexpected response from server.');
                }
            },
            error: function(xhr) {
                showErrorToast?.(xhr.responseJSON?.message || 'Failed to regenerate barcode.');
            },
            complete: function() {
                btn.prop('disabled', false).find('svg').css('animation', '');
            }
        });
    });

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
