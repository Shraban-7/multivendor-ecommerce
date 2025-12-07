<?php
$contactLinks = [
    ['title' => 'Help Center', 'route' => route('pages.show', 'help-center')],
    ['title' => 'Returns & Refunds', 'route' => route('pages.show', 'returns-refunds')],
    ['title' => 'Contact Us', 'route' => route('pages.show', 'contact-us')],
];

$infoLinks = [
    ['title' => 'About Us', 'route' => route('pages.show', 'about-us')],
    ['title' => 'Privacy Policy', 'route' => route('pages.show', 'privacy-policy')],
    ['title' => 'Terms and Conditions', 'route' => route('pages.show', 'terms-and-conditions')],
    ['title' => 'Become a Seller', 'route' => route('pages.show', 'become-a-seller')],
];
?>

<footer class="bg-gray-900 text-gray-300 pt-16 relative overflow-hidden">
    <!-- Decorative Top Border -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-600 via-orange-400 to-primary-600"></div>

    <div class="container mx-auto px-4 pb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand Column -->
            <div>
                <a href="#" class="flex items-center gap-2 mb-6">
                    <div class="bg-primary-500 text-white p-1.5 rounded group-hover:rotate-3 transition duration-300">
                        <i class="fas fa-shopping-bag text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-white">Slash<span class="text-primary-500">Mart</span></span>
                </a>
                <p class="text-gray-400 text-sm mb-6 leading-relaxed">{{ $settings->footer_text }}</p>
                <div class="flex gap-3">
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
                    <!-- <a href="#" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center text-white hover:bg-blue-600 hover:-translate-y-1 transition duration-300"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center text-white hover:bg-sky-500 hover:-translate-y-1 transition duration-300"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center text-white hover:bg-pink-600 hover:-translate-y-1 transition duration-300"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center text-white hover:bg-red-600 hover:-translate-y-1 transition duration-300"><i class="fab fa-youtube"></i></a> -->
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Quick Links
                    <span class="absolute -bottom-2 left-0 w-10 h-1 bg-primary-500 rounded-full"></span>
                </h4>
                <ul class="space-y-3 text-sm">
                    @foreach ($infoLinks as $link)
                    <li><a href="{{ $link['route'] }}" class="hover:text-primary-500 hover:pl-2 transition-all duration-300 block">{{ $link['title'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Customer Care
                    <span class="absolute -bottom-2 left-0 w-10 h-1 bg-primary-500 rounded-full"></span>
                </h4>
                <ul class="space-y-3 text-sm">
                    @foreach ($contactLinks as $link)
                    <li><a href="{{ $link['route'] }}" class="hover:text-primary-500 hover:pl-2 transition-all duration-300 block">{{ $link['title'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold text-lg mb-6 relative inline-block">
                    Contact Info
                    <span class="absolute -bottom-2 left-0 w-10 h-1 bg-primary-500 rounded-full"></span>
                </h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex gap-3 items-start">
                        <i class="fas fa-map-marker-alt text-primary-500 mt-1 text-lg"></i>
                        <span class="text-gray-400">{{ $settings->address }}</span>
                    </li>
                    <li class="flex gap-3 items-center">
                        <i class="fas fa-envelope text-primary-500 text-lg"></i>
                        <span class="text-gray-400 hover:text-white cursor-pointer">{{ $settings->email }}</span>
                    </li>
                    <li class="flex gap-3 items-center">
                        <i class="fas fa-phone-alt text-primary-500 text-lg"></i>
                        <span class="text-gray-400 font-bold hover:text-white cursor-pointer">{{ $settings->phone }}</span>
                    </li>
                </ul>
                <!-- <div class="mt-6">
                    <h5 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Payment Methods</h5>
                    <div class="flex gap-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/1200px-MasterCard_Logo.svg.png" class="h-6 bg-white px-1 rounded">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" class="h-6 bg-white px-1 rounded">
                        <img src="https://seeklogo.com/images/B/bkash-logo-0C52960A6D-seeklogo.com.png" class="h-6 bg-white px-1 rounded">
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-12 pt-6 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} SlashMart. All Rights Reserved.</p>
            <div class="flex gap-4 mt-2 md:mt-0">
                <a href="#" class="hover:text-white transition">Terms</a>
                <a href="#" class="hover:text-white transition">Privacy</a>
                <a href="#" class="hover:text-white transition">Cookies</a>
            </div>
        </div>
    </div>
</footer>