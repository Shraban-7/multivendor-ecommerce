@extends('frontend.layouts.app')

@section('title', 'Track Order | Account')

@section('content')
    <main class="tracking-page pb-5 sm:pb-10">

        <!-- Tracking Main Section Starts -->
        <section class="tracking-section container section-padding">
            <div class="max-w-4xl md:w-4/6 sm:w-8/12 xsm:w-10/12 mx-auto text-theme-dark">
                <!-- Order Header -->
                <div class="mb-6 sm:mb-8 space-y-5">
                    <div
                        class="p-4 sm:p-6 bg-light-yellow/10 rounded border border-light-yellow flex flex-wrap gap-y-2 justify-between items-center">
                        <div class="space-y-2">
                            <h1 class="text-lg sm:text-xl">#{{ $order->invoice_id }}</h1>
                            <p class="text-xs xsm:text-sm text-davy-gray">
                                {{ $order->items_count }} Products · Order Placed in
                                {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y \a\t h:i A') }}
                            </p>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-semibold text-light-yellow">
                            {{ money($order->sub_total) }}
                        </h2>
                    </div>
                    <p class="text-sm text-davy-gray">
                        Order expected arrival
                        <span
                            class="text-theme-dark">{{ \Carbon\Carbon::parse($order->estimated_arrival)->format('F j, Y') }}</span>
                    </p>
                </div>

                <!-- Progress Tracker -->
                <div class="progress-container px-3 sm:px-5">
                    <div class="flex items-center justify-between relative mx-7 -mb-2">
                        @if ($order->delivery_status == \App\Enums\OrderStatus::SHIPPED->value)
                            <div class="progress-line active"></div>
                        @else
                            <div class="progress-line"></div>
                        @endif
                        
                        @if ($order->delivery_status == \App\Enums\OrderStatus::DELIVERED->value)
                            <div class="progress-line active"></div>
                        @else
                            <div class="progress-line"></div>
                        @endif
                    </div>
                    <div class="steps relative flex justify-between">
                        <!-- Order Placed -->
                        <div class="step order-placed relative z-[1] completed">
                            <div class="step-dot flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full">
                                <span>
                                    <i class="fa-solid fa-check text-white text-xs sm:text-sm"></i>
                                </span>
                            </div>
                            <div class="step-content flex items-center flex-col gap-y-2 sm:gap-y-3 mt-4 sm:mt-6">
                                <!-- order icon -->
                                <svg class="step-icon text-violet-700 w-6 h-6 sm:w-8 sm:h-8" width="32" height="32"
                                    viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.2"
                                        d="M10 27H6C5.73478 27 5.48043 26.8946 5.29289 26.7071C5.10536 26.5196 5 26.2652 5 26V6C5 5.73478 5.10536 5.48043 5.29289 5.29289C5.48043 5.10536 5.73478 5 6 5H10V27Z"
                                        fill="currentColor" />
                                    <path d="M14 14H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14 18H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M26 5H6C5.44772 5 5 5.44772 5 6V26C5 26.5523 5.44772 27 6 27H26C26.5523 27 27 26.5523 27 26V6C27 5.44772 26.5523 5 26 5Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M10 5V27" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <span class="text-xs sm:text-sm text-center text-theme-dark">Order Placed</span>
                            </div>
                        </div>

                        <!-- Packaging -->
                        <div class="step packaging relative z-[1] completed">
                            @if ($order->delivery_status == \App\Enums\OrderStatus::SHIPPED->value)
                                <div class="step-dot flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full">
                                    <span>
                                        <i class="fa-solid fa-check text-white text-xs sm:text-sm"></i>
                                    </span>
                                </div>
                            @else
                                <div class="step-dot flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full">
                                    <span>
                                        <i class="fa-solid fa-check text-white text-xs sm:text-sm"></i>
                                    </span>
                                </div>
                            @endif
                            <div class="step-content flex items-center flex-col gap-y-2 sm:gap-y-3 mt-4 sm:mt-6">
                                <!-- packing icon -->
                                <svg class="step-icon text-primary w-6 h-6 sm:w-8 sm:h-8" width="32" height="32"
                                    viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.2"
                                        d="M4.1375 9.32495C4.04693 9.48052 3.99946 9.65743 4 9.83745V22.1625C4.00096 22.3405 4.04884 22.5151 4.13882 22.6687C4.2288 22.8224 4.35769 22.9495 4.5125 23.0375L15.5125 29.225C15.6608 29.3097 15.8292 29.3529 16 29.35L16.1125 16L4.1375 9.32495Z"
                                        fill="currentColor" />
                                    <path
                                        d="M28 22.1625V9.83753C27.999 9.6595 27.9512 9.48487 27.8612 9.33125C27.7712 9.17763 27.6423 9.05045 27.4875 8.96253L16.4875 2.77503C16.3393 2.68946 16.1711 2.64441 16 2.64441C15.8289 2.64441 15.6607 2.68946 15.5125 2.77503L4.5125 8.96253C4.35769 9.05045 4.22879 9.17763 4.13882 9.33125C4.04884 9.48487 4.00096 9.6595 4 9.83753V22.1625C4.00096 22.3406 4.04884 22.5152 4.13882 22.6688C4.22879 22.8224 4.35769 22.9496 4.5125 23.0375L15.5125 29.225C15.6607 29.3106 15.8289 29.3557 16 29.3557C16.1711 29.3557 16.3393 29.3106 16.4875 29.225L27.4875 23.0375C27.6423 22.9496 27.7712 22.8224 27.8612 22.6688C27.9512 22.5152 27.999 22.3406 28 22.1625V22.1625Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M22.125 19.0625V12.5625L10 5.875" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M27.8617 9.32495L16.1117 16L4.13672 9.32495" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.1125 16L16 29.35" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <span class="text-xs sm:text-sm text-center text-theme-dark">Packaging</span>
                            </div>
                        </div>

                        <!-- On The Road -->
                        <div class="step on-road relative z-[1] active">
                            @if ($order->delivery_status == \App\Enums\OrderStatus::SHIPPED->value)
                                <div class="step-dot flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full">
                                    <span>
                                        <i class="fa-solid fa-check text-white text-xs sm:text-sm"></i>
                                    </span>
                                </div>
                            @else
                                <div class="step-dot flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full">
                                    <span>
                                        <i class="fa-solid fa-check text-white text-xs sm:text-sm"></i>
                                    </span>
                                </div>
                            @endif
                            <div class="step-content flex items-center flex-col gap-y-2 sm:gap-y-3 mt-4 sm:mt-6">
                                <!-- packaging icon -->
                                <svg class="step-icon text-butterfly-blue w-6 h-6 sm:w-8 sm:h-8" width="32"
                                    height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.2"
                                        d="M22 18H2V23C2 23.2652 2.10536 23.5196 2.29289 23.7071C2.48043 23.8946 2.73478 24 3 24H5.5C5.5 23.2044 5.81607 22.4413 6.37868 21.8787C6.94129 21.3161 7.70435 21 8.5 21C9.29565 21 10.0587 21.3161 10.6213 21.8787C11.1839 22.4413 11.5 23.2044 11.5 24H20.5C20.4997 23.4731 20.6381 22.9553 20.9014 22.4989C21.1648 22.0425 21.5437 21.6635 22 21.4V18Z"
                                        fill="currentColor" />
                                    <path opacity="0.2"
                                        d="M26.5 24C26.5003 23.4732 26.362 22.9557 26.0988 22.4993C25.8356 22.043 25.4569 21.664 25.0008 21.4005C24.5447 21.1369 24.0273 20.9982 23.5005 20.9981C22.9737 20.998 22.4562 21.1366 22 21.4V15H30V23C30 23.2652 29.8946 23.5196 29.7071 23.7071C29.5196 23.8946 29.2652 24 29 24H26.5Z"
                                        fill="currentColor" />
                                    <path
                                        d="M22 10H27.325C27.5242 9.99872 27.7192 10.0577 27.8843 10.1693C28.0494 10.2808 28.1769 10.4397 28.25 10.625L30 15"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M2 18H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M23.5 27C25.1569 27 26.5 25.6569 26.5 24C26.5 22.3431 25.1569 21 23.5 21C21.8431 21 20.5 22.3431 20.5 24C20.5 25.6569 21.8431 27 23.5 27Z"
                                        stroke="currentColor" stroke-width="2" stroke-miterlimit="10" />
                                    <path
                                        d="M8.5 27C10.1569 27 11.5 25.6569 11.5 24C11.5 22.3431 10.1569 21 8.5 21C6.84315 21 5.5 22.3431 5.5 24C5.5 25.6569 6.84315 27 8.5 27Z"
                                        stroke="currentColor" stroke-width="2" stroke-miterlimit="10" />
                                    <path d="M20.5 24H11.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.5 24H3C2.73478 24 2.48043 23.8946 2.29289 23.7071C2.10536 23.5196 2 23.2652 2 23V9C2 8.73478 2.10536 8.48043 2.29289 8.29289C2.48043 8.10536 2.73478 8 3 8H22V21.4"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M22 15H30V23C30 23.2652 29.8946 23.5196 29.7071 23.7071C29.5196 23.8946 29.2652 24 29 24H26.5"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <span class="text-xs sm:text-sm text-center text-theme-dark">On The Road</span>
                            </div>
                        </div>

                        <!-- Delivered -->
                        <div class="step delivered relative z-[1]">
                            @if ($order->delivery_status == \App\Enums\OrderStatus::DELIVERED->value)
                                <div class="step-dot flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full">
                                    <span>
                                        <i class="fa-solid fa-check text-white text-xs sm:text-sm"></i>
                                    </span>
                                </div>
                            @else
                                <div class="step-dot flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full">
                                    <span>
                                        <i class="fa-solid fa-check text-white text-xs sm:text-sm"></i>
                                    </span>
                                </div>
                            @endif
                            <div class="step-content flex items-center flex-col gap-y-2 sm:gap-y-3 mt-4 sm:mt-6">
                                <!--  delivered icon -->
                                <svg class="step-icon text-leaf-green w-6 h-6 sm:w-8 sm:h-8" width="32"
                                    height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.2"
                                        d="M25 19.1125L20.4 23.7125C20.2746 23.8298 20.1227 23.9149 19.9573 23.9606C19.7918 24.0063 19.6178 24.0113 19.45 23.975L12.2 22.1625C12.0676 22.1256 11.9442 22.0618 11.8375 21.975L5 16.6375L9.075 8.97502L15.4875 7.10002C15.7154 7.03468 15.959 7.0524 16.175 7.15002L20.5 9.11252H17.9125C17.7826 9.11207 17.654 9.13723 17.5339 9.18656C17.4138 9.23589 17.3045 9.30843 17.2125 9.40002L12.3125 14.2875C12.2125 14.3902 12.1354 14.513 12.0863 14.6478C12.0373 14.7825 12.0174 14.9261 12.0281 15.0691C12.0387 15.2121 12.0796 15.3512 12.148 15.4772C12.2164 15.6032 12.3109 15.7132 12.425 15.8L13.1 16.3125C13.7932 16.8299 14.635 17.1094 15.5 17.1094C16.365 17.1094 17.2068 16.8299 17.9 16.3125L19.5 15.1125L25 19.1125Z"
                                        fill="currentColor" />
                                    <path
                                        d="M30.0875 15.225L27 16.7625L23 9.11247L26.125 7.54997C26.3572 7.43159 26.6269 7.40983 26.8751 7.48945C27.1233 7.56906 27.33 7.74359 27.45 7.97497L30.525 13.8625C30.5874 13.9803 30.6255 14.1095 30.6372 14.2423C30.6489 14.3751 30.634 14.5089 30.5932 14.6359C30.5525 14.7628 30.4867 14.8803 30.3999 14.9815C30.313 15.0827 30.2068 15.1654 30.0875 15.225V15.225Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M4.99979 16.6375L1.91229 15.0875C1.79341 15.0292 1.68746 14.9477 1.60073 14.8476C1.51401 14.7476 1.44829 14.6311 1.40747 14.5052C1.36666 14.3793 1.35159 14.2464 1.36315 14.1145C1.37471 13.9826 1.41268 13.8544 1.47479 13.7375L4.54979 7.84999C4.67008 7.61878 4.87588 7.44367 5.12337 7.36195C5.37086 7.28023 5.64047 7.29837 5.87479 7.41249L8.99979 8.97499L4.99979 16.6375Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M27 16.7625L25 19.1124L20.4 23.7125C20.2746 23.8297 20.1227 23.9148 19.9573 23.9605C19.7918 24.0062 19.6178 24.0112 19.45 23.975L12.2 22.1625C12.0676 22.1255 11.9442 22.0617 11.8375 21.975L5 16.6375"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M24.9982 19.1126L19.4982 15.1126L17.8982 16.3126C17.205 16.8299 16.3631 17.1094 15.4982 17.1094C14.6332 17.1094 13.7914 16.8299 13.0982 16.3126L12.4232 15.8001C12.309 15.7133 12.2146 15.6032 12.1462 15.4772C12.0777 15.3512 12.0369 15.2121 12.0262 15.0691C12.0156 14.9262 12.0354 14.7825 12.0845 14.6478C12.1335 14.5131 12.2106 14.3903 12.3107 14.2876L17.2107 9.40005C17.3027 9.30846 17.4119 9.23592 17.532 9.18659C17.6521 9.13726 17.7808 9.1121 17.9107 9.11255H22.9982"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M9.07422 8.97502L15.4867 7.10002C15.7146 7.03468 15.9582 7.0524 16.1742 7.15002L20.4992 9.11252"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14 26.6125L10.2375 25.6625C10.0842 25.6279 9.94221 25.5547 9.825 25.45L7 23"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <span class="text-xs sm:text-sm text-center text-theme-dark">Delivered</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order Activity -->
            <div class="lg:max-w-5xl md:w-5/6 sm:w-10/12 xsm:w-11/12 mx-auto border border-jet-gray/30 mt-8">
                <div class="p-4 sm:p-6">
                    <h2 class="sm:text-lg font-semibold mb-3 sm:mb-5">
                        Order Activity
                    </h2>
                    <div class="space-y-3 sm:space-y-4">
                        <!-- Activity Items -->
                        <div class="text-xs xsm:text-sm flex items-start space-x-2 xsm:space-x-3">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-[#EAF7E9] text-leaf-green flex items-center justify-center flex-shrink-0">
                                <!-- double check icon -->
                                <svg class="w-5 sm:w-6 h-5 sm:h-6" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.875 7.875L5.625 16.125L1.5 12" stroke="#2DB324" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M22.4984 7.875L14.2484 16.125L12.0547 13.9313" stroke="#2DB324"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <p>Your order has been delivered. Thank you for shopping!</p>
                                <p class="text-jet-gray mt-1">12 Fab, 2025 at 04:35 PM</p>
                            </div>
                        </div>

                        <div class="text-xs xsm:text-sm flex items-start space-x-2 xsm:space-x-3">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-butterfly-blue/10 text-lg sm:text-xl text-butterfly-blue flex items-center justify-center flex-shrink-0">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <div>
                                <p>
                                    Our delivery man (John Wick) Has picked-up your order for
                                    delivery.
                                </p>
                                <p class="text-jet-gray mt-1">12 Fab, 2025 at 10:30 PM</p>
                            </div>
                        </div>

                        <div class="text-xs xsm:text-sm flex items-start space-x-2 xsm:space-x-3">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-butterfly-blue/10 text-lg sm:text-xl text-butterfly-blue flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>

                            <div>
                                <p>Your order has reached at last mile hub.</p>
                                <p class="text-jet-gray mt-1">11 Fab, 2025 at 08:00 PM</p>
                            </div>
                        </div>

                        <div class="text-xs xsm:text-sm flex items-start space-x-2 xsm:space-x-3">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-butterfly-blue/10 text-lg sm:text-xl text-butterfly-blue flex items-center justify-center flex-shrink-0">
                                <i class="fa-regular fa-map"></i>
                            </div>
                            <div>
                                <p>Your order on the way to (last mile) hub.</p>
                                <p class="text-jet-gray mt-1">11 Fab, 2025 at 10:00 PM</p>
                            </div>
                        </div>

                        <div class="text-xs xsm:text-sm flex items-start space-x-2 xsm:space-x-3">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-[#EAF7E9] text-lg sm:text-xl text-leaf-green flex items-center justify-center flex-shrink-0">
                                <i class="fa-regular fa-circle-check"></i>
                            </div>
                            <div>
                                <p>Your order is successfully verified.</p>
                                <p class="text-jet-gray mt-1">10 Fab, 2025 at 02:30 PM</p>
                            </div>
                        </div>

                        <div class="text-xs xsm:text-sm flex items-start space-x-2 xsm:space-x-3">
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-butterfly-blue/10 text-butterfly-blue flex items-center justify-center flex-shrink-0">
                                <!-- order confirm icon -->
                                <svg class="w-5 sm:w-6 h-5 sm:h-6" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9 15H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M5.25 3.75H18.75C18.9489 3.75 19.1397 3.82902 19.2803 3.96967C19.421 4.11032 19.5 4.30109 19.5 4.5V18.75C19.5 19.3467 19.2629 19.919 18.841 20.341C18.419 20.7629 17.8467 21 17.25 21H6.75C6.15326 21 5.58097 20.7629 5.15901 20.341C4.73705 19.919 4.5 19.3467 4.5 18.75V4.5C4.5 4.30109 4.57902 4.11032 4.71967 3.96967C4.86032 3.82902 5.05109 3.75 5.25 3.75V3.75Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.5 2.25V5.25" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 2.25V5.25" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.5 2.25V5.25" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div>
                                <p>Your order has been confirmed.</p>
                                <p class="text-jet-gray mt-1">10 Fab, 2025 at 12:13 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Tracking Main Section Ended -->
    </main>
@endsection
