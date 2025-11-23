@extends('frontend.layouts.app')
@section('title', 'Checkout')

@section('content')
    <div>
        <form id="checkout-form" class="container mx-auto grid gap-6 lg:grid-cols-3 text-gray-800">
            <!-- Left Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Billing Information Box -->
                <section class="bg-white rounded-lg border border-gray-100 p-6 shadow-sm space-y-5">
                    <!-- Compact Header inside -->
                    <header class="border-b border-gray-200 pb-4 mb-4">
                        <h1 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-lock text-primary"></i>
                            Secure Checkout
                        </h1>
                        <p class="text-sm text-gray-500">
                            Review your information and complete your purchase
                        </p>
                    </header>

                    <!-- Billing Info -->
                    <div class="flex justify-between items-center">
                        <h2 class="text-base font-semibold text-gray-800">
                            Billing Information
                        </h2>

                        <button type="button" data-modal-target="add-billing-address-modal"
                            data-modal-toggle="add-billing-address-modal"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-sm font-medium text-primary border border-primary/50 rounded-md hover:bg-primary/5 hover:border-primary transition-colors duration-150">
                            <i class="fa-solid fa-plus text-[13px]"></i>
                            <span>Add New Address</span>
                        </button>
                    </div>

                    @if ($billingAddresses->count() > 0)
                        <div class="max-h-[18rem] overflow-y-auto space-y-2 scrollbar-thin scrollbar-thumb-gray-300">
                            @foreach ($billingAddresses as $address)
                                <label
                                    class="flex items-start gap-3 p-3 border rounded-md cursor-pointer text-[13px] leading-snug hover:border-primary/70 hover:bg-primary/5 transition {{ $address->is_default ? 'border-primary bg-primary/5' : 'border-gray-200' }}">
                                    <input type="radio" name="billing_address_id" value="{{ $address->id }}"
                                        class="mt-1 w-4 h-4 text-primary focus:ring-primary"
                                        {{ $address->is_default ? 'checked' : '' }} />

                                    <div class="flex-1 space-y-0.5">
                                        <div class="font-medium text-gray-900 flex justify-between items-center">
                                            <span>
                                                {{ ucfirst($address->type == \App\Enums\AddressType::HOME->value ? 'Home' : 'Office') }}
                                                <span class="text-xs text-gray-500">
                                                    ({{ $address->division->name }},
                                                    {{ $address->district->name }})
                                                </span>
                                            </span>

                                            <button type="button"
                                                data-modal-target="edit-address-modal-{{ $address->id }}"
                                                data-modal-toggle="edit-address-modal-{{ $address->id }}"
                                                class="text-gray-400 hover:text-primary transition" title="Edit address">
                                                <i class="fa-solid fa-pen text-[13px]"></i>
                                            </button>
                                        </div>
                                        <p class="text-gray-600 truncate">{{ $address->address }}</p>
                                        <div class="flex gap-4 text-gray-500 text-xs">
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fa-solid fa-user text-[10px]"></i>
                                                {{ $address->customer_name }}
                                            </span>
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fa-solid fa-phone text-[10px]"></i>
                                                {{ $address->customer_phone }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <!-- Right Section (Order Summary + Payment) -->
            <aside class="space-y-5">
                <div class="bg-white rounded-lg border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-base font-semibold mb-4 text-gray-800">
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
                            <dt>VAT</dt>
                            <dd>+{{ money($vat_amount) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt>Shipping Fee</dt>
                            <dd>+{{ money($shipping_fee) }}</dd>
                        </div>
                    </dl>

                    <div class="border-t border-dashed my-3"></div>

                    <div class="flex justify-between items-center text-base font-semibold mb-2">
                        <span>Total</span>
                        <span class="text-lg text-primary">{{ money($total + $vat_amount + $shipping_fee) }}</span>
                    </div>

                    <!-- Payment Method -->
                    <div class="mt-5">
                        <h3 class="text-sm font-medium mb-2 text-gray-700">
                            Payment Method
                        </h3>

                        <ul class="grid w-full gap-4 sm:grid-cols-3">
                            @if ($allCod)
                                <li>
                                    <input type="radio" id="payment-cod" name="payment" value="cod" class="hidden peer"
                                        {{ $allCod ? 'checked' : '' }} />

                                    <label for="payment-cod"
                                        class="inline-flex flex-col items-center justify-center w-full p-4 text-gray-700
                            bg-white border border-gray-300 rounded-xl cursor-pointer transition
                            hover:bg-gray-50 peer-checked:bg-primary/10 peer-checked:border-primary
                            peer-checked:text-primary">

                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="fa-solid fa-box-open text-xl"></i>
                                            <span class="text-sm">Cash on Delivery</span>
                                        </div>
                                    </label>
                                </li>
                            @endif

                            @foreach ($payment_gateways as $gateway)
                                <li>
                                    <input type="radio" id="payment-{{ $gateway->slug }}" name="payment"
                                        value="{{ $gateway->slug }}" class="hidden peer"
                                        {{ !$allCod && $gateway->is_default ? 'checked' : '' }} />

                                    <label for="payment-{{ $gateway->slug }}"
                                        class="inline-flex flex-col items-center justify-center w-full p-4
                                    bg-white border border-gray-300 rounded-xl cursor-pointer transition
                                    hover:bg-gray-50 peer-checked:bg-primary/10 peer-checked:border-primary
                                    peer-checked:text-primary">

                                        @if ($gateway->image)
                                            <img src="{{ storage_url($gateway->image) }}"
                                                class="h-6 w-auto mb-1 object-contain" />
                                        @else
                                            <i class="fa-solid fa-credit-card text-xl mb-1"></i>
                                        @endif

                                        <span class="text-sm">
                                            {{ $gateway->name }}
                                        </span>
                                    </label>
                                </li>
                            @endforeach

                        </ul>
                    </div>

                    <button id="continue-payment-btn" type="button" data-seller-id="{{ $selectedSellerId }}"
                        class="mt-6 w-full py-3 bg-primary text-white text-sm font-semibold rounded-md hover:bg-primary/90 focus:ring-2 focus:ring-primary/40 transition">
                        Continue to Payment
                    </button>
                </div>

                <!-- Trust Section -->
                <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm text-xs text-gray-600">
                    <div class="flex items-center gap-2 font-medium mb-2">
                        <i class="fa-solid fa-shield-halved text-green-600"></i>
                        Secure Payment
                    </div>
                    <p class="text-gray-500 mb-4 leading-relaxed">
                        Your transactions are protected with PCI‑DSS encryption and trusted global
                        gateways.
                    </p>
                    <div class="flex items-center justify-between gap-6 border-t border-gray-100 pt-4">
                        @foreach (payment_options() as $option)
                            <img src="{{ storage_url($option->image) }}" alt="{{ $option->name }}"
                                class="h-8 sm:h-10 object-contain opacity-90 hover:opacity-100 transition duration-300"
                                loading="lazy" />
                        @endforeach
                    </div>
                </div>
            </aside>
        </form>
    </div>

    <div id="add-billing-address-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm ">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Add New Address</h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                        data-modal-hide="add-billing-address-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!--Add Billing Address Modal -->
                <div class="p-4 md:p-5">
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
                                    {{ \App\Enums\AddressType::HOME->title() }}
                                </option>
                            </select>
                            <textarea name="address" placeholder="Address"
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"></textarea>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" name="is_default" type="checkbox" value="1"
                                        class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-light-yellow focus:border-light-yellow "
                                        required />
                                </div>
                                <label for="remember" class="ms-2 text-sm font-medium text-gray-900">Mark as
                                    default</label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" class="px-3 py-1 bg-gray-300 rounded"
                                data-modal-hide="addAddressModal">Cancel</button>
                            <button type="submit" class="px-3 py-1 bg-primary text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($billingAddresses as $address)
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
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
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
                                    <!-- JS will populate -->
                                </select>

                                <select name="type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                    <option value="{{ \App\Enums\AddressType::HOME->value }}"
                                        {{ old('type', $address->type) == \App\Enums\AddressType::HOME->value ? 'selected' : '' }}>
                                        {{ \App\Enums\AddressType::HOME->title() }}
                                    </option>
                                    <option value="{{ \App\Enums\AddressType::OFFICE->value }}"
                                        {{ old('type', $address->type) == \App\Enums\AddressType::OFFICE->value ? 'selected' : '' }}>
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
                                    data-modal-hide="edit-address-modal-{{ $address->id }}">Cancel</button>
                                <button type="submit" class="px-3 py-1 bg-primary text-white rounded">Save</button>
                            </div>
                        </form>
                    </div>
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

@endsection

@if (isset($oldDesign))
    <main class="cart-details-page pb-5 sm:pb-10">
        <!-- Checkout Main Section Starts -->
        <section class="checkout-section container  section-padding">
            <form id="checkout-form" class="block lg:grid gap-5 xl:gap-5 2xl:gap-20 lg:grid-cols-3">
                <!-- Billing information -->
                <div class="lg:col-span-2">
                    <div class="space-y-6 text-theme-dark">
                        <!-- Billing Information -->
                        <div class="space-y-4">
                            <!-- Title & Add Button in Same Row -->
                            <div class="flex items-center justify-between">
                                <h2 class="sm:text-lg font-semibold">Billing Information</h2>
                                <button data-modal-target="add-billing-address-modal" type="button"
                                    data-modal-toggle="add-billing-address-modal"
                                    class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/70 font-medium rounded-lg text-sm px-4 py-2">
                                    + Add Billing Address
                                </button>
                            </div>

                            @if ($billingAddresses->count() > 0)
                                <!-- Scrollable container with max 3 cards visible -->
                                <div class="overflow-y-auto px-2 space-y-3"
                                    style="max-height: calc(3 * 6.5rem + 1.5rem);">
                                    @foreach ($billingAddresses as $address)
                                        <label
                                            class="flex items-start gap-3 py-4 px-6 border rounded cursor-pointer hover:border-primary transition
                                                {{ $address->is_default == 1 ? 'border-primary bg-primary/5' : 'border-gray-300' }}">

                                            <input type="radio" name="billing_address_id"
                                                value="{{ $address->id }}"
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
                                                <button type="button"
                                                    data-modal-target="edit-address-modal-{{ $address->id }}"
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

                        <!-- Payment Options -->
                        <div class="flex flex-col gap-y-4 !my-6 md:!my-10 border border-jet-gray/30 py-5">
                            <h3 class="sm:text-lg font-medium pl-5">Payment Option</h3>
                            <div
                                class="grid grid-cols-2 xsm:grid-cols-3 sm:grid-cols-4 md:grid-cols-5 items-center gap-2 md:gap-4 p-3 md:p-5 border-t border-jet-gray/30 md:divide-x md:divide-jet-gray/30">
                                @foreach ($payment_gateways as $gateway)
                                    <label
                                        class="relative inline-flex flex-col gap-2 items-center p-2 cursor-pointer group">
                                        @if ($gateway->image)
                                            <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}"
                                                class="h-6 sm:h-7 w-auto" />
                                        @else
                                            <span class="text-2xl sm:text-3xl text-primary">
                                                <i class="fa-solid fa-credit-card"></i>
                                            </span>
                                        @endif
                                        <input type="radio" name="payment" value="{{ $gateway->slug }}"
                                            class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary focus:ring-2"
                                            {{ $gateway->is_default ? 'checked' : '' }} />
                                    </label>
                                @endforeach
                            </div>

                            <!-- Card Details -->
                            {{-- <div class="flex flex-col gap-y-4 md:p-5 p-4">
                                <div class="space-y-2">
                                    <label class="block text-sm" for="name-on-card">Name on Card</label>
                                    <input id="name-on-card" type="text"
                                        class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm" for="card-number">Card Number</label>
                                    <input type="text" id="card-number"
                                        class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm" for="expire-date">Expire Date</label>
                                        <input type="text" id="expire-date" placeholder="DD/YY"
                                            class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm" for="cvc">CVC</label>
                                        <input type="text" id="cvc"
                                            class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <!-- Additional Information -->
                        {{-- <div class="space-y-4">
                            <h3 class="sm:text-lg font-medium">Additional Information</h3>
                            <div class="space-y-2">
                                <label class="block text-sm" for="order-notes">Order Notes
                                    <span class="text-jet-gray">(Optional)</span></label>
                                <textarea id="order-notes" placeholder="Notes about your order, e.g. special notes for delivery"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow h-24 text-sm md:text-base"></textarea>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <!-- Continue Payment -->
                <div class="lg:col-span-1">
                    <input type="hidden" name="seller_id" id="seller_id" value="{{ $selectedSellerId }}">
                    <!-- Security Info -->
                    <div class="space-y-2">
                        <h2 class="mb-4 font-semibold lg:text-xl md:text-lg">
                            Order Summary
                        </h2>
                        <div class="order-summary">
                            <!-- summary -->
                            <div class="space-y-2 item-info">
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Subtotal:</span>
                                    <span id="itemsTotal" class="text-jet-gray mr-2">{{ money($sub_total) }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Item Discount:</span>
                                    <span id="itemDiscount" class="text-primary">-{{ money($discount) }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">TAX:</span>
                                    <span id="itemDiscount" class="text-jet-gray">+{{ money($vat_amount) }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Shipping Fee:</span>
                                    <span id="itemDiscount" class="text-jet-gray">+{{ money($shipping_fee) }}</span>
                                </p>
                            </div>
                            <!-- estimated total -->
                            <div
                                class="flex justify-between pt-3 mt-6 font-medium border-t-2 border-dashed total border-jet-gray/50">
                                <span>Estimated Total</span>
                                <span id="estimatedTotal"
                                    class="text-xl">{{ money($total + $vat_amount + $shipping_fee) }}</span>
                            </div>
                        </div>

                        <!-- checkout btn -->
                        <button id="continue-payment-btn" type="button" data-seller-id="{{ $selectedSellerId }}"
                            class="eq 2xl:text-2xl lg:text-xl text-lg w-full flex flex-col items-center bg-primary hover:bg-theme-dark text-white sm:py-4 py-3 rounded-full font-medium">
                            Continue Payment
                        </button>
                        <div class="text-davy-gray p-3 sm:p-4 protect-card-info">
                            <h2 class="text-xs sm:text-sm font-medium flex items-center gap-2">
                                <!-- custom shield icon -->
                                <svg width="22" height="26" class="text-leaf-green w-6 h-6 sm:w-8 sm:h-8"
                                    viewBox="0 0 22 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M9.82462 0.150834C10.2917 -0.0243682 10.8022 -0.0475726 11.2833 0.0845311L11.4869 0.150834L19.7747 3.25877C20.1948 3.41626 20.5615 3.68983 20.8321 4.04763C21.1027 4.40543 21.2661 4.83275 21.3033 5.27981L21.3115 5.47635V11.826C21.3113 13.7443 20.7932 15.627 19.8119 17.2754C18.8306 18.9237 17.4225 20.2766 15.7362 21.1912L15.4213 21.357L11.4502 23.3413C11.2317 23.4507 10.9929 23.5138 10.7488 23.5266C10.5047 23.5394 10.2607 23.5018 10.0318 23.4159L9.86133 23.3425L5.89027 21.3558C4.17423 20.4978 2.72169 19.1923 1.68598 17.5773C0.650278 15.9623 0.0698315 14.0976 0.00592001 12.18L0 11.8248V5.47754C6.88487e-06 5.02896 0.127427 4.58962 0.367427 4.21065C0.607427 3.83168 0.950134 3.52867 1.35565 3.33691L1.5368 3.25995L9.82462 0.150834ZM9.64111 7.20377L7.28381 11.1322C7.17483 11.3137 7.11597 11.5208 7.11325 11.7325C7.11052 11.9441 7.16402 12.1527 7.26828 12.3369C7.37255 12.5211 7.52384 12.6743 7.70671 12.781C7.88958 12.8876 8.09746 12.9437 8.30913 12.9437H10.9328L9.64111 15.0973C9.49026 15.3659 9.45006 15.6828 9.52903 15.9805C9.60799 16.2783 9.79991 16.5336 10.064 16.6921C10.3282 16.8507 10.6437 16.9001 10.9436 16.8298C11.2436 16.7595 11.5043 16.5751 11.6704 16.3156L14.0277 12.3872C14.1367 12.2057 14.1956 11.9986 14.1983 11.787C14.201 11.5753 14.1475 11.3667 14.0433 11.1825C13.939 10.9983 13.7877 10.8451 13.6048 10.7385C13.422 10.6319 13.2141 10.5757 13.0024 10.5757H10.3787L11.6716 8.42208C11.8332 8.15266 11.8811 7.8301 11.8048 7.52535C11.7286 7.2206 11.5343 6.95863 11.2649 6.79707C10.9955 6.63552 10.6729 6.5876 10.3682 6.66387C10.0634 6.74014 9.80266 6.93435 9.64111 7.20377Z"
                                        fill="currentColor" />
                                </svg>
                                <span> Protect Your Card Information</span>
                            </h2>
                            <!-- protect card info -->
                            <ul class="pl-2 text-xs list-disc list-inside flex flex-col gap-2 mt-2 sm:mt-3">
                                <li class="inline-flex items-start gap-2">
                                    <i class="fa-solid fa-check text-base sm:text-lg text-leaf-green"></i>
                                    <span>Temu follows the Payment Card Industry Data Security
                                        Standard (PCI DSS) when handling card data</span>
                                </li>
                                <li class="inline-flex items-start gap-2">
                                    <i class="fa-solid fa-check text-base sm:text-lg text-leaf-green"></i>
                                    <span>Card information is secure and uncompromised</span>
                                </li>
                                <li class="inline-flex items-start gap-2">
                                    <i class="fa-solid fa-check text-base sm:text-lg text-leaf-green"></i>
                                    <span> All data is encrypted</span>
                                </li>
                                <li class="inline-flex items-start gap-2">
                                    <i class="fa-solid fa-check text-base sm:text-lg text-leaf-green"></i>
                                    <span>Temu never sells your card information</span>
                                </li>
                            </ul>
                            <!-- payment options -->
                            <div class="flex flex-wrap gap-x-2 gap-y-1 mt-5">
                                @foreach (payment_options() as $option)
                                    <img src="{{ storage_url($option->image) }}" alt="{{ $option->name }}"
                                        class="w-auto h-8 sm:h-10" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Add Billing Address Modal modal -->
            <div id="add-billing-address-modal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm ">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Add New Address</h3>
                            <button type="button"
                                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                                data-modal-hide="add-billing-address-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!--Add Billing Address Modal -->
                        <div class="p-4 md:p-5">
                            <form id="addAddressForm" method="POST"
                                action="{{ route('billing_addresses.store') }}">
                                @csrf
                                <div class="space-y-3">
                                    <input type="text" name="customer_name" placeholder="Full Name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                    <input type="text" name="customer_phone" placeholder="Phone Number"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                    <select name="division_id" id="division_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                        <option value="">Select Division</option>
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->id }}">{{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="district_id" id="district_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                        <option value="">Select District</option>
                                    </select>

                                    <select name="type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                        <option value="{{ \App\Enums\AddressType::HOME->value }}">
                                            {{ \App\Enums\AddressType::HOME->title() }}
                                        </option>
                                        <option value="{{ \App\Enums\AddressType::OFFICE->value }}">
                                            {{ \App\Enums\AddressType::HOME->title() }}
                                        </option>
                                    </select>
                                    <textarea name="address" placeholder="Address"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"></textarea>
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="remember" name="is_default" type="checkbox" value="1"
                                                class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-light-yellow focus:border-light-yellow "
                                                required />
                                        </div>
                                        <label for="remember" class="ms-2 text-sm font-medium text-gray-900">Mark as
                                            default</label>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="button" class="px-3 py-1 bg-gray-300 rounded"
                                        data-modal-hide="addAddressModal">Cancel</button>
                                    <button type="submit"
                                        class="px-3 py-1 bg-primary text-white rounded">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
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
                                                {{ \App\Enums\AddressType::HOME->title() }}
                                            </option>
                                            <option value="{{ \App\Enums\AddressType::OFFICE->value }}"
                                                {{ old('type', $address->type) == \App\Enums\AddressType::OFFICE->value ? 'selected' : '' }}>
                                                {{ \App\Enums\AddressType::OFFICE->title() }}
                                            </option>
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

        </section>
        <!-- Checkout Main Section Ended -->
    </main>
@endisset

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

                if (addressId) {
                    selectedDistrictId =
                        '{{ old("district_id.' . $address->id . '", $address->district_id ?? '') }}';
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
                            toastr.success(response.message);
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
                        toastr.error(xhr.message);

                    }
                });
            });
        });
    </script>
@endpush
