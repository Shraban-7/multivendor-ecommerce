<section class="section-padding mb-12">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Top Brands</h2>
            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">
                View All →
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            @foreach ($brands as $brand)
                <div class="border  border-gray-100 bg-white p-6 flex flex-col items-center shadow-sm transition-shadow hover:shadow-md ">
                    <img src="{{ storage_url($brand->image) }}" alt="{{ $brand->name }}"
                        class="h-12 mb-4">
                    <span class="text-gray-700 font-medium">{{ $brand->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
