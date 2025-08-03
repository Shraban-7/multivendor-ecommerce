<section class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="uppercase md:text-center sm:text-xl xl:text-4xl lg:text-3xl md:text-2xl text-theme-dark mb-8">
        Featured Categories
    </h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-6">
        @foreach ($categories as $category)
        <div class="flex flex-col items-center p-4 bg-white rounded-lg shadow hover:shadow-lg cursor-pointer transition">
            <img src="{{ storage_url($category->image) }}" alt="{{ $category->name }}" class="w-16 h-16 mb-3 object-contain" />
            <span class="text-center font-medium text-gray-700 text-sm leading-tight break-words line-clamp-2">
                {{ $category->name }}
            </span>
        </div>
        @endforeach
    </div>
</section>