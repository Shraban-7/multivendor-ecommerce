@extends('frontend.layouts.app')
@section('title', 'Cart Details')

@section('content')
    <main class="pb-5 sm:pb-10">
        <div class="grid gap-6 xl:gap-8 2xl:gap-12 lg:grid-cols-3">
            <!-- Cart Items Main Section -->
            <div class="lg:col-span-2">
                <!-- Cart Items Container -->
                <div id="cart-wrapper" class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                    @if ($carts->isEmpty())
                        <div class="py-16 text-center">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fa-regular fa-face-frown text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-600 mb-2">Your cart is empty</p>
                            <p class="text-gray-500 mb-6">Add some items to get started</p>
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                Continue Shopping
                            </a>
                        </div>
                    @else
                        @foreach ($carts as $sellerId => $cartGroup)
                            @php
                                $seller = \App\Models\Seller::find($sellerId);
                                $shippingCharge = $seller?->shipping_cost;
                                $sellerName = $seller ? $seller->business_name : '';
                            @endphp
                            <!-- Store/Seller Header with Select All for this seller -->
                            <div class="mb-6 seller-section">
                                @if ($seller)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-4">
                                        <div class="flex items-center gap-3">
                                            <label for="selectSeller{{ $sellerId }}"
                                                class="flex items-center cursor-pointer">
                                                <div class="relative">
                                                    <input type="checkbox" name="seller_id"
                                                        id="selectSeller{{ $sellerId }}"
                                                        class="hidden form-checkbox seller-checkbox peer"
                                                        data-seller-id="{{ $sellerId }}"
                                                        data-shipping="{{ $shippingCharge }}" value="{{ $sellerId }}" />

                                                    <div
                                                        class="w-6 h-6 flex items-center justify-center border-2 border-gray-300 rounded-md
                                                     peer-checked:bg-primary peer-checked:border-primary transition-colors">
                                                        <i class="fas fa-check text-white hidden peer-checked:block"></i>
                                                    </div>
                                                </div>

                                            </label>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $sellerName }}</p>
                                                <p class="text-sm text-gray-500">
                                                    <span class="seller-count" data-seller-id="{{ $sellerId }}">0</span>
                                                    of {{ count($cartGroup->flatMap->cart_items) }} items selected
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fa-solid fa-store mr-2"></i>
                                            <span>Store</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Items for this seller -->
                                <div class="seller-items seller-{{ $sellerId }} space-y-4">
                                    @foreach ($cartGroup as $key => $cart)
                                        @foreach ($cart->cart_items as $item)
                                            @php
                                                $stock = $item->variant->stock_in - $item->variant->stock_out;
                                                $vat_amount =0;
                                                $vat_amount += floatval(($item->product->vat_percent * $item->price) / 100);
                                            @endphp
                                            <div class="p-4 bg-white border border-gray-200 rounded-lg cart-item"
                                                data-price="{{ $item->original_price }}"
                                                data-seller-id="{{ $sellerId }}"
                                                data-discounted-price="{{ $item->price }}" data-id="{{ $item->id }}"
                                                data-discount="{{ $item->product->discount }}" data-vat="{{ $vat_amount }}">
                                                <div class="flex gap-4">
                                                    <!-- Item Checkbox -->
                                                    <div class="flex items-start pt-2">
                                                        <input type="checkbox" id="item{{ $key }}"
                                                            class="hidden form-checkbox item-checkbox peer/item{{ $key }}"
                                                            data-item-id="{{ $key }}"
                                                            data-seller-id="{{ $sellerId }}" />
                                                    </div>

                                                    <!-- Item Image -->
                                                    <div
                                                        class="flex-shrink-0 w-20 h-20 overflow-hidden rounded-md sm:w-24 sm:h-24">
                                                        <a href="{{ route('products.details', $item->product->slug) }}">
                                                            <img src="{{ storage_url($item->product->thumbnail) }}"
                                                                alt="Product" class="object-cover w-full h-full" />
                                                        </a>
                                                    </div>

                                                    <!-- Item Content -->
                                                    <div class="flex flex-col flex-1">
                                                        <div class="flex justify-between">
                                                            <!-- Title and details -->
                                                            <div class="flex-1 pr-4">
                                                                <h2
                                                                    class="text-sm font-medium text-gray-900 line-clamp-2 sm:text-base">
                                                                    {{ $item->product->name }}
                                                                </h2>

                                                                @if ($item->product_variant_id && $item->variant && $item->variant->option_values)
                                                                    <div class="mt-1 text-xs text-gray-500">
                                                                        @foreach ($item->variant->option_values as $value)
                                                                            <span class="mr-2">
                                                                                {{ $value->option->name ?? '' }}:
                                                                                {{ $value->value }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Delete button -->
                                                            <button type="button" data-id="{{ $item->id }}"
                                                                class="delete-btn text-gray-400 hover:text-red-500 transition-colors">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Prices & Quantity Controls -->
                                                        <div class="flex items-center justify-between mt-4">
                                                            <!-- Price -->
                                                            <div class="flex items-center cart-item gap-2"
                                                                id="cart-item-{{ $item->id }}">
                                                                @if (!empty($item->discounted_price))
                                                                    <!-- Discounted item -->
                                                                    <span
                                                                        class="current-price text-lg font-bold text-primary">
                                                                        {{ money($item->price) }}
                                                                    </span>
                                                                    <span class="text-gray-500 line-through text-sm">
                                                                        {{ money($item->variant->selling_price) }}
                                                                    </span>
                                                                @else
                                                                    <!-- Regular item -->
                                                                    <span
                                                                        class="current-price text-lg font-bold text-primary">
                                                                        {{ money($item->price) }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <!-- Quantity controls -->
                                                            <div class="quantity-controls" data-id="{{ $item->id }}"
                                                                data-stock="{{ $stock }}">
                                                                <div
                                                                    class="flex items-center h-9 rounded-lg border border-gray-300 overflow-hidden">
                                                                    <input type="hidden" class="product-id"
                                                                        value="{{ $key }}">
                                                                    <input type="hidden" class="variant-sku"
                                                                        value="{{ $item->variant?->sku }}">

                                                                    <button type="button"
                                                                        class="decrease-qty flex items-center justify-center w-9 h-full text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                                                                        <i class="fa-solid fa-minus text-xs"></i>
                                                                    </button>

                                                                    <input readonly type="text"
                                                                        value="{{ $item->quantity }}" min="1"
                                                                        class="quantity-input w-12 h-full text-center border-0 quantity-input text-gray-900 focus:ring-0" />

                                                                    <button type="button"
                                                                        class="increase-qty flex items-center justify-center w-9 h-full text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                                                                        <i class="fa-solid fa-plus text-xs"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Recommendations section -->
                <div class="pt-10 mt-10 border-t border-gray-200">
                    <h2 class="mb-6 text-xl font-semibold text-gray-900">
                        You May Also Like
                    </h2>

                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @include('frontend.partials.product-card-load', ['products' => $products])
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
                        <h2 class="mb-5 text-xl font-semibold text-gray-900">
                            Order Summary
                        </h2>

                        <div class="space-y-4">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span id="itemsTotal" class="text-gray-600">{{ money($grand_total) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">VAT:</span>
                                    <span id="itemVat" class="text-gray-600">+{{ money(0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping Fee:</span>
                                    <span id="shippingCharge" class="text-gray-600">+{{ money(0) }}</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-dashed border-gray-300">
                                <div class="flex justify-between text-lg">
                                    <span>Total (<span
                                            id="selectedItemsCount">{{ $total_products_count }}</span> Items)</span>
                                    <span id="estimatedTotal"
                                        class="text-xl text-gray-900">{{ money($sub_total) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <a href="{{ route('orders.checkout') }}" id="checkoutLink" class="block">
                                <button id="checkoutBtn" type="button"
                                    class="w-full py-3 bg-primary text-white text-sm font-semibold rounded-md hover:bg-primary/90 focus:ring-2 focus:ring-primary/40 transition">
                                    Checkout (0)
                                </button>
                            </a>

                            {{-- <button type="button"
                                class="flex items-center justify-center w-full gap-2 py-3 font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                Express checkout with
                                <img src="{{ asset('assets/frontend/images/cart-paypal.png') }}" alt="PayPal"
                                    class="h-6" />
                            </button> --}}
                        </div>
                    </div>

                    <!-- Additional information -->
                    <div class="p-5 mt-5 bg-white border border-gray-200 rounded-xl shadow-sm">
                        <div class="space-y-5 text-sm text-gray-600">
                            <!-- Security info -->
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 text-green-500">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <p>You will not be charged until you review this order on the next page</p>
                            </div>

                            <!-- Protection info -->
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 text-green-500">
                                    <svg width="18" height="20" viewBox="0 0 22 26" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.82462 0.150834C10.2917 -0.0243682 10.8022 -0.0475726 11.2833 0.0845311L11.4869 0.150834L19.7747 3.25877C20.1948 3.41626 20.5615 3.68983 20.8321 4.04763C21.1027 4.40543 21.2661 4.83275 21.3033 5.27981L21.3115 5.47635V11.826C21.3113 13.7443 20.7932 15.627 19.8119 17.2754C18.8306 18.9237 17.4225 20.2766 15.7362 21.1912L15.4213 21.357L11.4502 23.3413C11.2317 23.4507 10.9929 23.5138 10.7488 23.5266C10.5047 23.5394 10.2607 23.5018 10.0318 23.4159L9.86133 23.3425L5.89027 21.3558C4.17423 20.4978 2.72169 19.1923 1.68598 17.5773C0.650278 15.9623 0.0698315 14.0976 0.00592001 12.18L0 11.8248V5.47754C6.88487e-06 5.02896 0.127427 4.58962 0.367427 4.21065C0.607427 3.83168 0.950134 3.52867 1.35565 3.33691L1.5368 3.25995L9.82462 0.150834ZM9.64111 7.20377L7.28381 11.1322C7.17483 11.3137 7.11597 11.5208 7.11325 11.7325C7.11052 11.9441 7.16402 12.1527 7.26828 12.3369C7.37255 12.5211 7.52384 12.6743 7.70671 12.781C7.88958 12.8876 8.09746 12.9437 8.30913 12.9437H10.9328L9.64111 15.0973C9.49026 15.3659 9.45006 15.6828 9.52903 15.9805C9.60799 16.2783 9.79991 16.5336 10.064 16.6921C10.3282 16.8507 10.6437 16.9001 10.9436 16.8298C11.2436 16.7595 11.5043 16.5751 11.6704 16.3156L14.0277 12.3872C14.1367 12.2057 14.1956 11.9986 14.1983 11.787C14.201 11.5753 14.1475 11.3667 14.0433 11.1825C13.939 10.9983 13.7877 10.8451 13.6048 10.7385C13.422 10.6319 13.2141 10.5757 13.0024 10.5757H10.3787L11.6716 8.42208C11.8332 8.15266 11.8811 7.8301 11.8048 7.52535C11.7286 7.2206 11.5343 6.95863 11.2649 6.79707C10.9955 6.63552 10.6729 6.5876 10.3682 6.66387C10.0634 6.74014 9.80266 6.93435 9.64111 7.20377Z"
                                            fill="currentColor" />
                                    </svg>
                                </div>
                                <p>Safe Payment Options</p>
                            </div>

                            <!-- Protection policy -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">{{ app_name() }} Purchase Protection</h4>
                                <p class="mb-2">Shop confidently knowing that if something goes wrong, we've got your
                                    back.</p>
                                <a href="#"
                                    class="text-primary hover:text-primary-dark font-medium inline-flex items-center gap-1 transition-colors">
                                    Learn More <i class="fa-solid fa-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.seller-checkbox').on('change', function() {
                const sellerId = $(this).data('seller-id');
                const isChecked = $(this).prop('checked');

                $('.seller-checkbox').not(this).prop('checked', false);

                updateSellerSelection();
            });

            function updateSellerSelection() {
                $('.item-checkbox').prop('checked', false);
                updateCounts();
                updateOrderSummary();
            }

            $('.seller-checkbox').on('change', function() {
                const sellerId = $(this).data('seller-id');
                const selectedCount = $('.item-checkbox:checked').length;

                $('#checkoutBtn')
                    .attr('data-seller-id', sellerId)
                    .find('span:first-child').text(`Checkout (${selectedCount})`);

                const checkoutRoute = "{{ route('orders.checkout') }}";
                $('#checkoutLink').attr('href', `${checkoutRoute}?seller_id=${sellerId}`);
            });

            $('.item-checkbox').on('change', function() {
                const selectedSellerId = $('.seller-checkbox:checked').data('seller-id');
                const selectedCount = $('.item-checkbox:checked').length;

                $('#checkoutBtn')
                    .attr('data-seller-id', selectedSellerId)
                    .find('span:first-child').text(`Checkout (${selectedCount})`);

                const checkoutRoute = "{{ route('orders.checkout') }}";
                $('#checkoutLink').attr('href', `${checkoutRoute}?seller_id=${selectedSellerId}`);
            });

            $('.increase-qty, .decrease-qty').click(debounce(function() {
                var cartItem = $(this).closest('.quantity-controls');
                var cartItemId = cartItem.data('id');
                var cartItemStock = cartItem.data('stock');

                let quantityInput = cartItem.find('.quantity-input');
                let currentQuantity = parseInt(quantityInput.val()) || 1;
                let formattedQuantity = currentQuantity.toString().padStart(2, "0");

                if ($(this).hasClass('increase-qty')) {
                    if (currentQuantity < cartItemStock) {
                        currentQuantity++;
                    } else {
                        currentQuantity = cartItemStock;
                        toastr.warning("Not enough stock!");
                        return false;
                    }
                } else if ($(this).hasClass('decrease-qty') && currentQuantity > 1) {
                    currentQuantity--;
                }

                updateCartQuantity(cartItemId, currentQuantity, quantityInput);
            }, 1000));

            $('.delete-btn').click(function() {
                var cartItemId = $(this).data('id');
                deleteCartItem(cartItemId);
            });

            const selectAllCheckbox = $('#selectAll');
            const sellerCheckboxes = $('.seller-checkbox');
            const itemCheckboxes = $('.item-checkbox');

            selectAllCheckbox.on('change', function() {
                const isChecked = $(this).prop('checked');
                sellerCheckboxes.prop('checked', isChecked);
                itemCheckboxes.prop('checked', isChecked);
                updateCounts();
                updateOrderSummary();
            });

            sellerCheckboxes.on('change', function() {
                const sellerId = $(this).data('seller-id');
                const isChecked = $(this).prop('checked');
                $(`.item-checkbox[data-seller-id="${sellerId}"]`).prop('checked',
                    isChecked);
                updateSellerCheckboxes();
                updateCounts();
                updateOrderSummary();
            });

            itemCheckboxes.on('change', function() {
                const sellerId = $(this).data('seller-id');
                updateSellerCheckbox(sellerId);
                updateSelectAllCheckbox();
                updateCounts();
                updateOrderSummary();
            });

            function updateCartQuantity(cartItemId, quantity, input) {
                $.ajax({
                    url: "{{ route('cart.update') }}",
                    type: "POST",
                    data: {
                        id: cartItemId,
                        quantity: quantity,
                    },
                    success: function(response) {
                        input.val(quantity);

                        if (response.success) {
                            updateOrderTotals(response);
                            toastr.success(response.message);
                            updateCartData();
                            updateOrderSummary();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while updating the cart.');
                    }
                });
            }

            function deleteCartItem(cartItemId) {
                $.ajax({
                    url: "{{ route('cart.delete') }}",
                    type: "POST",
                    data: {
                        id: cartItemId,
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while deleting the product.');
                    }
                });
            }

            function updateOrderTotals(response) {
                $('#itemsTotal').text(response.order_total);
                $('#estimatedTotal').text(response.order_subtotal);
                $('#itemDiscount').text('-' + response.discount);
                $('#selectedItemsCount').text(response.total_products_count);

                const checkoutBtn = $('#checkoutBtn');
                checkoutBtn.html(`Checkout (${response.total_products_count})`);

                if (parseInt(response.total_products_count) === 0) {
                    checkoutBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                } else {
                    checkoutBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                }
            }

            function updateSellerCheckbox(sellerId) {
                const sellerItems = $(`.item-checkbox[data-seller-id="${sellerId}"]`);
                const allSellerItemsChecked = sellerItems.length === sellerItems.filter(':checked').length;
                $(`.seller-checkbox[data-seller-id="${sellerId}"]`).prop('checked',
                    allSellerItemsChecked);
            }

            function updateSellerCheckboxes() {
                const allSellersSelected = sellerCheckboxes.length === $('.seller-checkbox:checked').length;
                selectAllCheckbox.prop('checked', allSellersSelected);
            }

            function updateSelectAllCheckbox() {
                const allItemsChecked = itemCheckboxes.length === itemCheckboxes.filter(':checked').length;
                selectAllCheckbox.prop('checked', allItemsChecked);
            }

            function updateCounts() {
                const selectedItems = $('.item-checkbox:checked');
                $('#selectedCount').text(selectedItems.length);

                $('.seller-count').each(function() {
                    const sellerId = $(this).data('seller-id');
                    const sellerItems = $(
                        `.item-checkbox[data-seller-id="${sellerId}"]:checked`);
                    $(this).text(sellerItems.length);
                });
            }

            function updateOrderSummary() {
                let discountedTotal = 0;
                let originalTotal = 0;
                let selectedCount = 0;
                let totalVat = 0;
                let sellerShippingCharge = $('.seller-checkbox:checked').data('shipping') || 0;
                $('.item-checkbox:checked').each(function() {

                    const cartItem = $(this).closest('.cart-item');
                    if (cartItem.length) {
                        const price = parseFloat(cartItem.data('price'));
                        const discountedPrice = parseFloat(cartItem.data('discounted-price'));
                        const discount = parseFloat(cartItem.data('discount'));
                        const quantity = parseInt(cartItem.find('.quantity-input').val(), 10);
                        const vat = parseFloat(cartItem.data('vat'));

                        discountedTotal += discountedPrice * quantity;
                        originalTotal += price * quantity;
                        totalVat += vat * quantity;
                        selectedCount += quantity;
                    }
                });

                const discount = originalTotal - discountedTotal;

                $('#itemsTotal').text(formatCurrency(discountedTotal));
                $('#itemVat').text('+' + formatCurrency(totalVat));
                let total = parseFloat(discountedTotal) + parseFloat(sellerShippingCharge)+parseFloat(totalVat);
                $('#estimatedTotal').text(formatCurrency(total));
                $('#selectedItemsCount').text(selectedCount);
                $('#shippingCharge').text('+' + formatCurrency(sellerShippingCharge));

                const checkoutBtn = $('#checkoutBtn');
                checkoutBtn.html(`Checkout (${selectedCount})`);

                if (selectedCount === 0) {
                    checkoutBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                } else {
                    checkoutBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                }
            }

            function updateCartData() {
                $.ajax({
                    url: "{{ route('cart.data') }}",
                    type: "GET",
                    success: function(data) {
                        $('#cartCount').text(data.cartCount);
                        $('#totalPrice').text(data.totalPrice);
                    },
                    error: function() {
                        toastr.error('Failed to update cart data.');
                    }
                });
            }

            function formatCurrency(amount) {
                amount = parseFloat(amount);
                const formatted = (amount % 1 === 0) ? amount.toFixed(0) : amount.toFixed(2);

                return '৳ ' + formatted.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            updateOrderSummary();
            updateCounts();
        });
    </script>
@endpush
