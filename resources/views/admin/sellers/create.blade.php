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

<form id="sellerForm" enctype="multipart/form-data" class="needs-validation" novalidate>
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
            <!-- STEP 1 -->
            <div class="form-step form-step-active" id="step1">
                <h5 class="fw-semibold mb-3 text-primary"><i data-feather="user" class="me-2"></i> Personal Information</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">NID Number</label><input type="text" name="nid_no" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Confirm Password *</label><input type="password" name="password_confirmation" class="form-control" required></div>
                    <div class="col-12"><label class="form-label">Profile Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                </div>
                <div class="text-end mt-4"><button type="button" class="btn btn-primary nextBtn px-4">Next →</button></div>
            </div>

            <!-- STEP 2 -->
            <div class="form-step" id="step2">
                <h5 class="fw-semibold mb-3 text-primary"><i data-feather="briefcase" class="me-2"></i> Business Information</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Business Name *</label><input type="text" name="business_name" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Business Email</label><input type="email" name="business_email" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Business Address *</label><textarea name="business_address" class="form-control" rows="2" required></textarea></div>
                    <div class="col-md-6">
                        <label class="form-label">Division *</label>
                        <select name="division_id" class="form-select" required>
                            <option value="">Select Division</option>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">District *</label>
                        <select name="district_id" class="form-select" required>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label">Business Logo</label><input type="file" name="business_logo" class="form-control" accept="image/*"></div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prevBtn px-4">← Back</button>
                    <button type="button" class="btn btn-primary nextBtn px-4">Next →</button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="form-step" id="step3">
                <h5 class="fw-semibold mb-3 text-primary"><i data-feather="file-text" class="me-2"></i> Documents Upload</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Trade License No</label><input type="text" name="trade_license_no" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label">Trade License Image</label><input type="file" name="trade_license_image" class="form-control" accept="image/*"></div>
                    <div class="col-md-6"><label class="form-label">Shop Image</label><input type="file" name="shop_image" class="form-control" accept="image/*"></div>
                    <div class="col-md-6"><label class="form-label">NID Front</label><input type="file" name="nid_front_image" class="form-control" accept="image/*"></div>
                    <div class="col-md-6"><label class="form-label">NID Back</label><input type="file" name="nid_back_image" class="form-control" accept="image/*"></div>
                </div>
                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary prevBtn px-4">← Back</button>
                    <button type="button" id="submitButton" class="btn btn-success px-5">
                        <i data-feather="save" class="me-2"></i>Register Seller
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
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
            steps.forEach((step, i) => step.classList.toggle("form-step-active", i === index));
            progress.style.width = `${((index + 1) / steps.length) * 100}%`;
        }

        nextBtns.forEach(btn => btn.addEventListener("click", () => {
            if (actStep < steps.length - 1) actStep++;
            showStep(actStep);
            window.scrollTo({ top: 0, behavior: "smooth" });
        }));

        prevBtns.forEach(btn => btn.addEventListener("click", () => {
            if (actStep > 0) actStep--;
            showStep(actStep);
            window.scrollTo({ top: 0, behavior: "smooth" });
        }));

        showStep(actStep);
    });
</script>

<script>
    feather.replace();

    $(document).ready(function() {

        // Load Districts dynamically
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

        // ✅ AJAX FORM SUBMIT
        $('#submitButton').on('click', function(e) {
            e.preventDefault();
            let form = $('#sellerForm')[0];
            let formData = new FormData(form);

            $.ajax({
                url: "{{ route('admin.sellers.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#submitButton').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                },
                success: function(response) {
                    $('#submitButton').attr('disabled', false).html('<i data-feather="save" class="me-2"></i>Register Seller');
                    feather.replace();
                    if (response.status) {
                        toastr.success('Seller registered successfully!');
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.sellers.index') }}";
                        }, 1500);
                    } else {
                        toastr.error(response.message || 'Failed to register seller');
                    }
                },
                error: function(xhr) {
                    $('#submitButton').attr('disabled', false).html('<i data-feather="save" class="me-2"></i>Register Seller');
                    feather.replace();

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let message = Object.values(errors).map(err => err[0]).join('<br>');
                        toastr.error(message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endpush
