@php
    $settings = settings();
    $appName = app_name();
    $socialLinks = social_links();
@endphp

<footer class="bg-[#191919] text-[#F5F5F5] mt-8">
    <!-- Main Footer Links -->
    <div class="max-w-[1400px] mx-auto px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Brand -->
            <div>
                <div class="flex items-center gap-2 mb-5">
                    @if (! empty($settings?->logo_white))
                        <img
                            src="{{ storage_url($settings->logo_white) }}"
                            alt="{{ $appName }}"
                            class="h-16 sm:h-20 lg:h-24 w-auto max-w-[220px] sm:max-w-[280px] lg:max-w-[320px] object-contain object-left"
                        >
                    @else
                        <span class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#F85606]">{{ $appName }}</span>
                    @endif
                </div>
                <p class="text-sm text-[#767676] mb-4">Bangladesh's leading online marketplace with thousands of products at the best prices.</p>
                @if ($socialLinks->isNotEmpty())
                    <div class="flex items-center gap-3">
                        @foreach ($socialLinks as $link)
                            <a href="{{ $link->link }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 flex items-center justify-center rounded-full bg-[#2A2A2A] hover:bg-[#F85606] eq text-[#F5F5F5]" aria-label="{{ $link->name }}">
                                <i class="{{ $link->icon_name }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Quick Links -->
            <div class="footer-accordion">
                <button class="flex items-center justify-between w-full sm:cursor-default text-sm font-semibold text-[#F5F5F5] mb-3 sm:mb-4 py-2 sm:py-0" onclick="toggleAccordion(this)" aria-expanded="false">
                    Quick Links
                    <svg class="w-4 h-4 sm:hidden transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="footer-accordion-content hidden sm:block space-y-2">
                    <a href="{{ url('/') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">Home</a>
                    <a href="{{ route('products.index') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">All Products</a>
                    <a href="{{ route('sellers.index') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">Top Sellers</a>
                    <a href="{{ route('flashSales.index') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">Flash Sales</a>
                    <a href="{{ route('pages.show', 'about-us') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">About Us</a>
                </div>
            </div>

            <!-- Customer Care -->
            <div class="footer-accordion">
                <button class="flex items-center justify-between w-full sm:cursor-default text-sm font-semibold text-[#F5F5F5] mb-3 sm:mb-4 py-2 sm:py-0" onclick="toggleAccordion(this)" aria-expanded="false">
                    Customer Care
                    <svg class="w-4 h-4 sm:hidden transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="footer-accordion-content hidden sm:block space-y-2">
                    <a href="{{ route('pages.show', 'help-center') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">Help Center</a>
                    <a href="{{ route('pages.show', 'return-policy') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">Return Policy</a>
                    <a href="{{ route('pages.show', 'shipping-info') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">Shipping Info</a>
                    <a href="{{ route('contactUs') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">Contact Us</a>
                    <a href="{{ route('pages.show', 'faq') }}" class="block text-sm text-[#767676] hover:text-[#F85606] eq">FAQ</a>
                </div>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-sm font-semibold text-[#F5F5F5] mb-4">Contact Info</h3>
                <div class="space-y-3">
                    @if (! empty($settings?->address))
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 text-[#F85606] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm text-[#767676]">{{ $settings->address }}</span>
                        </div>
                    @endif
                    @if (! empty($settings?->phone))
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#F85606]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:{{ $settings->phone }}" class="text-sm text-[#767676] hover:text-[#F85606] eq">{{ $settings->phone }}</a>
                        </div>
                    @endif
                    @if (! empty($settings?->email))
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#F85606]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $settings->email }}" class="text-sm text-[#767676] hover:text-[#F85606] eq">{{ $settings->email }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright Bar -->
    <div class="border-t border-[#2A2A2A]">
        <div class="max-w-[1400px] mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-[#595959]">{{ $settings?->footer_text ?: ('© '.date('Y').' '.$appName.'. All rights reserved.') }}</p>
            <div class="flex items-center gap-4 text-xs text-[#595959]">
                <a href="{{ route('pages.show', 'terms-and-conditions') }}" class="hover:text-[#F85606] eq">Terms &amp; Conditions</a>
                <a href="{{ route('pages.show', 'privacy-policy') }}" class="hover:text-[#F85606] eq">Privacy Policy</a>
                <a href="{{ route('pages.show', 'cookie-policy') }}" class="hover:text-[#F85606] eq">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<script>
    function toggleAccordion(btn) {
        const content = btn.nextElementSibling;
        const isOpen = !content.classList.contains('hidden');
        content.classList.toggle('hidden');
        btn.setAttribute('aria-expanded', !isOpen);
        const icon = btn.querySelector('svg');
        if (icon) icon.classList.toggle('rotate-180');
    }
</script>
