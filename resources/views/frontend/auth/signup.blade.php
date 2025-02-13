@extends('frontend.layouts.app')
@section('title', 'Sign Up')

@section('content')
    <section class="max-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md space-y-4 border rounded bg-white">
            <h2 class="sm:text-base text-sm font-medium border-b px-3 py-1.5 md:px-5 md:py-3 uppercase text-center">
                Create New Account
            </h2>

            <form spellcheck="false" action="{{ route('signup') }}" method="POST" class="flex flex-col gap-y-3 sm:gap-y-5 px-3 py-1.5 md:px-5 md:py-2">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 sm:gap-4">
                    <div class="from-ctrl space-y-1 sm:space-y-2">
                        <label for="first-name" class="block text-sm">Full Name</label>
                        <input required type="text" name="fullname" value="{{ old('fullname') }}" id="first-name"
                            class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                    </div>
                </div>

                <div class="from-ctrl space-y-1 sm:space-y-2">
                    <label for="register-email" class="block text-sm">Email</label>
                    <input required type="email" name="email" value="{{ old('email') }}" id="register-email"
                        class="eq w-full px-3 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                </div>

                <div class="from-ctrl space-y-1 sm:space-y-2">
                    <label for="register-password" class="block text-sm">Password</label>
                    <div class="relative">
                        <input required type="password" name="password" id="register-password"
                            class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                            placeholder="8+ characters" />
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                            onclick="togglePassword('register-password', this)">
                            <i class="fa-solid fa-eye"></i>
                            <i class="fa-solid fa-eye-slash hidden"></i>
                        </button>
                    </div>
                </div>

                <div class="from-ctrl space-y-1 sm:space-y-2">
                    <label for="confirm-password" class="block text-sm">Confirm Password</label>
                    <div class="relative">
                        <input required type="password" name="password_confirmation" id="confirm-password"
                            class="eq w-full pl-3 pr-10 py-2 border border-gray-300 rounded focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base" />
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-davy-gray"
                            onclick="togglePassword('confirm-password', this)">
                            <i class="fa-solid fa-eye"></i>
                            <i class="fa-solid fa-eye-slash hidden"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input required type="checkbox" id="terms" class="h-4 w-4 text-primary border-gray-300 rounded" />
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the <a href="#" class="text-primary hover:text-theme-dark">Terms and Conditions</a>
                    </label>
                </div>

                <button type="submit"
                    class="bg-primary text-white px-5 py-2 border-2 border-transparent rounded active:ring-[1] active:ring-light-yellow active:border-light-yellow text-xs md:text-sm uppercase font-bold hover:bg-theme-dark eq w-full">
                    Register
                </button>

                <p class="text-center text-sm">
                    Already have an account?
                    <a href="login.html" class="text-primary hover:text-theme-dark">Login here</a>
                </p>
            </form>
        </div>
    </section>
@endsection
