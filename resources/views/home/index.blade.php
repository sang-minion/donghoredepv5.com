<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 15/06/2017
 * Time: 11:18 SA
 */
 use \App\model\Banner;
 use \App\model\Product;
 use \App\model\Gift;
 use \App\model\Category;
  use \App\model\Partner;
?>
@extends('layouts.app')
@section('menu')
    @include('layouts.header')
@endsection
@section('content')
    <div class="container product">
        <div class="col-md-12 col-sm-12 xol-xs-12 product-banner-index">
            <div class="col-md-6 col-sm-6 col-xs-12 col-larges">
                @if(isset($banner_rong)&&count($banner_rong)>1&&$banner_rong[0]['banner_ghim']!=1)
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            @foreach($banner_rong as $k=>$v)
                                @if($k==0)
                                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                @else
                                    <li data-target="#carousel-example-generic" data-slide-to="{{$k}}"></li>
                                @endif
                            @endforeach
                        </ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            @foreach($banner_rong as $k=>$v)
                                @if($k==0)
                                    <div class="item active">
                                        <a href="{{$v->banner_link}}" title="{{$v->banner_title}}">
                                            <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$v->banner_id,$v->banner_media,900,900,'',true,true,true)}}"
                                                 alt="{{$v->banner_title}}">
                                        </a>
                                        <div class="carousel-caption" style="display:none;">
                                            {{$v->banner_title}}
                                        </div>
                                    </div>
                                @else
                                    <div class="item">
                                        <a href="{{$v->banner_link}}" title="{{$v->banner_title}}">
                                            <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$v->banner_id,$v->banner_media,900,900,'',true,true,true)}}"
                                                 alt="{{$v->banner_title}}">
                                        </a>
                                        <div class="carousel-caption"  style="display:none;">
                                            {{$v->banner_title}}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button"
                           data-slide="prev" title="quay lại">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button"
                           data-slide="next" title="tiếp theo">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </a>
                    </div>
                @else
                    <a href="{{isset($banner_rong[0]['banner_link'])?$banner_rong[0]['banner_link']:''}}">
                        <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,isset($banner_rong[0]['banner_id'])? $banner_rong[0]['banner_id']:'',isset($banner_rong[0]['banner_media'])?$banner_rong[0]['banner_media']:'',900,900,'',true,true,true)}}"
                             alt="{{isset($banner_rong[0]['banner_title'])?$banner_rong[0]['banner_title']:''}}">
                    </a>
                @endif

            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 col-small">
                <div class="col-md-6 col-sm-6 col-xs-6 col-small-1">
                    @if(isset($banner_nho)&&!empty($banner_nho[0]))
                        <a href="{{$banner_nho[0]->banner_link}}" title="{{$banner_nho[0]->banner_title}}">
                            <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_nho[0]->banner_id,$banner_nho[0]->banner_media,500,900,'',true,true,true)}}"
                                 alt="{{$banner_nho[0]->banner_title}}">
                        </a>
                    @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6 col-small-2">
                    <div class="col-md-12 col-sm-12 box-1">
                        @if(isset($banner_doi)&&!empty($banner_doi[0]))
                            <a href="{{$banner_doi[0]->banner_link}}" title="{{$banner_doi[0]->banner_title}}">
                                <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_doi[0]->banner_id,$banner_doi[0]->banner_media,400,400,'',true,true,true)}}"
                                     alt="{{$banner_doi[0]->banner_title}}">
                            </a>
                        @else
                            <a href="javascript:void(0)" title="banner">
                                <img src="javascript:void(0)" alt="banner"> </a>
                        @endif
                    </div>
                    <div class="col-md-12 col-sm-12 box-2">
                        @if(isset($banner_doi2)&&!empty($banner_doi2[0]))
                            <a href="{{$banner_doi2[0]->banner_link}}" title="{{$banner_doi2[0]->banner_title}}">
                                <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_doi2[0]->banner_id,$banner_doi2[0]->banner_media,400,400,'',true,true,true)}}"
                                     alt="{{$banner_doi2[0]->banner_title}}">
                            </a>
                        @else
                            <a href="javascript:void(0)" title="banner">
                                <img src="javascript:void(0)" alt="banner"> </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 xol-xs-12 product-center">
			@include('home.partical_product_for_we')            
        </div>
        <div class="col-md-12 col-sm-12 xol-xs-12 product-center">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#listprduct1" aria-controls="home" role="tab"
                                                          data-toggle="tab">Rẻ nhất</a></li>
                <li role="presentation"><a href="#listprduct2" aria-controls="profile" role="tab" data-toggle="tab">Quà
                        tặng </a></li>
                <li role="presentation"><a href="#listprduct3" aria-controls="messages" role="tab" data-toggle="tab">Nhiều
                        nhất</a></li>
                <li role="presentation"><a href="#listprduct4" aria-controls="settings" role="tab" data-toggle="tab">Mới
                        Nhất</a></li>
                <li role="presentation"><a href="#listprduct5" aria-controls="messages" role="tab" data-toggle="tab">Mua
                        nhiều nhất</a></li>
                <li role="presentation"><a href="#listprduct6" aria-controls="settings" role="tab" data-toggle="tab">Tốt
                        nhất</a></li>
            </ul>
            <div class="tab-content ">
                <div role="tabpanel" class="tab-pane fade in active" id="listprduct1">
                    @if(isset($pd_product_cheapest)&&!empty($pd_product_cheapest))
						@include('home.partical_list_product',['listPD'=>$pd_product_cheapest])						
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane  fade in " id="listprduct2">
                    @if(isset($pd_product_gif)&&!empty($pd_product_gif))
                        @include('home.partical_list_gift',['listGF'=>$pd_product_gif])
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct3">
                    @if(isset($pd_product_most)&&!empty($pd_product_most))
                        @include('home.partical_list_product',['listPD'=>$pd_product_most])	
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct4">
                    @if(isset($pd_product_news)&&!empty($pd_product_news))
                        @include('home.partical_list_product',['listPD'=>$pd_product_news])	
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct5">
                    @if(isset($pd_product_buy_most)&&!empty($pd_product_buy_most))
                        @include('home.partical_list_product',['listPD'=>$pd_product_buy_most])	
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct6">
                    @if(isset($pd_product_best)&&!empty($pd_product_best))
                        @include('home.partical_list_product',['listPD'=>$pd_product_best])	
                    @endif
                </div>
            </div>
        </div>
        @if(isset($ProductCate[0]['cate'])&&!empty($ProductCate[0]['cate'])&&isset($ProductCate[0]['prd'])&&!empty($ProductCate[0]['prd']))
            @include('home.partical_product_row_index_left',['Cate'=>$ProductCate[0]['cate'],'listPD'=>$ProductCate[0]['prd']])
        @endif
        @if(isset($ProductCate[1]['cate'])&&!empty($ProductCate[1]['cate'])&&isset($ProductCate[1]['prd'])&&!empty($ProductCate[1]['prd']))
            @include('home.partical_product_row_index_right',['Cate'=>$ProductCate[1]['cate'],'listPD'=>$ProductCate[1]['prd']])
        @endif
        <div class="cmt-customer">
            @if(isset($commentVote)&&!empty($commentVote))
                @include('home.partical_cmt_customer',['cmt'=>$commentVote])
            @endif
		</div>
        @if(count($ProductCate)>1)
            @for($i=2;$i<count($ProductCate);$i++)
                @if($i%2==0)
                    @if(isset($ProductCate[$i]['cate'])&&!empty($ProductCate[$i]['cate'])&&isset($ProductCate[$i]['prd'])&&!empty($ProductCate[$i]['prd']))
                        @include('home.partical_product_row_index_left',['Cate'=>$ProductCate[$i]['cate'],'listPD'=>$ProductCate[$i]['prd']])
                    @endif
                @elseif($i%2!=0)
                    @if(isset($ProductCate[$i]['cate'])&&!empty($ProductCate[$i]['cate'])&&isset($ProductCate[$i]['prd'])&&!empty($ProductCate[$i]['prd']))
                        @include('home.partical_product_row_index_right',['Cate'=>$ProductCate[$i]['cate'],'listPD'=>$ProductCate[$i]['prd']])
                    @endif
                @endif
            @endfor
        @endif
    </div>
    <div class="product">
        <div class="index-brand-partner">
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#lislogoBrand" aria-controls="home" role="tab"
                                                              data-toggle="tab">Logo thương hiệu đồng hồ</a></li>
                    <li role="presentation"><a href="#lislogoPartner" aria-controls="profile" role="tab"
                                               data-toggle="tab">ĐỐi tác</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane  fade in active" id="lislogoBrand">
                        <ul>
                            @if(isset($arBrand)&&!empty($arBrand))
                                @foreach($arBrand as $item)
                                    <li>
                                        <a href="{{Funclip::buildLinkCategory($item->category_id,$item->category_keyword)}}" title="{{$item->category_title}}">
                                            <img src="{{ThumbImg::thumbBaseNormal(Category::FOLDER,$item->category_id,$item->category_media,200,150,'',true,true,true)}}"/>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in " id="lislogoPartner">
						<ul>
                            @if(isset($partner)&&!empty($partner))
                                @foreach($partner as $item)
                                    <li>
                                        <a href="{{$item->partner_website}}" title="{{$item->partnet_title}}"  target="_blank" >
                                            <img src="{{ThumbImg::thumbBaseNormal(Partner::FOLDER,$item->partner_id,$item->partner_logo,200,150,'',true,true,true)}}"/>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection