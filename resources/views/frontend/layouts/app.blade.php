<!DOCTYPE html>
<html lang="en">

<?php
$settings = settings();
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    <!-- Link jQuery -->
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    @vite('resources/css/app.css')

    <!-- Link Font Awesome's CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/swiper/css/swiper-bundle.min.css') }}" />

    <title>@yield('title') | {{ $settings->app_name }}</title>
</head>

<body>
    <!-- Header Starts -->
    <header class="header-section bg-persian-red text-white font-primary">
        <!-- top nav -->
        @include('frontend.layouts.top-nav')
        <!-- bottom nav -->
        @include('frontend.layouts.bottom-nav')

    </header>
    <!-- Header Ended -->

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

    @yield('content')

    <!-- Footer Section Starts -->
    @include('frontend.layouts.footer')
    <!-- Footer Section Ended -->

    @vite('resources/js/app.js')

    <!-- Swiper JS Custom Cacarousel slider Script's-->
    <script src="{{ asset('assets/libs/swiper/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiperSliders.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/multipleProductsSwiper.js') }}"></script>

    <!-- Data table  -->

    <script src="{{ asset('assets/libs/datatables/simple-datatables@9.0.3.js') }}"></script>

    <!-- Toastr CSS & js -->
    <link rel="stylesheet" href="{{ asset('assets/libs/toastr/css/toastr.min.css') }}">

    <script src="{{ asset('assets/libs/toastr/js/toastr.min.js') }}"></script>

    <!-- custom scripts -->
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
            const eyeIcon = button.querySelector('.fa-eye');
            const eyeSlashIcon = button.querySelector('.fa-eye-slash');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.cartBtn').click(function() {
                var product_id = $(this).data('id');
                var modal = $(this).data('modal');
                var wishlistId = $(this).data('wishlist-id');
                var variantId = $('#variantId').val() || null;
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
                        variant_id: variantId,
                        quantity: qtyInput,
                        price: product_price,
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

    @stack('scripts')
</body>

</html>
