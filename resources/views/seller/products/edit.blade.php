@extends('seller.layouts.app')
@section('title', 'Edit Product')
@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Edit Product</h4>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form id="form" enctype="multipart/form-data" method="POST">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Name ( {{ $product->id }})</label>
                            <input name="name" type="text" value="{{ old('name', $product->name) }}"
                                class="form-control" required>
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
                            <select name="brand_id" class="form-select w-100" required>
                                <option value="" disabled>--Choose--</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ $brand->id == $product->brand_id ? 'selected' : '' }}>{{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Short Description</label>
                            <x-textarea-input name="short_description" :value="old('short_description', $product->short_description)" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Description</label>
                            <x-textarea-input name="description" :value="old('description', $product->description)" />
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">SKU</label>
                            <input name="sku" type="text" value="{{ old('sku', $product->sku) }}"
                                class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Buying Price</label>
                            <input name="buying_price" type="text"
                                value="{{ old('buying_price', $product->buying_price) }}" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Selling Price</label>
                            <input name="selling_price" type="text"
                                value="{{ old('selling_price', $product->selling_price) }}" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Tax</label>
                            <input name="tax" type="number" value="{{ old('tax', $product->tax) }}"
                                class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select w-100" id="" required>
                                <option value="" selected disabled>--Choose--</option>
                                <option value="{{ \App\Enums\DiscountType::FLAT }}"
                                    {{ \App\Enums\DiscountType::FLAT == $product->discount_type ? 'selected' : '' }}>
                                    {{ ucfirst(\App\Enums\DiscountType::FLAT) }}</option>
                                <option value="{{ \App\Enums\DiscountType::PERCENTAGE }}"
                                    {{ \App\Enums\DiscountType::PERCENTAGE == $product->discount_type ? 'selected' : '' }}>
                                    {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE) }}</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Discount Amount</label>
                            <input name="discount_amount" type="number"
                                value="{{ old('discount_amount', $product->discount_amount) }}" class="form-control"
                                required>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select w-100" id="" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ $unit->id == $product->unit_id ? 'selected' : '' }}>{{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Current stock</label>
                            <input name="stock_in" type="text" value="{{ old('stock_in', $product->stock_in) }}"
                                class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Light Deal Expire Date</label>
                            <input name="lightdeal_expired_at" type="date"
                                value="{{ old('lightdeal_expired_at', $product->lightdeal_expired_at->format('Y-m-d')) }}"
                                class="form-control">
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="gap-3 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_trending" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_trending"
                                        {{ $product->is_trending ? 'checked' : '' }} value="1" role="switch"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Trending</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="best_selling" value="0">
                                    <input class="form-check-input" type="checkbox" name="best_selling"
                                        {{ $product->best_selling ? 'checked' : '' }} value="1" role="switch"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Best Selling</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                        {{ $product->is_featured ? 'checked' : '' }} value="1" role="switch"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Featured</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_interest" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_interest"
                                        {{ $product->is_interest ? 'checked' : '' }} value="1" role="switch"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Interest Products</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_community" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_community"
                                        {{ $product->is_community ? 'checked' : '' }} value="1" role="switch"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Community
                                        Products</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_lightdeal" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_lightdeal"
                                        {{ $product->is_lightdeal ? 'checked' : '' }} value="1" role="switch"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Light Deal</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-12">
                            <label class="form-label">Thumbnail</label>
                            <x-image-input name="thumbnail" :image="storage_url($product->thumbnail)" />
                        </div>

                        <div class="col-12">
                            <label class="form-label">Product Video</label>
                            @if ($product->video)
                                <div>
                                    <video width="320" height="240" controls>
                                        <source src="{{ asset('storage/' . $product->video) }}">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

                            <!-- File Input for New Video -->
                            <input type="file" id="video" class="mb-2 form-control" name="video">
                        </div>


                        <div class="col-12">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" id="files" class="mb-2 form-control" name="files[]" multiple>
                            <div id="selectedImages" class="mb-2 row">
                                @foreach ($product->images as $image)
                                    <div class="mb-2 col-2">
                                        <img src="{{ asset('storage/' . $image->image) }}" alt="image" class="col-2"
                                            style="width: 100%; height: 150px;">
                                        <button type="button" class="mt-2 btn btn-danger btn-sm" style="width: 50%"
                                            onclick="deleteImage({{ $image->id }})">Delete
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-theme">Update</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById("files").addEventListener("change", function(event) {
                var selectedFiles = event.target.files;
                var imageContainer = document.getElementById("selectedImages");

                imageContainer.innerHTML = "";

                for (var i = 0; i < selectedFiles.length; i++) {
                    var file = selectedFiles[i];
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imgElement = document.createElement("img");
                        imgElement.src = e.target.result;
                        imgElement.classList.add("col-2");
                        imgElement.style.width = "100%";
                        imgElement.style.height = "150px";

                        var deleteButton = document.createElement("button");
                        deleteButton.innerHTML = "Delete";
                        deleteButton.classList.add("btn", "btn-danger", "btn-sm", "mt-2");
                        deleteButton.style.width = "50%";

                        var imageWrapper = document.createElement("div");
                        imageWrapper.classList.add("col-2", "mb-2");
                        imageWrapper.appendChild(imgElement);
                        imageWrapper.appendChild(deleteButton);

                        imageContainer.appendChild(imageWrapper);

                        deleteButton.addEventListener("click", function() {
                            imageContainer.removeChild(imageWrapper);
                        });
                    };

                    reader.readAsDataURL(file);
                }
            });

            $(document).ready(function() {
                if (!{{ $product->subcategory_id ? 'true' : 'false' }}) {
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

            $("#form").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{{ route('seller.products.update', $product->id) }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        location.reload();
                    },
                    error: function(error) {
                        alert('Something went wrong');
                    }
                });
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
        </script>
    @endpush

@endsection
