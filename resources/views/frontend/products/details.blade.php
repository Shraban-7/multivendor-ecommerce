@extends('frontend.layouts.app')
@section('title', $seo?->meta_title ?? $product['name'])

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

<?php
$settings = settings();
?>

@section('content')
    <main class="product-details-page">

        <section class="product-main-sec">
            <!-- Product Contents  -->
            <x-frontend.product-contents :product="$product" />

            {{-- ============================================================ --}}
            {{-- TABS: Description & Reviews --}}
            {{-- ============================================================ --}}
            <div class="mt-6">
                {{-- Tab Navigation --}}
                <div class="border-b border-ds-border-default">
                    <ul class="flex gap-0" id="default-tab"
                        data-tabs-toggle="#default-tab-content" role="tablist">
                        <li role="presentation">
                            <button
                                class="ds-tab-btn"
                                id="description-tab" data-tabs-target="#description" type="button" role="tab"
                                aria-controls="description" aria-selected="true">
                                Product Description
                            </button>
                        </li>
                        <li role="presentation">
                            <button
                                class="ds-tab-btn"
                                id="reviews-tab" data-tabs-target="#reviews" type="button" role="tab"
                                aria-controls="reviews" aria-selected="false">
                                Reviews ({{ $product['total_reviews'] ?? 0 }})
                            </button>
                        </li>
                    </ul>
                </div>

                <div id="default-tab-content">
                    {{-- ============================================================ --}}
                    {{-- DESCRIPTION TAB --}}
                    {{-- ============================================================ --}}
                    <div class="hidden" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="bg-ds-surface-base border border-ds-border-default rounded-md p-5 mt-4">
                            <div class="w-full md:w-2/3 lg:w-1/2">
                                <div class="text-sm text-ds-text-secondary leading-relaxed space-y-3">
                                    {!! $product['description'] !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- REVIEWS TAB --}}
                    {{-- ============================================================ --}}
                    <div class="hidden" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">

                            {{-- Review List --}}
                            <div class="order-2 lg:order-1 lg:col-span-2">
                                <div class="bg-ds-surface-base border border-ds-border-default rounded-md p-5">
                                    <h4 class="text-sm font-semibold text-ds-text-primary mb-4">Item Reviews</h4>
                                    <div class="space-y-0 reviews-wrapper" id="reviews-wrapper">
                                        @include('frontend.partials.review-card', [
                                            'reviews' => $product['reviews'],
                                        ])
                                    </div>
                                </div>
                            </div>

                            {{-- Ratings Overview --}}
                            <div class="order-1 lg:order-2 lg:col-span-1">
                                <div class="bg-ds-surface-base border border-ds-border-default rounded-md p-5">
                                    {{-- Average Score --}}
                                    <div class="text-center pb-4 border-b border-ds-border-default">
                                        <div class="text-4xl font-bold text-brand mb-1">
                                            {{ $product['rating'] }}<span class="text-lg font-medium text-ds-text-tertiary">%</span>
                                        </div>
                                        @if ($product['total_reviews'] > 0)
                                            <div class="flex justify-center gap-0.5 text-ds-star my-2">
                                                @php $average = round($product['rating']); @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $average)
                                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @else
                                                        <svg class="w-5 h-5 fill-ds-border-default" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="text-xs text-ds-text-tertiary">
                                                (Positive reviews)
                                            </div>
                                        @else
                                            <div class="flex justify-center gap-0.5 text-ds-border-default my-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @endfor
                                            </div>
                                            <div class="text-xs text-ds-text-tertiary">No reviews yet</div>
                                        @endif
                                    </div>

                                    {{-- Rating Distribution Bars --}}
                                    @php $total = $totalReviews ?: 1; @endphp
                                    <div class="space-y-2 py-4">
                                        @foreach ($ratings->sortDesc() as $star => $count)
                                            @php $percentage = round(($count / $total) * 100); @endphp
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-ds-text-secondary w-8 text-right shrink-0">{{ $star }}★</span>
                                                <div class="flex-1 h-2 bg-ds-surface-muted rounded-full overflow-hidden">
                                                    <div class="ds-rating-bar-fill h-full bg-ds-star rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <span class="text-xs text-ds-text-tertiary w-8 text-right shrink-0">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Average Rating with Stars --}}
                                    @if ($totalReviews > 0)
                                        <div class="flex items-center gap-2 pt-4 border-t border-ds-border-default">
                                            <div class="flex gap-0.5">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($averageRating))
                                                        <svg class="w-4 h-4 text-ds-star fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @elseif ($i - $averageRating < 1)
                                                        <svg class="w-4 h-4 text-ds-star" viewBox="0 0 20 20">
                                                            <defs><linearGradient id="halfStar{{ $i }}"><stop offset="50%" stop-color="#FFA000"/><stop offset="50%" stop-color="#E5E5E5"/></linearGradient></defs>
                                                            <path fill="url(#halfStar{{ $i }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 fill-ds-border-default" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-xs text-ds-text-secondary">
                                                ({{ $totalReviews }} {{ Str::plural('Review', $totalReviews) }})
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Load More Reviews --}}
                @if ($product['reviews']->count() > 2)
                    <div class="pt-6 text-center load-more-btn" id="load-more-reviews" style="display: none;">
                        <button id="loadMoreReviews" data-offset="2" data-type="reviews" data-url="{{ request()->url() }}"
                            class="inline-flex items-center gap-2 px-5 py-2 border border-ds-border-default text-ds-text-secondary text-xs font-medium rounded-sm hover:bg-ds-surface-muted hover:text-ds-text-primary transition-colors duration-100"
                            aria-label="Load more reviews">
                            <span>Load More</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                @endif
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- SIMILAR PRODUCTS --}}
        {{-- ============================================================ --}}
        <section class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-brand">Similar Products</h2>
            </div>

            <div id="product-wrapper"
                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @include('frontend.partials.product-card-load', ['products' => $products])
            </div>

            @if ($products->count() >= 8)
                <div class="mt-6 text-center">
                    <button data-page="1" data-url="{{ request()->url() }}" id="loadMoreProducts"
                        class="inline-flex items-center gap-2 px-6 py-2 border border-brand text-brand text-xs font-semibold rounded-sm hover:bg-brand hover:text-white transition-colors duration-100"
                        type="button">
                        <span>Load More</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
            @endif
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const $tabs = $('[data-tabs-target]');
            const $loadMoreSection = $('#load-more-reviews');

            const $descriptionTab = $('#description-tab');
            const $descriptionContent = $('#description');

            $descriptionTab.addClass('tab-active');
            $descriptionContent.removeClass('hidden');

            $tabs.on('click', function(e) {
                e.preventDefault();

                $tabs.removeClass('tab-active')
                    .attr('aria-selected', 'false');

                $('[role="tabpanel"]').addClass('hidden');

                $(this).addClass('tab-active')
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
                    data: { page: page },
                    beforeSend: function() {
                        button.prop('disabled', true).html(
                            '<svg class="animate-spin h-4 w-4 text-brand" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
                        );
                    },
                    success: function(response) {
                        if ($.trim(response) !== '') {
                            $('#product-wrapper').append(response);
                            button.data('page', page);
                            button.prop('disabled', false).html(
                                '<span>Load More</span> <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>'
                            );
                        } else {
                            button.hide();
                        }
                    },
                    error: function() {
                        button.prop('disabled', false).html(
                            '<span>Load More</span> <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>'
                        );
                        showErrorToast('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
