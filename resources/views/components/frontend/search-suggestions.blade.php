<div>
    @if($products->count())
    <div class="p-3">
        <h4 class="text-sm font-semibold text-gray-500 mb-2">Products</h4>
        <ul class="space-y-2">
            @foreach($products as $product)
            <li>
                <a href="{{ route('products.details', $product->slug) }}"
                    class="flex items-center gap-3 px-3 py-2 hover:bg-orange-50 rounded-md text-gray-700">
                    <img src="{{ $product->imageUrl }}"
                        alt="{{ $product->name }}"
                        class="w-10 h-10 rounded-md object-cover">

                    <div class="flex flex-col">
                        <span class="font-medium">{{ $product->name }}</span>
                        <span class="text-orange-500 text-sm">{{ money($product->price) }}</span>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($sellers->count())
    <div class="p-3 border-t border-gray-100">
        <h4 class="text-sm font-semibold text-gray-500 mb-2">Shops</h4>
        <ul class="space-y-2">
            @foreach($sellers as $seller)
            <li>
                <a href="{{ route('sellers.shop', $seller->username) }}"
                    class="flex items-center gap-3 px-3 py-2 hover:bg-orange-50 rounded-md text-gray-700">
                    <img src="{{ storage_url($seller->business_logo) }}" alt="{{ $seller->business_name }}" class="w-8 h-8 rounded-full object-cover">
                    <span>{{ $seller->business_name }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!$products->count() && !$sellers->count())
    <div class="p-3 text-gray-500 text-sm">
        No results found.
    </div>
    @endif
</div>