<li class="nav-item">
    <a class="nav-link {{ request()->route()->getName() == $route ? 'active' : '' }}" href="{{ route($route) }}">
        {{ $slot }}
    </a>
</li>