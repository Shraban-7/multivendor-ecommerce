<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/frontend/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/css/theme.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/libs/bootstrap-icons/font/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dashboard/libs/dropzone/dist/dropzone.css')}}">

    <title>@yield('title')</title>
</head>

<body class="bg-light">
    <main class="container d-flex flex-column">
        <div class="row align-items-center justify-content-center g-0 min-vh-100">
            <div class="col-12 col-md-8 col-lg-6 col-xxl-4 py-8 py-xl-0">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/frontend/images/logo.png') }}" alt="Logo" style="max-height: 48px;" onerror="this.style.display='none'">
                </div>
                @yield('content')
            </div>
        </div>
    </main>

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
    @stack('footer')
</body>

</html>
