<?php $notificationCount = notificationCount(); ?>

<nav class="navbar-classic navbar navbar-expand-lg">
    <a id="nav-toggle" href="#">
        <i data-feather="menu" class="nav-icon me-2" style="width: 20px; height: 20px;"></i>
    </a>

    <ul class="navbar-nav navbar-right-wrap ms-auto d-flex align-items-center gap-2 nav-top-wrap">
        <li class="nav-item">
            <a href="{{ route('seller.pos.index') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1 px-3">
                <i class="bi bi-cart"></i>
                <span>POS</span>
            </a>
        </li>

        <li class="nav-item position-relative">
            <a class="d-flex align-items-center justify-content-center position-relative p-1"
                href="{{ route('seller.notifications.index') }}" style="width: 40px; height: 40px;">
                <i data-feather="bell" style="width: 20px; height: 20px; color: #637381;"></i>
                @if ($notificationCount > 0)
                    <span class="position-absolute badge rounded-pill" style="top: 2px; right: 2px; font-size: 9px; padding: 2px 5px; min-width: 16px; background: #D93025; color: #fff;">
                        {{$notificationCount }}
                    </span>
                @endif
            </a>
        </li>

        <li class="nav-item dropdown ms-1">
            <a class="rounded-circle d-flex align-items-center text-decoration-none" href="#" role="button" id="dropdownUser"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-md" style="width: 36px; height: 36px; border: 2px solid #E5E5E5; border-radius: 50%; overflow: hidden;">
                    <img alt="avatar" src="{{ storage_url(seller()->image ?? '') }}" class="w-100 h-100 object-fit-cover" />
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; min-width: 220px;" aria-labelledby="dropdownUser">
                <div class="px-3 py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
                            <img alt="avatar" src="{{ storage_url(seller()->image ?? '') }}" class="w-100 h-100 object-fit-cover" />
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold" style="font-size: 14px;">{{ seller()->name ?? employee()->name }}</h6>
                            @if (auth('seller')->check())
                                <a href="{{ route('seller.profile')}}" class="small text-muted text-decoration-none">View Profile</a>
                            @elseif (auth('employee')->check())
                                <a href="{{ route('seller.employees.profile') }}" class="small text-muted text-decoration-none">View Profile</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="py-1">
                    <ul class="list-unstyled mb-0">
                        <li>
                            @if (auth('seller')->check())
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('seller.profile') }}">
                                    <i data-feather="settings" style="width: 16px; height: 16px;" class="text-muted"></i>
                                    Account Settings
                                </a>
                            @elseif (auth('employee')->check())
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('seller.employees.profile') }}">
                                    <i data-feather="settings" style="width: 16px; height: 16px;" class="text-muted"></i>
                                    Account Settings
                                </a>
                            @endif
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item py-2 d-flex align-items-center gap-2" onclick="this.form.submit();">
                                    <i data-feather="power" style="width: 16px; height: 16px;" class="text-muted"></i>
                                    Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</nav>
