<section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Top Sellers</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ($sellers as $seller)
            <div class="bg-gray-50 p-4 rounded-lg shadow hover:shadow-md transition">
                <div class="flex items-center flex-col">
                    <img src="{{ storage_url($seller->image) }}" alt="{{ $seller->name }}"
                        class="w-20 h-20 rounded-full object-cover mb-3">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $seller->name }}</h3>
                    <a href=""
                        class="text-sm text-indigo-600 mt-1 hover:underline">Visit Store</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>