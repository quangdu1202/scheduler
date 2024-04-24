{{--@php(date_default_timezone_set('Asia/Ho_Chi_Minh'))--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script type="text/javascript" src="{{ asset('/js/jquery.js') }}"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2-bootstrap-5-theme.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/css/datatables.min.css') }}">

    <script
        src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/fh-4.0.1/r-3.0.0/sc-2.4.1/datatables.min.js">
    </script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="wrapper">

        {{-- Sidebar --}}
        <aside id="sidebar">
            @include('partials/sidebar')
        </aside>

        {{-- Main content --}}
        <div id="app" class="main px-5">
            <div class="h-100 right-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('/js/script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/form-utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/select2.min.js') }}"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="{{ asset('/js/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                "enableHtml": true,
                "closeButton": false,
                "debug": false,
                "positionClass": "toast-bottom-right",
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "progressBar": true,
            };

            $('form select').select2({
                theme: "bootstrap-5",
                placeholder: "Select an option",
            });
        });
    </script>
</body>

</html>
