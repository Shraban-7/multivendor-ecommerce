@extends('frontend.layouts.app')
@section('title', $bundle->name)

@section('content')
<div class="bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4 py-4">
        <nav class="flex text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition"><i class="fas fa-home mr-1"></i>Home</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('bundles.index') }}" class="hover:text-primary-600 transition">Bundles</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="font-medium text-gray-900" aria-current="page">{{ $bundle->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-80 flex-shrink-0">
                            <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden">
                                <img src="{{ $bundle->thumbnail_url }}"
                                     alt="{{ $bundle->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            @if($bundle->images->count() > 0)
                            <div class="flex gap-2 mt-3 overflow-x-auto">
                                @foreach($bundle->images as $image)
                                <div class="w-16 h-16 flex-shrink-0 rounded border border-gray-200 overflow-hidden">
                                    <img src="{{ $image->image_url }}" class="w-full h-full object-cover">
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $bundle->name }}</h1>
                            @if($bundle->short_description)
                                <p class="text-gray-600 mb-4">{{ $bundle->short_description }}</p>
                            @endif

                            <div class="flex items-baseline gap-3 mb-4">
                                <span class="text-3xl font-bold text-primary-600">৳{{ number_format($calculatedPrice) }}</span>
                                @if($savings > 0)
                                    <s class="text-lg text-gray-400">৳{{ number_format($subtotal) }}</s>
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded">Save {{ $savingsPercent }}%</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                                <span><i class="fas fa-box mr-1"></i> {{ $bundle->items->count() }} items</span>
                                <span><i class="fas fa-tag mr-1"></i> {{ ucfirst($bundle->type) }}</span>
                                <span>
                                    <i class="fas fa-check-circle mr-1"></i>
                                    @if($stockStatus === 'in_stock')
                                        <span class="text-green-600">In Stock</span>
                                    @elseif($stockStatus === 'low_stock')
                                        <span class="text-orange-500">Only {{ $stock }} left</span>
                                    @else
                                        <span class="text-red-500">Out of Stock</span>
                                    @endif
                                </span>
                            </div>

                            @if($bundle->description)
                                <div class="prose prose-sm max-w-none text-gray-600">
                                    {!! nl2br(e($bundle->description)) !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-6 overflow-hidden">
                <div class="p-6 md:p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Bundle Items ({{ $bundle->items->count() }})</h2>
                    <div class="divide-y divide-gray-100">
                        @foreach($bundle->items as $item)
                            @php $product = $item->product; @endphp
                            <div class="py-4 flex items-center gap-4">
                                <div class="w-16 h-16 flex-shrink-0 rounded bg-gray-50 overflow-hidden">
                                    <img src="{{ $product?->image_url ?? asset('assets/frontend/images/placeholder.png') }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-sm truncate">{{ $product?->name ?? 'Unavailable' }}</p>
                                    <p class="text-xs text-gray-500">{{ $product?->sku ?? '' }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ money($product?->price ?? 0) }}</p>
                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right flex-shrink-0 w-20">
                                    <p class="text-sm font-semibold text-primary-600">{{ money(($product?->price ?? 0) * $item->quantity) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-2 flex justify-between items-center">
                        <span class="font-semibold text-gray-700">Total Value</span>
                        <span class="text-lg font-bold text-gray-900">{{ money($subtotal) }}</span>
                    </div>
                    @if($savings > 0)
                    <div class="flex justify-between items-center mt-1">
                        <span class="font-semibold text-gray-700">Bundle Price</span>
                        <span class="text-lg font-bold text-primary-600">{{ money($calculatedPrice) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-sm text-gray-500">You Save</span>
                        <span class="text-sm font-bold text-green-600">{{ money($savings) }} ({{ $savingsPercent }}%)</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="font-bold text-gray-900 mb-4">Bundle Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Items</span>
                        <span class="font-medium">{{ $bundle->items->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Type</span>
                        <span class="font-medium capitalize">{{ $bundle->type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Value</span>
                        <span class="font-medium">{{ money($subtotal) }}</span>
                    </div>
                    @if($savings > 0)
                    <div class="flex justify-between text-green-600">
                        <span>You Save</span>
                        <span class="font-bold">{{ money($savings) }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="flex justify-between text-lg">
                        <span class="font-bold text-gray-900">Bundle Price</span>
                        <span class="font-bold text-primary-600">{{ money($calculatedPrice) }}</span>
                    </div>
                </div>

                @if($stock > 0)
                    <button type="button"
                        class="mt-6 w-full bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition disabled:opacity-50"
                        onclick="alert('Add to cart functionality coming soon for bundles')">
                        <i class="fas fa-shopping-cart mr-2"></i> Add Bundle to Cart
                    </button>
                @else
                    <button type="button" disabled
                        class="mt-6 w-full bg-gray-300 text-gray-500 py-3 rounded-lg font-semibold cursor-not-allowed">
                        Out of Stock
                    </button>
                @endif
            </div>

            @if($relatedBundles->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
                <h3 class="font-bold text-gray-900 mb-4">More Bundles</h3>
                <div class="space-y-4">
                    @foreach($relatedBundles as $related)
                    <a href="{{ route('bundles.show', $related->slug) }}" class="flex items-center gap-3 group">
                        <div class="w-14 h-14 flex-shrink-0 rounded bg-gray-50 overflow-hidden">
                            <img src="{{ $related->thumbnail_url }}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary-600 transition">{{ $related->name }}</p>
                            <p class="text-xs font-semibold text-primary-600">{{ money($related->calculatePrice()) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
