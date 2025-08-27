@extends('seller.layouts.app')
@section('title', 'Edit Product')

@push('styles')
    <link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
@endpush

@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Edit Product</h4>
        <a href="{{ route('seller.products.show', $product->slug) }}" class="btn btn-secondary border ">
            ← Back to Details
        </a>
    </div>
    <div id="alertBox"></div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form id="productUpdateForm" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" value="{{ $product->name }}" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select w-100" id="categorySelect" required>
                                <option value="" disabled>--Choose--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-3">
                            <input type="hidden" name="subcategory_id" id="hiddenSubcategoryId" value="">

                            <label class="form-label">Subcategory</label>
                            <select name="subcategory_id" class="form-select w-100" id="subcategorySelect"
                                {{ $product->subcategory_id ? '' : 'disabled' }}>
                                <option value="" disabled>--Choose--</option>
                                @foreach ($categories as $category)
                                    @foreach ($category->subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}"
                                            {{ $subcategory->id == $product->subcategory_id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>

                        </div>

                        <div class="mb-3 col-md-3">
                            <label class="form-label">Brand</label>
                            <select name="brand" class="form-select w-100 brand-select">
                                <option value="" >--Choose--</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ isset($product) && $product->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Short Description</label>
                            <x-textarea-input name="short_description" :value="$product->short_description" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Description</label>
                            <x-textarea-input name="description" :value="$product->description" />
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">VAT (%)</label>
                            <input name="vat_percent" type="number" value="{{ $product->vat_percent }}"
                                class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Unit <small class="text-muted">(e.g., 2.5 kg)</small></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" step="0.01" name="unit_value" value="{{ $product->unit_value }}"
                                    class="form-control form-control" placeholder="Value" style="width: 60%;" required>
                                <select name="unit_id" class="form-select form-select" style="width: 40%;" required>
                                    <option value="" disabled {{ $product->unit_id === null ? 'selected' : '' }}>--
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

                        <div class="mb-3 col-md-3">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-select w-100">
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                    <option value="{{ $paymentType->value }}" @selected($paymentType->value == $product->payment_type->value)>
                                        {{ $paymentType->title() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-12">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_trending" value="0">
                                        <input class="form-check-input" type="checkbox" name="is_trending"
                                            {{ $product->is_trending ? 'checked' : '' }} value="1" role="switch"
                                            id="is_trending">
                                        <label class="form-check-label" for="is_trending">Trending</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="best_selling" value="0">
                                        <input class="form-check-input" type="checkbox" name="best_selling"
                                            {{ $product->best_selling ? 'checked' : '' }} value="1" role="switch"
                                            id="best_selling">
                                        <label class="form-check-label" for="best_selling">Best Selling</label>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            {{ $product->is_featured ? 'checked' : '' }} value="1" role="switch"
                                            id="is_featured">
                                        <label class="form-check-label" for="is_featured">Featured</label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="mb-3 col-12">
                            <label class="form-label">Thumbnail</label>
                            <!-- <x-image-input name="thumbnail" :image="storage_url($product->thumbnail)" /> -->                            

                            <div style="width: 250px;">
                                <div class="form-group">
                                    <div class="image-preview border bg-light d-flex justify-content-center text-center align-items-center position-relative"
                                        style="width: 200px; height: 200px; cursor: pointer; overflow: hidden;">
                                        <img src="{{ storage_url($product->thumbnail) }}" alt="image" class="img-fluid rounded" 
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <input type="file" name="thumbnail" class="d-none file-input" accept="image/*">
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-image d-none">Remove Image</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Product Video</label>
                            @if ($product->video)
                                <div>
                                    <video width="320" height="240" controls>
                                        <source src="{{ storage_url($product->video) }}">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

                            <!-- File Input for New Video -->
                            <input type="file" id="video" class="mb-2 form-control" name="video">
                        </div>


                        <div class="col-12 mb-3">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" id="files" class="mb-2 form-control" name="files[]" multiple>

                            <div id="selectedImages" class="row g-2">
                                @foreach ($product->images as $image)
                                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                        <div class="position-relative">
                                            <img src="{{ storage_url($image->image) }}" alt="image"
                                                class="img-fluid rounded"
                                                style="height: 150px; object-fit: cover; width: 100%;">

                                            <button type="button" class="btn btn-danger btn-sm w-100 mt-1"
                                                onclick="deleteImage({{ $image->id }})">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <button type="submit" id="updateBtn" class="btn btn-theme">Update</button>
                </form>
            </div>
        </div>
    </div>

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

            function deleteImage(imageId) {
                var url = "{{ route('seller.products.image.delete', ':id') }}".replace(':id', imageId);
                if (confirm("Are you sure you want to delete this image?")) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        method: "DELETE",
                        success: function(response) {
                            location.reload();
                        },
                        error: function(error) {
                            alert('Something went wrong');
                        }
                    });
                }
            }

            $('#productUpdateForm').submit(function(e) {
                e.preventDefault();

                let form = $('#productUpdateForm')[0];
                let formData = new FormData(form);

                $('#alertBox').html('');

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
                        $('#updateBtn').attr('disabled', false).text('Update');
                        $('#alertBox').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Product updated successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500);
                    },
                    error: function(xhr) {
                        $('#updateBtn').attr('disabled', false).text('Update');

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = Object.values(errors).map(item => `<div>${item[0]}</div>`).join(
                                '');
                            $('#alertBox').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${messages}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                        } else {
                            let errorMessage = "Something went wrong. Please try again.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                errorMessage = xhr.responseText;
                            }

                            $('#alertBox').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${errorMessage}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
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

@endsection
