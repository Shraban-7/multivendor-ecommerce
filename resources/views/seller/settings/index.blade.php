@extends('seller.layouts.app')

@section('title', 'Business Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="mb-0">Settings</h4>
    </div>
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card card-body">
                <form id="businessSettingsForm" action="{{ route('seller.settings.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name"
                                value="{{ old('business_name', $seller->business_name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="business_email" class="form-label">Business Email</label>
                            <input type="email" class="form-control" id="business_email" name="business_email"
                                value="{{ old('business_email', $seller->business_email) }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="business_address" class="form-label">Business Address</label>
                            <textarea name="business_address" id="business_address" class="form-control" rows="2">{{ old('business_address', $seller->business_address) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nid_no" class="form-label">Nid Number</label>
                            <input type="text" class="form-control" id="nid_no" name="nid_no"
                                value="{{ old('nid_no', $seller->nid_no ?? 'Not provided') }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trade_license_no" class="form-label">Trade License Number</label>
                            <input type="text" class="form-control " id="trade_license_no"
                                value="{{ old('trade_license_no', $seller->trade_license_no ?? 'Not provided') }}" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="trade_license_no" class="form-label">Shipping Cost</label>
                            <input type="number" class="form-control" id="shipping_cost" name="shipping_cost"
                                value="{{ old('shipping_cost', $seller->shipping_cost ?? 'Not provided') }}">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3">
                            <label class="form-label">Business Logo</label>
                            <x-image-input name="business_logo" :image="storage_url($seller->business_logo)" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shop Image</label>
                            <x-image-input name="shop_image" :image="storage_url($seller->shop_image)" />
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">NID Front Image</label><br>
                            <img src="{{ storage_url($seller->nid_front_image) }}" alt="NID Front"
                                class="img-fluid rounded img-thumbnail w-100" style="max-height: 200px;">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">NID Back Image</label><br>
                            <img src="{{ storage_url($seller->nid_back_image) }}" alt="NID Back"
                                class="img-fluid rounded img-thumbnail w-100" style="max-height: 200px;">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Trade License Image</label><br>
                            <img src="{{ storage_url($seller->trade_license_image) }}" alt="Trade License"
                                class="img-fluid rounded img-thumbnail" style="max-height: 200px;">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Banner</label>
                            <input type="file" id="files" class="mb-2 form-control" name="files[]" multiple accept="image">
                            <div id="selectedImages" class="mb-2 row">
                                @foreach ($seller->banner_images as $image)
                                    <div class="mb-2 col-2">
                                        <img src="{{ storage_url($image->image) }}" alt="image" class="col-2"
                                            style="width: 100%; height: 150px;">
                                            <form action="{{ route('seller.bannerImages.delete',$image->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="mt-2 btn btn-danger btn-sm" style="width: 50%" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                function loadStates(countryID, selectedStateID = null) {
                    if (countryID) {
                        $('#state_id').html('<option value="">Loading...</option>');
                        $.ajax({
                            url: baseGetStatesUrl + countryID,
                            type: "GET",
                            dataType: "json",
                            success: function(states) {
                                $('#state_id').empty().append('<option value="">Select State</option>');
                                $.each(states, function(key, state) {
                                    $('#state_id').append(
                                        `<option value="${state.id}">${state.name}</option>`
                                    );
                                });

                                if (selectedStateID) {
                                    $('#state_id').val(selectedStateID);
                                }
                            }
                        });
                    } else {
                        $('#state_id').html('<option value="">Select State</option>');
                    }
                }

                if (initialCountryId) {
                    loadStates(initialCountryId, initialStateId);
                }

                $('#country_id').on('change', function() {
                    const countryID = $(this).val();
                    loadStates(countryID);
                });

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
            });
        </script>
    @endpush
@endsection
