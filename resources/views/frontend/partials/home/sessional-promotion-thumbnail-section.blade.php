<section class="thumbnail-gallery">
            <div class="container grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-5 md:gap-4">
                @if ($special_category)
                    @foreach ($special_category->banners as $key => $banner)
                        @php
                            $gridClass = match ($key) {
                                0 => 'lg:col-span-2 lg:row-span-2 md:h-[33rem] h-96',
                                1 => 'lg:col-span-2 lg:h-[33rem] flex flex-col gap-4',

                                4 => 'lg:row-span-2 lg:h-[33rem] md:col-span-2 lg:col-span-1 h-96',
                                default => '',
                            };
                        @endphp

                        @if ($key === 0)
                            <!-- Layout for the first category (big single banner) -->
                            <div class="relative {{ $gridClass }}">
                                <div class="relative h-full overflow-hidden group rounded-xl">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif($key === 1)
                            <!-- Layout for the second category (split into two rows) -->
                            <div class="relative {{ $gridClass }}">
                                <!-- Top row (single banner) -->
                                <div class="relative overflow-hidden group rounded-xl h-1/2">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                        </a>
                                    </div>
                                </div>

                                <!-- Bottom row (grid of two banners) -->
                                <div class="grid grid-cols-2 gap-2 h-1/2">
                                    @if (isset($special_category->banners[$key + 1]))
                                        <div class="relative h-full overflow-hidden group rounded-xl">
                                            <div class="w-full h-full">
                                                <a href="#">
                                                    <img src="{{ asset('assets/' . $special_category->banners[$key + 1]->image) }}"
                                                        alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($special_category->banners[$key + 2]))
                                        <div class="relative h-full overflow-hidden group rounded-xl">
                                            <div class="w-full h-full">
                                                <a href="#">
                                                    <img src="{{ asset('assets/' . $special_category->banners[$key + 2]->image) }}"
                                                        alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($key === 4)
                            <!-- Layout for the third category (tall single banner) -->
                            <div class="relative {{ $gridClass }}">
                                <div class="relative h-full overflow-hidden group rounded-xl">
                                    <div class="w-full h-full">
                                        <a href="#">
                                            <img src="{{ asset('assets/' . $banner->image) }}"
                                                alt="{{ $category->name }}" class="object-cover w-full h-full" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </section>
