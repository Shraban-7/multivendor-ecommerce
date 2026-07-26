@extends('frontend.layouts.app')

@section('title', 'Wishlist')

@section('dashboard')
    <main class="wishlist-page">
        <section class="container py-8">
            <div class="border border-[#E5E5E5] rounded-sm">
                <h1 class="text-base font-semibold px-6 py-4 text-[#191919]">
                    Wishlist
                </h1>

                <div
                    class="hidden md:grid md:grid-cols-[2fr_1fr_1fr_1fr] gap-4 bg-[#F5F5F5] border-b border-[#E5E5E5] px-6 py-3 text-xs font-semibold text-[#767676] tracking-wider">
                    <h4>PRODUCTS</h4>
                    <h4>PRICE</h4>
                    <h4>STOCK STATUS</h4>
                    <h4>ACTIONS</h4>
                </div>

                <!-- Product Items -->
                <div class="divide-y divide-[#E5E5E5] text-sm">
                    @foreach ($wishlists as $wishlist)
                        <div class="grid md:grid-cols-[2fr_1fr_1fr_1fr] gap-4 px-6 py-4 items-center">
                            <!-- Product Info -->
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 flex items-center justify-center">
                                    <img src="{{ storage_url($wishlist->product->thumbnail) }}" alt="Product Image"
                                        class="object-contain w-full h-full" />
                                </div>
                                <p class="text-sm text-gray-800 line-clamp-2">{{ $wishlist->product->name }}</p>
                            </div>

                            <!-- Price Info -->
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-gray-400 line-through">{{ money($wishlist->product->price) }}</span>
                                <span class="font-semibold">{{ money($wishlist->product->compare_price) }}</span>
                            </div>

                            <!-- Stock Info -->
                            @php
                                $stock = $wishlist->product->stock_in - $wishlist->product->stock_out;
                            @endphp
                            <div class="{{ $stock > 0 ? 'text-green-600' : 'text-[#F85606]' }} font-medium">
                                {{ $stock > 0 ? 'IN STOCK' : 'STOCK OUT' }}
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="quantity" class="qtyInputValue"
                                    id="qtyInput{{ $wishlist->product->id }}">
                                @if ($stock > 0)
                                    {{-- <button type="button"
                                        class="cartBtn px-3 py-2 text-xs sm:text-sm bg-orange-500 text-white rounded hover:bg-orange-600"
                                        data-id="{{ $wishlist->product->id }}" data-wishlist-id="{{ $wishlist->id }}">
                                        ADD TO CART
                                    </button> --}}
                                    <a href="{{ route('products.details', $wishlist->product->slug) }}"
                                        class="bg-[#F85606] hover:bg-[#C43D00] text-white py-2 px-3 rounded text-sm font-medium eq flex items-center justify-center gap-1">
                                        <i class="fas fa-shopping-cart text-xs"></i> 
                                    </a>
                                @endif
                                <button type="button" class="wishlistRemoveBtn text-gray-400 hover:text-gray-600"
                                    data-id="{{ $wishlist->id }}">
                                    <i class="fas fa-x"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Track Order Main Section Ended -->
    </main>
@endsection
