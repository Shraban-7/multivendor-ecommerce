<?php
$seller = seller();
$employee = employee();
$route = request()->route()->getName();
?>

<style>
    .chevron-icon {
        transition: transform 0.3s ease;
    }

    .nav-link[aria-expanded="true"] .chevron-icon {
        transform: rotate(90deg);
    }
</style>

<nav class="navbar-vertical navbar">
    <div class="nav-scroller">
        <a class="navbar-brand d-flex" href="/">
            <img src="{{ storage_url($settings->logo) }}" alt="logo" />
        </a>
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <x-dashboard.nav-item-link :route="'seller.dashboard'">
                <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
            </x-dashboard.nav-item-link>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navPos"
                    aria-expanded="{{ request()->routeIs('seller.pos.*') ? 'true' : 'false' }}" aria-controls="navPos">

                    <div>
                        <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i>
                        Manage Pos
                    </div>

                    <i data-feather="chevron-right" class="chevron-icon transition"></i>
                </a>

                <div id="navPos" class="collapse {{ request()->routeIs('seller.pos.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.pos.index'))
                            <x-dashboard.nav-item-link :route="'seller.pos.index'">
                                Pos
                            </x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.pos.sales.index'))
                            <x-dashboard.nav-item-link :route="'seller.pos.sales.index'">
                                Sales
                            </x-dashboard.nav-item-link>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navProducts"
                    aria-expanded="{{ request()->routeIs('seller.products.*') ? 'true' : 'false' }}"
                    aria-controls="navProducts">

                    <div>
                        <i data-feather="package" class="nav-icon icon-xs me-2"></i>
                        Manage Products
                    </div>

                    <i data-feather="chevron-right" class="chevron-icon transition"></i>
                </a>

                <div id="navProducts" class="collapse {{ request()->routeIs('seller.products.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.products.index'))
                            <x-dashboard.nav-item-link :route="'seller.products.index'">
                                <i data-feather="database" class="nav-icon icon-xs me-2"></i> Products
                            </x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.products.stockHistory'))
                            <x-dashboard.nav-item-link :route="'seller.products.stockHistory'">
                                <i data-feather="clock" class="nav-icon icon-xs me-2"></i> Stock History
                            </x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.products.create'))
                            <x-dashboard.nav-item-link :route="'seller.products.create'">
                                <i data-feather="plus" class="nav-icon icon-xs me-2"></i> Add Product
                            </x-dashboard.nav-item-link>
                        @endif

                        @if ($seller || $employee->hasPermission('seller.products.printBarcode'))
                            <x-dashboard.nav-item-link :route="'seller.products.printBarcode'">
                                <i data-feather="printer" class="nav-icon icon-xs me-2"></i> Print Barcode
                            </x-dashboard.nav-item-link>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navCampaigns"
                    aria-expanded="{{ request()->routeIs('seller.campaigns.*') ? 'true' : 'false' }}"
                    aria-controls="navCampaigns">

                    <div>
                        <i data-feather="image" class="nav-icon icon-xs me-2"></i>
                        Manage Campaigns
                    </div>

                    <i data-feather="chevron-right" class="chevron-icon transition"></i>
                </a>

                <div id="navCampaigns" class="collapse {{ request()->routeIs('seller.campaigns.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.campaigns.index'))
                            <x-dashboard.nav-item-link :route="'seller.campaigns.index'">
                                All Campaigns
                            </x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.campaigns.create'))
                            <x-dashboard.nav-item-link :route="'seller.campaigns.create'">
                                Add Campaign
                            </x-dashboard.nav-item-link>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navOrders"
                    aria-expanded="{{ request()->routeIs('seller.orders.*') ? 'true' : 'false' }}"
                    aria-controls="navOrders">

                    <div>
                        <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i>
                        Manage Orders
                    </div>

                    <i data-feather="chevron-right" class="chevron-icon transition"></i>
                </a>

                <div id="navOrders" class="collapse {{ request()->routeIs('seller.orders.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.orders.index'))
                            <x-dashboard.nav-item-link :route="'seller.orders.index'">All</x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.pending'))
                            <x-dashboard.nav-item-link :route="'seller.orders.pending'">Pending</x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.shipped'))
                            <x-dashboard.nav-item-link :route="'seller.orders.shipped'">Shipped</x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.delivered'))
                            <x-dashboard.nav-item-link :route="'seller.orders.delivered'">Delivered</x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.returned'))
                            <x-dashboard.nav-item-link :route="'seller.orders.returned'">Returned</x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.refunded'))
                            <x-dashboard.nav-item-link :route="'seller.orders.refunded'">Refunded</x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.cancelled'))
                            <x-dashboard.nav-item-link :route="'seller.orders.cancelled'">Cancelled</x-dashboard.nav-item-link>
                        @endif
                    </ul>
                </div>
            </li>

            <x-dashboard.nav-item-link :route="'seller.customers'">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Manage Customers
            </x-dashboard.nav-item-link>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navEmployees"
                    aria-expanded="{{ request()->routeIs('seller.employees.*') ? 'true' : 'false' }}"
                    aria-controls="navEmployees">

                    <div>
                        <i data-feather="users" class="nav-icon icon-xs me-2"></i>
                        Manage Employees
                    </div>

                    <i data-feather="chevron-right" class="chevron-icon transition"></i>
                </a>

                <div id="navEmployees" class="collapse {{ request()->routeIs('seller.employees.*') ? 'show' : '' }}"
                    data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.employees.index'))
                            <x-dashboard.nav-item-link :route="'seller.employees.index'">
                                All Employees
                            </x-dashboard.nav-item-link>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.employees.create'))
                            <x-dashboard.nav-item-link :route="'seller.employees.create'">
                                Add Employee
                            </x-dashboard.nav-item-link>
                        @endif
                    </ul>
                </div>
            </li>

            @if ($seller || $employee->hasPermission('seller.chat.list'))
                <x-dashboard.nav-item-link :route="'seller.chat.list'">
                    <i data-feather="message-circle" class="nav-icon icon-xs me-2"></i> Messages
                </x-dashboard.nav-item-link>
            @endif

            @if ($seller || $employee->hasPermission('seller.expenses.index'))
                <x-dashboard.nav-item-link :route="'seller.expenses.index'">
                    <i data-feather="dollar-sign" class="nav-icon icon-xs me-2"></i> Expenses
                </x-dashboard.nav-item-link>
            @endif

            @if ($seller || $employee->hasPermission('seller.settings.index'))
                <x-dashboard.nav-item-link :route="'seller.settings.index'">
                    <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
                </x-dashboard.nav-item-link>
            @endif
        </ul>
    </div>
</nav>
