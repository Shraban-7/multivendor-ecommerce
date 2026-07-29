<?php $notificationCount = notificationCount(); ?>

<nav class="navbar-classic flex flex-row items-center justify-between relative">
    <a id="nav-toggle" href="#" class="flex items-center justify-center shrink-0 rounded-xs hover:bg-surface-muted transition-colors" style="width: 36px; height: 36px;">
        <i data-lucide="menu" style="width: 20px; height: 20px; color: #454f5b;"></i>
    </a>

    <ul class="nav-top-wrap flex flex-row items-center gap-2 list-none mb-0 pl-0 ml-auto">
        <li class="relative">
            <a class="flex items-center justify-center relative rounded-xs hover:bg-surface-muted transition-colors"
                href="{{ route('seller.notifications.index') }}" style="width: 40px; height: 40px;">
                <i data-lucide="bell" style="width: 20px; height: 20px; color: #637381;"></i>
                @if ($notificationCount > 0)
                    <span class="absolute rounded-full text-center" style="top: 2px; right: 2px; font-size: 9px; line-height: 12px; padding: 2px 5px; min-width: 16px; background: #D93025; color: #fff;">
                        {{ $notificationCount }}
                    </span>
                @endif
            </a>
        </li>

        <li class="dropdown relative">
            <a class="flex items-center no-underline" href="#" role="button" id="dropdownUser"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div style="width: 36px; height: 36px; border: 2px solid #E5E5E5; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
                    <img alt="avatar" src="{{ storage_url(seller()->image ?? '') }}" class="w-full h-full object-cover" />
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; min-width: 220px;" aria-labelledby="dropdownUser">
                <div class="px-3 py-3 border-b" style="border-color: #E5E5E5;">
                    <div class="flex items-center gap-2">
                        <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
                            <img alt="avatar" src="{{ storage_url(seller()->image ?? '') }}" class="w-full h-full object-cover" />
                        </div>
                        <div>
                            <h6 class="mb-0 font-semibold" style="font-size: 14px;">{{ seller()->name ?? employee()->name }}</h6>
                            @if (auth('seller')->check())
                                <a href="{{ route('seller.profile') }}" class="text-xs text-ink-tertiary no-underline">View Profile</a>
                            @elseif (auth('employee')->check())
                                <a href="{{ route('seller.employees.profile') }}" class="text-xs text-ink-tertiary no-underline">View Profile</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="py-1">
                    <ul class="list-none mb-0 pl-0">
                        <li>
                            @if (auth('seller')->check())
                                <a class="dropdown-item py-2 flex items-center gap-2" href="{{ route('seller.profile') }}">
                                    <i data-lucide="settings" style="width: 16px; height: 16px;" class="text-ink-tertiary"></i>
                                    Account Settings
                                </a>
                            @elseif (auth('employee')->check())
                                <a class="dropdown-item py-2 flex items-center gap-2" href="{{ route('seller.employees.profile') }}">
                                    <i data-lucide="settings" style="width: 16px; height: 16px;" class="text-ink-tertiary"></i>
                                    Account Settings
                                </a>
                            @endif
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 flex items-center gap-2 w-full text-start">
                                    <i data-lucide="power" style="width: 16px; height: 16px;" class="text-ink-tertiary"></i>
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
