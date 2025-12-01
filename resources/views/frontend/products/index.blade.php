@extends('frontend.layouts.app')
@section('title', 'Products')
@section('content')

<div class="bg-white border-b border-gray-200 py-3">
    <div class="container mx-auto px-4">
        <nav class="flex text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="/" class="hover:text-primary-600">Home</a></li>
                <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
                <li class="font-medium text-gray-900" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>
</div>

<section class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- ==================== SIDEBAR FILTERS (Desktop + Mobile Drawer) ==================== -->
        <aside :class="mobileFilterOpen ? 'fixed inset-0 z-50 bg-black/50' : 'hidden lg:block lg:w-1/4'" class="transition-all">

            <!-- Drawer Container (White Box) -->
            <div :class="mobileFilterOpen ? 'fixed inset-y-0 left-0 w-80 bg-white shadow-2xl overflow-y-auto z-50 p-6 animate-slide-in-left' : 'bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24'">

                <!-- Mobile Header -->
                <div class="flex items-center justify-between mb-6 lg:hidden">
                    <h2 class="text-xl font-bold text-gray-900">Filters</h2>
                    <button @click="mobileFilterOpen = false" class="text-gray-500 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
                </div>

                <!-- Filter: Categories -->
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <h3 class="font-bold text-gray-800 mb-3 flex justify-between cursor-pointer">Categories <i class="fas fa-minus text-xs text-gray-400 mt-1"></i></h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>
                            <label class="flex items-center gap-2 hover:text-primary-600 cursor-pointer">
                                <input type="checkbox" class="rounded text-primary-600 focus:ring-primary-500 border-gray-300">
                                Mobiles & Tablets <span class="text-gray-400 text-xs ml-auto">(120)</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 hover:text-primary-600 cursor-pointer">
                                <input type="checkbox" checked class="rounded text-primary-600 focus:ring-primary-500 border-gray-300">
                                Headphones <span class="text-gray-400 text-xs ml-auto">(45)</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 hover:text-primary-600 cursor-pointer">
                                <input type="checkbox" class="rounded text-primary-600 focus:ring-primary-500 border-gray-300">
                                Laptops <span class="text-gray-400 text-xs ml-auto">(32)</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 hover:text-primary-600 cursor-pointer">
                                <input type="checkbox" class="rounded text-primary-600 focus:ring-primary-500 border-gray-300">
                                Smart Watches <span class="text-gray-400 text-xs ml-auto">(18)</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <!-- Filter: Price Range -->
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <h3 class="font-bold text-gray-800 mb-4">Price Range</h3>
                    <div class="space-y-4">
                        <!-- Dual Slider Simulation -->
                        <div class="relative pt-1">
                            <input type="range" min="0" max="50000" x-model="priceMax" class="w-full absolute z-20 opacity-0 cursor-pointer h-2">
                            <input type="range" min="0" max="50000" x-model="priceMin" class="w-full absolute z-20 opacity-0 cursor-pointer h-2">
                            <div class="w-full h-1 bg-gray-200 rounded relative">
                                <div class="absolute h-1 bg-primary-500 rounded left-0" style="width: 30%; left: 10%;"></div>
                            </div>
                            <div class="flex justify-between items-center mt-3">
                                <div class="border border-gray-300 rounded px-2 py-1 bg-gray-50 text-xs w-20 text-center">৳ <span x-text="priceMin"></span></div>
                                <span class="text-gray-400">-</span>
                                <div class="border border-gray-300 rounded px-2 py-1 bg-gray-50 text-xs w-20 text-center">৳ <span x-text="priceMax"></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter: Brands -->
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <h3 class="font-bold text-gray-800 mb-3">Brands</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" class="rounded text-primary-600 border-gray-300"> Samsung
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" class="rounded text-primary-600 border-gray-300"> Apple
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" checked class="rounded text-primary-600 border-gray-300"> Sony
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" class="rounded text-primary-600 border-gray-300"> Xiaomi
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" class="rounded text-primary-600 border-gray-300"> Bose
                        </label>
                    </div>
                </div>

                <!-- Filter: Rating -->
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <h3 class="font-bold text-gray-800 mb-3">Rating</h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" class="rounded text-primary-600 border-gray-300">
                            <span class="text-yellow-400 text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" class="rounded text-primary-600 border-gray-300">
                            <span class="text-yellow-400 text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></span> & Up
                        </label>
                    </div>
                </div>

                <!-- Filter: Colors -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">Color</h3>
                    <div class="flex flex-wrap gap-2">
                        <button class="w-6 h-6 rounded-full bg-black ring-2 ring-offset-2 ring-gray-300 hover:ring-primary-500"></button>
                        <button class="w-6 h-6 rounded-full bg-blue-600"></button>
                        <button class="w-6 h-6 rounded-full bg-red-500"></button>
                        <button class="w-6 h-6 rounded-full bg-green-500"></button>
                        <button class="w-6 h-6 rounded-full bg-gray-200"></button>
                        <button class="w-6 h-6 rounded-full bg-white border border-gray-300"></button>
                    </div>
                </div>

                <!-- Apply Button (Mobile only) -->
                <button @click="mobileFilterOpen = false" class="w-full bg-primary-600 text-white py-3 rounded-lg font-bold lg:hidden mt-4">Apply Filters</button>
            </div>
        </aside>

        <!-- ==================== PRODUCT LISTING AREA ==================== -->
        <main class="w-full lg:w-3/4">

            <!-- Toolbar -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-500">
                    Showing <span class="font-bold text-gray-900">1-12</span> of 45 products
                </div>

                <div class="flex items-center gap-4">
                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 hidden sm:block">Sort by:</span>
                        <select class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2 outline-none">
                            <option>Popularity</option>
                            <option>Newest Arrivals</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                        </select>
                    </div>

                    <!-- View Toggle -->
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button @click="viewType = 'grid'" :class="viewType === 'grid' ? 'bg-white text-primary-600 shadow' : 'text-gray-400 hover:text-gray-600'" class="p-2 rounded transition"><i class="fas fa-th-large"></i></button>
                        <button @click="viewType = 'list'" :class="viewType === 'list' ? 'bg-white text-primary-600 shadow' : 'text-gray-400 hover:text-gray-600'" class="p-2 rounded transition"><i class="fas fa-list"></i></button>
                    </div>
                </div>
            </div>

            <!-- Active Filters Tags -->
            <div class="flex flex-wrap gap-2 mb-6">
                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2">
                    Headphones <button class="hover:text-red-500"><i class="fas fa-times"></i></button>
                </span>
                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2">
                    Sony <button class="hover:text-red-500"><i class="fas fa-times"></i></button>
                </span>
                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2">
                    ৳500 - ৳15000 <button class="hover:text-red-500"><i class="fas fa-times"></i></button>
                </span>
                <button class="text-xs text-red-500 hover:underline font-medium ml-2">Clear All</button>
            </div>

            <!-- Product Grid/List Container -->
            <div :class="viewType === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex flex-col gap-4'">

                <!-- Loop for Products -->
                <template x-for="i in 12">
                    <div :class="viewType === 'grid' ? 'flex-col' : 'flex-row items-center'" class="bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group overflow-hidden flex relative">

                        <!-- Badges (Absolute) -->
                        <div class="absolute top-2 left-2 z-10">
                            <span x-show="i % 3 === 0" class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">SALE</span>
                            <span x-show="i % 4 === 0" class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm ml-1">NEW</span>
                        </div>

                        <!-- Image -->
                        <div :class="viewType === 'grid' ? 'h-56 w-full' : 'h-40 w-48 shrink-0'" class="relative bg-gray-50 p-4 flex items-center justify-center">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=400" class="max-h-full object-contain mix-blend-multiply group-hover:scale-105 transition duration-500">

                            <!-- Hover Actions (Grid View Only) -->
                            <div x-show="viewType === 'grid'" class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2">
                                <button @click="quickViewOpen = true" class="w-10 h-10 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-75"><i class="far fa-eye"></i></button>
                                <button class="w-10 h-10 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-100"><i class="far fa-heart"></i></button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4 flex flex-col flex-1">
                            <span class="text-[10px] text-gray-500 uppercase tracking-wide mb-1">Electronics</span>
                            <h3 class="text-sm font-bold text-gray-800 line-clamp-2 mb-2 hover:text-primary-600 transition cursor-pointer">Sony WH-1000XM5 Wireless Noise Cancelling Headphones</h3>

                            <div class="flex items-center gap-1 mb-2">
                                <div class="flex text-yellow-400 text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                <span class="text-xs text-gray-400">(45)</span>
                            </div>

                            <div class="mt-auto flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 line-through">৳ 35,000</span>
                                    <span class="text-primary-600 font-bold text-lg">৳ 32,500</span>
                                </div>

                                <!-- Buttons differ based on view -->
                                <button x-show="viewType === 'grid'" class="bg-gray-100 text-gray-800 w-10 h-10 rounded-full hover:bg-primary-600 hover:text-white transition flex items-center justify-center"><i class="fas fa-shopping-cart"></i></button>

                                <div x-show="viewType === 'list'" class="flex gap-2">
                                    <button class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 shadow-md">Add to Cart</button>
                                    <button class="w-10 h-10 border border-gray-300 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-300 flex items-center justify-center transition"><i class="far fa-heart"></i></button>
                                </div>
                            </div>

                            <p x-show="viewType === 'list'" class="text-xs text-gray-500 mt-3 line-clamp-2">Industry-leading noise cancellation optimized to you. Magnificent Sound, engineered to perfection. Crystal clear hands-free calling.</p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Pagination / Load More -->
            <div class="mt-12 flex flex-col items-center">
                <p class="text-sm text-gray-500 mb-4">Showing 12 of 45 products</p>
                <div class="w-64 h-2 bg-gray-200 rounded-full overflow-hidden mb-6">
                    <div class="h-full bg-primary-500 w-1/3"></div>
                </div>

                <button class="group relative px-8 py-3 bg-white text-gray-800 font-bold rounded-full border-2 border-primary-500 overflow-hidden transition-colors hover:text-white">
                    <div class="absolute inset-0 w-0 bg-primary-600 transition-all duration-[250ms] ease-out group-hover:w-full"></div>
                    <span class="relative flex items-center gap-2">Load More <i class="fas fa-sync-alt group-hover:animate-spin"></i></span>
                </button>

                <!-- Number Pagination (Alternative) -->
                <div class="flex gap-2 mt-6">
                    <button class="w-10 h-10 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 text-gray-500"><i class="fas fa-chevron-left"></i></button>
                    <button class="w-10 h-10 rounded-lg bg-primary-600 text-white flex items-center justify-center shadow-lg shadow-primary-500/30">1</button>
                    <button class="w-10 h-10 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 text-gray-600">2</button>
                    <button class="w-10 h-10 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 text-gray-600">3</button>
                    <span class="flex items-end px-1 text-gray-400">...</span>
                    <button class="w-10 h-10 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 text-gray-600">8</button>
                    <button class="w-10 h-10 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-100 text-gray-500"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

        </main>
    </div>
</section>

@endsection