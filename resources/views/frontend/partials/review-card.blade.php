@foreach ($reviews as $review)
    <!-- review 1 -->
    <div class="space-y-2 review-item">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 overflow-hidden rounded-full user-avatar">
                <img src="{{ storage_url($review->user->image) }}" alt="Alan Walker" />
            </div>
            <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                <h3 class="font-medium">{{ $review->user->fullname }}</h3>
                <span class="flex gap-2 text-gray-400">
                    In
                    <span class="w-auto h-4 lg:h-6">
                        <img class="object-contain w-auto h-full" src="{{ asset('assets/frontend/images/us-flag.png') }}"
                            alt="Flag of USA" />
                    </span>
                    on {{ $review->created_at->format('M d, Y') }}
                </span>

            </div>
        </div>
        <!-- Rating -->
        <div class="flex flex-wrap items-center gap-3 rating">
            <div class="flex gap-1 text-xs flex-nowrap text-theme-dark md:text-sm">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $review->rating)
                        <i class="fa-solid fa-star text-yellow-400"></i>
                    @else
                        <i class="fa-regular fa-star text-gray-300"></i>
                    @endif
                @endfor
            </div>
            <span class="text-lg font-medium text-davy-gray sm:text-xl">{{ number_format($review->rating, 1) }}</span>
        </div>
        {{-- <h6 class="product-colour">Purchased : Black</h6> --}}
        <p class="w-10/12 product-feedback sm:w-3/5 md:w-4/5 xl:w-3/5">
            {!! $review->review_text !!}
        </p>
        <div
            class="flex items-center justify-center w-10/12 text-xs text-black xsm:text-sm lg:text-base xl:text-lg sm:w-3/5 md:w-4/5 xl:w-3/5">
            <div class="flex items-start gap-3 divide-x divide-black">
                <button class="flex items-center gap-2 hover:text-primary eq">
                    <svg class="w-5 h-5" width="26" height="32" viewBox="0 0 26 32" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.7299 11.2163H21.6028C22.3648 11.2163 23.0955 11.5156 23.6343 12.0485C24.1731 12.5814 24.4758 13.3041 24.4758 14.0577V27.6963C24.4758 28.4499 24.1731 29.1726 23.6343 29.7054C23.0955 30.2383 22.3648 30.5377 21.6028 30.5377H4.36514C3.60318 30.5377 2.87244 30.2383 2.33366 29.7054C1.79487 29.1726 1.49219 28.4499 1.49219 27.6963V14.0577C1.49219 13.3041 1.79487 12.5814 2.33366 12.0485C2.87244 11.5156 3.60318 11.2163 4.36514 11.2163H7.23809M18.7299 6.67006L12.984 0.987305M12.984 0.987305L7.23809 6.67006M12.984 0.987305V20.3797"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Share
                </button>
                <button class="flex items-center gap-2 pl-2 hover:text-butterfly-blue eq">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Helpful (1)
                </button>
            </div>
            <button class="ml-auto text-xl md:text-2xl lg:text-3xl" id="alan-walker-btn"
                data-dropdown-toggle="alan-walker-comment-dropdown" type="button">
                <i class="fa-solid fa-ellipsis"></i>
            </button>

            <!-- Dropdown menu -->
            <div id="alan-walker-comment-dropdown"
                class="z-30 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-38 md:w-44">
                <div class="py-2 text-sm text-gray-700" aria-labelledby="alan-walker-btn">
                    <button class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                        Not Helpful
                    </button>

                    <button class="block w-full px-4 py-2 text-left hover:bg-gray-100 text-persian-red">
                        Report Abuse
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
