<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">


    <link href="css/jquery.dataTables.css" rel="stylesheet" />

    <link href="/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Animation library for notifications   -->
    <!--<link href="https://watch-movies-community.net/css/animate.min.css" rel="stylesheet"/>-->
    <!--  Paper Dashboard core CSS    -->
    <link href="/css/paper-dashboard.css" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <!--<link href="https://watch-movies-community.net/css/demo.css" rel="stylesheet" />-->
    <!--  Fonts and icons     -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <!--<link href="https://watch-movies-community.net/css/themify-icons.css" rel="stylesheet">-->
    <!-- Scripts -->
  <script>
        window.Laravel = {!! json_encode([
           'csrfToken' => csrf_token(),
    ]) !!};
    </script>
    <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous"></script>



</head>
<body>

<div id="app">

    @yield('content')
</div>


<!-- Scripts -->


</body>



</html>

