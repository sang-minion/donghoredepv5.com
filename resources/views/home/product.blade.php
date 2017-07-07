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
?>
@extends('layouts.app')
@section('menu')
    @include('layouts.header')
@endsection
@section('content')
    <div class="container product product-other">
        <div class="col-md-12 col-sm-12 col-xs-12 product-banner">
            <div class="col-md-9 col-sm-9 col-xs-12 col-larges">
                @if(isset($cate['category_media_banner'])&&$cate['category_media_banner']!='')
                    <a href="javascript:void(0)">
                        <img src="{{ThumbImg::thumbBaseNormal(Category::FOLDER,$cate->category_id,$cate->category_media_banner,800,600,'',true,true,true)}}"
                             alt="{{$cate->category_title}}">
                    </a>
                @else
                    <a href="javascript:void(0)" title="banner">
                        <img src="javascript:void(0)" alt="banner">
                    </a>
                @endif
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 col-small">
                <div class="col-md-12 col-sm-12 col-xs-12 box-1">
                    @if(isset($banner_nho)&&!empty($banner_nho[0]))
                        <a href="{{$banner_nho[0]->banner_link}}" title="{{$banner_nho[0]->banner_title}}">
                            <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_nho[0]->banner_id,$banner_nho[0]->banner_media,400,400,'',true,true,true)}}"
                                 alt="{{$banner_nho[0]->banner_title}}">
                        </a>
                    @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12 product-center">
            <h3 style="display:none;">{{isset($cate['category_title'])?'Đồng hồ '.$cate['category_title']:''}}</h3>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#listprduct1" aria-controls="home" role="tab"
                                                          data-toggle="tab">Rẻ nhất</a></li>
                <li role="presentation"><a href="#listprduct2" aria-controls="profile" role="tab" data-toggle="tab">Quà
                        tặng </a></li>
                <li role="presentation"><a href="#listprduct3" aria-controls="messages" role="tab"
                                           data-toggle="tab">Nhiều
                        nhất</a></li>
                <li role="presentation"><a href="#listprduct4" aria-controls="settings" role="tab"
                                           data-toggle="tab">Mới
                        Nhất</a></li>
                <li role="presentation"><a href="#listprduct5" aria-controls="messages" role="tab"
                                           data-toggle="tab">Mua
                        nhiều nhất</a></li>
                <li role="presentation"><a href="#listprduct6" aria-controls="settings" role="tab"
                                           data-toggle="tab">Tốt
                        nhất</a></li>
            </ul>
            <div class="tab-content show-list-product">
                <div role="tabpanel" class="tab-pane fade in active " id="listprduct1">
                    @if(isset($pd_product_cheapest)&&!empty($pd_product_cheapest))
						@include('home.partical_list_product',['listPD'=>$pd_product_cheapest,'min'=>0,'max'=>5])
                    @endif
					@include('home.partical_product_for_we')
                    @if(isset($pd_product_cheapest)&&!empty($pd_product_cheapest)&&count($pd_product_cheapest)>4)
                        @include('home.partical_list_product',['listPD'=>$pd_product_cheapest,'min'=>5,'max'=>15])
                    @endif
                    @if(isset($banner_giua)&&!empty($banner_giua[0]))
                        <a href="{{$banner_giua[0]->banner_link}}" title="{{$banner_giua[0]->banner_title}}"
                           class="banner-center">
                            <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_giua[0]->banner_id,$banner_giua[0]->banner_media,800,400,'',true,true,true)}}"
                                 alt="{{$banner_giua[0]->banner_title}}">
                        </a>
                    @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                    @if(isset($pd_product_cheapest)&&!empty($pd_product_cheapest)&&count($pd_product_cheapest)>14)
                        @include('home.partical_list_product',['listPD'=>$pd_product_cheapest,'min'=>15,'max'=>26])
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane  fade in " id="listprduct2">
                    @if(isset($pd_product_gif)&&!empty($pd_product_gif))
                        @include('home.partical_list_gift',['listGF'=>$pd_product_gif,'min'=>0,'max'=>25])
                    @endif
					@include('home.partical_product_for_we')
                    @if(isset($pd_product_gif)&&!empty($pd_product_gif)&&count($pd_product_gif)>=5)
                        @include('home.partical_list_product',['listPD'=>$pd_product_gif,'min'=>5,'max'=>15])
                    @endif
                        <?php $bnng = 0;//rand(0,count($banner_giua)-1) ;if($bnng<0)$bng=0;?>
                        @if(isset($banner_giua)&&!empty($banner_giua[$bnng]))
                            <a href="{{$banner_giua[0]->banner_link}}" title="{{$banner_giua[$bnng]->banner_title}}"
                               class="banner-center">
                                <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_giua[$bnng]->banner_id,$banner_giua[$bnng]->banner_media,800,400,'',true,true,true)}}"
                                     alt="{{$banner_giua[$bnng]->banner_title}}">
                            </a>
                        @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                    @if(isset($pd_product_gif)&&!empty($pd_product_gif)&&count($pd_product_gif)>=15)
                        @include('home.partical_list_product',['listPD'=>$pd_product_gif,'min'=>15,'max'=>26])
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct3">
                    @if(isset($pd_product_most)&&!empty($pd_product_most))
                        @include('home.partical_list_product',['listPD'=>$pd_product_most,'min'=>0,'max'=>5])
                    @endif
                    @include('home.partical_product_for_we')
                    @if(isset($pd_product_most)&&!empty($pd_product_most)&&count($pd_product_most)>=5)
                        @include('home.partical_list_product',['listPD'=>$pd_product_most,'min'=>5,'max'=>15])
                    @endif
                        @if(isset($banner_giua)&&!empty($banner_giua[0]))
                            <a href="{{$banner_giua[0]->banner_link}}" title="{{$banner_giua[0]->banner_title}}"
                               class="banner-center">
                                <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_giua[0]->banner_id,$banner_giua[0]->banner_media,800,400,'',true,true,true)}}"
                                     alt="{{$banner_giua[0]->banner_title}}">
                            </a>
                        @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                    @if(isset($pd_product_most)&&!empty($pd_product_most)&&count($pd_product_most)>=15)
                        @include('home.partical_list_product',['listPD'=>$pd_product_most,'min'=>15,'max'=>26])
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct4">
                    @if(isset($pd_product_news)&&!empty($pd_product_news))
                        @include('home.partical_list_product',['listPD'=>$pd_product_most,'min'=>0,'max'=>5])
                    @endif
                    @include('home.partical_product_for_we')
                    @if(isset($pd_product_news)&&!empty($pd_product_news)&&count($pd_product_news)>=5)
                        @include('home.partical_list_product',['listPD'=>$pd_product_most,'min'=>5,'max'=>15])
                    @endif
                        @if(isset($banner_giua)&&!empty($banner_giua[$bnng]))
                            <a href="{{$banner_giua[0]->banner_link}}" title="{{$banner_giua[0]->banner_title}}"
                               class="banner-center">
                                <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_giua[0]->banner_id,$banner_giua[0]->banner_media,800,400,'',true,true,true)}}"
                                     alt="{{$banner_giua[0]->banner_title}}">
                            </a>
                        @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                    @if(isset($pd_product_news)&&!empty($pd_product_news)&&count($pd_product_news)>=15)
                        @include('home.partical_list_product',['listPD'=>$pd_product_most,'min'=>15,'max'=>26])
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct5">
                    @if(isset($pd_product_buy_most)&&!empty($pd_product_buy_most))
                        @include('home.partical_list_product',['listPD'=>$pd_product_buy_most,'min'=>0,'max'=>5])
                    @endif
                    @include('home.partical_product_for_we')
                    @if(isset($pd_product_buy_most)&&!empty($pd_product_buy_most)&&count($pd_product_buy_most)>=5)
                        @include('home.partical_list_product',['listPD'=>$pd_product_buy_most,'min'=>5,'max'=>15])
                    @endif
                        @if(isset($banner_giua)&&!empty($banner_giua[$bnng]))
                            <a href="{{$banner_giua[0]->banner_link}}" title="{{$banner_giua[0]->banner_title}}"
                               class="banner-center">
                                <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_giua[0]->banner_id,$banner_giua[0]->banner_media,800,400,'',true,true,true)}}"
                                     alt="{{$banner_giua[0]->banner_title}}">
                            </a>
                        @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                    @if(isset($pd_product_buy_most)&&!empty($pd_product_buy_most)&&count($pd_product_buy_most)>=15)
                        @include('home.partical_list_product',['listPD'=>$pd_product_buy_most,'min'=>15,'max'=>26])
                    @endif
                </div>
                <div role="tabpanel" class="tab-pane fade in " id="listprduct6">
                    @if(isset($pd_product_best)&&!empty($pd_product_best))
                        @include('home.partical_list_product',['listPD'=>$pd_product_best,'min'=>0,'max'=>5])
                    @endif
                    @include('home.partical_product_for_we')
                    @if(isset($pd_product_best)&&!empty($pd_product_best)&&count($pd_product_best)>=5)
                       @include('home.partical_list_product',['listPD'=>$pd_product_best,'min'=>5,'max'=>15])
                    @endif
                        <?php $bnng =  0;//rand(0,count($banner_giua)-1) ;if($bnng<0)$bng=0;?>
                        @if(isset($banner_giua)&&!empty($banner_giua[$bnng]))
                            <a href="{{$banner_giua[$bnng]->banner_link}}" title="{{$banner_giua[$bnng]->banner_title}}"
                               class="banner-center">
                                <img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$banner_giua[$bnng]->banner_id,$banner_giua[$bnng]->banner_media,800,400,'',true,true,true)}}"
                                     alt="{{$banner_giua[$bnng]->banner_title}}">
                            </a>
                        @else
                        <a href="javascript:void(0)" title="banner">
                            <img src="javascript:void(0)" alt="banner"> </a>
                    @endif
                    @if(isset($pd_product_best)&&!empty($pd_product_best)&&count($pd_product_best)>=15)
                        @include('home.partical_list_product',['listPD'=>$pd_product_best,'min'=>15,'max'=>26])
                    @endif
                </div>
            </div>
            <div class="paging-product">
                {!! $paging !!}
            </div>
        </div>
        <div class="cmt-customer">
            @if(isset($commentVote)&&!empty($commentVote))
                @include('home.partical_cmt_customer',['cmt'=>$commentVote])
            @endif
        </div>
	</div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection