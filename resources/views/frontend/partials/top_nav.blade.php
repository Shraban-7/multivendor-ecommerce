<div
        class="container h-auto md:h-20 py-3 flex flex-wrap md:flex-nowrap flex-col md:flex-row md:items-center justify-between gap-2 md:gap-0">
        <!-- Logo and Location -->
        <div
          class="flex items-center justify-between md:justify-start gap-2 lg:gap-5 rtl:space-x-reverse">
          <a href="/">
            <img
              src="{{ asset('assets/frontend/images/tesko-logo.png') }}"
              class="lg:h-8 md:h-6 h-8 self-center"
              alt="Tesko Logo"
            />
          </a>

          <div
            class="flex items-center flex-nowrap gap-2 text-base md:text-xs lg:text-base"
          >
            <span>
              <i class="fa-solid fa-location-dot text-theme-light"></i>
            </span>
            <form class="max-w-sm mx-auto text-theme-light">
              <label for="country-select" class="sr-only"
                >Underline select</label
              >
              <select
                id="country-select"
                class="block py-2.5 px-0 w-full text-sm bg-persian-red appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer cursor-pointer border-none"
              >
                <option value="BD" selected>Bangladesh</option>
                <option value="IN">India</option>
                <option value="US">United States</option>
                <option value="CA">Canada</option>
                <option value="FR">France</option>
                <option value="DE">Germany</option>
              </select>
            </form>
            <svg
              width="8"
              height="5"
              viewBox="0 0 8 5"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M7.091 0.544732L7.70494 1.1518L4.39956 4.4989C4.34659 4.55285 4.28349 4.59579 4.21386 4.62526C4.14424 4.65473 4.06947 4.67013 3.99387 4.67059C3.91827 4.67106 3.84333 4.65656 3.77335 4.62794C3.70337 4.59933 3.63974 4.55716 3.58613 4.50385L0.238452 1.19732L0.844946 0.583387L3.98701 3.6868L7.091 0.544732Z"
                fill="white"
              />
            </svg>
          </div>
        </div>

        <!-- Search Bar -->
        <div class="flex-grow md:mx-2 lg:mx-5 order-3 md:order-2">
          <div class="relative">
            <input
              type="text"
              placeholder="Search Everything at tesko online in store"
              class="text-sm md:text-xs lg:text-base w-full py-3 pl-4 lg:py-2 lg:pl-4 pr-10 rounded-full border border-gray-300 focus:outline-none focus:border-primary focus:ring-light-yellow font-[arial] text-theme-dark placeholder:text-theme-dark eq"
            />
            <button
              class="absolute top-1/2 right-1 transform -translate-y-1/2 bg-light-yellow p-2 rounded-full"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
              </svg>
            </button>
          </div>
        </div>

        <!-- User Activity Section -->
        <div
          class="flex items-center justify-evenly order-2 md:order-3 lg:gap-5 gap-2 text-xs lg:text-sm"
        >
          <!-- my items -->
          <a
            href="#"
            class="flex items-center gap-1 hover:text-light-yellow eq"
          >
            <span><i class="fa-regular fa-heart"></i></span>
            <p class="flex flex-col leading-none text-sm lg:text-base">
              <span class="md:text-xs lg:text-sm font-[arial]">Recorder</span>
              <span class="lg:text-base text-sm font-medium">My Items</span>
            </p>
          </a>
          <!-- sign in -->
          <a
            href="#"
            class="flex items-center gap-1 hover:text-light-yellow eq"
          >
            <span><i class="fa-regular fa-user"></i></span>
            <p class="flex flex-col leading-none text-base lg:text-base">
              <span class="md:text-xs lg:text-sm font-[arial]">Sign In</span>
              <span class="lg:text-base text-sm font-medium">Account</span>
            </p>
          </a>
          <!-- cart icon -->
          <a
            href="#"
            class="flex flex-col items-center leading-none hover:text-light-yellow eq"
          >
            <span class="block relative">
              <i class="fa-solid fa-cart-arrow-down"></i>
              <span
                class="absolute flex items-center justify-center w-5 h-5 font-bold bg-theme-light text-light-yellow rounded-full -top-3 -end-4 font-[arial] font-bold text-[10px]"
              >
                01
              </span>
            </span>
            <span class="lg:text-base text-sm font-medium">$50.00</span>
          </a>
        </div>
      </div>
