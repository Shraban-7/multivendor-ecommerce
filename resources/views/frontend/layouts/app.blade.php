<!DOCTYPE html>
<html lang="en">

<?php

use Illuminate\Support\Facades\View;
$settings = settings();
$isDashboard = View::hasSection('dashboard');
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <x-favicons />
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/libs/swiper/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/toastr/css/toastr.min.css') }}">

    <title>@yield('title')</title>
    <style>
        body {
            background-color: #f9f9f9;
        }
    </style>

    @stack('header')
</head>

<body>
    <header class="header-section bg-persian-red text-white font-primary sticky top-0 z-50">
        @include('frontend.layouts.top-nav')
    </header>

    <div class="hidden md:block">
        @include('frontend.layouts.bottom-nav')
    </div>

    <div
        class="block md:hidden">
        @include('frontend.layouts.mobile-dock')
    </div>

    @if (session('error') || session('success'))
        <div id="alert-border"
            class="fixed top-4 left-1/2 -translate-x-1/2 z-50 px-4 py-2 text-sm flex items-center gap-2
            {{ session('error') ? 'text-red-700 bg-red-100 border-red-500' : 'text-green-700 bg-green-100 border-green-500' }}
            border-l-4 rounded-md max-w-md w-[95%] sm:w-auto"
            role="alert">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="flex-1">{{ session('error') ?? session('success') }}</span>
            <button type="button" class="text-current hover:text-black" data-dismiss-target="#alert-border">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif

    @if (!$isDashboard)
        <main class="max-w-7xl mx-auto p-4">
            @yield('content')
        </main>
    @endif

    @if ($isDashboard)
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                @include('frontend.layouts.sidebar')
                <section class="md:col-span-3 space-y-6">
                    @yield('dashboard')
                </section>
            </div>
        </main>
        <!-- Floating Sidebar Toggle Button (Mobile Only) -->
        <button
            class="fixed bottom-16 right-6 z-50 md:hidden bg-yellow-500 text-white p-4 rounded-full shadow-lg hover:bg-yellow-600 focus:outline-none"
            id="sidebar-toggle">
            <i class="fa-solid fa-bars"></i>
        </button>
    @endif

    @include('frontend.layouts.footer')

    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>

    @vite('resources/js/app.js')

    <script src="{{ asset('assets/libs/swiper/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiperSliders.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/multipleProductsSwiper.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/simple-datatables@9.0.3.js') }}"></script>
    <script src="{{ asset('assets/libs/toastr/js/toastr.min.js') }}"></script>

    @if ($isDashboard)
        <!-- Sidebar toggle -->
        <script>
            const sidebarToggle = document.getElementById("sidebar-toggle");
            const mobileSidebar = document.getElementById("mobile-sidebar");
            const sidebarBackdrop = document.getElementById("sidebar-backdrop");
            sidebarToggle.addEventListener("click", () => {
                const isOpen = !mobileSidebar.classList.contains("-translate-x-full");
                if (isOpen) {
                    mobileSidebar.classList.add("-translate-x-full");
                    sidebarBackdrop.classList.add("hidden");
                } else {
                    mobileSidebar.classList.remove("-translate-x-full");
                    sidebarBackdrop.classList.remove("hidden");
                }
            });
            sidebarBackdrop.addEventListener("click", () => {
                mobileSidebar.classList.add("-translate-x-full");
                sidebarBackdrop.classList.add("hidden");
            });
        </script>
    @endif

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // document.querySelectorAll("video").forEach((video) => {
        //     const playBtn = video.parentElement.querySelector(".play-btn");
        //     const muteBtn = video.parentElement.querySelector(".mute-btn");

        //     playBtn.addEventListener("click", () => {
        //         if (video.paused) {
        //             video.play();
        //             playBtn.innerHTML = `<i class="fa-solid fa-pause text-light-yellow"></i>`;
        //         } else {
        //             video.pause();
        //             playBtn.innerHTML = `<i class="fa-solid fa-play"></i>`;
        //         }
        //     });

        //     video.muted = false;

        //     function updateMuteButton(muteBtn) {
        //         muteBtn.innerHTML = video.muted ?
        //             `<i class="fa-solid fa-volume-xmark text-persian-red"></i>` :
        //             `<i class="fa-solid fa-volume-high"></i>`;
        //     }

        //     muteBtn.addEventListener("click", () => {
        //         video.muted = !video.muted;
        //         updateMuteButton(muteBtn);
        //     });
        //     updateMuteButton(muteBtn);
        // });

        const currentYear = new Date().getFullYear();
        document.getElementById("current-year").textContent = currentYear;
    </script>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eye = button.querySelector('.fa-eye');
            const eyeSlash = button.querySelector('.fa-eye-slash');

            if (input.type === "password") {
                input.type = "text";
                eye.style.display = "none";
                eyeSlash.style.display = "inline";
            } else {
                input.type = "password";
                eye.style.display = "inline";
                eyeSlash.style.display = "none";
            }
        }
    </script>

    <script>
        $(document).ready(function() {

            $('body').on('click', '.addToCartBtn', function() {
                var $btn = $(this);
                var originalText = $btn.html();
                $btn.html(
                    `<svg class="animate-spin h-4 w-4 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Adding...`
                ).prop('disabled', true);

                var product_id = $btn.data('id');
                var modal = $btn.data('modal');
                var wishlistId = $btn.data('wishlist-id');

                var $product_content = $btn.closest('.product-contents');

                var variantId = $product_content.find('.variantId').val() || null;
                var product_price_text = $product_content.find('.product-price').text().replace(/[^0-9.]/g,
                    '');
                var product_price = parseFloat(product_price_text);

                let selectedOptionIds = [];
                $product_content.find('.variant-option:checked').each(function() {
                    selectedOptionIds.push($(this).val());
                });

                if (!product_id) {
                    alert("No Product Selected!");
                    $btn.html(originalText).prop('disabled', false);
                    return;
                }

                var qtyInput = $product_content.find('.qtyInputValue').val() || 1;

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        product_id: product_id,
                        variant_id: variantId,
                        quantity: qtyInput,
                        price: product_price,
                        selected_options: selectedOptionIds
                    },
                    success: function(data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('login') }}";
                        } else if (data.success) {
                            toastr.success(data.message);
                            updateCartData();

                            if ("{{ Route::currentRouteName() }}" === 'cart.details' && data
                                .action === 'add_to_cart') {
                                window.location.reload();
                            }
                        } else {
                            toastr.error(data.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        } else {
                            toastr.error('Something went wrong!');
                        }
                    },
                    complete: function() {
                        $btn.html(originalText).prop('disabled', false);
                    }
                });
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
                            window.location.href = "{{ route('login') }}";
                        } else if (data.success) {
                            $('button[data-modal-hide="quick-view-modal-' + product_id + '"]')
                                .trigger('click');
                            $row.fadeOut(300, function() {
                                $(this).remove();
                            });
                            toastr.success(data.message);
                            updateCartData();

                            window.location.href = "{{ route('orders.checkout') }}" +
                                "?seller_id=" + seller_id;
                        } else {
                            toastr.error(data.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        } else {
                            toastr.error('Something went wrong!');
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
                            window.location.href = "{{ route('login') }}";
                        } else if (data.success) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.error);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = "{{ route('login') }}";
                        } else {
                            toastr.error('Something went wrong!');
                        }
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
                        toastr.error('Failed to update cart data.');
                    }
                });
            }
        });
    </script>

    <script>
        $(function() {

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

            function updateProductUI($wrapper, variant, quantity) {
                const $mainImage = $wrapper.find(".main-product-image");
                const $thumbWrapper = $wrapper.find(".thumbnailWrapper");
                const $priceEl = $wrapper.find(".product-price");
                const $originalPriceEl = $wrapper.find(".original-price");
                const $skuEl = $wrapper.find(".sku-text");
                const $stockEl = $wrapper.find(".stock-text");
                const $availability = $wrapper.find(".availability-text");
                const $variantError = $wrapper.find(".variant-error");
                const $addToCartBtn = $wrapper.find(".addToCartBtn");
                const $variantIdInput = $wrapper.find("input.variantId");
                const $qtyInput = $wrapper.find(".qtyInputValue");
                const $qtyEl = $wrapper.find("input.quantity");
                const product = $wrapper.data("product");


                if (variant) {
                    const basePrice = parseFloat(variant.price) || 0;
                    const discounted = variant.discounted_price !== null ? parseFloat(variant.discounted_price) :
                        null;
                    const price = discounted && discounted > 0 ? discounted : basePrice;

                    $priceEl.text(`৳ ${formatPrice(price, quantity)}`);

                    if (discounted == 0 || discounted == null) {
                        $originalPriceEl.addClass('hidden');
                    } else {
                        $originalPriceEl.removeClass('hidden');
                        $originalPriceEl.text(`৳ ${formatPrice(basePrice, quantity)}`)
                    }

                    $skuEl.text(variant.sku);
                    $stockEl.text(variant.stock);
                    $availability.text(variant.stock > 0 ? "In Stock" : "Out of Stock");
                    $variantIdInput.val(variant.id);
                    $variantError.addClass("hidden");
                    if (variant.stock <= 0) {
                        $addToCartBtn.prop("disabled", true).addClass("opacity-50 cursor-not-allowed");

                    } else {
                        $addToCartBtn.prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                    }
                    $qtyInput.val(quantity);
                    $qtyEl.val(quantity);

                    if (variant.image) {
                        const imageUrl = storageURL(variant.image);
                        $wrapper.find('.main-product-image').attr('src', imageUrl);
                        $wrapper.find('.slide-thumb').removeClass('border-primary').addClass('border-transparent');
                        const $thumbEl = $wrapper.find(`.thumb-img[data-full="${imageUrl}"]`)

                        const currentMainImage = $wrapper.find('.main-product-image').attr('src');

                        if (currentMainImage == imageUrl) {
                            $thumbEl.closest('.slide-thumb').addClass('border-primary').removeClass(
                                'border-transparent');
                        }
                    }

                } else if (product.variants.length > 0 && !variant) {
                    $skuEl.text("N/A");
                    $stockEl.text("0");
                    $availability.text("Not Available");
                    $priceEl.text("৳ 0.00");
                    $originalPriceEl.addClass('hidden');

                    $variantIdInput.val("");
                    $variantError.removeClass("hidden");
                    $addToCartBtn.prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                    let allThumbs = '';
                    product.slider.forEach((img, i) => {
                        const full = `/storage/${img}`;
                        const border = i === 0 ? 'border-primary' : 'border-transparent';
                        allThumbs += `<div class="slide-thumb w-full xl:h-24 md:h-22 lg:h-28 h-20 rounded-2xl cursor-pointer border-2 ${border} overflow-hidden">
                        <img src="${full}" class="w-full h-full object-cover thumb-img" data-full="${full}" />
                    </div>`;
                    });
                    $wrapper.find('#thumbnailWrapper').html(allThumbs);
                    $wrapper.find('#main-product-image').attr('src', `/storage/${product.slider[0] ?? ''}`);

                } else {

                    console.log("else")
                    const basePrice = parseFloat(product.price) || 0;
                    const discounted = product.discounted_price !== null ? parseFloat(product.discounted_price) :
                        null;
                    const price = discounted && discounted > 0 ? discounted : basePrice;

                    $priceEl.text(`৳ ${formatPrice(price, quantity)}`);
                    $skuEl.text(product.sku ?? "N/A");
                    $stockEl.text(product.stock ?? 0);
                    $availability.text((product.stock ?? 0) > 0 ? "In Stock" : "Out of Stock");
                    $variantIdInput.val("");
                    $variantError.addClass("hidden");
                    $addToCartBtn.prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
                    $qtyInput.val(quantity);
                    $qtyEl.val(quantity)
                    let allThumbs = '';
                    product.slider.forEach((img, i) => {
                        const full = `/storage/${img}`;
                        const border = i === 0 ? 'border-primary' : 'border-transparent';
                        allThumbs += `<div class="slide-thumb w-full xl:h-24 md:h-22 lg:h-28 h-20 rounded-2xl cursor-pointer border-2 ${border} overflow-hidden">
                        <img src="${full}" class="w-full h-full object-cover thumb-img" data-full="${full}" />
                    </div>`;
                    });
                    $wrapper.find('#thumbnailWrapper').html(allThumbs);
                    $wrapper.find('#main-product-image').attr('src',
                        `/storage/${product.slider[0] ?? ''}`);
                }
            }

            function getSelectedVariant(product, selectedOptions) {
                const selectedIds = Object.values(selectedOptions).map(Number).sort();
                return (product.variants || []).find(v =>
                    JSON.stringify([...v.value_ids].sort()) === JSON.stringify(selectedIds)
                );
            }

            function collectSelectedOptions($wrapper) {
                const selectedOptions = {};
                $wrapper.find(".option-value-btn.bg-primary\\/10")
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
                    "bg-primary/10 text-primary border-primary"
                ).addClass("bg-white text-gray-800 border-gray-300");

                $btn.removeClass("bg-white text-gray-800 border-gray-300").addClass(
                    "bg-primary/10 text-primary border-primary"
                );

                const selectedOptions = collectSelectedOptions($wrapper);

                console.log(selectedOptions);


                const variant = getSelectedVariant(product, selectedOptions);

                console.log(variant);


                const quantity = parseInt($wrapper.find(".qtyInputValue").val()) || 1;

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
                    "border-transparent");
                $img.closest(".slide-thumb").addClass("border-primary").removeClass("border-transparent");
            });

            $(document).on("click", ".increaseBtn, .decreaseBtn", function() {
                const $btn = $(this);
                const $wrapper = $btn.closest("[id^='product-wrapper']");
                const product = $wrapper.data("product");
                if (!product) return;

                let quantity = parseInt($wrapper.find(".qtyInputValue").val()) || 1;
                quantity = $btn.hasClass("increaseBtn") ? quantity + 1 : Math.max(1, quantity - 1);

                const selectedOptions = collectSelectedOptions($wrapper);
                const variant = getSelectedVariant(product, selectedOptions);

                updateProductUI($wrapper, variant, quantity);
            });

            $(document).on("input", ".qtyInputValue", function() {
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

                const quantity = parseInt($wrapper.find(".qtyInputValue").val()) || 1;
                updateProductUI($wrapper, defaultVariant, quantity);

                $wrapper.data('variant-initialized', true);
            }

            $(document).ready(function() {
                $("[id^='product-wrapper']").each(function() {
                    initDefaultVariant($(this));
                });
            });

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

    @stack('scripts')
</body>

</html>
