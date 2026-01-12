@extends('frontend.layouts.app')
@section('title', $seo->meta_title ?? $product['name'])

@push('meta')
    <link rel="canonical" href="{{ url()->current() }}">
    @if ($seo)
        @if ($seo->meta_description)
            <meta name="description" content="{{ $seo->meta_description }}">
        @endif
        @if ($seo->meta_keywords)
            <meta name="keywords" content="{{ $seo->meta_keywords }}">
        @endif
        @if ($seo->og_title)
            <meta property="og:title" content="{{ $seo->og_title }}">
        @endif
        @if ($seo->og_description)
            <meta property="og:description" content="{{ $seo->og_description }}">
        @endif
        @if ($seo->og_image)
            <meta property="og:image" content="{{ storage_url($seo->og_image) }}">
        @endif
        <meta property="og:type" content="product">
        @if ($seo->og_title)
            <meta name="twitter:title" content="{{ $seo->og_title }}">
        @endif
        @if ($seo->og_description)
            <meta name="twitter:description" content="{{ $seo->og_description }}">
        @endif
        @if ($seo->og_image)
            <meta name="twitter:image" content="{{ storage_url($seo->og_image) }}">
        @endif
        <meta name="twitter:card" content="summary_large_image">
    @endif
@endpush

@push('styles')
    <style>
        .thumbnailWrapper::-webkit-scrollbar {
            height: 3px;
        }

        .thumbnailWrapper::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 10px;
        }

        .thumbnailWrapper::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
@endpush

<?php
$settings = settings();
?>

@section('content')
    <main class="product-details-page">

        <section class="product-main-sec">
            <!-- Product Contents  -->
            <x-frontend.product-contents :product="$product" />

            <!-- Flowbite Tab System for Description & Reviews -->
            <div class="py-6 md:py-8">
                <!-- Tabs -->
                <div class="mb-3 border-b border-gray-200">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium" id="default-tab"
                        data-tabs-toggle="#default-tab-content" role="tablist">
                        <li class="me-4" role="presentation">
                            <button
                                class="inline-block px-3 py-2 border-b-2 border-transparent text-gray-600 hover:text-primary hover:border-primary transition"
                                id="description-tab" data-tabs-target="#description" type="button" role="tab"
                                aria-controls="description" aria-selected="true">
                                Product Description
                            </button>
                        </li>
                        <li class="me-4" role="presentation">
                            <button
                                class="inline-block px-3 py-2 border-b-2 border-transparent text-gray-600 hover:text-primary hover:border-primary transition"
                                id="reviews-tab" data-tabs-target="#reviews" type="button" role="tab"
                                aria-controls="reviews" aria-selected="false">
                                Reviews ({{ $product['total_reviews'] ?? 0 }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div id="default-tab-content">
                    <!-- Description -->
                    <div class="hidden" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="bg-white p-5 rounded-xl shadow-sm">
                            <div class="shop-decriptions w-full md:w-2/3 lg:w-1/2">
                                <!-- Description -->
                                <div class="mt-3">
                                    <h2 class="text-lg font-semibold mb-2">Description:</h2>
                                    <p class="text-gray-700">
                                        {!! $product['description'] !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Reviews -->
                    <div class="hidden" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Reviews List -->
                            <div class="order-2 md:order-1 lg:w-[55%] md:w-[50%] w-full">
                                <div class="bg-white p-5 rounded-xl shadow-sm">
                                    <h4 class="text-base font-semibold mb-3">Item Reviews</h4>
                                    <div class="space-y-4 reviews-wrapper">
                                        @include('frontend.partials.review-card', [
                                            'reviews' => $product['reviews'],
                                        ])
                                    </div>
                                </div>
                            </div>

                            <!-- Ratings Overview -->
                            <div class="order-1 md:order-2 lg:w-[45%] md:w-[50%] w-full">
                                <div class="bg-white p-5 rounded-xl shadow-sm">
                                    <div class="flex items-start gap-4 mb-3">
                                        <div class="font-[arial] space-y-1">
                                            <div class="text-3xl md:text-4xl text-primary font-bold">
                                                {{ $product['rating'] . '%' }}
                                            </div>
                                            @if ($product['total_reviews'] > 0)
                                                <div class="flex text-xl text-yellow-400">
                                                    @php $average = round($product['rating']); @endphp
                                                    {!! str_repeat('★', $average) . str_repeat('☆', 5 - $average) !!}
                                                </div>
                                            @else
                                                <div class="flex text-xl text-gray-400">
                                                    {!! str_repeat('☆', 5) !!}
                                                </div>
                                            @endif
                                            <div class="text-xs text-gray-500">
                                                (Positive reviews)
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rating Bars -->
                                    @php $total = $totalReviews ?: 1; @endphp
                                    <div class="space-y-2">
                                        @foreach ($ratings->sortDesc() as $star => $count)
                                            @php $percentage = round(($count / $total) * 100); @endphp
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm w-12 text-gray-600">{{ $star }}★</span>
                                                <div class="flex-1">
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-yellow-400 h-2 rounded-full"
                                                            style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500 w-10 text-right">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Average Rating -->
                                    @if ($totalReviews > 0)
                                        <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-gray-200">
                                            <div class="flex gap-1 text-sm">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($averageRating))
                                                        <i class="fa-solid fa-star text-yellow-400"></i>
                                                    @elseif ($i - $averageRating < 1)
                                                        <i class="fa-solid fa-star-half-stroke text-yellow-400"></i>
                                                    @else
                                                        <i class="fa-solid fa-star text-gray-300"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600">
                                                ({{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }})
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($product['reviews']->count() > 2)
                    <!-- Load More Button -->
                    <div class="pt-6 text-center load-more-btn" id="load-more-reviews" style="display: none;">
                        <button id="loadMoreReviews" data-offset="2" data-type="reviews" data-url="{{ request()->url() }}"
                            class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-full text-sm font-medium inline-flex gap-2 items-center shadow transition">
                            <span>Load More</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                    </div>
                @endif
            </div>
        </section>

        <!-- Explore Interest Section Start  -->
        <section class="explore-interest section-padding">
            <!-- Section Tittle -->
            <h2 class="mb-5 text-xl font-medium sm:text-2xl lg:text-3xl text-jet-gray md:mb-8 lg:mb-10">
                Similar Products
            </h2>

            <div id="product-wrapper"
                class="grid items-start grid-cols-2 gap-5 p-2 md:grid-cols-4 lg:grid-cols-5 xl:gap-8 lg:p-0">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>

            @if ($products->count() >= 8)
                <!-- Load More Btn -->
                <div class="mt-10 text-center load-more-btn">
                    <button data-page="1" data-type="products" data-url="{{ request()->url() }}" id="loadMoreProducts"
                        class="text-sm font-semibold text-primary-600 border border-primary-600 px-4 py-1.5 rounded-full hover:bg-primary-600 hover:text-white transition"
                        type="button">
                        <span>Load More</span>
                        <i class="text-sm fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            @endif
        </section>
        <!-- Explore Interest Section Ended  -->
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const $tabs = $('[data-tabs-target]');
            const $loadMoreSection = $('#load-more-reviews');

            const $descriptionTab = $('#description-tab');
            const $descriptionContent = $('#description');

            $descriptionTab.addClass('tab-active').removeClass('border-transparent');
            $descriptionContent.removeClass('hidden');

            $tabs.on('click', function(e) {
                e.preventDefault();

                $tabs.removeClass('tab-active').addClass('text-gray-500 border-transparent')
                    .attr('aria-selected', 'false');

                $('[role="tabpanel"]').addClass('hidden');

                $(this).addClass('tab-active').removeClass('text-gray-500 border-transparent')
                    .attr('aria-selected', 'true');

                const target = $(this).data('tabs-target');
                $(target).removeClass('hidden');

                if ($loadMoreSection.length) {
                    if (target === '#reviews') {
                        $loadMoreSection.show();
                    } else {
                        $loadMoreSection.hide();
                    }
                }
            });

            $('#loadMoreReviews').on('click', function() {
                const $button = $(this);
                let offset = parseInt($button.data('offset'));
                const url = $button.data('url');
                const type = $button.data('type');

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

            $('#loadMoreProducts').on('click', function() {
                const button = $(this);
                let page = parseInt(button.data('page')) + 1;
                const url = button.data('url');

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
                        if ($.trim(response) !== '') {
                            $('#product-wrapper').append(response);

                            button.data('page', page);
                            button.prop('disabled', false).html(
                                '<span>Load More</span> <i class="fa-solid fa-chevron-down text-sm"></i>'
                            );

                            const scriptTags = $(response).filter('script[data-quickview]');
                            scriptTags.each(function() {
                                const json = $(this).html();
                                try {
                                    const data = JSON.parse(json);
                                    window.quickViewData = window.quickViewData || {};
                                    window.quickViewData[data.id] = {
                                        product: data.product,
                                        defaultVariant: data.defaultVariant
                                    };
                                } catch (e) {
                                    console.error('Invalid quick view JSON format', e);
                                }
                            });

                            if (typeof initFlowbite === 'function') initFlowbite();
                            if (typeof initQuickViewModals === 'function')
                                initQuickViewModals();
                            if (typeof initProductSwipers === 'function') initProductSwipers();
                        } else {
                            button.hide();
                        }
                    },
                    error: function() {
                        button.prop('disabled', false).text('Load More');
                        showErrorToast('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
