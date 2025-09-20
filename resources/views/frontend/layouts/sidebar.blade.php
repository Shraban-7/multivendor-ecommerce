<?php
$links = [];

if (affiliate()) {
    $links[] = [
        'title' => 'Dashboard',
        'route' => route('affiliator.dashboard'),
        'active' => request()->route()->getName() == 'affiliator.dashboard' ? 1 : 0,
    ];
}

$links[] = [
    'title' => 'Orders',
    'route' => route('orders.index'),
    'active' => request()->route()->getName() == 'orders.index' ? 1 : 0,
];

$links[] = [
    'title' => 'Profile',
    'route' => route('profile'),
    'active' => request()->route()->getName() == 'profile' ? 1 : 0,
];

if (affiliate()) {
    $links[] = [
        'title' => 'Withdraw',
        'route' => route('affiliator.withdraw'),
        'active' => request()->route()->getName() == 'affiliator.withdraw' ? 1 : 0,
    ];
}

$links = array_merge($links, [
    [
        'title' => 'Wishlist',
        'route' => route('wishlist.index'),
        'active' => request()->route()->getName() == 'wishlist.index' ? 1 : 0,
    ],
    [
        'title' => 'Messages',
        'route' => '#',
        'active' => 0,
    ],
]);



function getInitials($name)
{
    return strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', $name))));
}

$user = auth()->user();
$nameInitials = getInitials($user->name);

?>

<aside class="hidden md:block bg-white rounded-lg border border-gray-200 p-4 md:col-span-1 space-y-2 h-auto">

    <div class="flex items-center mb-8 p-4 rounded-lg bg-yellow-50">
        <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700 font-bold">
            {{ $nameInitials }}
        </div>
        <div class="ml-3">
            <p class="font-semibold">{{ $user->name }}</p>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
        </div>
    </div>

    <nav class="space-y-2">
        @foreach ($links as $link)
        <a href="{{ $link['route'] }}"
            class="block p-3 rounded-md {{ $link['active'] ? 'bg-yellow-500 text-white font-medium' : 'hover:bg-yellow-100' }}">
            {{ $link['title'] }}
        </a>
        @endforeach
        <a href="{{ route('logout') }}" class="block p-3 rounded-md text-red-600 hover:bg-red-50"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

    </nav>
</aside>


<!-- Mobile Offcanvas Sidebar -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="sidebar-backdrop"></div>

<aside
    class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-200 ease-in-out z-50 p-4 space-y-2"
    id="mobile-sidebar">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">My Account</h2>
        <button class="text-gray-500 hover:text-gray-700" id="sidebar-close">✕</button>
    </div>
    <nav class="space-y-2">
        @foreach ($links as $link)
        <a href="{{ $link['route'] }}"
            class="block p-3 rounded-md {{ $link['active'] ? 'bg-yellow-500 text-white font-medium' : 'hover:bg-yellow-100' }}">
            {{ $link['title'] }}
        </a>
        @endforeach
        <a href="{{ route('logout') }}" class="block p-3 rounded-md text-red-600 hover:bg-red-50"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>
</aside>