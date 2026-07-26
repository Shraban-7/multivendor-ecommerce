<section class="container mt-2 mb-4">
    <nav aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-x-1 gap-y-1 text-xs">
            <li>
                <a href="/" class="flex items-center gap-1 text-ds-text-secondary hover:text-brand transition-colors duration-100">
                    <span>Home</span>
                </a>
            </li>

            @foreach ($items as $index => $item)
            <li class="flex items-center" aria-hidden="true">
                <svg class="w-3 h-3 text-ds-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>

            <li @if ($loop->last) aria-current="page" @endif>
                @if (!empty($item['url']) && !$loop->last)
                <a href="{{ $item['url'] }}" class="text-ds-text-secondary hover:text-brand transition-colors duration-100">
                    {{ $item['label'] }}
                </a>
                @else
                <span class="text-ds-text-tertiary">
                    {{ $item['label'] }}
                </span>
                @endif
            </li>
            @endforeach
        </ol>
    </nav>
</section>
