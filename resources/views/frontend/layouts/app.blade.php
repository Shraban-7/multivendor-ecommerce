<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/frontend/images/favicon.ico') }}" type="image/x-icon" />
    <!-- Link Tailwind CSS's CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Link Font Awesome's CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Link Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <!-- Link Custome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/responsive.css') }}" />

    <title>Tesko | @yield('title')</title>
</head>

<body>
    <!-- Header Starts -->
    <header class="header-section bg-persian-red text-white font-primary">
        <!-- top nav -->
        @include('frontend.partials.top-nav')
        <!-- bottom nav -->
        @include('frontend.partials.bottom-nav')

    </header>
    <!-- Header Ended -->

    @yield('content')

    <!-- Footer Section Starts -->
    @include('frontend.partials.footer')
    <!-- Footer Section Ended -->

    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"
        integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Swiper JS Custom Cacarousel slider Script's-->
    <script src="{{ asset('assets/frontend/js/swiperSliders.js') }}"></script>

    <!-- Tailwind Global Config JS -->
    <script src="{{ asset('assets/frontend/tailwind.config.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- custom scripts -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        })
        document.querySelectorAll("video").forEach((video) => {
            const playBtn = video.parentElement.querySelector(".play-btn");
            const muteBtn = video.parentElement.querySelector(".mute-btn");

            playBtn.addEventListener("click", () => {
                if (video.paused) {
                    video.play();
                    playBtn.innerHTML = `<i class="fa-solid fa-pause text-light-yellow"></i>`;
                } else {
                    video.pause();
                    playBtn.innerHTML = `<i class="fa-solid fa-play"></i>`;
                }
            });

            video.muted = false;

            function updateMuteButton(muteBtn) {
                muteBtn.innerHTML = video.muted ?
                    `<i class="fa-solid fa-volume-xmark text-persian-red"></i>` :
                    `<i class="fa-solid fa-volume-high"></i>`;
            }

            muteBtn.addEventListener("click", () => {
                video.muted = !video.muted;
                updateMuteButton(muteBtn);
            });
            updateMuteButton(muteBtn);
        });

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
                        quantity: qtyInput
                    },
                    success: function(data) {
                        if (data.unauthorized) {
                            window.location.href = "{{ route('login') }}";
                        } else if (data.success) {
                            $('button[data-modal-hide="quick-view-modal-' + product_id + '"]').trigger('click');
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

    @stack('scripts')
</body>

</html>
