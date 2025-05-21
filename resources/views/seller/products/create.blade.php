@extends('seller.layouts.app')
@section('title', 'Add Product')
@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Add Product</h4>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <form id="form" enctype="multipart/form-data">
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
                                            {{ $subcategory->name }}</option>
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
                            <input name="sku" type="text" value="" class="form-control">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Buying Price</label>
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
                                    {{ ucfirst(\App\Enums\DiscountType::FLAT->label()) }}</option>
                                <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}">
                                    {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE->label()) }}</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Discount Amount</label>
                            <input name="discount_amount" type="number" value="" class="form-control" required>
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
                            <label class="form-label">Light Deal Expire Date</label>
                            <input name="lightdeal_expired_at" type="date" value="" class="form-control">
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="gap-3 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_trending" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_trending" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Trending</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="best_selling" value="0">
                                    <input class="form-check-input" type="checkbox" name="best_selling" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Best Selling</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Featured</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_interest" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_interest" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Interest Products</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_community" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_community" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Community
                                        Products</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_lightdeal" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_lightdeal" value="1"
                                        role="switch" id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Light Deal</label>
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
                        <div class="col-12">
                            <label class="form-label">Gallery Images</label>
                            <input type="file" id="files" class="mb-2 form-control" name="files[]" multiple>
                            <div id="selectedImages" class="mb-2 row">
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-theme">Save</button>
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

            $("#form").submit(function(e) {
                e.preventDefault();
                var submitBtn = $("#submitBtn");
                submitBtn.prop('disabled', true).text('Saving...');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('seller.products.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Something went wrong!');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Save');
                    }
                });
            });
        </script>
    @endpush

@endsection
