<nav class="navbar-classic navbar navbar-expand-lg">
    <a id="nav-toggle" href="#"><i
            data-lucide="menu"
            class="nav-icon me-2 icon-xs"></i></a>
    <div class="ms-lg-3 d-none d-md-none d-lg-block">
        <form class="flex items-center">
            <input type="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search" />
        </form>
    </div>
    <ul class="navbar-nav navbar-right-wrap ms-auto flex nav-top-wrap">
        <li class="dropdown stopevent">
            <a class="btn btn-light btn-icon btn-round indicator indicator-primary" href="#" role="button"
                id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="icon-xs" data-lucide="bell"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end"
                aria-labelledby="dropdownNotification">
                <div>
                    <div class="border-b px-3 pt-2 pb-3 flex
                        justify-between items-center">
                        <p class="mb-0 text-ink font-medium text-xl">Notifications</p>
                        <a href="#" class="text-ink-tertiary">
                            <span>
                                <i class="me-1 icon-xxs" data-lucide="settings"></i>
                            </span>
                        </a>
                    </div>
                    <ul class="flex flex-col  notification-list-scroll">
                        <li class="flex items-center px-0 py-2 border-b border-border bg-surface-muted">
                            <a href="#" class="text-ink-tertiary">
                                <h5 class=" mb-1">Rishi Chopra</h5>
                                <p class="mb-0">
                                    Mauris blandit erat id nunc blandit, ac eleifend dolor pretium.
                                </p>
                            </a>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border">
                            <a href="#" class="text-ink-tertiary">
                                <h5 class=" mb-1">Neha Kannned</h5>
                                <p class="mb-0">
                                    Proin at elit vel est condimentum elementum id in ante. Maecenas et sapien metus.
                                </p>
                            </a>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border">
                            <a href="#" class="text-ink-tertiary">
                                <h5 class=" mb-1">Nirmala Chauhan</h5>
                                <p class="mb-0">
                                    Morbi maximus urna lobortis elit sollicitudin sollicitudieget elit vel pretium.
                                </p>
                            </a>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border">
                            <a href="#" class="text-ink-tertiary">
                                <h5 class=" mb-1">Sina Ray</h5>
                                <p class="mb-0">
                                    Sed aliquam augue sit amet mauris volutpat hendrerit sed nunc eu diam.
                                </p>
                            </a>
                        </li>
                    </ul>
                    <div class="border-t px-3 py-2 text-center">
                        <a href="#" class="text-ink font-semibold">
                            View all Notifications
                        </a>
                    </div>
                </div>
            </div>
        </li>
        <li class="dropdown ms-2">
            <a class="rounded-full" href="#" role="button" id="dropdownUser"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-md avatar-indicators avatar-online">
                    <img alt="avatar" src="{{ asset('assets/frontend/images/hero-image-1.png') }}"
                        class="rounded-full" />
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end"
                aria-labelledby="dropdownUser">
                <div class="px-4 pb-0 pt-2">
                    <div class="leading-none ">
                        <h5 class="mb-1">Name</h5>
                        <a href="#" class="text-ink text-sm">Profile</a>
                    </div>
                    <div class=" dropdown-divider mt-3 mb-2"></div>
                </div>
                <ul class="list-none">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="me-2 icon-xxs dropdown-item-icon"
                                data-lucide="settings"></i>Account Settings
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @CSRF
                            <button class="dropdown-item" href="javascript:void(0)" onclick="this.form.submit();">
                                <i class="me-2 icon-xxs dropdown-item-icon"
                                    data-lucide="power"></i>Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
