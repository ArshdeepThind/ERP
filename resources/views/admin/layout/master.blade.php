<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Admin') }}</title>

    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('/images/favicon.ico') }}' />
    <link href="/css/admin-app.css" rel="stylesheet">

    
    <link rel="stylesheet" href="{{ asset('/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/intl-tel-input/build/css/intlTelInput.css') }}">
    @yield('css')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>

    </script>
</head>
<body class="skin sidebar-mini">
    <div class="wrapper">
        @include('admin.includes.headerATL')
        @include('admin.includes.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <div id="loader_logo" style="background-image: url('/images/loader_logo.gif');"></div>
    </div>
    
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/admin-app.js"></script>
    <script src="/js/admin-main.js"></script>
   
    @yield('js')
</body>
</html>
