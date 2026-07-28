<?php
$seller = seller();
$employee = employee();
$route = request()->route()->getName();
?>

<nav class="navbar-vertical navbar" id="sellerSidebar">
    <div class="nav-scroller">
        <a class="navbar-brand d-flex align-items-center gap-3 px-4 py-3 border-bottom" href="/"
            style="border-color: rgba(255, 255, 255, 0.08) !important;">
            <img src="{{ storage_url($seller->business_logo ?? $settings->logo) }}" alt="logo" class="sidebar-logo" />
        </a>

        <ul class="navbar-nav flex-column mb-0" id="sideNavbar">

            {{-- ═══ 1. MAIN ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">Main</div>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}"
                    href="{{ route('seller.dashboard') }}">
                    <i data-feather="home" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- ═══ 2. POS ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">Point of Sale</div>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navPos"
                    aria-expanded="{{ request()->routeIs('seller.pos.*') ? 'true' : 'false' }}" aria-controls="navPos">
                    <div class="d-flex align-items-center">
                        <i data-feather="shopping-cart" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>POS</span>
                    </div>
                    <i data-feather="chevron-right" class="chevron-icon" style="width: 16px; height: 16px;"></i>
                </a>
                <div id="navPos" class="collapse {{ request()->routeIs('seller.pos.*') ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.pos.index'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.pos.index') && !request()->routeIs('seller.pos.sales.*') ? 'active' : '' }}"
                                    href="{{ route('seller.pos.index') }}">
                                    <i data-feather="plus-circle" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>New Sale</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.pos.sales.index'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.pos.sales.*') ? 'active' : '' }}"
                                    href="{{ route('seller.pos.sales.index') }}">
                                    <i data-feather="list" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Sales History</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            {{-- ═══ 3. CATALOG ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">Catalog</div>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navProducts"
                    aria-expanded="{{ request()->routeIs('seller.products.*') || request()->routeIs('seller.stock.*') ? 'true' : 'false' }}" aria-controls="navProducts">
                    <div class="d-flex align-items-center">
                        <i data-feather="package" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Products</span>
                    </div>
                    <i data-feather="chevron-right" class="chevron-icon" style="width: 16px; height: 16px;"></i>
                </a>
                @php
                    $productMenuOpen = request()->routeIs('seller.products.*') || request()->routeIs('seller.stock.*') ? true : false;
                @endphp
                <div id="navProducts" class="collapse {{ $productMenuOpen ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.products.index'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.products.index') || request()->routeIs('seller.products.show') || request()->routeIs('seller.products.edit') ? 'active' : '' }}"
                                    href="{{ route('seller.products.index') }}">
                                    <i data-feather="database" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>All Products</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.products.create'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.products.create') ? 'active' : '' }}"
                                    href="{{ route('seller.products.create') }}">
                                    <i data-feather="plus" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Add Product</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.products.inventory'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.products.inventory') ? 'active' : '' }}"
                                    href="{{ route('seller.products.inventory') }}">
                                    <i data-feather="layers" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Inventory</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.stock.index'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.stock.*') ? 'active' : '' }}"
                                    href="{{ route('seller.stock.index') }}">
                                    <i data-feather="clock" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Stock History</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.products.printBarcode'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.products.printBarcode*') ? 'active' : '' }}"
                                    href="{{ route('seller.products.printBarcode') }}">
                                    <i data-feather="printer" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Print Barcode</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            {{-- ═══ 4. FLASH SALES ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">Promotions</div>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.flash-sales.*') ? 'active' : '' }}"
                    href="{{ route('seller.flash-sales.index') }}">
                    <i data-feather="zap" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                    <span>Flash Sales</span>
                </a>
            </li>

            {{-- ═══ 5. ORDERS ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">Orders</div>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navOrders"
                    aria-expanded="{{ request()->routeIs('seller.orders.*') ? 'true' : 'false' }}" aria-controls="navOrders">
                    <div class="d-flex align-items-center">
                        <i data-feather="shopping-bag" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Manage Orders</span>
                    </div>
                    <i data-feather="chevron-right" class="chevron-icon" style="width: 16px; height: 16px;"></i>
                </a>
                <div id="navOrders" class="collapse {{ request()->routeIs('seller.orders.*') ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.orders.index'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.index') }}">
                                    <i data-feather="list" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>All Orders</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.pending'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.orders.pending') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.pending') }}">
                                    <i data-feather="clock" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Pending</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.shipped'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.orders.shipped') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.shipped') }}">
                                    <i data-feather="truck" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Shipped</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.delivered'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.orders.delivered') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.delivered') }}">
                                    <i data-feather="check-circle" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Delivered</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.returned'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.orders.returned') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.returned') }}">
                                    <i data-feather="rotate-ccw" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Returned</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.refunded'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.orders.refunded') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.refunded') }}">
                                    <i data-feather="dollar-sign" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Refunded</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.orders.cancelled'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.orders.cancelled') ? 'active' : '' }}"
                                    href="{{ route('seller.orders.cancelled') }}">
                                    <i data-feather="x-circle" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Cancelled</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            {{-- ═══ 6. PEOPLE ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">People</div>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.customers') ? 'active' : '' }}"
                    href="{{ route('seller.customers') }}">
                    <i data-feather="users" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                    <span>Customers</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navEmployees"
                    aria-expanded="{{ request()->routeIs('seller.employees.*') ? 'true' : 'false' }}" aria-controls="navEmployees">
                    <div class="d-flex align-items-center">
                        <i data-feather="briefcase" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Employees</span>
                    </div>
                    <i data-feather="chevron-right" class="chevron-icon" style="width: 16px; height: 16px;"></i>
                </a>
                <div id="navEmployees" class="collapse {{ request()->routeIs('seller.employees.*') ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @if ($seller || $employee->hasPermission('seller.employees.index'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.employees.index') || request()->routeIs('seller.employees.edit') || request()->routeIs('seller.employees.profile') ? 'active' : '' }}"
                                    href="{{ route('seller.employees.index') }}">
                                    <i data-feather="list" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>All Employees</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.employees.create'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.employees.create') ? 'active' : '' }}"
                                    href="{{ route('seller.employees.create') }}">
                                    <i data-feather="user-plus" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Add Employee</span>
                                </a>
                            </li>
                        @endif
                        @if ($seller || $employee->hasPermission('seller.employees.salesReport'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.employees.salesReport') ? 'active' : '' }}"
                                    href="{{ route('seller.employees.salesReport') }}">
                                    <i data-feather="bar-chart" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                    <span>Sales Report</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            @if ($seller || $employee->hasPermission('seller.chat.list'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.chat.*') ? 'active' : '' }}"
                        href="{{ route('seller.chat.list') }}">
                        <i data-feather="message-circle" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Messages</span>
                    </a>
                </li>
            @endif

            {{-- ═══ 7. FINANCE ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">Finance</div>

            @if ($seller || $employee->hasPermission('seller.expenses.index'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.expenses.*') ? 'active' : '' }}"
                        href="{{ route('seller.expenses.index') }}">
                        <i data-feather="dollar-sign" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Expenses</span>
                    </a>
                </li>
            @endif

            {{-- ═══ 8. REPORTS ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">Reports</div>

            <li class="nav-item">
                <a class="nav-link has-arrow collapsed d-flex justify-content-between align-items-center" href="#!"
                    data-bs-toggle="collapse" data-bs-target="#navReports"
                    aria-expanded="{{ request()->routeIs('seller.reports.*') ? 'true' : 'false' }}" aria-controls="navReports">
                    <div class="d-flex align-items-center">
                        <i data-feather="bar-chart-2" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Analytics</span>
                    </div>
                    <i data-feather="chevron-right" class="chevron-icon" style="width: 16px; height: 16px;"></i>
                </a>
                <div id="navReports" class="collapse {{ request()->routeIs('seller.reports.*') ? 'show' : '' }}" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.reports.overview') ? 'active' : '' }}"
                                href="{{ route('seller.reports.overview') }}">
                                <i data-feather="trending-up" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                <span>Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.reports.financial') ? 'active' : '' }}"
                                href="{{ route('seller.reports.financial') }}">
                                <i data-feather="credit-card" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                <span>Financial</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.reports.sales') ? 'active' : '' }}"
                                href="{{ route('seller.reports.sales') }}">
                                <i data-feather="shopping-cart" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                <span>Sales</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.reports.customers') ? 'active' : '' }}"
                                href="{{ route('seller.reports.customers') }}">
                                <i data-feather="users" class="nav-icon me-2" style="width: 14px; height: 14px;"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ═══ 9. SYSTEM ═══ --}}
            <div class="sidebar-heading px-4 pt-3 pb-1 text-uppercase fw-semibold">System</div>

            @if ($seller || $employee->hasPermission('seller.settings.index'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.settings.*') ? 'active' : '' }}"
                        href="{{ route('seller.settings.index') }}">
                        <i data-feather="settings" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Settings</span>
                    </a>
                </li>
            @endif

            @if ($seller || $employee->hasPermission('seller.paymentListener.index'))
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.paymentListener.*') ? 'active' : '' }}"
                        href="{{ route('seller.paymentListener.index') }}">
                        <i data-feather="smartphone" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                        <span>Payment Checker</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('seller.plans.*') ? 'active' : '' }}"
                    href="{{ route('seller.plans.index') }}">
                    <i data-feather="trending-up" class="nav-icon me-3" style="width: 18px; height: 18px;"></i>
                    <span>Upgrade Plan</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
