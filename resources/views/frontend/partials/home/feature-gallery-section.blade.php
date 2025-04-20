<section class="feature-gallery">
            <div class="container grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-5 md:gap-4">
                <!-- col 1 -->
                <div class="relative lg:col-span-2 lg:row-span-2 lg:h-[33rem] h-96">
                    <div class="relative h-full overflow-hidden group rounded-xl">
                        <div class="w-full h-full">
                            <!-- gallery image -->
                            <img src="{{ storage_url($gallery_feature_pro_one->image) }}"
                                alt="Slow cooker with ingredients" class="object-cover w-full h-full" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 flex flex-col items-start justify-center w-full h-full gap-2 p-6 text-white sm:gap-5">
                            <p class="text-sm font-medium md:text-lg lg:text-xl">
                                {{ $gallery_feature_pro_one->subtitle }}
                            </p>
                            <h2 class="text-2xl md:text-4xl xl:text-5xl font-semibold !leading-[1.2]">
                                {{ $gallery_feature_pro_one->title }}
                            </h2>
                            <a href="{{ $gallery_feature_pro_one->button_link }}"
                                class="px-6 py-2 text-sm font-medium text-black bg-white rounded-full sm:text-base md:px-8 hover:bg-primary hover:text-white eq">
                                {{ $gallery_feature_pro_one->button_text }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- col 2 -->
                <div class="relative lg:col-span-2 lg:h-[33rem] overflow-hidden flex flex-col gap-4">
                    <!-- row 1 -->
                    <div class="relative overflow-hidden group rounded-xl h-1/2">
                        <!-- gallery image -->
                        <div class="w-full h-full">
                            <img src="{{ storage_url($gallery_feature_pro_two->image) }}"
                                alt="Coats and jackets collection" class="object-cover w-full h-full" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 flex flex-col items-start w-full h-full gap-2 p-6 text-white sm:gap-5">
                            <p class="text-sm font-medium md:text-lg lg:text-xl">
                                {{ $gallery_feature_pro_two->subtitle }}
                            </p>
                            <h2 class="text-2xl md:text-3xl xl:text-4xl font-semibold !leading-[1.2]">
                               {{ $gallery_feature_pro_two->title }}
                            </h2>
                            <a href="{{ $gallery_feature_pro_two->button_link }}"
                                class="px-6 py-2 text-sm font-medium text-black bg-white rounded-full sm:text-base md:px-8 hover:bg-primary hover:text-white eq">
                                {{ $gallery_feature_pro_two->button_text }}
                            </a>
                        </div>
                    </div>

                    <!-- row 2 -->
                    <div class="grid grid-cols-2 gap-2 h-1/2">
                        <div class="relative h-full overflow-hidden group rounded-xl">
                            <!-- gallery image -->
                            <div class="w-full h-full">
                                <img src="{{ storage_url($gallery_feature_pro_three->image) }}"
                                    alt="Home decor items" class="object-cover w-full h-full" />
                            </div>
                            <!-- overlay -->
                            <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                            <!-- content -->
                            <div class="absolute top-0 left-0 w-full h-full p-6 text-white">
                                <h2 class="text-xl md:text-lg xl:text-[1.7rem] font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                    {{ $gallery_feature_pro_three->title }}
                                </h2>
                                <a href="{{ $gallery_feature_pro_three->button_link }}" class="font-medium text-white underline hover:text-primary eq">{{ $gallery_feature_pro_three->button_text }}</a>
                            </div>
                        </div>

                        <div class="relative h-full overflow-hidden group rounded-xl">
                            <!-- gallery image -->
                            <div class="w-full h-full">
                                <img src="{{ storage_url($gallery_feature_pro_four->image) }}"
                                    alt="Fresh produce and vegetables" class="object-cover w-full h-full" />
                            </div>
                            <!-- overlay -->
                            <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                            <!-- content -->
                            <div class="absolute top-0 left-0 w-full h-full p-6 text-white">
                                <h2 class="text-xl md:text-lg xl:text-[1.7rem] font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                    {{ $gallery_feature_pro_four->title }}
                                </h2>
                                <a href="{{ $gallery_feature_pro_four->button_link }}" class="font-medium text-white underline hover:text-primary eq">{{ $gallery_feature_pro_four->button_text }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- col 3 -->
                <div class="lg:row-span-2 lg:h-[33rem] md:col-span-2 lg:col-span-1 h-96">
                    <div class="relative h-full overflow-hidden group rounded-xl">
                        <!-- gallery image -->
                        <div class="w-full lg:h-full">
                            <img src="{{ storage_url($gallery_feature_pro_five->image) }}"
                                alt="Fashion collection" class="object-cover w-full h-full lg:h-full" />
                        </div>
                        <!-- overlay -->
                        <div class="absolute inset-0 bg-black/30 eq group-hover:bg-black/50"></div>
                        <!-- content -->
                        <div
                            class="absolute top-0 left-0 flex flex-col items-start justify-center w-full h-full gap-5 p-6 text-white">
                            <h2 class="text-xl md:text-lg xl:text-2xl font-medium mb-2 sm:mb-4 !leading-[1.2]">
                                {{ $gallery_feature_pro_five->title }}
                            </h2>
                            <a href="{{ $gallery_feature_pro_five->button_link }}"
                                class="px-6 py-2 text-sm font-medium text-black bg-white rounded-full sm:text-base md:px-8 hover:bg-primary hover:text-white eq">
                                {{ $gallery_feature_pro_five->button_text }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
