@extends('seller.layouts.app')
@section('title', 'Add Product')

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
                        <div class="mt-2" style="width:120px;">
                            <div class="border bg-light d-flex justify-content-center align-items-center rounded"
                                style="height:120px;overflow:hidden;">
                                <img src="{{ asset('assets/frontend/images/default.png') }}" class="img-fluid"
                                    id="thumbPreview" style="object-fit:cover;width:100%;height:100%;">
                            </div>
                            <input type="file" name="thumbnail" class="d-none file-input" accept="image/*">
                            <button type="button" class="btn btn-danger btn-sm mt-2 w-100 d-none remove-image">Remove
                                Image</button>
                        </div>
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
    @push('scripts')
        <x-seller.image-cropper-modal />

        <script>
            $(function() {
                $(".brand-select").select2({
                    tags: true,
                    theme: "bootstrap-5",
                });

                $('#categorySelect').change(function() {
                    var selectedCategoryId = $(this).val();
                    var hasOptions = false;

                    $('#subcategorySelect').val('').trigger('change');

                    $('#subcategorySelect option').each(function() {
                        var optionCategoryId = $(this).data('category');

                        if (selectedCategoryId == optionCategoryId || selectedCategoryId == "") {
                            $(this).show();
                            hasOptions = true;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (hasOptions == true) {
                        $('#subcategorySelect').attr('disabled', false);
                    } else {
                        $('#subcategorySelect').attr('disabled', true);
                    }

                    if (!selectedCategoryId) {
                        $('#subcategorySelect').val('').trigger('change');
                    }
                });

                let variantCounter = 0;
                const variantImageData = new Map();
                const $variantsContainer = $('#variantsContainer');

                let attributeData = {};

                $.ajax({
                    url: '{{ route('seller.products.attribute_suggestions') }}',
                    method: 'GET',
                    success: function(data) {
                        attributeData =
                            data;
                    },
                    error: function(err) {
                        console.error("Failed to fetch attribute suggestions:", err);
                    }
                });

                function createAttributeRow() {
                    const timestamp = Date.now();
                    const keyDatalistId = 'attributeKeyList-' + timestamp;
                    const valueDatalistId = 'attributeValueList-' + timestamp;

                    const keyOptions = (attributeData.keys || []).map(key => `<option value="${key}">`).join('');

                    return `
                            <div class="input-group attribute-row input-group-sm mb-1">
                                <input type="text" class="form-control form-control-sm attribute-key" placeholder="Key (e.g., Color)" list="${keyDatalistId}">
                                <datalist id="${keyDatalistId}">
                                    ${keyOptions}
                                </datalist>

                                <input type="text" class="form-control form-control-sm attribute-value" placeholder="Value (e.g., Blue)" list="${valueDatalistId}">
                                <datalist id="${valueDatalistId}">
                                    <!-- Options will be populated dynamically based on key -->
                                </datalist>

                                <button type="button" class="btn btn-outline-danger remove-attribute-btn">&times;</button>
                            </div>`;
                }

                $(document).on('input', '.attribute-key', function() {
                    const key = $(this).val().trim();
                    const $row = $(this).closest('.attribute-row');
                    const $valueInput = $row.find('.attribute-value');
                    const $valueDatalist = $row.find('datalist');

                    $valueInput.val('');

                    $valueDatalist.empty();
                    if (key && attributeData.values && attributeData.values[key]) {
                        attributeData.values[key].forEach(val => {
                            $valueDatalist.append(`<option value="${val}">`);
                        });
                    }
                });

                $(document).on('click', '.generate-sku-btn', function() {
                    const randomSKU = crypto.randomUUID().split('-')[0].toUpperCase();
                    $(this).siblings('.variant-sku').val(randomSKU);
                });

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
                                        <span class="badge bg-secondary ms-2 variant-sku-display">SKU: N/A</span>
                                    </button>
                                    ${variantCounter > 1
                                        ? `<button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 mt-2 me-2 remove-variant-btn" data-variant-id="${id}">Remove</button>`
                                        : ''}
                                </div>
                
                                <div id="${collapseId}" class="collapse show">
                                    <div class="card-body p-3">
                                        <div class="row g-2 mb-3 align-items-end">
                                            <div class="col-md-3">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control variant-sku" placeholder="Variant SKU">
                                                    <button class="btn btn-outline-secondary generate-sku-btn" type="button">Generate</button>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <input type="number" class="form-control form-control-sm variant-buying-price" min="0.01" step="0.01" placeholder="Buying Price ({{ currency() }})">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control form-control-sm variant-selling-price" min="0.01" step="0.01" placeholder="selling Price ({{ currency() }})">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control form-control-sm variant-stock" min="0" step="1" placeholder="Stock">
                                            </div>
                                        </div>
                
                                        <h6 class="mt-2 mb-2 text-success">Attributes</h6>
                                        <div class="attributes-container">${createAttributeRow()}</div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary add-attribute-btn mt-2">+ Attribute</button>
                                    </div>
                                </div>
                            </div>
                        `;
                };

                const addVariant = () => {
                    $variantsContainer.append($(createVariantCard()));
                };

                const collectVariants = () => {
                    const variants = [];

                    $('.variant-card').each(function() {
                        const id = $(this).attr('id');
                        const sku = $(this).find('.variant-sku').val().trim();
                        const buying_price = parseFloat($(this).find('.variant-buying-price').val()) || 0;
                        const selling_price = parseFloat($(this).find('.variant-selling-price').val()) || 0;
                        const stock = parseInt($(this).find('.variant-stock').val()) || 0;

                        const attributes = [];
                        $(this).find('.attribute-row').each(function() {
                            const key = $(this).find('.attribute-key').val().trim();
                            const value = $(this).find('.attribute-value').val().trim();
                            if (key && value) attributes.push({
                                key,
                                value
                            });
                        });

                        variants.push({
                            sku,
                            buying_price,
                            selling_price,
                            stock,
                            attributes
                        });
                    });

                    return variants;
                };

                $(document).on('click', '#addVariantBtn', addVariant);
                $(document).on('click', '.remove-variant-btn', function() {
                    const variantId = $(this).data('variant-id');
                    $(`#${variantId}`).remove();
                    variantImageData.delete(variantId);
                });
                $(document).on('click', '.add-attribute-btn', function() {
                    $(this).siblings('.attributes-container').append(createAttributeRow());
                });
                $(document).on('click', '.remove-attribute-btn', function() {
                    $(this).closest('.attribute-row').remove();
                });
                $(document).on('input', '.variant-sku', function() {
                    const value = $(this).val().trim() || 'N/A';
                    $(this).closest('.variant-card').find('.variant-sku-display').text(`SKU: ${value}`);
                });
                $(document).on('change', '.variant-image', function() {
                    handleImageUpload(this);
                });

                $('#submitBtn').click(function(e) {
                    e.preventDefault();

                    let form = $('#productForm')[0];
                    let formData = new FormData(form);
                    const variants = [];
                    $('.variant-card').each(function(index) {
                        const $card = $(this);
                        const id = $card.attr('id');

                        const variant = {
                            sku: $card.find('.variant-sku').val(),
                            buying_price: $card.find('.variant-buying-price').val(),
                            selling_price: $card.find('.variant-selling-price').val(),
                            stock: $card.find('.variant-stock').val(),
                            attributes: []
                        };

                        $card.find('.attribute-row').each(function() {
                            const key = $(this).find('.attribute-key').val();
                            const value = $(this).find('.attribute-value').val();
                            if (key && value) {
                                variant.attributes.push({
                                    key,
                                    value
                                });
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
                            setTimeout(function() {
                                window.location.href =
                                    "{{ route('seller.products.index') }}";
                            }, 1500);
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
@endsection
