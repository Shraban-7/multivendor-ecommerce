@extends('admin.layouts.app')
@section('title', 'Edit Seller')

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

    .progress-bar {
        background: #e5e7eb;
    }
</style>

@section('content')

    <form id="sellerEditForm" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @method('POST')
        <input type="hidden" name="id" value="{{ $seller->id }}">

        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mx-auto">
            <div class="px-5 py-4 border-b border-border flex items-center justify-between">
                <h4 class="font-semibold mb-0">
                    <i data-lucide="user-check" class="me-2 text-brand"></i> Edit Seller Information
                </h4>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back to Sellers
                </a>
            </div>

            <!-- Progress Bar -->
            <div class="w-full" style="height: 6px; background: #e5e7eb;">
                <div class="h-full bg-brand-deep transition-all" id="formProgress" style="width: 33%;"></div>
            </div>

            <div class="p-4">
                <!-- STEP 1 -->
                <div class="form-step form-step-active" id="step1">
                    <h5 class="font-semibold mb-3 text-brand"><i data-lucide="user" class="me-2"></i> Personal
                        Information</h5>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Full Name</label><input type="text"
                                name="name" value="{{ $seller->name }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Email</label><input type="email" name="email"
                                value="{{ $seller->email }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Phone</label><input type="text" name="phone"
                                value="{{ $seller->phone }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required></div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">NID Number</label><input type="text"
                                name="nid_no" value="{{ $seller->nid_no }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></div>
                        <div class="col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Profile Image</label>
                            <input type="file" name="image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*">
                            @if ($seller->image)
                                <img src="{{ storage_url($seller->image) }}" class="mt-2 rounded" width="100">
                            @endif
                        </div>
                    </div>
                    <div class="text-right mt-4"><button type="button" class="btn btn-primary nextBtn">Next →</button>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="form-step" id="step2">
                    <h5 class="font-semibold mb-3 text-brand"><i data-lucide="briefcase" class="me-2"></i> Business
                        Information</h5>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Business Name</label><input type="text"
                                name="business_name" value="{{ $seller->business_name }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                        </div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Business Email</label><input type="email"
                                name="business_email" value="{{ $seller->business_email }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"></div>
                        <div class="col-span-full"><label class="block text-xs font-medium text-ink-secondary mb-1">Business Address</label>
                            <textarea name="business_address" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="2" required>{{ $seller->business_address }}</textarea>
                        </div>
                        <div class="col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Shop Description</label>
                            <x-textarea-input name="business_description" :value="$seller->business_description" />
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Shop Type</label>
                            <select name="shop_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                <option value="individual" {{ $seller->shop_type == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="business" {{ $seller->shop_type == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="company" {{ $seller->shop_type == 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Division</label>
                            <select name="division_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                <option value="">Select Division</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        {{ $seller->division_id == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">District</label>
                            <select name="district_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Business Logo</label>
                            <input type="file" name="business_logo" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*">
                            @if ($seller->business_logo)
                                <img src="{{ storage_url($seller->business_logo) }}" class="mt-2 rounded"
                                    width="100">
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <button type="button" class="btn btn-light prevBtn">← Back</button>
                        <button type="button" class="btn btn-primary nextBtn">Next →</button>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="form-step" id="step3">
                    <h5 class="font-semibold mb-3 text-brand"><i data-lucide="file-text" class="me-2"></i> Documents
                        Upload</h5>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Trade License No</label><input type="text"
                                name="trade_license_no" value="{{ $seller->trade_license_no }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        </div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Trade License Image</label><input type="file"
                                name="trade_license_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*">
                            @if ($seller->trade_license_image)
                                <img src="{{ storage_url($seller->trade_license_image) }}" class="mt-2 rounded"
                                    width="100">
                            @endif
                        </div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">Shop Image</label><input type="file"
                                name="shop_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*">
                            @if ($seller->shop_image)
                                <img src="{{ storage_url($seller->shop_image) }}" class="mt-2 rounded" width="100">
                            @endif
                        </div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">NID Front</label><input type="file"
                                name="nid_front_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*">
                            @if ($seller->nid_front_image)
                                <img src="{{ storage_url($seller->nid_front_image) }}" class="mt-2 rounded"
                                    width="100">
                            @endif
                        </div>
                        <div class="md:col-span-1"><label class="block text-xs font-medium text-ink-secondary mb-1">NID Back</label><input type="file"
                                name="nid_back_image" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" accept="image/*">
                            @if ($seller->nid_back_image)
                                <img src="{{ storage_url($seller->nid_back_image) }}" class="mt-2 rounded"
                                    width="100">
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <button type="button" class="btn btn-light prevBtn">← Back</button>
                        <button type="button" id="updateButton" class="btn btn-success">
                            <i data-lucide="save" class="me-2"></i>Update Seller
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
            const progressBar = document.querySelector("#formProgress");
            let actStep = 0;

            function showStep(index) {
                steps.forEach((step, i) => step.classList.toggle("form-step-active", i === index));
                progressBar.style.width = `${((index + 1) / steps.length) * 100}%`;
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
        window.renderIcons && window.renderIcons();

        $(document).ready(function() {
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

            $('#updateButton').on('click', function(e) {
                e.preventDefault();
                let form = $('#sellerEditForm')[0];
                let formData = new FormData(form);

                $.ajax({
                    url: "{{ route('admin.sellers.update', $seller->username) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#updateButton').attr('disabled', true).html(
                            '<i data-lucide="loader-circle" class="animate-spin"></i> Updating...');
                        window.renderIcons && window.renderIcons();
                    },
                    success: function(response) {
                        $('#updateButton').attr('disabled', false).html(
                            '<i data-lucide="save" class="me-2"></i>Update Seller');
                        window.renderIcons && window.renderIcons();
                        if (response.status) {
                            showSuccessToast('Seller updated successfully!');
                            setTimeout(() => {
                                window.location.href = response.data.redirect;
                            }, 1500);
                        } else {
                            showErrorToast(response.message || 'Failed to update seller');
                        }
                    },
                    error: function(xhr) {
                        $('#updateButton').attr('disabled', false).html(
                            '<i data-lucide="save" class="me-2"></i>Update Seller');
                        window.renderIcons && window.renderIcons();

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
