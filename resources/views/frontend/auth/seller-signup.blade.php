@extends('frontend.layouts.app')
@section('title', 'Seller Registration')

@push('header')
    <style>
        .form-step {
            display: none;
            opacity: 0;
            transform: translateX(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .form-step.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }

        .file-upload {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: #f59e0b;
            background-color: #fffbeb;
        }

        .file-upload.dragover {
            border-color: #f59e0b;
            background-color: #fef3c7;
        }

        .password-toggle {
            cursor: pointer;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #f59e0b;
        }
    </style>
@endpush

@section('content')

    <div class="flex items-center justify-center mb-8">
        <div class="max-w-2xl w-full bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Progress Indicator -->
            <div class="bg-white p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-sm md:text-base">
                                1
                            </div>
                            <span class="text-xs md:text-sm mt-1 font-medium text-orange-500">Personal</span>
                        </div>
                        <div class="h-1 w-8 md:w-16 bg-orange-500"></div>
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-sm md:text-base">
                                2
                            </div>
                            <span class="text-xs md:text-sm mt-1 font-medium text-gray-500">Business</span>
                        </div>
                        <div class="h-1 w-8 md:w-16 bg-gray-300"></div>
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-sm md:text-base">
                                3
                            </div>
                            <span class="text-xs md:text-sm mt-1 font-medium text-gray-500">Shop</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="p-6 md:p-8">
                <!-- Step 1: Personal Information -->
                <form id="step1" class="form-step active">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Personal Information</h2>
                    <p class="text-gray-600 mb-6">Please provide your personal details for verification.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label for="fullName" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="name" name="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter your full name" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter your email" required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="phone" name="phone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter your phone number" required>
                        </div>

                        <div>
                            <label for="nid" class="block text-sm font-medium text-gray-700 mb-1">NID Number</label>
                            <input type="text" id="nid_no" name="nid_no"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter your NID number" required>
                        </div>

                        <div class="relative">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition pr-10"
                                placeholder="Create a password" required>
                            <span class="password-toggle absolute right-3 top-9 text-gray-500">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>

                        <div class="relative">
                            <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                                Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition pr-10"
                                placeholder="Confirm your password" required>
                            <span class="password-toggle absolute right-3 top-9 text-gray-500">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Required Images</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Personal Image</label>

                                <!-- Upload Placeholder -->
                                <div
                                    class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer transition">
                                    <input type="file" id="profile_image" name="image" accept="image/*"
                                        class="tempImageInput hidden" />
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click or drag to upload</p>
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

                            <!-- NID Front Image -->
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-1">NID Front Image</label>

                                <div
                                    class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer transition">
                                    <input type="file" id="nid_front_image" name="nid_front_image" accept="image/*"
                                        class="tempImageInput hidden" />
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click or drag to upload</p>
                                </div>

                                <div id="nid_front_imagePreviewWrapper"
                                    class="hidden absolute inset-0 flex items-center justify-center z-10">
                                    <img id="nid_front_imagePreview"
                                        class="w-[100px] h-[100px] object-cover rounded-full shadow" />
                                    <button type="button"
                                        class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                        data-input="nid_front_image">
                                        <i class="fas fa-times text-red-600 text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- NID Back Image -->
                            <div class="file-upload-container">
                                <label class="block text-sm font-medium text-gray-700 mb-1">NID Back Image</label>

                                <div
                                    class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer transition">
                                    <input type="file" id="nid_back_image" name="nid_back_image" accept="image/*"
                                        class="tempImageInput hidden" />
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click or drag to upload</p>
                                </div>

                                <div id="nid_back_imagePreviewWrapper"
                                    class="hidden absolute inset-0 flex items-center justify-center z-10">
                                    <img id="nid_back_imagePreview"
                                        class="w-[100px] h-[100px] object-cover rounded-full shadow" />
                                    <button type="button"
                                        class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                        data-input="nid_back_image">
                                        <i class="fas fa-times text-red-600 text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="button" data-current="1" data-next="2"
                            class="next-step px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

                <!-- Step 2: Business Information -->
                <form id="step2" class="form-step">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Business Information</h2>
                    <p class="text-gray-600 mb-6">Please provide your business details.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">Business
                                Name</label>
                            <input type="text" id="business_name" name="business_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter your business name" required>
                        </div>

                        <div>
                            <label for="business_email" class="block text-sm font-medium text-gray-700 mb-1">Business
                                Email</label>
                            <input type="email" id="business_email" name="business_email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter business email" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="business_address" class="block text-sm font-medium text-gray-700 mb-1">Business
                                Address</label>
                            <textarea id="business_address" name="business_address" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter your business address" required></textarea>
                        </div>

                        <div>
                            <label for="division" class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                            <select id="division_id" name="division_id" name="division"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                required>
                                <option value="" disabled selected>Select Division</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                            <select id="district_id" name="district_id" name="district"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                required>
                                <option value="" disabled selected>Select District</option>
                                <!-- Districts will be populated based on division selection -->
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Business Logo</h3>

                        <div class="file-upload-container relative max-w-xs">
                            <!-- Upload Box -->
                            <div
                                class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click or drag to upload business logo</p>
                                <input type="file" id="business_logo" name="business_logo"
                                    class="hidden tempImageInput" accept="image/*" required>
                            </div>

                            <!-- Image Preview -->
                            <div id="business_logoPreviewWrapper"
                                class="hidden absolute inset-0 flex items-center justify-center z-10">
                                <img id="business_logoPreview"
                                    class="w-[100px] h-[100px] object-cover rounded-full shadow" />
                                <button type="button"
                                    class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                    data-input="business_logo">
                                    <i class="fas fa-times text-red-600 text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-between mt-8">
                        <button type="button" data-current="2" data-prev="1"
                            class="prev-step px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="button" data-current="2" data-next="3"
                            class="next-step px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

                <!-- Step 3: Shop Information -->
                <form id="step3" class="form-step">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Shop Information</h2>
                    <p class="text-gray-600 mb-6">Please provide your shop details.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label for="trade_license_no" class="block text-sm font-medium text-gray-700 mb-1">Trade
                                License
                                Number</label>
                            <input type="text" id="trade_license_no" name="trade_license_no"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                placeholder="Enter trade license number" required>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Required Images</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Trade License -->
                            <div class="file-upload-container relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Trade License Image</label>

                                <div
                                    class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition">
                                    <input type="file" id="trade_license_image" name="trade_license_image"
                                        accept="image/*" class="tempImageInput hidden" required />
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click or drag to upload trade license</p>
                                </div>

                                <div id="trade_license_imagePreviewWrapper"
                                    class="hidden absolute inset-0 flex items-center justify-center z-10">
                                    <img id="trade_license_imagePreview"
                                        class="w-[200px] h-[120px] object-cover rounded-md shadow" />
                                    <button type="button"
                                        class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                        data-input="trade_license_image">
                                        <i class="fas fa-times text-red-600 text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Shop Image -->
                            <div class="file-upload-container relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Shop Image</label>
                                <div
                                    class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition">
                                    <input type="file" id="shop_image" name="shop_image" accept="image/*"
                                        class="tempImageInput hidden" required />
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click or drag to upload shop image</p>
                                </div>

                                <div id="shop_imagePreviewWrapper"
                                    class="hidden absolute inset-0 flex items-center justify-center z-10">
                                    <img id="shop_imagePreview"
                                        class="w-[200px] h-[120px] object-cover rounded-md shadow" />
                                    <button type="button"
                                        class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow remove-image-btn"
                                        data-input="shop_image">
                                        <i class="fas fa-times text-red-600 text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" data-current="3" data-prev="2"
                            class="prev-step px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
                            Submit Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.tempImageInput').change(function() {
                let input = this;
                let file = input.files[0];

                if (!file) return;

                let inputName = $(input).attr('name');

                let formData = new FormData();
                formData.append('image', file);
                formData.append('name', inputName);

                $.ajax({
                    url: "{{ route('seller.signup.uploadTempImage') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {},
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors.image) {
                                alert(errors.image[0]);
                            }
                        } else {
                            console.error('Upload failed:', xhr);
                        }
                    }
                });
            });

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

            let currentStep = 1;

            function collectStepData(stepForm) {
                let formData = new FormData();

                stepForm.find(':input').each(function() {
                    const $input = $(this);
                    const name = $input.attr('name');
                    if (!name) return;

                    if ($input.attr('type') === 'file') {
                        return;
                    }

                    formData.append(name, $input.val());
                });

                formData.append('step', currentStep);
                formData.append('_token', '{{ csrf_token() }}');
                return formData;
            }

            $(document).on('input change', ':input', function() {
                const $field = $(this);
                $field.removeClass('border-red-500');
                $field.next('.error-msg').remove();
            });

            function submitStep(formData, callback, $btn = null) {
                let originalContent = $btn ? $btn.html() : null;

                if ($btn) {
                    $btn.prop('disabled', true).html(`
                        <svg class="animate-spin h-5 w-5 mr-2 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg> Loading...
                    `);
                }

                $.ajax({
                    url: "{{ route('seller.signup') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        callback(res);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (const [field, messages] of Object.entries(errors)) {
                                const $field = $(`#step${currentStep} [name="${field}"]`);
                                if ($field.length) {
                                    $field.addClass('border-red-500');
                                    if (!$field.next('.error-msg').length) {
                                        $field.after(
                                            '<span class="error-msg text-red-500 text-xs mt-1">' +
                                            messages[0] + '</span>'
                                        );
                                    }
                                }
                            }
                        } else {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                        }
                    },
                    complete: function() {
                        if ($btn && originalContent !== null) {
                            $btn.prop('disabled', false).html(originalContent);
                        }
                    }
                });
            }

            $(document).on('click', '.next-step', function() {
                const nextStep = $(this).data('next');
                const $btn = $(this);
                const stepForm = $('#step' + currentStep);
                const formData = collectStepData(stepForm);

                submitStep(formData, function(res) {
                    stepForm.removeClass('active').addClass('hidden');
                    $('#step' + nextStep).removeClass('hidden').addClass('active');
                    updateStepIndicators(currentStep, nextStep);
                    currentStep = nextStep;
                }, $btn);
            });

            $(document).on('click', '.prev-step', function() {
                const prevStep = $(this).data('prev');
                $('#step' + currentStep).removeClass('active').addClass('hidden');
                $('#step' + prevStep).removeClass('hidden').addClass('active');
                const $nextBtn = $('#step' + prevStep).find('.next-step');
                $nextBtn.prop('disabled', false).html('Next');

                updateStepIndicators(currentStep, prevStep);
                currentStep = prevStep;
            });

            $('#step3').submit(function(e) {
                e.preventDefault();
                const $btn = $(this).find('button[type="submit"]');
                const formData = collectStepData($(this));

                submitStep(formData, function(res) {
                    window.location.href = "{{ route('frontend.message') }}";
                }, $btn);
            });


            function updateStepIndicators(fromStep, toStep) {
                const steps = [1, 2, 3];

                steps.forEach((step, index) => {
                    const $circle = $('.flex.items-center.justify-between .flex.flex-col.items-center').eq(
                        index).find('div:first');
                    const $label = $('.flex.items-center.justify-between .flex.flex-col.items-center').eq(
                        index).find('span:first');
                    const $bar = $('.flex.items-center.justify-between .h-1').eq(index === 0 ? 0 : index -
                        1);

                    if (step < toStep) {
                        $circle.removeClass('bg-gray-300 text-gray-600').addClass(
                            'bg-orange-500 text-white');
                        $label.removeClass('text-gray-500').addClass('text-orange-500');
                        if ($bar.length) $bar.removeClass('bg-gray-300').addClass('bg-orange-500');
                    } else if (step === toStep) {
                        $circle.removeClass('bg-gray-300 text-gray-600').addClass(
                            'bg-orange-500 text-white');
                        $label.removeClass('text-gray-500').addClass('text-orange-500');
                        if ($bar.length) $bar.removeClass('bg-gray-300').addClass('bg-orange-500');
                    } else {
                        $circle.removeClass('bg-orange-500 text-white').addClass(
                            'bg-gray-300 text-gray-600');
                        $label.removeClass('text-orange-500').addClass('text-gray-500');
                        if ($bar.length) $bar.removeClass('bg-orange-500').addClass('bg-gray-300');
                    }
                });
            }

            $(document).on('click', 'input[type="file"]', function(e) {
                e.stopPropagation();
            });

            $(document).on('click', '.file-upload', function() {
                $(this).find('input[type="file"]').trigger('click');
            });

            $(document).on('change', 'input[type="file"]', function() {
                const file = this.files[0];
                const uploadBox = $(this).closest('.file-upload');

                uploadBox.find('.image-preview-wrapper').remove();

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewHTML = `
                                <div class="image-preview-wrapper relative flex justify-center mt-2">
                                    <img src="${e.target.result}" class="w-[100px] h-[100px] object-cover rounded shadow" />
                                    <button type="button" class="absolute -top-2 -right-2 bg-red-600 rounded-full py-1 px-2.5 shadow-lg remove-image-btn">
                                        <i class="fas fa-times text-white text-xs"></i>
                                    </button>
                                </div>
                        `;

                        uploadBox.children('i, p').hide();

                        uploadBox.append(previewHTML);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $(document).on('click', '.remove-image-btn', function(e) {
                e.stopPropagation();
                const wrapper = $(this).closest('.file-upload');
                wrapper.find('input[type="file"]').val('');
                wrapper.find('.image-preview-wrapper').remove();
                wrapper.children('i, p').show();
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
                        window.location.href = "{{ route('frontend.message') }}";
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
