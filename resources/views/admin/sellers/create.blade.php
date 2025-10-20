@extends('admin.layouts.app')
@section('title', 'Add New Seller')

<style>
    .form-step {
        display: none;
        animation: fadeIn 0.4s ease-in-out;
    }

    .form-step-active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .progress {
        background: #e5e7eb;
    }
</style>
@section('content')

<form id="sellerForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    <div class="card border-0 shadow-sm mx-auto">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-end">
                <h4 class="fw-semibold mb-0 text-center">
                    <i data-feather="user-check" class="me-2 text-primary"></i> Seller Registration Form
                </h4>
               <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left" class="icon-xs"></i> Back to Sellers
                </a>
            </div>            
        </div>
        <!-- Progress Bar -->
        <div class="progress rounded-0" style="height: 6px;">
            <div class="progress-bar bg-primary" id="formProgress" style="width: 33%;"></div>
        </div>
        <div class="card-body p-4">
            <!-- STEP 1: Personal Info -->
            <div class="form-step form-step-active" id="step1">
                <h5 class="fw-semibold mb-3 text-primary">
                    <i data-feather="user" class="me-2"></i> Personal Information
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NID Number</label>
                        <input type="text" name="nid_no" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required />
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-primary nextBtn px-4">Next →</button>
                </div>
            </div>
            <!-- STEP 2: Business Info -->
            <div class="form-step" id="step2">
                <h5 class="fw-semibold mb-3 text-primary">
                    <i data-feather="briefcase" class="me-2"></i> Business Information
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Business Name <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Email</label>
                        <input type="email" name="business_email" class="form-control" required />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Business Address <span class="text-danger">*</span></label>
                        <textarea name="business_address" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Division <span class="text-danger">*</span></label>
                        <select name="division_id" class="form-select" required>
                            <option value="">Select Division</option>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <select name="district_id" class="form-select" required>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Business Logo</label>
                        <input type="file" name="business_logo" class="form-control" accept="image/*" required />
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prevBtn px-4">← Back</button>
                    <button type="button" class="btn btn-primary nextBtn px-4">Next →</button>
                </div>
            </div>
            <!-- STEP 3: Documents -->
            <div class="form-step" id="step3">
                <h5 class="fw-semibold mb-3 text-primary">
                    <i data-feather="file-text" class="me-2"></i> Documents Upload
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Trade License No</label>
                        <input type="text" name="trade_license_no" class="form-control" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trade License Image</label>
                        <input type="file" name="trade_license_image" class="form-control" accept="image/*" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Shop Image</label>
                        <input type="file" name="shop_image" class="form-control" accept="image/*" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NID Front</label>
                        <input type="file" name="nid_front_image" class="form-control" accept="image/*" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NID Back</label>
                        <input type="file" name="nid_back_image" class="form-control" accept="image/*" required />
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prevBtn px-4">← Back</button>
                    <button type="submit" class="btn btn-success px-5">
                        <i data-feather="save" class="me-2"></i>Register Seller
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{--<form id="sellerForm" method="POST" enctype="multipart/form-data" class="needs-validation">
    @csrf
    <div class="row">
        <!-- Personal Info -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i data-feather="user" class="me-2"></i> Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NID Number</label>
                        <input type="text" name="nid_no" class="form-control" value="{{ old('nid_no') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Info -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i data-feather="briefcase" class="me-2"></i> Business Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Business Name <span class="text-danger">*</span></label>
                        <input type="text" name="business_name" class="form-control"
                            value="{{ old('business_name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Business Email</label>
                        <input type="email" name="business_email" class="form-control"
                            value="{{ old('business_email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Business Address <span class="text-danger">*</span></label>
                        <textarea name="business_address" class="form-control" rows="2" required>{{ old('business_address') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Division <span class="text-danger">*</span></label>
                        <select name="division_id" class="form-select" required>
                            <option value="">Select Division</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <select name="district_id" class="form-select" required>
                            <option value="">Select District</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Business Logo</label>
                        <input type="file" name="business_logo" class="form-control" accept="image/*"
                            required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i data-feather="file-text" class="me-2"></i> Documents Upload</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Trade License Number</label>
                        <input type="text" name="trade_license_no" class="form-control"
                            value="{{ old('trade_license_no') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trade License Image </label>
                        <input type="file" name="trade_license_image" class="form-control" accept="image/*"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shop Image</label>
                        <input type="file" name="shop_image" class="form-control" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NID Front Image</label>
                        <input type="file" name="nid_front_image" class="form-control" accept="image/*"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NID Back Image</label>
                        <input type="file" name="nid_back_image" class="form-control" accept="image/*"
                            required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Submit -->
    <div class="text-end mt-3">
        <button type="submit" id="submitButton" class="btn btn-primary">
            <i data-feather="save" class="icon-xs me-1"></i> Save Seller
        </button>
    </div>
</form>--}}

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nextBtns = document.querySelectorAll(".nextBtn");
        const prevBtns = document.querySelectorAll(".prevBtn");
        const steps = document.querySelectorAll(".form-step");
        const progress = document.querySelector("#formProgress");

        let actStep = 0;

        function showStep(index) {
            steps.forEach((step, i) => {
                step.classList.toggle("form-step-active", i === index);
            });
            const progressPercent = ((index + 1) / steps.length) * 100;
            progress.style.width = `${progressPercent}%`;
        }

        nextBtns.forEach((btn) =>
            btn.addEventListener("click", () => {
                if (actStep < steps.length - 1) actStep++;
                showStep(actStep);
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            })
        );

        prevBtns.forEach((btn) =>
            btn.addEventListener("click", () => {
                if (actStep > 0) actStep--;
                showStep(actStep);
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            })
        );

        showStep(actStep);
    });
</script>
<script>
    feather.replace();
    $(document).ready(function() {
        $('select[name="division_id"]').on('change', function() {
            let divisionId = $(this).val();
            let districtSelect = $('select[name="district_id"]');
            districtSelect.html('<option value="">Loading...</option>');

            if (divisionId) {
                $.get("{{ url('/get-districts') }}/" + divisionId, function(data) {
                    districtSelect.html('<option value="">Select District</option>');
                    $.each(data, function(key, district) {
                        districtSelect.append('<option value="' + key + '">' + district + '</option>');
                    });
                });
            } else {
                districtSelect.html('<option value="">Select District</option>');
            }
        });

        $('#submitButton').click(function(e) {
            e.preventDefault();

            let form = $('#sellerForm')[0];
            let formData = new FormData(form);
            $.ajax({
                url: "{{ route('admin.sellers.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#submitButton').attr('disabled', true).text('Saving...');
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success('Seller added successfully!');

                        setTimeout(function() {
                            window.location.href =
                                "{{ route('admin.sellers.index') }}";
                        }, 1500);
                    } else {
                        toastr.error(response.messages);
                    }
                },
                error: function(xhr) {
                    $('#submitButton').attr('disabled', false).text('Save Seller');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors).map(item => item[0]).join('<br>');
                        toastr.error(messages);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endpush