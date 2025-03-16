@extends('frontend.layouts.app')
@section('content')
 <main class="electronics-page">
      <!-- Page Promotion Banner Starts -->
      <section class="page-promotion container md:w-full py-5">
        <div
          class="promo-wrapper md:container bg-[#9EB5AF] grid grid-cols-1 md:grid-cols-2 rounded-lg md:rounded-3xl overflow-hidden">
          <div
            class="order-2 md:order-1 promo-content flex flex-col gap-3 sm:gap-5 items-start justify-center p-5 md:p-10 lg:p-14 2xl:p-20">
            <h2
              class="lg:text-3xl md:text-2xl text-xl text-white font-bold md:pr-10 lg:pr-14 2xl:pr-20 line-clamp-2">
              Powering Possibilities, Empowering Lives.
            </h2>
            <p class="text-xs text-white md:pr-7 lg:pr-14 2xl:pr-20">
              Discounts on living room sets, bedroom furniture, outdoor
              furniture, and home office desks.
            </p>
            <a
              href="#"
              class="theme-btn bg-[#5A422A] px-5 py-2 lg:px-7 lg:py-3 rounded-lg text-white hover:bg-theme-light hover:text-theme-dark eq text-xs lg:text-sm"
              >Learn More</a
            >
          </div>
          <div class="promo-image order-1">
            <div class="img-wrap w-full">
              <div
                class="w-full lg:h-96 md:h-80 h-40 rounded-lg md:rounded-3xl overflow-hidden"
              >
                <a href="#" class="w-full h-full block">
                  <img
                    src="./assests/images/electronics-promo.png"
                    alt="Electronics"
                    class="w-full h-full object-cover"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Page Promotion Banner Ended -->

      <!-- All Filterts Sidebar Starts -->
      <section
        id="all-filters-drawer"
        class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-80 text-theme-dark"
        tabindex="-1"
        aria-labelledby="drawer-label"
      >
        <h5
          id="drawer-label"
          class="inline-flex items-center mb-4 text-persian-blue"
        >
          Filter search
        </h5>
        <button
          type="button"
          data-drawer-hide="all-filters-drawer"
          aria-controls="all-filters-drawer"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 right-2.5 inline-flex items-center justify-center"
        >
          <svg
            class="w-3 h-3"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 14 14"
          >
            <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
            />
          </svg>
        </button>
        <form action="#" class="space-y-5">
          <!-- Categories -->
          <div>
            <h3
              class="text-lg mb-3 border-dashed border-b border-jet-gray pb-2"
            >
              Categories
            </h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="radio"
                  name="category"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">Smartphone</span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="category"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">Laptop</span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="category"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">Headphone</span>
              </label>
            </div>
          </div>

          <!-- Brand -->
          <div>
            <h3
              class="text-lg mb-3 border-dashed border-b border-jet-gray pb-2"
            >
              Brand
            </h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="radio"
                  name="brand"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">Samsung</span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="brand"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">Apple</span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="brand"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">Dell</span>
              </label>
            </div>
          </div>

          <!-- Review -->
          <div>
            <h3
              class="text-lg mb-3 border-dashed border-b border-jet-gray pb-2"
            >
              Review
            </h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="radio"
                  name="review"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <div class="ml-2 flex items-center">
                  <div class="flex text-light-yellow">★★★★★</div>
                  <span class="ml-1 text-sm text-jet-gray">5 Star</span>
                </div>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="review"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <div class="ml-2 flex items-center">
                  <div class="flex text-light-yellow">
                    ★★★★<span class="text-gray-300">★</span>
                  </div>
                  <span class="ml-1 text-sm text-jet-gray">4 Star</span>
                </div>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="review"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <div class="ml-2 flex items-center">
                  <div class="flex text-light-yellow">
                    ★★★<span class="text-gray-300">★★</span>
                  </div>
                  <span class="ml-1 text-sm text-jet-gray">3 Star</span>
                </div>
              </label>
            </div>
          </div>

          <!-- Price -->
          <div>
            <h3
              class="text-lg mb-3 border-dashed border-b border-jet-gray pb-2"
            >
              Price
            </h3>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="radio"
                  name="price"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">Under $ 23</span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="price"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">$25-$50</span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="price"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm">$50-$100</span>
              </label>
            </div>

            <div class="flex gap-2 mt-5">
              <div class="inline-flex items-center">
                <input id="min" type="radio" value="min" name="price" class="sr-only peer" />
                <label
                  for="min"
                  class="px-5 border rounded-3xl py-1 text-base text-jet-gray border-gray-300 peer-checked:ring-primary peer-checked:ring-[1px] peer-checked:!border-primary peer-checked:text-primary"
                  >$ Min</label
                >
              </div>
              <div class="inline-flex items-center">
                <input id="max" type="radio" value="max" name="price" class="sr-only peer"
                />
                <label
                  for="max"
                  class="px-5 border rounded-3xl py-1 text-base text-jet-gray border-gray-300 peer-checked:ring-primary peer-checked:ring-[1px] peer-checked:!border-primary peer-checked:text-primary"
                  >$ Max</label
                >
              </div>
            </div>
          </div>

          <!-- Ships From -->
          <div>
            <h3
              class="text-lg mb-3 border-dashed border-b border-jet-gray pb-2"
            >
              Ships From
            </h3>

            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="radio"
                  name="shipping"
                  class="w-4 h-4 text-primary focus:ring-primary"
                />
                <span class="ml-2 text-sm text-gray-600" >Local Area (2 miles)</span >
              </label>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-start gap-3">
            <button
              type="reset"
              class="px-5 py-2 border-2 border-theme-dark rounded-full text-sm text-gray-600 hover:bg-persian-red hover:text-theme-light eq"
            >
              Reset
            </button>
            <button
              class="flex-1 px-4 py-2 bg-primary text-white rounded-full text-sm hover:bg-theme-dark eq"
            >
              Show 150 Result
            </button>
          </div>
        </form>
      </section>
      <!-- All Filterts Sidebar Ended-->

      <!-- Page Main Content Starts -->
      <section class="products-section section-padding container">
        <!-- Page Title -->
        <div class="md:mb-11 mb-8">
          <h1
            class="text-xl sm:text-2xl font-medium text-jet-gray mb-5 md:mb-10 uppercase"
          >
            Electronics/ All Categories
          </h1>

          <!-- Filters action btns -->
          <div class="flex flex-nowrap items-start justify-between">
            <div
              class="flex flex-wrap items-center gap-2 sm:gap-4 xl:w-auto lg:w-9/12 w-10/12"
            >
              <!-- All Categories -->
              <form
                class="items-center gap-1 rounded-3xl bg-aqua-deep hover:bg-rangoon-green eq sm:text-sm text-xs md:text-base sm:pl-5 pl-3 sm:!pr-2 !pr-1 py-2.5 sm:py-3 inline-flex text-white cursor-pointer"
              >
                <label for="sort-by" class="sr-only block whitespace-nowrap">
                  All Categories</label
                >
                <select
                  id="sort-by"
                  class="block w-full bg-inherit appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer"
                >
                  <option selected>All Categories</option>
                  <option value="oils">Oils</option>
                  <option value="vagitables">Vagitables</option>
                  <option value="drings">Drings</option>
                  <option value="meats">Meats</option>
                </select>
              </form>

              <!-- Relevance -->
              <form
                class="items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray"
              >
                <label for="sort-by" class="block whitespace-nowrap"
                  >Sort By:</label
                >
                <select
                  id="sort-by"
                  class="block w-full bg-transparent appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer"
                >
                  <option selected>Relevance</option>
                  <option value="best-selling">Best Selling</option>
                  <option value="trending">Trending</option>
                  <option value="popularity">Popularity</option>
                  <option value="new-arrivals">New Arrivals</option>
                </select>
              </form>

              <!-- Color -->
              <div class="flex items-center gap-4">
                <!-- Dropdown Menu -->
                <div class="relative">
                  <button
                    id="colorSortButton"
                    data-dropdown-toggle="colorSortDropdown"
                    class="bg-theme-light/90 hover:bg-aqua-deep/10 eq rounded-3xl text-xs sm:text-sm px-3 sm:px-5 sm:py-3 py-2.5 text-center inline-flex text-jet-gray items-center"
                    type="button"
                  >
                    Color
                    <svg
                      class="w-4 h-4 ml-2"
                      aria-hidden="true"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      ></path>
                    </svg>
                  </button>

                  <!-- Dropdown Content -->
                  <div
                    id="colorSortDropdown"
                    class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow"
                  >
                    <ul
                      class="py-1 text-sm text-gray-700"
                      aria-labelledby="colorSortButton"
                    >
                      <li>
                        <button
                          class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 w-full"
                        >
                          <span class="h-4 w-4 rounded-full bg-red-500"></span>
                          Red
                        </button>
                      </li>
                      <li>
                        <button
                          class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 w-full"
                        >
                          <span class="h-4 w-4 rounded-full bg-blue-500"></span>
                          Blue
                        </button>
                      </li>
                      <li>
                        <button
                          class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 w-full"
                        >
                          <span
                            class="h-4 w-4 rounded-full bg-green-500"
                          ></span>
                          Green
                        </button>
                      </li>
                      <li>
                        <button
                          class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 w-full"
                        >
                          <span
                            class="h-4 w-4 rounded-full bg-yellow-500"
                          ></span>
                          Yellow
                        </button>
                      </li>
                      <li>
                        <button
                          class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 w-full"
                        >
                          <span
                            class="h-4 w-4 rounded-full bg-purple-500"
                          ></span>
                          Purple
                        </button>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Material -->
              <form
                class="items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray"
              >
                <label for="sort-by" class="sr-only block whitespace-nowrap"
                  >Material</label
                >
                <select
                  id="sort-by"
                  class="block w-full bg-transparent appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer"
                >
                  <option selected>Material</option>
                  <option value="organic">Organic</option>
                  <option value="fresh">Fresh</option>
                  <option value="grain">Grain</option>
                  <option value="dairy">Dairy</option>
                  <option value="plant">Plant</option>
                  <option value="alant">Animal</option>
                </select>
              </form>

              <!-- Review -->
              <form
                class="items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray"
              >
                <label for="sort-by" class="sr-only block whitespace-nowrap"
                  >Review</label
                >
                <select
                  id="sort-by"
                  class="block w-full bg-transparent appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer"
                >
                  <option selected>Review</option>
                  <option value="highest-rated">Highest Rated</option>
                  <option value="most-reviewed">Most Reviewed</option>
                  <option value="top-feedback">Top Feedback</option>
                  <option value="verified-reviews">Verified Reviews</option>
                  <option value="plant">Plant</option>
                  <option value="alant">Animal</option>
                </select>
              </form>

              <!-- Recommended -->
              <form
                class="items-center gap-1 rounded-3xl bg-theme-light/90 hover:bg-aqua-deep/10 eq sm:text-sm text-xs sm:pl-5 pl-4 sm:!pr-2 pr-1 sm:py-3 py-2.5 inline-flex text-jet-gray"
              >
                <label for="sort-by" class="sr-only block whitespace-nowrap"
                  >Recommended</label
                >
                <select
                  id="sort-by"
                  class="block w-full bg-transparent appearance-none border-0 focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer"
                >
                  <option selected>Recommended</option>
                  <option value="best-sellers">Best Sellers</option>
                  <option value="editor-pick">Editor's Pick</option>
                  <option value="customers-choice">Customers' Choice</option>
                  <option value="staff-recommended">Staff Recommended</option>
                </select>
              </form>
            </div>

            <!-- All Filters Trigure Btn -->
            <div class="w-2/12 lg:w-3/12 xl:w-auto">
              <button
                data-drawer-target="all-filters-drawer"
                data-drawer-show="all-filters-drawer"
                aria-controls="all-filters-drawer"
                class="ml-auto w-10 h-10 md:w-auto rounded-full md:rounded-3xl text-sm bg-primary text-white hover:bg-theme-dark eq md:px-5 md:py-3 flex gap-1 items-center justify-center"
              >
                <span class="hidden md:block">All Filters</span>

                <svg
                  width="12"
                  height="10"
                  viewBox="0 0 12 10"
                  fill="none"
                  stroke="currentColor"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M10.029 3.98803C10.503 3.7507 10.7405 3.63203 10.87 3.44047C11 3.24936 11 3.01869 11 2.55735V2.25068C11 1.66134 11 1.36623 10.78 1.18311C10.561 1 10.2075 1 9.5 1H2.5C1.793 1 1.4395 1 1.22 1.18311C1.0005 1.36623 1 1.66134 1 2.25112V2.55779C1 3.01869 1 3.24936 1.13 3.44047C1.26 3.63158 1.4965 3.7507 1.971 3.98803L3.4275 4.71693C3.7455 4.87604 3.905 4.9556 4.019 5.0436C4.256 5.22627 4.402 5.44138 4.468 5.70583C4.5 5.83205 4.5 5.9805 4.5 6.27695V7.46363C4.5 7.86763 4.5 8.06985 4.626 8.22719C4.752 8.38497 4.976 8.46275 5.423 8.61831C6.3625 8.94453 6.832 9.10764 7.166 8.92186C7.5 8.73608 7.5 8.31208 7.5 7.46318V6.2765C7.5 5.9805 7.5 5.83205 7.532 5.70538C7.59479 5.44634 7.75297 5.21331 7.9815 5.04316"
                    stroke="currentColor"
                    stroke-linecap="round"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Product Card's Wrapper -->
        <div
          class="grid grid-cols-1 xsm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 sm:gap-6 gap-3"
        >
          <!-- Product Card 1 -->
          <div
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-1.png"
                  alt="ASUS Vivo15 OLED K513 Core-i5 11th Gen 15.6″ FHD Laptop"
                  class="w-full h-full object-cover"
                />
              </a>

              <!-- Quick View Toggle -->
              <button
                type="button"
                data-modal-target="quick-view-modal"
                data-modal-toggle="quick-view-modal"
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>

              <!-- Quick View Modal -->
              <div
                id="quick-view-modals"
                tabindex="-1"
                aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
              >
                <div class="relative container max-h-full">
                  <!-- Modal content -->
                  <div
                    class="relative shadow-lg bg-white rounded-2xl md:rounded-3xl"
                  >
                    <!-- Modal Close Triger -->
                    <button
                      type="button"
                      class="text-white bg-theme-dark hover:bg-theme-dark/80 rounded-full lg:w-10 lg:h-10 w-7 h-7 inline-flex justify-center items-center md:text-2xl text-lg absolute right-4 top-4 z-10"
                      data-modal-hide="quick-view-modal"
                    >
                      <i class="fa-solid fa-xmark"></i>
                      <span class="sr-only">Close modal</span>
                    </button>

                    <!-- Modal body -->
                    <div class="p-4 md:p-10">
                      <div class="flex flex-col md:flex-row gap-5">
                        <!-- Product Images Section -->
                        <div
                          class="lg:w-[55%] md:w-[50%] w-full flex flex-col lg:flex-row gap-3 lg:gap-5"
                        >
                          <!-- Thumbnails -->
                          <div
                            class="lg:w-1/6 w-full space-y-3 order-2 lg:order-1"
                          >
                            <div
                              class="product-thumbnails overflow-hidden xl:h-[37rem] lg:h-[41rem] h-auto"
                            >
                              <div class="swiper-wrapper">
                                <!-- thumb 1 -->
                                <div class="swiper-slide">
                                  <div
                                    class="slide-thumb w-full xl:h-24 lg:h-28 sm:h-24 h-16 rounded-xl md:rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary overflow-hidden"
                                  >
                                    <img
                                      src="./assests/images/prod-details-thumb-1.png"
                                      alt="Product thumbnail of A Young boy wear a jacket with green T-Shirt & Short Pant"
                                      class="w-full h-full object-cover"
                                    />
                                  </div>
                                </div>
                                <!-- thumb 2 -->
                                <div class="swiper-slide">
                                  <div
                                    class="slide-thumb w-full xl:h-24 lg:h-28 sm:h-24 h-16 rounded-xl md:rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary overflow-hidden"
                                  >
                                    <img
                                      src="./assests/images/prod-details-thumb-2.png"
                                      alt="Product thumbnail 2"
                                      class="w-full h-full object-cover"
                                    />
                                  </div>
                                </div>
                                <!-- thumb 3 -->
                                <div class="swiper-slide">
                                  <div
                                    class="slide-thumb w-full xl:h-24 lg:h-28 sm:h-24 h-16 rounded-xl md:rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary overflow-hidden"
                                  >
                                    <img
                                      src="./assests/images/prod-details-thumb-3.png"
                                      alt="Product thumbnail 3"
                                      class="w-full h-full object-cover"
                                    />
                                  </div>
                                </div>
                                <!-- thumb 4 -->
                                <div class="swiper-slide">
                                  <div
                                    class="slide-thumb w-full xl:h-24 lg:h-28 sm:h-24 h-16 rounded-xl md:rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary overflow-hidden"
                                  >
                                    <img
                                      src="./assests/images/prod-details-thumb-1.png"
                                      alt="Product thumbnail 4"
                                      class="w-full h-full object-cover"
                                    />
                                  </div>
                                </div>
                                <!-- thumb 5 -->
                                <div class="swiper-slide">
                                  <div
                                    class="slide-thumb w-full xl:h-24 lg:h-28 sm:h-24 h-16 rounded-xl md:rounded-2xl cursor-pointer border-2 border-transparent hover:border-primary overflow-hidden"
                                  >
                                    <img
                                      src="./assests/images/prod-details-thumb-2.png"
                                      alt="Product thumbnail 5"
                                      class="w-full h-full object-cover"
                                    />
                                  </div>
                                </div>
                                <!-- Repeat thumb for more thumbnails -->
                              </div>
                            </div>
                          </div>

                          <!-- Main Image Slider -->
                          <div
                            class="lg:w-5/6 w-full relative order-1 lg:order-2"
                          >
                            <div
                              class="product-swiper w-full h-80 sm:h-[28rem] md:h-[37rem] xl:h-[37rem] lg:h-[41rem] rounded-2xl overflow-hidden relative"
                            >
                              <div class="swiper-wrapper">
                                <!-- product image 1 -->
                                <div
                                  class="swiper-slide h-full rounded-2xl overflow-hidden"
                                >
                                  <img
                                    src="./assests/images/prod-details-img-1.png"
                                    alt="A Young boy wear a jacket with green T-Shirt & Short Pant"
                                    class="w-full h-full object-cover"
                                  />
                                </div>
                                <!-- product image 2 -->
                                <div
                                  class="swiper-slide h-full rounded-2xl overflow-hidden"
                                >
                                  <img
                                    src="./assests/images/prod-details-img-2.png"
                                    alt="A Young boy showing his back side with wear a green colour full sleeve T-Shirt & White Short Pant"
                                    class="w-full h-full object-cover"
                                  />
                                </div>
                                <!-- product image 3 -->
                                <div
                                  class="swiper-slide h-full rounded-2xl overflow-hidden"
                                >
                                  <img
                                    src="./assests/images/prod-details-img-3.png"
                                    alt="A Young boy in standing position and one hand under in left pocket with wear a green colour full sleeve T-Shirt & White Short Pant"
                                    class="w-full h-full object-cover"
                                  />
                                </div>
                                <!-- product image 4 -->
                                <div
                                  class="swiper-slide h-full rounded-2xl overflow-hidden"
                                >
                                  <img
                                    src="./assests/images/prod-details-img-1.png"
                                    alt="A Young boy wear a jacket with green T-Shirt & Short Pant"
                                    class="w-full h-full object-cover"
                                  />
                                </div>
                                <!-- product image 5 -->
                                <div
                                  class="swiper-slide h-full rounded-2xl overflow-hidden"
                                >
                                  <img
                                    src="./assests/images/prod-details-img-2.png"
                                    alt="A Young boy showing his back side with wear a green colour full sleeve T-Shirt & White Short Pant"
                                    class="w-full h-full object-cover"
                                  />
                                </div>

                                <!-- Repeat product image for more slides -->
                              </div>
                              <!-- Navigation Buttons -->

                              <div
                                class="swiper-button-prev text-theme-light"
                              ></div>
                              <div
                                class="swiper-button-next text-theme-light"
                              ></div>
                            </div>
                          </div>
                        </div>

                        <!-- Product Details Section -->
                        <div
                          class="lg:w-[45%] md:w-[50%] w-full md:px-2 xl:px-3"
                        >
                          <div class="w-full space-y-2">
                            <!-- Free Shipping Banner -->
                            <div
                              class="text-sm justify-center lg:text-base text-rustic-red bg-[#FEEFE1] px-4 py-3 flex flex-wrap flex-col xsm:flex-row md:flex-col lg:flex-row xsm:justify-between items-center"
                            >
                              <div class="flex items-center gap-2 text-center">
                                <i
                                  class="fa-solid fa-check text-theme-teal"
                                ></i>
                                <span>Free shipping special for you</span>
                              </div>
                              <span class="text-jet-gray font-light"
                                >Exclusive offer</span
                              >
                            </div>

                            <h1
                              class="lg:text-base text-rustic-red text-sm lg:pr-5 xl:pr-16"
                            >
                              <a href="#" class="hover:text-primary eq">
                                Men's Fit Stretch Jaket - Caramel, Woven Fabric,
                                Matching Washable - Perfect for Business Casual
                                & Summer Wear
                              </a>
                            </h1>

                            <div
                              class="flex flex-wrap items-center gap-2 xsm:gap-x-5 sm:gap-x-10 md:gap-2 lg:gap-x-10 text-sm"
                            >
                              <div class="flex items-center gap-2">
                                <span
                                  class="text-jet-gray border-r border-gray-400 pr-2 text-nowrap"
                                  >486 sold</span
                                >
                                <div
                                  class="flex flex-wrap items-center gap-x-2 text-davy-gray"
                                >
                                  <span>Provided By</span>
                                  <a
                                    href="#"
                                    class="inline-block provider-icon w-6 h-6 overflow-hidden rounded-full"
                                  >
                                    <img
                                      src="./assests//images/provider-icon-1.png"
                                      alt="Louis Vuitton"
                                      class="h-full w-full object-contain"
                                    />
                                  </a>
                                  <span>(3.1K+ sold)</span>
                                </div>
                              </div>

                              <div class="flex items-center gap-2">
                                <!-- 5 star rating -->
                                <span class="text-xs">5.00 Star</span>
                                <!-- Repeat for 5 stars -->
                                <span>★★★★★</span>
                              </div>
                            </div>

                            <div class="flex items-center gap-2">
                              <span
                                class="bg-leaf-green text-white text-xs px-2.5 py-1 rounded-full"
                                >Best Seller</span
                              >
                              <p class="text-davy-gray text-sm">
                                From this provider
                              </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                              <div
                                class="new-price flex items-center gap-1 flex-no-wrap"
                              >
                                <i class="fa-solid fa-bolt text-[#ffa755]"></i>
                                <span
                                  class="align-center text-sm text-[#ffa755]"
                                  >$</span
                                >
                                <h3
                                  class="current-price font-bold text-primary"
                                >
                                  25.89
                                </h3>
                              </div>
                              <h6 class="old-price text-jet-gray line-through">
                                $ 49.99
                              </h6>
                              <span
                                class="text-xs px-2.5 py-0.5 rounded-lg border border-primary"
                                >-69% last 2 days</span
                              >
                              <span class="text-leaf-green text-xs"
                                >Almost Sold Out</span
                              >
                            </div>
                          </div>

                          <div
                            class="user-action rounded-lg border-primary border-2 overflow-hidden mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5"
                          >
                            <!-- Special Sale Banner -->
                            <div
                              class="bg-primary text-sm md:text-base text-white px-4 py-1 flex justify-between items-center"
                            >
                              <span>Special Sale | Two Days Left</span>
                              <i class="fa-solid fa-arrow-right"></i>
                            </div>

                            <div class="clr-size-qty p-4">
                              <!-- Color Selection -->
                              <div class="colour">
                                <h6 class="text-davy-gray sm:text-lg">
                                  Color :
                                </h6>
                                <form
                                  class="flex flex-wrap items-center gap-4 sm:gap-5 mt-2"
                                >
                                  <div
                                    class="form-ctrl flex flex-col gap-2 items-center"
                                  >
                                    <input
                                      id="black"
                                      type="radio"
                                      value="black"
                                      name="colour"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="black"
                                      class="w-6 h-6 sm:w-8 sm:h-8 block peer-checked:ring peer-checked:ring-black bg-black rounded-full peer-checked:border-2 sm:peer-checked:border-4 border border-black peer-checked:border-primary cursor-pointer"
                                    >
                                    </label>
                                    <label
                                      for="black"
                                      class="block cursor-pointer text-davy-gray text-sm sm:text-base"
                                      >Black</label
                                    >
                                  </div>
                                  <div
                                    class="form-ctrl flex flex-col gap-2 items-center"
                                  >
                                    <input
                                      id="green"
                                      type="radio"
                                      value="green"
                                      name="colour"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="green"
                                      class="w-6 h-6 sm:w-8 sm:h-8 block peer-checked:ring peer-checked:ring-green-700 bg-green-700 rounded-full peer-checked:border-2 sm:peer-checked:border-4 border border-green-700 peer-checked:border-primary cursor-pointer"
                                    ></label>
                                    <label
                                      for="green"
                                      class="block cursor-pointer text-davy-gray text-sm sm:text-base"
                                      >Green</label
                                    >
                                  </div>
                                  <div
                                    class="form-ctrl flex flex-col gap-2 items-center"
                                  >
                                    <input
                                      id="gray"
                                      type="radio"
                                      value="gray"
                                      name="colour"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="gray"
                                      class="w-6 h-6 sm:w-8 sm:h-8 block peer-checked:ring peer-checked:ring-gray-400 bg-gray-400 rounded-full peer-checked:border-2 sm:peer-checked:border-4 border border-gray-400 peer-checked:border-primary cursor-pointer"
                                    ></label>
                                    <label
                                      for="gray"
                                      class="block cursor-pointer text-davy-gray text-sm sm:text-base"
                                      >Gray</label
                                    >
                                  </div>
                                  <div
                                    class="form-ctrl flex flex-col gap-2 items-center"
                                  >
                                    <input
                                      id="white"
                                      type="radio"
                                      value="white"
                                      name="colour"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="white"
                                      class="w-6 h-6 sm:w-8 sm:h-8 block peer-checked:ring peer-checked:ring-primary bg-white rounded-full peer-checked:border-2 sm:peer-checked:border-4 border peer-checked:border-black cursor-pointer"
                                    ></label>
                                    <label
                                      for="white"
                                      class="block cursor-pointer text-davy-gray text-sm sm:text-base"
                                      >White</label
                                    >
                                  </div>
                                </form>
                              </div>
                              <!-- Size Selection -->
                              <div class="size mt-3">
                                <div
                                  class="text-davy-gray flex flex-wrap items-center gap-x-2"
                                >
                                  <h6 class="sm:text-lg">Size :</h6>
                                  <a
                                    href="#"
                                    class="inline-flex items-center hover:text-violet-700 hover:underline eq"
                                  >
                                    <img
                                      src="./assests/images/size-scale.png"
                                      alt="Size Chart"
                                      class="w-10 xsm:w-14 h-auto"
                                    />
                                    <span class="text-xs"> Size Chart</span>
                                  </a>
                                  <a
                                    href="#"
                                    class="hover:text-light-yellow hover:underline eq ml-2 xsm:ml-4"
                                  >
                                    <span class="text-xs">
                                      What's My Size?</span
                                    >
                                  </a>
                                </div>
                                <form
                                  class="flex flex-wrap items-center gap-2 mt-2 text-xs"
                                >
                                  <div class="form-ctrl">
                                    <input
                                      id="xs"
                                      type="radio"
                                      value="XS"
                                      name="size"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="xs"
                                      class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer"
                                      >XS
                                    </label>
                                  </div>
                                  <div class="form-ctrl">
                                    <input
                                      id="s"
                                      type="radio"
                                      value="S"
                                      name="size"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="s"
                                      class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer"
                                      >S
                                    </label>
                                  </div>
                                  <div class="form-ctrl">
                                    <input
                                      id="m"
                                      type="radio"
                                      value="M"
                                      name="size"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="m"
                                      class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer"
                                      >M
                                    </label>
                                  </div>
                                  <div class="form-ctrl">
                                    <input
                                      id="l"
                                      type="radio"
                                      value="L"
                                      name="size"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="l"
                                      class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer"
                                      >L
                                    </label>
                                  </div>
                                  <div class="form-ctrl">
                                    <input
                                      id="xl"
                                      type="radio"
                                      value="XL"
                                      name="size"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="xl"
                                      class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer"
                                      >XL
                                    </label>
                                  </div>
                                  <div class="form-ctrl">
                                    <input
                                      id="xxl"
                                      type="radio"
                                      value="XXL"
                                      name="size"
                                      class="hidden peer"
                                    />
                                    <label
                                      for="xxl"
                                      class="px-4 py-1 sm:px-5 sm:py-1.5 block ring-[1px] hover:bg-gray-100 ring-transparent peer-checked:ring-primary rounded border peer-checked:border-primary peer-checked:text-primary cursor-pointer"
                                      >XXL
                                    </label>
                                  </div>
                                </form>
                              </div>
                              <!-- Quantity -->
                              <div class="quantity mt-3">
                                <div
                                  class="text-davy-gray flex flex-wrap items-center gap-x-2"
                                >
                                  <h6 class="sm:text-lg">Quantity :</h6>
                                  <div
                                    class="flex items-center border rounded p-1"
                                  >
                                    <button
                                      id="decreaseBtn"
                                      class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold"
                                    >
                                      <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input
                                      readonly
                                      id="quantity"
                                      type="number"
                                      min="1"
                                      class="text-center text-persian-blue w-12 h-5 text-sm font-medium border-0 focus:ring-0"
                                    />
                                    <button
                                      id="increaseBtn"
                                      class="w-5 h-5 flex items-center justify-center text-persian-blue/40 bg-jet-gray/20 hover:bg-jet-gray/40 eq active:text-primary rounded text-sm font-bold"
                                    >
                                      <i class="fa-solid fa-plus"></i>
                                    </button>
                                  </div>
                                  <span class="text-davy-gray text-xs"
                                    >In Stock</span
                                  >
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Action Button -->
                          <div
                            class="flex gap-4 mt-5 w-full xsm:w-4/5 md:w-11/12 lg:w-4/5"
                          >
                            <button
                              class="text-sm md:text-base font-medium flex-1 px-6 py-2.5 bg-primary text-white rounded-full hover:bg-theme-dark eq"
                            >
                              Add To Cart
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >ASUS Vivo15 OLED K513 Core-i5 11th Gen 15.6″ FHD Laptop</a
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
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-2.png"
                  alt="Apple watch series 10 depth rainmaker"
                  class="w-full h-full object-cover"
                />
              </a>
              <button
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >Apple watch series 10 depth rainmaker</a
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
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-3.png"
                  alt="Quadcopter With Height Hold, App Control, And Obstacle For flying"
                  class="w-full h-full object-cover"
                />
              </a>
              <button
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >Quadcopter With Height Hold, App Control, And Obstacle For
                  flying</a
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
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-4.png"
                  alt="Sports Wireless Headphones, ANC and ENC Headphone"
                  class="w-full h-full object-cover"
                />
              </a>
              <button
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >Sports Wireless Headphones, ANC and ENC Headphone</a
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
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-5.png"
                  alt="SAMSUNG GALAXY A15 LTE Blue 6 +128GB Dual Sim, Smartphone"
                  class="w-full h-full object-cover"
                />
              </a>
              <button
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >SAMSUNG GALAXY A15 LTE Blue 6 +128GB Dual Sim, Smartphone</a
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
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-6.png"
                  alt="Electric
                Bike, 500W Motor, 14'' Tire Folding Mini Ebikes"
                  class="w-full h-full object-cover"
                />
              </a>
              <button
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >Electric Bike, 500W Motor, 14" Tire Folding Mini Ebikes</a
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
          <!-- Product Card 7 -->
          <div
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-7.png"
                  alt="FG-07S Wireless Headphones  Stereo Channel Surround Audio"
                  class="w-full h-full object-cover"
                />
              </a>
              <button
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >FG-07S Wireless Headphones Stereo Channel Surround Audio</a
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
          <!-- Product Card 8 -->
          <div
            class="relative text-base xsm:text-sm sm:text-base md:text-sm lg:text-base xl:text-lg 2xl:text-xl rounded-xl hover:shadow-lg eq"
          >
            <div
              class="relative h-60 xsm:h-48 sm:h-56 lg:h-64 xl:h-72 overflow-hidden rounded-lg"
            >
              <a href="#" class="block w-full h-full">
                <img
                  src="./assests/images/electronic-prod-8.png"
                  alt="24V 2 Seater 4WD/2WD Switchable Ride On Car For Kids "
                  class="w-full h-full object-cover"
                />
              </a>
              <button
                class="absolute bottom-10 xsm:bottom-3 lg:bottom-8 xsm:left-3 lg:left-5 left-5 bg-white hover:bg-primary hover:text-white rounded-full px-4 py-2 flex items-center gap-2 shadow-lg eq"
              >
                <i class="fa-regular fa-eye"></i>
                Quick View
              </button>
            </div>

            <div class="p-4 xsm:p-2 lg:p-5">
              <h3
                class="font-medium line-clamp-2 xsm:h-10 sm:h-12 md:h-10 lg:h-12 xl:h-14"
              >
                <a href="#" class="hover:text-primary eq"
                  >24V 2 Seater 4WD/2WD Switchable Ride On Car For Kids </a
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

        <!-- Load More Btn -->
        <div class="load-more-btn text-center mt-10">
          <button
            class="theme-btn bg-theme-teal hover:bg-aqua-deep text-white px-5 py-2 xl:text-xl text-base md:text-lg inline-flex gap-2 items-center eq"
            type="button"
          >
            <span>Load More</span>
            <i class="fa-solid fa-chevron-down text-sm"></i>
          </button>
        </div>
      </section>
      <!-- Page Main Content Ended -->
    </main>

    @include('frontend.partials.quick-view-modal')
@endsection
