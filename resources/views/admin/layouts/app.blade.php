<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/frontend/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/css/theme.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/libs/bootstrap-icons/font/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/libs/dropzone/dist/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/css/custom.css')}}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Datatable -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/libs/data-table/datatables.min.css') }}" >


    <!-- <link href="assets/dashboard/libs/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet" />
        <link href="assets/dashboard/libs/prismjs/themes/okaidia.css" rel="stylesheet"> -->

    <title>@yield('title')</title>
</head>

<body>
    <div class="container-scroller">
        <div id="db-wrapper">
            @include('admin.layouts.sidebar')
            <div id="page-content">
                <div class="header">
                    @include('admin.layouts.navbar')
                </div>
                <div class="container-fluid my-3 px-sm-4">
                    <x-flash-message />
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/dashboard/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script src="{{ asset('assets/dashboard/libs/prismjs/prism.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/libs/prismjs/plugins/toolbar/prism-toolbar.min.js') }}"></script>

    <!-- <script src="./assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="./assets/libs/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script> -->
    <!-- Theme JS -->
    <script src="{{ asset('assets/dashboard/js/theme.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/feather.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Datatable -->
    <script src="{{ asset('assets/dashboard/libs/data-table/datatables.min.js') }} "></script>
    @stack('footer')
    @stack('scripts')
</body>

</html>
