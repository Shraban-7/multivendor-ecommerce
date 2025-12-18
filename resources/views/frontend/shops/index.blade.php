@extends('frontend.layouts.app')
@section('title', 'Shops')

@section('content')

    <div class="container mx-auto px-4 pb-10">
        <div class="mb-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-2xl font-bold text-gray-900 text-center md:text-left">
                    Our <span class="text-primary-600">Sellers</span>
                </h2>
                <form method="GET" action="#" class="relative w-full md:w-auto max-w-xl rounded-full">
                    <input type="text" name="name" placeholder="Search for shop name..."
                        class="w-full md:w-96 pl-6 pr-16 py-3 rounded-full border-none ring-1 ring-gray-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all text-sm md:text-base">

                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary-600 hover:bg-primary-700 text-white w-10 h-10 flex items-center justify-center rounded-full transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
            @foreach ($sellers as $seller)
                <div
                    class="group bg-white rounded-xl shadow-card hover:shadow-card-hover transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 relative">
                    <div class="h-28 md:h-32 w-full relative overflow-hidden">
                        @if ($seller->cover_image)
                            <img src="{{ storage_url($seller->cover_image) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                        <span
                            class="absolute top-3 right-3 bg-white/90 text-primary-700 text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                            VERIFIED
                        </span>
                    </div>
                    <div class="px-4 pb-5 relative">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2">
                            <div
                                class="w-16 h-16 md:w-20 md:h-20 rounded-full border-[3px] border-white shadow-md bg-white p-0.5">
                                <img src="{{ storage_url($seller->business_logo) }}"
                                    class="w-full h-full rounded-full object-cover">
                            </div>
                        </div>

                        <div class="pt-10 md:pt-12 text-center">
                            <a href="{{ route('sellers.shop', $seller->username) }}">
                                <h3
                                    class="text-sm md:text-lg font-bold text-gray-900 flex justify-center items-center gap-1">
                                    {{ $seller->business_name }}
                                    <i class="fa-solid fa-circle-check text-blue-500 text-sm" title="Verified Seller"></i>
                                </h3>
                            </a>

                            @if ($seller->division)
                                <p class="text-[11px] text-gray-500 mb-3">
                                    {{ $seller->district->name ?? '' }} | {{ $seller->division->name ?? '' }}
                                </p>
                            @endif

                            <div class="flex justify-center items-center gap-4 mb-4 text-xs">
                                <div class="text-center">
                                    <span class="block font-bold text-gray-800">{{ $seller->total_followers }}</span>
                                    <span class="text-gray-400 text-[10px]">Followers</span>
                                </div>

                                <div class="w-px h-6 bg-gray-200"></div>

                                <div class="text-center">
                                    <div class="flex items-center justify-center gap-1 text-yellow-400">
                                        <span class="font-bold text-gray-800">{{ $seller->rating }}</span>
                                        <i class="fa-solid fa-star text-xs"></i>
                                    </div>
                                    <span class="text-gray-400 text-[10px]">Rating</span>
                                </div>
                            </div>
                            <button onclick="toggleFollow(this)"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-full text-xs md:text-sm transition active:scale-95 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-plus"></i> Follow
                            </button>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 border-t text-xs text-gray-500 flex justify-between items-center group-hover:bg-primary-50 transition">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-box"></i> {{ $seller->products_count }}+ Products
                        </span>

                        <a href="{{ route('sellers.shop', $seller->username) }}"
                            class="font-medium text-gray-800 hover:text-primary-600 flex items-center gap-1 transition">
                            Visit <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
