@foreach ($sellers as $seller)
    <div class="group bg-white rounded-sm border border-[#E5E5E5] hover:shadow-card transition-shadow duration-200 overflow-hidden">
        <div class="h-24 sm:h-28 relative overflow-hidden bg-[#F5F5F5]">
            @if ($seller->cover_image)
                <img src="{{ storage_url($seller->cover_image) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     loading="lazy" alt="">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
            <span class="absolute top-2 right-2 bg-white/90 text-[10px] font-bold text-[#191919] px-1.5 py-0.5 rounded-xs shadow-sm">VERIFIED</span>
        </div>

        <div class="px-3 pb-3 relative">
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 rounded-full border-2 border-white shadow-sm bg-white overflow-hidden">
                <img src="{{ $seller->business_logo ? storage_url($seller->business_logo) : asset('assets/frontend/images/placeholder.png') }}"
                     class="w-full h-full object-cover" loading="lazy" alt="">
            </div>

            <div class="pt-8 text-center">
                <a href="{{ route('sellers.shop', $seller->username) }}">
                    <h3 class="text-sm font-semibold text-[#191919] hover:text-[#F85606] transition-colors duration-100 flex items-center justify-center gap-1">
                        {{ $seller->business_name }}
                        <i class="fa-solid fa-circle-check text-blue-500 text-[10px]"></i>
                    </h3>
                </a>

                @if ($seller->division)
                    <p class="text-[10px] text-[#767676] mt-0.5">
                        {{ $seller->district->name ?? '' }}{{ $seller->district && $seller->division ? ' | ' : '' }}{{ $seller->division->name ?? '' }}
                    </p>
                @endif

                <div class="flex items-center justify-center gap-3 mt-2 text-[10px]">
                    <div>
                        <span class="block font-semibold text-[#191919]">{{ $seller->total_followers }}</span>
                        <span class="text-[#767676]">Followers</span>
                    </div>
                    <div class="w-px h-4 bg-[#E5E5E5]"></div>
                    <div>
                        <div class="flex items-center gap-0.5 text-yellow-400">
                            <span class="font-semibold text-[#191919]">{{ number_format($seller->rating, 1) }}</span>
                            <i class="fa-solid fa-star text-[10px]"></i>
                        </div>
                        <span class="text-[#767676]">Rating</span>
                    </div>
                </div>

                <button onclick="toggleFollow(this)"
                    class="mt-2.5 w-full bg-[#F85606] hover:bg-[#C43D00] text-white text-xs font-medium py-1.5 rounded-sm transition-colors duration-100 active:scale-95 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i> Follow
                </button>
            </div>
        </div>

        <div class="border-t border-[#E5E5E5] px-3 py-2 flex items-center justify-between text-[10px] text-[#767676] bg-[#FAFAFA]">
            <span class="flex items-center gap-1">
                <i class="fa-solid fa-box"></i> {{ $seller->products_count }}+ Products
            </span>
            <a href="{{ route('sellers.shop', $seller->username) }}"
               class="font-medium text-[#191919] hover:text-[#F85606] flex items-center gap-1 transition-colors duration-100">
                Visit <i class="fa-solid fa-arrow-right text-[9px]"></i>
            </a>
        </div>
    </div>
@endforeach
