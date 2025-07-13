<style>
    .chevron-icon {
        transition: transform 0.3s ease;
    }

    .nav-link[aria-expanded="true"] .chevron-icon {
        transform: rotate(90deg);
    }
</style>

<?php
$routePath = request()->path();
$settings = settings();
?>

<nav class="navbar-vertical navbar">
    <div class="nav-scroller">
        <a class="navbar-brand d-flex" href="">
            <img src="{{ storage_url($settings->logo) }}" alt="logo" />
            {{-- <h5 class="text-white ms-5 ">Ecommerce</h5> --}}
        </a>
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <x-dashboard.nav-item-link :route="'admin.dashboard'">
                <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
            </x-dashboard.nav-item-link>

            @if (hasPermission('admin.brands.index'))
                <x-dashboard.nav-item-link :route="'admin.brands.index'">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Brands
                </x-dashboard.nav-item-link>
            @endif

            @if (hasPermission('admin.categories.index'))
                <x-dashboard.nav-item-link :route="'admin.categories.index'">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Categories
                </x-dashboard.nav-item-link>
            @endif

            @if (hasPermission('admin.subcategories.index'))
                <x-dashboard.nav-item-link :route="'admin.subcategories.index'">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Subcategories
                </x-dashboard.nav-item-link>
            @endif

            @if (hasPermission('admin.productAttributes.index'))
                <x-dashboard.nav-item-link :route="'admin.options.index'">
                    <i data-feather="package" class="nav-icon icon-xs me-2"></i> Manage Options
                </x-dashboard.nav-item-link>
            @endif

            @if (hasPermission('admin.products.index'))
                <x-dashboard.nav-item-link :route="'admin.products.index'">
                    <i data-feather="package" class="nav-icon icon-xs me-2"></i> Products
                </x-dashboard.nav-item-link>
            @endif

            @if (hasPermission('admin.customers.index'))
                <x-dashboard.nav-item-link :route="'admin.customers.index'">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> Customers
                </x-dashboard.nav-item-link>
            @endif

            @if (hasPermission('admin.sellers.index'))
                <x-dashboard.nav-item-link :route="'admin.sellers.index'">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i>Sellers
                </x-dashboard.nav-item-link>
            @endif

            {{-- @if (hasPermission('admin.orders.index')) --}}
            <x-dashboard.nav-item-link :route="'admin.orders.index'">
                <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i> Orders
            </x-dashboard.nav-item-link>

            {{-- @endif --}}

            @if (hasPermission('admin.reviews.index'))
                <x-dashboard.nav-item-link :route="'admin.reviews.index'">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i>Reviews
                </x-dashboard.nav-item-link>
            @endif

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navMembers"
                    aria-expanded="{{ request()->routeIs('admin.admins.*') ? 'true' : 'false' }}"
                    aria-controls="navMembers">

                    <div>
                        <i data-feather="users" class="nav-icon icon-xs me-2"></i>
                        Manage Members
                    </div>

                    <i data-feather="chevron-right" class="chevron-icon transition"></i>
                </a>

                <div id="navMembers" class="collapse {{ request()->routeIs('admin.admins.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if (hasPermission('admin.admins.index'))
                            <x-dashboard.nav-item-link :route="'admin.admins.index'">
                                Admin List
                            </x-dashboard.nav-item-link>
                        @endif
                        @if (hasPermission('admin.admins.create'))
                            <x-dashboard.nav-item-link :route="'admin.admins.create'">
                                Add Admin
                            </x-dashboard.nav-item-link>
                        @endif
                    </ul>
                </div>
            </li>


            @if (hasPermission('admin.roles.index'))
                <x-dashboard.nav-item-link :route="'admin.roles.index'">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i>Permissions
                </x-dashboard.nav-item-link>
            @endif

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navSettings"
                    aria-expanded="{{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }}"
                    aria-controls="navSettings">

                    <div>
                        <i data-feather="settings" class="nav-icon icon-xs me-2"></i>
                        Settings
                    </div>

                    <i data-feather="chevron-right" class="chevron-icon transition"></i>
                </a>

                <div id="navSettings" class="collapse {{ request()->routeIs('admin.settings.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if (hasPermission('admin.settings.hero.index'))
                            <x-dashboard.nav-item-link :route="'admin.settings.hero.index'">
                                Hero Banners
                            </x-dashboard.nav-item-link>
                        @endif

                        @if (hasPermission('admin.settings.banners.index'))
                            <x-dashboard.nav-item-link :route="'admin.settings.banners.index'">
                                Home Mid Banners
                            </x-dashboard.nav-item-link>
                        @endif

                        @if (hasPermission('admin.settings.posters.index'))
                            <x-dashboard.nav-item-link :route="'admin.settings.posters.index'">
                                Promo Posters
                            </x-dashboard.nav-item-link>
                        @endif

                        @if (hasPermission('admin.settings.socialLinks.index'))
                            <x-dashboard.nav-item-link :route="'admin.settings.socialLinks.index'">
                                Social Links
                            </x-dashboard.nav-item-link>
                        @endif

                        @if (hasPermission('admin.settings.paymentGateways.index'))
                            <x-dashboard.nav-item-link :route="'admin.settings.paymentGateways.index'">
                                Payment Gateways
                            </x-dashboard.nav-item-link>
                        @endif

                        @if (hasPermission('admin.settings.index'))
                            <x-dashboard.nav-item-link :route="'admin.settings.index'">
                                General
                            </x-dashboard.nav-item-link>
                        @endif
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</nav>
