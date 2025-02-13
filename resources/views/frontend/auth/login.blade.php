@extends('frontend.layouts.app')
@section('title', 'Login')

@section('content')
    <section class="max-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md space-y-4 border rounded bg-white">
            <h2 class="sm:text-base text-sm font-medium border-b px-3 py-1.5 md:px-5 md:py-3 uppercase text-center">
                Login to Your Account
            </h2>

            <form spellcheck="false" action="{{ route('login') }}" method="POST" class="flex flex-col gap-y-3 sm:gap-y-5 px-3 py-1.5 md:px-5 md:py-2">
                @csrf
                <div class="from-ctrl space-y-1 sm:space-y-2">
                    <label for="login-email" class="block text-sm">Email</label>
                    <input required type="email" name="email" id="login-email"
                        class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                </div>

                <div class="from-ctrl space-y-1 sm:space-y-2">
                    <label for="login-password" class="block text-sm">Password</label>
                    <div class="relative">
                        <input required type="password" name="password" id="login-password"
                            class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                            onclick="togglePassword('login-password', this)">
                            <i class="fa-solid fa-eye"></i>
                            <i class="fa-solid fa-eye-slash hidden"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember-me" class="h-4 w-4 text-primary border-gray-300 rounded" />
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>
                    <a href="#" class="text-sm text-primary hover:text-theme-dark">Forgot password?</a>
                </div>

                <button type="submit"
                    class="bg-primary text-white px-5 py-2 border-2 border-transparent rounded active:ring-[1] active:ring-light-yellow active:border-light-yellow text-xs md:text-sm uppercase font-bold hover:bg-theme-dark eq w-full">
                    Login
                </button>

                <p class="text-center text-sm">
                    Don't have an account?
                    <a href="{{ route('signup') }}" class="text-primary hover:text-theme-dark">Register here</a>
                </p>
            </form>
        </div>
    </section>
@endsection
