<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 28/06/2017
 * Time: 21:36 CH
 */
?>

@extends('layouts.app')
@section('menu')
    @include('layouts.header')
@endsection
@section('content')
    <div class="container product">
        @if(isset($pd_product)&&!empty($pd_product))
            <div class="col-md-12 col-sm-12 col-xs-12 product-center">
                @if(isset($title)&&!empty($title)) <h4 class="search-title">{{isset($tk)?'Tìm thấy '.$total_product.' ':''}}{{$title}}</h4>@endif
                <div class="show-list-product">
                    <ul>
                        @foreach($pd_product as $k=>$item)
                            <li class="col-product">
                                <a href="{{Funclip::buildLinkDetailProduct($item->product_id,$item->product_alias)}}"
                                   class="box-product" title="{{$item->product_title}}">
                                    <img class="box-img"
                                         src="{{ThumbImg::thumbBaseNormal(\App\model\Product::FOLDER,$item->product_id,$item->product_media,220,200,'',true,true,true)}}"
                                         alt="{{$item->product_title}}">
                                    <h5 class="title-product">{{$item->product_title}}</h5>
                                    <h5 class="vote"><i class="fa fa-star item-vote active"></i><i
                                                class="fa fa-star item-vote active"></i><i
                                                class="fa fa-star item-vote"></i><i
                                                class="fa fa-star item-vote"></i><i
                                                class="fa fa-star item-vote"></i>
                                    </h5>
                                    <h5 class="price">{{$item->product_price_saleof>0?Utility::numberFormat($item->product_price_saleof).'đ':Utility::numberFormat($item->product_price).'đ'}}
                                        <span class="price-odl">{{$item->product_price_saleof>0?Utility::numberFormat($item->product_price).'đ':''}}</span>
                                    </h5>
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
        @endif
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection

