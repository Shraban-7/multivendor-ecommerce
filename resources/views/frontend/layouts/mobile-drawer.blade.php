<!-- ==================== MOBILE MENU OVERLAY & DRAWER ==================== -->
<div id="mobileMenuOverlay" class="fixed inset-0 bg-black/50 z-50 hidden opacity-0"></div>

<div id="mobileMenuDrawer" class="fixed top-0 left-0 w-[85%] max-w-[300px] h-full bg-white z-[60] transform -translate-x-full shadow-2xl overflow-y-auto">
    <!-- Drawer Header -->
    <div class="p-4 bg-primary-500 text-white flex justify-between items-center">
        <span class="font-bold text-lg">Menu</span>
        <button id="closeMobileMenu" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20"><i class="fas fa-times"></i></button>
    </div>

    <!-- Drawer Content -->
    <div class="p-4">
        <ul class="space-y-1">
            <li><a href="#" class="block py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">Home</a></li>

            <!-- Mobile Accordion -->
            <li>
                <button onclick="toggleMobileSubmenu('mob-electronics')" class="w-full flex justify-between items-center py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">
                    <span>Electronics</span> <i class="fas fa-chevron-down text-xs transition-transform duration-300" id="icon-mob-electronics"></i>
                </button>
                <div id="mob-electronics" class="mobile-submenu pl-4 bg-gray-50 rounded-lg">
                    <ul class="py-2 space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="block">Mobiles</a></li>
                        <li><a href="#" class="block">Laptops</a></li>
                        <li><a href="#" class="block">Accessories</a></li>
                    </ul>
                </div>
            </li>

            <li>
                <button onclick="toggleMobileSubmenu('mob-fashion')" class="w-full flex justify-between items-center py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">
                    <span>Fashion</span> <i class="fas fa-chevron-down text-xs transition-transform duration-300" id="icon-mob-fashion"></i>
                </button>
                <div id="mob-fashion" class="mobile-submenu pl-4 bg-gray-50 rounded-lg">
                    <ul class="py-2 space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="block">Men</a></li>
                        <li><a href="#" class="block">Women</a></li>
                        <li><a href="#" class="block">Kids</a></li>
                    </ul>
                </div>
            </li>

            <li><a href="#" class="block py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">Shop</a></li>
            <li><a href="#" class="block py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">Track Order</a></li>
        </ul>
    </div>

    <!-- Drawer Footer -->
    <div class="mt-auto p-4 border-t border-gray-100 bg-gray-50">
        <a href="#" class="flex items-center gap-2 text-primary-600 font-bold mb-4">
            <i class="far fa-user"></i> Login / Register
        </a>
        <div class="text-xs text-gray-400">
            <p>Call Us: +880 1700-000000</p>
            <p>Email: info@slashmart.com</p>
        </div>
    </div>
</div>