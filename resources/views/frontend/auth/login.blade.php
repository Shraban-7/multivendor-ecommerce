<!DOCTYPE html>
<html lang="en">

<?php
$settings = settings();
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    <!-- Tailwind CSS -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite('resources/css/app.css')
    <!-- Flowbite CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" /> --}}
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/responsive.css') }}" />

    <title>Login | {{ $settings->app_name }}</title>
</head>

<body>
    <!-- Login Page -->
    <main class="login-page">
        @if (session('error') || session('success') || session('warning'))
            <div id="alert-border"
                class="fixed top-4 left-1/2 -translate-x-1/2 z-50 px-4 py-2 text-sm flex items-center gap-2
            @if (session('error')) text-red-700 bg-red-100 border-red-500
            @elseif (session('success'))
                text-green-700 bg-green-100 border-green-500
            @elseif (session('warning'))
                text-yellow-700 bg-yellow-100 border-yellow-500 @endif
            border-l-4 rounded-md max-w-md w-[95%] sm:w-auto"
                role="alert">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="flex-1">
                    {{ session('error') ?? (session('success') ?? session('warning')) }}
                </span>
                <button type="button" class="text-current hover:text-black" data-dismiss-target="#alert-border">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif


        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="login-form-sec section-padding px-5 md:px-10 2xl:px-20 flex flex-col sm:mx-15 mx-10">
                <div class="welcome-text space-y-2 mt-3 sm:mt-5 lg:w-9/w-12 2xl:w-4/5">
                    <div class="w-24 h-10 sm:w-32 sm:h-12">
                        <a href="{{ route('home') }}">
                            <img src="{{ storage_url($settings->logo_white) }}" alt="Logo"
                                class="object-contain w-full h-full" />
                        </a>
                    </div>
                    <h1
                        class="text-2xl xsm:text-3xl sm:text-4xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-black !leading-tight">
                        Welcome Back!
                    </h1>
                    <p class="font-medium text-davy-gray/60">
                        We are very happy to see you back!
                    </p>
                </div>

                <div class="login-form mt-3 sm:mt-5 lg:w-9/w-12 2xl:w-4/5">
                    <form action="{{ route('login') }}" method="POST"
                        class="w-full flex flex-col gap-4 sm:gap-5 mb-3 md:mb-4">
                        @csrf
                        <div class="form-ctrl space-y-1 sm:space-y-2">
                            <label class="block text-sm" for="email">Email</label>
                            <input required id="email" type="email" name="email" value="{{ old('email') }}"
                                class="eq w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                        </div>
                        <div class="form-ctrl space-y-1 sm:space-y-2">
                            <label class="block text-sm" for="password">Password</label>
                            <input required type="password" id="password" name="password"
                                class="eq w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                        </div>
                        <div class="flex items-start text-davy-gray/80">
                            <input id="remember" type="checkbox"
                                class="h-4 w-4 text-light-yellow focus:ring-light-yellow border-gray-300 rounded" />
                            <label for="remember" class="ml-2 text-sm">Remember Me</label>
                        </div>

                        <button type="submit"
                            class="text-white bg-butterfly-blue py-2 md:py-3 w-full rounded-lg hover:bg-light-yellow eq">
                            Login
                        </button>
                    </form>

                    <div class="social-login space-y-3">
                        <div class="devider-sec flex items-center justify-center text-davy-gray/80">
                            <span class="h-px bg-davy-gray/80 block flex-1"></span><span
                                class="block px-3">OR</span><span class="h-px bg-davy-gray/80 block flex-1"></span>
                        </div>

                        <div class="login-options flex flex-col lg:flex-row flex-wrap gap-5 text-davy-gray/80">
                            <button
                                class="px-1 py-2 flex-1 inline-flex gap-1 items-center justify-center border border-jet-gray/20 rounded hover:bg-jet-gray/10 eq">
                                <img src="{{ asset('assets/frontend/images/google-icon.png') }}"
                                    class="h-8 md:h-10 w-auto" />
                                <span>Login with Google</span>
                            </button>
                            <button
                                class="px-1 py-2 flex-1 inline-flex gap-1 items-center justify-center border border-jet-gray/20 rounded hover:bg-jet-gray/10 eq">
                                <img src="{{ asset('assets/frontend/images/microsoft-icon.png') }}"
                                    class="h-8 md:h-10 w-auto" />
                                <span>Login with Microsoft</span>
                            </button>
                        </div>
                    </div>

                    <div class="text-center mt-6 space-y-2">
                        <p class="text-jet-gray/80">
                            New to our marketplace?
                            <a href="{{ route('signup') }}" class="text-primary hover:underline">
                                Sign Up Here
                            </a>
                        </p>
                        <p class="text-jet-gray/80">
                            Own a store or want to sell products?
                            <a href="{{ route('seller.signup') }}" class="text-primary hover:underline">
                                Become a Seller
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="login-banner hidden md:block">
                <div class="h-full lg:h-[48.5rem] xl:h-[50.5rem] relative bg-red-300">
                    <div class="image-wrap h-full overflow-hidden">
                        <img src="{{ asset('assets/frontend/images/login-banner.png') }}" alt="Login Banner"
                            class="w-full h-full object-cover" />
                    </div>
                    <div class="w-2/6 h-full bg-gradient-to-r from-white from-15% to-transparent absolute top-0 left-0">
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JS -->
    @vite('resources/js/app.js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="{{ asset('assets/frontend/tailwind.config.js') }}"></script> --}}
</body>

</html>
