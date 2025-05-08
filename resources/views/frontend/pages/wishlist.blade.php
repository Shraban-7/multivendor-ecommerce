@extends('frontend.layouts.app')

@section('title', 'Wishlist')

@section('content')
    <main class="wishlist-page">
        <!-- Promotional Header Starts -->
        <section>
            <a href="#" class="block promo-header bg-light-yellow text-white py-3 sm:py-4">
                <div class="container flex flex-wrap justify-center xsm:justify-start items-center gap-x-2">
                    <i class="fa-solid fa-truck-fast text-lg"></i>
                    <h3 class="text-sm">Free Shipping Special For You</h3>
                    <p class="text-xs ml-2 xsm:ml-3">Limited Offer</p>
                </div>
            </a>
        </section>
        <!-- Promotional Header Ended -->

        <!-- Page Breadcrumb -->
        <section class="page-breadcrumb-links bg-jet-gray/10 py-4 md:py-6">
            <nav class="flex container" aria-label="Breadcrumb">
                <ol class="inline-flex flex-wrap items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm text-davy-gray hover:text-primary eq">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            Account
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-davy-gray mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm text-butterfly-blue md:ms-2">Track Order</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </section>

        <!-- Track Order Main Section Starts -->
        <section class="track-order-section container py-8">
            <div class="border border-jet-gray/30 rounded-lg">
                <h1 class="text-lg font-medium px-6 py-4 text-theme-dark">
                    Wishlist
                </h1>

                <!-- Table Header -->
                <div
                    class="hidden md:grid md:grid-cols-[2fr_1fr_1fr_1fr] gap-4 bg-jet-gray/10 border-b border-jet-gray/30 px-6 py-3 text-sm font-medium text-davy-gray">
                    <h4>PRODUCTS</h4>
                    <h4>PRICE</h4>
                    <h4>STOCK STATUS</h4>
                    <h4>ACTIONS</h4>
                </div>

                <!-- Product Items -->
                <div class="divide-y divide-gray-200 text-sm rounded-b-lg shadow-sm">
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
                                    class="text-gray-400 line-through">{{ money($wishlist->product->selling_price) }}</span>
                                <span class="font-semibold">{{ money($wishlist->product->discounted_price) }}</span>
                            </div>

                            <!-- Stock Info -->
                            <div
                                class="{{ $wishlist->product->stock_in > 0 ? 'text-green-600' : 'text-orange-600' }} font-medium">
                                {{ $wishlist->product->stock_in > 0 ? 'IN STOCK' : 'STOCK OUT' }}
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="quantity" class="qtyInputValue"
                                    id="qtyInput{{ $wishlist->product->id }}">
                                <button type="button"
                                    class="cartBtn px-3 py-2 text-xs sm:text-sm bg-orange-500 text-white rounded hover:bg-orange-600"
                                    data-id="{{ $wishlist->product->id }}" data-wishlist-id="{{ $wishlist->id }}">
                                    ADD TO CART
                                </button>
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.wishlistRemoveBtn').on('click', function() {
                    var wishlistId = $(this).data('id');
                    var $row = $(this).closest('.grid');
                    const wishlistDeleteRoute = "{{ route('wishlist.delete', ['wishlist' => '__id__']) }}";
                    var url = wishlistDeleteRoute.replace('__id__', wishlistId);
                    if (!wishlistId) return;

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            wishlist: wishlistId
                        },
                        success: function(response) {
                            if (response.success) {
                                $row.fadeOut(300, function() {
                                    $(this).remove();
                                    toastr.success(response.message);
                                });
                            } else {
                                alert(response.message || 'Failed to remove item');
                            }
                        },
                        error: function() {
                            alert('Something went wrong. Please try again.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
