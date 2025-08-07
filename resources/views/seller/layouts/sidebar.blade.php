<style>
    .chevron-icon {
        transition: transform 0.3s ease;
    }

    .nav-link[aria-expanded="true"] .chevron-icon {
        transform: rotate(90deg);
    }
</style>
<nav class="navbar-vertical navbar">
    <?php
    $settings = settings();
    ?>
    <div class="nav-scroller">
        <a class="navbar-brand d-flex" href="/">
            <img src="{{ storage_url($settings->logo) }}" alt="logo" />
            {{-- <h5 class="text-white ms-5 ">Ecommerce</h5> --}}
        </a>
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <x-dashboard.nav-item-link :route="'seller.dashboard'">
                <i data-feather="home" class="nav-icon icon-xs me-2"></i> Dashboard
            </x-dashboard.nav-item-link>

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
                        <x-dashboard.nav-item-link :route="'seller.products.index'">
                            Products
                        </x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.products.create'">
                            Add Product
                        </x-dashboard.nav-item-link>
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
                        <x-dashboard.nav-item-link :route="'seller.campaigns.index'">
                            All Campaigns
                        </x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.campaigns.create'">
                            Add Campaign
                        </x-dashboard.nav-item-link>
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
                        <x-dashboard.nav-item-link :route="'seller.orders.pending'">Pending</x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.shipped'">Shipped</x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.delivered'">Delivered</x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.returned'">Returned</x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.refunded'">Refunded</x-dashboard.nav-item-link>
                        <x-dashboard.nav-item-link :route="'seller.orders.cancelled'">Cancelled</x-dashboard.nav-item-link>
                    </ul>
                </div>
            </li>

            <x-dashboard.nav-item-link :route="'seller.customers'">
                <i data-feather="users" class="nav-icon icon-xs me-2"></i> Manage Customers
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'seller.chat.list'">
                <i data-feather="message-circle" class="nav-icon icon-xs me-2"></i> Messages
            </x-dashboard.nav-item-link>

            <x-dashboard.nav-item-link :route="'seller.settings.index'">
                <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
            </x-dashboard.nav-item-link>
        </ul>
    </div>
</nav>
