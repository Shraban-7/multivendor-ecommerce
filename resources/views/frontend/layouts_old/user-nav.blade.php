@php
    $navItems = [
        ['label' => 'Affiliate Dashboard', 'route' => 'affiliator.dashboard'],
        ['label' => 'Withdraw', 'route' => 'affiliator.withdraw'],
        ['label' => 'My Orders', 'route' => 'orders.index'],
    ];

    $shouldShowNav = request()->routeIs('affiliator.dashboard') 
                   || request()->routeIs('affiliator.withdraw') 
                   || request()->routeIs('orders.index');
@endphp

@if($shouldShowNav)
    <div class="flex flex-wrap gap-4 border-b border-gray-200 pb-2 mb-6">
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="px-4 py-2 rounded-md text-sm font-medium 
                      {{ request()->routeIs($item['route']) 
                            ? 'bg-primary text-white' 
                            : 'hover:bg-gray-100 text-gray-700' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
@endif
