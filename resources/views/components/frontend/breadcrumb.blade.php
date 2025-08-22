<section class="container mt-2 mb-4">
    <nav aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center space-x-2 text-sm text-davy-gray">
            <li>
                <a href="/" class="flex items-center gap-1 hover:text-primary transition-colors">
                    <span>Home</span>
                </a>
            </li>

            @foreach ($items as $index => $item)
            <li>
                <i class="fa-solid fa-chevron-right text-xs text-davy-gray"></i>
            </li>

            <li @if ($loop->last) aria-current="page" @endif>
                @if (!empty($item['url']) && !$loop->last)
                <a href="{{ $item['url'] }}" class="hover:text-primary transition-colors">
                    {{ $item['label'] }}
                </a>
                @else
                <span class="text-davy-gray">
                    {{ $item['label'] }}
                </span>
                @endif
            </li>
            @endforeach
        </ol>
    </nav>
</section>