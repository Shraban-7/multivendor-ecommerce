@extends('frontend.layouts.app')
@section('title', 'Profile')

@section('content')
    @php
        $user = auth()->user();
    @endphp
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
                            User
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-butterfly-blue md:ms-2">Account Setting</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Account Settings Main Section Starts -->
        <section class="checkout-section container section-padding">
            <div class="block lg:grid lg:grid-cols-4">
                <div class="lg:col-span-3">
                    <div class="space-y-5 md:space-y-8 text-theme-dark">
                        <!--  Account Settings -->
                        <div class="space-y-4 border border-jet-gray/30 rounded md:pb-4 pb-3">
                            <h2 class="sm:text-base text-sm font-medium border-b border-jet-gray/30 px-3 py-1.5 md:px-5 md:py-3 uppercase">
                                Account Settings
                            </h2>

                            <form spellcheck="false" action="{{ route('accountUpdate') }}" method="POST"
                                enctype="multipart/form-data"
                                class="flex sm:flex-cols flex-wrap sm:flex-row gap-3 md:gap-5 px-3 py-1.5 md:px-5 md:py-2">
                                @csrf
                                <!-- Display image -->
                                <div
                                    class="display-image w-20 h-20 xsm:w-32 xsm:h-32 md:w-36 md:h-36 xl:w-40 xl:h-40 rounded-full overflow-hidden border border-jet-gray/30 relative group/avater">
                                    @if ($user->image)
                                        <img id="preview-image" src="{{ asset('storage/' . $user->image) }}"
                                            alt="User Avatar" class="object-cover w-full h-full" />
                                    @else
                                        <img id="preview-image" src="{{ asset('assets/frontend/images/user-avater.png') }}"
                                            alt="User Avatar" class="object-cover w-full h-full" />
                                    @endif

                                    <label for="dropzone-file"
                                        class="group-hover/avater:opacity-90 opacity-0 absolute flex flex-col items-center justify-center p-4 top-0 left-0 w-full h-full border-2 border-jet-gray/40 border-dashed rounded-full cursor-pointer bg-gray-100 text-center eq">
                                        <svg class="size-7 xsm:size-8 mb-2 text-davy-gray" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="hidden xsm:block text-xs text-davy-gray">
                                            <span class="font-semibold">Click to upload</span> or
                                            drag and drop
                                        </p>

                                        <input id="dropzone-file" type="file" name="image" class="hidden"
                                            accept="image/*" />
                                    </label>
                                </div>

                                <!-- account setting inputs -->
                                <div class="flex-1 space-y-3 sm:space-y-5">
                                    <!-- display name & username -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4">
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label for="username" class="block text-sm">Username</label>
                                            <input required type="text" id="username" name="username"
                                                value="{{ old('username', $user->username) }}"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
                                    </div>

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
                                            <label for="secondary-email" class="block text-sm">Secondary Email</label>
                                            <input type="email" id="secondary-email" name="secondary_email"
                                                value="{{ old('secondary_email', $user->secondary_email) }}"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                            <!-- billing -->
                            <div class="billing-address space-y-4 border border-jet-gray/30 rounded md:pb-4 pb-3">
                                <h2
                                    class="sm:text-base text-sm font-medium border-b border-jet-gray/30 px-3 py-1.5 md:px-5 md:py-3 uppercase">
                                    Billing Address
                                </h2>

                                <!-- billing address form -->
                                <form spellcheck="false"
                                    class="flex sm:flex-cols flex-wrap sm:flex-row gap-y-3 sm:gap-y-5 px-3 py-1.5 md:px-5 md:py-2">
                                    <!-- first name & last name -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4 w-full">
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label for="bill-addr-first-name" class="block text-sm">First Name</label>
                                            <input required type="text" id="bill-addr-first-name" value="Kevin"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label for="bill-addr-last-name" class="block text-sm">Last Name</label>
                                            <input required type="text" id="bill-addr-last-name" value="Gilbert"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
                                    </div>

                                    <!-- company Name -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label for="bill-addr-company-name" class="block text-sm">Company Name
                                            <span class="text-jet-gray">(Optional)</span></label>
                                        <input type="text" id="bill-addr-company-name"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>

                                    <!-- address -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="bill-addr-address">Address</label>
                                        <input required type="text" id="bill-addr-address"
                                            value="Road No. 13/x, House no. 1320/C, Flat No. 5D"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>

                                    <!-- country -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="bill-addr-country">Country</label>
                                        <select required id="bill-addr-country"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                            <option>Select...</option>
                                            <option value="BD" selected>Bangladesh</option>
                                            <option value="IN">India</option>
                                            <option value="PK">Pakistan</option>
                                        </select>
                                    </div>

                                    <!-- region state -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="bill-addr-region-state">Region/State</label>
                                        <select required id="bill-addr-region-state"
                                            class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                            <option>Select...</option>
                                            <option value="DH" selected>Dhaka</option>
                                            <option value="WB">West Bengal</option>
                                            <option value="IS">Islamabad</option>
                                        </select>
                                    </div>

                                    <!-- city & zip code -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4 w-full">
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label class="block text-sm" for="bill-addr-city">City</label>
                                            <select required id="bill-addr-city"
                                                class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                                <option>Select...</option>
                                                <option value="DHA" selected>Dhaka</option>
                                                <option value="CTH">Chittagong</option>
                                                <option value="BAR">Bartishal</option>
                                            </select>
                                        </div>
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label for="bill-addr-zip-code" class="block text-sm">Zip Code</label>
                                            <input required type="text" id="bill-addr-zip-code" value="1207"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
                                    </div>

                                    <!-- email -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="bill-addr-email">Email</label>
                                        <input required type="email" id="bill-addr-email" value="kevin12345@gmail.com"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>

                                    <!-- phone number -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="bill-addr-phone-number">Phone Number</label>
                                        <input required type="tell" id="bill-addr-phone-number"
                                            value="+1-202-555-0118"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base outline-light-yellow" />
                                    </div>

                                    <button type="submit"
                                        class="bg-primary text-white px-5 py-2 border-2 border-transparent rounded active:ring-[1] active:ring-light-yellow active:border-light-yellow text-xs md:text-sm uppercase font-bold mt-1 md:mt-2 hover:bg-theme-dark eq">
                                        save changes
                                    </button>
                                </form>
                            </div>

                            <!-- shipping address -->
                            <div class="shipping-address space-y-4 border border-jet-gray/30 rounded md:pb-4 pb-3">
                                <h2
                                    class="sm:text-base text-sm font-medium border-b border-jet-gray/30 px-3 py-1.5 md:px-5 md:py-3 uppercase">
                                    Shipping Address
                                </h2>

                                <!-- shipping address form -->
                                <form spellcheck="false"
                                    class="flex sm:flex-cols flex-wrap sm:flex-row gap-y-3 sm:gap-y-5 px-3 py-1.5 md:px-5 md:py-2">
                                    <!-- first name & last name -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4 w-full">
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label for="ship-addr-first-name" class="block text-sm">First Name</label>
                                            <input required type="text" id="ship-addr-first-name" value="Kevin"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label for="ship-addr-last-name" class="block text-sm">Last Name</label>
                                            <input required type="text" id="ship-addr-last-name" value="Gilbert"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
                                    </div>

                                    <!-- company Name -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label for="ship-addr-company-name" class="block text-sm">Company Name
                                            <span class="text-jet-gray">(Optional)</span></label>
                                        <input type="text" id="ship-addr-company-name"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>

                                    <!-- address -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="ship-addr-address">Address</label>
                                        <input required type="text" id="ship-addr-address"
                                            value="Road No. 13/x, House no. 1320/C, Flat No. 5D"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>

                                    <!-- country -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="ship-addr-country">Country</label>
                                        <select required id="ship-addr-country"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                            <option>Select...</option>
                                            <option value="BD" selected>Bangladesh</option>
                                            <option value="IN">India</option>
                                            <option value="PK">Pakistan</option>
                                        </select>
                                    </div>

                                    <!-- region state -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="ship-addr-region-state">Region/State</label>
                                        <select required id="ship-addr-region-state"
                                            class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                            <option>Select...</option>
                                            <option value="DH" selected>Dhaka</option>
                                            <option value="WB">West Bengal</option>
                                            <option value="IS">Islamabad</option>
                                        </select>
                                    </div>

                                    <!-- city & zip code -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-4 w-full">
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label class="block text-sm" for="ship-addr-city">City</label>
                                            <select required id="ship-addr-city"
                                                class="eq w-full px-4 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base text-jet-gray">
                                                <option>Select...</option>
                                                <option value="DHA" selected>Dhaka</option>
                                                <option value="CTH">Chittagong</option>
                                                <option value="BAR">Bartishal</option>
                                            </select>
                                        </div>
                                        <div class="from-ctrl space-y-1 sm:space-y-2">
                                            <label for="ship-addr-zip-code" class="block text-sm">Zip Code</label>
                                            <input required type="text" id="ship-addr-zip-code" value="1207"
                                                class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                        </div>
                                    </div>

                                    <!-- email -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="ship-addr-email">Email</label>
                                        <input required type="email" id="ship-addr-email" value="kevin12345@gmail.com"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                                    </div>

                                    <!-- phone number -->
                                    <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                        <label class="block text-sm" for="ship-addr-phone-number">Phone Number</label>
                                        <input required type="tell" id="ship-addr-phone-number"
                                            value="+1-202-555-0118"
                                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base outline-light-yellow" />
                                    </div>

                                    <button type="submit"
                                        class="bg-primary text-white px-5 py-2 border-2 border-transparent rounded active:ring-[1] active:ring-light-yellow active:border-light-yellow text-xs md:text-sm uppercase font-bold mt-1 md:mt-2 hover:bg-theme-dark eq">
                                        save changes
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="change-password space-y-4 border border-jet-gray/30 rounded md:pb-4 pb-3">
                            <h2 class="sm:text-base text-sm font-medium border-b border-jet-gray/30 px-3 py-1.5 md:px-5 md:py-3 uppercase">
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
                                        <button type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                                            onclick="togglePassword('current-password', this)">
                                            <i class="fa-solid fa-eye"></i>
                                            <i class="fa-solid fa-eye-slash hidden"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                    <label class="block text-sm" for="new-password">New Password</label>
                                    <div class="relative">
                                        <input type="password" id="new-password"
                                            class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                                            placeholder="8+ characters" name="password" />
                                        <button type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                                            onclick="togglePassword('new-password', this)">
                                            <i class="fa-solid fa-eye"></i>
                                            <i class="fa-solid fa-eye-slash hidden"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="from-ctrl space-y-1 sm:space-y-2 w-full">
                                    <label class="block text-sm" for="confirm-password">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password"
                                            class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                                            id="confirm-password" name="password_confirmation" />
                                        <button type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                                            onclick="togglePassword('confirm-password', this)">
                                            <i class="fa-solid fa-eye"></i>
                                            <i class="fa-solid fa-eye-slash hidden"></i>
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
                </div>
            </div>
        </section>
        <!-- Account Settings Main Section Ended -->
    </main>

    @push('scripts')
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
