<?php
$seller = seller();
$employee = employee();

/** Employee guard: falls back to false when no employee is authenticated. */
$can = fn (string $permission) => $seller || $employee?->hasPermission($permission);

$productsOpen = request()->routeIs('seller.products.*')
    || request()->routeIs('seller.stock.*')
    || request()->routeIs('seller.bulk-upload.*')
    || request()->routeIs('seller.bundles.*');
$ordersOpen = request()->routeIs('seller.orders.*') || request()->routeIs('seller.returns.*');
$shippingOpen = request()->routeIs('seller.shipping.*');
$couponsOpen = request()->routeIs('seller.coupons.*');
$employeesOpen = request()->routeIs('seller.employees.*');
$payoutsOpen = request()->routeIs('seller.payouts.*');
$reportsOpen = request()->routeIs('seller.reports.*');
?>

<nav class="navbar-vertical navbar" id="sellerSidebar" aria-label="Seller navigation">
    <a class="navbar-brand" href="{{ route('seller.dashboard') }}">
        <img src="{{ storage_url($seller->business_logo ?? $settings->logo) }}" alt="logo" class="sidebar-logo" />
    </a>

    <div class="sidebar-scroll">
        <ul class="navbar-nav flex-col mb-0" id="sideNavbar">

            {{-- ═══ 1. MAIN ═══ --}}
            <li class="sidebar-heading">Main</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}"
                    href="{{ route('seller.dashboard') }}"
                    @if (request()->routeIs('seller.dashboard')) aria-current="page" @endif>
                    <i data-lucide="layout-dashboard" class="nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- ═══ 2. CATALOG ═══ --}}
            <li class="sidebar-heading">Catalog</li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $productsOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navProducts"
                    aria-expanded="{{ $productsOpen ? 'true' : 'false' }}" aria-controls="navProducts">
                    <i data-lucide="shopping-bag" class="nav-icon"></i>
                    <span>Products</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navProducts" class="collapse {{ $productsOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        @if ($can('seller.products.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.products.index') || request()->routeIs('seller.products.show') || request()->routeIs('seller.products.edit') ? 'active' : '' }}"
                                    href="{{ route('seller.products.index') }}">
                                    <i data-lucide="layout-list" class="nav-icon"></i>
                                    <span>All Products</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.products.create'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.products.create') ? 'active' : '' }}"
                                    href="{{ route('seller.products.create') }}">
                                    <i data-lucide="package-plus" class="nav-icon"></i>
                                    <span>Add Product</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.products.inventory'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.products.inventory') ? 'active' : '' }}"
                                    href="{{ route('seller.products.inventory') }}">
                                    <i data-lucide="warehouse" class="nav-icon"></i>
                                    <span>Inventory</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.stock.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.stock.*') ? 'active' : '' }}"
                                    href="{{ route('seller.stock.index') }}">
                                    <i data-lucide="history" class="nav-icon"></i>
                                    <span>Stock History</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.bulk-upload.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.bulk-upload.*') ? 'active' : '' }}"
                                    href="{{ route('seller.bulk-upload.index') }}">
                                    <i data-lucide="file-up" class="nav-icon"></i>
                                    <span>Bulk Upload</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.bundles.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.bundles.*') ? 'active' : '' }}"
                                    href="{{ route('seller.bundles.index') }}">
                                    <i data-lucide="boxes" class="nav-icon"></i>
                                    <span>Bundles</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.products.printBarcode'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.products.printBarcode*') ? 'active' : '' }}"
                                    href="{{ route('seller.products.printBarcode') }}">
                                    <i data-lucide="barcode" class="nav-icon"></i>
                                    <span>Print Barcode</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            @if ($can('seller.reviews.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.reviews.*') ? 'active' : '' }}"
                        href="{{ route('seller.reviews.index') }}"
                        @if (request()->routeIs('seller.reviews.*')) aria-current="page" @endif>
                        <i data-lucide="star" class="nav-icon"></i>
                        <span>Product Reviews</span>
                    </a>
                </li>
            @endif

            {{-- ═══ 3. SALES ═══ --}}
            <li class="sidebar-heading">Sales</li>

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
                        @if ($can('seller.orders.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.index') }}">
                                    <i data-lucide="clipboard-list" class="nav-icon"></i>
                                    <span>All Orders</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.orders.pending'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.orders.pending') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.pending') }}">
                                    <i data-lucide="timer" class="nav-icon"></i>
                                    <span>Pending</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.orders.shipped'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.orders.shipped') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.shipped') }}">
                                    <i data-lucide="package" class="nav-icon"></i>
                                    <span>Shipped</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.orders.delivered'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.orders.delivered') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.delivered') }}">
                                    <i data-lucide="package-check" class="nav-icon"></i>
                                    <span>Delivered</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.orders.returned'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.orders.returned') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.returned') }}">
                                    <i data-lucide="package-x" class="nav-icon"></i>
                                    <span>Returned</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.orders.refunded'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.orders.refunded') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.refunded') }}">
                                    <i data-lucide="hand-coins" class="nav-icon"></i>
                                    <span>Refunded</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.orders.cancelled'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.orders.cancelled') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.cancelled') }}">
                                    <i data-lucide="circle-x" class="nav-icon"></i>
                                    <span>Cancelled</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.returns.*') ? 'active' : '' }}"
                                href="{{ route('seller.returns.index') }}">
                                <i data-lucide="undo-2" class="nav-icon"></i>
                                <span>Returns &amp; Refunds</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $shippingOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navShipping"
                    aria-expanded="{{ $shippingOpen ? 'true' : 'false' }}" aria-controls="navShipping">
                    <i data-lucide="truck" class="nav-icon"></i>
                    <span>Shipping</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navShipping" class="collapse {{ $shippingOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.shipping.shipments*') ? 'active' : '' }}"
                                href="{{ route('seller.shipping.shipments') }}">
                                <i data-lucide="container" class="nav-icon"></i>
                                <span>Shipments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.shipping.zones*') ? 'active' : '' }}"
                                href="{{ route('seller.shipping.zones') }}">
                                <i data-lucide="map" class="nav-icon"></i>
                                <span>Zones</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ═══ 4. PROMOTIONS ═══ --}}
            <li class="sidebar-heading">Promotions</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('seller.flash-sales.*') ? 'active' : '' }}"
                    href="{{ route('seller.flash-sales.index') }}"
                    @if (request()->routeIs('seller.flash-sales.*')) aria-current="page" @endif>
                    <i data-lucide="zap" class="nav-icon"></i>
                    <span>Flash Sales</span>
                </a>
            </li>

            @if ($can('seller.coupons.index'))
                <li class="nav-item">
                    <a class="nav-link nav-link-toggle {{ $couponsOpen ? '' : 'collapsed' }}" href="#!"
                        data-bs-toggle="collapse" data-bs-target="#navCoupons"
                        aria-expanded="{{ $couponsOpen ? 'true' : 'false' }}" aria-controls="navCoupons">
                        <i data-lucide="ticket-percent" class="nav-icon"></i>
                        <span>Coupons</span>
                        <i data-lucide="chevron-right" class="chevron-icon"></i>
                    </a>
                    <div id="navCoupons" class="collapse {{ $couponsOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                        <ul class="nav flex-col">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.coupons.index') ? 'active' : '' }}"
                                    href="{{ route('seller.coupons.index') }}">
                                    <i data-lucide="tickets" class="nav-icon"></i>
                                    <span>All Coupons</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.coupons.create') ? 'active' : '' }}"
                                    href="{{ route('seller.coupons.create') }}">
                                    <i data-lucide="ticket-plus" class="nav-icon"></i>
                                    <span>Create Coupon</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.coupons.analytics') ? 'active' : '' }}"
                                    href="{{ route('seller.coupons.analytics') }}">
                                    <i data-lucide="chart-pie" class="nav-icon"></i>
                                    <span>Analytics</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- ═══ 5. PEOPLE ═══ --}}
            <li class="sidebar-heading">People</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('seller.customers') ? 'active' : '' }}"
                    href="{{ route('seller.customers') }}"
                    @if (request()->routeIs('seller.customers')) aria-current="page" @endif>
                    <i data-lucide="users" class="nav-icon"></i>
                    <span>Customers</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $employeesOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navEmployees"
                    aria-expanded="{{ $employeesOpen ? 'true' : 'false' }}" aria-controls="navEmployees">
                    <i data-lucide="id-card" class="nav-icon"></i>
                    <span>Employees</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navEmployees" class="collapse {{ $employeesOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        @if ($can('seller.employees.index'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.employees.index') || request()->routeIs('seller.employees.edit') || request()->routeIs('seller.employees.profile') ? 'active' : '' }}"
                                    href="{{ route('seller.employees.index') }}">
                                    <i data-lucide="contact" class="nav-icon"></i>
                                    <span>All Employees</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.employees.create'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.employees.create') ? 'active' : '' }}"
                                    href="{{ route('seller.employees.create') }}">
                                    <i data-lucide="user-plus" class="nav-icon"></i>
                                    <span>Add Employee</span>
                                </a>
                            </li>
                        @endif
                        @if ($can('seller.employees.salesReport'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.employees.salesReport') ? 'active' : '' }}"
                                    href="{{ route('seller.employees.salesReport') }}">
                                    <i data-lucide="file-chart-column" class="nav-icon"></i>
                                    <span>Sales Report</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            @if ($can('seller.chat.list'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.chat.*') ? 'active' : '' }}"
                        href="{{ route('seller.chat.list') }}"
                        @if (request()->routeIs('seller.chat.*')) aria-current="page" @endif>
                        <i data-lucide="messages-square" class="nav-icon"></i>
                        <span>Messages</span>
                    </a>
                </li>
            @endif

            {{-- ═══ 6. FINANCE ═══ --}}
            <li class="sidebar-heading">Finance</li>

            @if ($can('seller.expenses.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.expenses.*') ? 'active' : '' }}"
                        href="{{ route('seller.expenses.index') }}"
                        @if (request()->routeIs('seller.expenses.*')) aria-current="page" @endif>
                        <i data-lucide="receipt" class="nav-icon"></i>
                        <span>Expenses</span>
                    </a>
                </li>
            @endif

            @if ($can('seller.payouts.index'))
                <li class="nav-item">
                    <a class="nav-link nav-link-toggle {{ $payoutsOpen ? '' : 'collapsed' }}" href="#!"
                        data-bs-toggle="collapse" data-bs-target="#navPayouts"
                        aria-expanded="{{ $payoutsOpen ? 'true' : 'false' }}" aria-controls="navPayouts">
                        <i data-lucide="banknote" class="nav-icon"></i>
                        <span>Payouts</span>
                        <i data-lucide="chevron-right" class="chevron-icon"></i>
                    </a>
                    <div id="navPayouts" class="collapse {{ $payoutsOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                        <ul class="nav flex-col">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.payouts.index') ? 'active' : '' }}"
                                    href="{{ route('seller.payouts.index') }}">
                                    <i data-lucide="history" class="nav-icon"></i>
                                    <span>Payout History</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.payouts.create') ? 'active' : '' }}"
                                    href="{{ route('seller.payouts.create') }}">
                                    <i data-lucide="send" class="nav-icon"></i>
                                    <span>Request Payout</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('seller.payouts.methods.*') ? 'active' : '' }}"
                                    href="{{ route('seller.payouts.methods.index') }}">
                                    <i data-lucide="wallet" class="nav-icon"></i>
                                    <span>Payment Methods</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- ═══ 7. INSIGHTS ═══ --}}
            <li class="sidebar-heading">Insights</li>

            @if ($can('seller.performance.dashboard'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.performance.*') ? 'active' : '' }}"
                        href="{{ route('seller.performance.dashboard') }}"
                        @if (request()->routeIs('seller.performance.*')) aria-current="page" @endif>
                        <i data-lucide="gauge" class="nav-icon"></i>
                        <span>Performance</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link nav-link-toggle {{ $reportsOpen ? '' : 'collapsed' }}" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navReports"
                    aria-expanded="{{ $reportsOpen ? 'true' : 'false' }}" aria-controls="navReports">
                    <i data-lucide="chart-column" class="nav-icon"></i>
                    <span>Analytics</span>
                    <i data-lucide="chevron-right" class="chevron-icon"></i>
                </a>
                <div id="navReports" class="collapse {{ $reportsOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-col">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.reports.overview') ? 'active' : '' }}"
                                href="{{ route('seller.reports.overview') }}">
                                <i data-lucide="chart-no-axes-combined" class="nav-icon"></i>
                                <span>Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.reports.financial') ? 'active' : '' }}"
                                href="{{ route('seller.reports.financial') }}">
                                <i data-lucide="circle-dollar-sign" class="nav-icon"></i>
                                <span>Financial</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.reports.sales') ? 'active' : '' }}"
                                href="{{ route('seller.reports.sales') }}">
                                <i data-lucide="chart-line" class="nav-icon"></i>
                                <span>Sales</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('seller.reports.customers') ? 'active' : '' }}"
                                href="{{ route('seller.reports.customers') }}">
                                <i data-lucide="user-round-search" class="nav-icon"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ═══ 8. SYSTEM ═══ --}}
            <li class="sidebar-heading">System</li>

            @if ($can('seller.settings.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.settings.*') ? 'active' : '' }}"
                        href="{{ route('seller.settings.index') }}"
                        @if (request()->routeIs('seller.settings.*')) aria-current="page" @endif>
                        <i data-lucide="settings" class="nav-icon"></i>
                        <span>Settings</span>
                    </a>
                </li>
            @endif

            @if ($can('seller.paymentListener.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.paymentListener.*') ? 'active' : '' }}"
                        href="{{ route('seller.paymentListener.index') }}"
                        @if (request()->routeIs('seller.paymentListener.*')) aria-current="page" @endif>
                        <i data-lucide="scan-line" class="nav-icon"></i>
                        <span>Payment Checker</span>
                    </a>
                </li>
            @endif

            @if ($can('seller.support.index'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('seller.support.*') ? 'active' : '' }}"
                        href="{{ route('seller.support.index') }}"
                        @if (request()->routeIs('seller.support.*')) aria-current="page" @endif>
                        <i data-lucide="headset" class="nav-icon"></i>
                        <span>Support</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('seller.plans.*') ? 'active' : '' }}"
                    href="{{ route('seller.plans.index') }}"
                    @if (request()->routeIs('seller.plans.*')) aria-current="page" @endif>
                    <i data-lucide="crown" class="nav-icon"></i>
                    <span>Upgrade Plan</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
