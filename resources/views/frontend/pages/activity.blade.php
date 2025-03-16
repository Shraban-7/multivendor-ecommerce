@extends('seller.layouts.app')
@section('title','Actions')

@section('content')
<main class="activity-page">
      <!-- Page Main Content Starts -->
      <section class="container activity-section section-padding">
        <!-- User Info -->
        <div
          class="flex flex-wrap items-center gap-4 mb-6 user-header sm:gap-5 lg:mb-12 md:mb-8"
        >
          <div
            class="overflow-hidden rounded-full user-dp size-12 md:size-16 lg:size-20"
          >
            <img
              src="./assests/images/activity-avatar.png"
              alt="A man smiling with Orange Shirt & Orange Backgorund"
            />
          </div>

          <h1 class="text-lg font-medium md:text-xl lg:text-2xl text-davy-gray">
            John Doe
          </h1>

          <span
            class="bg-butterfly-blue text-white px-2.5 py-1.5 md:px-4 md:py-2 lg:px-6 lg:py-3 rounded-3xl inline-block"
          >
            My Activity
          </span>
        </div>

        <!-- Product Card's Wrapper -->
        <div
          class="grid grid-cols-1 gap-3 xsm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6"
        >
          <!-- Product Card 1 -->
          <div
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-1.png"
                  alt="ASUS Vivo15 OLED K513 Core-i5 11th Gen 15.6″ FHD Laptop"
                  class="object-cover w-full h-full"
                />
              </a>
              <button
                class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12"
              >
                <a
                  href="#"
                  class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq"
                  >ASUS Vivo15 OLED K513 Core-i5 11th Gen 15.6″ FHD Laptop</a
                >
              </h3>
              <p class="text-leaf-green">Almost sold Out</p>

              <div class="flex flex-wrap items-center gap-x-1">
                <div
                  class="flex flex-no-wrap items-center gap-x-1 text-light-yellow"
                >
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <img
                    src="./assests/images/fire-icon.png"
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
                  <div class="flex flex-no-wrap items-center gap-1 price">
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
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-2.png"
                  alt="Apple watch series 10 depth rainmaker"
                  class="object-cover w-full h-full"
                />
              </a>
              <button
                class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12"
              >
                <a
                  href="#"
                  class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq"
                  >Apple watch series 10 depth rainmaker</a
                >
              </h3>
              <p class="text-leaf-green">Almost sold Out</p>

              <div class="flex flex-wrap items-center gap-x-1">
                <div
                  class="flex flex-no-wrap items-center gap-x-1 text-light-yellow"
                >
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <img
                    src="./assests/images/fire-icon.png"
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
                  <div class="flex flex-no-wrap items-center gap-1 price">
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
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-3.png"
                  alt="Quadcopter With Height Hold, App Control, And Obstacle For flying"
                  class="object-cover w-full h-full"
                />
              </a>
              <button
                class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12"
              >
                <a
                  href="#"
                  class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq"
                  >Quadcopter With Height Hold, App Control, And Obstacle For
                  flying</a
                >
              </h3>
              <p class="text-leaf-green">Almost sold Out</p>

              <div class="flex flex-wrap items-center gap-x-1">
                <div
                  class="flex flex-no-wrap items-center gap-x-1 text-light-yellow"
                >
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <img
                    src="./assests/images/fire-icon.png"
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
                  <div class="flex flex-no-wrap items-center gap-1 price">
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
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-4.png"
                  alt="Sports Wireless Headphones, ANC and ENC Headphone"
                  class="object-cover w-full h-full"
                />
              </a>
              <button
                class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12"
              >
                <a
                  href="#"
                  class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq"
                  >Sports Wireless Headphones, ANC and ENC Headphone</a
                >
              </h3>
              <p class="text-leaf-green">Almost sold Out</p>

              <div class="flex flex-wrap items-center gap-x-1">
                <div
                  class="flex flex-no-wrap items-center gap-x-1 text-light-yellow"
                >
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <img
                    src="./assests/images/fire-icon.png"
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
                  <div class="flex flex-no-wrap items-center gap-1 price">
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
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-5.png"
                  alt="SAMSUNG GALAXY A15 LTE Blue 6 +128GB Dual Sim, Smartphone"
                  class="object-cover w-full h-full"
                />
              </a>
              <button
                class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12"
              >
                <a
                  href="#"
                  class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq"
                  >SAMSUNG GALAXY A15 LTE Blue 6 +128GB Dual Sim, Smartphone</a
                >
              </h3>
              <p class="text-leaf-green">Almost sold Out</p>

              <div class="flex flex-wrap items-center gap-x-1">
                <div
                  class="flex flex-no-wrap items-center gap-x-1 text-light-yellow"
                >
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <img
                    src="./assests/images/fire-icon.png"
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
                  <div class="flex flex-no-wrap items-center gap-1 price">
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
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-sm xl:text-base rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative overflow-hidden rounded-lg h-60 xsm:h-48 sm:h-56 lg:h-56 xl:h-64 2xl:h-60"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-6.png"
                  alt="Electric
                  Bike, 500W Motor, 14'' Tire Folding Mini Ebikes"
                  class="object-cover w-full h-full"
                />
              </a>
              <button
                class="absolute flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-lg bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 hover:bg-primary hover:text-white eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium lg:mb-2 xl:mb-0 xsm:h-10 sm:h-12 md:h-10 lg:h-14 xl:h-12"
              >
                <a
                  href="#"
                  class="line-clamp-2 lg:line-clamp-3 xl:line-clamp-2 hover:text-primary eq"
                  >Electric Bike, 500W Motor, 14" Tire Folding Mini Ebikes</a
                >
              </h3>
              <p class="text-leaf-green">Almost sold Out</p>

              <div class="flex flex-wrap items-center gap-x-1">
                <div
                  class="flex flex-no-wrap items-center gap-x-1 text-light-yellow"
                >
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <img
                    src="./assests/images/fire-icon.png"
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
                  <div class="flex flex-no-wrap items-center gap-1 price">
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

        <!-- Load More Btn -->
        <div class="mt-10 text-center load-more-btn">
          <button
            class="inline-flex items-center gap-2 px-5 py-2 text-base text-white theme-btn bg-theme-teal hover:bg-aqua-deep xl:text-xl md:text-lg eq"
            type="button"
          >
            <span>Load More</span>
            <i class="text-sm fa-solid fa-chevron-down"></i>
          </button>
        </div>
      </section>
      <!-- Page Main Content Ended -->
    </main>
@endsection
