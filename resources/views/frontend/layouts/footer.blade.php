<footer class="w-full">
    <!-- Newsletter Section -->
    <div class="newsletter-sec relative lg:px-16 md:px-10 -mb-20 z-[20]">
        <div
            class="container text-white bg-black p-5 md:p-8 lg:px-12 lg:py-8 mx-auto flex flex-col md:flex-row justify-between items-center gap-6 rounded-2xl">
            <h2
                class="text-xl xsm:text-2xl text-center md:text-left lg:text-4xl font-bold md:text-xl md:max-w-xs lg:max-w-md">
                STAY UPTO DATE ABOUT OUR LATEST OFFERS
            </h2>
            <div class="w-full md:w-96 lg:w-80 xl:w-86 flex flex-col gap-4">
                <form action="#">
                    <div class="relative">
                        <input type="email" id="subscribe-email" placeholder="Enter your email address" required
                            class="text-sm md:text-xs lg:text-base w-full py-3 pl-12 rounded-full border border-gray-400 focus:outline-none focus:border-persian-red focus:ring-persian-red font-[arial] text-black/40 placeholder:text-black/40 eq" />
                        <label for="subscribe-email"
                            class="inline-block text-black/40 absolute top-1/2 left-1 transform -translate-y-1/2 pl-4 text-lg">
                            <i class="fa-regular fa-envelope"></i>
                        </label>
                    </div>

                    <button
                        class="text-sm md:text-xs lg:text-base py-3 w-full rounded-full block bg-white text-black hover:bg-persian-red hover:text-white mt-3 eq">
                        Subscribe to Newsletter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="bg-[#F0F0F0] text-sm px-4 pb-12 pt-32 z-[0]">
        <div class="container mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mb-8">
                <!-- Company Info -->
                <div class="lg:col-span-1">
                    <a href="/" class="w-24 block mb-4 sm:mb-5">
                        <img class="w-full h-auto object-contain"
                            src="{{ asset('assets/frontend/images/footer-logo.png') }}" alt="Tesko Logo" />
                    </a>
                    <p class="text-black/60 mb-4">
                        We have clothes that suits your style and which you're proud to
                        wear. From women to men.
                    </p>
                    <div class="flex gap-1 xsm:gap-2 sm:gap-4">
                        @foreach (social_links() as $socialLink)
                            <a href="{{ $socialLink->link }}"
                                class="text-black hover:text-white hover:bg-black eq w-6 h-6 xsm:w-8 xsm:h-8 flex items-center justify-center bg-white rounded-full border border-black/20 text-xs xsm:text-sm">
                                <i class="fa-brands {{ $socialLink->icon_name }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Company Links -->
                <div>
                    <h3 class="font-medium text-base mb-4">COMPANY</h3>
                    <ul class="space-y-3 md:space-y-4">
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">About</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Features</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Works</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Career</a>
                        </li>
                    </ul>
                </div>

                <!-- Help Links -->
                <div>
                    <h3 class="font-medium text-base mb-4">HELP</h3>
                    <ul class="space-y-3 md:space-y-4">
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Customer
                                Support</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Delivery
                                Details</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Terms &
                                Conditions</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Privacy
                                Policy</a>
                        </li>
                    </ul>
                </div>

                <!-- FAQ Links -->
                <div>
                    <h3 class="font-medium text-base mb-4">FAQ</h3>
                    <ul class="space-y-3 md:space-y-4">
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Account</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Manage
                                Deliveries</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Orders</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Payments</a>
                        </li>
                    </ul>
                </div>

                <!-- Resources Links -->
                <div>
                    <h3 class="font-medium text-base mb-4">RESOURCES</h3>
                    <ul class="space-y-3 md:space-y-4">
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Free
                                eBooks</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Development
                                Tutorial</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">How to -
                                Blog</a>
                        </li>
                        <li>
                            <a href="#" class="text-black/60 hover:text-primary eq nav-link">Youtube
                                Playlist</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="flex flex-col sm:flex-row justify-between items-center pt-5 border-t border-black/10">
                <p class="text-black/60 text-center sm:text-left mb-4 sm:mb-0">
                    Tesko © 2020-<span id="current-year"></span>, All Rights Reserved
                </p>
                <div class="flex">
                    @foreach (payment_gateways() as $gateway)
                        <div class="w-[3rem] sm:w-[4rem]">
                            <img src="{{ storage_url($gateway->image) }}" alt="{{ $gateway->name }}"
                                class="w-full h-auto object-contain" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>

