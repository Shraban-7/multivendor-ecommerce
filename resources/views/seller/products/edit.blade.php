@extends('seller.layouts.app')
@section('title', 'Edit Product')

@push('styles')
    <link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
@endpush

@section('content')

    <div class="d-flex align-items-end justify-content-between mb-3">
        <h4 class="fw-semibold mb-0">Edit Product</h4>
        <a href="{{ route('seller.products.show', $product->slug) }}" class="btn btn-secondary btn-sm">← Back to Details</a>
    </div>
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-4">
            <form id="productUpdateForm" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-12 col-lg-8">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Product Name</label>
                                <input type="text" class="form-control form-control-sm" value="{{ $product->name }}"
                                    name="name" required />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Brand</label>
                                <select name="brand" class="form-select form-select-sm">
                                    <option value="">--Choose--</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="category_id" class="form-select form-select-sm" id="categorySelect" required>
                                    <option value="" disabled>--Choose--</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected($category->id == $product->category_id)>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Subcategory</label>
                                <select name="subcategory_id" class="form-select form-select-sm" id="subcategorySelect"
                                    {{ $product->subcategory_id ? '' : 'disabled' }}>
                                    <option value="" disabled>--Choose--</option>
                                    @foreach ($categories as $category)
                                        @foreach ($category->subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}"
                                                @selected($subcategory->id == $product->subcategory_id)>
                                                {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Unit <small class="text-muted">(e.g., 2.5 kg)</small></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="unit_value"
                                        value="{{ $product->unit_value }}" class="form-control form-control-sm"
                                        placeholder="Value" style="width: 60%;" required>
                                    <select name="unit_id" class="form-select form-select-sm" style="width: 40%;" required>
                                        <option value="" disabled {{ $product->unit_id === null ? 'selected' : '' }}>
                                            --
                                        </option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ $product->unit_id == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->short_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Payment Type</label>
                                <select name="payment_type" class="form-select form-select-sm w-100">
                                    @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                        <option value="{{ $paymentType->value }}" @selected($paymentType->value == $product->payment_type->value)>
                                            {{ $paymentType->title() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Low Stock Quantity</label>
                                <input name="low_stock_quantity" type="number" min="0"
                                    class="form-control form-control-sm" value="{{ $product->low_stock_quantity }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Buying Price</label>
                                <input type="text" name="buying_price" class="form-control form-control-sm"
                                    value="{{ $product->buying_price }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Selling Price</label>
                                <input type="text" name="selling_price" class="form-control form-control-sm"
                                    value="{{ $product->selling_price }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" class="form-select form-select-sm">
                                    <option value="">-- None --</option>
                                    <option
                                        {{ \App\Enums\DiscountType::FLAT->value == $product->discount_type ? 'selected' : '' }}
                                        value="{{ \App\Enums\DiscountType::FLAT->value }}">Flat</option>
                                    <option
                                        {{ \App\Enums\DiscountType::PERCENTAGE->value == $product->discount_type ? 'selected' : '' }}
                                        value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">Percentage</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Discount Value</label>
                                <input name="discount_value" type="number" class="form-control form-control-sm"
                                    value="{{ $product->discount_value }}">
                            </div>

                            @if ($product->variants_count > 0)
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="useMainPrices"
                                            name="useMainPrices">
                                        <label class="form-check-label fw-semibold" for="useMainPrices">
                                            Use main prices for all variants
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="useMainDiscount"
                                            name="useMainDiscount">
                                        <label class="form-check-label fw-semibold" for="useMainDiscount">
                                            Use main discount for all variants
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="col-12">
                                <label class="form-label">Short Description</label>
                                <x-textarea-input name="short_description" :value="$product->short_description" />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <x-textarea-input name="description" :value="$product->description" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3 d-flex justify-content-center">
                            <div>
                                <label class="form-label">Thumbnail <span class="text-muted small">(Ratio
                                        1:1)</span></label>
                                <div style="width: 250px;">
                                    <div class="form-group">
                                        <div class="image-preview border bg-light d-flex justify-content-center text-center align-items-center position-relative"
                                            style="width: 200px; height: 200px; cursor: pointer; overflow: hidden;">
                                            <img src="{{ $product->imageUrl }}" alt="image" class="img-fluid rounded"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <input type="file" name="thumbnail" class="d-none file-input"
                                            accept="image/*">
                                        <button type="button"
                                            class="btn btn-danger btn-sm mt-2 remove-image d-none">Remove
                                            Image</button>
                                    </div>
                                </div>
                                <span class="text-muted small mt-2">NB: JPG/PNG/WEBP only, max 10MB</span>
                            </div>
                        </div>

                        <div class="mt-3 border-top pt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured"
                                    {{ $product->is_featured ? 'checked' : '' }} />
                                <label class="form-check-label small">Featured Product</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="best_selling"
                                    {{ $product->best_selling ? 'checked' : '' }} />
                                <label class="form-check-label small">Best Selling</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="button" id="updateBtn" class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('seller.products.partials.upload-images')

    @php
        $seo = $product->seo;
    @endphp
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">SEO & Social Share Settings</h5>
        </div>

        <div class="card-body">
            <form id="productSeoForm" enctype="multipart/form-data">
                @csrf
                <h5 class="mb-3">Meta Information (Search Engines)</h5>

                <div class="mb-3">
                    <label class="form-label">Meta Title
                        <small class="text-muted">(max 70 characters)</small>
                    </label>
                    <input type="text" name="meta_title" maxlength="70" class="form-control"
                        placeholder="e.g. Red Cotton T-Shirt – Buy Online" value="{{ $seo?->meta_title }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Description
                        <small class="text-muted">(recommended up to 160 characters)</small>
                    </label>
                    <textarea name="meta_description" maxlength="160" rows="3" class="form-control"
                        placeholder="Short, keyword-rich description shown in Google results.">{{ $seo?->meta_description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Keywords
                        <small class="text-muted">(comma separated)</small>
                    </label>
                    <input type="text" name="meta_keywords" maxlength="255" class="form-control"
                        placeholder="e.g. t-shirt, red cotton shirt, mens fashion" value="{{ $seo?->meta_keywords }}">
                    <small class="text-muted d-block mt-1">
                        *Keywords are optional; modern search engines rely more on content.
                    </small>
                </div>

                <hr class="my-4">

                <!-- Open Graph Section -->
                <h5 class="mb-3">Open Graph (Social Media Preview)</h5>
                <p class="small text-muted">
                    These fields control how the product appears when shared on Facebook, WhatsApp,
                    LinkedIn, etc. If left blank, the Meta Title/Description will be used.
                </p>

                <div class="mb-3">
                    <label class="form-label">OG Title
                        <small class="text-muted">(max 70 characters)</small>
                    </label>
                    <input type="text" name="og_title" maxlength="70" class="form-control"
                        placeholder="Catchy title for social sharing" value="{{ $seo?->og_title }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">OG Description
                        <small class="text-muted">(recommended up to 160 characters)</small>
                    </label>
                    <textarea name="og_description" maxlength="160" rows="3" class="form-control"
                        placeholder="Appears below the title when shared on social media.">{{ $seo?->og_description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">OG Image</label>
                    <input type="file" name="og_image" class="form-control">

                    @if (!empty($seo->og_image))
                        <div class="mt-2">
                            <p class="mb-1">Current OG Image:</p>
                            <img src="{{ storage_url($seo->og_image) }}" alt="OG Image" class="img-thumbnail"
                                style="max-width: 200px;">
                        </div>
                    @endif

                    <small class="text-muted d-block mt-1">
                        Recommended size: <strong>1200 × 630 px</strong>, JPG/PNG/WebP, max 2 MB.
                        This image will be shown as the preview when the link is shared.
                    </small>
                </div>
                <div>
                    <button type="button" id="seoUpdateBtn" class="btn btn-primary">
                        Save SEO Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="alertBox"></div>

    <!-- Image Cropper Modal -->
    <div class="modal fade" id="thumbnailCropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop Thumbnail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="closeCropperModalBtn"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="file" id="thumbnailUploadInput" accept="image/*" class="form-control mb-3">
                    <img id="thumbnailCropperImage" src="#" class="d-none img-fluid" style="max-height: 400px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="cropThumbnailBtn">Crop & Insert</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".brand-select").select2({
            tags: true,
            theme: "bootstrap-5",
        });

        $("#files").on("change", function(event) {
            var selectedFiles = event.target.files;
            var $imageContainer = $("#selectedImages");

            $imageContainer.empty();

            $.each(selectedFiles, function(i, file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var $imgElement = $("<img>", {
                        src: e.target.result,
                        class: "col-2",
                        css: {
                            width: "100%",
                            height: "150px"
                        }
                    });

                    var $deleteButton = $("<button>", {
                        text: "Delete",
                        class: "btn btn-danger btn-sm mt-2",
                        css: {
                            width: "50%"
                        }
                    });

                    var $imageWrapper = $("<div>", {
                        class: "col-2 mb-2"
                    });

                    $imageWrapper.append($imgElement).append($deleteButton);
                    $imageContainer.append($imageWrapper);

                    $deleteButton.on("click", function() {
                        $imageWrapper.remove();
                    });
                };

                reader.readAsDataURL(file);
            });
        });

        $(document).ready(function() {
            if (!"{{ $product->subcategory_id ? 'true' : 'false' }}") {
                $('#subcategorySelect').attr('disabled', true).val('');
                $('#hiddenSubcategoryId').val('');
            }
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
                $('#hiddenSubcategoryId').val(null);
            }

            if (!selectedCategoryId) {
                $('#subcategorySelect').val('').trigger('change');
                $('#hiddenSubcategoryId').val(null);

            }
        });

        $('#updateBtn').on('click', function(e) {
            e.preventDefault();

            let form = $('#productUpdateForm')[0];
            let formData = new FormData(form);

            $.ajax({
                url: "{{ route('seller.products.update', $product->slug) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#updateBtn').attr('disabled', true).text('Updating...');
                },
                success: function(response) {
                    showSuccessToast('Product updated successfully!');

                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                },
                error: function(xhr) {
                    $('#updateBtn').attr('disabled', false).text('Update');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors)
                            .map(item => item[0])
                            .join('<br>');
                        showErrorToast(messages);
                    } else {
                        let errorMessage = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON?.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            errorMessage = xhr.responseText;
                        }
                        showErrorToast(errorMessage);
                    }
                }
            });
        });
    </script>

    <script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <script>
        let cropper;
        const modal = new bootstrap.Modal(document.getElementById('thumbnailCropperModal'));

        const thumbnailInput = document.getElementById('thumbnailUploadInput');
        const cropperImage = document.getElementById('thumbnailCropperImage');
        const cropButton = document.getElementById('cropThumbnailBtn');

        const imageInputComponent = document.querySelector('input[name="thumbnail"]');
        const imagePreviewDiv = imageInputComponent.closest('.form-group').querySelector('.image-preview');
        const removeImageBtn = imageInputComponent.closest('.form-group').querySelector('.remove-image');

        // imagePreviewDiv.addEventListener('click', function(e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     modal.show();
        // });

        thumbnailInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropperImage.classList.remove('d-none');

                if (cropper) cropper.destroy();
                // cropper = new Cropper(cropperImage, {
                //     aspectRatio: 1,
                //     viewMode: 2,
                //     // autoCropArea: 1,
                //     // autoCropArea: 0.8,
                //     minCropBoxWidth: 800,
                //     minCropBoxHeight: 800,
                //     cropBoxResizable: false,
                //     movable: true,
                //     zoomable: true,
                // });
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 3 / 4,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    movable: true,
                    zoomable: true,
                    scalable: false,
                    cropBoxResizable: true,
                });
            };
            reader.readAsDataURL(file);
        });

        cropButton.addEventListener('click', function() {
            if (!cropper) return;

            const cropperOptions = {
                // width: 800,
                // height: 800,
                width: 900,
                height: 1200,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            };

            cropper.getCroppedCanvas(cropperOptions).toBlob(function(blob) {
                const previewURL = URL.createObjectURL(blob);

                imagePreviewDiv.innerHTML =
                    `<img src="${previewURL}" class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" style="z-index: 1;">`;
                removeImageBtn.classList.remove('d-none');

                const file = new File([blob], "thumbnail.png", {
                    type: 'image/png'
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                imageInputComponent.files = dataTransfer.files;

                modal.hide();
                thumbnailInput.value = '';
                cropperImage.classList.add('d-none');
                cropper.destroy();
                cropper = null;
            }, 'image/png');
        });

        document.getElementById('closeCropperModalBtn').addEventListener('click', () => {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            thumbnailInput.value = '';
            cropperImage.classList.add('d-none');
        });
    </script>
@endpush
