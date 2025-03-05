@extends('seller.layouts.app')
@section('title', 'Add Product')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Add Product</h4>
</div>


<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <form id="form" enctype="multipart/form-data">
                @CSRF
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select w-100" id="categorySelect" required>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Subcategory</label>
                        <select name="subcategory_id" class="form-select w-100" id="subcategorySelect" disabled>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($categories as $category)
                            @foreach ($category->subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" data-category="{{ $category->id  }}">{{ $subcategory->name }}</option>
                            @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select w-100" id="" required>
                            <option value="" selected disabled>--Choose--</option>
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" value="" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Slug</label>
                        <input name="slug" type="text" value="" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sku</label>
                        <input name="sku" type="text" value="" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Buying Price</label>
                        <input name="buying_price" type="text" value="" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Selling Price</label>
                        <input name="selling_price" type="text" value="" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Current stock</label>
                        <input name="stock_in" type="text" value="" class="form-control" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Thumbnail</label>
                        <x-image-input name="thumbnail" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Gallery Images</label>
                        <input type="file" id="files" class="form-control mb-2" name="files[]" multiple>
                        <div id="selectedImages" class="row mb-2">
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
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ route('seller.products.store') }}",
            data: formData,
            contentType: false, 
            processData: false,
            success: function(data) {
                location.reload();
                // if ($.isEmptyObject(data.error)) {
                //     alert(data.success);
                //     location.reload();
                // } else {
                //     printErrorMsg(data.error);
                // }
            }
        });
    });
</script>
@endpush

@endsection