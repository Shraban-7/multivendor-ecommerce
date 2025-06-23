@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
    <main class="cart-details-page pb-5 sm:pb-10">
        <!-- Promotional Header Starts -->
        <section>
            <a href="#" class="block promo-header bg-light-yellow text-white py-3 sm:py-4">
                <div class="container flex flex-wrap justify-center xsm:justify-start items-center gap-x-2">
                    <i class="fa-solid fa-truck-fast text-lg"></i>
                    <h3 class="text-sm">Free Shipping Special For You</h3>
                    <p class="text-xs ml-2 xsm:ml-3">Limited Offer</p>
                </div>
            </a>
        </section>
        <!-- Promotional Header Ended -->

        <!-- Page Breadcrumb -->
        <section class="page-breadcrumb-links bg-jet-gray/10 py-4 md:py-6">
            <nav class="flex container" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            Shopping Cart
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-butterfly-blue md:ms-2">Checkout</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Checkout Main Section Starts -->
        <section class="checkout-section container section-padding">
            <form id="checkout-form" class="block lg:grid gap-5 xl:gap-10 2xl:gap-20 lg:grid-cols-3">
                <!-- Billing information -->
                <div class="lg:col-span-2">
                    <div class="space-y-6 text-theme-dark">
                        <!-- Billing Information -->
                        <div class="space-y-4">
                            <h2 class="sm:text-lg font-semibold">Billing Information</h2>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <!-- Customer Name -->
                                <div class="space-y-2">
                                    <label for="customer-name" class="block text-sm">Customer Name</label>
                                    <input type="text" id="customer-name" value="{{ auth()->user()->name }}" name="customer_name"
                                        placeholder="Enter customer name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                </div>

                                <!-- Customer Email -->
                                <div class="space-y-2">
                                    <label for="customer-email" class="block text-sm">Customer Email</label>
                                    <input type="email" id="customer-email" value="{{ auth()->user()->email }}"  name="customer_email"
                                        placeholder="customer@example.com"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                </div>

                                <!-- Customer Phone -->
                                <div class="space-y-2">
                                    <label for="customer-phone" class="block text-sm">Customer Phone</label>
                                    <input type="text" id="customer-phone" value="{{ auth()->user()->phone }}"  name="customer_phone"
                                        placeholder="+88012364899"
                                        class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex space-x-4">
                                <div class="w-1/4 space-y-2">
                                    <label class="block text-sm" for="type">Type</label>
                                    <select id="type" required name="type"
                                        class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base">
                                        <option value="home">Home</option>
                                        <option value="office">Office</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="w-3/4 space-y-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input required list="addressList" id="address" name="address"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-light-yellow focus:border-light-yellow transition" />
                                    <datalist id="addressList">
                                        @foreach ($customer_addresses as $customer_address)
                                            <option value="{{ $customer_address->address }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <input type="hidden" name="seller_id" id="" value="{{ $selectedSellerId }}">
                            </div>

                            <!-- Location Details -->
                            {{-- <div class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm" for="country">Country</label>
                                    <select id="country"
                                        class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                        <option>Select...</option>
                                        <option value="BD">Bangladesh</option>
                                        <option value="IN">India</option>
                                        <option value="PK">Pakistan</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm" for="region/state">Region/State</label>
                                    <select id="region/state"
                                        class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                        <option>Select...</option>
                                        <option value="DH">Dhaka</option>
                                        <option value="WB">West Bengal</option>
                                        <option value="IS">Islamabad</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm" for="city">City</label>
                                    <select id="city"
                                        class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                        <option>Select...</option>
                                        <option value="DHA">Dhaka</option>
                                        <option value="CTH">Chittagong</option>
                                        <option value="BAR">Bartishal</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm" for="zip-code">Zip Code</label>
                                    <input type="text" id="zip-code"
                                        class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                </div>
                            </div> --}}

                            <!-- Different Address Checkbox -->
                            <div class="flex items-center">
                                <input id="diff-addr" type="checkbox"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
                                <label for="diff-addr" class="ml-2 text-sm">Ship into different address</label>
                            </div>
                        </div>

                        <!-- Payment Options -->
                        <div class="flex flex-col gap-y-4 !my-6 md:!my-10 border border-jet-gray/30 py-5">
                            <h3 class="sm:text-lg font-medium pl-5">Payment Option</h3>
                            <div
                                class="grid grid-cols-2 xsm:grid-cols-3 sm:grid-cols-4 md:grid-cols-5 items-center gap-2 md:gap-4 p-3 md:p-5 border-y border-jet-gray/30 md:divide-x md:divide-jet-gray/30">
                                <label class="relative inline-flex flex-col gap-2 items-center p-2 cursor-pointer group">
                                    <span class="text-2xl sm:text-3xl text-primary"><i
                                            class="fa-solid fa-dollar-sign"></i></span>
                                    <span class="font-medium text-sm whitespace-nowrap group-hover:text-primary eq">Cash on
                                        Delivery</span>
                                    <input type="radio" name="payment"
                                        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary focus:ring-2" />
                                </label>
                                <label class="relative inline-flex flex-col gap-2 items-center p-2 cursor-pointer group">
                                    <img src="{{ asset('assets/frontend/images/payment-option-1.png') }}"
                                        alt="Venmo Payment" class="h-6 sm:h-7 w-auto" />
                                    <span
                                        class="font-medium text-sm whitespace-nowrap group-hover:text-primary eq">Venmo</span>
                                    <input type="radio" name="payment"
                                        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary focus:ring-2" />
                                </label>
                                <label class="relative inline-flex flex-col gap-2 items-center p-2 cursor-pointer group">
                                    <img src="{{ asset('assets/frontend/images/payment-option-2.png') }}"
                                        alt="PayPal Payment" class="h-6 sm:h-7 w-auto" />
                                    <span
                                        class="font-medium text-sm whitespace-nowrap group-hover:text-primary eq">Paypal</span>
                                    <input type="radio" name="payment"
                                        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary focus:ring-2" />
                                </label>
                                <label class="relative inline-flex flex-col gap-2 items-center p-2 cursor-pointer group">
                                    <img src="{{ asset('assets/frontend/images/payment-option-3.png') }}"
                                        alt="Amazon Payment" class="h-6 sm:h-7 w-auto" />
                                    <span class="font-medium text-sm whitespace-nowrap group-hover:text-primary eq">Amazon
                                        Pay</span>
                                    <input type="radio" name="payment"
                                        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary focus:ring-2" />
                                </label>
                                <label class="relative inline-flex flex-col gap-2 items-center p-2 cursor-pointer group">
                                    <span class="text-2xl sm:text-3xl text-primary"><i
                                            class="fa-regular fa-credit-card"></i></span>
                                    <span
                                        class="font-medium text-sm whitespace-nowrap group-hover:text-primary eq">Debit/Credit
                                        Card</span>
                                    <input type="radio" name="payment"
                                        class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary focus:ring-2"
                                        checked />
                                </label>
                            </div>

                            <!-- Card Details -->
                            <div class="flex flex-col gap-y-4 md:p-5 p-4">
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
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="space-y-4">
                            <h3 class="sm:text-lg font-medium">Additional Information</h3>
                            <div class="space-y-2">
                                <label class="block text-sm" for="order-notes">Order Notes
                                    <span class="text-jet-gray">(Optional)</span></label>
                                <textarea id="order-notes" placeholder="Notes about your order, e.g. special notes for delivery"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow h-24 text-sm md:text-base"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Continue Payment -->
                <div class="lg:col-span-1">
                    <!-- Security Info -->
                    <div class="space-y-2">
                        <h2 class="mb-4 font-semibold lg:text-xl md:text-lg">
                            Order Summary
                        </h2>
                        <div class="order-summary">
                            <!-- summary -->
                            <div class="space-y-2 item-info">
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Item's total:</span>
                                    <span id="itemsTotal"
                                        class="text-jet-gray mr-2">{{ money($total + $discount) }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Item Discount:</span>
                                    <span id="itemDiscount" class="font-bold text-primary">-{{ money($discount) }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">TAX:</span>
                                    <span id="itemDiscount" class="font-bold text-jet-gray">+{{ money($tax) }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-theme-dark">Shipping Fee:</span>
                                    <span id="itemDiscount"
                                        class="font-bold text-jet-gray">+{{ money($shipping_fee) }}</span>
                                </p>
                            </div>
                            <!-- estimated total -->
                            <div
                                class="flex justify-between pt-3 mt-6 font-medium border-t-2 border-dashed total border-jet-gray/50">
                                <span>Estimated Total</span>
                                <span id="estimatedTotal"
                                    class="text-xl">{{ money($total + $tax + $shipping_fee) }}</span>
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
                                @foreach (payment_gateways() as $gateway)
                                    <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}"
                                        class="w-auto h-8 sm:h-10" />
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <!-- Checkout Main Section Ended -->
    </main>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#continue-payment-btn').click(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('orders.checkout') }}",
                        data: $('#checkout-form').serialize(),
                        success: function(response) {
                            toastr.success(response.message);
                            window.location.href = "{{ route('orders.success', ':orderId') }}"
                                .replace(':orderId', response.order.id);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
