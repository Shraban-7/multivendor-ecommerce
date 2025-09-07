@extends('frontend.layouts.app')
@section('title', 'Profile')

@section('dashboard')
    <main>
        <div class="space-y-5 md:space-y-8 text-theme-dark">
            <!--  Account Settings -->
            <div class="space-y-4 border border-jet-gray/30 rounded md:pb-4 pb-3">
                <h2
                    class="sm:text-base text-sm font-medium border-b border-jet-gray/30 px-3 py-1.5 md:px-5 md:py-3 uppercase">
                    Account Settings
                </h2>

                <form spellcheck="false" action="{{ route('accountUpdate') }}" method="POST" enctype="multipart/form-data"
                    class="flex sm:flex-cols flex-wrap sm:flex-row gap-3 md:gap-5 px-3 py-1.5 md:px-5 md:py-2">
                    @csrf
                    <!-- Display image -->
                    <div
                        class="display-image w-20 h-20 xsm:w-32 xsm:h-32 md:w-36 md:h-36 xl:w-40 xl:h-40 rounded-full overflow-hidden border border-jet-gray/30 relative group/avater">
                        @if ($user->image)
                            <img id="preview-image" src="{{ storage_url($user->avatar) }}" alt="User Avatar"
                                class="object-cover w-full h-full" />
                        @else
                            <img id="preview-image" src="{{ asset('assets/frontend/images/user-avater.png') }}"
                                alt="User Avatar" class="object-cover w-full h-full" />
                        @endif

                        <label for="dropzone-file"
                            class="group-hover/avater:opacity-90 opacity-0 absolute flex flex-col items-center justify-center p-4 top-0 left-0 w-full h-full border-2 border-jet-gray/40 border-dashed rounded-full cursor-pointer bg-gray-100 text-center eq">
                            <svg class="size-7 xsm:size-8 mb-2 text-davy-gray" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="hidden xsm:block text-xs text-davy-gray">
                                <span class="font-semibold">Click to upload</span> or
                                drag and drop
                            </p>

                            <input id="dropzone-file" type="file" name="image" class="hidden" accept="image/*" />
                        </label>
                    </div>

                    <!-- account setting inputs -->
                    <div class="flex-1 space-y-3 sm:space-y-5">
                        <!-- full name & email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4">
                            <div class="from-ctrl space-y-1 sm:space-y-2">
                                <label for="full-name" class="block text-sm">Full Name</label>
                                <input required type="text" id="full-name" name="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                            </div>
                            <div class="from-ctrl space-y-1 sm:space-y-2">
                                <label for="email" class="block text-sm">Email</label>
                                <input required type="email" id="email" name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                            </div>
                        </div>

                        <!-- secondary email & phone number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4">
                            <div class="from-ctrl space-y-1 sm:space-y-2">
                                <label for="phone-number" class="block text-sm">Phone Number</label>
                                <input type="tel" id="phone-number" name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                            </div>
                        </div>


                        <button type="submit"
                            class="bg-primary text-white px-5 py-2 border-2 border-transparent rounded active:ring-[1] active:ring-light-yellow active:border-light-yellow text-xs md:text-sm uppercase font-bold mt-3 md:mt-5 hover:bg-theme-dark eq">
                            save changes
                        </button>
                    </div>
                </form>
            </div>

            <!--  Billing Address & Shipping Address -->
            <div class="grid grid-cols-1 gap-5 md:gap-8">
                <!-- billing -->
                <div class="billing-address space-y-4 border border-jet-gray/30 rounded md:pb-4 pb-3">
                    <h2
                        class="sm:text-base text-sm font-medium border-b border-jet-gray/30 px-3 py-1.5 md:px-5 md:py-3 uppercase">
                        Billing Address
                    </h2>

                    <!-- billing address form -->
                    @if ($billingAddresses->count() > 0)
                        <!-- Scrollable container with max 3 cards visible -->
                        <div class="overflow-y-auto px-2 space-y-3" style="max-height: calc(3 * 6.5rem + 1.5rem);">
                            @foreach ($billingAddresses as $address)
                                <label
                                    class="flex items-start gap-3 py-4 px-6 border rounded cursor-pointer hover:border-primary transition
                                                {{ $address->is_default == 1 ? 'border-primary bg-primary/5' : 'border-gray-300' }}">

                                    <input type="radio" name="billing_address_id" value="{{ $address->id }}"
                                        class="mt-1 w-4 h-4 text-primary focus:ring-primary border-gray-300"
                                        {{ $address->is_default == 1 ? 'checked' : '' }}>

                                    <div class="text-sm">
                                        <p class="font-medium">
                                            {{ ucfirst(
                                                $address->type == \App\Enums\AddressType::HOME->value
                                                    ? \App\Enums\AddressType::HOME->title()
                                                    : \App\Enums\AddressType::OFFICE->title(),
                                            ) }}
                                            - {{ $address->address }}
                                        </p>
                                        <p><strong>Name:</strong> {{ $address->customer_name }}</p>
                                        <p><strong>Phone:</strong> {{ $address->customer_phone }}</p>
                                        <p><strong>Division:</strong> {{ $address->division->name }}</p>
                                        <p><strong>District:</strong> {{ $address->district->name }}</p>
                                    </div>

                                    <div class="ml-auto flex flex-col gap-2">
                                        <button type="button" data-modal-target="edit-address-modal-{{ $address->id }}"
                                            data-modal-toggle="edit-address-modal-{{ $address->id }}"
                                            class="px-3 py-1 bg-blue-500 text-white rounded text-xs">
                                            Edit
                                        </button>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            @foreach ($billingAddresses as $address)
                <!-- Edit Address Modal -->
                <div id="edit-address-modal-{{ $address->id }}" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow-sm">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                <h3 class="text-lg font-semibold mb-4">Edit Address</h3>
                                <button type="button"
                                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                    data-modal-hide="edit-address-modal-{{ $address->id }}">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>

                            <!-- Edit Billing Address Modal -->
                            <div class="p-4 md:p-5">
                                <form id="editAddressForm-{{ $address->id }}" method="POST"
                                    action="{{ route('billing_addresses.update', $address->id) }}">
                                    @csrf


                                    <div class="space-y-3">
                                        <input type="text" name="customer_name" placeholder="Full Name"
                                            value="{{ old('customer_name', $address->customer_name) }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">

                                        <input type="text" name="customer_phone" placeholder="Phone Number"
                                            value="{{ old('customer_phone', $address->customer_phone) }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">

                                        <select name="division_id" id="division_id_{{ $address->id }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base division-select">
                                            <option value="">Select Division</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}"
                                                    {{ old('division_id', $address->division_id) == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select name="district_id" id="district_id_{{ $address->id }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base district-select">
                                            <option value="">Select District</option>
                                            <!-- districts loaded by JS -->
                                        </select>

                                        <select name="type"
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                            <option value="{{ \App\Enums\AddressType::HOME->value }}"
                                                {{ old('type', $address->type) == \App\Enums\AddressType::HOME->value ? 'selected' : '' }}>
                                                {{ \App\Enums\AddressType::HOME->title() }}</option>
                                            <option value="{{ \App\Enums\AddressType::OFFICE->value }}"
                                                {{ old('type', $address->type) == \App\Enums\AddressType::OFFICE->value ? 'selected' : '' }}>
                                                {{ \App\Enums\AddressType::OFFICE->title() }}</option>
                                        </select>

                                        <textarea name="address" placeholder="Address"
                                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">{{ old('address', $address->address) }}</textarea>

                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="is_default_{{ $address->id }}" name="is_default"
                                                    type="checkbox" value="1"
                                                    class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-light-yellow focus:border-light-yellow"
                                                    {{ old('is_default', $address->is_default) ? 'checked' : '' }} />
                                            </div>
                                            <label for="is_default_{{ $address->id }}"
                                                class="ms-2 text-sm font-medium text-gray-900">Mark as default</label>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="button" class="px-3 py-1 bg-gray-300 rounded"
                                            data-modal-hide="edit-address-modal-{{ $address->id }}">Cancel</button>
                                        <button type="submit"
                                            class="px-3 py-1 bg-primary text-white rounded">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Change Password -->
            <div class="change-password space-y-4 border border-jet-gray/30 rounded md:pb-4 pb-3">
                <h2
                    class="sm:text-base text-sm font-medium border-b border-jet-gray/30 px-3 py-1.5 md:px-5 md:py-3 uppercase">
                    Change Password
                </h2>

                <!-- change password form -->
                <form spellcheck="false" action="{{ route('updatePassword') }}" method="POST"
                    class="flex sm:flex-cols flex-wrap sm:flex-row gap-y-3 sm:gap-y-5 px-3 py-1.5 md:px-5 md:py-2">
                    @csrf
                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                        <label class="block text-sm" for="current-password">Current Password</label>
                        <div class="relative">
                            <input type="password"
                                class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                                id="current-password" name="current_password" />
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                                onclick="togglePassword('current-password', this)">
                                <i class="fa-solid fa-eye"></i>
                                <i class="fa-solid fa-eye-slash" style="display: none"></i>
                            </button>
                        </div>
                    </div>

                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                        <label class="block text-sm" for="new-password">New Password</label>
                        <div class="relative">
                            <input type="password" id="new-password"
                                class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                                placeholder="8+ characters" name="password" />
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                                onclick="togglePassword('new-password', this)">
                                <i class="fa-solid fa-eye"></i>
                                <i class="fa-solid fa-eye-slash" style="display: none"></i>
                            </button>

                        </div>
                    </div>

                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                        <label class="block text-sm" for="confirm-password">Confirm Password</label>
                        <div class="relative">
                            <input type="password"
                                class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                                id="confirm-password" name="password_confirmation" />
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                                onclick="togglePassword('confirm-password', this)">
                                <i class="fa-solid fa-eye"></i>
                                <i class="fa-solid fa-eye-slash" style="display: none"></i>
                            </button>

                        </div>
                    </div>

                    <button type="submit"
                        class="bg-primary text-white px-5 py-2 border-2 border-transparent rounded active:ring-[1] active:ring-light-yellow active:border-light-yellow text-xs md:text-sm uppercase font-bold mt-1 md:mt-2 hover:bg-theme-dark eq">
                        Change Passowrd
                    </button>
                </form>
            </div>
        </div>
    </main>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {

                function loadDistricts(divisionId, selectedDistrictId = null) {
                    let districtDropdown = $('#district_id');
                    districtDropdown.html('<option value="">Loading...</option>');

                    if (divisionId) {
                        $.get(`/get-districts/${divisionId}`, function(data) {
                            let options = '<option value="">Select District</option>';
                            // data assumed to be an object like { "1": "District 1", "2": "District 2" }
                            $.each(data, function(id, name) {
                                options += `<option value="${id}">${name}</option>`;
                            });
                            districtDropdown.html(options);

                            if (selectedDistrictId) {
                                districtDropdown.val(selectedDistrictId);
                            }
                        });
                    } else {
                        districtDropdown.html('<option value="">Select District</option>');
                    }
                }

                $('#division_id').on('change', function() {
                    let divisionId = $(this).val();
                    loadDistricts(divisionId);
                });

                // On page load: pre-select district if applicable
                let selectedDivisionId = $('#division_id').val();
                let selectedDistrictId = '{{ $billingAddress->district_id ?? '' }}';

                if (selectedDivisionId) {
                    loadDistricts(selectedDivisionId, selectedDistrictId);
                }
            });
        </script>

        <script>
            $(document).ready(function() {
                function loadDistricts(divisionId, districtId, districtSelect) {
                    if (!divisionId) {
                        districtSelect.html('<option value="">Select District</option>');
                        return;
                    }
                    districtSelect.html('<option value="">Loading...</option>');

                    $.get(`/get-districts/${divisionId}`, function(data) {
                        let options = '<option value="">Select District</option>';
                        $.each(data, function(id, name) {
                            options += `<option value="${id}">${name}</option>`;
                        });
                        districtSelect.html(options);

                        if (districtId) {
                            districtSelect.val(districtId);
                        }
                    });
                }

                @foreach ($billingAddresses as $address)
                    let divisionId{{ $address->id }} = $('#division_id_{{ $address->id }}').val();
                    let districtId{{ $address->id }} = '{{ old('district_id', $address->district_id) }}';
                    let districtSelect{{ $address->id }} = $('#district_id_{{ $address->id }}');

                    if (divisionId{{ $address->id }}) {
                        loadDistricts(divisionId{{ $address->id }}, districtId{{ $address->id }},
                            districtSelect{{ $address->id }});
                    }
                @endforeach

                $('.division-select').on('change', function() {
                    let divisionId = $(this).val();
                    let modalId = $(this).attr('id').split('_').pop();
                    let districtSelect = $('#district_id_' + modalId);

                    loadDistricts(divisionId, null, districtSelect);
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const input = document.getElementById('dropzone-file');
                const previewImage = document.getElementById('preview-image');
                const container = document.querySelector('.display-image');

                // Handle file selection
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                        }

                        reader.readAsDataURL(file);
                    }
                });

                // Handle drag and drop
                container.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    container.classList.add('border-primary');
                });

                container.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    container.classList.remove('border-primary');
                });

                container.addEventListener('drop', function(e) {
                    e.preventDefault();
                    container.classList.remove('border-primary');

                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        input.files = e.dataTransfer.files;
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                        }

                        reader.readAsDataURL(file);
                    }
                });
            });
        </script>
    @endpush
@endsection
