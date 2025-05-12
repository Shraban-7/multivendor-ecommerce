@foreach ($reviews as $review)
    <!-- review 1 -->
    <div class="review-item space-y-2 py-6">
        <div class="flex items-center gap-3">
            <div class="user-avatar w-12 h-12 rounded-full overflow-hidden">
                <img src="{{ storage_url($review->user->image) }}" alt="{{ $review->user->username }}" />
            </div>
            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <h3 class="font-medium">{{ $review->user->username }}</h3>
                <span class="flex items-center gap-2 text-gray-400">
                    In
                    <span class="h-4 lg:h-6 w-auto"><img class="w-auto h-full object-contain"
                            src="{{ asset('assets/frontend/images/us-flag.png') }}" alt="Flag of USA" /></span>
                    on {{ optional($review->created_at)->format('M d, Y') ?? '' }}

                </span>
            </div>
        </div>
        <!-- Rating -->
        @php
            $rating = $review->rating;
            $fullStars = floor($rating);
            $halfStar = $rating - $fullStars >= 0.5;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        @endphp

        <div class="rating flex flex-wrap items-center gap-3">
            <div class="flex flex-nowrap gap-1 text-theme-dark text-xs md:text-sm">
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fa-solid fa-star"></i>
                @endfor

                @if ($halfStar)
                    <i class="fa-solid fa-star-half-stroke"></i>
                @endif

                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="fa-regular fa-star"></i>
                @endfor
            </div>
            <span class="text-davy-gray text-lg sm:text-xl font-medium">{{ number_format($rating, 1) }}</span>
        </div>

        <!-- colour -->
        {{-- <h6 class="product-colour">Purchased : </h6> --}}
        <!-- product images -->
        @if ($review->images->isNotEmpty())
            <div class="flex product-images gap-2 md:gap-3 py-2">
                @foreach ($review->images as $image)
                    <div class="img-wrap w-1/3 h-28 sm:h-32 md:h-24 lg:h-36 overflow-hidden rounded-xl">
                        <img src="{{ storage_url($image->image) }}" alt="" class="w-full h-full object-cover" />
                    </div>
                @endforeach
            </div>
        @endif
        <!-- comment -->
        <p class="product-feedback">
            {{ $review->review_text }}
        </p>

        <div class="flex justify-center items-center text-black text-xs xsm:text-sm lg:text-base xl:text-lg">
            <div class="flex flex-row items-start divide-x divide-solid divide-black gap-x-3 pt-2">
                <!-- Share Button -->
                <button class="flex items-center gap-2 hover:text-primary pr-3">
                    <svg class="w-5 h-5" width="26" height="32" viewBox="0 0 26 32" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.7299 11.2163H21.6028C22.3648 11.2163 23.0955 11.5156 23.6343 12.0485C24.1731 12.5814 24.4758 13.3041 24.4758 14.0577V27.6963C24.4758 28.4499 24.1731 29.1726 23.6343 29.7054C23.0955 30.2383 22.3648 30.5377 21.6028 30.5377H4.36514C3.60318 30.5377 2.87244 30.2383 2.33366 29.7054C1.79487 29.1726 1.49219 28.4499 1.49219 27.6963V14.0577C1.49219 13.3041 1.79487 12.5814 2.33366 12.0485C2.87244 11.5156 3.60318 11.2163 4.36514 11.2163H7.23809M18.7299 6.67006L12.984 0.987305M12.984 0.987305L7.23809 6.67006M12.984 0.987305V20.3797"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Share
                </button>

                <!-- Helpful Button -->
                <button class="flex items-center gap-2 hover:text-butterfly-blue pl-0 helpful-btn"
                    data-review-id="{{ $review->id }}" data-url="{{ route('sellers.reviews.helpful', $review->id) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Helpful (<span class="helpful-count">{{ $review->helpful_count }}</span>)
                </button>
            </div>

            <button class="ml-auto text-xl md:text-2xl lg:text-3xl" id="btn-{{ $review->id }}"
                data-dropdown-toggle="comment-dropdown-{{ $review->id }}" type="button">
                <i class="fa-solid fa-ellipsis"></i>
            </button>  

            <!-- Dropdown menu -->
            <div id="comment-dropdown-{{ $review->id }}"
                class="z-30 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-38 md:w-44">
                <div class="py-2 text-sm text-gray-700" aria-labelledby="alan-walker-btn">
                    <button class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                        Not Helpful
                    </button>

                    @php
                        $seller = App\Models\Seller::where('id', auth('seller')->id())->first();
                        $user = App\Models\User::where('id', auth()->id())->first();

                        if ($seller) {
                            $existReport = App\Models\ReportReview::where('seller_id', $seller->id)
                                ->where('review_id', $review->id)
                                ->first();
                        } elseif ($user) {
                            $existReport = App\Models\ReportReview::where('user_id', $user->id)
                                ->where('review_id', $review->id)
                                ->first();
                        }
                    @endphp

                    @if ($existReport)
                    @else
                        <button
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-persian-red report-abuse-btn"
                            data-review-id="{{ $review->id }}" data-url="{{ route('sellers.reviews.report') }}">
                            Report Abuse
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endforeach
