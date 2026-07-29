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

    .w-full h-2 bg-surface-muted rounded-full overflow-hidden {
        background: #e5e7eb;
    }
</style>

@section('content')

<form id="sellerForm" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mx-auto">
        <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white py-3 border-b">
            <div class="flex justify-between items-end">
                <h4 class="font-semibold mb-0 text-center">
                    <i data-feather="user-check" class="me-2 text-brand"></i> Seller Registration Form
                </h4>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-light btn-sm">
                    <i data-feather="arrow-left" class="icon-xs"></i> Back to Sellers
                </a>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden rounded-0" style="height: 6px;">
            <div class="h-full bg-brand-deep rounded-full transition-all bg-brand-deep" id="formProgress" style="width: 33%;"></div>
        </div>

        <div class="p-5 p-4">
            <!-- STEP 1 -->
            <div class="form-step form-step-active" id="step1">
                <div class="grid grid-cols-1 gap-3">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Select Plan</label>
                        <select name="plan_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} | {{ money($plan->price) }}/{{ $plan->duration_type}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Full Name</label><input type="text" name="name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Email</label><input type="email" name="email" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Phone</label><input type="text" name="phone" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>

                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Password</label><input type="password" name="password" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Confirm Password</label><input type="password" name="password_confirmation" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Profile Image</label><input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*"></div>
                </div>
                <div class="text-right mt-4"><button type="button" class="btn btn-primary nextBtn">Next →</button></div>
            </div>

            <!-- STEP 2 -->
            <div class="form-step" id="step2">
                <h5 class="font-semibold mb-3 text-brand"><i data-feather="briefcase" class="me-2"></i> Business Information</h5>
                <div class="grid grid-cols-1 gap-3">
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Business Name</label><input type="text" name="business_name" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Business Email</label><input type="email" name="business_email" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></div>
                    <div class="col-span-full"><label class="block text-xs font-medium text-ink-secondary mb-1">Business Address</label><textarea name="business_address" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="2" required></textarea></div>
                    <div class="col-span-full"><label class="block text-xs font-medium text-ink-secondary mb-1">Shop Description</label><textarea name="business_description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3"></textarea></div>
                    <div class="md:col-span-1">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Shop Type</label>
                        <select name="shop_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="individual">Individual</option>
                            <option value="business">Business</option>
                            <option value="company">Company</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Division</label>
                        <select name="division_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            <option value="">Select Division</option>
                            @foreach ($divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">District</label>
                        <select name="district_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="col-span-full"><label class="block text-xs font-medium text-ink-secondary mb-1">Business Logo</label><input type="file" name="business_logo" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" class="btn btn-light prevBtn">← Back</button>
                    <button type="button" class="btn btn-primary nextBtn">Next →</button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="form-step" id="step3">
                <h5 class="font-semibold mb-3 text-brand"><i data-feather="file-text" class="me-2"></i> Documents Upload</h5>
                <div class="grid grid-cols-1 gap-3">
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Trade License No</label><input type="text" name="trade_license_no" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">NID Number</label><input type="text" name="nid_no" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></div>

                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Trade License Image</label><input type="file" name="trade_license_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*"></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Shop Image</label><input type="file" name="shop_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*"></div>

                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">NID Front</label><input type="file" name="nid_front_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*"></div>
                    <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">NID Back</label><input type="file" name="nid_back_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" class="btn btn-light prevBtn">← Back</button>
                    <button type="button" id="submitButton" class="btn btn-success">
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
        const w-full h-2 bg-surface-muted rounded-full overflow-hidden = document.querySelector("#formProgress");
        let actStep = 0;

        function showStep(index) {
            steps.forEach((step, i) => step.classList.toggle("form-step-active", i === index));
            w-full h-2 bg-surface-muted rounded-full overflow-hidden.style.width = `${((index + 1) / steps.length) * 100}%`;
        }

        nextBtns.forEach(btn => btn.addEventListener("click", () => {
            if (actStep < steps.length - 1) actStep++;
            showStep(actStep);
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }));

        prevBtns.forEach(btn => btn.addEventListener("click", () => {
            if (actStep > 0) actStep--;
            showStep(actStep);
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
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
                        showSuccessToast('Seller registered successfully!');
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.sellers.index') }}";
                        }, 1500);
                    } else {
                        showErrorToast(response.message || 'Failed to register seller');
                    }
                },
                error: function(xhr) {
                    $('#submitButton').attr('disabled', false).html('<i data-feather="save" class="me-2"></i>Register Seller');
                    feather.replace();

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let message = Object.values(errors).map(err => err[0]).join('<br>');
                        showErrorToast(message);
                    } else {
                        showErrorToast('Something went wrong. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endpush