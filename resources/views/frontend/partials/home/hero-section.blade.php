 <section class="hero-section flex flex-wrap lg:h-screen 2xl:h-[110vh]">
            <div class="w-full h-full md:w-1/2">
                <a href="#">
                    <img src="{{ storage_url($hero_grid_one->image) }}" alt="{{ $hero_grid_one->title }}"
                        class="object-cover w-full h-full" />
                </a>
            </div>

            <div class="w-full h-full md:w-1/2">
                <div class="flex h-1/2">
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_two->image) }}" alt="{{ $hero_grid_two->title }}"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_three->image) }}" alt="{{ $hero_grid_three->title }}"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                </div>
                <div class="flex h-1/2">
                    <div class="md:w-[45%] w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_four->image) }}" alt="{{ $hero_grid_four->title }}"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                    <div class="md:w-[55%] w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_five->image) }}" alt="{{ $hero_grid_five->title }}"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                </div>
            </div>
        </section>
