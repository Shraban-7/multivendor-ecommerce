@extends('seller.layouts.app')
@section('title', 'Add Product')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    #productForm .form-label {
        margin-bottom: 3px;
        font-size: 14px;
        font-weight: 500;
        color: #6c757d !important;
    }
</style>
@endpush

@section('content')
<form id="productForm" autocomplete="off" method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-header bg-white">
            <h4 class="fw-bold mb-0 text-dark">Add Product</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Product Name</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>
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

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand" class="form-select form-select-sm brand-select">
                                <option disabled selected>-- Select Brand --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unit Value</label>
                            <input type="number" name="unit_value" class="form-control form-control-sm" placeholder="Enter value" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select form-select-sm" required>
                                <option disabled selected>--</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cost Price</label>
                            <input name="cost_price" type="number" min="0" step="0.01" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price</label>
                            <input name="price" type="number" min="0" step="0.01" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-select form-select-sm" required>
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}">{{ $paymentType->title() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Low Stock Quantity</label>
                            <input name="low_stock_quantity" type="number" min="0" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Compare Price <span class="text-muted small">(optional sale)</span></label>
                            <input name="compare_price" type="number" min="0" step="0.01" class="form-control form-control-sm" placeholder="Leave empty for no sale">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" class="form-control form-control-sm" rows="2" placeholder="Brief summary for search results and listings"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="4" placeholder="Full product description with features and details"></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Specifications <span class="text-muted small">(key:value pairs, one per line)</span></label>
                            <textarea name="specifications" class="form-control form-control-sm" rows="3" placeholder="e.g. Material: Cotton&#10;Color: Red&#10;Warranty: 1 Year"></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Country of Origin</label>
                            <input type="text" name="country_of_origin" class="form-control form-control-sm" placeholder="e.g. Bangladesh">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Manufacturer Name</label>
                            <input type="text" name="manufacturer_name" class="form-control form-control-sm" placeholder="Manufacturer name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Manufacturer Details</label>
                            <input type="text" name="manufacturer_details" class="form-control form-control-sm" placeholder="Address / contact">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Tags <span class="text-muted small">(comma separated)</span></label>
                            <input type="text" name="tags" class="form-control form-control-sm" placeholder="e.g. cotton, summer, casual">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control form-control-sm" placeholder="0.00">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control form-control-sm" placeholder="0.00">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="form-control form-control-sm" placeholder="0.00">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="form-control form-control-sm" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
                    <label class="form-label text-center">Product Thumbnail <span class="text-muted small">(1:1 Ratio)</span></label>
                    <div class="mb-3">
                        <x-image-input name="thumbnail" />
                    </div>
                    <label for="inputImage" class="text-primary text-decoration-underline" style="cursor:pointer;">Crop Image First</label>
                    <input type="file" id="inputImage" hidden accept="image/*" />
                    <span class="text-muted small mt-2 text-center">NB: JPG/PNG/WEBP only, max 10MB</span>
                </div>
            </div>
        </div>
    </div>
    @include('seller.products.variant-generator')
    <button type="button" id="submitBtn" class="btn btn-primary d-inline-flex align-items-center gap-1">Save Product</button>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<x-seller.image-cropper-modal />

<script>
    $(function() {
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
        });

                }

        $('#submitBtn').click(function(e) {
            e.preventDefault();

            let form = $('#productForm')[0];
            let formData = new FormData(form);
            const variants = collectVariantsData();

            // $('.variant-card').each(function() {
            //     const $card = $(this);
            //     const variant = {
            //         sku: $card.find('.variant-sku').val()?.trim() || null,
            //         cost_price: $card.find('.variant-buying-price').val() || null,
            //         price: $card.find('.variant-selling-price').val() || null,
            //         stock: $card.find('.variant-stock').val() || null,
            //         attributes: {}
            //     };

            //     $card.find('.attribute-value').each(function() {
            //         const attrName = $(this).data('attribute-name');
            //         if (!attrName) return;

            //         const selectedTexts = $(this).select2('data')
            //             .map(item => item.text.trim())
            //             .filter(v => v && !v.startsWith('-- Select')); // remove placeholder text

            //         if (selectedTexts.length) {
            //             variant.attributes[attrName] = selectedTexts.length === 1 ? selectedTexts[0] : selectedTexts;
            //         }
            //     });

            //     variants.push(variant);
            // });

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
                    showSuccessToast(response.message);
                    setTimeout(() => window.location.href = "{{ route('seller.products.index') }}", 1500);
                },
                error: function(xhr) {
                    $('#submitBtn').attr('disabled', false).text('Save');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors).map(item => item[0]).join(
                            '<br>');
                        showErrorToast(messages);
                    } else {
                        showErrorToast('Something went wrong. Please try again.');
                    }
                }
            });
        });

        function collectVariantsData() {
            const variantBody = document.getElementById("variantsTableBody");
            if (!variantBody) return [];

            const variantRows = variantBody.querySelectorAll("tr");
            const variants = [];

            variantRows.forEach((row) => {
                const colorId = row.dataset.colorId || '';
                const sizeId = row.dataset.sizeId || '';

                const sku = row.querySelector('td:nth-child(2) input')?.value.trim() || '';

                const costPriceInput = row.querySelector('input[placeholder="Cost Price"]');
                const priceInput = row.querySelector('input[placeholder="Price"]');
                const comparePriceInput = row.querySelector('input[placeholder="Compare Price"]');
                const imageInput = row.querySelector('input[type="file"]');

                variants.push({
                    color_id: colorId ? parseInt(colorId) : null,
                    size_id: sizeId ? parseInt(sizeId) : null,
                    sku: sku,
                    cost_price: costPriceInput?.value || '',
                    price: priceInput?.value || '',
                    compare_price: comparePriceInput?.value || '',
                    image: imageInput?.files?.[0] || null,
                });
            });

            return variants;
        }
    });
</script>
@endpush
