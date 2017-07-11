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

    <title>{{CGlobal::$title}}</title>
    <link rel="shortcut icon" href="{{asset('resources/assets/media/favicon.ico')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link href="{{ asset('resources/assets/focus/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/focus/css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/assets/libs/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">

    {!! CGlobal::$extraHeaderCSS !!}

    <script type="text/javascript">
        var BASE_URL = "{{config('app.base_url')}}";
    </script>
    <script src="{{ asset('resources/assets/focus/js/app.js') }}"></script>
    <script src="{{ asset('resources/assets/focus/js/reset.js') }}"></script>
    <script src="{{ asset('resources/assets/focus/js/focus.js') }}"></script>

    {!! CGlobal::$extraHeaderJS !!}

<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header" id="header-top">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route("index")}}">{{config('app.name')}}</a>
        </div>
        <ul class="nav navbar-right top-nav">
            <li><a href="{{route('admin.user_profile')}}" title="thông tin cá nhân"><i class="fa fa-user"></i><span>{{Session::get('user')['name']}}
                        &nbsp</span></a></li>
            <li><a href="{{route('admin.user_changepass')}}" title="đổi mật khẩu"><i class="fa fa-refresh"></i>&nbsp</a>
            </li>
            <li><a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="đăng xuất">
                    <i class="fa fa-sign-out"></i>&nbsp;
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
        <div class="navbar-header" id="header-bootom">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('index')}}">{{config('app.name')}}</a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                {!! CGlobal::$textMenu !!}
            </ul>
        </div>
    </nav>

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="page-header" style="">
                    <i class="fa fa-backward fa-2x goback-top" title="quay về trang trước" id="goback"></i>
                    <ol class="breadcrumb">
                        <li><a href="{{route('index')}}"><i class="fa fa-home"></i> Home</a></li>
                        {!! CGlobal::$breadcrumbtop !!}
                    </ol>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- The Modal : modal hiển show ảnh -->
<div id="Modal-IMG" class="modal-show-img">
    <span class="close">&times;</span>
    <img class="modal-img-content" id="img01" alt="{{config('app.name')}}">
    <div id="modal-img-caption"></div>
</div>
<a class="btn-top" href="javascript:void(0);" title="Top" style="display: none"></a>
{!! CGlobal::$extraFooterCSS !!}
{!! CGlobal::$extraFooterJS !!}
</body>

</html>

