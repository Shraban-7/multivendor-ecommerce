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
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-sm md:text-base">
                            1
                        </div>
                        <span class="text-xs md:text-sm mt-1 font-medium text-orange-500">Personal</span>
                    </div>
                    <div class="h-1 w-8 md:w-16 bg-orange-500"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-sm md:text-base">
                            2
                        </div>
                        <span class="text-xs md:text-sm mt-1 font-medium text-gray-500">Business</span>
                    </div>
                    <div class="h-1 w-8 md:w-16 bg-gray-300"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold text-sm md:text-base">
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
                        <input type="text" id="fullName" name="fullName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter your full name" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter your email" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter your phone number" required>
                    </div>

                    <div>
                        <label for="nid" class="block text-sm font-medium text-gray-700 mb-1">NID Number</label>
                        <input type="text" id="nid" name="nid" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter your NID number" required>
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition pr-10" placeholder="Create a password" required>
                        <span class="password-toggle absolute right-3 top-9 text-gray-500">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>

                    <div class="relative">
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition pr-10" placeholder="Confirm your password" required>
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
                            <div class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer transition">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click or drag to upload</p>
                                <input type="file" class="hidden" accept="image/*" required>
                            </div>
                        </div>

                        <div class="file-upload-container">
                            <label class="block text-sm font-medium text-gray-700 mb-1">NID Front Image</label>
                            <div class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer transition">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click or drag to upload</p>
                                <input type="file" class="hidden" accept="image/*" required>
                            </div>
                        </div>

                        <div class="file-upload-container">
                            <label class="block text-sm font-medium text-gray-700 mb-1">NID Back Image</label>
                            <div class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer transition">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click or drag to upload</p>
                                <input type="file" class="hidden" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="button" class="next-step px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
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
                        <label for="businessName" class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                        <input type="text" id="businessName" name="businessName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter your business name" required>
                    </div>

                    <div>
                        <label for="businessEmail" class="block text-sm font-medium text-gray-700 mb-1">Business Email</label>
                        <input type="email" id="businessEmail" name="businessEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter business email" required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="businessAddress" class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
                        <textarea id="businessAddress" name="businessAddress" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter your business address" required></textarea>
                    </div>

                    <div>
                        <label for="division" class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                        <select id="division" name="division" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" required>
                            <option value="" disabled selected>Select Division</option>
                            <option value="dhaka">Dhaka</option>
                            <option value="chattogram">Chattogram</option>
                            <option value="rajshahi">Rajshahi</option>
                            <option value="khulna">Khulna</option>
                            <option value="barishal">Barishal</option>
                            <option value="sylhet">Sylhet</option>
                            <option value="rangpur">Rangpur</option>
                            <option value="mymensingh">Mymensingh</option>
                        </select>
                    </div>

                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                        <select id="district" name="district" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" required>
                            <option value="" disabled selected>Select District</option>
                            <!-- Districts will be populated based on division selection -->
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Business Logo</h3>

                    <div class="file-upload-container max-w-xs">
                        <div class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600">Click or drag to upload business logo</p>
                            <input type="file" class="hidden" accept="image/*" required>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" class="prev-step px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button type="button" class="next-step px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
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
                        <label for="tradeLicense" class="block text-sm font-medium text-gray-700 mb-1">Trade License Number</label>
                        <input type="text" id="tradeLicense" name="tradeLicense" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" placeholder="Enter trade license number" required>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Required Images</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="file-upload-container">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trade License Image</label>
                            <div class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click or drag to upload trade license</p>
                                <input type="file" class="hidden" accept="image/*" required>
                            </div>
                        </div>

                        <div class="file-upload-container">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shop Image</label>
                            <div class="file-upload border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer transition">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Click or drag to upload shop image</p>
                                <input type="file" class="hidden" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" class="prev-step px-6 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Form step navigation
        const steps = document.querySelectorAll('.form-step');
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        const progressSteps = document.querySelectorAll('.flex.flex-col.items-center');

        let currentStep = 0;

        // Initialize first step
        showStep(currentStep);

        // Next button event listeners
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                    updateProgressIndicator();
                }
            });
        });

        // Previous button event listeners
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                    updateProgressIndicator();
                }
            });
        });

        // Show the current step
        function showStep(stepIndex) {
            steps.forEach((step, index) => {
                step.classList.toggle('active', index === stepIndex);
            });
        }

        // Update progress indicator
        function updateProgressIndicator() {
            progressSteps.forEach((step, index) => {
                const circle = step.querySelector('div');
                const label = step.querySelector('span');

                if (index <= currentStep) {
                    circle.classList.remove('bg-gray-300', 'text-gray-600');
                    circle.classList.add('bg-orange-500', 'text-white');
                    label.classList.remove('text-gray-500');
                    label.classList.add('text-orange-500');
                } else {
                    circle.classList.remove('bg-orange-500', 'text-white');
                    circle.classList.add('bg-gray-300', 'text-gray-600');
                    label.classList.remove('text-orange-500');
                    label.classList.add('text-gray-500');
                }
            });
        }

        // Password toggle functionality
        const passwordToggles = document.querySelectorAll('.password-toggle');
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // File upload functionality
        const fileUploads = document.querySelectorAll('.file-upload');
        fileUploads.forEach(upload => {
            const input = upload.querySelector('input[type="file"]');

            // Click to upload
            upload.addEventListener('click', function() {
                input.click();
            });

            // Drag and drop functionality
            upload.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            upload.addEventListener('dragleave', function() {
                this.classList.remove('dragover');
            });

            upload.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');

                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;

                    // Update UI to show file name
                    const fileName = e.dataTransfer.files[0].name;
                    const fileText = this.querySelector('p');
                    fileText.textContent = fileName;
                    fileText.classList.add('text-orange-500', 'font-medium');
                }
            });

            // Change event for regular file selection
            input.addEventListener('change', function() {
                if (this.files.length) {
                    const fileName = this.files[0].name;
                    const fileText = upload.querySelector('p');
                    fileText.textContent = fileName;
                    fileText.classList.add('text-orange-500', 'font-medium');
                }
            });
        });

        // Division to District mapping (simplified)
        const divisionDistricts = {
            dhaka: ['Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Kishoreganj'],
            chattogram: ['Chattogram', 'Cox\'s Bazar', 'Rangamati', 'Bandarban'],
            rajshahi: ['Rajshahi', 'Bogra', 'Pabna', 'Sirajganj'],
            khulna: ['Khulna', 'Satkhira', 'Bagerhat', 'Jessore'],
            barishal: ['Barishal', 'Patuakhali', 'Bhola', 'Jhalokati'],
            sylhet: ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
            rangpur: ['Rangpur', 'Dinajpur', 'Nilphamari', 'Gaibandha'],
            mymensingh: ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur']
        };

        // Update districts based on division selection
        const divisionSelect = document.getElementById('division');
        const districtSelect = document.getElementById('district');

        divisionSelect.addEventListener('change', function() {
            const selectedDivision = this.value;
            const districts = divisionDistricts[selectedDivision] || [];

            // Clear previous options
            districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';

            // Add new options
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.toLowerCase();
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        });
    });
</script>
@endpush