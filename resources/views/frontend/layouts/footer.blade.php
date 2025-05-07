<footer class="w-full">
  <!-- Newsletter Section -->
  <div class="newsletter-sec relative lg:px-16 md:px-10 -mb-20 z-[20]">
    <div class="container bg-black text-white px-8 py-5 md:p-8 lg:px-12 lg:py-8 flex flex-col md:flex-row justify-between items-center gap-6 rounded-2xl">
      <h2 class="text-xl sm:text-2xl lg:text-4xl font-bold text-center md:text-left max-w-md">
        STAY UP TO DATE ABOUT OUR LATEST OFFERS
      </h2>
      <div class="w-full md:w-96 lg:w-80 xl:w-86 flex flex-col gap-4">
        <form action="#">
          <div class="relative">
            <label for="subscribe-email" class="absolute top-1/2 left-4 transform -translate-y-1/2 text-black/40 text-lg">
              <i class="fa-regular fa-envelope"></i>
            </label>
            <input
              type="email"
              id="subscribe-email"
              placeholder="Enter your email address"
              required
              class="w-full py-3 pl-12 pr-4 rounded-full border border-gray-400 text-black/40 placeholder:text-black/40 focus:outline-none focus:border-red-600 focus:ring-1 focus:ring-red-600 transition"
            />
          </div>
          <button
            type="submit"
            class="w-full py-3 mt-3 rounded-full bg-white text-black hover:bg-red-600 hover:text-white transition"
          >
            Subscribe to Newsletter
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Main Footer Content -->
  <div class="bg-[#F0F0F0] text-sm px-4 pb-12 pt-32">
    <div class="container mx-auto">
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-8">
        <!-- Company Info -->
        <div class="lg:col-span-1">
          <a href="/" class="block w-24 mb-4">
            <img src="{{ asset('assets/frontend/images/footer-logo.png') }}" alt="Tesko Logo" class="w-full h-auto object-contain" />
          </a>
          <p class="text-black/60 mb-4">
            We have clothes that suit your style and which you're proud to wear. From women to men.
          </p>
          <div class="flex gap-2">
            @foreach (social_links() as $socialLink)
              <a href="{{ $socialLink->link }}" class="w-8 h-8 flex items-center justify-center bg-white border border-black/20 rounded-full text-black hover:bg-black hover:text-white transition">
                <i class="fa-brands {{ $socialLink->icon_name }}"></i>
              </a>
            @endforeach
          </div>
        </div>

        <!-- Company Links -->
        <div>
          <h3 class="font-medium text-base mb-4">COMPANY</h3>
          <ul class="space-y-3">
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">About</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Features</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Works</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Career</a></li>
          </ul>
        </div>

        <!-- Help Links -->
        <div>
          <h3 class="font-medium text-base mb-4">HELP</h3>
          <ul class="space-y-3">
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Customer Support</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Delivery Details</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Terms & Conditions</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Privacy Policy</a></li>
          </ul>
        </div>

        <!-- FAQ Links -->
        <div>
          <h3 class="font-medium text-base mb-4">FAQ</h3>
          <ul class="space-y-3">
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Account</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Manage Deliveries</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Orders</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Payments</a></li>
          </ul>
        </div>

        <!-- Resources Links -->
        <div>
          <h3 class="font-medium text-base mb-4">RESOURCES</h3>
          <ul class="space-y-3">
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Free eBooks</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">Development Tutorial</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">How to - Blog</a></li>
            <li><a href="#" class="text-black/60 hover:text-red-600 transition">YouTube Playlist</a></li>
          </ul>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="flex flex-col sm:flex-row justify-between items-center pt-5 border-t border-black/10">
        <p class="text-black/60 text-center sm:text-left mb-4 sm:mb-0">
          Tesko © 2020-<span id="current-year"></span>, All Rights Reserved
        </p>
        <div class="flex gap-2">
          @foreach (payment_gateways() as $gateway)
            <div class="w-12 sm:w-16">
              <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}" class="w-full h-auto object-contain" />
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</footer>


