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
    <div class="card mb-3">
        <div class="card-header bg-white">
            <h4 class="mb-0">Add Product</h4>
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
                            <label class="form-label">SKU</label>
                            <input name="sku" type="text" value="{{ \App\Models\ProductVariant::generate_sku() }}" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit Value</label>
                            <input type="number" name="unit_value" class="form-control form-control-sm" placeholder="Enter value" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select form-select-sm" required>
                                <option disabled selected>--</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                                @endforeach
                            </select>
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
                            <select name="brand" class="form-select form-select-sm brand-select" required>
                                <option disabled selected>-- Select Brand --</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Buying Price</label>
                            <input name="buying_price" type="number" min="0" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Selling Price</label>
                            <input name="selling_price" type="number" min="0" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">VAT (%)</label>
                            <input name="vat_percent" type="number" min="0" class="form-control form-control-sm" required>
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
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select form-select-sm">
                                <option value="">-- None --</option>
                                <option value="{{ \App\Enums\DiscountType::FLAT->value }}">Flat</option>
                                <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">Percentage</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Value</label>
                            <input name="discount_value" type="number" class="form-control form-control-sm">
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
                    <span class="text-muted small mt-2 text-center">NB: JPG/PNG/WEBP only, max 4MB</span>
                </div>
            </div>
        </div>
    </div>
    @include('seller.products.variant-generator')
    <div class="d-flex justify-content-end">
        <button type="button" id="submitBtn" class="btn btn-primary">Save Product</button>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<x-seller.image-cropper-modal />

<script>
    $(function() {
        $('.option_values').select2({
            tags: true,
            placeholder: 'Select or type a value',
            dropdownParent: null,
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

        $(".brand-select").select2({
            tags: true,
            theme: "bootstrap-5",
        });

        let selectedCategoryId = null;

        $('#categorySelect').change(function() {
            selectedCategoryId = $(this).val();
            showVariantOptions(selectedCategoryId);
        });

        function showVariantOptions(categoryId) {
            $('.attributeColumn').addClass('d-none');
            const $visibleColumns = $('.attributeColumn[data-category="' + categoryId + '"]').removeClass('d-none');
            if ($visibleColumns.length > 0) {
                $('#variantGenerator').removeClass('d-none');
            } else {
                $('#variantGenerator').addClass('d-none');
            }
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
            //         buying_price: $card.find('.variant-buying-price').val() || null,
            //         selling_price: $card.find('.variant-selling-price').val() || null,
            //         variant_discount_type: $card.find('.variant-discount-type').val() || null,
            //         variant_discount_value: $card.find('.variant-discount-value').val() || null,
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

        function collectVariantsData() {
            const variantBody = document.getElementById("variantsTableBody");
            if (!variantBody) return [];

            const variantRows = variantBody.querySelectorAll("tr");
            const variants = [];

            // Get headers only from THIS table
            const table = variantBody.closest("table");
            const headerCells = table.querySelectorAll("thead th");

            // Extract only attribute columns (skip fixed ones)
            const skipColumns = [
                "#",
                "SKU",
                "Buying Price",
                "Selling Price",
                "Discount Type",
                "Discount Value",
                "Image",
                "Actions",
            ];

            const attributeHeaders = Array.from(headerCells)
                .map((cell) => cell.textContent.trim())
                .filter((title) => !skipColumns.includes(title) && title !== "");

            variantRows.forEach((row) => {
                const variant = {};
                variant.sku =
                    row.querySelector('td:nth-child(2) input')?.value.trim() || "";

                // Collect attribute values
                variant.attributes = {};

                attributeHeaders.forEach((title, i) => {
                    // +3 because the 1st column is #, 2nd is SKU
                    const cellInput = row.querySelector(`td:nth-child(${i + 3}) input`);
                    variant.attributes[title] = cellInput?.value?.trim() || "";
                });

                // Prices
                const colStart = 3 + attributeHeaders.length;
                const buyingPriceInput = row.querySelector(
                    `td:nth-child(${colStart}) input`
                );
                const sellingPriceInput = row.querySelector(
                    `td:nth-child(${colStart + 1}) input`
                );
                const discountTypeSelect = row.querySelector(".variant-discount-type");
                const discountValueInput = row.querySelector(".variant-discount-value");
                const imageInput = row.querySelector('input[type="file"]');

                variant.buying_price = buyingPriceInput?.value || "";
                variant.selling_price = sellingPriceInput?.value || "";
                variant.discount_type = discountTypeSelect?.value || "none";
                variant.discount_value = discountValueInput?.value || "";
                variant.image = imageInput?.files?.[0] || null;

                variants.push(variant);
            });

            return variants;
        }
    });
</script>
@endpush