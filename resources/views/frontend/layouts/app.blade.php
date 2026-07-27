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
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    {{--
    <link rel="stylesheet" href="{{ asset('assets/libs/toastr/css/toastr.min.css') }}"> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/libs/swiper/css/swiper-bundle.min.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Noto Sans"', '-apple-system', 'BlinkMacSystemFont', 'Roboto', '"Helvetica Neue"', 'Helvetica', 'Arial', '"PingFang SC"', '"Microsoft YaHei"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#F85606',
                            deep: '#C43D00',
                            tint: '#FFF1EA',
                        },
                        'ds-text': {
                            primary: '#191919',
                            secondary: '#595959',
                            tertiary: '#767676',
                            inverse: '#FFFFFF',
                        },
                        'ds-surface': {
                            base: '#FFFFFF',
                            muted: '#F5F5F5',
                            raised: '#FFFFFF',
                            strong: '#191919',
                        },
                        'ds-border': {
                            default: '#E5E5E5',
                            strong: '#C7C7C7',
                        },
                        'ds-feedback': {
                            success: '#1D8A45',
                            danger: '#D93025',
                            warning: '#B7791A',
                            info: '#0F6FC5',
                        },
                        'ds-star': '#FFA000',
                        primary: {
                            50: '#FFF1EA',
                            100: '#FFE0CC',
                            500: '#F85606',
                            600: '#C43D00',
                            700: '#9A2E00',
                        }
                    },
                    borderRadius: {
                        'xs': '2px',
                        'sm': '4px',
                        'md': '6px',
                    },
                    boxShadow: {
                        'card': '0 1px 2px rgba(25,25,25,0.08)',
                        'raised': '0 2px 8px rgba(25,25,25,0.12)',
                    },
                }
            }
        }
    </script>

    <style>
        /* === Design System: Focus & Accessibility === */
        *:focus-visible {
            outline: 2px solid #C43D00;
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* === Custom Scrollbar (design tokens) === */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #F5F5F5;
        }

        ::-webkit-scrollbar-thumb {
            background: #C7C7C7;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #F85606;
        }

        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* === Tab System === */
        .ds-tab-btn {
            position: relative;
            padding: 10px 16px;
            font-weight: 500;
            font-size: 14px;
            color: #595959;
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            transition: color 100ms ease, border-color 100ms ease;
            cursor: pointer;
        }
        .ds-tab-btn:hover {
            color: #F85606;
        }
        .ds-tab-btn.tab-active,
        .ds-tab-btn[aria-selected="true"] {
            color: #C43D00;
            border-bottom-color: #C43D00;
            font-weight: 600;
        }

        /* === Thumbnail strip === */
        .ds-thumb-strip {
            scrollbar-width: thin;
            scrollbar-color: #C7C7C7 transparent;
        }
        .ds-thumb-strip::-webkit-scrollbar {
            height: 3px;
        }
        .ds-thumb-strip::-webkit-scrollbar-thumb {
            background-color: #C7C7C7;
            border-radius: 10px;
        }
        .ds-thumb-strip::-webkit-scrollbar-track {
            background: transparent;
        }

        /* === Rating bar animation === */
        .ds-rating-bar-fill {
            transition: width 600ms cubic-bezier(0.2, 0, 0, 1);
        }

        /* === Home / product Swiper nav === */
        .hero-swiper .swiper-button-next,
        .hero-swiper .swiper-button-prev {
            width: 2.25rem;
            height: 2.25rem;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 9999px;
            color: #191919;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
            opacity: 0;
            transition: opacity 0.2s ease, background 0.2s ease;
        }
        .hero-swiper:hover .swiper-button-next,
        .hero-swiper:hover .swiper-button-prev {
            opacity: 1;
        }
        .hero-swiper .swiper-button-next:after,
        .hero-swiper .swiper-button-prev:after {
            font-size: 0.75rem;
            font-weight: 700;
        }
        .hero-swiper .swiper-pagination-bullet {
            background: rgba(255, 255, 255, 0.7);
            opacity: 1;
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 9999px;
            transition: width 0.25s ease, background 0.25s ease;
        }
        .hero-swiper .swiper-pagination-bullet-active {
            background: #F85606;
            width: 1.5rem;
        }
        .flash-sale-swiper .swiper-button-next,
        .flash-sale-swiper .swiper-button-prev {
            width: 2.25rem;
            height: 2.25rem;
            background: #fff;
            border: 1px solid #E5E5E5;
            border-radius: 9999px;
            color: #191919;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            opacity: 0;
            top: 42%;
            margin-top: 0;
            transition: opacity 0.2s ease, background 0.2s ease, color 0.2s ease;
            z-index: 20;
        }
        .flash-sale-swiper:hover .swiper-button-next,
        .flash-sale-swiper:hover .swiper-button-prev {
            opacity: 1;
        }
        .flash-sale-swiper .swiper-button-next:hover,
        .flash-sale-swiper .swiper-button-prev:hover {
            background: #F85606;
            color: #fff;
            border-color: #F85606;
        }
        .flash-sale-swiper .swiper-button-next:after,
        .flash-sale-swiper .swiper-button-prev:after {
            font-size: 0.75rem;
            font-weight: 700;
        }
        .flash-sale-swiper .swiper-button-disabled {
            opacity: 0.3 !important;
            cursor: not-allowed;
        }
        .flash-sale-swiper .flash-sale-slide {
            width: auto !important;
            height: auto;
            box-sizing: border-box;
        }
        .flash-sale-swiper .flash-sale-card-wrap {
            width: 132px;
            margin-right: 16px;
            height: 100%;
        }
        @media (min-width: 640px) {
            .flash-sale-swiper .flash-sale-card-wrap {
                width: 148px;
                margin-right: 20px;
            }
        }
        .flash-sale-swiper .swiper-slide:last-child .flash-sale-card-wrap {
            margin-right: 0;
        }
        .category-swiper .category-slide {
            width: auto !important;
            height: auto;
            box-sizing: border-box;
        }
        .category-swiper .category-card-wrap {
            width: 96px;
            margin-right: 12px;
        }
        .category-swiper .swiper-slide:last-child .category-card-wrap {
            margin-right: 0;
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

<body class="bg-ds-surface-muted font-sans min-h-screen text-ds-text-primary antialiased">

    @include('components.frontend.global-loader')

    @include('frontend.layouts.mobile-drawer')

    <div class="hidden">
        <!-- prevents navbar autocomplete -->
        <input type="password" name="password" autocomplete="current-password" ...>
        <input type="text" name="name" autocomplete="name" ...>
        <input type="password" name="password" autocomplete="new-password" ...>
    </div>

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
            <div class="space-y-6">
                @include('frontend.layouts.dashboard-nav')
                <section class="space-y-6">
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
    @include('components.frontend.custom-toastr')

    <script>
        window.toggleModal = function (modalId) {
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

    {{--
    <script src="{{ asset('assets/libs/toastr/js/toastr.min.js') }}"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
            return function (...args) {
                const context = this;
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(context, args), delay);
            };
        }

        function refreshCsrfToken() {
            return $.get("{{ route('refresh.csrf') }}").then(function (data) {
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

        function getSelectedVariant(product, selectedOptions) {
            const hasColorOption = (product.options || []).some(o => o.id === 'color');
            const hasSizeOption = (product.options || []).some(o => o.id === 'size');
            const colorId = selectedOptions['color'] != null ? Number(selectedOptions['color']) : null;
            const sizeId = selectedOptions['size'] != null ? Number(selectedOptions['size']) : null;

            if (hasColorOption && colorId === null) return null;
            if (hasSizeOption && sizeId === null) return null;

            return (product.variants || []).find(v => {
                const variantColor = v.color_id != null ? Number(v.color_id) : null;
                const variantSize = v.size_id != null ? Number(v.size_id) : null;
                const colorOk = !hasColorOption || variantColor === colorId;
                const sizeOk = !hasSizeOption || variantSize === sizeId;

                return colorOk && sizeOk;
            }) || null;
        }

        function collectSelectedOptions($wrapper) {
            const selectedOptions = {};
            $wrapper.find('.option-value-btn.active-option').each(function () {
                const $btn = $(this);
                selectedOptions[$btn.attr('data-option-id')] = Number($btn.attr('data-value-id'));
            });
            return selectedOptions;
        }

        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            window.togglePassword = function (inputId, button) {
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

            $('.buyNowBtn').click(function () {
                var product_id = $(this).data('id');
                var seller_id = $(this).data('seller');
                var wishlistId = $(this).data('wishlist-id');
                var variantSku = $('#variantSku').val();
                var product_price_text = $('.product-price').text().replace(/[^0-9.]/g, '');
                var product_price = parseFloat(product_price_text);
                var $row = $(this).closest('.grid');

                let selectedOptionIds = [];

                $('.variant-option:checked').each(function () {
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
                    success: function (data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('home') }}";
                        } else if (data.success) {
                            $row.fadeOut(300, function () {
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
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('home') }}";
                        } else {
                            showErrorToast(xhr.responseJSON.error);
                        }
                    }
                });
            });

            $('.wishlistBtn').click(function () {
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
                    success: function (data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('home') }}";
                        } else if (data.success) {
                            showSuccessToast(data.message);
                            updateWishlistData();
                        } else {
                            showErrorToast(data.error);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('home') }}";
                        } else {
                            showErrorToast(xhr.responseJSON.error);
                        }
                    }
                });
            });

            $('.wishlistRemoveBtn').on('click', function () {
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
                    success: function (response) {
                        if (response.success) {
                            $row.fadeOut(300, function () {
                                $(this).remove();
                                showSuccessToast(response.message);
                                updateWishlistData();
                            });
                        } else {
                            alert(response.message || 'Failed to remove item');
                        }
                    },
                    error: function () {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });

            function updateWishlistData() {
                $.ajax({
                    url: "{{ route('wishlist.data') }}",
                    type: "GET",
                    success: function (data) {
                        if (data.wishlistCount > 0) {
                            $('#wishlistCount').removeClass('hidden');
                        }

                        $('#wishlistCount').text(data.wishlistCount);
                    },
                    error: function () {
                        showErrorToast('Failed to update wishlist data.');
                    }
                });
            }


            function storageURL(fileName) {
                if (!fileName) {
                    return "{{ asset('assets/frontend/images/default.png') }}";
                }

                const value = String(fileName);
                if (/^(https?:)?\/\//i.test(value)) {
                    return value;
                }

                if (value.startsWith('/storage/')) {
                    return "{{ url('/') }}" + value;
                }

                if (value.includes('/storage/')) {
                    return value;
                }

                return "{{ url('/') }}" + '/storage/' + value.replace(/^\/+/, '');
            }

            function formatPrice(price, quantity) {
                const total = Math.round(price * quantity * 100) / 100;
                return total.toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function productHasVariants(product) {
                return !!(product.has_variants || (product.variants && product.variants.length > 0));
            }

            /** Selling price matches cart: compare_price ?? price */
            function resolveDisplayPrices(price, comparePrice) {
                const listPrice = parseFloat(price) || 0;
                const compare = comparePrice !== null && comparePrice !== '' && comparePrice !== undefined
                    ? parseFloat(comparePrice)
                    : null;
                const hasSale = compare !== null && !isNaN(compare) && compare > 0 && compare < listPrice;
                const selling = hasSale ? compare : listPrice;

                return { listPrice, selling, hasSale };
            }

            function renderPriceUI($priceEl, $originalPriceEl, price, comparePrice, quantity) {
                const { listPrice, selling, hasSale } = resolveDisplayPrices(price, comparePrice);
                const qty = quantity || 1;

                if (hasSale) {
                    $originalPriceEl.removeClass('hidden');
                    $priceEl.removeClass('text-[#191919]').addClass('text-[#F85606]')
                        .text(`৳ ${formatPrice(selling, qty)}`);
                    $originalPriceEl.text(`৳ ${formatPrice(listPrice, qty)}`);
                } else {
                    $originalPriceEl.addClass('hidden').text('');
                    $priceEl.removeClass('text-[#F85606]').addClass('text-[#191919]')
                        .text(`৳ ${formatPrice(selling, qty)}`);
                }
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
                    const stock = parseInt(variant.stock, 10) || 0;
                    const safeQty = Math.max(1, Math.min(quantity || 1, stock > 0 ? stock : 1));

                    renderPriceUI($priceEl, $originalPriceEl, variant.price, variant.compare_price, safeQty);

                    $skuEl.text(variant.sku || product.sku || 'N/A');
                    $stockEl.text(stock);
                    $availability
                        .text(stock > 0 ? 'In Stock' : 'Out of Stock')
                        .removeClass('text-gray-500')
                        .toggleClass('text-green-600', stock > 0)
                        .toggleClass('text-red-600', stock <= 0);
                    $variantIdInput.val(variant.id);
                    $qtyEl.val(safeQty);
                    $variantError.addClass('hidden');

                    $addToCartBtn
                        .prop('disabled', stock <= 0)
                        .toggleClass('opacity-50 cursor-not-allowed', stock <= 0);

                    if (!isInitialLoad && variant.image) {
                        $mainImage.attr('src', storageURL(variant.image));
                    }
                } else if (productHasVariants(product)) {
                    $originalPriceEl.addClass('hidden').text('');
                    $priceEl.removeClass('text-[#F85606]').addClass('text-[#191919]')
                        .text('Select options to see price');
                    $skuEl.text('—');
                    $stockEl.text('—');
                    $availability
                        .text('Select options')
                        .removeClass('text-green-600 text-red-600')
                        .addClass('text-gray-500');
                    $variantIdInput.val('');
                    $qtyEl.val(quantity || 1);
                    $addToCartBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                } else {
                    const stock = parseInt(product.stock, 10) || 0;

                    renderPriceUI($priceEl, $originalPriceEl, product.price, product.compare_price, quantity || 1);

                    $skuEl.text(product.sku || 'N/A');
                    $stockEl.text(stock);
                    $availability
                        .text(stock > 0 ? 'In Stock' : 'Out of Stock')
                        .removeClass('text-gray-500')
                        .toggleClass('text-green-600', stock > 0)
                        .toggleClass('text-red-600', stock <= 0);
                    $variantIdInput.val('');
                    $qtyEl.val(quantity || 1);
                    $addToCartBtn
                        .prop('disabled', stock <= 0)
                        .toggleClass('opacity-50 cursor-not-allowed', stock <= 0);
                }
            }

            function selectOptionButton($btn) {
                const optionId = $btn.attr('data-option-id');
                const $group = $btn.closest('[data-option-group]');
                const $buttons = $group.length
                    ? $group.find('.option-value-btn')
                    : $btn.closest('form, [id^="product-wrapper"]').find(`.option-value-btn[data-option-id="${optionId}"]`);

                $buttons.each(function () {
                    const $el = $(this);
                    $el.removeClass('active-option bg-primary/10 text-primary-500 border-primary-500 ring-2 ring-primary-200');
                    if ($el.attr('data-option-id') === 'color') {
                        $el.addClass('border-gray-300');
                    } else {
                        $el.addClass('bg-gray-50 text-gray-700 border-gray-300');
                    }
                });

                $btn.addClass('active-option');
                if ($btn.attr('data-option-id') === 'color') {
                    $btn.removeClass('border-gray-300').addClass('border-primary-500 ring-2 ring-primary-200');
                } else {
                    $btn.removeClass('bg-gray-50 text-gray-700 border-gray-300')
                        .addClass('bg-primary/10 text-primary-500 border-primary-500');
                }
            }

            function syncVariantUI($wrapper) {
                const product = $wrapper.data('product');
                if (!product) return;

                const selectedOptions = collectSelectedOptions($wrapper);
                const variant = getSelectedVariant(product, selectedOptions);
                const quantity = parseInt($wrapper.find('input.quantity').val(), 10) || 1;
                updateProductUI($wrapper, variant, quantity);
            }

            $(document).on('click', '.option-value-btn', function (e) {
                e.preventDefault();
                const $btn = $(this);
                const $wrapper = $btn.closest("[id^='product-wrapper']");
                if (!$wrapper.length) return;

                $wrapper.find('.variant-error').addClass('hidden');
                selectOptionButton($btn);
                syncVariantUI($wrapper);
            });

            $(document).on("click", ".slide-thumb", function (e) {
                e.preventDefault();
                const $thumb = $(this);
                const $wrapper = $thumb.closest("[id^='product-wrapper']");
                if (!$wrapper.length) return;

                const full = $thumb.attr("data-full")
                    || $thumb.find(".thumb-img").attr("data-full")
                    || $thumb.find(".thumb-img").attr("src");
                if (!full) return;

                const $mainImage = $wrapper.find(".main-product-image").first();
                const $thumbWrapper = $wrapper.find(".thumbnailWrapper");

                $mainImage.attr("src", full);
                $thumbWrapper.find(".slide-thumb")
                    .removeClass("border-brand")
                    .addClass("border-ds-border-default hover:border-ds-border-strong");
                $thumb
                    .removeClass("border-ds-border-default hover:border-ds-border-strong")
                    .addClass("border-brand");
            });

            $(document).on("click", ".increaseBtn, .decreaseBtn", debounce(function () {
                const $btn = $(this);
                const $wrapper = $btn.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                const $qtyInput = $wrapper.find("input.quantity");
                let quantity = parseInt($qtyInput.val(), 10) || 1;

                const selectedOptions = collectSelectedOptions($wrapper);
                const variant = getSelectedVariant(product, selectedOptions);
                const availableStock = variant
                    ? (parseInt(variant.stock, 10) || 0)
                    : (productHasVariants(product) ? 0 : (parseInt(product.stock, 10) || 0));

                if ($btn.hasClass("increaseBtn")) {
                    if (productHasVariants(product) && !variant) {
                        showWarningToast("Please select options first!");
                        return;
                    }
                    if (quantity < availableStock) {
                        quantity += 1;
                    } else {
                        quantity = Math.max(availableStock, 1);
                        showWarningToast("Not enough stock!");
                    }
                } else {
                    quantity = Math.max(1, quantity - 1);
                }

                updateProductUI($wrapper, variant, quantity);
            }, 300));

            $(document).on("input", ".quantity", function () {
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

            document.addEventListener('modal:open', function (event) {
                const modalEl = event.detail;
                const $modal = $(modalEl);
            });

            function onLoadMoreProducts() {
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

        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (link) {
                    const href = link.getAttribute('href');
                    const target = link.getAttribute('target');
                    if (
                        !href ||
                        href.startsWith('#') ||
                        href.startsWith('javascript:') ||
                        target === '_blank' ||
                        e.ctrlKey ||
                        e.metaKey ||
                        e.shiftKey
                    ) {
                        return;
                    }

                    if (href === window.location.href) {
                        return;
                    }

                    //showLoader();
                }
            });

            //"Stuck Loader" on Back/Forward Button navigation
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    hideLoader();
                }
            });
        });
    </script>

    @stack('scripts')
    <script src="{{ asset('assets/libs/swiper/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sliders.js') }}?v=3"></script>
    <script>
        window.CartRoutes = {
            add: "{{ route('cart.add') }}",
            data: "{{ route('cart.data') }}",
            update: "{{ route('cart.update') }}",
            delete: "{{ route('cart.delete') }}",
        };
        window.CurrentRouteName = "{{ Route::currentRouteName() }}";
    </script>
    <script src="{{ asset('assets/js/cart.js') }}?v=1"></script>
</body>

</html>