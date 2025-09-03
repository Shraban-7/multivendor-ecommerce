<section class="py-10">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">Top Sellers</h2>
            <a href="{{ route('sellers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                View All →
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($sellers as $seller)
                <article class="border border-gray-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                    <div class="relative mx-auto mb-5 h-28 w-28">
                        <div class="flex h-full w-full items-center justify-center rounded-full bg-gray-50 shadow">
                            <img src="{{ storage_url($seller->image) }}" alt="{{ $seller->name }}"
                                class="w-20 h-20 mx-auto object-cover mb-4">
                        </div>
                    </div>

                    <h3 class="mb-3 text-center text-sm font-semibold text-gray-900">{{ $seller->name }}</h3>

                    <div class="mb-6 flex items-center justify-center gap-1">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($seller->avg_rating))
                                    <i class="fas fa-star text-amber-400"></i>
                                @elseif ($i == ceil($seller->avg_rating) && $seller->avg_rating - floor($seller->avg_rating) >= 0.5)
                                    <i class="fas fa-star-half-alt text-amber-400"></i>
                                @else
                                    <i class="far fa-star text-gray-300"></i>
                                @endif
                            @endfor

                            <span class="ml-2 text-xs text-gray-500">
                                ({{ $seller->reviews_count }} reviews)
                            </span>

                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <button type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                        <a href="{{ route('sellers.shop', $seller->username) }}"
                            class="px-5 py-2 text-sm font-semibold text-gray-500 hover:text-black/90">VISIT
                            STORE</a>
                    </div>
                </article>
            @endforeach

        </div>
    </div>
</section>
