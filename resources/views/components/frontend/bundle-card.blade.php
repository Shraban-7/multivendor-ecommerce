@props(['bundle'])

@php
    $bundle->loadMissing('items.product');
    $itemsCount = $bundle->items->count();
    $calculatedPrice = $bundle->calculatePrice();
    $originalTotal = $bundle->calculateOriginalTotal();
    $savings = $originalTotal - $calculatedPrice;
    $savingsPercent = $originalTotal > 0 ? round(($savings / $originalTotal) * 100) : 0;
@endphp

<div class="group bg-ds-surface-base border border-ds-border-default rounded-sm overflow-hidden hover:shadow-card transition-shadow duration-200">
    <a href="{{ route('bundles.show', $bundle->slug) }}" class="block relative aspect-square bg-ds-surface-muted overflow-hidden">
        @if ($savingsPercent > 0)
            <span class="absolute top-1.5 left-1.5 z-10 bg-ds-feedback-danger text-white text-[10px] font-bold px-1.5 py-0.5 rounded-xs">-{{ $savingsPercent }}%</span>
        @endif
        <img src="{{ $bundle->thumbnail_url }}"
             alt="{{ $bundle->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
             loading="lazy">
        <div class="absolute bottom-1.5 left-1.5 z-10 bg-black/60 text-white text-[9px] font-medium px-1.5 py-0.5 rounded-xs">
            {{ $itemsCount }} items
        </div>
    </a>

    <div class="p-2.5">
        <a href="{{ route('bundles.show', $bundle->slug) }}" class="block text-xs text-ds-text-primary leading-snug line-clamp-2 hover:text-brand transition-colors duration-100 min-h-[2.5rem]">
            {{ $bundle->name }}
        </a>

        @if ($bundle->short_description)
            <p class="text-[10px] text-ds-text-tertiary mt-1 line-clamp-1">{{ $bundle->short_description }}</p>
        @endif

        <div class="flex items-baseline gap-1.5 mt-1.5">
            <span class="text-sm font-bold text-ds-feedback-danger">৳{{ number_format($calculatedPrice) }}</span>
            @if ($savings > 0)
                <s class="text-[10px] text-ds-text-tertiary">৳{{ number_format($originalTotal) }}</s>
            @endif
        </div>
    </div>
</div>
