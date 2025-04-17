 <section class="hero-section flex flex-wrap lg:h-screen 2xl:h-[110vh]">
            <div class="w-full h-full md:w-1/2">
                <a href="#">
                    <img src="{{ storage_url($hero_grid_one->image) }}" alt="Image 1"
                        class="object-cover w-full h-full" />
                </a>
            </div>

            <div class="w-full h-full md:w-1/2">
                <div class="flex h-1/2">
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_two->image) }}" alt="Image 2"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                    <div class="w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_three->image) }}" alt="Image 3"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                </div>
                <div class="flex h-1/2">
                    <div class="md:w-[45%] w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_four->image) }}" alt="Image 4"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                    <div class="md:w-[55%] w-1/2">
                        <a href="#">
                            <img src="{{ storage_url($hero_grid_five->image) }}" alt="Image 5"
                                class="object-cover w-full h-full" />
                        </a>
                    </div>
                </div>
            </div>
        </section>
