<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 18/06/2017
 * Time: 23:16 CH
 */
?>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle offcanvas-toggle pull-right" data-toggle="offcanvas"
                    data-target="#js-bootstrap-offcanvas">
                <i class="fa fa-bars fa-2x"></i>
            </button>
            <a class="logo navbar-brand visible-xs" href="{{route('index') }}" title="{{config('app.name')}}">
                <img src="{{asset('resources/assets/media/logo.png')}}">
            </a>
        </div>
        <div class="navbar-offcanvas navbar-offcanvas-touch" id="js-bootstrap-offcanvas">
            <ul class="nav navbar-nav navbar-left ">
                <li class="hotline"><a href="tel:{{$hostline}}" title="hotline">
                        <img class="icon-phone" src="{{asset('resources/assets/media/smart2.png')}}"></img>
                        <div><b>Hotline</b><br><span>{{Funclip::formatPhonenumber($hostline)}}</span></div>
                    </a></li>
                <li class="zalo">
                    <a href="tel:{{$zalo}}" title="zalo">
                        <img class="icon-zalo" src="{{asset('resources/assets/media/zalo.png')}}"></img>
                        <div><b>Zalo</b><br><span>{{Funclip::formatPhonenumber($zalo)}}</span></div>
                    </a>
                </li>
                <li class="frmsearch">
                    <form class="navbar-form" method="GET" action="{{route('search')}}">
                        <div class="input-group">
                            <input type="text" class="form-control" name="key" id="search"
                                   placeholder="Nhập tên SP cần tìm ...">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="logocenter hidden-xs ">
                    <a href="{{route('index')}}" class="logo" title="">
                        <img class="icon-logo" src="{{asset('resources/assets/media/logo.png')}}"></img>
                    </a>
                </li>
                @foreach($menu as $k=>$item)
                    @if($k <= 3)
                        @if(isset($item['submenu'])&&is_array($item['submenu'])&&count($item['submenu'])>0)
                            <li class="dropdown item-menu">
                                <a href="javascript:void(0)" title="{{$item['title']}}" class="dropdown-toggle {{$item['active']}}"
                                   data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span> <i class="fa fa-angle-left"></i>  {{$item['title']}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($item['submenu'] as $k=>$sub)
                                        <li>
                                            <a href="{{$sub['link']}}" class="{{$sub['active']}}" title="{{$sub['title']}}">{{$sub['title']}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="item-menu">
                                <a href="{{$item['link']}}" class="{{$item['active']}}"
                                   title="{{$item['title']}}"><span>{{$item['title']}}</span></a>
                            </li>
                        @endif
                    @endif
                @endforeach
                @if(count($menu)>3)
                    <li class="dropdown menu-other">
                        <a class="dropdown-toggle"
                           data-toggle="sub" role="button" aria-haspopup="true" aria-expanded="false"><span class="title-other-menu">Danh mục <i
                                        class="fa fa-list"></i></span></a>
                        <ul class="dropdown-menu" id="sub">
                            @foreach($menu as $k=>$item)
                                @if($k > 3)
									@if(isset($item['submenu'])&&is_array($item['submenu'])&&count($item['submenu'])>0)
										<li class="dropdown-submenu">
											<a href="javascript:void(0)" title="{{$item['title']}}" class="dropdown-toggle {{$item['active']}}"
											   data-toggle="sub{{$k}}" role="button" aria-haspopup="true" aria-expanded="false">
												 {{$item['title']}}
											</a>
											<ul class="dropdown-menu" id="sub{{$k}}">
												@foreach($item['submenu'] as $k=>$sub)
													<li>
														<a href="{{$sub['link']}}" class="{{$sub['active']}}" title="{{$sub['title']}}">{{$sub['title']}}</a>
													</li>
												@endforeach
											</ul>
										</li>
									@else
										<li class="">
											<a href="{{$item['link']}}" class="{{$item['active']}}"
											   title="{{$item['title']}}">{{$item['title']}}</a>
										</li>
									@endif									
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>