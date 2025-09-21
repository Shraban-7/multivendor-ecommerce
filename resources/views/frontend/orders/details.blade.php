@extends('frontend.layouts.app')
@section('title', 'Order Details')

@section('content')
    <main class="max-w-5xl mx-auto px-4 py-6">
        <!-- Order Header -->
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-xl font-bold">Order #{{ $order->invoice_id }}</h1>
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Delivered
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d Y') }}</p>
            </div>

            <button class="text-sm text-primary-600 hover:text-primary-500 font-medium">
                Download Invoice
            </button>
        </div>

        <!-- Vendor Info -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-5">
            <div class="bg-primary-100 px-4 py-3 border-b border-primary-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ storage_url($order->seller->business_logo) }}" alt="Vendor logo"
                            class="h-8 w-8 rounded-full mr-3">
                        <h2 class="font-medium">{{ $order->seller->business_name }}</h2>
                    </div>
                    <a href="#" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Contact Seller</a>
                </div>
            </div>

            <!-- Order Items - Compact List -->
            <div class="divide-y divide-gray-100">
                <!-- Item 1 -->
                @foreach ($order->items as $item)
                    <div class="p-4 flex items-start">
                        <img src="{{ storage_url($item->product->thumbnail) }}" alt="Product image"
                            class="w-16 h-16 rounded object-cover mr-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="font-medium">{{ $item->product->name }}</h3>
                                <p class="font-medium">{{ money($item->price) }}</p>
                            </div>
                            <div class="flex justify-between items-end mt-1">
                                <div class="text-sm text-gray-500">
                                    <p>{{ $item->variant->fullname }} • Qty: {{ $item->quantity }}</p>
                                </div>
                                <div>
                                    <button class="text-xs text-primary-600 hover:text-primary-500">Track Package</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Details & Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Shipping & Delivery Info -->
            <div class="md:col-span-2 bg-white rounded-lg shadow-sm p-4">
                <h2 class="font-medium mb-3 text-gray-900">Shipping Details</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h3 class="text-xs font-medium text-gray-500 mb-1">Shipping Address</h3>
                        <p class="text-gray-900">{{ $order->customer_name }}<br>{{ $order->customer_address }}</p>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-gray-500 mb-1">Delivery Method</h3>
                        <p class="text-gray-900">Standard Shipping</p>
                        <p class="text-gray-500 text-xs mt-1">Delivered on {{ $order->updated_at->format('F d ,Y') }}</p>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-gray-500 mb-1">Payment Method</h3>
                        <p class="text-gray-900">Visa •••• 4242</p>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-gray-500 mb-1">Contact</h3>
                        <p class="text-gray-900">{{ $order->customer_email }}<br>{{ $order->customer_phone }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4 flex flex-wrap gap-2">
                    <button class="text-xs px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Return Item
                    </button>
                    <button class="text-xs px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Report Issue
                    </button>
                    @if ($item->is_reviewed == 0)
                        <button id="open-review-modal"
                            class="text-xs px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Write Review
                        </button>
                    @endif
                </div>
            </div>

            <div id="review-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative">

                    <!-- Close Button -->
                    <button type="button" id="close-review-modal"
                        class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
                        ✕
                    </button>

                    <!-- Modal Content -->
                    <div class="w-full flex flex-col items-center">
                        <!-- User Avatar -->
                        <div class="w-20 h-20 rounded-full overflow-hidden mb-4 border border-gray-300 shadow-sm">
                            <img src="{{ $user->avatar }}" alt="Profile" class="w-full h-full object-cover" />
                        </div>

                        <!-- Heading -->
                        <h2 class="text-xl md:text-2xl font-semibold text-davy-gray mb-1 text-center">
                            Share your experience</h2>
                        <p class="text-sm text-jet-gray mb-4 text-center max-w-md">
                            Your feedback helps us ensure top-quality service.
                        </p>

                        <!-- Review Form -->
                        <form action="{{ route('orders.review') }}" method="POST" enctype="multipart/form-data"
                            class="w-full space-y-6" id="review-form">
                            @csrf

                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                            <!-- Star Rating -->
                            <div id="stars-container" class="flex justify-center gap-2 mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="review-star text-3xl cursor-pointer text-gray-300"
                                        data-rating="{{ $i }}">
                                        <i class="fa-solid fa-star"></i>
                                    </span>
                                @endfor

                            </div>
                            <input type="hidden" name="rating" id="star-rating" value="3">

                            <!-- Review Text -->
                            <textarea id="feedback-text" name="description" required
                                class="w-full p-3 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-primary text-base"
                                placeholder="Write your review here..." rows="2"></textarea>

                            <!-- Image Upload -->
                            <div>
                                <label for="image-input" class="block text-sm font-medium text-davy-gray mb-2">
                                    Upload Images (Optional)
                                </label>

                                <div id="dropzone"
                                    class="relative flex flex-col items-center justify-center w-full p-4 text-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 transition hover:border-primary">
                                    <p class="text-sm text-gray-500">Click or drag &
                                        drop to upload</p>
                                    <input name="images[]" type="file" id="image-input" multiple
                                        class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                                </div>
                                <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
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

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <h2 class="font-medium mb-3 text-gray-900">Order Summary</h2>

                <div class="space-y-2 text-sm mb-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span>{{ money($order->total) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span>{{ money($order->shipping_fee) }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span>{{ money($order->tax) }}</span>
                    </div>

                    <div class="border-t border-gray-200 pt-2 flex items-center justify-between font-medium">
                        <span>Total</span>
                        <span>{{ $order->total }}</span>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="mt-6 text-center">
                    <h3 class="text-xs font-medium text-gray-500 mb-2">Need Help?</h3>
                    <a href="#" class="text-primary-600 hover:text-primary-500 text-sm font-medium">
                        Contact Customer Support
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Items -->
        <div class="mt-8">
            <h2 class="text-lg font-medium mb-4">You May Also Like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <!-- Product 1 -->
                <div class="bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <img src="https://via.placeholder.com/150" alt="Product"
                        class="w-full h-32 object-cover rounded-md mb-2">
                    <h3 class="font-medium text-sm">Wireless Charger</h3>
                    <p class="text-primary-600 text-sm mt-1">$29.99</p>
                </div>

                <!-- Product 2 -->
                <div class="bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <img src="https://via.placeholder.com/150" alt="Product"
                        class="w-full h-32 object-cover rounded-md mb-2">
                    <h3 class="font-medium text-sm">Bluetooth Speaker</h3>
                    <p class="text-primary-600 text-sm mt-1">$59.99</p>
                </div>

                <!-- Product 3 -->
                <div class="bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <img src="https://via.placeholder.com/150" alt="Product"
                        class="w-full h-32 object-cover rounded-md mb-2">
                    <h3 class="font-medium text-sm">Phone Stand</h3>
                    <p class="text-primary-600 text-sm mt-1">$14.99</p>
                </div>

                <!-- Product 4 -->
                <div class="bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <img src="https://via.placeholder.com/150" alt="Product"
                        class="w-full h-32 object-cover rounded-md mb-2">
                    <h3 class="font-medium text-sm">Screen Protector</h3>
                    <p class="text-primary-600 text-sm mt-1">$9.99</p>
                </div>
            </div>
        </div>
    </main>


    <!-- Old Design -->
    <main class="order-details-page p-5 sm:pb-10 hidden">
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
                            {{ $order->created_at->format('F d Y') }}
                        </p>
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
                                            <div
                                                class="w-16 h-20 md:w-20 md:h-24 flex-shrink-0 rounded-xl overflow-hidden">
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
