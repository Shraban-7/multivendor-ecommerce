@php
    $links = [];
    if (auth('web')->check()) {
        $user = auth('web')->user();
        if (affiliate()) {
            $links[] = [
                'title' => 'Dashboard',
                'route' => route('affiliator.dashboard'),
                'active' => request()->routeIs('affiliator.dashboard'),
            ];
        }
        $links[] = [
            'title' => 'Orders',
            'route' => route('orders.index'),
            'active' => request()->routeIs('orders.index'),
        ];
        $links[] = [
            'title' => 'Profile',
            'route' => route('profile'),
            'active' => request()->routeIs('profile'),
        ];
        if (affiliate()) {
            $links[] = [
                'title' => 'Withdraw',
                'route' => route('affiliator.withdraw'),
                'active' => request()->routeIs('affiliator.withdraw'),
            ];
        }
        $links[] = [
            'title' => 'Wishlist',
            'route' => route('wishlist.index'),
            'active' => request()->routeIs('wishlist.index'),
        ];
    }
    if (!function_exists('getInitials')) {
        function getInitials($name) {
            return strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', $name))));
        }
    }
    $nameInitials = isset($user) ? getInitials($user->name) : '';
@endphp

<div class="bg-white rounded-sm border border-[#E5E5E5]">
    <div class="flex flex-wrap items-center gap-3 px-5 py-3">
        <div class="flex items-center gap-2 shrink-0">
            <div class="h-8 w-8 rounded-full bg-[#F85606]/10 flex items-center justify-center text-[#F85606] font-bold text-xs shrink-0">
                {{ $nameInitials }}
            </div>
            <span class="text-sm font-semibold text-[#191919] hidden sm:inline">{{ $user->name }}</span>
        </div>
        <div class="flex flex-wrap items-center gap-1">
            @foreach ($links as $link)
                <a href="{{ $link['route'] }}"
                    class="px-3 py-1.5 text-sm rounded-sm whitespace-nowrap {{ $link['active'] ? 'bg-[#F85606] text-white font-medium' : 'text-[#595959] hover:bg-[#FFF8F5] hover:text-[#F85606]' }} transition-colors">
                    {{ $link['title'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>
