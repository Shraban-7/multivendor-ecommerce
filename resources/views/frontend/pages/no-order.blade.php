@extends('frontend.layouts.app')

@section('title','No Order')

@section('content')
<main class="no-order-page">
      <!-- Promotional Header Starts -->
      <section>
        <a
          href="#"
          class="block promo-header bg-light-yellow text-white py-3 sm:py-4"
        >
          <div
            class="container flex flex-wrap justify-center xsm:justify-start items-center gap-x-2"
          >
            <i class="fa-solid fa-truck-fast text-lg"></i>
            <h3 class="text-sm">Free Shipping Special For You</h3>
            <p class="text-xs ml-2 xsm:ml-3">Limited Offer</p>
          </div>
        </a>
      </section>
      <!-- Promotional Header Ended -->

      <!-- No Order Section Starts -->
      <section>
        <div
          class="no-order-contents flex flex-col gap-5 md:gap-8 items-center text-center section-padding"
        >
          <div class="no-order-img w-1/4 md:w-3/12 lg:w-2/12">
            <img
              src="{{ asset('assets/frontend/images/no-order.png') }}"
              alt="A Empty Cart Image with Red Rounded Crosh Icon"
              class="object-contain"
            />
          </div>

          <div class="info space-y-2 md:space-y-4">
            <h2 class="text-xl md:text-2xl font-medium text-theme-dark">
              No Orders in this account
            </h2>
            <p class="md:text-lg text-jet-gray">
              If you remember ordering before,
              <a href="#" class="text-butterfly-blue hover:text-primary eq"
                >switch account</a
              >
              or
              <a href="#" class="text-butterfly-blue hover:text-primary eq"
                >Q & A</a
              >
            </p>
          </div>
        </div>
      </section>
      <!-- No Order Section Ended -->

      <!-- Explore Interest Section Start  -->
      <section class="explore-interest section-padding">
        <div class="container">
          <!-- Section Tittle -->
          <h1
            class="text-xl sm:text-2xl lg:text-3xl font-medium text-jet-gray mb-5 md:mb-8 lg:mb-10"
          >
            Explore Your Interest
          </h1>

          <div
            class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-3 gap-5 xl:gap-8 lg:p-0 p-2 items-start"
          >
            <!-- Product Card 1 -->
            <div
              class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
            >
              <div
                class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="{{ asset('assets/frontend/images/interest-prod-1.png') }}"
                    alt="The Iconic Doeskin Blazer"
                    class="w-full h-full object-cover"
                  />
                </a>
                <button
                  class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
                >
                  <i class="fa-regular fa-eye"></i>
                  Quick View
                </button>
              </div>

              <div class="p-4 xsm:p-2 lg:p-5">
                <h3
                  class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3"
                >
                  <a href="#" class="hover:text-primary eq"
                    >The Iconic Doeskin Blazer</a
                  >
                </h3>
                <p class="text-leaf-green">Almost sold Out</p>

                <div class="flex flex-wrap items-center gap-x-1">
                  <div
                    class="flex items-center flex-no-wrap gap-x-1 text-light-yellow"
                  >
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <img
                      src="{{ asset('assets/frontend/images/fire-icon.png') }}"
                      class="w-8 h-auto"
                      alt="Fire Icon"
                    />
                  </div>

                  <span class="text-jet-gray">4.5K+ Sold</span>
                </div>

                <div
                  class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2"
                >
                  <span class="text-primary/80">Final Hours</span>
                  <div
                    class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8"
                  >
                    <div class="price flex items-center gap-1 flex-no-wrap">
                      <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                      <span class="align-center text-sm text-[#ffa755]">$</span>
                      <h3 class="font-bold text-primary">25.89</h3>
                    </div>
                    <div>
                      <button
                        class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq"
                      >
                        <i class="fa-solid fa-cart-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Product Card 2 -->
            <div
              class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
            >
              <div
                class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="{{ asset('assets/frontend/images/interest-prod-2.png') }}"
                    alt="Solid Polo T-Shirts From Tommy Hilfiger"
                    class="w-full h-full object-cover"
                  />
                </a>
                <button
                  class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
                >
                  <i class="fa-regular fa-eye"></i>
                  Quick View
                </button>
              </div>

              <div class="p-4 xsm:p-2 lg:p-5">
                <h3
                  class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3"
                >
                  <a href="#" class="hover:text-primary eq"
                    >Solid Polo T-Shirts From Tommy Hilfiger</a
                  >
                </h3>
                <p class="text-leaf-green">Almost sold Out</p>

                <div class="flex flex-wrap items-center gap-x-1">
                  <div
                    class="flex items-center flex-no-wrap gap-x-1 text-light-yellow"
                  >
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <img
                      src="{{ asset('assets/frontend/images/fire-icon.png') }}"
                      class="w-8 h-auto"
                      alt="Fire Icon"
                    />
                  </div>

                  <span class="text-jet-gray">2.8K+ Sold</span>
                </div>

                <div
                  class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2"
                >
                  <span class="text-primary/80">Final Hours</span>
                  <div
                    class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8"
                  >
                    <div class="price flex items-center gap-1 flex-no-wrap">
                      <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                      <span class="align-center text-sm text-[#ffa755]">$</span>
                      <h3 class="font-bold text-primary">30.50</h3>
                    </div>
                    <div>
                      <button
                        class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq"
                      >
                        <i class="fa-solid fa-cart-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Product Card 3 -->
            <div
              class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
            >
              <div
                class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="{{ asset('assets/frontend/images/interest-prod-3.png') }}"
                    alt="Clark Multiple Color Silicone Navy Dial Watch"
                    class="w-full h-full object-cover"
                  />
                </a>
                <button
                  class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
                >
                  <i class="fa-regular fa-eye"></i>
                  Quick View
                </button>
              </div>

              <div class="p-4 xsm:p-2 lg:p-5">
                <h3
                  class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3"
                >
                  <a href="#" class="hover:text-primary eq"
                    >Clark Multiple Color Silicone Navy Dial Watch</a
                  >
                </h3>
                <p class="text-leaf-green">Almost sold Out</p>

                <div class="flex flex-wrap items-center gap-x-1">
                  <div
                    class="flex items-center flex-no-wrap gap-x-1 text-light-yellow"
                  >
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <img
                      src="{{ asset('assets/frontend/images/fire-icon.png') }}"
                      class="w-8 h-auto"
                      alt="Fire Icon"
                    />
                  </div>

                  <span class="text-jet-gray">1.2K+ Sold</span>
                </div>

                <div
                  class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2"
                >
                  <span class="text-primary/80">Final Hours</span>
                  <div
                    class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8"
                  >
                    <div class="price flex items-center gap-1 flex-no-wrap">
                      <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                      <span class="align-center text-sm text-[#ffa755]">$</span>
                      <h3 class="font-bold text-primary">45.34</h3>
                    </div>
                    <div>
                      <button
                        class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq"
                      >
                        <i class="fa-solid fa-cart-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Product Card 4 -->
            <div
              class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
            >
              <div
                class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="{{ asset('assets/frontend/images/interest-prod-4.png') }}"
                    alt="Rebook Classic Leather Double Sneakers"
                    class="w-full h-full object-cover"
                  />
                </a>
                <button
                  class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
                >
                  <i class="fa-regular fa-eye"></i>
                  Quick View
                </button>
              </div>

              <div class="p-4 xsm:p-2 lg:p-5">
                <h3
                  class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3"
                >
                  <a href="#" class="hover:text-primary eq"
                    >Rebook Classic Leather Double Sneakers</a
                  >
                </h3>
                <p class="text-leaf-green">Almost sold Out</p>

                <div class="flex flex-wrap items-center gap-x-1">
                  <div
                    class="flex items-center flex-no-wrap gap-x-1 text-light-yellow"
                  >
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <img
                      src="{{ asset('assets/frontend/images/fire-icon.png') }}"
                      class="w-8 h-auto"
                      alt="Fire Icon"
                    />
                  </div>

                  <span class="text-jet-gray">6.2K+ Sold</span>
                </div>

                <div
                  class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2"
                >
                  <span class="text-primary/80">Final Hours</span>
                  <div
                    class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8"
                  >
                    <div class="price flex items-center gap-1 flex-no-wrap">
                      <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                      <span class="align-center text-sm text-[#ffa755]">$</span>
                      <h3 class="font-bold text-primary">80.00</h3>
                    </div>
                    <div>
                      <button
                        class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq"
                      >
                        <i class="fa-solid fa-cart-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Product Card 5 -->
            <div
              class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
            >
              <div
                class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="{{ asset('assets/frontend/images/interest-prod-5.png') }}"
                    alt="Classic Men Bleech Fleece Short"
                    class="w-full h-full object-cover"
                  />
                </a>
                <button
                  class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
                >
                  <i class="fa-regular fa-eye"></i>
                  Quick View
                </button>
              </div>

              <div class="p-4 xsm:p-2 lg:p-5">
                <h3
                  class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3"
                >
                  <a href="#" class="hover:text-primary eq"
                    >Classic Men Bleech Fleece Short</a
                  >
                </h3>
                <p class="text-leaf-green">Almost sold Out</p>

                <div class="flex flex-wrap items-center gap-x-1">
                  <div
                    class="flex items-center flex-no-wrap gap-x-1 text-light-yellow"
                  >
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <img
                      src="{{ asset('assets/frontend/images/fire-icon.png') }}"
                      class="w-8 h-auto"
                      alt="Fire Icon"
                    />
                  </div>

                  <span class="text-jet-gray">4.8K+ Sold</span>
                </div>

                <div
                  class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2"
                >
                  <span class="text-primary/80">Final Hours</span>
                  <div
                    class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8"
                  >
                    <div class="price flex items-center gap-1 flex-no-wrap">
                      <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                      <span class="align-center text-sm text-[#ffa755]">$</span>
                      <h3 class="font-bold text-primary">30.50</h3>
                    </div>
                    <div>
                      <button
                        class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq"
                      >
                        <i class="fa-solid fa-cart-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Product Card 6 -->
            <div
              class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
            >
              <div
                class="relative h-60 xsm:h-48 sm:h-56 sm:h-90 lg:h-[17rem] xl:h-[22rem] overflow-hidden rounded-lg"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="{{ asset('assets/frontend/images/interest-prod-6.png') }}"
                    alt="Enamel Flag Alay Leather Belt"
                    class="w-full h-full object-cover"
                  />
                </a>
                <button
                  class="absolute bottom-10 xsm:bottom-3 lg:bottom-10 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
                >
                  <i class="fa-regular fa-eye"></i>
                  Quick View
                </button>
              </div>

              <div class="p-4 xsm:p-2 lg:p-5">
                <h3
                  class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14 lg:w-3/4 xl:w-2/3"
                >
                  <a href="#" class="hover:text-primary eq"
                    >Enamel Flag Alay Leather Belt</a
                  >
                </h3>
                <p class="text-leaf-green">Almost sold Out</p>

                <div class="flex flex-wrap items-center gap-x-1">
                  <div
                    class="flex items-center flex-no-wrap gap-x-1 text-light-yellow"
                  >
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <img
                      src="{{ asset('assets/frontend/images/fire-icon.png') }}"
                      class="w-8 h-auto"
                      alt="Fire Icon"
                    />
                  </div>

                  <span class="text-jet-gray">8.7K+ Sold</span>
                </div>

                <div
                  class="flex flex-wrap items-center gap-x-5 xsm:gap-x-1 sm:gap-x-2 xl:mt-2"
                >
                  <span class="text-primary/80">Final Hours</span>
                  <div
                    class="flex items-center gap-x-5 xsm:gap-x-2 sm:gap-x-5 xl:gap-x-8"
                  >
                    <div class="price flex items-center gap-1 flex-no-wrap">
                      <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                      <span class="align-center text-sm text-[#ffa755]">$</span>
                      <h3 class="font-bold text-primary">20.25</h3>
                    </div>
                    <div>
                      <button
                        class="text-xs xsm:text-[10px] sm:text-base md:text-xs xl:text-base w-7 h-7 xsm:w-6 xsm:h-6 md:w-8 md:h-8 sm:w-10 sm:h-10 xl:w-10 xl:h-10 flex items-center justify-center bg-primary rounded-full text-white hover:bg-theme-dark eq"
                      >
                        <i class="fa-solid fa-cart-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Load More Products Button -->
          <div class="load-more-btn text-center pt-10">
            <button
              class="theme-btn bg-theme-teal hover:bg-aqua-deep text-white px-5 py-2 xl:text-xl text-base md:text-lg inline-flex gap-2 items-center eq"
              type="button"
            >
              <span>Load More</span>
              <i class="fa-solid fa-caret-down"></i>
            </button>
          </div>
        </div>
      </section>
      <!-- Explore Interest Section Ended  -->
    </main>
@endsection
