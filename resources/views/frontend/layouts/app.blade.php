<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicons />
    @stack('meta')
    <title>@yield('title') | eCommerce Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    {{-- <link rel="stylesheet" href="{{ asset('assets/libs/toastr/css/toastr.min.css') }}"> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316', // Orange-500
                            600: '#ea580c', // Orange-600
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #f97316;
        }

        /* Hide scrollbar for smooth sliders but keep functionality */
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @stack('header')
</head>

<?php

use Illuminate\Support\Facades\View;

$settings = settings();
$notificationCount = notificationCount();
$isDashboard = View::hasSection('dashboard');
?>

<body class="bg-gray-50 font-sans min-h-screen text-gray-800 antialiased">

    @include('components.frontend.global-loader')

    @include('frontend.layouts.mobile-drawer')

    <div class="hidden">
        <!-- prevents navbar autocomplete -->
        <input type="password" name="password" autocomplete="current-password" ...>
        <input type="text" name="name" autocomplete="name" ...>
        <input type="password" name="password" autocomplete="new-password" ...>
    </div>

    <x-frontend.quickviewModal />

    @include('frontend.layouts.navbar')

    @if (View::hasSection('breadcrumbs'))
        <div>
            @yield('breadcrumbs')
        </div>
    @endif

    @if (!$isDashboard)
        <div class="container mx-auto max-w-7xl px-3 lg:px-4 py-4 min-h-screen mb-20 md:mb-5">
            @yield('content')
        </div>
    @endif

    @if ($isDashboard)
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-10" id="main">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                @include('frontend.layouts.sidebar')
                <section class="md:col-span-3 space-y-6">
                    @yield('dashboard')
                </section>
            </div>
        </main>
    @endif

    @include('frontend.layouts.footer')

    @include('frontend.layouts.mobile-nav')

    <button id="backToTop"
        class="hidden fixed bottom-20 md:bottom-8 right-4 md:right-8 bg-primary-600 text-white w-10 h-10 md:w-12 md:h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-700 transition z-40 opacity-0 translate-y-10 transition-all duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>

    <x-auth-modal />
    <x-frontend.custom-toastr />

    <script>
        window.toggleModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            const isHidden = modal.classList.contains("hidden");

            if (isHidden) {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
                document.body.style.overflow = "hidden";
            } else {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
                document.body.style.overflow = "auto";
            }
        };
    </script>

    {{-- <script src="{{ asset('assets/libs/toastr/js/toastr.min.js') }}"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const quickViewModal = document.getElementById('quickViewModal');
            // const openQuickViewBtns = document.querySelectorAll('.btn-quickview');
            const closeQuickViewBtns = document.querySelectorAll('.close-quickview');
            const quickViewContent = document.getElementById('quickViewContent');

            function toggleQuickView(show) {
                if (show) {
                    quickViewModal.classList.remove('hidden');
                    // Small delay to allow display:block to apply before changing opacity for transition
                    setTimeout(() => quickViewModal.style.opacity = '1', 10);
                } else {
                    quickViewModal.style.opacity = '0';
                    setTimeout(() => quickViewModal.classList.add('hidden'), 300);
                }
            }

            $(document).on('click', '.btn-quickview', function(e) {
                e.preventDefault();

                const $btn = $(this);
                const slug = $btn.data('slug');
                const delay = 800;

                $btn.find('.icon').addClass('hidden');
                $btn.find('.spinner').removeClass('hidden');
                $btn.prop('disabled', true);

                $('#quickViewModal .quickview-content').html('');

                setTimeout(() => {
                    $.ajax({
                        url: `/products/${slug}/quick-view`,
                        type: 'GET',

                        success: function(response) {
                            $('#quickViewModal .quickview-content').html(response);
                            toggleQuickView(true);
                        },

                        complete: function() {
                            $btn.find('.spinner').addClass('hidden');
                            $btn.find('.icon').removeClass('hidden');
                            $btn.prop('disabled', false);
                        }
                    });
                }, delay);
            });



            // openQuickViewBtns.forEach(btn => {
            //     btn.addEventListener('click', (e) => {
            //         e.preventDefault();
            //         e.stopPropagation();
            //         toggleQuickView(true);
            //     });
            // });

            closeQuickViewBtns.forEach(btn => {
                btn.addEventListener('click', () => toggleQuickView(false));
            });

            // Close on click outside
            quickViewModal.addEventListener('click', (e) => {
                if (e.target === quickViewModal) {
                    toggleQuickView(false);
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('#quickViewCloseBtn')) {
                    toggleQuickView(false);
                }
            });

            // --- Back to Top Button ---
            const backToTopBtn = document.getElementById('backToTop');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 100) {
                    backToTopBtn.classList.remove('hidden');
                    setTimeout(() => {
                        backToTopBtn.classList.remove('opacity-0', 'translate-y-10');
                    }, 10);
                } else {
                    backToTopBtn.classList.add('opacity-0', 'translate-y-10');
                    // Wait for transition to finish before hiding
                    setTimeout(() => backToTopBtn.classList.add('hidden'), 300);
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <script>
        function debounce(func, delay) {
            let timer;
            return function(...args) {
                const context = this;
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(context, args), delay);
            };
        }
        $(function() {
            function refreshCsrfToken() {
                return $.get("{{ route('refresh.csrf') }}").then(function(data) {
                    const newToken = data.token;
                    $('meta[name="csrf-token"]').attr('content', newToken);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': newToken
                        }
                    });

                    return newToken;
                });
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const $sidebarToggle = $("#sidebar-toggle");
            const $mobileSidebar = $("#mobile-sidebar");
            const $sidebarBackdrop = $("#sidebar-backdrop");

            $sidebarToggle.on("click", function() {
                const isOpen = !$mobileSidebar.hasClass("-translate-x-full");
                if (isOpen) {
                    $mobileSidebar.addClass("-translate-x-full");
                    $sidebarBackdrop.addClass("hidden");
                } else {
                    $mobileSidebar.removeClass("-translate-x-full");
                    $sidebarBackdrop.removeClass("hidden");
                }
            });

            $sidebarBackdrop.on("click", function() {
                $mobileSidebar.addClass("-translate-x-full");
                $sidebarBackdrop.addClass("hidden");
            });

            window.togglePassword = function(inputId, button) {
                const $input = $("#" + inputId);
                const $button = $(button);
                const $eye = $button.find(".fa-eye");
                const $eyeSlash = $button.find(".fa-eye-slash");

                if ($input.attr("type") === "password") {
                    $input.attr("type", "text");
                    $eye.hide();
                    $eyeSlash.show();
                } else {
                    $input.attr("type", "password");
                    $eye.show();
                    $eyeSlash.hide();
                }
            };

            $('body').on('click', '.addToCartBtn', function() {
                var $btn = $(this);
                var originalText = $btn.html();
                $btn.html(
                    `<svg class="animate-spin h-4 w-4 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg> Adding...`
                ).prop('disabled', true);

                var product_id = $btn.data('id');
                var $product_content = $btn.closest("[id^='product-wrapper']");
                var product = $product_content.data("product");

                if (!product) {
                    showErrorToast("Product data not found!");
                    $btn.html(originalText).prop('disabled', false);
                    return;
                }

                const selectedOptions = collectSelectedOptions($product_content);
                const variant = getSelectedVariant(product, selectedOptions);
                var variantId = variant ? variant.id : null;

                var product_price_text = $product_content.find('.product-price').text().replace(/[^0-9.]/g,
                    '');
                var product_price = parseFloat(product_price_text);
                var qtyInput = $product_content.find('.quantity').val() || 1;

                function addToCartRequest() {
                    return $.ajax({
                        url: "{{ route('cart.add') }}",
                        type: "POST",
                        data: {
                            product_id: product_id,
                            variant_id: variantId,
                            quantity: qtyInput,
                            price: product_price,
                        },
                        success: function(data) {
                            if (data.success) {
                                showSuccessToast(data.message);
                                updateCartData();

                                if ("{{ Route::currentRouteName() }}" === 'cart.details' &&
                                    data.action === 'add_to_cart') {
                                    window.location.reload();
                                }
                            } else if (data.error) {
                                showErrorToast(data.error);
                            } else {
                                showErrorToast('Unexpected response!');
                            }
                        },
                        error: async function(xhr) {
                            if (xhr.status === 419) {
                                await refreshCsrfToken();
                                addToCartRequest();
                            } else if (xhr.status === 401) {
                                showWarningToast(xhr.responseJSON.error);
                                auth.toggleModal(true);
                            } else if (xhr.status === 403) {
                                showWarningToast(xhr.responseJSON.error);
                            } else {
                                showErrorToast('Something went wrong!');
                            }
                        },
                        complete: function() {
                            $btn.html(originalText).prop('disabled', false);
                        }
                    });
                }

                addToCartRequest();
            });

            $('.buyNowBtn').click(function() {
                var product_id = $(this).data('id');
                var seller_id = $(this).data('seller');
                var wishlistId = $(this).data('wishlist-id');
                var variantSku = $('#variantSku').val();
                var product_price_text = $('.product-price').text().replace(/[^0-9.]/g, '');
                var product_price = parseFloat(product_price_text);
                var $row = $(this).closest('.grid');

                let selectedOptionIds = [];

                $('.variant-option:checked').each(function() {
                    selectedOptionIds.push($(this).val());
                });

                if (!product_id) {
                    alert("No Product Selected!");
                    return;
                }
                var qtyInput = $('#qtyInput' + product_id).val();

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        product_id: product_id,
                        seller_id: seller_id,
                        variant_sku: variantSku,
                        quantity: qtyInput,
                        price: product_price,
                        option_ids: selectedOptionIds,
                    },
                    success: function(data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('home') }}";
                        } else if (data.success) {
                            $('button[data-modal-hide="quick-view-modal-' + product_id + '"]')
                                .trigger('click');
                            $row.fadeOut(300, function() {
                                $(this).remove();
                            });
                            showSuccessToast(data.message);
                            updateCartData();

                            window.location.href = "{{ route('orders.checkout') }}" +
                                "?seller_id=" + seller_id;
                        } else {
                            showErrorToast(data.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('home') }}";
                        } else {
                            showErrorToast('Something went wrong!');
                        }
                    }
                });
            });

            $('.wishlistBtn').click(function() {
                var product_id = $(this).data('id');
                if (!product_id) {
                    alert("No Product Selected!");
                    return;
                }

                $.ajax({
                    url: "{{ route('wishlist.store') }}",
                    type: "POST",
                    data: {
                        product_id: product_id,
                    },
                    success: function(data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('home') }}";
                        } else if (data.success) {
                            showSuccessToast(data.message);
                            updateWishlistData();
                        } else {
                            showErrorToast(data.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('home') }}";
                        } else {
                            showErrorToast('Something went wrong!');
                        }
                    }
                });
            });

            $('.wishlistRemoveBtn').on('click', function() {
                var wishlistId = $(this).data('id');
                var $row = $(this).closest('.grid');
                const wishlistDeleteRoute = "{{ route('wishlist.delete', ['wishlist' => '__id__']) }}";
                var url = wishlistDeleteRoute.replace('__id__', wishlistId);
                if (!wishlistId) return;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        wishlist: wishlistId
                    },
                    success: function(response) {
                        if (response.success) {
                            $row.fadeOut(300, function() {
                                $(this).remove();
                                showSuccessToast(response.message);
                                updateWishlistData();
                            });
                        } else {
                            alert(response.message || 'Failed to remove item');
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });

            function updateCartData() {
                $.ajax({
                    url: "{{ route('cart.data') }}",
                    type: "GET",
                    success: function(data) {
                        if (data.cartCount > 0) {
                            $('#cartCount').removeClass('hidden')
                            $('#totalPrice').removeClass('hidden')
                        }
                        $('#cartCount').text(data.cartCount);
                        $('#totalPrice').text(data.totalPrice);
                    },
                    error: function() {
                        showErrorToast('Failed to update cart data.');
                    }
                });
            }

            function updateWishlistData() {
                $.ajax({
                    url: "{{ route('wishlist.data') }}",
                    type: "GET",
                    success: function(data) {
                        if (data.wishlistCount > 0) {
                            $('#wishlistCount').removeClass('hidden');
                        }

                        $('#wishlistCount').text(data.wishlistCount);
                    },
                    error: function() {
                        showErrorToast('Failed to update wishlist data.');
                    }
                });
            }


            $("[id^='product-wrapper']").each(function() {
                initDefaultVariant($(this));
            });

            function storageURL(fileName) {
                return "{{ url('/') }}" + '/storage/' + fileName;
            }

            function formatPrice(price, quantity) {
                const total = Math.round(price * quantity * 100) / 100;
                return total.toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateProductUI($wrapper, variant = null, quantity, isInitialLoad = false) {
                const $qtyEl = $wrapper.find("input.quantity");
                const $mainImage = $wrapper.find(".main-product-image");
                const $priceEl = $wrapper.find(".product-price");
                const $originalPriceEl = $wrapper.find(".original-price");
                const $skuEl = $wrapper.find(".sku-text");
                const $stockEl = $wrapper.find(".stock-text");
                const $availability = $wrapper.find(".availability-text");
                const $variantError = $wrapper.find(".variant-error");
                const $addToCartBtn = $wrapper.find(".addToCartBtn");
                const $variantIdInput = $wrapper.find("input.variantId");
                const product = $wrapper.data("product");

                if (variant) {
                    const basePrice = parseFloat(variant.price) || 0;
                    const discounted = variant.discounted_price !== null ? parseFloat(variant.discounted_price) :
                        null;

                    const price = discounted && discounted > 0 ? discounted : basePrice;

                    if (!discounted || discounted == 0) {
                        $originalPriceEl.addClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    } else {
                        $originalPriceEl.removeClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(discounted, quantity)}`);
                        $originalPriceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    }

                    $skuEl.text(variant.sku);
                    $stockEl.text(variant.stock);
                    $availability.text(variant.stock > 0 ? "In Stock" : "Out of Stock");
                    $variantIdInput.val(variant.id);
                    $qtyEl.val(quantity);
                    $qtyEl.attr('value', parseInt($qtyEl.val()));
                    $variantError.addClass("hidden");

                    $addToCartBtn.prop("disabled", variant.stock <= 0).toggleClass("opacity-50 cursor-not-allowed",
                        variant.stock <= 0);

                    if (!isInitialLoad && variant.image) {
                        const imageUrl = storageURL(variant.image);
                        $mainImage.attr('src', imageUrl);
                    }

                } else {
                    const basePrice = parseFloat(product.price) || 0;
                    const discounted = product.discounted_price !== null ? parseFloat(product.discounted_price) :
                        null;
                    const price = discounted && discounted > 0 ? discounted : basePrice;

                    if (!discounted || discounted == 0) {
                        $originalPriceEl.addClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    } else {
                        $originalPriceEl.removeClass('hidden');
                        $priceEl.text(`৳ ${formatPrice(discounted, quantity)}`);
                        $originalPriceEl.text(`৳ ${formatPrice(basePrice, quantity)}`);
                    }

                    $skuEl.text(product.sku || "N/A");
                    $stockEl.text(product.stock || 0);
                    $availability.text((product.stock || 0) > 0 ? "In Stock" : "Out of Stock");
                    $qtyEl.val(quantity);
                    $qtyEl.attr('value', parseInt($qtyEl.val()));
                    $variantIdInput.val("");
                    $variantError.addClass("hidden");
                    $addToCartBtn.prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                }
            }

            function getSelectedVariant(product, selectedOptions) {
                const selectedIds = Object.values(selectedOptions).map(Number).sort();
                return (product.variants || []).find(v =>
                    JSON.stringify([...v.value_ids].sort()) === JSON.stringify(selectedIds)
                );
            }

            $(document).on('click', '.option-value-btn', function() {
                const $btn = $(this);
                $btn.closest('[data-option-id]')
                    .find('.option-value-btn')
                    .removeClass('active-option bg-primary/10 text-primary-500 border-primary-500')
                    .addClass('bg-gray-50 text-gray-700 border-gray-300');
                $btn
                    .addClass('active-option bg-primary/10 text-primary-500 border-primary-500')
                    .removeClass('bg-gray-50 text-gray-700 border-gray-300');
            });

            function collectSelectedOptions($wrapper) {
                const selectedOptions = {};
                $wrapper.find(".option-value-btn.active-option")
                    .each(function() {
                        const $btn = $(this);
                        const optId = $btn.data("option-id");
                        const valId = $btn.data("value-id");
                        selectedOptions[optId] = parseInt(valId);
                    });

                return selectedOptions;
            }

            $(document).on("click", ".option-value-btn", function() {
                const $btn = $(this);
                const $wrapper = $btn.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                const optId = $btn.data("option-id");
                const valId = $btn.data("value-id");

                $wrapper.find(`.option-value-btn[data-option-id="${optId}"]`).removeClass(
                    "text-primary-500 border-primary-500"
                ).addClass("bg-white text-gray-800 border-gray-300");

                $btn.removeClass("bg-white text-gray-800 border-gray-300").addClass(
                    "text-primary-500 border-primary-500"
                );

                const selectedOptions = collectSelectedOptions($wrapper);

                const variant = getSelectedVariant(product, selectedOptions);

                const quantity = parseInt($wrapper.find(".quantity").val()) || 1;

                updateProductUI($wrapper, variant, quantity);
            });

            $(document).on("click", ".thumb-img", function() {
                const $img = $(this);
                const full = $img.data("full");
                const $wrapper = $img.closest("[id^='product-wrapper']");
                const $mainImage = $wrapper.find(".main-product-image");
                const $thumbWrapper = $wrapper.find(".thumbnailWrapper");

                $mainImage.attr("src", full);
                $thumbWrapper.find(".slide-thumb").removeClass("border-primary").addClass(
                    "border-gray-200");
                $img.closest(".slide-thumb").addClass("border-primary").removeClass("border-gray-200");
            });

            $(document).on("click", ".increaseBtn, .decreaseBtn", debounce(function() {
                const $btn = $(this);
                const $wrapper = $btn.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                const $qtyInput = $wrapper.find("input.quantity");
                let quantity = parseInt($qtyInput.val()) || 1;

                const selectedOptions = collectSelectedOptions($wrapper);
                const variant = getSelectedVariant(product, selectedOptions);

                const availableStock = variant ? variant.stock : product.stock;

                if ($btn.hasClass("increaseBtn")) {
                    if (quantity < availableStock) quantity += 1;
                    else {
                        quantity = availableStock;
                        showWarningToast("Not enough stock!");
                    }
                } else {
                    quantity -= 1;
                    if (quantity < 1) quantity = 1;
                }

                updateProductUI($wrapper, variant, quantity);
            }, 300));

            $(document).on("input", ".quantity", function() {
                const $input = $(this);
                const $wrapper = $input.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                let quantity = parseInt($input.val()) || 1;
                quantity = quantity > 0 ? quantity : 1;

                const selectedOptions = collectSelectedOptions($wrapper);
                const variant = getSelectedVariant(product, selectedOptions);

                updateProductUI($wrapper, variant, quantity);
            });

            function initDefaultVariant($wrapper) {
                if ($wrapper.data('variant-initialized')) return;

                const product = $wrapper.data("product");
                if (!product?.variants?.length) return;

                const defaultVariant = product.variants.find(v => v.is_default);
                if (!defaultVariant) return;

                defaultVariant.value_ids.forEach(valId => {
                    const $btn = $wrapper.find(`.option-value-btn[data-value-id="${valId}"]`);
                    const optId = $btn.data("option-id");

                    $wrapper.find(`.option-value-btn[data-option-id="${optId}"]`)
                        .removeClass("bg-primary/10 text-primary border-primary")
                        .addClass("bg-white text-gray-800 border-gray-300");

                    $btn.removeClass("bg-white text-gray-800 border-gray-300")
                        .addClass("bg-primary/10 text-primary border-primary");
                });

                const quantity = parseInt($wrapper.find(".quantity").val()) || 1;
                updateProductUI($wrapper, defaultVariant, quantity, true);

                $wrapper.data('variant-initialized', true);
            }

            document.addEventListener('modal:open', function(event) {
                const modalEl = event.detail;
                const $modal = $(modalEl);

                $modal.find("[id^='product-wrapper']").each(function() {
                    initDefaultVariant($(this));
                });
            });

            function onLoadMoreProducts() {
                $("[id^='product-wrapper']").each(function() {
                    initDefaultVariant($(this));
                });
            }
        });
    </script>

    @if (auth()->check() && auth()->user()->isAffiliate())
        <script>
            function copyReferralLink(button, referralCode, productUrl) {
                // Append ?ref=referralCode to the product URL
                const referralUrl = `${productUrl}?ref=${referralCode}`;

                navigator.clipboard.writeText(referralUrl).then(() => {
                    const tooltip = button.querySelector('.tooltip-text');
                    tooltip.classList.remove('opacity-0');
                    tooltip.classList.add('opacity-100');

                    setTimeout(() => {
                        tooltip.classList.remove('opacity-100');
                        tooltip.classList.add('opacity-0');
                    }, 2000);
                });
            }
        </script>
    @endif

    <script>
        const globalLoader = document.getElementById('global-loader');
        function showLoader() {
            globalLoader.classList.remove('hidden');
            globalLoader.classList.add('flex');            
            // Disable scrolling on the body
            document.body.classList.add('overflow-hidden');
        }

        function hideLoader() {
            globalLoader.classList.add('hidden');
            globalLoader.classList.remove('flex');            
            // Re-enable scrolling
            document.body.classList.remove('overflow-hidden');
        }
    </script>

    @stack('scripts')
</body>

</html>
