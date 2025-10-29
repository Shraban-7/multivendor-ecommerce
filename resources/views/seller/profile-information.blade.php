@extends('seller.layouts.app')
@section('title', 'Edit Profile')

@section('content')
    <div class="container-fluid px-0">
        <h3 class="header mb-2">Edit Profile</h3>

        <div class="row g-4 align-items-stretch">
            <div class="col-md-6 d-flex">
                <form id="personalForm" enctype="multipart/form-data" class="flex-fill d-flex flex-column">
                    @csrf
                    <input type="hidden" name="section" value="personal">

                    <div class="card shadow-sm border-0 mb-0 flex-fill d-flex flex-column">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="fw-semibold text-primary mb-0">
                                <i data-feather="user" class="me-2"></i>Edit Personal Information
                            </h5>
                        </div>
                        <div class="card-body flex-grow-1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" value="{{ auth('seller')->user()->name }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ auth('seller')->user()->email }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" value="{{ auth('seller')->user()->phone }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">NID Number</label>
                                    <input type="text" name="nid_no" value="{{ auth('seller')->user()->nid_no }}"
                                        class="form-control">
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">Profile Picture</label>
                                    <x-image-input name="image" :image="auth('seller')->user()->image
                                        ? storage_url(auth('seller')->user()->image)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">NID Front Image</label>
                                    <x-image-input name="nid_front_image" :image="auth('seller')->user()->nid_front_image
                                        ? storage_url(auth('seller')->user()->nid_front_image)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>

                                <div class="col-4 mb-3">
                                    <label class="form-label">NID Back Image</label>
                                    <x-image-input name="nid_back_image" :image="auth('seller')->user()->nid_back_image
                                        ? storage_url(auth('seller')->user()->nid_back_image)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>

                            </div>
                        </div>
                        <div class="text-end p-3 border-top bg-light">
                            <button type="submit" class="btn btn-success">Update Personal Info</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6 d-flex">
                <form id="businessForm" enctype="multipart/form-data" class="flex-fill d-flex flex-column">
                    @csrf
                    <input type="hidden" name="section" value="business">

                    <div class="card shadow-sm border-0 mb-0 flex-fill d-flex flex-column">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="fw-semibold text-primary mb-0">
                                <i data-feather="briefcase" class="me-2"></i>Edit Business Information
                            </h5>
                        </div>
                        <div class="card-body flex-grow-1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Business Name</label>
                                    <input type="text" name="business_name"
                                        value="{{ auth('seller')->user()->business_name }}" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Business Email</label>
                                    <input type="email" name="business_email"
                                        value="{{ auth('seller')->user()->business_email }}" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Business Address</label>
                                    <textarea name="business_address" class="form-control">{{ auth('seller')->user()->business_address }}</textarea>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Division</label>
                                    <select name="division_id" id="divisionSelect" class="form-select" required>
                                        <option value="">Select Division</option>
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->id }}"
                                                {{ auth('seller')->user()->division_id == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">District</label>
                                    <select name="district_id" id="districtSelect" class="form-select" required>
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Shop Image</label>
                                    <x-image-input name="shop_image" :image="auth('seller')->user()->shop_image
                                        ? storage_url(auth('seller')->user()->shop_image)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Business Logo</label>
                                    <x-image-input name="business_logo" :image="auth('seller')->user()->business_logo
                                        ? storage_url(auth('seller')->user()->business_logo)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>

                            </div>
                        </div>
                        <div class="text-end p-3 border-top bg-light">
                            <button type="submit" class="btn btn-success">Update Business Info</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <form id="documentForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="section" value="documents">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="fw-semibold text-primary mb-0">
                                <i data-feather="file-text" class="me-2"></i>Edit Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Trade License No</label>
                                    <input type="text" name="trade_license_no"
                                        value="{{ auth('seller')->user()->trade_license_no }}" class="form-control">
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Trade License Image</label>
                                    <x-image-input name="trade_license_image" :image="auth('seller')->user()->trade_license_image
                                        ? storage_url(auth('seller')->user()->trade_license_image)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>

                            </div>
                        </div>
                        <div class="text-end p-3 border-top bg-light">
                            <button type="submit" class="btn btn-success">Update Documents</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let sellerDivision = "{{ $seller->division_id }}";
            let sellerDistrict = "{{ $seller->district_id }}";

            function loadDistricts(divisionId, selectedDistrict = null) {
                let districtSelect = $('select[name="district_id"]');
                districtSelect.html('<option value="">Loading...</option>');

                if (divisionId) {
                    $.get("{{ url('/get-districts') }}/" + divisionId, function(data) {
                        districtSelect.html('<option value="">Select District</option>');
                        $.each(data, function(key, district) {
                            let selected = selectedDistrict == key ? 'selected' : '';
                            districtSelect.append('<option value="' + key + '" ' + selected + '>' +
                                district + '</option>');
                        });
                    });
                } else {
                    districtSelect.html('<option value="">Select District</option>');
                }
            }

            if (sellerDivision) {
                loadDistricts(sellerDivision, sellerDistrict);
            }

            $('select[name="division_id"]').on('change', function() {
                loadDistricts($(this).val());
            });
            
            $(function() {
                $('form').on('submit', function(e) {
                    e.preventDefault();
                    let form = $(this);
                    let formData = new FormData(this);
                    let btn = form.find('button[type=submit]');

                    $.ajax({
                        url: "{{ route('seller.profile') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            btn.prop('disabled', true).html(
                                '<i class="fa fa-spinner fa-spin"></i> Saving...');
                        },
                        success: function(res) {
                            btn.prop('disabled', false).html('Saved');
                            toastr.success(res.message);
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html('Save');
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let message = Object.values(errors).map(err => err[0]).join('<br>');
                                toastr.error(message);
                            } else {
                                toastr.error('Something went wrong!');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
