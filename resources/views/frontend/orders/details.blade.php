@extends('frontend.layouts.app')
@section('title', 'Order Details')
<?php
use App\Domain\Order\Enums\OrderStatus;

?>
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-10">
        @include('frontend.layouts.dashboard-nav')
        <div class="mt-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-xl font-semibold text-[#191919]">Order #{{ $order->invoice_id }}</h1>

                    @if ($order->status->label() == OrderStatus::PENDING->label())
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::PENDING->label() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::ACCEPTED->label())
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::ACCEPTED->label() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::SHIPPED->label())
                        <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::SHIPPED->label() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::DELIVERED->label())
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::DELIVERED->label() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::CANCELLED->label())
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::CANCELLED->label() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::RETURN_REQUESTED->label())
                        <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::RETURN_REQUESTED->title() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::RETURN_APPROVED->label())
                        <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::RETURN_APPROVED->title() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::RETURNED->label())
                        <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::RETURNED->title() }}
                        </span>
                    @elseif ($order->status->label() == OrderStatus::REFUNDED->label())
                        <span class="bg-cyan-100 text-cyan-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ OrderStatus::REFUNDED->title() }}
                        </span>
                    @endif

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
                                @if ($item->is_reviewed == 0)
                                    <button data-item="{{ $item->id }}"
                                        class="open-review-modal text-xs px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                        Write Review
                                    </button>
                                @else
                                    <div class="flex text-lg mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fa-solid fa-star {{ $i <= $item->review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                @endif

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
                        <p class="text-gray-900">{{ $order->billing_address->customer_name }}<br>{{ $order->billing_address->address }}</p>
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
                        <p class="text-gray-900">{{ $order->billing_address->customer_phone }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4 flex flex-wrap gap-2">
                    @if ($order->status->value == App\Domain\Order\Enums\OrderStatus::DELIVERED->value)
                        <button type="button" id="open-return-modal"
                            class="text-xs px-3 py-1.5 border border-[#F85606] text-[#F85606] rounded-sm hover:bg-[#FFF8F5] transition-colors font-medium">
                            Request Return
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

                            {{-- <input type="hidden" name="order_item_id" value="{{ $item->id }}"> --}}
                            <!-- Hidden input for the order item id -->
                            <input type="hidden" name="order_item_id" id="review-item-id" value="">

                            <!-- Stars in modal -->
                            <div id="stars-container" class="flex justify-center gap-2 mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="review-star text-3xl cursor-pointer" data-rating="{{ $i }}">
                                        <!-- IMPORTANT: put the color class on the icon itself -->
                                        <i class="fa-solid fa-star text-gray-300"></i>
                                    </span>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="star-rating" value="0">


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
                                    class="w-full bg-primary-500 text-white py-2 rounded-lg hover:bg-theme-dark transition">
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
                    @if ($order->refund_amount > 0)
                        <div class="flex items-center justify-between text-cyan-700 font-medium pt-1">
                            <span>Refunded</span>
                            <span>{{ money($order->refund_amount) }}</span>
                        </div>
                    @endif
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
            <h2 class="text-base font-bold text-brand">Similar Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mt-4">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>
        </div>
    </div>
</div>

    {{-- Return Request Modal --}}
    <div id="return-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-sm shadow-lg w-full max-w-xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="px-5 py-3.5 border-b border-[#E5E5E5] flex items-center justify-between sticky top-0 bg-white">
                <h2 class="text-base font-semibold text-[#191919]">Request Return</h2>
                <button type="button" id="close-return-modal" class="text-[#A0A0A0] hover:text-[#191919] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-5">
                <form action="{{ route('returns.store') }}" method="POST" class="space-y-4" id="return-form">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    {{-- Return Type --}}
                    <div>
                        <label class="text-sm font-medium text-[#191919] block mb-2">Return Type</label>
                        <div class="flex flex-wrap gap-3">
                            <label class="flex items-center gap-2 px-3 py-2 border border-[#E5E5E5] rounded-sm cursor-pointer hover:border-[#F85606] has-[:checked]:border-[#F85606] has-[:checked]:bg-[#FFF8F5] transition-colors">
                                <input type="radio" name="type" value="full" checked class="accent-[#F85606]">
                                <span class="text-sm text-[#191919]">Full Refund</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 border border-[#E5E5E5] rounded-sm cursor-pointer hover:border-[#F85606] has-[:checked]:border-[#F85606] has-[:checked]:bg-[#FFF8F5] transition-colors">
                                <input type="radio" name="type" value="partial" class="accent-[#F85606]">
                                <span class="text-sm text-[#191919]">Partial Refund</span>
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 border border-[#E5E5E5] rounded-sm cursor-pointer hover:border-[#F85606] has-[:checked]:border-[#F85606] has-[:checked]:bg-[#FFF8F5] transition-colors">
                                <input type="radio" name="type" value="exchange" class="accent-[#F85606]">
                                <span class="text-sm text-[#191919]">Exchange</span>
                            </label>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div id="return-items-section" class="hidden space-y-2">
                        <label class="text-sm font-medium text-[#191919] block">Select Items</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto border border-[#E5E5E5] rounded-sm p-3">
                            @foreach ($order->items as $item)
                                <label class="flex items-center gap-3 p-2 border border-[#E5E5E5] rounded-sm hover:bg-[#FAFAFA] cursor-pointer">
                                    <input type="checkbox" name="items[{{ $item->id }}][id]" value="{{ $item->id }}" disabled class="item-checkbox accent-[#F85606]">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-[#191919] truncate">{{ $item->product_name }}</p>
                                        <p class="text-xs text-[#767676]">{{ $item->variant?->fullname ?? '' }} × Qty: {{ $item->quantity }} — {{ money($item->total) }}</p>
                                    </div>
                                    <select name="items[{{ $item->id }}][quantity]" class="text-xs border border-[#E5E5E5] rounded-sm px-1 py-0.5">
                                        @for ($i = 1; $i <= $item->quantity; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Exchange Note --}}
                    <div id="exchange-note-section" class="hidden space-y-1.5">
                        <label for="exchange_note" class="text-sm font-medium text-[#191919]">What do you want instead?</label>
                        <textarea name="exchange_note" id="exchange_note" rows="2"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors resize-none"
                            placeholder="Describe the product, size, color, or variant you want in exchange..."></textarea>
                    </div>

                    {{-- Reason --}}
                    <div class="space-y-1.5">
                        <label for="reason" class="text-sm font-medium text-[#191919]">Reason for Return</label>
                        <textarea name="reason" id="reason" rows="3" required
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors resize-none"
                            placeholder="Tell us why you want to return this order..."></textarea>
                    </div>

                    <div class="flex gap-3 justify-end pt-2">
                        <button type="button" id="cancel-return"
                            class="px-4 py-2 text-sm font-medium text-[#595959] border border-[#E5E5E5] rounded-sm hover:bg-[#F5F5F5] transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-[#F85606] rounded-sm hover:bg-[#E04D05] transition-colors">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const $modal = $('#review-modal');
            const $starContainer = $('#stars-container');
            const $starRatingInput = $('#star-rating');
            const $reviewItemId = $('#review-item-id');

            let currentRating = 0;

            $(document).on('click', '.open-review-modal', function() {
                const itemId = $(this).data('item');
                const existingRating = parseInt($(this).data('rating') || 0, 10);

                $reviewItemId.val(itemId);
                currentRating = existingRating || 0;
                $starRatingInput.val(currentRating);

                setStarState(currentRating);
                $modal.removeClass('hidden');
            });

            $('#close-review-modal, #cancel-review').on('click', function() {
                $modal.addClass('hidden');
            });

            $starContainer.on('click', '.review-star', function() {
                const r = $(this).data('rating');
                currentRating = parseInt(r, 10);
                $starRatingInput.val(currentRating);
                setStarState(currentRating);
            });

            $starContainer.on('mouseover', '.review-star', function() {
                const hover = $(this).data('rating');
                setStarState(parseInt(hover, 10), true);
            });

            $starContainer.on('mouseout', function() {
                setStarState(currentRating, false);
            });

            function setStarState(rating, isHover = false) {
                $starContainer.find('.review-star').each(function() {
                    const val = parseInt($(this).data('rating'), 10);
                    const $icon = $(this).find('i.fa-star');

                    if (val <= rating) {
                        $icon.addClass('text-yellow-400').removeClass('text-gray-300');
                    } else {
                        $icon.addClass('text-gray-300').removeClass('text-yellow-400');
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

                selectedFiles = selectedFiles.concat([...e.originalEvent.dataTransfer.files]);
                updateInputFiles();
                showPreviews();
            });

            $input.on('change', function(e) {
                selectedFiles = selectedFiles.concat([...e.target.files]);
                updateInputFiles();
                showPreviews();
            });

            function showPreviews() {
                $previewContainer.empty();

                selectedFiles.forEach((file, i) => {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const $wrapper = $('<div class="relative group">');
                        const $img = $('<img>', {
                            src: e.target.result,
                            class: 'w-full h-24 object-cover rounded-lg border border-gray-200'
                        });

                        const $remove = $(`
                            <button type="button" data-index="${i}"
                                class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 
                                text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                &times;
                            </button>
                        `);

                        $remove.on('click', function() {
                            selectedFiles.splice($(this).data('index'), 1);
                            updateInputFiles();
                            showPreviews();
                        });

                        $wrapper.append($img, $remove);
                        $previewContainer.append($wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function updateInputFiles() {
                const dt = new DataTransfer();
                selectedFiles.forEach(f => dt.items.add(f));
                $input[0].files = dt.files;
            }

            // Return Modal
            const $returnModal = $('#return-modal');
            const $typeRadios = $returnModal.find('input[name="type"]');
            const $itemsSection = $returnModal.find('#return-items-section');
            const $itemCheckboxes = $returnModal.find('.item-checkbox');
            const $exchangeNoteSection = $returnModal.find('#exchange-note-section');
            const $exchangeNote = $returnModal.find('#exchange_note');

            $typeRadios.on('change', function () {
                const val = $(this).val();
                if (val === 'full') {
                    $itemsSection.addClass('hidden');
                    $itemCheckboxes.prop('disabled', true);
                    $exchangeNoteSection.addClass('hidden');
                    $exchangeNote.prop('disabled', true);
                } else {
                    $itemsSection.removeClass('hidden');
                    $itemCheckboxes.prop('disabled', false);
                    if (val === 'exchange') {
                        $exchangeNoteSection.removeClass('hidden');
                        $exchangeNote.prop('disabled', false);
                    } else {
                        $exchangeNoteSection.addClass('hidden');
                        $exchangeNote.prop('disabled', true);
                    }
                }
            });

            $('#open-return-modal').on('click', function () {
                $('input[name="type"][value="full"]').prop('checked', true);
                $itemsSection.addClass('hidden');
                $itemCheckboxes.prop('disabled', true).prop('checked', false);
                $exchangeNoteSection.addClass('hidden');
                $exchangeNote.prop('disabled', true);
                $returnModal.removeClass('hidden');
            });
            $('#close-return-modal, #cancel-return').on('click', function () {
                $returnModal.addClass('hidden');
            });
            $returnModal.on('click', function (e) {
                if (e.target === this) $returnModal.addClass('hidden');
            });
        });
    </script>
@endpush
