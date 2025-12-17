<div id="quickViewModalMain"
    class="hidden-custom fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    <!-- Modal Overlay Click Handler attached in JS -->
    <div id="quickViewContent"
        class="bg-white rounded-2xl w-[95%] max-w-4xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[90vh]">
        <!-- Image Side -->
        <div class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center p-4 relative">
            <button
                class="close-quickview absolute top-4 left-4 md:hidden w-8 h-8 flex items-center justify-center bg-white rounded-full shadow"><i
                    class="fa-solid fa-times"></i></button>
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=600&auto=format&fit=crop"
                alt="Product" class="max-h-[300px] md:max-h-[400px] object-contain mix-blend-multiply">
        </div>
        <!-- Details Side -->
        <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col overflow-y-auto">
            <div class="flex justify-between items-start">
                <div>
                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">In
                        Stock</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-2">Nike Air Premium Runner</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-500 text-sm">(124 Reviews)</span>
                    </div>
                </div>
                <button class="close-quickview hidden md:block text-gray-400 hover:text-red-500 text-xl"><i
                        class="fa-solid fa-times"></i></button>
            </div>

            <div class="mt-4 border-b border-gray-100 pb-4">
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-bold text-primary-600">৳ 4,500</span>
                    <span class="text-gray-400 line-through mb-1">৳ 6,200</span>
                    <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold mb-1">-27%</span>
                </div>
                <p class="text-gray-600 text-sm mt-3 leading-relaxed">
                    Authentic premium running shoes designed for maximum comfort and durability. Perfect for daily
                    wear or sports activities. Imported directly.
                </p>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <span class="block text-sm font-semibold text-gray-700 mb-2">Color</span>
                    <div class="flex gap-2">
                        <button
                            class="w-8 h-8 rounded-full bg-red-500 ring-2 ring-offset-2 ring-gray-300 focus:ring-primary-500"></button>
                        <button class="w-8 h-8 rounded-full bg-blue-500"></button>
                        <button class="w-8 h-8 rounded-full bg-black"></button>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <div class="flex border border-gray-300 rounded-lg overflow-hidden w-24">
                        <button class="px-3 bg-gray-50 hover:bg-gray-100">-</button>
                        <input type="text" value="1"
                            class="w-full text-center border-none focus:ring-0 text-sm">
                        <button class="px-3 bg-gray-50 hover:bg-gray-100">+</button>
                    </div>
                    <button
                        class="flex-1 bg-primary-600 text-white font-semibold py-2.5 rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/30">
                        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
                    <button
                        class="w-12 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-300 transition">
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

<div id="quickViewModal"
    class="hidden-custom fixed inset-0 z-[60] flex items-center justify-center 
            bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">

    <!-- Modal Box -->
    <div
        class="relative bg-white rounded-2xl w-[95%] max-w-4xl 
                overflow-hidden shadow-2xl flex flex-col">

        <!-- Close Button -->
        <button id="quickViewCloseBtn"
            class="close-quickview absolute top-3 right-3 w-9 h-9 flex items-center justify-center 
                   rounded-full bg-white shadow hover:bg-gray-200 transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Scroll Container -->
        <div id="quickViewContent" class="quickview-content overflow-y-auto max-h-[80vh]">
            <!-- AJAX will inject here -->
        </div>

    </div>
</div>