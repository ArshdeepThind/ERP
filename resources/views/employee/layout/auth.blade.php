<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ERP') }}</title>

    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('/images/favicon.ico') }}' />
    

    <!-- Styles -->
    <link href="/css/employee-app.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/plugins/iCheck/square/blue.css') }}">
    
    @yield('css')
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="hold-transition login-page">
    <div id="app">
         @yield('content')
    </div>

    
   <script src="https://www.gstatic.com/firebasejs/4.13.0/firebase.js"></script> 
    <script>
            
     var config = {
        apiKey: "key",
        authDomain: "domain",
        databaseURL: "url",
        projectId: "erpsystem",
        storageBucket: "erpsystem",
        messagingSenderId: ""
      };
      firebase.initializeApp(config);

        var database = firebase.database();    

    </script>   
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/admin-app.js"></script>
    <script src="/js/admin-main.js"></script>
    @yield('js')
</body>
</html>
