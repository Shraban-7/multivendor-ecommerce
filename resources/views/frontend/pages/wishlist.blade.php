@extends('frontend.layouts.app')
@section('title', 'Wishlist')

@section('dashboard')
    <div class="space-y-6">
        <div class="bg-white rounded-sm border border-[#E5E5E5]">
            <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                <h2 class="text-base font-semibold text-[#191919]">Wishlist</h2>
            </div>

            <div class="hidden md:grid md:grid-cols-[2fr_1fr_80px] gap-4 px-5 py-3 bg-[#F5F5F5] text-xs font-semibold text-[#767676] uppercase tracking-wider border-b border-[#E5E5E5] {{ $wishlists->isEmpty() ? 'hidden' : '' }}" id="wishlist-header">
                <span>Product</span>
                <span>Stock</span>
                <span></span>
            </div>

            <div class="divide-y divide-[#E5E5E5] {{ $wishlists->isEmpty() ? 'hidden' : '' }}" id="wishlist-items">
                @foreach ($wishlists as $wishlist)
                    @php $stock = $wishlist->product->stock_in - $wishlist->product->stock_out; @endphp
                    <div class="grid md:grid-cols-[2fr_1fr_80px] gap-4 px-5 py-4 items-center">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-16 h-16 shrink-0 rounded-sm overflow-hidden border border-[#E5E5E5]">
                                <img src="{{ storage_url($wishlist->product->thumbnail) }}" alt=""
                                    class="w-full h-full object-cover">
                            </div>
                            <p class="text-sm text-[#191919] truncate">{{ $wishlist->product->name }}</p>
                        </div>
                        <div class="text-sm font-medium {{ $stock > 0 ? 'text-green-600' : 'text-[#F85606]' }}">
                            {{ $stock > 0 ? 'In Stock' : 'Out of Stock' }}
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($stock > 0)
                                <a href="{{ route('products.details', $wishlist->product->slug) }}"
                                    class="flex items-center justify-center w-8 h-8 bg-[#F85606] text-white rounded-sm hover:bg-[#E04D05] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                    </svg>
                                </a>
                            @endif
                            <button type="button" class="wishlistRemoveBtn flex items-center justify-center w-8 h-8 text-[#A0A0A0] hover:text-red-500 hover:bg-red-50 rounded-sm transition-colors"
                                data-id="{{ $wishlist->id }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-col items-center justify-center py-16 text-center {{ $wishlists->isNotEmpty() ? 'hidden' : '' }}" id="wishlist-empty">
                <div class="w-16 h-16 mb-4 bg-[#F5F5F5] rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#A0A0A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-[#191919]">Your wishlist is empty</h3>
                <p class="text-sm text-[#767676] mt-1">Save your favorite items here.</p>
                <a href="{{ route('home') }}"
                    class="mt-4 inline-flex items-center gap-2 px-5 py-2 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                    Browse Products
                </a>
            </div>
        </div>
    </div>
@endsection
