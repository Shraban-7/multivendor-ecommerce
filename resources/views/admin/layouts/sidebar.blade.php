<nav class="navbar-vertical navbar">
    <div class="nav-scroller">
        <a class="navbar-brand d-flex" href="">
            <img src="{{ asset('assets/frontend/images/tesko-icon.png') }}" alt="logo" />
            <h5 class="text-white ms-5 ">Ecommerce</h5>
        </a>
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <x-dashboard.nav-item-link :route="'admin.dashboard'">
                <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'admin.dashboard'">
                <i data-feather="package" class="nav-icon icon-xs me-2"></i> Products
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'admin.customers.index'">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Customers
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'admin.sellers.index'">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i>Sellers
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'admin.dashboard'">
                <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
            </x-dashboard.nav-item-link>
        </ul>
    </div>
</nav>