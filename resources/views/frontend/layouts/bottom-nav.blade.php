<nav class="bg-[#F2F8FD] border-b">
    <div class="container flex flex-wrap md:flex-nowrap items-center justify-between md:justify-start relative">
        <!-- All Departments -->
        <div class="group relative h-full py-5 flex">
            <!-- mega menu trigure btn -->
            <button data-dropdown-toggle="dropdown"
                class="flex items-center flex-no-wrap space-x-2 pr-5 text-persian-blue border-r border-butterfly-blue font-semibold text-base md:text-sm lg:text-base whitespace-nowrap">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4 4H10V10H4V4ZM14 4H20V10H14V4ZM4 14H10V20H4V14ZM14 17C14 17.7956 14.3161 18.5587 14.8787 19.1213C15.4413 19.6839 16.2044 20 17 20C17.7956 20 18.5587 19.6839 19.1213 19.1213C19.6839 18.5587 20 17.7956 20 17C20 16.2044 19.6839 15.4413 19.1213 14.8787C18.5587 14.3161 17.7956 14 17 14C16.2044 14 15.4413 14.3161 14.8787 14.8787C14.3161 15.4413 14 16.2044 14 17Z"
                        stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <span>All Department</span>
            </button>

            <!-- mega menu container -->
            <div id="dropdown"
                class="hidden text-jet-gray absolute left-0 top-full w-60 bg-white shadow-lg z-50 text-sm">
                <!-- Categories Column -->
                <div class="border border-[#E4E7E9] rounded w-full h-[31.2rem]">
                    <ul class="py-2">
                        @foreach (all_department_categories() as $category)
                            @if ($category->subcategories->isNotEmpty())
                                <li
                                    class="category-item hover:bg-[#F2F4F5] hover:text-rangoon-green px-4 py-2 group/phone eq">
                                    <p class="flex justify-between items-center hover:font-semibold cursor-pointer eq">
                                        {{ $category->name }}
                                        <span class="invisible group-hover/phone:visible">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    </p>
                                    <!-- Brands Submenu -->
                                    <div
                                        class="hidden group-hover/phone:block absolute left-60 top-0 min-h-[31.2rem] w-44 text-jet-gray">
                                        <ul
                                            class="py-4 px-3 bg-white border border-[#E4E7E9] rounded ms-3 h-[31.2rem] overflow-y-auto">
                                            <li class="brand-item">
                                                <a href="{{ route('category.details',$category->slug) }}"
                                                    class="block hover:bg-[#F2F4F5] px-4 py-2 hover:text-rangoon-green hover:font-semibold eq rounded-sm">All</a>
                                            </li>
                                            @foreach ($category->subcategories as $subcategory)
                                                <li
                                                    class="brand-item hover:bg-gray-100 px-4 py-2 rounded-sm group/brand eq">
                                                    <h4
                                                        class="hover:font-semibold eq cursor-pointer hover:text-rangoon-green">
                                                        {{ $subcategory->name }}
                                                    </h4>
                                                    <!-- brand feature products -->
                                                    {{-- <div
                                                        class="hidden group-hover/brand:block absolute left-[10rem] top-0 w-[45vw] lg:w-[50vw] max-h-[31.2rem] p-4 bg-white border-t border-r border-b border-[#E4E7E9] rounded-tr rounded-br h-[31.2rem] xl:overflow-y-auto overflow-y-scroll">
                                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 h-full">
                                                            <!-- Product Cards -->
                                                            <div class="feature-phones p-2 order-2 lg:order-1">
                                                                <h3
                                                                    class="text-lg text-rangoon-green font-bold mb-4 uppercase">
                                                                    Featured Phones
                                                                </h3>

                                                                <div class="feature-items-wrapper space-y-4">
                                                                    <!-- item 1 -->
                                                                    <div
                                                                        class="group/feature feature-item-card flex border border-jet-gray/30 rounded p-2 gap-3 hover:shadow eq">
                                                                        <div class="item-image w-1/4">
                                                                            <a href="#" target="_blank">
                                                                                <img src="{{ asset('assets/frontend/images/feature-product-1.png') }}"
                                                                                    alt=" Samsung Electronics Samsung Galexy S21 5G"
                                                                                    class="w-full h-full object-contain" />
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-details flex flex-col w-3/4">
                                                                            <h4 class="line-clamp-2">
                                                                                <a href="#" target="_self"
                                                                                    class="text-rangoon-green group-hover/feature:text-primary eq">
                                                                                    Samsung Electronics Samsung Galexy
                                                                                    S21
                                                                                    5G
                                                                                </a>
                                                                            </h4>
                                                                            <p
                                                                                class="text-butterfly-blue font-semibold">
                                                                                $160
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <!-- item 2 -->
                                                                    <div
                                                                        class="group/feature feature-item-card flex border border-jet-gray/30 rounded p-2 gap-3 hover:shadow eq">
                                                                        <div class="item-image w-1/4">
                                                                            <a href="#" target="_blank">
                                                                                <img src="{{ asset('assets/frontend/images/feature-product-2.png') }}"
                                                                                    alt="Simple Mobile 5G LTE Galexy 12 Mini 512GB Gaming Phone"
                                                                                    class="w-full h-full object-contain" />
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-details flex flex-col w-3/4">
                                                                            <h4 class="line-clamp-2">
                                                                                <a href="#" target="_self"
                                                                                    class="text-rangoon-green group-hover/feature:text-primary eq">
                                                                                    Simple Mobile 5G LTE Galexy 12 Mini
                                                                                    512GB Gaming Phone
                                                                                </a>
                                                                            </h4>
                                                                            <p
                                                                                class="text-butterfly-blue font-semibold">
                                                                                $1,500
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <!-- item 3 -->
                                                                    <div
                                                                        class="group/feature feature-item-card flex border border-jet-gray/30 rounded p-2 gap-3 hover:shadow eq">
                                                                        <div class="item-image w-1/4">
                                                                            <a href="#" target="_blank">
                                                                                <img src="{{ asset('assets/frontend/images/feature-product-3.png') }}"
                                                                                    alt="Sony DSCHX8 High Zoom Point & Shoot Camera"
                                                                                    class="w-full h-full object-contain" />
                                                                            </a>
                                                                        </div>
                                                                        <div class="item-details flex flex-col w-3/4">
                                                                            <h4 class="line-clamp-2">
                                                                                <a href="#" target="_self"
                                                                                    class="text-rangoon-green group-hover/feature:text-primary eq">
                                                                                    Sony DSCHX8 High Zoom Point & Shoot
                                                                                    Camera
                                                                                </a>
                                                                            </h4>
                                                                            <p
                                                                                class="text-butterfly-blue font-semibold">
                                                                                <span
                                                                                    class="line-through font-normal text-[#929FA5]">$3200</span>
                                                                                $2,300
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Discount Feature Product Image -->
                                                            <div class="h-full discount-image order-1">
                                                                <a href="#" class="2xl:h-[29rem] block relative">
                                                                    <img src="{{ asset('assets/frontend/images/feature-product-4.png') }}"
                                                                        alt="Feature Phone"
                                                                        class="w-full h-full mb-2 md:mb-0 object-contain" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @elseif ($category->subcategories->isEmpty())
                                <li class="category-item">
                                    <a href="{{ route('category.details',$category->slug) }}"
                                        class="block hover:bg-[#F2F4F5] px-4 py-2 hover:text-rangoon-green hover:font-semibold eq">{{ $category->name }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Toggle Menu Button Mobile -->
        <button data-collapse-toggle="navbar-multi-level" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-multi-level" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>

        <!-- Nav Links -->
        <div class="nav-links hidden w-full md:block md:w-auto" id="navbar-multi-level">
            <ul
                class="flex flex-col font-light p-3 lg:p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:space-x-3 lg:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:ms-4 md:border-0 md:text-persian-blue md:text-sm lg:text-base md:flex-wrap">
                <li>
                    <a href="/"
                        class="block py-2 px-3 text-persian-blue rounded hover:bg-primary md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 eq md:text-persian-blue nav-link"
                        aria-current="page">Home</a>
                </li>
                @foreach (nav_categories() as $category)
                    <li>
                        <a href="{{ route('category.details',$category->slug) }}"
                            class="block py-2 px-3 text-persian-blue rounded hover:bg-primary md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 eq md:text-persian-blue nav-link">{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
