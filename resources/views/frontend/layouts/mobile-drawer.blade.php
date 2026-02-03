<!-- ==================== MOBILE MENU OVERLAY & DRAWER ==================== -->
<div id="mobileMenuOverlay" class="fixed inset-0 bg-black/50 z-50 hidden opacity-0"></div>

<div id="mobileMenuDrawer"
     class="fixed top-0 left-0 w-[85%] max-w-[300px] h-full bg-white z-[60] transform -translate-x-full shadow-2xl flex flex-col">
    
    <!-- Drawer Header -->
    <div class="p-4 bg-primary-500 text-white flex justify-between items-center">
        <span class="font-bold text-lg">Menu</span>
        <button id="closeMobileMenu" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Drawer Content (scrollable) -->
    <div class="p-4 overflow-y-auto flex-1">
        <ul class="space-y-1">

            <!-- Home -->
            <li>
                <a href="#" class="block py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">
                    Home
                </a>
            </li>

            <!-- Dynamic Categories -->
            @foreach (dropdown_categories() as $cat)
                <li>
                    <button type="button"
                        class="mob-acc-btn w-full flex justify-between items-center py-2.5 px-2 
                               text-gray-700 font-medium hover:bg-gray-50 rounded"
                        data-target="#mob-{{ $cat->slug }}">
                        
                        <span>{{ $cat->name }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>

                    <!-- Submenu -->
                    <div id="mob-{{ $cat->slug }}" class="mob-acc-panel pl-4 bg-gray-50 rounded-lg">
                        <ul class="py-2 space-y-2 text-sm text-gray-600">

                            <!-- Category link -->
                            <li>
                                <a class="font-semibold text-primary-600"
                                   href="{{ route('products.index',['category'=>$cat->slug]) }}">
                                   {{ $cat->name }}
                                </a>
                            </li>

                            <!-- Subcategories -->
                            @foreach ($cat->subcategories as $sub)
                                <li>
                                    <a class="hover:text-primary-600"
                                       href="{{ route('products.index',['subcategory'=>$sub->slug]) }}">
                                       {{ $sub->name }}
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </li>
            @endforeach

            <!-- Other Static Links -->
            <li>
                <a href="#" class="block py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">
                    Shop
                </a>
            </li>
            <li>
                <a href="#" class="block py-2.5 px-2 text-gray-700 font-medium hover:bg-gray-50 rounded">
                    Track Order
                </a>
            </li>
        </ul>
    </div>

    <!-- Drawer Footer (always bottom) -->
    <div class="p-4 border-t border-gray-100 bg-gray-50">
        <a href="#" class="auth-btn flex items-center gap-2 text-primary-600 font-bold mb-2">
            <i class="far fa-user"></i> Login / Register
        </a>
    </div>
</div>

<!-- Styles -->
<style>
    .mob-acc-panel { display: none; }
    .mob-acc-btn .rot { transform: rotate(180deg); }
</style>

<!-- Accordion Logic -->
<script>
    $(function () {
        $('.mob-acc-panel').hide();
        $('.mob-acc-btn').on('click', function () {
            const $btn = $(this);
            const $icon = $btn.find('i');
            const target = $btn.data('target');
            const $panel = $(target);
            $('.mob-acc-panel').not($panel).stop(true, true).slideUp(200);
            $('.mob-acc-btn i').not($icon).removeClass('rot');

            if ($panel.is(':visible')) {
                $panel.stop(true, true).slideUp(200);
                $icon.removeClass('rot');
            } else {
                $panel.stop(true, true).slideDown(200);
                $icon.addClass('rot');
            }
        });
    });
</script>
