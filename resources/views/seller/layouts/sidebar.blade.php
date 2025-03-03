<nav class="navbar-vertical navbar">
    <div class="nav-scroller">
        <a class="navbar-brand d-flex" href="">
            <img src="{{ asset('assets/frontend/images/tesko-icon.png') }}" alt="logo" />
            <h5 class="text-white ms-5 ">Ecommerce</h5>
        </a>
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <x-dashboard.nav-item-link :route="'seller.dashboard'">
                <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
            </x-dashboard.nav-item-link>
            
            <x-dashboard.nav-item-link :route="'seller.products'">
                <i data-feather="package" class="nav-icon icon-xs me-2"></i> Manage Product
            </x-dashboard.nav-item-link>


            <li class="nav-item">
                <a class="nav-link has-arrow  collapsed " href="#!" data-bs-toggle="collapse"
                    data-bs-target="#navOrders" aria-expanded="false" aria-controls="navOrders">
                    <i data-feather="shopping-cart" class="nav-icon icon-xs me-2">
                    </i> Manage Orders
                </a>
                <div id="navOrders" class="collapse {{ request()->routeIs('seller.orders.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <x-dashboard.nav-item-link :route="'seller.orders.pending'">
                            Pending
                        </x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.shipped'">
                            Shipped
                        </x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.delivered'">
                            Delivered
                        </x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.cancelled'">
                            Cancelled
                        </x-dashboard.nav-item-link>
                    </ul>
                </div>
            </li>

            <x-dashboard.nav-item-link :route="'seller.dashboard'">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Manage Customers
            </x-dashboard.nav-item-link>
            <x-dashboard.nav-item-link :route="'seller.dashboard'">
                <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
            </x-dashboard.nav-item-link>
        </ul>
    </div>
</nav>