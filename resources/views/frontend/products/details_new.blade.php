@extends('frontend.layouts.app')
@section('title', $product['name'])

@section('content')
@php

$settings = settings();

$publicProduct = [
    'name' => $product['name'],
    'price' => $product['price'],
    'compare_price' => $product['compare_price'],
    'sku' => $product['sku'],
    'stock' => $product['stock'],
    'slider' => $product['slider'],
    'variants' => $product['variants'],
];

$showProductDiscount = $showVariantDiscount = 1;
@endphp
<main class="product-details-page">
    <!-- Breadcrumb -->
    <section class="container py-4">
        <nav class="text-sm" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-gray-500">
                <li>
                    <a href="/" class="hover:text-primary transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-xs mx-2"></i>
                    <a href="#" class="hover:text-primary transition-colors">{{ $product['category'] }}</a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-xs mx-2"></i>
                    <span class="text-gray-400">{{ $product['subcategory'] }}</span>
                </li>
            </ol>
        </nav>
    </section>

    <!-- Product Main Section -->
    <section class="container bg-white rounded-lg shadow-sm p-4 md:p-6 mb-8">
        <!-- Product Contents -->
        <x-frontend.product-contents-new :product="$product" />

        <!-- Divider -->
        <div class="border-t border-gray-200 my-6"></div>

        <!-- Ratings & Reviews Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ratings Overview -->
            <div class="lg:col-span-2">
                <h3 class="text-xl font-semibold mb-4">Customer Reviews</h3>

                <div class="flex flex-col md:flex-row items-start gap-6 mb-6">
                    <!-- Overall Rating -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg min-w-[180px]">
                        <div class="text-5xl font-bold text-blue-600 mb-1">
                            {{ $product['rating'] }}<span class="text-2xl">%</span>
                        </div>
                        <div class="flex justify-center text-yellow-400 text-xl mb-2">
                            @php $average = round($product['rating']/20); @endphp
                            {!! str_repeat('★', $average) . str_repeat('☆', 5 - $average) !!}
                        </div>
                        <div class="text-sm text-gray-500">Positive reviews</div>
                    </div>

                    <!-- Rating Breakdown -->
                    <div class="flex-1 w-full space-y-3">
                        @foreach ($ratings->sortDesc() as $star => $count)
                        @php $percentage = round(($count / ($totalReviews ?: 1)) * 100); @endphp
                        <div class="flex items-center">
                            <span class="w-10 text-sm text-gray-600">{{ $star }} star</span>
                            <div class="flex-1 mx-2 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="w-10 text-sm text-right text-gray-600">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Review Filter/Sort -->
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-medium">{{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }}</h4>
                    <div class="relative">
                        <select class="appearance-none bg-gray-100 border-0 rounded pl-3 pr-8 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option>Most Recent</option>
                            <option>Highest Rated</option>
                            <option>Lowest Rated</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-xs text-gray-500"></i>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="space-y-6">
                    @include('frontend.partials.review-card', ['reviews' => $product['reviews']])
                </div>

                @if ($product['reviews']->count() > 2)
                <div class="text-center mt-8">
                    <button id="loadMoreReviews" class="bg-white border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Load More Reviews
                    </button>
                </div>
                @endif
            </div>

            <!-- Seller & Commitments -->
            <div class="space-y-6">
                <!-- Seller Info -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ storage_url($seller['shop_logo']) }}" alt="{{ $seller['shop_name'] }}" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-semibold">{{ $seller['shop_name'] }}</h4>
                            <div class="flex items-center text-sm text-gray-500">
                                <span>{{ $seller['rating'] }}</span>
                                <i class="fas fa-star text-yellow-400 ml-1"></i>
                                <span class="mx-2">•</span>
                                <span>{{ $seller['total_followers'] }}+ followers</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-store mr-1"></i> Follow
                        </button>
                        <a href="{{ route('sellers.shop', $seller['username']) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md text-sm font-medium text-center transition-colors">
                            Shop All ({{ count($products) }})
                        </a>
                    </div>
                </div>

                <!-- Commitments -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h4 class="font-semibold mb-4">Our Commitments</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Security -->
                        <div class="flex items-start gap-3">
                            <div class="bg-blue-100 p-2 rounded-full">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                            </div>
                            <div>
                                <h5 class="font-medium text-sm">Security & Privacy</h5>
                                <p class="text-gray-500 text-sm">Safe payments and secure privacy</p>
                            </div>
                        </div>
                        <!-- Delivery -->
                        <div class="flex items-start gap-3">
                            <div class="bg-green-100 p-2 rounded-full">
                                <i class="fas fa-truck text-green-600"></i>
                            </div>
                            <div>
                                <h5 class="font-medium text-sm">Delivery Guarantee</h5>
                                <p class="text-gray-500 text-sm">Return if damaged or 15-day refund</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h4 class="font-semibold mb-3">Description</h4>
                    <p class="text-gray-600 text-sm">{{ $product['description'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recommended Products -->
    <section class="container mb-10">
        <h3 class="text-xl font-semibold mb-6">You May Also Like</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @include('frontend.partials.product-card-load', ['products' => $products])
        </div>

        @if ($products->count() >= 8)
        <div class="text-center mt-8">
            <button id="loadMoreProducts" class="bg-white border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Load More Products
            </button>
        </div>
        @endif
    </section>
</main>


@push('scripts')
<script>
    $(document).ready(function() {
        $('#loadMoreReviews').on('click', function() {
            var $button = $(this);
            var offset = parseInt($button.data('offset'));
            var url = $button.data('url');
            var type = $button.data('type');

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    offset: offset,
                    type: type
                },
                success: function(response) {
                    if ($.trim(response) === '') {

                        $button.hide();
                    } else {
                        $('#reviews-wrapper').append(response);
                        $button.data('offset', offset + 2);
                    }
                },
                error: function() {
                    console.error('Failed to load more reviews.');
                }
            });
        });
    });
</script>

<script>
    $('#loadMoreProducts').on('click', function() {
        let button = $(this);
        let page = parseInt(button.data('page')) + 1;
        let url = button.data('url');

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                page: page,
                type: 'products'
            },
            beforeSend: function() {
                button.prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Loading...'
                );
            },
            success: function(response) {
                if (response.trim() !== '') {
                    $('#product-wrapper').append(response);

                    button.data('page', page);
                    button.prop('disabled', false).html(
                        '<span>Load More</span> <i class="fa-solid fa-chevron-down text-sm"></i>'
                    );

                    if (typeof initFlowbite === 'function') {
                        initFlowbite();
                    }

                    if (typeof initProductSwipers === 'function') {
                        initProductSwipers();
                    }

                } else {
                    button.hide();
                }
            },
            error: function() {
                button.prop('disabled', false).text('Load More');
                alert('Something went wrong. Please try again.');
            }
        });
    });
</script>
@endpush
@endsection