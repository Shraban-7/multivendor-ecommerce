@extends('frontend.layouts.app')
@section('title', 'Checkout')

@section('content')
    <div>
        <form id="checkout-form" class="container mx-auto grid gap-6 lg:grid-cols-3 text-gray-800">
            <!-- Left Section -->
            <div class="lg:col-span-2 space-y-6">

                <div class="flex-1 space-y-8">

                    <!-- Section: Saved Addresses -->
                    <section class="bg-white p-6 sm:p-8 rounded-2xl shadow-soft border border-gray-100">

                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">
                                Shipping Address
                            </h2>

                            <button type="button" onclick="toggleModal('addBillingAddressModal')"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-sm font-medium text-primary border border-primary/50 rounded-md hover:bg-primary/5 hover:border-primary transition-colors duration-150">
                                <i class="fa-solid fa-plus text-[13px]"></i>
                                <span>Add New</span>
                            </button>
                        </div>

                        <!-- Address Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

                            @if ($billingAddresses->count() > 0)
                                @foreach ($billingAddresses as $address)
                                    <!-- Saved Address 1 (Active) -->
                                    <label class="relative block cursor-pointer group">
                                        <input type="radio" name="billing_address_id" value="{{ $address->id }}"
                                            class="peer sr-only" {{ $address->is_default ? 'checked' : '' }}>
                                        <div
                                            class="p-5 rounded-xl border-2 border-primary-100  peer-checked:border-primary-500 peer-checked:bg-white relative transition-all duration-200">
                                            <div
                                                class="absolute top-4 right-4 text-primary-600 opacity-0 peer-checked:opacity-100">
                                                <i data-lucide="check-circle-2" class="w-6 h-6 fill-primary-100"></i>
                                            </div>
                                            <div class="flex items-center justify-between gap-2 mb-2">
                                                <span
                                                    class="px-2 py-1 rounded-md bg-white border border-gray-200 text-xs font-bold text-gray-700 uppercase tracking-wide">{{ ucfirst($address->type->value == \App\Enums\AddressType::HOME->value ? 'Home' : 'Office') }}</span>
                                                <button type="button" class="text-gray-400 hover:text-primary transition"
                                                    onclick="toggleModal('editBillingAddressModal-{{ $address->id }}')"
                                                    title="Edit address">
                                                    <i class="fa-solid fa-pen text-[13px]"></i>
                                                </button>
                                            </div>
                                            <h3 class="font-bold text-gray-900 text-sm">{{ $address->customer_name }}</h3>
                                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $address->address }}
                                                ,<br>{{ $address->district->name }}, {{ $address->division->name }}</p>
                                            <p class="text-sm text-gray-900 font-medium mt-2">{{ $address->customer_phone }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </section>
                </div>
            </div>

            <!-- Right Section (Order Summary + Payment) -->
            <aside class="space-y-5">
                <div class="bg-white rounded-lg border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        Order Summary
                    </h2>

                    <dl class="text-sm space-y-2 mb-4">
                        <div class="flex justify-between">
                            <dt>Subtotal</dt>
                            <dd class="font-medium">{{ money($total) }}</dd>
                        </div>
                        {{-- <div class="flex justify-between">
                            <dt>Discount</dt>
                            <dd class="text-primary">-{{ money($discount) }}</dd>
            </div> --}}
                        <div class="flex justify-between">
                            <dt>Shipping Fee</dt>
                            <dd>+{{ money($shipping_fee) }}</dd>
                        </div>
                    </dl>

                    <div class="border-t border-dashed my-3"></div>

                    <div class="flex justify-between items-center text-base font-semibold mb-2">
                        <span>Total</span>
                        <span class="text-lg text-primary">{{ money($total + $shipping_fee) }}</span>
                    </div>

                    <div class="mt-5">
                        <h2 class="text-base font-semibold mb-4 text-gray-800">
                            Payment Method
                        </h2>

                        <div class="max-w-md mx-auto space-y-4">
                            @if ($allCod)
                            <label class="relative block cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" class="peer sr-only" checked>
                                
                                <div class="flex items-center justify-between p-4 border rounded-xl
                                            transition-all duration-200
                                            peer-checked:border-orange-600
                                            peer-checked:bg-orange-50
                                            hover:border-gray-400">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-400
                                                flex items-center justify-center
                                                peer-checked:border-orange-600">
                                    <div class="w-2.5 h-2.5 bg-orange-600 rounded-full 
                                                scale-0 peer-checked:scale-100 
                                                transition-transform duration-200"></div>
                                    </div>
                                    <div>
                                    <p class="font-semibold text-gray-800">Cash on Delivery</p>
                                    <p class="text-sm text-gray-500">Pay when your order arrives</p>
                                    </div>
                                </div>

                                <span class="text-sm font-medium text-gray-600">COD</span>
                                </div>
                            </label>
                            @endif

                            <!-- PAY NOW -->
                            <label class="relative block cursor-pointer">
                                <input type="radio" name="payment_method" value="pay_now" class="peer sr-only" @checked(!$allCod)>
                                
                                <div class="flex items-center justify-between p-4 border rounded-xl
                                            transition-all duration-200
                                            peer-checked:border-orange-600
                                            peer-checked:bg-orange-50
                                            hover:border-gray-400">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-gray-400
                                                flex items-center justify-center
                                                peer-checked:border-orange-600">
                                    <div class="w-2.5 h-2.5 bg-orange-600 rounded-full 
                                                scale-0 peer-checked:scale-100 
                                                transition-transform duration-200"></div>
                                    </div>
                                    <div>
                                    <p class="font-semibold text-gray-800">Pay Now</p>
                                    <p class="text-sm text-gray-500">bKash / Nagad / Bank</p>
                                    </div>
                                </div>

                                <span class="text-sm font-medium text-gray-600">Online</span>
                                </div>
                            </label>

                        </div>

                        <div class="space-y-3 mb-4">
                            {{--@foreach ($payment_gateways as $gateway)
                                <label
                                    class="group relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30 has-[:checked]:shadow-sm">
                                    <input name="payment" id="payment-{{ $gateway->slug }}" type="radio"
                                        value="{{ $gateway->slug }}"
                                        class="h-5 w-5 text-primary-600 border-gray-300 focus:ring-primary-500"
                                        {{ !$allCod && $gateway->is_default ? 'checked' : '' }}>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="block text-sm font-bold text-gray-900 group-has-[:checked]:text-primary-700">{{ $gateway->name }}</span>

                                            @if ($gateway->image)
                                                <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}"
                                                    class="h-8 w-auto object-contain">
                                            @else
                                                <i class="fa-solid fa-credit-card text-xl mb-1"></i>
                                            @endif
                                        </div>
                                        <span class="block text-xs text-gray-500 mt-0.5">Pay securely using your
                                            {{ $gateway->name }}
                                            wallet</span>
                                    </div>
                                </label>
                            @endforeach--}}
                            
                            @if ($allCod)
                                <label
                                    class="group relative flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50/30 has-[:checked]:shadow-sm">
                                    <input name="payment" type="radio" checked
                                        class="h-5 w-5 text-primary-600 border-gray-300 focus:ring-primary-500">
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="block text-sm font-bold text-gray-900 group-has-[:checked]:text-primary-700">Cash
                                                on Delivery</span>
                                            <div class="p-1.5 bg-gray-100 rounded text-gray-500">
                                                <i data-lucide="banknote" class="w-5 h-5"></i>
                                            </div>
                                        </div>
                                        <span class="block text-xs text-gray-500 mt-0.5">Pay only when you receive your
                                            order</span>
                                    </div>
                                </label>
                            @endif
                        </div>
                    </div>

                    <button id="continue-payment-btn" type="button" data-seller-id="{{ $selectedSellerId }}"
                        class="mt-6 w-full py-3 bg-primary-500 text-white text-sm font-semibold rounded-md hover:bg-primary-500/90 focus:ring-2 focus:ring-primary/40 transition">
                        Continue to Payment
                    </button>
                </div>

            </aside>
        </form>
    </div>

    @foreach ($billingAddresses as $address)
        <div id="editBillingAddressModal-{{ $address->id }}"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
            <!-- Modal Box -->
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Add New Address</h3>
                    <button onclick="toggleModal('editBillingAddressModal-{{ $address->id }}')"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <form id="addAddressForm" method="POST"
                        action="{{ route('billing_addresses.update', $address->id) }}">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="customer_name" value="{{ $address->customer_name }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                            <input type="text" name="customer_phone" value="{{ $address->customer_phone }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                            <select name="division_id" id="division_id_{{ $address->id }}"
                                class="division-select w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                                data-address-id="{{ $address->id }}">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        {{ old('division_id.' . $address->id, $address->division_id) == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="district_id" id="district_id_{{ $address->id }}"
                                class="district-select w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                <option value="">Select District</option>
                            </select>

                            <select name="type"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                <option value="{{ \App\Enums\AddressType::HOME->value }}"
                                    {{ old('type', $address->type->value) == \App\Enums\AddressType::HOME->value ? 'selected' : '' }}>
                                    {{ \App\Enums\AddressType::HOME->title() }}
                                </option>
                                <option value="{{ \App\Enums\AddressType::OFFICE->value }}"
                                    {{ old('type', $address->type->value) == \App\Enums\AddressType::OFFICE->value ? 'selected' : '' }}>
                                    {{ \App\Enums\AddressType::OFFICE->title() }}
                                </option>
                            </select>

                            <textarea name="address" placeholder="Address"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">{{ old('address', $address->address) }}</textarea>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_default_{{ $address->id }}" name="is_default" type="checkbox"
                                        value="1"
                                        class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-light-yellow focus:border-light-yellow"
                                        {{ old('is_default', $address->is_default) ? 'checked' : '' }} />
                                </div>
                                <label for="is_default_{{ $address->id }}"
                                    class="ms-2 text-sm font-medium text-gray-900">Mark as default</label>
                            </div>

                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" class="px-3 py-1 bg-gray-300 rounded"
                                onclick="toggleModal('editBillingAddressModal-{{ $address->id }}')">Cancel</button>
                            <button type="submit" class="px-3 py-1 bg-primary-500 text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @php
        $billingAddressesArray = $billingAddresses
            ->map(function ($address) {
                $districtId = old('district_id.' . $address->id, $address->district_id);
                return [
                    'id' => $address->id,
                    'division_id' => $address->division_id,
                    'district_id' => $districtId,
                ];
            })
            ->toArray();
    @endphp

    <div id="addBillingAddressModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <!-- Modal Box -->
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Add New Address</h3>
                <button onclick="toggleModal('addBillingAddressModal')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <form id="addAddressForm" method="POST" action="{{ route('billing_addresses.store') }}">
                    @csrf
                    <div class="space-y-3">
                        <input type="text" name="customer_name" placeholder="Full Name"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                        <input type="text" name="customer_phone" placeholder="Phone Number"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                        <select name="division_id" id="division_id"
                            class="division-select w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                            <option value="">Select Division</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="district_id" id="district_id"
                            class="district-select w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                            <option value="">Select District</option>
                        </select>

                        <select name="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                            <option value="{{ \App\Enums\AddressType::HOME->value }}">
                                {{ \App\Enums\AddressType::HOME->title() }}
                            </option>
                            <option value="{{ \App\Enums\AddressType::OFFICE->value }}">
                                {{ \App\Enums\AddressType::OFFICE->title() }}
                            </option>
                        </select>
                        <textarea name="address" placeholder="Address"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"></textarea>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" name="is_default" type="checkbox" value="1"
                                    {{ $billingAddresses->count() == 0 ? 'checked' : '' }}
                                    class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-light-yellow focus:border-light-yellow " />
                            </div>
                            <label for="remember" class="ms-2 text-sm font-medium text-gray-900">Mark as
                                default</label>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" class="px-3 py-1 bg-gray-300 rounded"
                            onclick="toggleModal('addBillingAddressModal')">Cancel</button>
                        <button type="submit" class="px-3 py-1 bg-primary-500 text-white rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            function loadDistricts(divisionId, selectedDistrictId, $districtSelect) {
                if (!$districtSelect.length) return;

                $districtSelect.html('<option value="">Loading...</option>');

                if (!divisionId) {
                    $districtSelect.html('<option value="">Select District</option>');
                    return;
                }

                $.get(`/get-districts/${divisionId}`, function(data) {
                    let options = '<option value="">Select District</option>';
                    $.each(data, function(id, name) {
                        options += `<option value="${id}">${name}</option>`;
                    });
                    $districtSelect.html(options);

                    if (selectedDistrictId) {
                        $districtSelect.val(selectedDistrictId);
                    }
                });
            }

            $('.division-select').on('change', function() {
                const divisionId = $(this).val();
                const addressId = $(this).data('address-id');
                const $district = addressId ? $(`#district_id_${addressId}`) : $('#district_id');
                loadDistricts(divisionId, null, $district);
            });

            $('.division-select').each(function() {
                const $this = $(this);
                const divisionId = $this.val();
                const addressId = $this.data('address-id');
                const $district = addressId ? $(`#district_id_${addressId}`) : $('#district_id');

                let selectedDistrictId = $district.data('selected-district') || null;

                if (typeof addressId !== 'undefined' && addressId) {
                    selectedDistrictId =
                        "{{ old('district_id.' . ($address->id ?? ''), $address->district_id ?? '') }}";
                } else {
                    selectedDistrictId = "{{ old('district_id', '') }}";
                }

                if (divisionId) {
                    loadDistricts(divisionId, selectedDistrictId, $district);
                }
            });

            const billingAddresses = @json($billingAddressesArray);

            billingAddresses.forEach(address => {
                const $division = $(`#division_id_${address.id}`);
                const $district = $(`#district_id_${address.id}`);

                if ($division.length && $district.length && address.division_id) {
                    loadDistricts(address.division_id, address.district_id, $district);
                }
            });

            $('#billingAddressAccordion').on('click', '.accordion-header', function(e) {
                e.preventDefault();
                $(this).next('.accordion-body').slideToggle();
                $(this).find('.caret').toggleClass('rotate-180');
            });

            $('#continue-payment-btn').click(function(e) {
                e.preventDefault();

                const $btn = $(this);
                const originalText = $btn.text();
                const sellerId = $btn.data('seller-id');

                $btn.attr('disabled', true)
                    .addClass('opacity-60 cursor-not-allowed')
                    .html(`
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span>Processing...</span>
                            </div>
                        `);

                let formData = $('#checkout-form').serializeArray();
                formData.push({
                    name: 'seller_id',
                    value: sellerId
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ route('orders.checkout') }}",
                    data: $.param(formData),
                    success: function(response) {
                        if (response.status === true) {
                            showSuccessToast(response.message);
                            if (response.payment_url !== '') {
                                window.location.href = response.payment_url;
                            }
                        } else {
                            window.location.href = "{{ route('home') }}";
                        }
                    },
                    error: function(xhr) {
                        $btn.html(originalText)
                            .attr('disabled', false)
                            .removeClass('opacity-60 cursor-not-allowed');
                        const message = xhr.responseJSON?.message || 'Payment failed. Please try again.';
                        showErrorToast(message);
                    }
                });
            });
        });
    </script>
@endpush
