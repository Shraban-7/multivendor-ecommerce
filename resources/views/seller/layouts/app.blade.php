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

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script src="{{ asset('assets/dashboard/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/prismjs/plugins/toolbar/prism-toolbar.min.js') }}"></script>

    <!-- Theme JS -->
    <script src="{{ asset('assets/dashboard/js/theme.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/feather.js') }}"></script>
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
</body>

</html>
