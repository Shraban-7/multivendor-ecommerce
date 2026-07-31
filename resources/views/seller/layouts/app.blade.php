<?php $settings = settings(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">

    {{-- Tailwind CSS CDN — Phase 2 setup (Bootstrap CSS retained until Phase 4) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            // Tailwind's `.collapse` utility sets `visibility: collapse`, which
            // hides Bootstrap sidebar submenus even when they have `.show`.
            blocklist: ['collapse'],
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Noto Sans"', '-apple-system', 'BlinkMacSystemFont', 'Roboto', '"Helvetica Neue"', 'Arial', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#F85606',
                            deep: '#C43D00',
                            tint: '#FFF1EA'
                        },
                        ink: {
                            DEFAULT: '#191919',
                            secondary: '#595959',
                            tertiary: '#767676'
                        },
                        surface: {
                            muted: '#F5F5F5',
                            strong: '#191919'
                        },
                        border: {
                            DEFAULT: '#E5E5E5',
                            strong: '#C7C7C7'
                        },
                        feedback: {
                            success: '#1D8A45',
                            danger: '#D93025',
                            warning: '#B7791A',
                            info: '#0F6FC5'
                        }
                    },
                    borderRadius: {
                        xs: '4px',
                        sm: '8px',
                        md: '12px'
                    }
                }
            }
        }
    </script>
    {{-- End Tailwind setup --}}

    {{-- Bootstrap theme CSS retained — provides sidebar, navbar, layout, and component styling --}}
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/libs/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/libs/dropzone/dist/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset_versioned('assets/dashboard/css/custom.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Datatable -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/libs/data-table/datatables.min.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    @stack('styles')

    <title>@yield('title')</title>
</head>

<body>
    <div class="container-scroller">
        <div id="db-wrapper">
            @include('seller.layouts.sidebar')
            <div id="sidebarBackdrop" aria-hidden="true"></div>
            <div id="page-content">
                <div class="header">
                    @include('seller.layouts.navbar')
                </div>

                <div class="page-inner-content px-4 py-3">
                    @if(View::hasSection('container-fluid'))
                    <div class="w-full px-0">
                        @yield('container-fluid')
                    </div>
                    @else
                    <div class="w-full px-0">
                        @yield('content')
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-custom-toastr/>

    <script src="{{ asset('assets/dashboard/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

    {{-- Lucide icons (Feather's successor). Must load before theme.min.js, which
         calls feather.replace() inline — the shim below routes that to Lucide. --}}
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        (function () {
            // Lucide renamed many icons it inherited from Feather ("shape first":
            // XCircle -> CircleX). Pre-1.0 builds ship only the legacy names,
            // 1.0+ dropped them. Mapping both directions means the panel renders
            // correctly whichever version the CDN serves.
            var EQUIVALENTS = [
                ['check-circle', 'circle-check'],
                ['x-circle', 'circle-x'],
                ['alert-circle', 'circle-alert'],
                ['alert-triangle', 'triangle-alert'],
                ['more-horizontal', 'ellipsis'],
                ['more-vertical', 'ellipsis-vertical'],
                ['upload-cloud', 'cloud-upload'],
                ['pie-chart', 'chart-pie'],
                ['bar-chart', 'chart-column'],
                ['bar-chart-2', 'chart-no-axes-column'],
                ['external-link', 'square-arrow-out-up-right'],
                ['arrow-up-circle', 'circle-arrow-up'],
                ['home', 'house'],
                ['edit', 'pencil'],
                ['trash', 'trash-2'],
                ['grid', 'grid-3x3'],
                ['columns', 'columns-2'],
                ['loader', 'loader-circle'],
                ['rotate-ccw', 'undo-2'],
                ['file-text', 'receipt'],
                ['layers', 'boxes']
            ];

            // Newer Lucide names → older safe substitutes (one-way only).
            var LEGACY = {
                'layout-dashboard': 'layout-grid',
                'layout-list': 'list',
                'package-plus': 'plus',
                'package-check': 'check-circle',
                'package-x': 'x-circle',
                'warehouse': 'boxes',
                'history': 'clock',
                'file-up': 'upload',
                'barcode': 'scan',
                'timer': 'clock',
                'hand-coins': 'dollar-sign',
                'ship': 'truck',
                'container': 'package',
                'map': 'map-pin',
                'ticket-percent': 'tag',
                'ticket-plus': 'plus',
                'tickets': 'tag',
                'chart-pie': 'pie-chart',
                'id-card': 'briefcase',
                'contact': 'users',
                'file-chart-column': 'bar-chart-2',
                'messages-square': 'message-circle',
                'banknote': 'dollar-sign',
                'gauge': 'activity',
                'chart-no-axes-combined': 'bar-chart-2',
                'circle-dollar-sign': 'dollar-sign',
                'chart-line': 'trending-up',
                'user-round-search': 'users',
                'scan-line': 'smartphone',
                'headset': 'life-buoy',
                'crown': 'award',
                'layout-grid': 'grid'
            };

            var FALLBACKS = {};
            EQUIVALENTS.forEach(function (pair) {
                FALLBACKS[pair[0]] = pair[1];
                FALLBACKS[pair[1]] = pair[0];
            });
            Object.keys(LEGACY).forEach(function (key) {
                FALLBACKS[key] = LEGACY[key];
            });

            function toPascal(name) {
                return name.replace(/(^[a-z0-9]|-[a-z0-9])/g, function (m) {
                    return m.replace('-', '').toUpperCase();
                });
            }

            function resolveName(name, available) {
                if (available[toPascal(name)]) {
                    return name;
                }

                var seen = {};
                var current = name;

                while (FALLBACKS[current] && !seen[current]) {
                    seen[current] = true;
                    current = FALLBACKS[current];

                    if (available[toPascal(current)]) {
                        return current;
                    }
                }

                return null;
            }

            function renderIcons(root) {
                if (!window.lucide) {
                    return;
                }

                var available = window.lucide.icons || window.lucide;
                var scope = root && root.querySelectorAll ? root : document;
                var unresolved = [];

                scope.querySelectorAll('[data-lucide]').forEach(function (el) {
                    var name = el.getAttribute('data-lucide');
                    var resolved = resolveName(name, available);

                    if (!resolved) {
                        unresolved.push(name);
                        return;
                    }

                    if (resolved !== name) {
                        el.setAttribute('data-lucide', resolved);
                    }
                });

                if (unresolved.length && window.console) {
                    console.warn('[icons] no Lucide match for:', unresolved.join(', '));
                }

                window.lucide.createIcons();
            }

            window.renderIcons = renderIcons;

            // Legacy shim: theme.min.js and leftover call sites still invoke
            // feather.replace(). Route those calls to Lucide.
            window.feather = { replace: renderIcons };

            document.addEventListener('DOMContentLoaded', function () {
                renderIcons();
            });
        })();
    </script>

    <script src="{{ asset('assets/dashboard/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/prismjs/plugins/toolbar/prism-toolbar.min.js') }}"></script>

    <!-- Theme JS -->
    <script src="{{ asset('assets/dashboard/js/theme.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Datatable -->
    <script src="{{ asset('assets/dashboard/libs/data-table/datatables.min.js') }} "></script>

    <!-- chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    @stack('scripts')
    <script>
        $(document).ready(function() {
            $(document).on("click", ".image-preview", function() {
                $(this).closest(".form-group").find(".file-input").click();
            });

            $(document).on("change", ".file-input", function(event) {
                let input = $(this);
                let file = event.target.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let previewDiv = input.closest(".form-group").find(".image-preview");
                        previewDiv.html(
                            `<img src="${e.target.result}" class="max-w-full h-auto" style="max-width: 100%; max-height: 100%;">`
                        );
                        input.closest(".form-group").find(".remove-image").removeClass("d-none");
                    };
                    reader.readAsDataURL(file);
                }
            });
            $(document).on("click", ".remove-image", function() {
                let formGroup = $(this).closest(".form-group");
                formGroup.find(".image-preview").html(`<span class="text-ink-tertiary">Click to Upload</span>`);
                formGroup.find(".file-input").val("");
                $(this).addClass("d-none");
            });
        });
    </script>
    <script>
        (function () {
            var wrapper = document.getElementById('db-wrapper');
            var backdrop = document.getElementById('sidebarBackdrop');

            function closeSidebar() {
                if (wrapper) {
                    wrapper.classList.remove('toggled');
                }
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            // Bootstrap swaps aria-expanded on collapse, so redraw the chevrons.
            document.querySelectorAll('#sideNavbar .collapse').forEach(function (panel) {
                panel.addEventListener('shown.bs.collapse', function () {
                    window.renderIcons && window.renderIcons();
                });
                panel.addEventListener('hidden.bs.collapse', function () {
                    window.renderIcons && window.renderIcons();
                });
            });
        })();
    </script>

    @stack('modals')
</body>

</html>
