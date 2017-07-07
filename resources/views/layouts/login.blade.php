<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 03/06/2017
 * Time: 8:46 SA
 */
?>

        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Đăng nhập</title>
    <link rel="shortcut icon" href="{{asset('resources/assets/media/favicon.ico')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link href="{{ asset('resources/assets/focus/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/focus/css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/libs/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="{{ asset('resources/assets/focus/js/app.js') }}"></script>
    <script src="{{ asset('resources/assets/focus/js/reset.js') }}"></script>
    {!! CGlobal::$extraHeaderCSS !!}
    {!! CGlobal::$extraHeaderJS !!}
    <script type="text/javascript">
        var BASE_URL = "{{config('app.url')}}";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid" id="wrapper">
    @yield('content')
</div>
{!! CGlobal::$extraFooterCSS !!}
{!! CGlobal::$extraFooterJS !!}
</body>

</html>

