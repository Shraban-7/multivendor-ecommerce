<?php
$user = auth()->user();
$seller = seller();
?>
@foreach ($reviews as $review)
    <div class="review-item py-5 {{ !$loop->last ? 'border-b border-ds-border-default' : '' }}">
        {{-- User Info --}}
        <div class="flex items-center gap-3">
            <div class="user-avatar w-9 h-9 rounded-full overflow-hidden bg-ds-surface-muted shrink-0">
                <img src="{{ $review->user->avatar }}" alt="{{ $review->user->username }}" class="w-full h-full object-cover" />
            </div>
            <div class="flex flex-col">
                <h3 class="text-sm font-medium text-ds-text-primary">{{ $review->user->username }}</h3>
                <span class="text-[11px] text-ds-text-tertiary">
                    {{ optional($review->created_at)->format('M d, Y') ?? '' }}
                </span>
            </div>
        </div>

        {{-- Star Rating --}}
        @php
            $rating = $review->rating;
            $fullStars = floor($rating);
            $halfStar = $rating - $fullStars >= 0.5;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        @endphp

        <div class="rating flex items-center gap-0.5 mt-2">
            @for ($i = 0; $i < $fullStars; $i++)
                <svg class="w-3.5 h-3.5 text-ds-star fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor

            @if ($halfStar)
                <svg class="w-3.5 h-3.5 text-ds-star" viewBox="0 0 20 20">
                    <defs><linearGradient id="reviewHalf{{ $review->id }}"><stop offset="50%" stop-color="#FFA000"/><stop offset="50%" stop-color="#E5E5E5"/></linearGradient></defs>
                    <path fill="url(#reviewHalf{{ $review->id }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endif

            @for ($i = 0; $i < $emptyStars; $i++)
                <svg class="w-3.5 h-3.5 fill-ds-border-default" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>

        {{-- Review Images --}}
        @if ($review->images && $review->images->isNotEmpty())
            <div class="product-images flex gap-2 mt-3">
                @foreach ($review->images as $image)
                    <div class="img-wrap w-20 h-20 sm:w-24 sm:h-24 overflow-hidden rounded-sm border border-ds-border-default">
                        <img src="{{ storage_url($image->image) }}" alt="Review image" class="w-full h-full object-cover" loading="lazy" />
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Review Text --}}
        <p class="product-feedback text-sm text-ds-text-secondary leading-relaxed mt-3">
            {{ $review->description }}
        </p>
    </div>
@endforeach
