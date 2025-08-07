<nav class="navbar-classic navbar navbar-expand-lg">
    <a id="nav-toggle" href="#"><i data-feather="menu" class="nav-icon me-2 icon-xs"></i></a>
    <div class="ms-lg-3 d-none d-md-none d-lg-block">
        <form class="d-flex align-items-center">
            <input type="search" class="form-control" placeholder="Search" />
        </form>
    </div>
    <ul class="navbar-nav navbar-right-wrap ms-auto d-flex align-items-center gap-3 nav-top-wrap">
        <li class="dropdown stopevent position-relative">
            <a class="indicator indicator-primary text-muted position-relative" href="{{ route('seller.notifications.index') }}">
                <i class="icon-xs" data-feather="bell"></i>
                @if (notificationCount(auth('seller')->id()) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 10px;">
                        {{ notificationCount(auth('seller')->id()) }}
                    </span>
                @endif
            </a>
        </li>

        <li class="dropdown">
            <a class="rounded-circle d-flex align-items-center" href="#" role="button" id="dropdownUser"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-md avatar-indicators avatar-online">
                    <img alt="avatar" src="{{ storage_url(seller()->image) }}" class="rounded-circle" />
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                <div class="px-4 pb-0 pt-2">
                    <div class="lh-1">
                        <h5 class="mb-1">{{ seller()->name }}</h5>
                        <a href="{{ route('seller.profile', seller()->username) }}"
                            class="text-inherit fs-6">Profile</a>
                    </div>
                    <div class="dropdown-divider mt-3 mb-2"></div>
                </div>
                <ul class="list-unstyled">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="settings"></i>Account Settings
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('seller.logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" onclick="this.form.submit();">
                                <i class="me-2 icon-xxs dropdown-item-icon" data-feather="power"></i>Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </li>

    </ul>

</nav>
