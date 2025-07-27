@extends('seller.layouts.app')
@section('title', 'Add Product')

@push('styles')
<link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-end">
    <h4 class="mb-0">Add Product</h4>
</div>

<div id="alertBox"></div>

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <form id="form" method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
                @CSRF
                <div class="row">
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" value="" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select w-100" id="categorySelect" required>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-3">
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

                    <div class="mb-3 col-md-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select w-100" id="" required>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Short Description</label>
                        <x-textarea-input name="short_description" value="" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Description</label>
                        <x-textarea-input name="description" value="" />
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">SKU</label>
                        <input name="sku" type="text" value="{{ strtoupper(uniqid()) }}" class="form-control">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Cost Price</label>
                        <input name="buying_price" type="number" value="" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Selling Price</label>
                        <input name="selling_price" type="number" value="" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Tax</label>
                        <input name="tax" type="number" value="" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" class="form-select w-100" id="" required>
                            <option value="" selected disabled>--Choose--</option>
                            <option value="{{ \App\Enums\DiscountType::FLAT->value }}">
                                {{ ucfirst(\App\Enums\DiscountType::FLAT->label()) }}
                            </option>
                            <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">
                                {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE->label()) }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Discount Value</label>
                        <input name="discount_value" type="number" value="" class="form-control" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Unit <small class="text-muted">(e.g., 2.5 kg)</small></label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" step="0.01" name="unit_value" class="form-control form-control"
                                placeholder="Value" style="width: 60%;" required>
                            <select name="unit_id" class="form-select form-select" style="width: 40%;" required>
                                <option value="" disabled>--</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">
                                    {{ $unit->short_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Low Stock Quantity</label>
                        <input name="low_stock_quantity" type="number" value="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_trending" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_trending"
                                        value="1" role="switch" id="is_trending">
                                    <label class="form-check-label" for="is_trending">Trending</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="best_selling" value="0">
                                    <input class="form-check-input" type="checkbox" name="best_selling"
                                        value="1" role="switch" id="best_selling">
                                    <label class="form-check-label" for="best_selling">Best Selling</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                        value="1" role="switch" id="is_featured">
                                    <label class="form-check-label" for="is_featured">Featured</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_interest" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_interest"
                                        value="1" role="switch" id="is_interest">
                                    <label class="form-check-label" for="is_interest">Interest Products</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_community" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_community"
                                        value="1" role="switch" id="is_community">
                                    <label class="form-check-label" for="is_community">Community Products</label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-3 col-12">
                        <label class="form-label">Thumbnail</label>
                        <x-image-input name="thumbnail" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Product Video</label>
                        <input type="file" id="video" class="mb-2 form-control" name="video">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Gallery Images</label>
                        <input type="file" id="files" class="mb-2 form-control" name="files[]" multiple>

                        <div id="selectedImages" class="row g-2"></div>
                    </div>
                </div>
                <button type="button" id="submitBtn" class="btn btn-theme">Save</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeCropperModalBtn"></button>
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
    document.getElementById("files").addEventListener("change", function(event) {
        const selectedFiles = event.target.files;
        const imageContainer = document.getElementById("selectedImages");

        imageContainer.innerHTML = "";

        Array.from(selectedFiles).forEach(file => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const wrapper = document.createElement("div");
                wrapper.className = "col-6 col-sm-4 col-md-3 col-lg-2 text-center";

                const img = document.createElement("img");
                img.src = e.target.result;
                img.className = "img-fluid rounded";
                img.style.height = "150px";
                img.style.objectFit = "cover";

                const btn = document.createElement("button");
                btn.type = "button";
                btn.innerHTML = "Delete";
                btn.className = "btn btn-danger btn-sm mt-2 w-100";

                btn.addEventListener("click", () => {
                    wrapper.remove();
                });

                wrapper.appendChild(img);
                wrapper.appendChild(btn);
                imageContainer.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        });
    });;

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

        $('#alertBox').html('');

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
                $('#alertBox').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Product added successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);

                setTimeout(function() {
                    window.location.href = "{{ route('seller.products.index') }}";
                }, 1500);
            },
            error: function(xhr) {
                $('#submitBtn').attr('disabled', false).text('Save');

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
                    $('#alertBox').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Something went wrong. Please try again.
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
    let croppedBlob = null;

    const imageInputComponent = document.querySelector('input[name="thumbnail"]');
    const imagePreviewDiv = imageInputComponent.closest('.form-group').querySelector('.image-preview');
    const removeImageBtn = imageInputComponent.closest('.form-group').querySelector('.remove-image');

    const modal = new bootstrap.Modal(document.getElementById('thumbnailCropperModal'));

    imagePreviewDiv.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        modal.show();
    });

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
            cropper = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 2,
                autoCropArea: 1,
                responsive: true
            });
        };
        reader.readAsDataURL(file);
    });

    cropButton.addEventListener('click', function() {
        if (cropper) {
            cropper.getCroppedCanvas().toBlob(function(blob) {
                croppedBlob = blob;

                const previewURL = URL.createObjectURL(blob);

                imagePreviewDiv.innerHTML = `<img src="${previewURL}" class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" style="z-index: 1;">`;
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
</script>
@endpush

@endsection