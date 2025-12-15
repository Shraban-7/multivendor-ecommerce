@php
    $product = $productItem->product;
@endphp
<div class="bg-white rounded-xl border border-gray-100 hover:border-primary-500 hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col h-full relative">
    <!-- Discount Badge -->
    <div class="absolute top-3 left-3 z-10">
        @php
        $discountPercent = $product->discount_amount > 0
            ? round(($product->discount_amount / $product->selling_price) * 100)
            : 0;
        @endphp

        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
            -{{ $discountPercent }}%
        </span>
    </div>

    <!-- Product Image -->
    <div class="relative h-48 w-full bg-gray-50 p-4 flex items-center justify-center overflow-hidden">
        <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="max-h-full object-contain mix-blend-multiply group-hover:scale-110 transition duration-500">

        <!-- Hover Actions -->
        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-2">
            <button data-slug="{{ $product->slug }}" class="btn-quickview w-9 h-9 bg-white text-gray-600 rounded-full shadow-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transform translate-y-4 group-hover:translate-y-0 transition delay-75" title="Quick View">
                <i class="far fa-eye"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="p-3 flex flex-col flex-1">
        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 hover:text-primary-600 transition cursor-pointer">
            <a href="{{ route('products.details', $product->slug) }}">{{ $product->name }}</a>
        </h3>

        <!-- Rating -->
        <div class="flex items-center gap-1 mb-3">
            @php
                $rating = $product->avg_rating ?? 0;
                $fullStars = floor($rating);
                $hasHalfStar = ($rating - $fullStars) >= 0.5;
            @endphp
            
            <div class="flex text-yellow-400 text-[10px]">
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star"></i>
                @endfor
            
                @if ($hasHalfStar)
                    <i class="fas fa-star-half-alt"></i>
                @endif
            
                @for ($i = 0; $i < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $i++)
                    <i class="far fa-star"></i>
                @endfor
            </div>

            <span class="text-[10px] text-gray-400">({{ $product->rating_count}})</span>
        </div>

        <!-- Price & Cart -->
        <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
            <div class="flex flex-col">
                @if ($product->discount_price)
                <span class="text-xs text-gray-400 line-through">{{ money($product->selling_price) }}</span>
                <span class="text-primary-600 font-bold text-lg">{{ money($product->discount_price) }}</span>
                @else
                <span class="text-primary-600 font-bold text-lg"> {{ money($product->selling_price) }}</span>
                @endif
            </div>
            <button data-slug="{{ $product->slug }}" class="btn-quickview  w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-primary-600 hover:text-white transition shadow-sm">
                <i class="fas fa-shopping-cart text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Progress Bar (Optional: Shows stock left) -->
    <div class="px-3 pb-3">
        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
            @php
            $percentageSold = $productItem->stock_in > 0 ? $productItem->stock_out / $productItem->stock_in : 0;
            @endphp
            <div class="bg-gradient-to-r from-orange-400 to-red-500 h-1.5 rounded-full" style="width: {{ $percentageSold * 100 }}%"></div>
        </div>
        <div class="flex justify-between text-[10px] font-medium text-gray-500">
            <span>Sold: {{ $productItem->stock_out }}</span>
            <span class="text-red-500">Only {{ $productItem->stock_in - $productItem->stock_out }} left!</span>
        </div>
    </div>
</div>