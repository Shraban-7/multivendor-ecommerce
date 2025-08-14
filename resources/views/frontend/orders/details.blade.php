@extends('frontend.layouts.app')
@section('title', 'Order Details')

@section('content')
    <main class="order-details-page p-5 sm:pb-10">
        <!-- Page Breadcrumb -->
        <section class="page-breadcrumb-links bg-jet-gray/10 py-4 md:py-6">
            <nav class="flex container" aria-label="Breadcrumb">
                <ol class="inline-flex flex-wrap items-center gap-x-1 gap-y-2 md:gap-x-2 rtl:gap-x-reverse">
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
                            Order History
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            Order Details
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-butterfly-blue md:ms-2">Completed</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Order Details Main Section Starts -->
        <section class="order-details-section container section-padding">
            <div class="order-details-head">
                <h2 class="sm:text-2xl text-xl">Order Detail</h2>

                <div class="order-details-menus pt-3 md:pt-5 pb-5 md:pb-8 border-b border-jet-gray/30">
                    <ul class="flex flex-wrap gap-3">
                        <li>
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Active</a>
                        </li>
                        <li aria-current="page">
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Completed</a>
                        </li>
                        <li>
                            <a href="#"
                                class="inline-block sm:px-5 px-3.5 sm:py-3 py-1.5 bg-jet-gray/10 hover:bg-jet-gray/20 eq text-jet-gray text-sm rounded-3xl">Cancelled</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="order-details-body py-5 md:py-8">
                <!-- Order ID and Status -->
                <div class="flex flex-wrap items-start gap-5 xsm:gap-10 md:gap-16 mb-2">
                    <div>
                        <p class="font-medium">Order ID : #{{ $order->invoice_id }}</p>
                        <p class="text-xs text-davy-gray">Order Placed on:
                            {{ $order->created_at->format('F d Y') }}</p>
                    </div>
                    <span class="inline-block bg-leaf-green text-white px-3.5 py-1.5 rounded-full text-sm">
                        Delivered
                    </span>
                </div>

                <!-- Order More Info -->
                <div class="lg:w-10/12 pt-5 md:pt-8">
                    <div class="flex flex-col sm:flex-row items-start gap-5">
                        <div class="space-y-5 w-full sm:w-1/2">
                            <!-- Shipping Address -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Shipping Address
                                    </h3>

                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray space-y-1 pt-3 md:space-y-1.5 md:pt-5">
                                    <p>{{ $order->customer_name }}</p>
                                    <p>{{ $order->customer_address }}</p>
                                    <div class="flex gap-2 items-center pt-2">
                                        <i class="fa-solid fa-phone"></i>
                                        <span>{{ $order->customer_phone }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Payment Details
                                    </h3>
                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray md:space-y-2 md:pt-5 space-y-1 pt-3">
                                    <div class="flex gap-2">
                                        <img src="{{ asset('assets/frontend/images/payment-method-visa.png') }}"
                                            alt="Visa Card" class="w-10 md:w-14 object-contain" />

                                        <h4 class="text-aqua-deep font-medium md:text-lg">
                                            ### 2355
                                        </h4>
                                    </div>
                                    <p class="font-medium">
                                        Expires : <span class="text-aqua-deep">06/24</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5 w-full sm:w-1/2">
                            <!-- Items Included -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Items Included
                                    </h3>
                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray space-y-1.5 divide-y-2 divide-davy-gray/10">
                                    <!-- Item 1 -->
                                    @foreach ($order->items as $item)
                                        <div class="flex gap-2 md:gap-4 py-3 md:py-5 border-b border-gray-200">
                                            @php
                                                $imageUrl = null;
                                                if ($item->variant && $item->variant->image) {
                                                    $imageUrl = storage_url($item->variant->image);
                                                } elseif (isset($item->product->thumbnail)) {
                                                    $imageUrl = storage_url($item->product->thumbnail);
                                                }
                                            @endphp
                                            <div class="w-16 h-20 md:w-20 md:h-24 flex-shrink-0 rounded-xl overflow-hidden">
                                                <a href="{{ route('products.details', $item->product->slug) }}">
                                                    <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}"
                                                        class="w-full h-full object-cover" />
                                                </a>
                                            </div>

                                            <div class="flex-grow space-y-1 md:space-y-2">
                                                <a href="{{ route('products.details', $item->product->slug) }}">
                                                    <p class="font-medium text-sm md:text-base">
                                                        {{ $item->product->name }}
                                                    </p>
                                                </a>
                                                <p class="text-sm text-jet-gray">Quantity: {{ $item->quantity }}</p>
                                                <p class="flex items-center gap-1 text-aqua-deep mt-1">
                                                    <span
                                                        class="text-lg md:text-2xl font-medium">{{ money($item->unit_price) }}</span>
                                                    @if ($item->discount != null && $item->discount != 0)
                                                        <span
                                                            class="text-lg md:text-xl text-jet-gray font-medium line-through">
                                                            {{ money($item->unit_price + $item->discount) }}
                                                        </span>
                                                    @endif
                                                </p>

                                                @if ($item->variant && $item->variant->option_values->count())
                                                    <div class="w-full text-xs xsm:text-sm text-gray-600 mt-1">
                                                        @foreach ($item->variant->option_values as $optionValue)
                                                            <span class="mr-2">
                                                                {{ $optionValue->option->name ?? '' }}:
                                                                {{ $optionValue->value ?? '' }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif


                                                @if ($item->is_reviewed == 0)
                                                <!-- Submit Review Button -->
                                                <button type="button"
                                                    class="inline-block mt-2 text-xs md:text-sm text-white bg-primary hover:bg-theme-dark px-4 py-2 rounded transition-all duration-200"
                                                    id="open-review-modal">
                                                    Submit a Review
                                                </button>

                                                @endif
                                                <!-- Review Modal -->
                                                <div id="review-modal"
                                                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                                    <div
                                                        class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative">

                                                        <!-- Close Button -->
                                                        <button type="button" id="close-review-modal"
                                                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
                                                            ✕
                                                        </button>

                                                        <!-- Modal Content -->
                                                        <div class="w-full flex flex-col items-center">
                                                            <!-- User Avatar -->
                                                            <div
                                                                class="w-20 h-20 rounded-full overflow-hidden mb-4 border border-gray-300 shadow-sm">
                                                                <img src="{{ $user->avatar }}" alt="Profile"
                                                                    class="w-full h-full object-cover" />
                                                            </div>

                                                            <!-- Heading -->
                                                            <h2
                                                                class="text-xl md:text-2xl font-semibold text-davy-gray mb-1 text-center">
                                                                Share your experience</h2>
                                                            <p class="text-sm text-jet-gray mb-4 text-center max-w-md">
                                                                Your feedback helps us ensure top-quality service.
                                                            </p>

                                                            <!-- Review Form -->
                                                            <form action="{{ route('orders.review') }}" method="POST"
                                                                enctype="multipart/form-data" class="w-full space-y-6"
                                                                id="review-form">
                                                                @csrf

                                                                <input type="hidden" name="order_item_id"
                                                                    value="{{ $item->id }}">
                                                                <!-- Star Rating -->
                                                                <div id="stars-container"
                                                                    class="flex justify-center gap-2 mb-1">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <span
                                                                            class="review-star text-3xl cursor-pointer text-gray-300"
                                                                            data-rating="{{ $i }}">
                                                                            <i class="fa-solid fa-star"></i>
                                                                        </span>
                                                                    @endfor

                                                                </div>
                                                                <input type="hidden" name="rating" id="star-rating"
                                                                    value="3">

                                                                <!-- Review Text -->
                                                                <textarea id="feedback-text" name="description" required
                                                                    class="w-full p-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-primary text-base"
                                                                    placeholder="Write your review here..." rows="2"></textarea>

                                                                <!-- Image Upload -->
                                                                <div>
                                                                    <label for="image-input"
                                                                        class="block text-sm font-medium text-davy-gray mb-2">
                                                                        Upload Images (Optional)
                                                                    </label>

                                                                    <div id="dropzone"
                                                                        class="relative flex flex-col items-center justify-center w-full p-4 text-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 transition hover:border-primary">
                                                                        <p class="text-sm text-gray-500">Click or drag &
                                                                            drop to upload</p>
                                                                        <input name="images[]" type="file"
                                                                            id="image-input" multiple
                                                                            class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                                                                    </div>
                                                                    <div id="preview-container"
                                                                        class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                                                    </div>
                                                                </div>

                                                                <!-- Buttons -->
                                                                <div class="flex flex-col md:flex-row gap-3">
                                                                    <button type="submit"
                                                                        class="w-full bg-primary text-white py-2 rounded-lg hover:bg-theme-dark transition">
                                                                        Submit Review
                                                                    </button>
                                                                    <button type="button" id="cancel-review"
                                                                        class="w-full text-center bg-gray-100 text-gray-800 py-2 rounded-lg hover:bg-gray-200 transition">
                                                                        Cancel
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="rounded-xl border-2 border-jet-gray/20 bg-davy-gray/5 p-5">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg md:text-xl font-medium text-theme-dark">
                                        Order Summary
                                    </h3>
                                    <span><i class="fa-solid fa-chevron-down text-rangoon-green"></i></span>
                                </div>
                                <div class="text-davy-gray space-y-3 pt-5">
                                    <div class="flex justify-between text-sm md:text-base">
                                        <span>Subtotal</span>
                                        <span class="font-medium">{{ money($order->sub_total) }}</span>
                                    </div>

                                    <div class="flex justify-between text-sm md:text-base">
                                        <span>Discount</span>
                                        <span class="text-red-500 font-medium">-{{ money($order->discount) }}</span>
                                    </div>

                                    <div class="flex justify-between text-sm md:text-base">
                                        <span>Tax</span>
                                        <span class="font-medium text-jet-gray">+{{ money($order->tax) }}</span>
                                    </div>

                                    <div class="flex justify-between text-sm md:text-base">
                                        <span>Delivery</span>
                                        <span class="text-jet-gray font-medium">+{{ money($order->shipping_fee) }}</span>
                                    </div>

                                    <div class="border-t border-davy-gray/20 pt-4 mt-4">
                                        <h2 class="flex justify-between text-base md:text-lg font-semibold text-gray-800">
                                            <span>Total</span>
                                            <span>{{ money($order->total) }}</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-actions space-y-3 md:space-y-5 mt-8 md:mt-12">
                        <a href="#"
                            class="inline-block capitalize bg-primary text-theme-light md:text-lg lg:text-xl w-full py-2.5 md:py-3 lg:py-4 text-center rounded-full hover:bg-theme-dark eq">Return
                            or refund</a>

                    </div>
                </div>
            </div>
        </section>
        <!-- Checkout Main Section Ended -->
    </main>

    @push('scripts')
        <script>
            $(document).ready(function() {

                // ==== Modal Open/Close ====
                $('#open-review-modal').on('click', function() {
                    $('#review-modal').removeClass('hidden');
                });

                $('#close-review-modal, #cancel-review').on('click', function() {
                    $('#review-modal').addClass('hidden');
                });

                let currentRating = 0;

                setStarState(currentRating);

                $('#stars-container').on('click', '.review-star', function() {
                    currentRating = $(this).data('rating');
                    setStarState(currentRating);
                    $('#star-rating').val(currentRating);
                });

                $('#stars-container').on('mouseover', '.review-star', function() {
                    const hoverRating = $(this).data('rating');
                    setStarState(hoverRating, true);
                });

                $('#stars-container').on('mouseout', function() {
                    setStarState(currentRating);
                });

                function setStarState(rating, isHover = false) {
                    $('#stars-container .review-star').each(function() {
                        const starRating = $(this).data('rating');
                        if (isHover) {
                            $(this).toggleClass('active', starRating <= rating);
                        } else {
                            $(this).toggleClass('active', starRating <= rating);
                        }
                    });
                }

                // ==== Image Upload & Preview ====
                const $dropzone = $('#dropzone');
                const $input = $('#image-input');
                const $previewContainer = $('#preview-container');
                let selectedFiles = [];

                $dropzone.on('dragover', function(e) {
                    e.preventDefault();
                    $dropzone.addClass('border-primary bg-gray-100');
                });

                $dropzone.on('dragleave', function() {
                    $dropzone.removeClass('border-primary bg-gray-100');
                });

                $dropzone.on('drop', function(e) {
                    e.preventDefault();
                    $dropzone.removeClass('border-primary bg-gray-100');
                    const files = Array.from(e.originalEvent.dataTransfer.files);
                    selectedFiles = selectedFiles.concat(files);
                    updateInputFiles();
                    showPreviews();
                });

                $input.on('change', function(e) {
                    const files = Array.from(e.target.files);
                    selectedFiles = selectedFiles.concat(files);
                    updateInputFiles();
                    showPreviews();
                });

                function showPreviews() {
                    $previewContainer.empty();
                    selectedFiles.forEach((file, index) => {
                        if (!file.type.startsWith('image/')) return;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const $wrapper = $('<div class="relative group">');
                            const $img = $('<img>', {
                                src: e.target.result,
                                class: 'w-full h-24 object-cover rounded-lg border border-gray-200'
                            });
                            const $removeBtn = $(
                                `<button type="button" data-index="${index}" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">&times;</button>`
                            );

                            $removeBtn.on('click', function() {
                                const idx = $(this).data('index');
                                selectedFiles.splice(idx, 1);
                                updateInputFiles();
                                showPreviews();
                            });

                            $wrapper.append($img).append($removeBtn);
                            $previewContainer.append($wrapper);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                function updateInputFiles() {
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => dataTransfer.items.add(file));
                    $input[0].files = dataTransfer.files;
                }
            });
        </script>
    @endpush
@endsection
