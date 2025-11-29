@extends('frontend.layouts.app')
@section('content')

<!-- ==================== QUICK VIEW MODAL ==================== -->
<div x-show="quickViewOpen" x-transition class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm" x-cloak>
    <div @click.away="quickViewOpen = false" class="bg-white rounded-2xl w-[95%] max-w-4xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[90vh]">
        <!-- Image Side -->
        <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-4 relative">
            <button @click="quickViewOpen = false" class="absolute top-4 left-4 md:hidden w-8 h-8 flex items-center justify-center bg-white rounded-full shadow"><i class="fa-solid fa-times"></i></button>
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=600&auto=format&fit=crop" alt="Product" class="max-h-[300px] md:max-h-[400px] object-contain mix-blend-multiply">
        </div>
        <!-- Details Side -->
        <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col overflow-y-auto">
            <div class="flex justify-between items-start">
                <div>
                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">In Stock</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-2">Nike Air Premium Runner</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm">(124 Reviews)</span>
                    </div>
                </div>
                <button @click="quickViewOpen = false" class="hidden md:block text-gray-400 hover:text-red-500 text-xl"><i class="fa-solid fa-times"></i></button>
            </div>

            <div class="mt-4 border-b border-gray-100 pb-4">
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-bold text-primary-600">৳ 4,500</span>
                    <span class="text-gray-400 line-through mb-1">৳ 6,200</span>
                    <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold mb-1">-27%</span>
                </div>
                <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                    Authentic premium running shoes designed for maximum comfort and durability. Perfect for daily wear or sports activities. Imported directly.
                </p>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <span class="block text-sm font-semibold text-gray-700 mb-2">Color</span>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 rounded-full bg-red-500 ring-2 ring-offset-2 ring-gray-300 focus:ring-primary-500"></button>
                        <button class="w-8 h-8 rounded-full bg-blue-500"></button>
                        <button class="w-8 h-8 rounded-full bg-black"></button>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <div class="flex border border-gray-300 rounded-lg overflow-hidden w-24">
                        <button class="px-3 bg-gray-50 hover:bg-gray-100">-</button>
                        <input type="text" value="1" class="w-full text-center border-none focus:ring-0 text-sm">
                        <button class="px-3 bg-gray-50 hover:bg-gray-100">+</button>
                    </div>
                    <button class="flex-1 bg-primary-600 text-white font-semibold py-2.5 rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/30">
                        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
                    <button class="w-12 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-300 transition">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>
            <div class="mt-auto pt-4 text-xs text-gray-500 flex gap-4">
                <span>SKU: NIK-001</span>
                <span>Category: Shoes</span>
            </div>
        </div>
    </div>
</div>

<!-- ==================== 3. HERO BANNER AREA ==================== -->
<section class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-auto lg:h-[450px]">
        <!-- Main Slider (Spans 3 cols) -->
        <div class="lg:col-span-3 relative rounded-2xl overflow-hidden shadow-lg group">
            <div class="absolute inset-0 bg-gray-900/10 z-10 group-hover:bg-transparent transition"></div>
            <!-- Banner Image -->
            <img src="https://images.unsplash.com/photo-1607082349566-187342175e2f?q=80&w=1200&auto=format&fit=crop" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700" alt="Hero Banner">

            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-center px-8 lg:px-16 z-20 bg-gradient-to-r from-black/60 to-transparent">
                <span class="text-primary-500 font-bold tracking-widest uppercase mb-2 animate-bounce">Exclusive Offer</span>
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-4 leading-tight">Eid Collection <br> <span class="text-primary-400">Up to 70% Off</span></h1>
                <p class="text-gray-200 mb-8 max-w-md text-sm lg:text-base">Discover the latest trends in fashion, electronics, and lifestyle with our exclusive Eid Mega Sale.</p>
                <a href="#" class="w-fit bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-full font-semibold transition shadow-lg shadow-primary-500/50 flex items-center gap-2">
                    Shop Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Side Banners (Stacked) -->
        <div class="hidden lg:flex flex-col gap-6 h-full">
            <div class="relative rounded-2xl overflow-hidden h-1/2 shadow-md group">
                <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-black/30 p-6 flex flex-col justify-end">
                    <h3 class="text-white text-xl font-bold">New Gadgets</h3>
                    <a href="#" class="text-primary-400 text-sm font-semibold hover:underline mt-1">Discover <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
            <div class="relative rounded-2xl overflow-hidden h-1/2 shadow-md group">
                <img src="https://images.unsplash.com/photo-1596462502278-27bfdd403348?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-black/30 p-6 flex flex-col justify-end">
                    <h3 class="text-white text-xl font-bold">Beauty Picks</h3>
                    <a href="#" class="text-primary-400 text-sm font-semibold hover:underline mt-1">Shop Now <i class="fas fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 15. WHY CHOOSE US (Highlight) ==================== -->
<section class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4 p-2">
            <div class="bg-orange-50 w-12 h-12 rounded-full flex items-center justify-center text-primary-600 text-xl"><i class="fas fa-shipping-fast"></i></div>
            <div>
                <h4 class="font-bold text-gray-800 text-sm">Fast Delivery</h4>
                <p class="text-xs text-gray-500">All over Bangladesh</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-2">
            <div class="bg-blue-50 w-12 h-12 rounded-full flex items-center justify-center text-blue-600 text-xl"><i class="fas fa-shield-alt"></i></div>
            <div>
                <h4 class="font-bold text-gray-800 text-sm">Secure Payment</h4>
                <p class="text-xs text-gray-500">100% Safe Transaction</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-2">
            <div class="bg-green-50 w-12 h-12 rounded-full flex items-center justify-center text-green-600 text-xl"><i class="fas fa-undo"></i></div>
            <div>
                <h4 class="font-bold text-gray-800 text-sm">Easy Return</h4>
                <p class="text-xs text-gray-500">7 Days Return Policy</p>
            </div>
        </div>
        <div class="flex items-center gap-4 p-2">
            <div class="bg-purple-50 w-12 h-12 rounded-full flex items-center justify-center text-purple-600 text-xl"><i class="fas fa-headset"></i></div>
            <div>
                <h4 class="font-bold text-gray-800 text-sm">24/7 Support</h4>
                <p class="text-xs text-gray-500">Dedicated Support</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 5. CATEGORY GRID SECTION ==================== -->
<section class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Shop By <span class="text-primary-600">Category</span></h2>
        <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700">View All <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-4">
        <!-- Category Item (Loop for 8) -->
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Mobiles</span>
        </a>
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-laptop"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Laptops</span>
        </a>
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-tshirt"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Men's Fashion</span>
        </a>
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-female"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Women's</span>
        </a>
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-couch"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Home & Living</span>
        </a>
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-camera"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Photography</span>
        </a>
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-running"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Sports</span>
        </a>
        <a href="#" class="group flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-sm border border-transparent hover:border-primary-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl text-gray-600 group-hover:bg-primary-50 group-hover:text-primary-600 transition">
                <i class="fas fa-gift"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 text-center group-hover:text-primary-600">Gifts</span>
        </a>
    </div>
</section>

<!-- ==================== 6. FLASH SALE SECTION ==================== -->
<section class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row items-center justify-between border-b border-gray-100 pb-4 mb-6 gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-bolt text-primary-500"></i> Flash <span class="text-primary-600">Sale</span>
                </h2>
                <div class="flex gap-2 items-center text-white text-xs font-bold">
                    <span class="bg-gray-800 p-1.5 rounded">05</span> :
                    <span class="bg-primary-600 p-1.5 rounded">32</span> :
                    <span class="bg-gray-800 p-1.5 rounded">45</span>
                </div>
            </div>
            <a href="#" class="text-sm font-semibold text-primary-600 border border-primary-600 px-4 py-1.5 rounded-full hover:bg-primary-600 hover:text-white transition">See All Products</a>
        </div>

        <!-- Scrollable Product List -->
        <div class="flex overflow-x-auto gap-4 pb-4 hide-scroll snap-x">
            @foreach ($data['featured_products'] as $product)
            <div class="min-w-[200px] md:min-w-[240px] snap-start bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group relative">
                <!-- Badges -->
                <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
                    <span class="bg-primary-600 text-white text-[10px] font-bold px-2 py-1 rounded">-40%</span>
                </div>

                <!-- Image Container -->
                <div class="relative h-48 w-full bg-gray-100 rounded-t-xl overflow-hidden p-4 flex items-center justify-center">
                    <img src="{{ $product['thumbnail'] }}" class="max-h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">

                    <!-- Hover Actions -->
                    <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2">
                        <button @click="quickViewOpen = true" class="w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-75"><i class="far fa-eye"></i></button>
                        <button class="w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-100"><i class="far fa-heart"></i></button>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-3">
                    <h3 class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-primary-600 cursor-pointer mb-1">{{ $product['name'] }}</h3>
                    <div class="flex items-center gap-1 mb-2">
                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                        <span class="text-xs text-gray-400">(4.5)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($product['discounted_price'])
                        <span class="text-primary-600 font-bold text-lg">{{ money($product['discounted_price']) }}</span>
                        <span class="text-gray-400 text-xs line-through">{{ money($product['selling_price']) }}</span>
                        @else
                        <span class="text-primary-600 font-bold text-lg">{{ money($product['selling_price']) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Add Cart Button (Initially hidden or styled differently) -->
                <div class="p-3 pt-0">
                    <button class="w-full py-2 rounded-lg bg-gray-100 text-gray-800 text-xs font-bold hover:bg-primary-600 hover:text-white transition group-hover:bg-primary-600 group-hover:text-white">Add To Cart</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ==================== 13. SPECIAL CAMPAIGN BANNER ==================== -->
<section class="container mx-auto px-4 py-4">
    <div class="relative rounded-2xl overflow-hidden shadow-lg h-40 md:h-64 flex items-center bg-gray-900">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1596462502278-27bfdd403348?q=80&w=1200')] bg-cover bg-center opacity-40"></div>
        <div class="relative z-10 p-8 md:pl-16">
            <span class="text-yellow-400 font-bold tracking-widest text-sm uppercase mb-2 block">Black Friday Special</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Flat 50% Discount <br>On Traditional Wear</h2>
            <button class="bg-white text-gray-900 px-6 py-2.5 rounded-lg font-bold hover:bg-primary-500 hover:text-white transition shadow-lg">Check Offers</button>
        </div>
    </div>
</section>

<!-- ==================== 7 & 8. FEATURED VENDORS & SELLERS ==================== -->
<section class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Top <span class="text-primary-600">Sellers</span></h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Seller Card -->
        @foreach ($data['sellers'] as $seller)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition text-center group cursor-pointer">
            <div class="w-16 h-16 mx-auto mb-3">
                <img src="{{ storage_url($seller->business_logo) }}" class="w-full h-full object-cover rounded-full border-2 border-gray-100 group-hover:border-primary-500">
            </div>
            <h3 class="font-bold text-gray-800 text-sm mb-1 group-hover:text-primary-600">{{ $seller->business_name }}</h3>
            <div class="flex justify-center text-xs text-yellow-400 mb-2">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <button class="text-xs font-medium text-primary-600 border border-primary-200 px-3 py-1 rounded-full group-hover:bg-primary-600 group-hover:text-white transition">Visit Store</button>
        </div>
        @endforeach
    </div>
</section>

<!-- ==================== 9. FEATURED PRODUCTS (GRID) ==================== -->
<section class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Featured <span class="text-primary-600">Products</span></h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach ($data['featured_products'] as $product)
        <div class="bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-2xl transition-all duration-300 group overflow-hidden flex flex-col h-full relative">
            <div class="relative h-48 w-full bg-gray-50 p-4 flex items-center justify-center overflow-hidden">
                <img src="{{ $product['thumbnail'] }}" class="max-h-full object-contain hover:scale-105 transition duration-500 mix-blend-multiply z-0">
                <div class="absolute top-2 right-2 z-10">
                    <button class="w-8 h-8 rounded-full bg-white text-gray-400 hover:text-red-500 shadow flex items-center justify-center transition"><i class="far fa-heart"></i></button>
                </div>
                <!-- Quick View Button (Centered Overlay on Hover) -->
                <!-- FIX: Changed from bottom-absolute to centered-absolute with opacity transition -->
                <div class="absolute inset-0 bg-black/10 z-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button @click="quickViewOpen = true" class="bg-white text-gray-900 px-4 py-2 rounded-full text-xs font-bold hover:bg-primary-600 hover:text-white shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                        <i class="far fa-eye mr-1"></i> Quick View
                    </button>
                </div>
            </div>

            <!-- Content Container -->
            <div class="p-3 flex flex-col flex-1 relative z-20 bg-white">
                <span class="text-[10px] text-gray-500 uppercase tracking-wide mb-1">{{ $product['category'] }}</span>
                <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-auto hover:text-primary-600 transition cursor-pointer">{{ $product['name'] }}</h3>

                <div class="mt-2 pt-2 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex flex-col">
                        @if($product['discounted_price'])
                        <span class="text-xs text-gray-400 line-through">{{ money($product['discounted_price']) }}</span>
                        <span class="text-primary-600 font-bold">{{ money($product['selling_price']) }}</span>
                        @else
                        <span class="text-primary-600 font-bold">{{ money($product['selling_price']) }}</span>
                        @endif
                    </div>
                    <button class="bg-primary-100 text-primary-700 w-8 h-8 rounded-full hover:bg-primary-600 hover:text-white transition flex items-center justify-center"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-10">
        <button class="bg-white border-2 border-gray-200 text-gray-700 font-bold py-3 px-8 rounded-full hover:border-primary-500 hover:text-primary-600 transition">Load More Products</button>
    </div>
</section>

<!-- ==================== 14. POPULAR BRANDS SLIDER ==================== -->
<section class="container mx-auto px-4 py-8 border-t border-gray-200">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Popular <span class="text-primary-600">Brands</span></h2>
    <div class="flex flex-wrap justify-center md:justify-between items-center gap-8 opacity-60 grayscale hover:grayscale-0 transition duration-500">
        <!-- Brand Logos (FontAwesome placeholders for demo) -->
        <i class="fab fa-apple text-5xl hover:text-gray-900 cursor-pointer"></i>
        <i class="fab fa-samsung text-5xl hover:text-blue-600 cursor-pointer"></i>
        <i class="fab fa-sony text-5xl hover:text-gray-900 cursor-pointer"></i>
        <i class="fab fa-microsoft text-5xl hover:text-blue-500 cursor-pointer"></i>
        <i class="fab fa-google text-5xl hover:text-red-500 cursor-pointer"></i>
        <i class="fab fa-nike text-5xl hover:text-gray-900 cursor-pointer"></i>
    </div>
</section>

<!-- ==================== 16. TESTIMONIALS ==================== -->
<section class="bg-orange-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">What Our Customers Say</h2>
            <p class="text-gray-500">Thousands of happy customers across Bangladesh</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Review Card -->
            <div class="bg-white p-6 rounded-2xl shadow-sm relative">
                <div class="text-primary-500 text-4xl opacity-20 absolute top-4 right-4"><i class="fas fa-quote-right"></i></div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="text-yellow-400 text-sm"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                </div>
                <p class="text-gray-600 text-sm italic mb-6">"Fast delivery within Dhaka. The product quality was exactly as described. Highly recommended for authentic gadgets."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Rahim Uddin</h4>
                        <span class="text-xs text-gray-500">Dhaka, Bangladesh</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm relative">
                <div class="text-primary-500 text-4xl opacity-20 absolute top-4 right-4"><i class="fas fa-quote-right"></i></div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="text-yellow-400 text-sm"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                </div>
                <p class="text-gray-600 text-sm italic mb-6">"Best prices I found online. The packaging was secure and customer service helped me track my order."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Fatema Begum</h4>
                        <span class="text-xs text-gray-500">Chittagong, Bangladesh</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm relative">
                <div class="text-primary-500 text-4xl opacity-20 absolute top-4 right-4"><i class="fas fa-quote-right"></i></div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="text-yellow-400 text-sm"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                </div>
                <p class="text-gray-600 text-sm italic mb-6">"Excellent collection of traditional wear. Bought a Panjabi for Eid and the fabric is premium."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden">
                        <img src="https://randomuser.me/api/portraits/men/85.jpg" alt="User">
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Tanvir Ahmed</h4>
                        <span class="text-xs text-gray-500">Sylhet, Bangladesh</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 17 & 18. NEWSLETTER & APP DOWNLOAD ==================== -->
<section class="container mx-auto px-4 py-12">
    <div class="bg-gray-900 rounded-3xl p-8 md:p-12 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
        <!-- Decor Circles -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-primary-600 opacity-20 blur-3xl"></div>

        <div class="relative z-10 w-full md:w-1/2 text-center md:text-left">
            <span class="text-primary-500 font-bold tracking-widest text-sm uppercase">Mobile App</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-4">Shop Faster with our App</h2>
            <p class="text-gray-400 mb-6">Get exclusive app-only discounts and real-time order tracking. Available for iOS and Android.</p>
            <div class="flex gap-4 justify-center md:justify-start">
                <button class="bg-gray-800 border border-gray-700 hover:border-white text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="fab fa-apple text-2xl"></i>
                    <div class="text-left">
                        <div class="text-[10px] leading-none text-gray-400">Download on the</div>
                        <div class="text-sm font-bold leading-none">App Store</div>
                    </div>
                </button>
                <button class="bg-gray-800 border border-gray-700 hover:border-white text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="fab fa-google-play text-2xl text-green-500"></i>
                    <div class="text-left">
                        <div class="text-[10px] leading-none text-gray-400">Get it on</div>
                        <div class="text-sm font-bold leading-none">Google Play</div>
                    </div>
                </button>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl relative z-10">
            <h3 class="text-white text-xl font-bold mb-2">Subscribe to our Newsletter</h3>
            <p class="text-gray-300 text-sm mb-4">Subscribe to the weekly newsletter for all the latest updates & exclusive offers.</p>
            <form class="flex flex-col gap-3">
                <input type="email" placeholder="Your Email Address" class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-primary-500">
                <button type="button" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-lg transition shadow-lg shadow-primary-500/30">Subscribe Now</button>
            </form>
        </div>
    </div>
</section>

@endsection