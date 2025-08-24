@extends('frontend.layouts.app')
@section('title', 'Sign Up')
@section('content')
    <!-- Registration Page -->
    <main class="registration-page">
        <div class="min-h-screen">
            <div class="registration-form-sec section-padding px-5 md:px-10 2xl:px-20 py-8 flex flex-col justify-center">
                <div class="w-full max-w-2xl mx-auto">
                    <div class="welcome-text space-y-3 mb-6">
                        <h1 class="text-3xl sm:text-4xl font-bold text-rangoon-green !leading-tight">
                            Join Our <span class="text-light-yellow">Seller Community!</span>
                        </h1>
                        <p class="font-medium text-davy-gray">
                            Create your seller account in a few simple steps
                        </p>
                    </div>

                    <!-- Stepper -->
                    <div class="w-full py-6">
                        <ol class="flex items-center w-full">
                            <!-- Step 1 -->
                            <li id="step-1-indicator"
                                class="flex w-full items-center text-light-yellow after:content-[''] after:w-full after:h-1 after:border-b after:border-light-yellow after:border-4 after:inline-block">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-light-yellow rounded-full lg:h-12 lg:w-12 shrink-0">
                                    <i class="fas fa-user text-white text-base lg:text-lg"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium text-light-yellow">Personal</span>
                            </li>

                            <!-- Step 2 -->
                            <li id="step-2-indicator"
                                class="flex w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-4 after:inline-block">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-gray-500 rounded-full lg:h-12 lg:w-12 shrink-0">
                                    <i class="fas fa-briefcase text-white text-base lg:text-lg"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-500">Business</span>
                            </li>

                            <!-- Step 3 -->
                            <li id="step-3-indicator" class="flex items-center">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-gray-500  rounded-full lg:h-12 lg:w-12 shrink-0">
                                    <i class="fas fa-file-alt text-white text-base lg:text-lg"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-500">Documents</span>
                            </li>
                        </ol>
                    </div>

                    <div class="registration-form mt-3 sm:mt-5">
                        <!-- Registration Form -->
                        <form action="{{ route('seller.signup') }}" method="POST" enctype="multipart/form-data"
                            class="w-full mb-3 md:mb-4" id="sellerRegistrationForm">
                            @csrf

                            <!-- Step 1: Personal Information -->
                            <div id="step-1" class="registration-step active">
                                <div class="flex flex-col gap-4 sm:gap-5">
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
                                            <input required type="password" id="password_confirmation"
                                                name="password_confirmation"
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

                                                <input type="file" id="nid_back_image" name="nid_back_image"
                                                    accept="image/*"
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
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
                                        <label class="block text-sm font-medium text-davy-gray"
                                            for="business_address">Business Address <span
                                                class="text-persian-red">*</span></label>
                                        <textarea id="business_address" name="business_address" rows="3"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white resize-none"></textarea>
                                    </div>

                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Business Logo</label>
                                        <div id="businessLogoUpload"
                                            class="relative border-2 border-dashed border-gray-300 rounded-lg text-center cursor-pointer hover:border-light-yellow transition w-[120px] h-[120px]">

                                            <input type="file" id="business_logo" name="business_logo"
                                                accept="image/*"
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
                                            <label class="block text-sm font-medium text-davy-gray"
                                                for="trade_license_no">Trade License Number</label>
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
                                        <button type="submit"
                                            class="text-white bg-light-yellow hover:bg-light-yellow/90 focus:ring-4 focus:ring-light-yellow/50 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none transition-all">
                                            Register as Seller
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                                    $districtSelect.append('<option value="' + id +
                                        '">' + name + '</option>');
                                });
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                $districtSelect.html('<option value="">Select District</option>');
                            }
                        });
                    } else {
                        $districtSelect.html('<option value="">Select District</option>');
                    }
                });
            });


            $(document).ready(function() {
                $(document).on('click', '.next-step', function() {
                    const currentStep = parseInt($(this).data('current'));
                    const nextStep = parseInt($(this).data('next'));
                    let isValid = true;

                    $('#step-' + currentStep + ' :input[required]').each(function() {
                        if (!$(this).val()) {
                            isValid = false;
                            $(this).addClass('border-red-500');
                            if (!$(this).next('.error-msg').length) {
                                $(this).after(
                                    '<span class="error-msg text-red-500 text-xs mt-1">This field is required</span>'
                                    );
                            }
                        } else {
                            $(this).removeClass('border-red-500');
                            $(this).next('.error-msg').remove();
                        }
                    });

                    if (!isValid) return; 

                    $('#step-' + currentStep).addClass('hidden').removeClass('active');
                    $('#step-' + nextStep).removeClass('hidden').addClass('active');

                    updateStepIndicators(currentStep, nextStep);
                });

                $(document).on('click', '.prev-step', function() {
                    const currentStep = parseInt($(this).data('current'));
                    const prevStep = parseInt($(this).data('prev'));

                    $('#step-' + currentStep).addClass('hidden').removeClass('active');
                    $('#step-' + prevStep).removeClass('hidden').addClass('active');

                    updateStepIndicatorsBackward(currentStep, prevStep);
                });

                function updateStepIndicators(currentStep, nextStep) {
                    const $currentIndicator = $('#step-' + currentStep + '-indicator');
                    $currentIndicator.removeClass('text-light-yellow').addClass('text-[#FD740F]');
                    $currentIndicator.find('div').removeClass('bg-light-yellow').addClass('bg-[#FD740F]');
                    $currentIndicator.find('i').removeClass('text-gray-500').addClass('text-white');
                    $currentIndicator.find('span').removeClass('text-light-yellow').addClass('text-[#FD740F]');
                    if (currentStep < 3) {
                        $currentIndicator.removeClass('after:border-gray-200').addClass('after:border-[#FD740F]');
                    }

                    const $nextIndicator = $('#step-' + nextStep + '-indicator');
                    $nextIndicator.addClass('text-light-yellow');
                    $nextIndicator.find('div').removeClass('bg-gray-500').addClass('bg-light-yellow');
                    $nextIndicator.find('i').removeClass('text-gray-500').addClass('text-white');
                    $nextIndicator.find('span').removeClass('text-gray-500').addClass('text-light-yellow');
                }

                function updateStepIndicatorsBackward(currentStep, prevStep) {
                    const $currentIndicator = $('#step-' + currentStep + '-indicator');
                    $currentIndicator.removeClass('text-light-yellow');
                    $currentIndicator.find('div').removeClass('bg-light-yellow').addClass('bg-gray-500');
                    $currentIndicator.find('i').removeClass('text-white text-light-yellow').addClass('text-white');
                    $currentIndicator.find('span').removeClass('text-light-yellow').addClass('text-gray-500');

                    const $prevIndicator = $('#step-' + prevStep + '-indicator');
                    $prevIndicator.removeClass('text-gray-500').addClass('text-light-yellow');
                    $prevIndicator.find('div').removeClass('bg-gray-500').addClass('bg-light-yellow');
                    $prevIndicator.find('i').removeClass('text-gray-500').addClass('text-white');
                    $prevIndicator.find('span').removeClass('text-gray-500').addClass('text-light-yellow');

                    if (prevStep < 3 && currentStep === 3) {
                        $('#step-2-indicator').removeClass('after:border-[#FD740F]').addClass(
                            'after:border-light-yellow');
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
            });
        </script>
    @endpush
@endsection
