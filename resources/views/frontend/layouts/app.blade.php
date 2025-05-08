<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/frontend/images/favicon.ico') }}" type="image/x-icon" />
    <!-- Link jQuery -->
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    @vite('resources/css/app.css')

    <!-- Link Font Awesome's CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/swiper/css/swiper-bundle.min.css') }}" />

    <title>Tesko | @yield('title')</title>
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

    @yield('content')

    <!-- Footer Section Starts -->
    @include('frontend.layouts.footer')
    <!-- Footer Section Ended -->

    @vite('resources/js/app.js')

    <!-- Swiper JS Custom Cacarousel slider Script's-->
    <script src="{{ asset('assets/libs/swiper/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/swiperSliders.js') }}"></script>

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
        });
    </script>
    <script>
        $(document).ready(function() {
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
        });
    </script>

    <script>
        $(document).ready(function() {
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
        });
    </script>

    @stack('scripts')
</body>

</html>
