@extends('seller.layouts.app')
@section('title','Feature Shop')

@section('content')
<main class="featured-shops-page">
      <!-- Page Promotion Banner Starts -->
      <section class="container py-5 page-promotion md:w-full">
        <div
          class="promo-wrapper md:container bg-[#6B54B7] grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden"
        >
          <div
            class="flex flex-col items-start justify-center order-2 gap-3 p-5 md:order-1 promo-content sm:gap-5 md:p-10 lg:p-14 2xl:p-20"
          >
            <h2
              class="text-xl font-bold text-white lg:text-3xl md:text-2xl md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2"
            >
              Your One-Stop Shop for Everything You Love!" 🛍️🚀
            </h2>
            <p class="text-xs text-white md:pr-7 lg:pr-14 2xl:pr-20">
              Whether it's fashion, electronics, home essentials, or more, grab
              massive discounts and special deals before they're gone!
            </p>
            <a
              href="#"
              class="theme-btn bg-[#5A422A] px-5 py-2 lg:px-7 lg:py-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm"
              >Learn More</a
            >
          </div>
          <div class="order-1 promo-image">
            <div class="w-full img-wrap">
              <div
                class="w-full h-40 overflow-hidden rounded-lg lg:h-96 md:h-80 md:rounded-3xl"
              >
                <a href="#" class="block w-full h-full">
                  <img
                    src="./assests/images/promo-banner-image-2.png"
                    alt="A man viewing a large size Laptop"
                    class="object-cover w-full h-full"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Page Promotion Banner Ended -->

      <!-- Page Main Content Starts -->
      <section class="container feature-shops-section section-padding">
        <div class="shops-categories-wrapper space-y-14">
          <!-- Featured Shops -->
          <div class="featured-shop-category">
            <div class="flex items-center justify-between category-title">
              <h2
                class="text-xl font-medium capitalize sm:text-2xl text-davy-gray"
              >
                Featured shop
              </h2>

              <a
                href="#"
                class="flex items-center gap-1 text-sm sm:text-base text-sand-brown group/link hover:text-primary eq"
                >See All
                <i
                  class="text-xs fa-solid fa-chevron-right sm:text-sm lg:group-hover/link:translate-x-1 eq"
                ></i
              ></a>
            </div>
            <div
              class="flex flex-wrap items-center gap-2 mt-4 shops sm:gap-3 md:gap-5 md:mt-5"
            >
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-1.png"
                  alt="Nike Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-2.png"
                  alt="Birds Eye Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-3.png"
                  alt="Hovis Bakery Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-4.png"
                  alt="OXO Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-5.png"
                  alt="WDC24 Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-1.png"
                  alt="Nike Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-2.png"
                  alt="Birds Eye Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-3.png"
                  alt="Hovis Bakery Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-4.png"
                  alt="OXO Logo"
                />
              </a>
              <a
                href="#"
                class="inline-block shop-image size-12 md:size-14 lg:size-16"
              >
                <img
                  src="./assests/images/featured-shop-5.png"
                  alt="WDC24 Logo"
                />
              </a>
            </div>
          </div>

          <!-- Shop By Price -->
          <div class="price-category">
            <div class="flex items-center justify-between category-title">
              <h2
                class="text-xl font-medium capitalize sm:text-2xl text-davy-gray"
              >
                Shop by price
              </h2>

              <a
                href="#"
                class="flex items-center gap-1 text-sm sm:text-base text-sand-brown group/link hover:text-primary eq"
                >See All
                <i
                  class="text-xs fa-solid fa-chevron-right sm:text-sm lg:group-hover/link:translate-x-1 eq"
                ></i
              ></a>
            </div>

            <div
              class="flex flex-wrap items-center gap-3 mt-4 prices sm:gap-4 md:gap-5 md:mt-5"
            >
              <a href="#" class="inline-block price-range">
                <h3
                  class="inline-flex gap-1 px-4 py-2 text-xl font-bold md:text-2xl flex-nowrap bg-jet-gray/10 md:px-6 md:py-3 rounded-xl hover:bg-jet-gray/20 text-theme-dark eq"
                >
                  <p>
                    <span class="text-base font-medium align-text-top">$</span>1
                  </p>
                  <span> — </span>
                  <p>
                    <span class="text-base font-medium align-text-top">$</span>2
                  </p>
                </h3>
              </a>
              <a href="#" class="inline-block price-range">
                <h3
                  class="inline-flex gap-1 px-4 py-2 text-xl font-bold md:text-2xl flex-nowrap bg-jet-gray/10 md:px-6 md:py-3 rounded-xl hover:bg-jet-gray/20 text-theme-dark eq"
                >
                  <p>
                    <span class="text-base font-medium align-text-top">$</span>2
                  </p>
                  <span> — </span>
                  <p>
                    <span class="text-base font-medium align-text-top">$</span>5
                  </p>
                </h3>
              </a>
              <a href="#" class="inline-block price-range">
                <h3
                  class="inline-flex gap-1 px-4 py-2 text-xl font-bold md:text-2xl flex-nowrap bg-jet-gray/10 md:px-6 md:py-3 rounded-xl hover:bg-jet-gray/20 text-theme-dark eq"
                >
                  <p>
                    <span class="text-base font-medium align-text-top">$</span>5
                  </p>
                  <span> — </span>
                  <p>
                    <span class="text-base font-medium align-text-top">$</span
                    >10
                  </p>
                </h3>
              </a>
              <a href="#" class="inline-block price-range">
                <h3
                  class="inline-flex gap-1 px-4 py-2 text-lg md:text-xl flex-nowrap bg-jet-gray/10 md:px-6 md:py-3 rounded-xl hover:bg-jet-gray/20 text-theme-dark eq"
                >
                  Premium
                </h3>
              </a>
            </div>
          </div>

          <!-- Shop By Grocery -->
          <div class="product-grocery">
            <div class="flex items-center justify-between category-title">
              <h2
                class="text-xl font-medium capitalize sm:text-2xl text-davy-gray"
              >
                Shop by Grocery
              </h2>

              <a
                href="#"
                class="flex items-center gap-1 text-sm sm:text-base text-sand-brown group/link hover:text-primary eq"
                >See All
                <i
                  class="text-xs fa-solid fa-chevron-right sm:text-sm lg:group-hover/link:translate-x-1 eq"
                ></i
              ></a>
            </div>

            <div
              class="flex flex-wrap items-center gap-6 mt-4 groceries md:gap-10 lg:gap-12 md:mt-5 text-davy-gray"
            >
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-1.png"
                    alt="Fresh Foods"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Fresh Foods
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-2.png"
                    alt="Meat & Sea food"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Meats & Sea foods
                </h5>
              </a>

              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-3.png"
                    alt="Snacks"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Snacks
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-4.png"
                    alt="Breakfast & Cereal"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Breakfast & Cereal
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-5.png"
                    alt="Bakery"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Bakery
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-6.png"
                    alt="Dairy & Eggs"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Dairy & Eggs
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-7.png"
                    alt="Frozen"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Frozen
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/feature-category-8.png"
                    alt="Coffee & Tea"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Coffee & Tea
                </h5>
              </a>
            </div>
          </div>

          <!-- Shop By Brand -->
          <div class="brand-category">
            <div class="flex items-center justify-between category-title">
              <h2
                class="text-xl font-medium capitalize sm:text-2xl text-davy-gray"
              >
                Shop by Brand
              </h2>

              <a
                href="#"
                class="flex items-center gap-1 text-sm sm:text-base text-sand-brown group/link hover:text-primary eq"
                >See All
                <i
                  class="text-xs fa-solid fa-chevron-right sm:text-sm lg:group-hover/link:translate-x-1 eq"
                ></i
              ></a>
            </div>

            <div
              class="flex flex-wrap items-center gap-4 mt-4 brands sm:gap-5 md:gap-8 lg:gap-10 md:mt-5 text-davy-gray"
            >
              <a
                href="#"
                class="brand-item bg-jet-gray/10 p-2 md:p-3 rounded-full size-[4.5rem] md:size-20 lg:size-24 hover:bg-jet-gray/20 eq group/brand"
              >
                <div class="flex items-center justify-center w-full h-full">
                  <img
                    src="./assests/images/brand-shop-1.png"
                    alt="Better Goods"
                    class="object-contain w-full h-full eq"
                  />
                </div>
              </a>
              <a
                href="#"
                class="brand-item bg-jet-gray/10 p-2 md:p-3 rounded-full size-[4.5rem] md:size-20 lg:size-24 hover:bg-jet-gray/20 eq group/brand"
              >
                <div class="flex items-center justify-center w-full h-full">
                  <img
                    src="./assests/images/brand-shop-2.png"
                    alt="Marketside"
                    class="object-contain w-full h-full eq"
                  />
                </div>
              </a>
              <a
                href="#"
                class="brand-item bg-jet-gray/10 p-2 md:p-3 rounded-full size-[4.5rem] md:size-20 lg:size-24 hover:bg-jet-gray/20 eq group/brand"
              >
                <div class="flex items-center justify-center w-full h-full">
                  <img
                    src="./assests/images/brand-shop-3.png"
                    alt="Great Value"
                    class="object-contain w-full h-full eq"
                  />
                </div>
              </a>
              <a
                href="#"
                class="brand-item bg-jet-gray/10 p-2 md:p-3 rounded-full size-[4.5rem] md:size-20 lg:size-24 hover:bg-jet-gray/20 eq group/brand"
              >
                <div class="flex items-center justify-center w-full h-full">
                  <img
                    src="./assests/images/brand-shop-4.png"
                    alt="Prima Della"
                    class="object-contain w-full h-full eq"
                  />
                </div>
              </a>
            </div>
          </div>

          <!-- Shop By Category -->
          <div class="product-category">
            <div class="flex items-center justify-between category-title">
              <h2
                class="text-xl font-medium capitalize sm:text-2xl text-davy-gray"
              >
                Shop by Category
              </h2>

              <a
                href="#"
                class="flex items-center gap-1 text-sm sm:text-base text-sand-brown group/link hover:text-primary eq"
                >See All
                <i
                  class="text-xs fa-solid fa-chevron-right sm:text-sm lg:group-hover/link:translate-x-1 eq"
                ></i
              ></a>
            </div>

            <div
              class="flex flex-wrap items-center gap-6 mt-4 categories md:gap-10 lg:gap-12 md:mt-5 text-davy-gray"
            >
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/featrured-cat-item-1.png"
                    alt="Decor"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Decor
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/featrured-cat-item-2.png"
                    alt="Air Freshner"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Air Freshner
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/featrured-cat-item-3.png"
                    alt="Bakeware"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Bakeware
                </h5>
              </a>
              <a
                href="#"
                class="inline-flex flex-col items-center gap-2 category-item md:gap-3 group/category"
              >
                <div
                  class="relative flex items-center justify-center h-20 w-14 lg:w-16 round-shape lg:h-28"
                >
                  <img
                    src="./assests/images/featrured-cat-item-4.png"
                    alt="Kitchen Essential"
                    class="object-contain w-full h-auto max-h-20 lg:max-h-28 eq"
                  />
                </div>
                <h5
                  class="font-medium group-hover/category:text-primary text-nowrap eq"
                >
                  Kitchen Essential
                </h5>
              </a>
            </div>
          </div>
        </div>
      </section>
      <!-- Page Main Content Ended -->
    </main>
@endsection
