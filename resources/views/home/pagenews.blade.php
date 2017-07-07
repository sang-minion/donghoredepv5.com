<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 25/06/2017
 * Time: 8:31 SA
 */
 use \App\model\News;
 use \App\model\Banner;
  use \App\model\Product;
?>
@extends('layouts.app')
@section('menu')
    @include('layouts.header')
@endsection
@section('content')
    <div class="container ">
	<div class="product-details">
        @if(isset($pd_product)&&!empty($pd_product))
            <div class="col-md-9 col-sm-9 col-xs-12 product-center">
                @if(isset($title)) <h4 class="news-title">{{$title}}</h4>@endif
                <div class="show-list-product">
                    <ul>
                        @foreach($pd_product as $k=>$item)
                            <li class="col-news">
                                <a href="{{Funclip::buildLinkDetailNews($item->news_id,$item->news_alias)}}"
                                   class="box-news" title="{{$item->news_title}}">
                                    <img class="box-img"
                                         src="{{ThumbImg::thumbBaseNormal(News::FOLDER,$item->news_id,$item->news_media,300,250,'',true,true,true)}}"
                                         alt="{{$item->news_title}}">
                                    <div class="box-content">
                                        <h4 class="title-news">{{$item->news_title}}</h4>
                                        <div class="box-new-content">
                                            {{stripcslashes($item->news_intro) }}
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if(isset($paging))
                    <div class="paging-product">
                        {!! $paging !!}
                    </div>
                @endif
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 product col-right-same-product">
			<h5></h5>
                @if(isset($banner_right)&&!empty($banner_right[0]))
                    <a href="{{$banner_right[0]->banner_link}}" title="{{$banner_right[0]->banner_title}}"
                       class="advertis">
                        <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_right[0]->banner_id,$banner_right[0]->banner_media,400,400,'',true,true,true)}}"
                             alt="{{$banner_right[0]->banner_title}}">
                    </a>
                @else
                    <a href="javascript:void(0)" title="banner">
                        <img src="javascript:void(0)" alt="banner"> </a>
                @endif
                <div class="product-center">
                    <h4 class="title-same-product">Có thể bạn quan tâm</h4>
                            @if(isset($sameProduct)&&!empty($sameProduct))
                                @include('home.partical_list_product',['listPD'=>$sameProduct])	
                            @endif
                    </div>
                </div>
            </div>
        @else
            <H3>Không tìm thấy nội dung bạn yêu cầu</H3>
        @endif
    </div>
	</div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection
