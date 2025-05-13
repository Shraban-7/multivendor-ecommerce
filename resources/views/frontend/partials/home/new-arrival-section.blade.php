 <section class="new-arrivals-section section-padding">
     <div class="container">
         <!-- Section Title -->
         <div class="relative sec-heading">
             <h2 class="font-semibold uppercase sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                 New Arrivals
             </h2>

             <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2"><a href="#"
                     class="theme-btn theme-outline-btn">View All</a></span>
         </div>

         <!-- New Arrivals Products Slider -->
         <div class="mt-5 swiper productCommonSwiper md:mt-10">
             <div class="swiper-wrapper">
                 <!-- slide 1 -->
                 @foreach ($new_arrival_products as $product)
                     <div class="swiper-slide group/community-pro-card eq">
                         <div class="flex flex-col items-center w-full p-2 product-card">
                             <div
                                 class="w-full h-full border border-jet-gray/30 rounded-md hover:shadow-md eq overflow-hidden flex flex-col">
                                 <!-- Image Section -->
                                 <div
                                     class="h-52 px-4 pt-6 pb-4 overflow-hidden item-img flex items-center justify-center">
                                     <a href="{{ route('products.details', $product->slug) }}">
                                         <img class="object-contain w-full h-full"
                                             src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->slug }}"
                                             loading="lazy" />
                                     </a>
                                 </div>

                                 <!-- Content Section -->
                                 <div class="flex-1 flex flex-col justify-between p-3 space-y-2 sm:p-4">
                                     <!-- Ratings -->
                                     <div class="text-xs sm:text-sm text-light-yellow rating-stars">
                                         <i class="fa-solid fa-star"></i>
                                         <i class="fa-solid fa-star"></i>
                                         <i class="fa-solid fa-star"></i>
                                         <i class="fa-solid fa-star"></i>
                                         <i class="fa-regular fa-star"></i>
                                     </div>

                                     <!-- Name & Price -->
                                     <div class="flex items-end justify-between gap-2">
                                         <div class="name-price w-full">
                                             <!-- Product Name (no wrap, ellipsis) -->
                                             <h2
                                                 class="text-sm font-medium text-theme-dark group-hover/community-pro-card:text-butterfly-blue line-clamp-1 leading-snug">
                                                 <a
                                                     href="{{ route('products.details', $product->slug) }}">{{ $product->name }}</a>
                                             </h2>

                                             <!-- Prices (no wrap) -->
                                             <div class="flex gap-x-2 text-nowrap text-sm sm:text-base">
                                                 <p class="font-semibold text-theme-teal">
                                                     {{ money($product->discounted_price) }}
                                                 </p>
                                                 <p class="line-through text-jet-gray">
                                                     {{ money($product->selling_price) }}
                                                 </p>
                                             </div>
                                         </div>

                                         <!-- Add to Cart -->
                                         <div class="shrink-0">
                                             <input type="hidden" name="quantity" value="1"
                                                 id="qtyInput{{ $product->id }}">
                                             <button data-id="{{ $product->id }}" type="button"
                                                 class="flex items-center justify-center text-sm rounded cartBtn w-8 h-8 sm:w-10 sm:h-10 bg-primary text-theme-light hover:bg-light-yellow eq">
                                                 <i class="fa-solid fa-plus"></i>
                                             </button>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 @endforeach
             </div>
         </div>

         <!-- Become Sellers, Trending Products & Promo Poster -->
         <div class="flex flex-col gap-5 mt-10 promo-trending-products lg:flex-row">
             <div class="flex flex-col w-full gap-5 trend-prods sm:flex-row lg:w-7/12 sm:h-96">
                 <!-- seller -->
                 <div class="w-full h-auto seller sm:h-full sm:w-1/2">
                     <div class="w-full h-full item-img">
                         <a href="{{ $promo_poster_one->link }}">
                             <img src="{{ storage_url($promo_poster_one->image) }}" class="object-cover w-full h-full"
                                 alt="{{ $promo_poster_one->title }}" />
                         </a>
                     </div>
                 </div>

                 <!-- trending -->
                 <div class="products h-auto sm:h-full sm:w-1/2 w-full bg-[#F8F8F8] rounded-lg">
                     <!-- Product Cards -->
                     <div class="p-5 trending-phones">
                         <h3 class="mb-4 text-lg font-semibold capitalize text-rangoon-green">
                             Trending Products
                             <span class="block w-28 h-[1.85px] bg-theme-teal"></span>
                         </h3>
                         <div class="space-y-4 trending-items-wrapper">
                             <!-- item 1 -->
                             @foreach ($trending_products as $product)
                                 <div class="flex gap-3 py-2 border-b border-dashed group/trending trending-item-card">
                                     <div class="w-1/4 item-image">
                                         <a href="{{ route('products.details', $product->slug) }}" target="_blank">
                                             <img src="{{ storage_url($product->thumbnail) }}"
                                                 alt="{{ $product->slug }}"
                                                 class="object-contain w-full h-full group-hover/trending:rotate-12 eq"
                                                 loading="lazy" />
                                         </a>
                                     </div>
                                     <div class="flex flex-col w-3/4 gap-2 text-xs item-details">
                                         <h4>
                                             <a href="{{ route('products.details', $product->slug) }}" target="_self"
                                                 class="font-semibold text-theme-dark line-clamp-1 group-hover/trending:text-theme-teal eq">
                                                 {{ $product->name }}
                                             </a>
                                         </h4>
                                         <p class="text-jet-gray">{{ $product->quantity }}
                                             {{ $product->unit->name }}</p>
                                         <p class="font-semibold text-theme-teal">
                                             {{ money($product->selling_price) }}</p>
                                     </div>
                                 </div>
                             @endforeach
                         </div>
                     </div>
                 </div>
             </div>

             <!-- promotional poster -->
             <div class="w-full h-auto promotional-poster lg:w-5/12 sm:h-96">
                 <div class="w-full h-full overflow-hidden promo-img rounded-2xl">
                     <a href="{{ $promo_poster_two->link }}">
                         <img src="{{ storage_url($promo_poster_two->image) }}"
                             class="object-cover w-full h-full sm:object-contain"
                             alt="{{ $promo_poster_two->title }}" />
                     </a>
                 </div>
             </div>
         </div>
     </div>
 </section>
