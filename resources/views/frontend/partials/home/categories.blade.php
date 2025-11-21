<section class="mt-5 p-5 bg-white rounded">
    <h2 class="sm:text-xl md:text-2xl text-theme-dark mb-3">
        Featured Categories
    </h2>
    <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3">
        @foreach ($categories as $category)
        <div class="p-2 md:p-4 bg-white rounded-lg shadow hover:shadow-lg cursor-pointer transition">
            <a href="{{ route('category.details',$category->slug) }}" class="flex flex-col items-center">
                <img src="{{ storage_url($category->image) }}" alt="{{ $category->name }}" class="w-10 h-10 md:w-16 md:h-16 mb-3 object-contain" />
                <span class="text-center text-gray-700 text-xs md:text-sm leading-tight break-words line-clamp-2">
                    {{ $category->name }}
                </span>
            </a>
        </div>
        @endforeach
    </div>
</section>