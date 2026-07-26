@extends('seller.layouts.app')
@section('title', 'Add Product')

@section('content')

    <div class="card">
        <div class="card-header bg-white">
            <h4 class="mb-0">Add Product</h4>
        </div>
        <div class="card-body">
            <div id="alertBox"></div>
            <form id="form" method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
                @CSRF
                <div class="row">
                    <div class="mb-3 col-md-8">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" value="" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">SKU</label>
                        <input name="sku" type="text" value="{{ \App\Domain\Product\Models\ProductVariant::generate_sku() }}"
                            class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select w-100" id="categorySelect" required>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">Subcategory</label>
                        <select name="subcategory_id" class="form-select w-100" id="subcategorySelect" disabled>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($categories as $category)
                                @foreach ($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}">
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">Brand</label>
                        <select name="brand" class="form-select w-100 brand-select" required>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- <div class="mb-3 col-md-6">
                                    <label class="form-label">Short Description</label>
                                    <x-textarea-input name="short_description" value="" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Description</label>
                                    <x-textarea-input name="description" value="" />
                                </div> -->

                    <div class="mb-3 col-md-3">
                        <label class="form-label">Cost Price</label>
                        <input name="cost_price" type="number" value="" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-3">
                        <label class="form-label">Price</label>
                        <input name="price" type="number" value="" class="form-control" required>
                    </div>


                    <div class="mb-3 col-md-3">
                        <label class="form-label">Payment Type</label>
                        <select name="payment_type" class="form-select w-100" required>
                            @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}">
                                    {{ $paymentType->title() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-3">
                        <label class="form-label">Compare Price</label>
                        <input name="compare_price" type="number" step="0.01" min="0" value="" class="form-control" placeholder="Optional sale price">
                    </div>

                    <div class="mb-3 col-md-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-6">
                                <label class="form-label">Unit Value</label>
                                <input type="number" name="unit_value" class="form-control" placeholder="Enter value"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Unit</label>
                                <select name="unit_id" class="form-select" required>
                                    <option value="" disabled selected>--</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-3">
                        <label class="form-label">Low Stock Quantity</label>
                        <input name="low_stock_quantity" type="number" value="" class="form-control" required>
                    </div>

                    <div class="mb-3 col-12">
                        <label class="form-label">Thumbnail <span class="text-muted small">(Ratio 1:1)</span></label>
                        <label for="inputImage" class="text-primary text-decoration-underline" style="cursor: pointer">
                            Crop Image First
                        </label>
                        <input type="file" id="inputImage" hidden accept="image/*" />
                        <div style="width: 250px;">
                            <div class="form-group">
                                <div class="image-preview border bg-light d-flex justify-content-center text-center align-items-center position-relative"
                                    style="width: 200px; height: 200px; cursor: pointer; overflow: hidden;">
                                    <img src="{{ asset('assets/frontend/images/default.png') }}" alt="image"
                                        class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <input type="file" name="thumbnail" class="d-none file-input" accept="image/*">
                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-image d-none">Remove
                                    Image</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="submitBtn" class="btn btn-theme">Save Product</button>
            </form>

        </div>
    </div>

    <!-- Image Cropper Modal -->
    <!-- <div class="modal fade" id="thumbnailCropperModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
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
        </div> -->

@endsection

@push('scripts')
    <x-seller.image-cropper-modal />

    <script>
        $(".brand-select").select2({
            tags: true,
            theme: "bootstrap-5",
        });

        $("#files").on("change", function(event) {
            const selectedFiles = event.target.files;
            const $imageContainer = $("#selectedImages");

            $imageContainer.empty();

            $.each(selectedFiles, function(i, file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const $wrapper = $("<div>", {
                        class: "col-6 col-sm-4 col-md-3 col-lg-2 text-center"
                    });

                    const $img = $("<img>", {
                        src: e.target.result,
                        class: "img-fluid rounded",
                        css: {
                            height: "150px",
                            objectFit: "cover"
                        }
                    });

                    const $btn = $("<button>", {
                        type: "button",
                        text: "Delete",
                        class: "btn btn-danger btn-sm mt-2 w-100"
                    });

                    $btn.on("click", function() {
                        $wrapper.remove();
                    });

                    $wrapper.append($img).append($btn);
                    $imageContainer.append($wrapper);
                };

                reader.readAsDataURL(file);
            });
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

        $('#submitBtn').click(function(e) {
            e.preventDefault();

            let form = $('#form')[0];
            let formData = new FormData(form);
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
                    showSuccessToast('Product added successfully!');

                    setTimeout(function() {
                        window.location.href = "{{ route('seller.products.index') }}";
                    }, 1500);
                },
                error: function(xhr) {
                    $('#submitBtn').attr('disabled', false).text('Save');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors).map(item => item[0]).join('<br>');
                        showErrorToast(messages);
                    } else {
                        showErrorToast('Something went wrong. Please try again.');
                    }
                }
            });
        });
    </script>

    <!-- <script>
        let cropper;
        let croppedBlob = null;

        const imageInputComponent = document.querySelector('input[name="thumbnail"]');
        const imagePreviewDiv = imageInputComponent.closest('.form-group').querySelector('.image-preview');
        const removeImageBtn = imageInputComponent.closest('.form-group').querySelector('.remove-image');

        const modal = new bootstrap.Modal(document.getElementById('thumbnailCropperModal'));

        // imagePreviewDiv.addEventListener('click', function(e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     modal.show();
        // });

        const thumbnailInput = document.getElementById('thumbnailUploadInput');
        const cropperImage = document.getElementById('thumbnailCropperImage');
        const cropButton = document.getElementById('cropThumbnailBtn');

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
                //     // responsive: true
                //     autoCropArea: 0.8,
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
            if (cropper) {
                const cropperOptions = {
                    // width: 800,
                    // height: 800,
                    width: 900,
                    height: 1200,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                };

                cropper.getCroppedCanvas(cropperOptions).toBlob(function(blob) {
                    croppedBlob = blob;

                    const previewURL = URL.createObjectURL(blob);

                    imagePreviewDiv.innerHTML =
                        `<img src="${previewURL}" class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" style="z-index: 1;">`;
                    removeImageBtn.classList.remove('d-none');

                    // Convert blob to File and set it on original input
                    const file = new File([blob], "thumbnail.png", {
                        type: 'image/png'
                    });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    imageInputComponent.files = dataTransfer.files;

                    modal.hide();
                    thumbnailInput.value = '';
                    cropperImage.classList.add('d-none');
                    if (cropper) cropper.destroy();
                    cropper = null;
                }, 'image/png');
            }
        });

        document.getElementById('closeCropperModalBtn').addEventListener('click', () => {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            thumbnailInput.value = '';
            cropperImage.classList.add('d-none');
        });
    </script> -->
@endpush
