@extends('frontend.layouts.app')
@section('title', 'Order Details')

@section('content')
    <main class="max-w-5xl mx-auto px-4 py-6">
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

            <a href="{{ route('invoice', $order->invoice_id) }}" target="__blank"
                class="text-sm text-primary-600 hover:text-primary-500 font-medium">
                Download Invoice
            </a>
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

            <div class="divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <div class="p-4 flex items-start">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="font-medium">{{ $item->product_name }}</h3>
                                <p class="font-medium">{{ money($item->total) }}</p>
                            </div>
                            <div class="flex justify-between items-end mt-1">
                                <div class="text-sm text-gray-500">
                                    <p>{{ $item->variant->fullname ?? '' }} • Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

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

                    @if ($order->payment)
                        <div>
                            <h3 class="text-xs font-medium text-gray-500 mb-1">Payment Method</h3>
                            {{ ucfirst($order->payment->gateway) }}
                        </div>
                    @endif

                    <div>
                        <h3 class="text-xs font-medium text-gray-500 mb-1">Contact</h3>
                        <p class="text-gray-900">{{ $order->customer_email }}<br>{{ $order->customer_phone }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4 flex flex-wrap gap-2">
                    {{-- <button class="text-xs px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Return Item
                </button>
                <button class="text-xs px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Report Issue
                </button> --}}
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
                        <span class="text-gray-600">Shipping Fee</span>
                        <span>{{ money($order->shipping_fee) }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-2 flex items-center justify-between font-medium">
                        <span>Total</span>
                        <span>{{ $order->payable }}</span>
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

        <div class="mt-8">
            <h2 class="text-lg font-medium mb-4">You May Also Like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($products as $product)
                    <a href="{{ route('products.details', $product->slug) }}">
                        <div class="bg-white p-3 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}"
                                class="w-full h-32 object-cover rounded-md mb-2">
                            <h3 class="font-medium text-sm">{{ $product->name }}</h3>
                            <p class="text-yellow-500 text-sm font-bold mt-1">{{ money($product->selling_price) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
