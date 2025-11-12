@extends('seller.layouts.app')
@section('title', 'Add Product')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<form id="productForm" autocomplete="off" method="POST" action="{{ route('seller.products.store') }}"
    enctype="multipart/form-data">
    @csrf
    <div class="card mb-3">
        <div class="card-header bg-white">
            <h4 class="mb-0">Add Product</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Basic Info -->
                <div class="col-md-8 mb-3">
                    <label class="form-label">Product Name</label>
                    <input name="name" type="text" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">SKU</label>
                    <input name="sku" type="text" value="{{ \App\Models\ProductVariant::generate_sku() }}"
                        class="form-control form-control-sm" required>
                </div>

                <!-- Category -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Category</label>
                    <select id="categorySelect" name="category_id" class="form-select form-select-sm" required>
                        <option disabled selected>-- Select Category --</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Subcategory</label>
                    <select id="subcategorySelect" name="subcategory_id" class="form-select form-select-sm" disabled>
                        <option disabled selected>-- Select Subcategory --</option>
                        @foreach ($categories as $category)
                        @foreach ($category->subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}">
                            {{ $subcategory->name }}
                        </option>
                        @endforeach
                        @endforeach
                    </select>
                </div>

                <!-- Brand -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Brand</label>
                    <select name="brand" class="form-select form-select-sm brand-select" required>
                        <option disabled selected>-- Select Brand --</option>
                        @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pricing -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Buying Price</label>
                    <input name="buying_price" type="number" min="0" class="form-control form-control-sm"
                        required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Selling Price</label>
                    <input name="selling_price" type="number" min="0" class="form-control form-control-sm"
                        required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">VAT (%)</label>
                    <input name="vat_percent" type="number" min="0" class="form-control form-control-sm"
                        required>
                </div>

                <!-- Payment & Discount -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Payment Type</label>
                    <select name="payment_type" class="form-select form-select-sm" required>
                        @foreach (App\Enums\PaymentType::cases() as $paymentType)
                        <option value="{{ $paymentType->value }}">{{ $paymentType->title() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Discount Type</label>
                    <select name="discount_type" class="form-select form-select-sm">
                        <option value="">-- None --</option>
                        <option value="{{ \App\Enums\DiscountType::FLAT->value }}">Flat</option>
                        <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">Percentage</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Discount Value</label>
                    <input name="discount_value" type="number" class="form-control form-control-sm">
                </div>

                <!-- Unit -->
                <div class="col-md-3 mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-6">
                            <label class="form-label">Unit Value</label>
                            <input type="number" name="unit_value" class="form-control form-control-sm"
                                placeholder="Enter value" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select form-select-sm" required>
                                <option disabled selected>--</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Stock -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Low Stock Quantity</label>
                    <input name="low_stock_quantity" type="number" min="0" class="form-control form-control-sm"
                        required>
                </div>

                <!-- Thumbnail -->
                <div class="col-12 mb-3">
                    <label class="form-label">Thumbnail <span class="text-muted small">(1:1 Ratio)</span></label>
                    <label for="inputImage" class="text-primary text-decoration-underline" style="cursor:pointer;">Crop
                        Image First</label>
                    <input type="file" id="inputImage" hidden accept="image/*" />
                    <x-image-input name="thumbnail" />
                    <span class="text-muted small mt-2">NB: JPG/PNG/WEBP only, max 4MB</span>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Product Variants</h5>
    <div class="card-body mb-3" id="variantsContainer"></div>


    <button type="button" class="btn btn-sm btn-success mb-3" id="addVariantBtn">+ Add Variant</button>

    <div class="d-flex justify-content-end">
        <button type="button" id="submitBtn" class="btn btn-primary">Save Product</button>
    </div>

</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<x-seller.image-cropper-modal />

<script>

    const categoryAttributes = @json($categoryAttributes);
    let variantCounter = 0;
    const $variantsContainer = $('#variantsContainer');
    
    $(function() {

        function initAttributeSelect2() {
            $('.attribute-value').select2({
                tags: true,
                placeholder: 'Select or type a value',
                width: 'resolve',
                dropdownParent: null,
                allowClear: true
            });
        }

        $('.attribute-value').select2({
            tags: true,
            placeholder: 'Select or type a value',
            width: '100%',
            createTag: function(params) {
                const term = $.trim(params.term);
                if (term === '') {
                    return null;
                }

                // Check if the option already exists
                if ($(".attribute-value option").filter(function() {
                        return $(this).text() === term;
                    }).length) {
                    return null; // Ignore duplicates
                }

                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });

        $(".brand-select").select2({
            tags: true,
            theme: "bootstrap-5",
        });

        let selectedCategoryId = null;

        $('#categorySelect').change(function() {
            selectedCategoryId = $(this).val();

            let hasOptions = false;
            $('#subcategorySelect').val('').trigger('change');

            $('#subcategorySelect option').each(function() {
                const optionCategoryId = $(this).data('category');
                if (selectedCategoryId == optionCategoryId) {
                    $(this).show();
                    hasOptions = true;
                } else {
                    $(this).hide();
                }
            });

            $('#subcategorySelect').attr('disabled', !hasOptions);

            $('.variant-card').each(function() {
                const $attributesContainer = $(this).find('.attributes-container');
                $attributesContainer.empty();

                if (selectedCategoryId && categoryAttributes[selectedCategoryId]?.length > 0) {
                    $attributesContainer.append(createAttributeRows());
                }
            });
            initAttributeSelect2();
        });

        function createAttributeRows() {
            if (!selectedCategoryId || !categoryAttributes[selectedCategoryId] || categoryAttributes[selectedCategoryId].length === 0) {
                return '';
            }

            return categoryAttributes[selectedCategoryId].map(attr => {
                const timestamp = Date.now() + Math.floor(Math.random() * 1000);
                const valueOptions = attr.values.map(value =>
                    `<option value="${value.id}">${value.value}</option>`
                ).join('');

                return `
                    <div class="col-sm-6 col-xl-4">
                        <div class="input-group input-group-sm mb-2 attribute-row">
                            <label class="form-label mb-0">${attr.name}</label>
                            <select class="form-select form-select-sm attribute-value" 
                                    id="attribute-value-${timestamp}" 
                                    data-attribute-name="${attr.name}">
                                <option value="">-- Select ${attr.name} --</option>
                                ${valueOptions}
                            </select>
                        </div>
                    </div>
                `;
            }).join('');
        }

        const createVariantCard = () => {
            variantCounter++;
            const id = `variant-${variantCounter}`;
            const collapseId = `collapse-${variantCounter}`;

            return `
                <div class="card variant-card mb-3" id="${id}">
                    <div class="card-header bg-white p-0 position-relative">
                        <button class="btn btn-link w-100 text-start text-decoration-none p-3 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                            Variant #${variantCounter}
                        </button>
                        ${variantCounter > 1 ? `<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 mt-2 me-2 remove-variant-btn" data-variant-id="${id}">Remove</button>` : ''}
                    </div>

                    <div id="${collapseId}" class="collapse show">
                        <div class="card-body p-3">
                            <div class="row g-2 mb-3 align-items-end">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control variant-sku" placeholder="Variant SKU">
                                        <button class="btn btn-outline-secondary generate-sku-btn" type="button">Generate</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control form-control-sm variant-stock" min="0" step="1" placeholder="Stock">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control form-control-sm variant-buying-price" min="0.01" step="0.01" placeholder="Buying Price ({{ currency() }})">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control form-control-sm variant-selling-price" min="0.01" step="0.01" placeholder="Selling Price ({{ currency() }})">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm variant-discount-type">
                                        <option value="">-- Select Discount Type --</option>
                                        <option value="{{ \App\Enums\DiscountType::FLAT->value }}">Flat</option>
                                        <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control form-control-sm variant-discount-value" min="0.01" step="0.01" placeholder="Discount value ({{ currency() }})">
                                </div>
                            </div>

                            <h6 class="mt-2 mb-2">Attributes (optional)</h6>
                            <div class="attributes-container row g-2">
                                ${createAttributeRows()}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        };

        const createVariantCardOld = () => {
            variantCounter++;
            const id = `variant-${variantCounter}`;
            const collapseId = `collapse-${variantCounter}`;

            return `
                            <div class="card variant-card mb-3" id="${id}">
                                <div class="card-header bg-white p-0 position-relative">
                                    <button class="btn btn-link w-100 text-start text-decoration-none p-3 collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                                        Variant #${variantCounter}
                                    </button>
                                    ${variantCounter > 1 ? `<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 mt-2 me-2 remove-variant-btn" data-variant-id="${id}">Remove</button>` : ''}
                                </div>

                                <div id="${collapseId}" class="collapse show">
                                    <div class="card-body p-3">
                                        <div class="row g-2 mb-3 align-items-end">
                                            <div class="col-md-6">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control variant-sku" placeholder="Variant SKU">
                                                    <button class="btn btn-outline-secondary generate-sku-btn" type="button">Generate</button>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="number" class="form-control form-control-sm variant-stock" min="0" step="1" placeholder="Stock">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control form-control-sm variant-buying-price" min="0.01" step="0.01" placeholder="Buying Price ({{ currency() }})">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control form-control-sm variant-selling-price" min="0.01" step="0.01" placeholder="Selling Price ({{ currency() }})">
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-select form-select-sm variant-discount-type">
                                                    <option value="">-- Select Discount Type --</option>
                                                    <option value="{{ \App\Enums\DiscountType::FLAT->value }}">Flat</option>
                                                    <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">Percentage</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control form-control-sm variant-discount-value" min="0.01" step="0.01" placeholder="Discount value ({{ currency() }})">
                                            </div>
                                        </div>

                                        <h6 class="mt-2 mb-2 text-success">Attributes</h6>
                                        <div class="attributes-container row g-2">${createAttributeRow()}</div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary add-attribute-btn mt-2">+ Attribute</button>
                                    </div>
                                </div>
                            </div>
                        `;
        };

        const addVariant = () => {
            $variantsContainer.append($(createVariantCard()));
            initAttributeSelect2();
        };

        $(document).on('click', '#addVariantBtn', addVariant);
        $(document).on('click', '.remove-variant-btn', function() {
            const variantId = $(this).data('variant-id');
            $(`#${variantId}`).remove();
        });

        $(document).on('click', '.add-attribute-btn', function() {
            $(this).siblings('.attributes-container').append(createAttributeRow());
        });

        $(document).on('click', '.remove-attribute-btn', function() {
            $(this).closest('.attribute-row').remove();
        });

        $(document).on('click', '.generate-sku-btn', function() {
            const randomSKU = crypto.randomUUID().split('-')[0].toUpperCase();
            $(this).siblings('.variant-sku').val(randomSKU);
        });

        $('#submitBtn').click(function(e) {
            e.preventDefault();

            let form = $('#productForm')[0];
            let formData = new FormData(form);
            const variants = [];

            $('.variant-card').each(function() {
                const $card = $(this);
                const variant = {
                    sku: $card.find('.variant-sku').val()?.trim() || null,
                    buying_price: $card.find('.variant-buying-price').val() || null,
                    selling_price: $card.find('.variant-selling-price').val() || null,
                    variant_discount_type: $card.find('.variant-discount-type').val() || null,
                    variant_discount_value: $card.find('.variant-discount-value').val() || null,
                    stock: $card.find('.variant-stock').val() || null,
                    attributes: {}
                };

                $card.find('.attribute-value').each(function() {
                    const attrName = $(this).data('attribute-name');
                    if (!attrName) return;

                    const selectedTexts = $(this).select2('data')
                        .map(item => item.text.trim())
                        .filter(v => v && !v.startsWith('-- Select')); // remove placeholder text

                    if (selectedTexts.length) {
                        variant.attributes[attrName] = selectedTexts.length === 1 ? selectedTexts[0] : selectedTexts;
                    }
                });

                variants.push(variant);
            });

            formData.append('variants', JSON.stringify(variants));

            $.ajax({
                url: "{{ route('seller.products.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#submitBtn').attr('disabled', true).text('Saving...');
                },
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(() => window.location.href =
                        "{{ route('seller.products.index') }}", 1500);
                },
                error: function(xhr) {
                    $('#submitBtn').attr('disabled', false).text('Save');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors).map(item => item[0]).join(
                            '<br>');
                        toastr.error(messages);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        });

        addVariant();
    });
</script>
@endpush