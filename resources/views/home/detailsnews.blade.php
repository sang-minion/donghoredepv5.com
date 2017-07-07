<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 25/06/2017
 * Time: 13:13 CH
 */
?>
<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 15/06/2017
 * Time: 11:18 SA
 */
 use \App\model\Banner;
 use \App\model\Product;
 use \App\model\News;
?>
@extends('layouts.app')
@section('menu')
    @include('layouts.header')
@endsection
@section('content')
    <div class="container">
        <div class=" product-details">
        <div class="col-md-9 col-sm-9 col-xs-12 col-left">
            <div class="product">
                @if(isset($title)) <h4 class="news-title">{{$title}}</h4>@endif
                <div class=" product-center content-details-news">
                    <h4 class="title-inners">@if(isset($product)&&!empty($product)){!! stripcslashes(isset($product->news_title)?$product->news_title:$product->static_title) !!}@endif</h4>
                    <p>
						@if(isset($product)&&!empty($product)){!! stripcslashes(isset($product->news_content)?$product->news_content:$product->static_content) !!}@endif
					</p>
				</div>                
            </div>
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
                <div class="show-list-product">
                    @if(isset($sameProduct)&&!empty($sameProduct))
                                @include('home.partical_list_product',['listPD'=>$sameProduct])	
                            @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection
