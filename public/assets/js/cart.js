function updateCartData() {
    $.ajax({
        url: CartRoutes.data,
        type: "GET",
        success: function (data) {
            const el = $('#cartCount');
            el.text(data.cartCount);
            if (data.cartCount > 0) {
                el.removeClass('hidden');
            } else {
                el.addClass('hidden');
            }
            $('#totalPrice').text(data.totalPrice);
        },
        error: function () {
            showErrorToast('Failed to update cart data.');
        }
    });
}

$(document).ready(function () {
    var el = $('#cartCount');
    var count = parseInt(el.text(), 10);
    if (count > 0) {
        el.removeClass('hidden');
    } else {
        el.addClass('hidden');
    }
});

$('body').on('click', '.addToCartBtn', function () {
    var $btn = $(this);
    var originalText = $btn.html();
    var $product_content = $btn.closest("[id^='product-wrapper']");
    var product = $product_content.data("product");

    if (!product) {
        showErrorToast("Product data not found!");
        return;
    }

    var options = product.options || [];
    if (options.length > 0) {
        var selectedOptions = collectSelectedOptions($product_content);
        var allSelected = options.every(function(opt) {
            return selectedOptions[opt.id] !== undefined;
        });

        if (!allSelected) {
            var missing = options.filter(function(opt) {
                return selectedOptions[opt.id] === undefined;
            }).map(function(opt) { return opt.name; }).join(' and ');

            $product_content.find('.variant-error').removeClass('hidden')
                .text('Please select ' + missing + ' before adding to cart.');
            showErrorToast('Please select ' + missing + ' first!');
            return;
        }
    }

    $btn.html(
        `<svg class="animate-spin h-4 w-4 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg> Adding...`
    ).prop('disabled', true);

    var product_id = $btn.data('id');
    var product_price_text = $product_content.find('.product-price').text().replace(/[^0-9.]/g, '');
    var product_price = parseFloat(product_price_text);
    var qtyInput = $product_content.find('.quantity').val() || 1;

    var selectedOptions = collectSelectedOptions($product_content);
    var variant = getSelectedVariant(product, selectedOptions);
    var variantId = variant ? variant.id : null;

    function addToCartRequest() {
        return $.ajax({
            url: CartRoutes.add,
            type: "POST",
            data: {
                product_id: product_id,
                variant_id: variantId,
                quantity: qtyInput,
                price: product_price,
            },
            success: function (data) {
                if (data.success) {
                    showSuccessToast(data.message);
                    updateCartData();

                    if (CurrentRouteName === 'cart.details' &&
                        data.action === 'add_to_cart') {
                        window.location.reload();
                    }
                } else if (data.warning) {
                    showWarningToast(data.warning);
                } else {
                    showErrorToast('Unexpected response!');
                }
            },
            error: async function (xhr) {
                if (xhr.status === 419) {
                    await refreshCsrfToken();
                    addToCartRequest();
                } else if (xhr.status === 401) {
                    showWarningToast(xhr.responseJSON.error);
                    auth.toggleModal(true);
                } else{
                    showErrorToast(xhr.responseJSON.error);
                }
            },
            complete: function () {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    }

    addToCartRequest();
});

$('body').on('click', '.addToCartNoVariant', function () {
    let $btn = $(this);
    let $icon = $btn.find('.icon');
    let $spinner = $btn.find('.spinner');

    $icon.addClass('hidden');
    $spinner.removeClass('hidden');
    $btn.prop('disabled', true);

    let product_id = $btn.data('id');

    function sendRequest() {
        return $.ajax({
            url: CartRoutes.add,
            type: "POST",
            data: {
                product_id: product_id,
                variant_id: null,
                quantity: 1,
            },
            success: function (data) {
                if (data.success) {
                    showSuccessToast(data.message);
                    updateCartData();

                    if (CurrentRouteName === "cart.details" &&
                        data.action === "add_to_cart") {
                        window.location.reload();
                    }
                } else if (data.warning) {
                    showWarningToast(data.warning);
                } else {
                    showErrorToast("Unexpected response!");
                }
            },
            error: async function (xhr) {
                if (xhr.status === 419) {
                    await refreshCsrfToken();
                    return sendRequest();
                }
                if (xhr.status === 401) {
                    showWarningToast(xhr.responseJSON.error);
                    auth.toggleModal(true);
                } else {
                    showErrorToast(xhr.responseJSON.error);
                }
            },
            complete: function () {
                $spinner.addClass('hidden');
                $icon.removeClass('hidden');
                $btn.prop('disabled', false);
            }
        });
    }

    sendRequest();
});

function updateCartQuantity(cartItemId, quantity, input) {
    $.ajax({
        url: CartRoutes.update,
        type: "POST",
        data: {
            id: cartItemId,
            quantity: quantity,
        },
        success: function(response) {
            input.val(quantity);

            if (response.success) {
                if (typeof updateOrderTotals === 'function') {
                    updateOrderTotals(response);
                }
                showSuccessToast(response.message);
                updateCartData();
                if (typeof updateOrderSummary === 'function') {
                    updateOrderSummary();
                }
            } else {
                showErrorToast(response.message);
            }
        },
        error: function() {
            showErrorToast('An error occurred while updating the cart.');
        }
    });
}

function deleteCartItem(cartItemId) {
    $.ajax({
        url: CartRoutes.delete,
        type: "POST",
        data: {
            id: cartItemId,
        },
        success: function(response) {
            if (response.success) {
                showSuccessToast(response.message);
                location.reload();
            } else {
                showErrorToast(response.message);
            }
        },
        error: function() {
            showErrorToast('An error occurred while deleting the product.');
        }
    });
}

$('body').on('click', '.buyNowBtn', function () {
    var $btn = $(this);
    var originalText = $btn.html();
    var $product_content = $btn.closest("[id^='product-wrapper']");
    var product = $product_content.data("product");
    var sellerId = $btn.data('seller');

    if (!product) {
        showErrorToast("Product data not found!");
        return;
    }

    if (!sellerId) {
        showErrorToast("Seller information missing!");
        return;
    }

    var options = product.options || [];
    if (options.length > 0) {
        var selectedOptions = collectSelectedOptions($product_content);
        var allSelected = options.every(function(opt) {
            return selectedOptions[opt.id] !== undefined;
        });

        if (!allSelected) {
            var missing = options.filter(function(opt) {
                return selectedOptions[opt.id] === undefined;
            }).map(function(opt) { return opt.name; }).join(' and ');

            $product_content.find('.variant-error').removeClass('hidden')
                .text('Please select ' + missing + ' before buying.');
            showErrorToast('Please select ' + missing + ' first!');
            return;
        }
    }

    $btn.html(
        `<svg class="animate-spin h-4 w-4 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg> Redirecting...`
    ).prop('disabled', true);

    var product_id = $btn.data('id');
    var qtyInput = $product_content.find('.quantity').val() || 1;
    var selectedOptions = collectSelectedOptions($product_content);
    var variant = getSelectedVariant(product, selectedOptions);
    var variantId = variant ? variant.id : null;

    function buyNowRequest() {
        return $.ajax({
            url: CartRoutes.add,
            type: "POST",
            data: {
                product_id: product_id,
                variant_id: variantId,
                quantity: qtyInput,
            },
            success: function (data) {
                if (data.success) {
                    showSuccessToast(data.message);
                    updateCartData();
                    window.location.href = CheckoutRoute + '?seller_id=' + sellerId;
                } else if (data.warning) {
                    showWarningToast(data.warning);
                    $btn.html(originalText).prop('disabled', false);
                } else {
                    showErrorToast('Unexpected response!');
                    $btn.html(originalText).prop('disabled', false);
                }
            },
            error: async function (xhr) {
                if (xhr.status === 419) {
                    await refreshCsrfToken();
                    return buyNowRequest();
                }
                if (xhr.status === 401) {
                    showWarningToast(xhr.responseJSON.error);
                    auth.toggleModal(true);
                } else {
                    showErrorToast(xhr.responseJSON.error);
                }
                $btn.html(originalText).prop('disabled', false);
            }
        });
    }

    buyNowRequest();
});
