<?php
$links = [];

if (auth('web')->check()) {
    // Normal User
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

    $links = array_merge($links, [
        [
            'title' => 'Wishlist',
            'route' => route('wishlist.index'),
            'active' => request()->routeIs('wishlist.index'),
        ],
        [
            'title' => 'Messages',
            'route' => '#',
            'active' => 0,
        ],
    ]);
}

if (auth('seller')->check()) {

    $user = auth('seller')->user();

    $links[] = [
        'title' => 'Dashboard',
        'route' => route('seller.dashboard'),
        'active' => request()->routeIs('seller.dashboard'),
    ];
}

if (auth('admin')->check()) {
    // Admin
    $user = auth('admin')->user();

    $links[] = [
        'title' => 'Admin Dashboard',
        'route' => route('admin.dashboard'),
        'active' => request()->routeIs('admin.dashboard'),
    ];
}

if (!function_exists('getInitials')) {
    function getInitials($name)
    {
        return strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', $name))));
    }
}

$nameInitials = isset($user) ? getInitials($user->name) : '';
?>


<aside class="hidden md:block bg-white rounded-sm border border-[#E5E5E5] p-4 md:col-span-1 space-y-2 h-auto">
    <div class="flex items-center gap-3 mb-6 p-3 rounded-sm bg-[#FFF8F5]">
        <div class="h-10 w-10 rounded-full bg-[#F85606]/10 flex items-center justify-center text-[#F85606] font-bold text-sm shrink-0">
            {{ $nameInitials }}
        </div>
        <div class="min-w-0">
            <p class="font-semibold text-sm text-[#191919] truncate">{{ $user->name }}</p>
            <p class="text-xs text-[#767676] truncate">{{ $user->email }}</p>
        </div>
    </div>
    <nav class="space-y-1">
        @foreach ($links as $link)
            <a href="{{ $link['route'] }}"
                class="block px-3 py-2 text-sm rounded-sm {{ $link['active'] ? 'bg-[#F85606] text-white font-medium' : 'text-[#595959] hover:bg-[#FFF8F5] hover:text-[#F85606]' }} transition-colors">
                {{ $link['title'] }}
            </a>
        @endforeach
        <a href="{{ route('logout') }}"
            class="block px-3 py-2 text-sm rounded-sm text-red-500 hover:bg-red-50 transition-colors"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </nav>
</aside>

<div class="fixed inset-0 bg-black/50 z-40 hidden" id="sidebar-backdrop"></div>

<aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-200 ease-in-out z-50 p-4 space-y-2" id="mobile-sidebar">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-[#191919]">My Account</h2>
        <button class="text-[#A0A0A0] hover:text-[#191919] transition-colors" id="sidebar-close">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <nav class="space-y-1">
        @foreach ($links as $link)
            <a href="{{ $link['route'] }}"
                class="block px-3 py-2 text-sm rounded-sm {{ $link['active'] ? 'bg-[#F85606] text-white font-medium' : 'text-[#595959] hover:bg-[#FFF8F5] hover:text-[#F85606]' }} transition-colors">
                {{ $link['title'] }}
            </a>
        @endforeach
        <a href="{{ route('logout') }}"
            class="block px-3 py-2 text-sm rounded-sm text-red-500 hover:bg-red-50 transition-colors"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </nav>
</aside>
