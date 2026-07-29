<?php
$settings = settings();

$catalogsOpen = request()->routeIs('admin.brands.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.subcategories.*') || request()->routeIs('admin.colors.*') || request()->routeIs('admin.sizes.*');
$sellersOpen = request()->routeIs('admin.sellers.*') || request()->routeIs('admin.seller.requests') || request()->routeIs('admin.seller.payments');
$ordersOpen = request()->routeIs('admin.orders.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.payouts.*') || request()->routeIs('admin.reviews.*') || request()->routeIs('admin.returns.*');
$membersOpen = request()->routeIs('admin.admins.*');
$gatewaysOpen = request()->routeIs('admin.paymentGateways.*') || request()->routeIs('admin.manualGateways.*');
$settingsOpen = request()->routeIs('admin.settings.*') || request()->routeIs('admin.staticPages.*') || request()->routeIs('admin.banners.*');
$subscriptionsOpen = request()->routeIs('admin.subscription-plans.*') || request()->routeIs('admin.subscriptions.*');
?>

<nav class="navbar-vertical navbar" id="adminSidebar" aria-label="Admin navigation">
    <a class="navbar-brand flex" href="{{ route('admin.dashboard') }}">
        <img src="{{ storage_url($settings->logo_white) }}" alt="logo" />
    </a>

    <div class="sidebar-scroll">
        <ul class="navbar-nav flex-col mb-0" id="sideNavbar">

            {{-- ═══ 1. MAIN ═══ --}}
            <li class="sidebar-heading">Main</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}"
                    @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                    <i data-lucide="layout-dashboard" class="nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- ═══ 2. CATALOG ═══ --}}
            <li class="sidebar-heading">Catalog</li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $catalogsOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navCatalogs"
                    aria-expanded="{{ $catalogsOpen ? 'true' : 'false' }}" aria-controls="navCatalogs">
                    <i data-lucide="layers" class="nav-icon"></i>
                    <span>Manage Catalogs</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navCatalogs" class="collapse {{ $catalogsOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.brands.index') ? 'active' : '' }}"
                                href="{{ route('admin.brands.index') }}">
                                <i data-lucide="tag" class="nav-icon"></i>
                                <span>Brands</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
                                href="{{ route('admin.categories.index') }}">
                                <i data-lucide="folder" class="nav-icon"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.subcategories.index') ? 'active' : '' }}"
                                href="{{ route('admin.subcategories.index') }}">
                                <i data-lucide="folder-open" class="nav-icon"></i>
                                <span>Subcategories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.colors.index') ? 'active' : '' }}"
                                href="{{ route('admin.colors.index') }}">
                                <i data-lucide="palette" class="nav-icon"></i>
                                <span>Colors</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sizes.index') ? 'active' : '' }}"
                                href="{{ route('admin.sizes.index') }}">
                                <i data-lucide="maximize" class="nav-icon"></i>
                                <span>Sizes</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}"
                    href="{{ route('admin.products.index') }}"
                    @if (request()->routeIs('admin.products.index')) aria-current="page" @endif>
                    <i data-lucide="package" class="nav-icon"></i>
                    <span>Products</span>
                </a>
            </li>

            {{-- ═══ 3. CUSTOMERS ═══ --}}
            <li class="sidebar-heading">Customers</li>

            @if (hasPermission('admin.customers.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}"
                    href="{{ route('admin.customers.index') }}"
                    @if (request()->routeIs('admin.customers.index')) aria-current="page" @endif>
                    <i data-lucide="users" class="nav-icon"></i>
                    <span>Customers</span>
                </a>
            </li>
            @endif

            {{-- ═══ 4. PROMOTIONS ═══ --}}
            <li class="sidebar-heading">Promotions</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.flash-sales.index') ? 'active' : '' }}"
                    href="{{ route('admin.flash-sales.index') }}"
                    @if (request()->routeIs('admin.flash-sales.index')) aria-current="page" @endif>
                    <i data-lucide="zap" class="nav-icon"></i>
                    <span>Flash Sales</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.coupons.index') ? 'active' : '' }}"
                    href="{{ route('admin.coupons.index') }}"
                    @if (request()->routeIs('admin.coupons.index')) aria-current="page" @endif>
                    <i data-lucide="ticket-percent" class="nav-icon"></i>
                    <span>Coupons</span>
                </a>
            </li>

            {{-- ═══ 5. SELLERS ═══ --}}
            @if (hasPermission('admin.sellers.index') || hasPermission('admin.seller.create') || hasPermission('admin.sellers.pending'))
            <li class="sidebar-heading">Sellers</li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $sellersOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navSellers"
                    aria-expanded="{{ $sellersOpen ? 'true' : 'false' }}" aria-controls="navSellers">
                    <i data-lucide="store" class="nav-icon"></i>
                    <span>Manage Sellers</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navSellers" class="collapse {{ $sellersOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        @if (hasPermission('admin.sellers.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sellers.index') ? 'active' : '' }}"
                                href="{{ route('admin.sellers.index') }}">
                                <i data-lucide="list" class="nav-icon"></i>
                                <span>All Sellers</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission('admin.sellers.pending'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sellers.pending') ? 'active' : '' }}"
                                href="{{ route('admin.sellers.pending') }}">
                                <i data-lucide="clock" class="nav-icon"></i>
                                <span>Pending</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission('admin.sellers.create'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.sellers.create') ? 'active' : '' }}"
                                href="{{ route('admin.sellers.create') }}">
                                <i data-lucide="user-plus" class="nav-icon"></i>
                                <span>Add Seller</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            {{-- ═══ 6. ORDERS ═══ --}}
            <li class="sidebar-heading">Orders</li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $ordersOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navOrders"
                    aria-expanded="{{ $ordersOpen ? 'true' : 'false' }}" aria-controls="navOrders">
                    <i data-lucide="shopping-cart" class="nav-icon"></i>
                    <span>Manage Orders</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navOrders" class="collapse {{ $ordersOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"
                                href="{{ route('admin.orders.index') }}">
                                <i data-lucide="clipboard-list" class="nav-icon"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.returns.index') ? 'active' : '' }}"
                                href="{{ route('admin.returns.index') }}">
                                <i data-lucide="undo-2" class="nav-icon"></i>
                                <span>Returns &amp; Refunds</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.payments.index') ? 'active' : '' }}"
                                href="{{ route('admin.payments.index') }}">
                                <i data-lucide="credit-card" class="nav-icon"></i>
                                <span>Payments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.payouts.index') ? 'active' : '' }}"
                                href="{{ route('admin.payouts.index') }}">
                                <i data-lucide="banknote" class="nav-icon"></i>
                                <span>Seller Payouts</span>
                            </a>
                        </li>
                        @if (hasPermission('admin.reviews.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}"
                                href="{{ route('admin.reviews.index') }}">
                                <i data-lucide="star" class="nav-icon"></i>
                                <span>Reviews</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>

            {{-- ═══ 7. OPERATIONS ═══ --}}
            <li class="sidebar-heading">Operations</li>

            @if (hasPermission('admin.seller-performance.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.seller-performance.index') ? 'active' : '' }}"
                    href="{{ route('admin.seller-performance.index') }}"
                    @if (request()->routeIs('admin.seller-performance.index')) aria-current="page" @endif>
                    <i data-lucide="gauge" class="nav-icon"></i>
                    <span>Seller Performance</span>
                </a>
            </li>
            @endif

            @if (hasPermission('admin.support.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.support.index') ? 'active' : '' }}"
                    href="{{ route('admin.support.index') }}"
                    @if (request()->routeIs('admin.support.index')) aria-current="page" @endif>
                    <i data-lucide="headset" class="nav-icon"></i>
                    <span>Support Tickets</span>
                </a>
            </li>
            @endif

            @if (hasPermission('admin.shipping.carriers.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.shipping.carriers.index') ? 'active' : '' }}"
                    href="{{ route('admin.shipping.carriers.index') }}"
                    @if (request()->routeIs('admin.shipping.carriers.index')) aria-current="page" @endif>
                    <i data-lucide="truck" class="nav-icon"></i>
                    <span>Shipping Carriers</span>
                </a>
            </li>
            @endif

            {{-- ═══ 8. ADMINISTRATION ═══ --}}
            <li class="sidebar-heading">Administration</li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $membersOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navMembers"
                    aria-expanded="{{ $membersOpen ? 'true' : 'false' }}" aria-controls="navMembers">
                    <i data-lucide="users" class="nav-icon"></i>
                    <span>Manage Members</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navMembers" class="collapse {{ $membersOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        @if (hasPermission('admin.admins.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.admins.index') ? 'active' : '' }}"
                                href="{{ route('admin.admins.index') }}">
                                <i data-lucide="list" class="nav-icon"></i>
                                <span>Admin List</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission('admin.admins.create'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.admins.create') ? 'active' : '' }}"
                                href="{{ route('admin.admins.create') }}">
                                <i data-lucide="user-plus" class="nav-icon"></i>
                                <span>Add Admin</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>

            @if (hasPermission('admin.roles.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}"
                    href="{{ route('admin.roles.index') }}"
                    @if (request()->routeIs('admin.roles.index')) aria-current="page" @endif>
                    <i data-lucide="shield" class="nav-icon"></i>
                    <span>Permissions</span>
                </a>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $gatewaysOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navGateways"
                    aria-expanded="{{ $gatewaysOpen ? 'true' : 'false' }}" aria-controls="navGateways">
                    <i data-lucide="credit-card" class="nav-icon"></i>
                    <span>Payment Gateways</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navGateways" class="collapse {{ $gatewaysOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.paymentGateways.index') ? 'active' : '' }}"
                                href="{{ route('admin.paymentGateways.index') }}">
                                <i data-lucide="globe" class="nav-icon"></i>
                                <span>Payment Gateways</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.manualGateways.index') ? 'active' : '' }}"
                                href="{{ route('admin.manualGateways.index') }}">
                                <i data-lucide="wallet" class="nav-icon"></i>
                                <span>Manual Gateways</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $settingsOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navSettings"
                    aria-expanded="{{ $settingsOpen ? 'true' : 'false' }}" aria-controls="navSettings">
                    <i data-lucide="settings" class="nav-icon"></i>
                    <span>Settings</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navSettings" class="collapse {{ $settingsOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        @if (hasPermission('admin.settings.socialLinks.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.socialLinks.index') ? 'active' : '' }}"
                                href="{{ route('admin.settings.socialLinks.index') }}">
                                <i data-lucide="share-2" class="nav-icon"></i>
                                <span>Social Links</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission('admin.settings.paymentOptions.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.paymentOptions.index') ? 'active' : '' }}"
                                href="{{ route('admin.settings.paymentOptions.index') }}">
                                <i data-lucide="credit-card" class="nav-icon"></i>
                                <span>Payment Options</span>
                            </a>
                        </li>
                        @endif
                        @if (hasPermission('admin.settings.index'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}"
                                href="{{ route('admin.settings.index') }}">
                                <i data-lucide="sliders" class="nav-icon"></i>
                                <span>General</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.staticPages.index') ? 'active' : '' }}"
                                href="{{ route('admin.staticPages.index') }}">
                                <i data-lucide="file-text" class="nav-icon"></i>
                                <span>Static Pages</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.banners.index') ? 'active' : '' }}"
                                href="{{ route('admin.banners.index') }}">
                                <i data-lucide="image" class="nav-icon"></i>
                                <span>Banners</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $subscriptionsOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navSubscriptions"
                    aria-expanded="{{ $subscriptionsOpen ? 'true' : 'false' }}" aria-controls="navSubscriptions">
                    <i data-lucide="crown" class="nav-icon"></i>
                    <span>Subscriptions</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navSubscriptions" class="collapse {{ $subscriptionsOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.subscription-plans.index') ? 'active' : '' }}"
                                href="{{ route('admin.subscription-plans.index') }}">
                                <i data-lucide="clipboard-list" class="nav-icon"></i>
                                <span>Plans</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.subscriptions.index') ? 'active' : '' }}"
                                href="{{ route('admin.subscriptions.index') }}">
                                <i data-lucide="calendar" class="nav-icon"></i>
                                <span>Subscriptions</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @if (hasPermission('admin.images.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.images.index') ? 'active' : '' }}"
                    href="{{ route('admin.images.index') }}"
                    @if (request()->routeIs('admin.images.index')) aria-current="page" @endif>
                    <i data-lucide="image" class="nav-icon"></i>
                    <span>Image</span>
                </a>
            </li>
            @endif

        </ul>
    </div>
</nav>