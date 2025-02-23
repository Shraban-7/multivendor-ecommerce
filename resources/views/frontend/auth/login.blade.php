<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="{{ asset('assets/frontend/images/favicon.ico') }}"
      type="image/x-icon"
    />
    <!-- Link Tailwind CSS's CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Link Flowbite CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"
      rel="stylesheet"
    />
    <!-- Link Custome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/responsive.css') }}" />

    <title>Login | Tesko</title>
  </head>
  <body>
    <!-- Login Details Page -->
    <main class="login-page">
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="login-form-sec section-padding px-5 md:px-10 2xl:px-20">
          <div class="welcome-text space-y-2">
            <div class="w-24 h-10 sm:w-32 sm:h-12">
              <a href="{{ route('home') }}">
                <img
                  src="{{ asset('assets/frontend/images/tesko-login-logo.png') }}"
                  alt="Tesko Logo"
                  class="object-contain w-full h-full"
                />
              </a>
            </div>
            <h1
              class="text-2xl xsm:text-3xl sm:text-4xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-black !leading-tight"
            >
              Hay, <br />Welcome Back!
            </h1>
            <p class="font-medium text-davy-gray/60">
              We are very happy to see you back!
            </p>
          </div>

          <div class="login-form mt-3 sm:mt-5 lg:w-9/w-12 2xl:w-4/5">
            <!-- Login Form -->
            <form
              action="{{ route('login') }}" method="POST"
              class="w-full flex flex-col gap-4 sm:gap-5 mb-3 md:mb-4"
            >
            @csrf
              <!-- Input fields -->
              <div class="form-ctrl space-y-1 sm:space-y-2">
                <label class="block text-sm" for="email">Email</label>
                <input
                  required
                  id="email"
                  type="email"
                  name="email"
                  placeholder="tescocommunity@gmail.com"
                  class="eq w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                />
              </div>
              <div class="form-ctrl space-y-1 sm:space-y-2">
                <label class="block text-sm" for="password">Password</label>
                <input
                  required
                  type="password"
                  id="password"
                  name="password"
                  placeholder="•••••••••••••"
                  class="eq w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[1] focus:ring-light-yellow focus:border-light-yellow text-sm md:text-base"
                />
              </div>
              <!-- Agree to Terms & Privacy policy Address Checkbox -->
              <div class="flex items-start text-davy-gray/80">
                <input
                  required
                  id="terms"
                  type="checkbox"
                  class="h-4 w-4 text-light-yellow focus:ring-light-yellow border-gray-300 rounded"
                />
                <label for="terms" class="ml-2 text-sm"
                  >By signing up, you are creating a tesko account, and you
                  agree to tesko's
                  <a href="#" class="text-butterfly-blue hover:underline eq"
                    >Term of Use</a
                  >
                  and
                  <a href="#" class="text-butterfly-blue hover:underline eq"
                    >Privacy Policy</a
                  >.</label
                >
              </div>
              <!-- Remember me Checkbox -->
              <div class="flex items-start text-davy-gray/80">
                <input
                  id="remember"
                  type="checkbox"
                  class="h-4 w-4 text-light-yellow focus:ring-light-yellow border-gray-300 rounded"
                />
                <label for="remember" class="ml-2 text-sm"
                  >Remember Me as
                  <a href="#" class="text-butterfly-blue hover:underline eq"
                    >Member</a
                  >
                  of
                  <a href="#" class="text-butterfly-blue hover:underline eq"
                    >Tesko Community</a
                  >.
                </label>
              </div>

              <button
                type="submit"
                class="text-white bg-butterfly-blue py-2 md:py-3 w-full rounded-lg hover:bg-light-yellow eq"
              >
                Login
              </button>
            </form>

            <!-- Social Login -->
            <div class="social-login space-y-3">
              <div
                class="devider-sec flex flex-nowrap items-center justify-center text-davy-gray/80"
              >
                <span class="h-px bg-davy-gray/80 block flex-1"></span
                ><span class="block px-3">OR</span
                ><span class="h-px bg-davy-gray/80 block flex-1"></span>
              </div>

              <div
                class="login-options flex flex-col lg:flex-row flex-wrap gap-5 text-davy-gray/80"
              >
                <button
                  class="px-1 py-2 flex-1 inline-flex gap-1 items-center justify-center border border-jet-gray/20 rounded hover:bg-jet-gray/10 eq"
                >
                  <img
                    src="{{ asset('assets/frontend/images/google-icon.png') }}"
                    class="h-8 md:h-10 w-auto"
                  />
                  <span>Login with Google</span>
                </button>
                <button
                  class="px-1 py-2 flex-1 inline-flex gap-1 items-center justify-center border border-jet-gray/20 rounded hover:bg-jet-gray/10 eq"
                >
                  <img
                    src="{{ asset('assets/frontend/images/microsoft-icon.png') }}"
                    class="h-8 md:h-10 w-auto"
                  />
                  <span>Login with Microsoft</span>
                </button>
              </div>
            </div>

            <!-- create new acc -->
            <p class="text-davy-gray/80 mt-3 md:mt-4 text-center">
              Don't have account?
              <a href="#" class="text-butterfly-blue hover:underline eq"
                >Sign Up here!</a
              >
            </p>
          </div>
        </div>

        <!-- Login Page Image -->
        <div class="login-banner hidden md:block">
          <div class="h-full lg:h-[48.5rem] xl:h-[50.5rem] relative bg-red-300">
            <div class="image-wrap h-full overflow-hidden">
              <img
                src="{{ asset('assets/frontend/images/login-banner.png') }}"
                alt="Login Banner"
                class="w-full h-full object-cover"
              />
            </div>
            <!-- gradient overlay -->
            <div
              class="w-2/6 h-full bg-gradient-to-r from-white from-15% to-transparent absolute top-0 left-0"
            ></div>
          </div>
        </div>
      </div>
    </main>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <!-- Tailwind Global Config JS -->
    <script src="{{ asset('assets/frontend/tailwind.config.js') }}"></script>
  </body>
</html>
