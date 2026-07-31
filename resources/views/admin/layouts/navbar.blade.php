<nav class="navbar-classic flex flex-row items-center justify-between relative">
    <a id="nav-toggle" href="#" class="flex items-center justify-center shrink-0 rounded-xs hover:bg-surface-muted transition-colors" style="width: 36px; height: 36px;">
        <i data-lucide="menu" style="width: 20px; height: 20px; color: #454f5b;"></i>
    </a>

    <ul class="nav-top-wrap flex flex-row items-center gap-2 list-none mb-0 pl-0 ml-auto">
        <li class="dropdown relative">
            <a class="flex items-center justify-center relative rounded-xs hover:bg-surface-muted transition-colors"
                href="#" role="button" id="dropdownNotification" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" style="width: 40px; height: 40px;">
                <i data-lucide="bell" style="width: 20px; height: 20px; color: #637381;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2"
                style="border-radius: 12px; min-width: 320px;" aria-labelledby="dropdownNotification">
                <div class="border-b px-3 pt-2 pb-3 flex justify-between items-center" style="border-color: #E5E5E5;">
                    <p class="mb-0 text-ink font-medium text-xl">Notifications</p>
                    <a href="#" class="text-ink-tertiary">
                        <span>
                            <i class="me-1 icon-xxs" data-lucide="settings"></i>
                        </span>
                    </a>
                </div>
                <ul class="flex flex-col notification-list-scroll">
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
                <div class="border-t px-3 py-2 text-center" style="border-color: #E5E5E5;">
                    <a href="#" class="text-ink font-semibold">
                        View all Notifications
                    </a>
                </div>
            </div>
        </li>

        <li class="dropdown relative">
            <a class="flex items-center no-underline" href="#" role="button" id="dropdownUser"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="flex items-center justify-center"
                    style="width: 36px; height: 36px; border: 2px solid #E5E5E5; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: #F5F5F5;">
                    <span style="font-size: 14px; font-weight: 600; color: #454f5b;">{{ mb_strtoupper(mb_substr(admin()->name ?? 'A', 0, 1)) }}</span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2"
                style="border-radius: 12px; min-width: 220px;" aria-labelledby="dropdownUser">
                <div class="px-3 py-3 border-b" style="border-color: #E5E5E5;">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center"
                            style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0; background: #F5F5F5;">
                            <span style="font-size: 14px; font-weight: 600; color: #454f5b;">{{ mb_strtoupper(mb_substr(admin()->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div>
                            <h6 class="mb-0 font-semibold" style="font-size: 14px;">{{ admin()->name }}</h6>
                            <a href="{{ route('admin.profile') }}" class="text-xs text-ink-tertiary no-underline">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="py-1">
                    <ul class="list-none mb-0 pl-0">
                        <li>
                            <a class="dropdown-item py-2 flex items-center gap-2" href="{{ route('admin.profile') }}">
                                <i data-lucide="settings" style="width: 16px; height: 16px;" class="text-ink-tertiary"></i>
                                Account Settings
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
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
