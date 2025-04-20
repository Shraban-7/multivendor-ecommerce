<?php
$routePath = request()->path();
?>

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

            <x-dashboard.nav-item-link :route="'admin.products.index'">
                <i data-feather="package" class="nav-icon icon-xs me-2"></i> Products
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'admin.customers.index'">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Customers
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'admin.sellers.index'">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i>Sellers
            </x-dashboard.nav-item-link>

            <li class="nav-item">
                <a class="nav-link has-arrow  collapsed " href="#!" data-bs-toggle="collapse"
                    data-bs-target="#navSettings" aria-expanded="false" aria-controls="navSettings">
                    <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
                </a>
                <div id="navSettings" class="collapse {{ request()->routeIs('admin.settings.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <x-dashboard.nav-item-link :route="'admin.settings.hero.index'">
                           Hero Banners
                        </x-dashboard.nav-item-link>
                    </ul>
                    <ul class="nav flex-column">
                        <x-dashboard.nav-item-link :route="'admin.settings.banners.index'">
                           Home Mid Banners
                        </x-dashboard.nav-item-link>
                    </ul>
                    <ul class="nav flex-column">
                        <x-dashboard.nav-item-link :route="'admin.settings.posters.index'">
                           Promo Posters
                        </x-dashboard.nav-item-link>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
