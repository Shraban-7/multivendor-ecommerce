<li class="nav-item">
    <a class="nav-link d-flex align-items-center {{ request()->route()->getName() == $route ? 'active' : '' }}" href="{{ route($route) }}">
        {{ $slot }}
    </a>
</li>
