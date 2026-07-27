@php
    $links = [];
    $hasActive = false;
    if (auth('web')->check()) {
        $user = auth('web')->user();
        if (affiliate()) {
            $active = request()->routeIs('affiliator.dashboard');
            if ($active) $hasActive = true;
            $links[] = [
                'title' => 'Dashboard',
                'route' => route('affiliator.dashboard'),
                'active' => $active,
            ];
        }
        $profileActive = request()->routeIs('profile');
        if ($profileActive) $hasActive = true;
        $links[] = [
            'title' => 'Profile',
            'route' => route('profile'),
            'active' => $profileActive,
        ];
        $addressesActive = request()->routeIs('addresses');
        if ($addressesActive) $hasActive = true;
        $links[] = [
            'title' => 'Address',
            'route' => route('addresses'),
            'active' => $addressesActive,
        ];
        $ordersActive = request()->routeIs('orders.index') || request()->routeIs('orders.details');
        if ($ordersActive) $hasActive = true;
        $links[] = [
            'title' => 'Orders',
            'route' => route('orders.index'),
            'active' => $ordersActive,
        ];
        if (affiliate()) {
            $withdrawActive = request()->routeIs('affiliator.withdraw');
            if ($withdrawActive) $hasActive = true;
            $links[] = [
                'title' => 'Withdraw',
                'route' => route('affiliator.withdraw'),
                'active' => $withdrawActive,
            ];
        }
        $returnsActive = request()->routeIs('returns.index');
        if ($returnsActive) $hasActive = true;
        $links[] = [
            'title' => 'Returns',
            'route' => route('returns.index'),
            'active' => $returnsActive,
        ];
        $wishlistActive = request()->routeIs('wishlist.index');
        if ($wishlistActive) $hasActive = true;
        $links[] = [
            'title' => 'Wishlist',
            'route' => route('wishlist.index'),
            'active' => $wishlistActive,
        ];
        if (!$hasActive) {
            foreach ($links as &$link) {
                if ($link['title'] === 'Profile') {
                    $link['active'] = true;
                    break;
                }
            }
        }
    }
@endphp

<div class="bg-white rounded-sm border border-[#E5E5E5]">
    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-1 px-5 py-3">
        @foreach ($links as $link)
            <a href="{{ $link['route'] }}"
                class="px-3 py-1.5 text-sm rounded-sm whitespace-nowrap {{ $link['active'] ? 'bg-[#F85606] text-white font-medium' : 'text-[#595959] hover:bg-[#FFF8F5] hover:text-[#F85606]' }} transition-colors">
                {{ $link['title'] }}
            </a>
        @endforeach
    </div>
</div>
