<?php
$user = auth()->user();
$seller = seller();
?>
@foreach ($reviews as $review)
    <div class="review-item space-y-2 py-6">
        <div class="flex items-center gap-3">
            <div class="user-avatar w-12 h-12 rounded-full overflow-hidden">
                <img src="{{ $review->user->avatar }}" alt="{{ $review->user->username }}" />
            </div>
            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <h3 class="font-medium">{{ $review->user->username }}</h3>
                <span class="flex items-center gap-2 text-gray-400">
                    on {{ optional($review->created_at)->format('M d, Y') ?? '' }}

                </span>
            </div>
        </div>

        @php
            $rating = $review->rating;
            $fullStars = floor($rating);
            $halfStar = $rating - $fullStars >= 0.5;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        @endphp

        <div class="rating flex flex-wrap items-center gap-3">
            <div class="flex flex-nowrap gap-1 text-theme-dark text-xs md:text-sm">
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fa-solid fa-star text-yellow-400"></i>
                @endfor

                @if ($halfStar)
                    <i class="fa-solid fa-star-half-stroke text-yellow-400"></i>
                @endif

                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="fa-solid fa-star text-gray-400"></i>
                @endfor
            </div>
        </div>

        @if ($review->images->isNotEmpty())
            <div class="flex product-images gap-2 md:gap-3 py-2">
                @foreach ($review->images as $image)
                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                        <img src="{{ storage_url($image->image) }}" alt="" class="w-full h-full object-cover" />
                    </div>
                @endforeach
            </div>
        @endif

        <p class="product-feedback">
            {{ $review->description }}
        </p>
    </div>
@endforeach
