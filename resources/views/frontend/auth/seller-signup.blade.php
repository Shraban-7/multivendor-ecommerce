@extends('frontend.auth.layout')
@section('title', 'Sign Up')
@section('content')
    @php
        $settings = settings();
    @endphp
    <!-- Registration Page -->
    <div class="w-full max-w-2xl ">
        <div class="p-5 sm:p-8 lg:p-10 xl:p-12 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col justify-center">
                <div class="mb-6">
                    <a href="{{ route('home') }}" class="inline-block">
                        <img src="{{ storage_url($settings->logo_white) }}" alt="{{ $settings->app_name }}"
                            class="h-16 sm:h-16 object-contain" />
                    </a>
                </div>
                <div class="welcome-text mb-6">
                    <h1 class="text-xl sm:text-2xl font-bold text-rangoon-green !leading-tight">
                        Join Our <span class="text-light-yellow">Seller Community!</span>
                    </h1>
                    <p class="text-davy-gray">
                        Create your seller account in a few simple steps
                    </p>
                </div>
            </div>

            <!-- Stepper -->
            <div class="w-full py-6">
                <ol class="flex items-center w-full justify-between">
                    <!-- Step 1 -->
                    <li id="step-1-indicator" class="relative flex flex-col items-center text-light-yellow w-full">
                        <!-- Connector line -->
                        <div class="absolute left-1/2 top-1/3 w-full h-1 bg-light-yellow -translate-y-1/2 z-0">
                        </div>

                        <!-- Circle -->
                        <div
                            class="relative z-10 flex items-center justify-center w-10 h-10 bg-light-yellow rounded-full lg:h-12 lg:w-12">
                            <i class="fas fa-user text-white text-base lg:text-lg"></i>
                        </div>
                        <!-- Label -->
                        <span class="mt-2 text-sm font-medium text-light-yellow">Personal</span>
                    </li>

                    <!-- Step 2 -->
                    <li id="step-2-indicator" class="relative flex flex-col items-center text-gray-500 w-full">
                        <div class="absolute top-1/3 left-1/2 w-full h-1 bg-gray-200 -translate-y-1/2 z-0">
                        </div>
                        <div
                            class="relative z-10 flex items-center justify-center w-10 h-10 bg-gray-500 rounded-full lg:h-12 lg:w-12">
                            <i class="fas fa-briefcase text-white text-base lg:text-lg"></i>
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-500">Business</span>
                    </li>

                    <!-- Step 3 -->
                    <li id="step-3-indicator" class="relative flex flex-col items-center text-gray-500 w-full">
                        <div
                            class="relative z-10 flex items-center justify-center w-10 h-10 bg-gray-500 rounded-full lg:h-12 lg:w-12">
                            <i class="fas fa-file-alt text-white text-base lg:text-lg"></i>
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-500">Documents</span>
                    </li>
                </ol>

            </div>

            <div class="registration-form mt-3 sm:mt-5">
                <!-- Registration Form -->
                <form method="POST" enctype="multipart/form-data" class="w-full mb-3 md:mb-4" id="sellerRegistrationForm">
                    @csrf

                    <!-- Step 1: Personal Information -->
                    <div id="step-1" class="registration-step active">
                        <div class="flex flex-col gap-4 gap-4 sm:gap-5">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="name">Full Name
                                        <span class="text-persian-red">*</span></label>
                                    <input required id="name" type="text" name="name"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="email">Email
                                        <span class="text-persian-red">*</span></label>
                                    <input required id="email" type="email" name="email"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="phone">Phone
                                        Number</label>
                                    <input id="phone" type="text" name="phone"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="password">Password
                                        <span class="text-persian-red">*</span></label>
                                    <input required type="password" id="password" name="password"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>

                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray"
                                        for="password_confirmation">Confirm Password <span
                                            class="text-persian-red">*</span></label>
                                    <input required type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>
                            </div>
                            <div class="form-ctrl space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Profile Image</label>
                                <div id="profile_imageUpload"auto
                                    class="relative border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-light-yellow transition w-[120px] h-[120px]">
                                    <input type="file" id="profile_image" name="image" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />

                                    <!-- Upload Placeholder -->
                                    <div
                                        class="upload-placeholder flex flex-col items-center justify-center h-full text-gray-500 pointer-events-none">
                                        <i class="fas fa-cloud-upload-alt text-xl mb-1"></i>
                                        <p class="text-xs font-medium">Upload</p>
                                    </div>

                                    <!-- Image Preview -->
                                    <div id="profile_imagePreviewWrapper"
                                        class="hidden absolute inset-0 flex items-center justify-center z-10">
                                        <img id="profile_imagePreview"
                                            class="w-[100px] h-[100px] object-cover rounded-full shadow" />
                                        <button type="button"
                                            class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                            data-input="profile_image">
                                            <i class="fas fa-times text-red-600 text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1  gap-4">
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="nid_no">NID
                                        Number</label>
                                    <input id="nid_no" type="text" name="nid_no"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- NID Front Image Upload -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">NID Front Image</label>
                                    <div id="nidFrontUpload"
                                        class="relative border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-light-yellow transition w-full h-[180px]">

                                        <!-- File input overlay -->
                                        <input type="file" id="nid_front_image" name="nid_front_image"
                                            accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-0" />

                                        <!-- Placeholder UI -->
                                        <div
                                            class="flex flex-col items-center justify-center h-full text-gray-500 pointer-events-none">
                                            <i class="fas fa-id-card text-3xl mb-2"></i>
                                            <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                            <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                        </div>

                                        <!-- Preview Section -->
                                        <div id="nid_front_imagePreviewWrapper"
                                            class="hidden absolute inset-0 flex items-center justify-center z-10">
                                            <img id="nid_front_imagePreview"
                                                class="w-[300px] h-[180px] object-cover rounded-md shadow" />
                                            <button type="button"
                                                class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                                data-input="nid_front_image">
                                                <i class="fas fa-times text-red-600 text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- NID Back Image Upload -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">NID Back Image</label>
                                    <div id="nidBackUpload"
                                        class="relative border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-light-yellow transition w-full h-[180px]">

                                        <input type="file" id="nid_back_image" name="nid_back_image" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-0" />

                                        <div
                                            class="flex flex-col items-center justify-center h-full text-gray-500 pointer-events-none">
                                            <i class="fas fa-id-card-clip text-3xl mb-2"></i>
                                            <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                            <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                        </div>

                                        <div id="nid_back_imagePreviewWrapper"
                                            class="hidden absolute inset-0 flex items-center justify-center z-10">
                                            <img id="nid_back_imagePreview"
                                                class="w-[300px] h-[180px] object-cover rounded-md shadow" />
                                            <button type="button"
                                                class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                                data-input="nid_back_image">
                                                <i class="fas fa-times text-red-600 text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="flex justify-end mt-2">
                                <button type="button" data-current="1" data-next="2"
                                    class="next-step text-white bg-light-yellow hover:bg-light-yellow/90 focus:ring-4 focus:ring-light-yellow/50 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none transition-all">
                                    Next: Business Information
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Step 2: Business Information -->
                    <div id="step-2" class="registration-step hidden">
                        <div class="flex flex-col gap-4 sm:gap-5">
                            <!-- Business information fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Business Name -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="business_name">
                                        Business Name <span class="text-persian-red">*</span>
                                    </label>
                                    <input required id="business_name" type="text" name="business_name"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2
                                                    focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>

                                <!-- Business Email -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="business_email">
                                        Business Email
                                    </label>
                                    <input id="business_email" type="email" name="business_email"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2
                                                    focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>
                            </div>

                            <!-- Division & District -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Division -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="division_id">
                                        Division <span class="text-persian-red">*</span>
                                    </label>
                                    <select required id="division_id" name="division_id"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2
                                                        focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white">
                                        <option value="">Select Division</option>
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- District -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="district_id">
                                        District <span class="text-persian-red">*</span>
                                    </label>
                                    <select required id="district_id" name="district_id"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2
                                                        focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white">
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-ctrl space-y-2">
                                <label class="block text-sm font-medium text-davy-gray" for="business_address">Business
                                    Address <span class="text-persian-red">*</span></label>
                                <textarea id="business_address" name="business_address" rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white resize-none"></textarea>
                            </div>

                            <div class="form-ctrl space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Business Logo</label>
                                <div id="businessLogoUpload"
                                    class="relative border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-light-yellow transition w-[120px] h-[120px]">

                                    <input type="file" id="business_logo" name="business_logo" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-0" />

                                    <!-- Placeholder -->
                                    <div
                                        class="flex flex-col items-center justify-center h-full text-gray-500 pointer-events-none">
                                        <i class="fas fa-building text-2xl mb-1"></i>
                                        <p class="text-xs font-medium">Upload</p>
                                    </div>

                                    <!-- Preview -->
                                    <div id="business_logoPreviewWrapper"
                                        class="hidden absolute inset-0 flex items-center justify-center z-10">
                                        <img id="business_logoPreview"
                                            class="w-[90px] h-[90px] object-cover rounded-md shadow" />
                                        <button type="button"
                                            class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                            data-input="business_logo">
                                            <i class="fas fa-times text-red-600 text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="flex justify-between mt-2">
                                <!-- Previous Button -->
                                <button type="button" data-current="2" data-prev="1"
                                    class="prev-step text-light-yellow border border-light-yellow hover:bg-light-yellow/10 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none focus:ring-4 focus:ring-light-yellow/50 transition-all">
                                    Previous
                                </button>

                                <!-- Next Button -->
                                <button type="button" data-current="2" data-next="3"
                                    class="next-step text-white bg-light-yellow hover:bg-light-yellow/90 focus:ring-4 focus:ring-light-yellow/50 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none transition-all">
                                    Next: Documents
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Step 3: Documents Information -->
                    <div id="step-3" class="registration-step hidden">
                        <div class="flex flex-col gap-4 sm:gap-5">
                            <!-- Document fields -->
                            <div class="grid grid-cols-1 gap-4">
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-davy-gray" for="trade_license_no">Trade
                                        License Number</label>
                                    <input id="trade_license_no" type="text" name="trade_license_no"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Trade License Image Upload -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Trade License
                                        Image</label>
                                    <div id="tradeLicenseUpload"
                                        class="relative border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-indigo-500 transition w-[140px] h-[140px]">

                                        <input type="file" id="trade_license_image" name="trade_license_image"
                                            accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-0" />

                                        <!-- Placeholder -->
                                        <div
                                            class="flex flex-col items-center justify-center h-full text-gray-500 pointer-events-none">
                                            <i class="fas fa-file-alt text-2xl mb-1"></i>
                                            <p class="text-xs font-medium">Upload</p>
                                        </div>

                                        <!-- Preview -->
                                        <div id="trade_license_imagePreviewWrapper"
                                            class="hidden absolute inset-0 flex items-center justify-center z-10">
                                            <img id="trade_license_imagePreview"
                                                class="w-[100px] h-[100px] object-cover rounded-md shadow" />
                                            <button type="button"
                                                class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                                data-input="trade_license_image">
                                                <i class="fas fa-times text-red-600 text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Shop Image Upload -->
                                <div class="form-ctrl space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Shop Image</label>
                                    <div id="shopImageUpload"
                                        class="relative border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-indigo-500 transition w-[140px] h-[140px]">

                                        <input type="file" id="shop_image" name="shop_image" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-0" />

                                        <!-- Placeholder -->
                                        <div
                                            class="flex flex-col items-center justify-center h-full text-gray-500 pointer-events-none">
                                            <i class="fas fa-store text-2xl mb-1"></i>
                                            <p class="text-xs font-medium">Upload</p>
                                        </div>

                                        <!-- Preview -->
                                        <div id="shop_imagePreviewWrapper"
                                            class="hidden absolute inset-0 flex items-center justify-center z-10">
                                            <img id="shop_imagePreview"
                                                class="w-[100px] h-[100px] object-cover rounded-md shadow" />
                                            <button type="button"
                                                class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                                data-input="shop_image">
                                                <i class="fas fa-times text-red-600 text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start text-davy-gray/80 mt-4">
                                <div class="flex items-center h-5">
                                    <input required id="terms" type="checkbox"
                                        class="w-4 h-4 text-light-yellow focus:ring-light-yellow border-gray-300 rounded" />
                                </div>
                                <label for="terms" class="ml-2 text-sm">By signing up, you are creating
                                    a SlashMart seller account, and you
                                    agree to SlashMart's
                                    <a href="#" class="text-light-yellow hover:underline eq">Term
                                        of Use</a>
                                    and
                                    <a href="#" class="text-light-yellow hover:underline eq">Privacy
                                        Policy</a>.</label>
                            </div>
                            <div class="flex justify-between mt-2">
                                <button type="button" data-current="3" data-prev="2"
                                    class="prev-step text-light-yellow border border-light-yellow hover:bg-light-yellow/10 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none focus:ring-4 focus:ring-light-yellow/50 transition-all">
                                    Previous
                                </button>
                                <button type="button"
                                    class="submit-btn text-white bg-light-yellow hover:bg-light-yellow/90 focus:ring-4 focus:ring-light-yellow/50 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none transition-all">
                                    Register as Seller
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#division_id').change(function() {
                    let divisionId = $(this).val();
                    let $districtSelect = $('#district_id');
                    $districtSelect.html('<option value="">Loading...</option>');

                    if (divisionId) {
                        $.ajax({
                            url: '/get-districts/' + divisionId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $districtSelect.html('<option value="">Select District</option>');
                                $.each(data, function(id, name) {
                                    $districtSelect.append('<option value="' + id + '">' +
                                        name + '</option>');
                                });
                            },
                            error: function() {
                                $districtSelect.html('<option value="">Select District</option>');
                            }
                        });
                    } else {
                        $districtSelect.html('<option value="">Select District</option>');
                    }
                });

                $(document).on('click', '.next-step', function() {
                    const $btn = $(this);
                    const currentStep = parseInt($(this).data('current'));
                    const nextStep = parseInt($(this).data('next'));
                    const $currentStep = $('#step-' + currentStep);

                    $currentStep.find('.error-msg').remove();
                    $currentStep.find(':input').removeClass('border-red-500');

                    const originalContent = $btn.html();
                    $btn.attr('disabled', true).html(`
                            <svg class="animate-spin h-5 w-5 mr-2 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg> Loading...
                    `);

                    let formData = new FormData();
                    $currentStep.find(':input').each(function() {
                        let name = $(this).attr('name');
                        if (!name) return;

                        if ($(this).attr('type') === 'file' && this.files[0]) {
                            formData.append(name, this.files[0]);
                        } else {
                            formData.append(name, $(this).val());
                        }
                    });
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('step', currentStep);

                    $.ajax({
                        url: "{{ route('seller.signup') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $currentStep.addClass('hidden').removeClass('active');
                            $('#step-' + nextStep).removeClass('hidden').addClass('active');
                            updateStepIndicators(currentStep, nextStep);
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                for (const [field, messages] of Object.entries(errors)) {
                                    const $field = $currentStep.find(`[name="${field}"]`);
                                    if ($field.length) {
                                        $field.addClass('border-red-500');
                                        if (!$field.next('.error-msg').length) {
                                            $field.after(
                                                '<span class="error-msg text-red-500 text-xs mt-1">' +
                                                messages[0] + '</span>');
                                        }
                                    }
                                }
                            }
                        },
                        complete: function() {
                            $btn.prop('disabled', false).html(originalContent);
                        }

                    });
                });

                $(document).on('click', '.prev-step', function() {
                    const currentStep = parseInt($(this).data('current'));
                    const prevStep = parseInt($(this).data('prev'));

                    $('#step-' + currentStep).addClass('hidden').removeClass('active');
                    $('#step-' + prevStep).removeClass('hidden').addClass('active');

                    updateStepIndicators(currentStep, prevStep, 'prev');
                });

                function updateStepIndicators(fromStep, toStep) {
                    for (let i = 1; i <= 3; i++) {
                        const $indicator = $('#step-' + i + '-indicator');
                        if (i < toStep) {
                            $indicator.removeClass('text-light-yellow text-gray-500').addClass('text-[#FD740F]');
                            $indicator.find('div').removeClass('bg-gray-500 bg-light-yellow').addClass('bg-[#FD740F]');
                            $indicator.find('span').removeClass('text-gray-500 text-light-yellow').addClass(
                                'text-[#FD740F]');
                            if (i < 3) $indicator.addClass('after:border-[#FD740F]');
                        } else if (i === toStep) {
                            $indicator.removeClass('text-[#FD740F] text-gray-500').addClass('text-light-yellow');
                            $indicator.find('div').removeClass('bg-[#FD740F] bg-gray-500').addClass('bg-light-yellow');
                            $indicator.find('span').removeClass('text-[#FD740F] text-gray-500').addClass(
                                'text-light-yellow');
                        } else {
                            $indicator.removeClass('text-[#FD740F] text-light-yellow').addClass('text-gray-500');
                            $indicator.find('div').removeClass('bg-[#FD740F] bg-light-yellow').addClass('bg-gray-500');
                            $indicator.find('span').removeClass('text-[#FD740F] text-light-yellow').addClass(
                                'text-gray-500');
                            $indicator.removeClass('after:border-[#FD740F]').addClass('after:border-gray-200');
                        }
                    }
                }

                $(document).on('change', 'input[type="file"]', function() {
                    const inputId = $(this).attr('id');
                    const previewWrapperId = inputId + 'PreviewWrapper';
                    const previewId = inputId + 'Preview';

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#' + previewId).attr('src', e.target.result);
                            $('#' + previewWrapperId).removeClass('hidden');
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                $(document).on('click', '.remove-image-btn', function(e) {
                    e.stopPropagation();
                    const inputId = $(this).data('input');
                    $('#' + inputId).val('');
                    $('#' + inputId + 'Preview').attr('src', '');
                    $('#' + inputId + 'PreviewWrapper').addClass('hidden');
                });

                $(document).on('click', '.submit-btn', function(e) {
                    e.preventDefault();
                    let $btn = $(this);
                    let form = $('#sellerRegistrationForm')[0];
                    let formData = new FormData(form);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('step', 3);

                    let originalContent = $btn.html();
                    $btn.attr('disabled', true).html(
                        `<svg class="animate-spin h-5 w-5 mr-2 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Loading...`
                    );

                    $.ajax({
                        url: "{{ route('seller.signup') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $btn.attr('disabled', false).html(originalContent);
                            window.location.href = "{{ route('thank_you') }}";
                        },
                        error: function(xhr) {
                            $btn.attr('disabled', false).html(originalContent);
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                Object.entries(errors).forEach(([field, messages]) => {
                                    const $field = $('#step-3').find(`[name="${field}"]`);
                                    if ($field.length) {
                                        $field.addClass('border-red-500');
                                        if (!$field.next('.error-msg').length) {
                                            $field.after(
                                                '<span class="error-msg text-red-500 text-xs mt-1">' +
                                                messages[0] + '</span>');
                                        }
                                    }
                                });
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
