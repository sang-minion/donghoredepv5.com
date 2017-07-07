<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('resources/assets/media/favicon.ico ')}}">
    <title>{{CGlobal::$title}}</title>
    {!! CGlobal::$extraMeta !!}
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link href="{{ asset('resources/assets/focus/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/focus/css/bootstrap.offcanvas.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/focus/css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/backend/css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/libs/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">

    <script type="text/javascript">
        var BASE_URL = "{{config('app.url')}}";
    </script>
    <script src="{{ asset('resources/assets/focus/js/app.js') }}"></script>
    <script src="{{ asset('resources/assets/focus/js/bootstrap.offcanvas.js') }}"></script>
    <script src="{{ asset('resources/assets/focus/js/reset.js') }}"></script>
    <script src="{{ asset('resources/assets/focus/js/focus.js') }}"></script>
    <script src="{{ asset('resources/assets/backend/js/home.js') }}"></script>
    <script src="{{ asset('resources/assets/backend/js/cart.js') }}"></script>

    {!! CGlobal::$extraHeaderCSS !!}
    {!! CGlobal::$extraHeaderJS !!}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="body-offcanvas">
<div id="app">
    <div class="container-fluid">
        <div class="row menu">
            @yield('menu')
        </div>
        <div class="row content">
            @yield('content')
        </div>
        <div class="row footer">
            @yield('footer')
        </div>
    </div>
</div>

<!-- Scripts -->
{!! CGlobal::$extraFooterCSS !!}
{!! CGlobal::$extraFooterJS !!}
</body>
</html>
