<footer class="bg-white pt-16 border-t border-gray-200">
    <div class="container mx-auto px-4 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <a href="#" class="flex items-center gap-2 mb-4">
                    <i class="fas fa-shopping-bag text-primary-600 text-2xl"></i>
                    <span class="text-2xl font-bold text-gray-900">Slash<span
                            class="text-primary-600">Mart</span></span>
                </a>
                <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                    {{ $settings->footer_text }}
                </p>

                <div class="flex gap-4">
                    @foreach (social_links() as $socialLink)
                    @php
                    $color = $socialLink->color;
                    $bg = "bg-{$color}-100";
                    $text = "text-{$color}-600";
                    $hoverBg = "hover:bg-{$color}-600";
                    $hoverText = 'hover:text-white';
                    @endphp

                    <a href="{{ $socialLink->link }}"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition {{ $bg }} {{ $text }} {{ $hoverBg }} {{ $hoverText }}">
                        <i class="fa-brands {{ $socialLink->icon_name }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-gray-900 font-bold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-primary-600 transition">About Us</a></li>
                    <li><a href="#" class="hover:text-primary-600 transition">Contact Us</a></li>
                    <li><a href="#" class="hover:text-primary-600 transition">Blog</a></li>
                    <li><a href="shop.html" class="hover:text-primary-600 transition">Flash Sales</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-gray-900 font-bold mb-4">Customer Care</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-primary-600 transition">Help Center</a></li>
                    <li><a href="#" class="hover:text-primary-600 transition">Returns & Refunds</a></li>
                    <li><a href="#" class="hover:text-primary-600 transition">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-primary-600 transition">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-gray-900 font-bold mb-4">Contact Us</h4>
                <ul class="space-y-3 text-sm text-gray-500">
                    <li class="flex gap-3"><i
                            class="fas fa-map-marker-alt text-primary-500 mt-1"></i><span>{{ $settings->address }}</span>
                    </li>
                    <li class="flex gap-3"><i
                            class="fas fa-envelope text-primary-500 mt-1"></i><span>{{ $settings->email }}</span>
                    </li>
                    <li class="flex gap-3"><i
                            class="fas fa-phone text-primary-500 mt-1"></i><span>{{ $settings->phone }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-100 mt-10 pt-6 text-center text-sm text-gray-400">
            <p>&copy; 2025 SlashMart. All Rights Reserved.</p>
        </div>
    </div>
</footer>