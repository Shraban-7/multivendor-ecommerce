<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/frontend/images/favicon.ico') }}">

    {{-- Tailwind CSS CDN — Phase 2 setup (Bootstrap CSS retained until Phase 4) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
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

    <link rel="stylesheet" href="{{asset('assets/dashboard/css/theme.css')}}">
    <link rel="stylesheet" href="{{asset_versioned('assets/dashboard/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/libs/bootstrap-icons/font/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/libs/dropzone/dist/dropzone.css')}}">

    <title>@yield('title')</title>
</head>

<body>
    <main class="container flex flex-col">
        <div class="grid grid-cols-1 items-center justify-center g-0 min-vh-full">
            <div class="col-span-full md:col-span-2 lg:col-span-1 2xl:col-span-4 py-8 py-xl-0">
                @yield('content')
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/dashboard/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

    {{-- Lucide icons. theme.min.js still calls feather.replace(); the shim below absorbs it. --}}
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        (function () {
            function renderIcons() {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }

            window.renderIcons = renderIcons;
            window.feather = { replace: renderIcons };

            document.addEventListener('DOMContentLoaded', renderIcons);
        })();
    </script>

    <script src="{{ asset('assets/dashboard/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/prismjs/plugins/toolbar/prism-toolbar.min.js') }}"></script>
    
    <!-- Theme JS -->
    <script src="{{ asset('assets/dashboard/js/theme.min.js') }}"></script>
    @stack('footer')
</body>

</html>