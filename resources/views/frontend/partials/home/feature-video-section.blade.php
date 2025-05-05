 <section class="featured-videos-section section-padding">
     <div class="container">
         <!-- Section Title -->
         <div class="relative sec-heading">
             <h2 class="font-semibold uppercase sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark">
                 Featured In Videos
             </h2>

             <span class="absolute right-0 inline-block -translate-y-1/2 top-1/2"><a href="#"
                     class="theme-btn theme-outline-btn">View All</a></span>
         </div>

         <!-- Featured Video Swiper Slider -->
         <div class="mt-5 swiper featuredVideoSwiper md:mt-10">
             <div class="swiper-wrapper">
                 @foreach ($featured_products as $product)
                     <!-- slide 1 -->
                     <div class="py-3 swiper-slide group/featured-videos-pro-card eq">
                         <div
                             class="relative overflow-hidden border rounded-t-lg rounded-b-sm group hover:shadow-lg eq">
                             <div class="relative w-full sm:h-[30rem] h-96 overflow-hidden">
                                 <video controls muted loop class="object-cover w-full h-full cursor-pointer"
                                     poster="{{ storage_url($product->thumbnail) }}">
                                     <source src="{{ storage_url($product->video) }}" type="video/mp4" />
                                 </video>
                                 <div class="absolute w-1/3 bottom-3 sm:bottom-5 left-3 sm:left-5 md:left-8">
                                     <a href="#"
                                         class="block w-full font-light text-white truncate hover:text-light-yellow eq">@jesikaperker07854</a>
                                 </div>
                                 <div
                                     class="absolute flex gap-2 bottom-3 sm:bottom-5 right-3 sm:right-5 md:right-8 md:gap-3">
                                     <button
                                         class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full play-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                         <i class="fa-solid fa-play"></i>
                                     </button>
                                     <button
                                         class="flex items-center justify-center w-8 h-8 p-2 text-white transition-colors rounded-full mute-btn bg-white/20 hover:bg-white/30 sm:w-10 sm:h-10">
                                         <i class="fa-solid fa-volume-high"></i>
                                     </button>
                                 </div>
                             </div>
                             <!-- Product Info -->
                             <div class="flex items-start gap-3 px-2 py-4 sm:px-3 md:px-6">
                                 <div class="overflow-hidden w-15 h-15">
                                     <a href="#">
                                         <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                             class="w-12 h-auto object-cover" />
                                     </a>
                                 </div>
                                 <div class="flex-1">
                                     <p class="font-semibold">{{ money($product->selling_price) }}</p>
                                     <p class="text-xs text-gray-400 line-clamp-2">
                                         {{ $product->name }}
                                     </p>
                                 </div>
                             </div>
                         </div>
                     </div>
                 @endforeach
             </div>
         </div>
     </div>
 </section>
