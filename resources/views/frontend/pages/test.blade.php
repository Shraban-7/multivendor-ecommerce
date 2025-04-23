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
                                    class="flex items-center justify-center w-10 h-10 bg-gray-500 rounded-full lg:h-12 lg:w-12 shrink-0">
                                    <i class="fas fa-file-alt text-white text-base lg:text-lg"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-500">Documents</span>
                            </li>
                        </ol>
                    </div>

                    <div class="registration-form mt-3 sm:mt-5">
                        <!-- Registration Form -->
                        <form action="#" method="POST" enctype="multipart/form-data" class="w-full mb-3 md:mb-4"
                            id="sellerRegistrationForm">
                            @csrf

                            <!-- Step 1: Personal Information -->
                            <div id="step-1" class="registration-step active">
                                <div class="flex flex-col gap-4 sm:gap-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray" for="fullname">Full Name
                                                <span class="text-persian-red">*</span></label>
                                            <input required id="fullname" type="text" name="fullname"
                                                placeholder="John Doe"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>

                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray" for="username">Username
                                                <span class="text-persian-red">*</span></label>
                                            <input required id="username" type="text" name="username"
                                                placeholder="johndoe123"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray" for="email">Email
                                                <span class="text-persian-red">*</span></label>
                                            <input required id="email" type="email" name="email"
                                                placeholder="john.doe@example.com"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>

                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray" for="phone">Phone
                                                Number</label>
                                            <input id="phone" type="tel" name="phone" placeholder="(123) 456-7890"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray" for="password">Password
                                                <span class="text-persian-red">*</span></label>
                                            <input required type="password" id="password" name="password"
                                                placeholder="•••••••••••••"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>

                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray"
                                                for="password_confirmation">Confirm Password <span
                                                    class="text-persian-red">*</span></label>
                                            <input required type="password" id="password_confirmation"
                                                name="password_confirmation" placeholder="•••••••••••••"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>
                                    </div>

                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Profile Image</label>

                                        <div id="profileImageUpload"
                                            class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition">
                                            <input type="file" id="profile_image" name="image" accept="image/*"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                onchange="previewImage(this, 'profileImagePreviewWrapper')" />

                                            <div
                                                class="flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                                                <i class="fas fa-cloud-upload-alt text-3xl mb-2"></i>
                                                <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                                <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                            </div>

                                            <div id="profileImagePreviewWrapper" class="hidden mt-4 relative">
                                                <img id="profileImagePreview"
                                                    class="w-32 h-32 object-cover mx-auto rounded-md shadow" />
                                                <button type="button"
                                                    class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow"
                                                    onclick="removeImage('profileImageUpload', 'profile_image')">
                                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="grid grid-cols-1  gap-4">
                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray" for="nid_no">NID
                                                Number</label>
                                            <input id="nid_no" type="text" name="nid_no" placeholder="123456789"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- NID Front Image Upload -->
                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">NID Front Image</label>

                                            <div id="nidFrontUpload"
                                                class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition">
                                                <input type="file" id="nid_front_image" name="nid_front_image"
                                                    accept="image/*"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                    onchange="previewImage(this, 'nidFrontPreviewWrapper')" />

                                                <div
                                                    class="flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                                                    <i class="fas fa-id-card text-3xl mb-2"></i>
                                                    <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                                    <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                                </div>

                                                <div id="nidFrontPreviewWrapper" class="hidden mt-4 relative">
                                                    <img id="nidFrontPreview"
                                                        class="w-32 h-32 object-cover mx-auto rounded-md shadow" />
                                                    <button type="button"
                                                        class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow"
                                                        onclick="removeImage('nidFrontUpload', 'nid_front_image')">
                                                        <i class="fas fa-times text-red-600 text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- NID Back Image Upload -->
                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">NID Back Image</label>

                                            <div id="nidBackUpload"
                                                class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition">
                                                <input type="file" id="nid_back_image" name="nid_back_image"
                                                    accept="image/*"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                    onchange="previewImage(this, 'nidBackPreviewWrapper')" />

                                                <div
                                                    class="flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                                                    <i class="fas fa-id-card-clip text-3xl mb-2"></i>
                                                    <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                                    <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                                </div>

                                                <div id="nidBackPreviewWrapper" class="hidden mt-4 relative">
                                                    <img id="nidBackPreview"
                                                        class="w-32 h-32 object-cover mx-auto rounded-md shadow" />
                                                    <button type="button"
                                                        class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow"
                                                        onclick="removeImage('nidBackUpload', 'nid_back_image')">
                                                        <i class="fas fa-times text-red-600 text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="flex justify-end mt-2">
                                        <button type="button" class="next-step" data-current="1" data-next="2"
                                            class="text-white bg-light-yellow hover:bg-light-yellow/90 focus:ring-4 focus:ring-light-yellow/50 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none transition-all">
                                            Next: Business Information
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Business Information -->
                            <div id="step-2" class="registration-step hidden">
                                <div class="flex flex-col gap-4 sm:gap-5">
                                    <!-- Business information fields -->
                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-davy-gray"
                                            for="business_name">Business Name <span
                                                class="text-persian-red">*</span></label>
                                        <input required id="business_name" type="text" name="business_name"
                                            placeholder="Your Business Name"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                    </div>

                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-davy-gray"
                                            for="business_email">Business Email</label>
                                        <input id="business_email" type="email" name="business_email"
                                            placeholder="business@example.com"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                    </div>

                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-davy-gray"
                                            for="business_address">Business Address <span
                                                class="text-persian-red">*</span></label>
                                        <textarea id="business_address" name="business_address" rows="3" placeholder="Full address of your business"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white resize-none"></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray"
                                                for="country_id">Country <span class="text-persian-red">*</span></label>
                                            <select id="country_id" name="country_id"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white">
                                                <option value="" selected disabled>Select Country</option>
                                                <option value="1">United States</option>
                                                <option value="2">United Kingdom</option>
                                                <option value="3">Canada</option>
                                                <!-- Add more country options here -->
                                            </select>
                                        </div>

                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray"
                                                for="state_id">State/Region <span
                                                    class="text-persian-red">*</span></label>
                                            <select id="state_id" name="state_id"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white">
                                                <option value="" selected disabled>Select State</option>
                                                <option value="1">California</option>
                                                <option value="2">New York</option>
                                                <option value="3">Texas</option>
                                                <!-- Add more state options here -->
                                            </select>
                                        </div>

                                        <div class="form-ctrl space-y-2">
                                            <label class="block text-sm font-medium text-davy-gray"
                                                for="zip">ZIP/Postal Code</label>
                                            <input id="zip" type="text" name="zip" placeholder="12345"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>
                                    </div>

                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Business Logo</label>

                                        <div id="businessLogoUpload"
                                            class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition">
                                            <input type="file" id="business_logo" name="business_logo"
                                                accept="image/*"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                onchange="previewImage(this, 'businessLogoPreviewWrapper')" />

                                            <div
                                                class="flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                                                <i class="fas fa-building text-3xl mb-2"></i>
                                                <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                                <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                            </div>

                                            <div id="businessLogoPreviewWrapper" class="hidden mt-4 relative">
                                                <img id="businessLogoPreview"
                                                    class="w-32 h-32 object-cover mx-auto rounded-md shadow" />
                                                <button type="button"
                                                    class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow"
                                                    onclick="removeImage('businessLogoUpload', 'business_logo')">
                                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between mt-2">
                                        <button type="button" class="prev-step" data-current="2" data-prev="1"
                                            class="text-light-yellow border border-light-yellow hover:bg-light-yellow/10 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none focus:ring-4 focus:ring-light-yellow/50 transition-all">
                                            Previous
                                        </button>
                                        <button type="button" class="next-step" data-current="2" data-next="3"
                                            class="text-white bg-light-yellow hover:bg-light-yellow/90 focus:ring-4 focus:ring-light-yellow/50 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none transition-all">
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
                                                placeholder="TL12345678"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base bg-white" />
                                        </div>
                                    </div>

                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Trade License Image</label>
                                        <div id="tradeLicenseUpload"
                                            class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition">
                                            <input type="file" id="trade_license_image" name="trade_license_image"
                                                accept="image/*"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                onchange="previewImage(this, 'tradeLicensePreviewWrapper')" />
                                            <div
                                                class="flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                                                <i class="fas fa-file-alt text-3xl mb-2"></i>
                                                <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                                <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                            </div>
                                            <div id="tradeLicensePreviewWrapper" class="hidden mt-4 relative">
                                                <img id="tradeLicensePreview"
                                                    class="w-32 h-32 object-cover mx-auto rounded-md shadow" />
                                                <button type="button"
                                                    class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow"
                                                    onclick="removeImage('tradeLicenseUpload', 'trade_license_image')">
                                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-ctrl space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Shop Image</label>
                                        <div id="shopImageUpload"
                                            class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition">
                                            <input type="file" id="shop_image" name="shop_image" accept="image/*"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                onchange="previewImage(this, 'shopImagePreviewWrapper')" />
                                            <div
                                                class="flex flex-col items-center justify-center text-gray-500 pointer-events-none">
                                                <i class="fas fa-store text-3xl mb-2"></i>
                                                <p class="text-sm font-medium">Click to upload or drag and drop</p>
                                                <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 5MB)</p>
                                            </div>
                                            <div id="shopImagePreviewWrapper" class="hidden mt-4 relative">
                                                <img id="shopImagePreview"
                                                    class="w-32 h-32 object-cover mx-auto rounded-md shadow" />
                                                <button type="button"
                                                    class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow"
                                                    onclick="removeImage('shopImageUpload', 'shop_image')">
                                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Agree to Terms & Privacy policy Checkbox -->
                                    <div class="flex items-start text-davy-gray/80 mt-4">
                                        <div class="flex items-center h-5">
                                            <input required id="terms" type="checkbox"
                                                class="w-4 h-4 text-light-yellow focus:ring-light-yellow border-gray-300 rounded" />
                                        </div>
                                        <label for="terms" class="ml-2 text-sm">By signing up, you are creating
                                            a tesko seller account, and you
                                            agree to tesko's
                                            <a href="#" class="text-light-yellow hover:underline eq">Term
                                                of Use</a>
                                            and
                                            <a href="#" class="text-light-yellow hover:underline eq">Privacy
                                                Policy</a>.</label>
                                    </div>

                                    <div class="flex justify-between mt-2">
                                        <button type="button" class="prev-step" data-current="3" data-prev="2"
                                            class="text-light-yellow border border-light-yellow hover:bg-light-yellow/10 font-medium rounded-lg text-sm px-6 py-3 focus:outline-none focus:ring-4 focus:ring-light-yellow/50 transition-all">
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
                $(document).on('click', '.next-step', function() {
                    const currentStep = parseInt($(this).data('current'));
                    const nextStep = parseInt($(this).data('next'));

                    // Hide current step
                    $('#step-' + currentStep).addClass('hidden').removeClass('active');

                    // Show next step
                    $('#step-' + nextStep).removeClass('hidden').addClass('active');

                    // Update step indicators
                    updateStepIndicators(currentStep, nextStep);
                });

                // Previous step button click
                $(document).on('click', '.prev-step', function() {
                    const currentStep = parseInt($(this).data('current'));
                    const prevStep = parseInt($(this).data('prev'));

                    // Hide current step
                    $('#step-' + currentStep).addClass('hidden').removeClass('active');

                    // Show previous step
                    $('#step-' + prevStep).removeClass('hidden').addClass('active');

                    // Update step indicators for previous navigation
                    updateStepIndicatorsBackward(currentStep, prevStep);
                });

                // Function to update step indicators when moving forward
                function updateStepIndicators(currentStep, nextStep) {
                    // Mark current step as completed (persian-red/orange color)
                    const $currentIndicator = $('#step-' + currentStep + '-indicator');
                    $currentIndicator.removeClass('text-light-yellow').addClass('text-[#FD740F]');
                    $currentIndicator.find('div').removeClass('bg-light-yellow').addClass('bg-[#FD740F]');
                    // Make sure icon is white when background is colored
                    $currentIndicator.find('i').removeClass('text-gray-500').addClass('text-white');
                    $currentIndicator.find('span').removeClass('text-light-yellow').addClass('text-[#FD740F]');

                    // Update the "after" line (progress bar)
                    if (currentStep < 3) {
                        $currentIndicator.removeClass('after:border-gray-200').addClass('after:border-[#FD740F]');
                    }

                    // Mark next step as active (light-yellow color)
                    const $nextIndicator = $('#step-' + nextStep + '-indicator');
                    $nextIndicator.addClass('text-light-yellow');
                    $nextIndicator.find('div').removeClass('bg-gray-500').addClass('bg-light-yellow');
                    // Make sure icon is white when background is colored
                    $nextIndicator.find('i').removeClass('text-gray-500').addClass('text-white');
                    $nextIndicator.find('span').removeClass('text-gray-500').addClass('text-light-yellow');
                }

                // Function to update step indicators when moving backward
                function updateStepIndicatorsBackward(currentStep, prevStep) {
                    // Reset current step to default state (gray)
                    const $currentIndicator = $('#step-' + currentStep + '-indicator');
                    $currentIndicator.removeClass('text-light-yellow');
                    $currentIndicator.find('div').removeClass('bg-light-yellow').addClass('bg-gray-500');
                    // Make icon gray when background is gray
                    $currentIndicator.find('i').removeClass('text-white').addClass('text-gray-500');
                    $currentIndicator.find('span').removeClass('text-light-yellow').addClass('text-gray-500');

                    // Mark previous step as active (light-yellow color)
                    const $prevIndicator = $('#step-' + prevStep + '-indicator');
                    $prevIndicator.removeClass('text-[#FD740F]').addClass('text-light-yellow');
                    $prevIndicator.find('div').removeClass('bg-[#FD740F]').addClass('bg-light-yellow');
                    // Make sure icon is white when background is colored
                    $prevIndicator.find('i').removeClass('text-gray-500').addClass('text-white');
                    $prevIndicator.find('span').removeClass('text-[#FD740F]').addClass('text-light-yellow');

                    // Update the "after" line (progress bar)
                    if (prevStep < 3) {
                        if (currentStep === 3) {
                            $('#step-2-indicator').removeClass('after:border-[#FD740F]').addClass(
                                'after:border-light-yellow');
                        }
                    }
                }

                // Image preview functionality
                $(document).on('change', 'input[type="file"]', function() {
                    const inputId = $(this).attr('id');
                    const previewWrapperId = inputId + 'PreviewWrapper';

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        const previewId = inputId + 'Preview';

                        reader.onload = function(e) {
                            $('#' + previewId).attr('src', e.target.result);
                            $('#' + previewWrapperId).removeClass('hidden');
                        };

                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // Image removal functionality
                $(document).on('click', '[onclick*="removeImage"]', function(e) {
                    e.preventDefault();

                    const uploadContainerId = $(this).closest('div[id$="Upload"]').attr('id');
                    const inputId = uploadContainerId.replace('Upload', '');
                    const previewWrapperId = inputId + 'PreviewWrapper';

                    $('#' + inputId).val('');
                    $('#' + previewWrapperId).addClass('hidden');
                });
            });
        </script>
    @endpush
@endsection
