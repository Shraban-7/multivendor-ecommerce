@extends('frontend.layouts.app')

@section('title', 'Contact Us')

@section('content')
    <main class="contact-us-page">
        <!-- Page Promotion Banner Starts -->
        <section class="page-promotion container md:w-full py-5">
            <div
                class="promo-wrapper md:container bg-[#5C62D6] grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden">
                <div
                    class="order-2 md:order-1 promo-content flex flex-col gap-3 sm:gap-5 items-start justify-center p-5 md:p-10 lg:p-14 2xl:p-20">
                    <h2
                        class="lg:text-3xl md:text-2xl text-xl text-white font-bold md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2">
                        Your Satisfaction, Our Priority - Reach Out Today
                    </h2>
                    <p class="text-xs text-white md:pr-7 lg:pr-14 2xl:pr-20">
                        Our team is ready to assist you. Reach out to us via email, phone,
                        or live chat, and we'll get back to you as soon as possible.
                    </p>
                    <a href="#"
                        class="theme-btn bg-[#5A422A] px-5 py-2 lg:px-7 lg:py-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm">Learn
                        More</a>
                </div>
                <div class="promo-image order-1">
                    <div class="img-wrap w-full">
                        <div class="w-full lg:h-96 md:h-80 h-40 rounded-lg md:rounded-3xl overflow-hidden">
                            <a href="#" class="w-full h-full block">
                                <img src="{{ asset('assets/frontend/images/promo-banner-image.png') }}" alt="A man viewing a large size Laptop"
                                    class="w-full h-full object-cover" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Page Promotion Banner Ended -->

        <!-- Contact info & Images Starts -->
        <section class="contact-us-section section-padding container">
            <div class="flex flex-col md:flex-row gap-8 xl:mb-24 lg:mb-10 md:mb-14 sm:mb-52 mb-44">
                <!-- Left Content -->
                <div
                    class="w-full md:w-2/5 lg:w-2/6 space-y-3 md:space-y-4 lg:space-y-8 text-theme-dark md:pr-2 lg:pr-5 text-sm lg:text-base">
                    <div>
                        <h1 class="text-xl md:text-2xl lg:text-3xl mb-3 md:mb-4">
                            DISCOVER US
                        </h1>
                        <p>Flick is here to help you:</p>
                        <p>
                            Our experts are available to answer any questions you might
                            have. We've got the answers.
                        </p>
                    </div>

                    <div class="space-y-2 md:space-y-3">
                        <h2 class="text-lg md:text-xl lg:text-2xl">VISIT US</h2>
                        <a href="https://maps.app.goo.gl/zWrLsjDqgfVLAGts5" target="_blank"
                            class="inline-block hover:text-light-yellow underline eq">
                            House: 4, Road: 3, Block: J, Banasree, Rampura, Dhaka 1219
                        </a>
                        <p class="font-light pt-2">
                            Feel free to get in touch with us through our channels.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-xl mb-2 md:mb-3">EMAIL US</h2>
                        <a href="mailto:tesko546@gmail.com" target="_blank"
                            class="inline-block hover:text-light-yellow eq">tesko544@gmail.com</a>
                    </div>

                    <div class="flex flex-col items-start">
                        <h2 class="text-xl mb-2 md:mb-3">CALL US</h2>
                        <a href="tel:+8801737413566" class="inline-block hover:text-light-yellow eq">+880 1737-413566</a>
                        <a href="tel:+8801826580966" class="inline-block hover:text-light-yellow eq">+880 1826-580966</a>
                    </div>
                </div>

                <!-- Right Image Grid -->
                <div class="w-full md:w-3/5 lg:w-4/6 relative">
                    <div class="flex justify-center gap-3 sm:gap-5 lg:gap-10">
                        <div
                            class="border-2 sm:border-[0.4rem] border-white w-1/3 h-48 sm:h-60 lg:h-80 z-10 overflow-hidden">
                            <img src="{{ asset('assets/frontend/images/contact-us-1.png') }} " alt="Warehouse interior with blue shelving"
                                class="w-full h-full object-cover" />
                        </div>

                        <div
                            class="border-2 sm:border-[0.4rem] border-white w-1/3 h-48 sm:h-60 lg:h-80 z-10 overflow-hidden mt-20">
                            <img src="{{ asset('assets/frontend/images/contact-us-2.png') }} " alt="Building interior"
                                class="w-full h-full object-cover" />
                        </div>
                    </div>

                    <div class="flex justify-start gap-3 sm:gap-5 lg:gap-10 absolute top-36 sm:top-44 lg:top-48 left-0">
                        <div
                            class="border-2 sm:border-[0.4rem] border-white w-1/3 h-48 sm:h-60 lg:h-80 z-10 overflow-hidden">
                            <img src="{{ asset('assets/frontend/images/contact-us-3.png') }} " alt="Warehouse interior with blue shelving"
                                class="w-full h-full object-cover" />
                        </div>

                        <div
                            class="border-2 sm:border-[0.4rem] border-white w-1/3 h-48 sm:h-60 lg:h-80 z-10 overflow-hidden mt-20">
                            <img src="{{ asset('assets/frontend/images/contact-us-4.png') }} " alt="Building interior"
                                class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact info & Images Ended -->

        <!-- Contact Form Starts -->
        <section class="bg-theme-dark relative">
            <!-- Background Text -->
            <h1
                class="outline-text text-5xl xsm:text-7xl sm:text-8xl md:text-7xl lg:text-[9.5vw] font-[arial] font-bold opacity-30 z-0 absolute left-0 top-0 leading-none">
                Contact Us
            </h1>

            <!-- contact contents -->
            <div class="container section-padding overflow-hidden">
                <div class="grid md:grid-cols-2 gap-4 md:gap-8 lg:gap-12 relative z-10">
                    <!-- Left Content -->
                    <div class="space-y-2 md:space-y-4 lg:space-y-6 my-4 md:my-0 xl:my-5 text-theme-light">
                        <div>
                            <h2 class="text-3xl xsm:text-4xl lg:text-5xl font-bold leading-normal 2xl:leading-relaxed">
                                Need help? <br />
                                We're here
                            </h2>
                        </div>
                        <p class="text-sm sm:text-base lg:text-lg w-3/4">
                            Our customer support team is ready to assist you. Whether you
                            have questions about your order, products, or need help with
                            returns, we're just a message away.
                        </p>
                    </div>

                    <!-- Right Form -->
                    <form action="#" class="w-full block space-y-8 pb-5">
                        <div
                            class="sm:grid md:block lg:grid sm:grid-cols-2 sm:gap-6 sm:space-y-0 space-y-6 md:space-y-6 lg:space-y-0">
                            <!-- name -->
                            <div class="from-ctrl relative z-0">
                                <input type="text" id="your-name"
                                    class="block py-2.5 px-0 w-full text-sm text-theme-light bg-transparent border-0 border-b border-theme-light appearance-none focus:outline-none focus:ring-0 focus:border-light-yellow peer"
                                    placeholder=" " />
                                <label for="your-name"
                                    class="absolute text-sm text-theme-light eq transform -translate-y-6 scale-90 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-light-yellow peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Your
                                    name</label>
                            </div>

                            <!-- email -->
                            <div class="from-ctrl relative z-0">
                                <input type="email" id="your-email"
                                    class="block py-2.5 px-0 w-full text-sm text-theme-light bg-transparent border-0 border-b border-theme-light appearance-none focus:outline-none focus:ring-0 focus:border-light-yellow peer"
                                    placeholder=" " />
                                <label for="your-email"
                                    class="absolute text-sm text-theme-light eq transform -translate-y-6 scale-90 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-light-yellow peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Your
                                    Email</label>
                            </div>

                            <!-- phone number -->
                            <div class="from-ctrl relative z-0">
                                <input type="tel" id="phone-number"
                                    class="block py-2.5 px-0 w-full text-sm text-theme-light bg-transparent border-0 border-b border-theme-light appearance-none focus:outline-none focus:ring-0 focus:border-light-yellow peer"
                                    placeholder=" " />
                                <label for="phone-number"
                                    class="absolute text-sm text-theme-light eq transform -translate-y-6 scale-90 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-light-yellow peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Phone
                                    Number</label>
                            </div>

                            <!-- order status -->
                            <div class="from-ctrl relative z-0">
                                <label for="order-status" class="sr-only">Order Status</label>
                                <select id="order-status"
                                    class="block py-2.5 px-0 w-full text-sm text-theme-light bg-transparent border-0 border-b border-theme-light appearance-none focus:outline-none focus:ring-0 focus:border-light-yellow peer">
                                    <option disabled selected class="text-theme-dark">
                                        Order Status
                                    </option>
                                    <option value="processing" class="text-theme-dark">
                                        Processing
                                    </option>
                                    <option value="shipped" class="text-theme-dark">
                                        Shipped
                                    </option>
                                    <option value="delivered" class="text-theme-dark">
                                        Delivered
                                    </option>
                                    <option value="no-order" class="text-theme-dark">
                                        No Order
                                    </option>
                                </select>
                            </div>

                            <!-- order number (optional) -->
                            <div class="from-ctrl relative z-0">
                                <input type="text" id="order-number"
                                    class="block py-2.5 px-0 w-full text-sm text-theme-light bg-transparent border-0 border-b border-theme-light appearance-none focus:outline-none focus:ring-0 focus:border-light-yellow peer"
                                    placeholder=" " />
                                <label for="order-number"
                                    class="absolute text-sm text-theme-light eq transform -translate-y-6 scale-90 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-light-yellow peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Order
                                    Number (if applicable)</label>
                            </div>

                            <!-- inquiry type -->
                            <div class="from-ctrl relative z-0">
                                <label for="inquiry-type" class="sr-only">Inquiry Type</label>
                                <select id="inquiry-type"
                                    class="block py-2.5 px-0 w-full text-sm text-theme-light bg-transparent border-0 border-b border-theme-light appearance-none focus:outline-none focus:ring-0 focus:border-light-yellow peer">
                                    <option disabled selected class="text-theme-dark">
                                        Inquiry Type
                                    </option>
                                    <option value="order" class="text-theme-dark">
                                        Order Status
                                    </option>
                                    <option value="product" class="text-theme-dark">
                                        Product Information
                                    </option>
                                    <option value="return" class="text-theme-dark">
                                        Returns & Refunds
                                    </option>
                                    <option value="shipping" class="text-theme-dark">
                                        Shipping & Delivery
                                    </option>
                                    <option value="account" class="text-theme-dark">
                                        Account Issues
                                    </option>
                                    <option value="other" class="text-theme-dark">Other</option>
                                </select>
                            </div>

                            <!-- message -->
                            <div class="from-ctrl relative z-0 col-span-2">
                                <textarea rows="2" id="message"
                                    class="block py-2.5 px-0 w-full text-sm text-theme-light bg-transparent border-0 border-b border-theme-light appearance-none focus:outline-none focus:ring-0 focus:border-light-yellow peer"
                                    placeholder=" "></textarea>
                                <label for="message"
                                    class="absolute text-sm text-theme-light eq transform -translate-y-6 scale-90 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-light-yellow peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">How
                                    can we help you?</label>
                            </div>
                        </div>

                        <button type="submit"
                            class="bg-light-yellow hover:bg-theme-light hover:text-theme-dark eq sm:px-8 px-5 py-1.5">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </section>
        <!-- Contact Form Ended -->

        <!-- Location on Google Map Starts -->
        <section class="-mb-40 md:-mb-24">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2886.0331494319375!2d90.42427297410089!3d23.764442388239445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b90061b5f8cd%3A0x467b7d00a5480374!2sSpinner%20Tech%20%7C%20Software%20%26%20Mobile%20App%20Development!5e1!3m2!1sen!2sbd!4v1742043182012!5m2!1sen!2sbd"
                class="w-full h-96 lg:h-[32rem] border-0" frameborder="0" allowfullscreen loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>
        <!-- Location on Google Map Ended -->
    </main>

    @push('scripts')
    @endpush
@endsection
