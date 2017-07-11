<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 15/06/2017
 * Time: 11:18 SA
 */
use \App\model\Banner;
use \App\model\Product;
?>
@extends('layouts.app')
@section('menu')
    @include('layouts.header',['menu'=>$menu,'a'=>'a'])
@endsection
@section('content')
    <div class="container">
        <div class=" product-details">
            <h5 class="title"><a href="{{route('index')}}" title="Trang chủ"><i class="fa fa-home"></i> <i
                            class="fa fa-angle-right"></i></a> @if(isset($product)&&!empty($product))@if(isset($cates)&&!empty($cates))
                    <a href="{{Funclip::buildLinkCategory($cates->category_id,$cates->category_keyword)}}"
                       title="{{$cates->category_title}}">{{$cates->category_title}} <i
                                class="fa fa-angle-right"></i></a>@endif {{$product->product_title}}@else Không tìm
                thấy nội dung bạn yêu cầu @endif</h5>
            <div class="col-md-9 col-sm-9 col-xs-12 col-left">
                @if(isset($product)&&!empty($product))
                    <?php
                    $product_multi_media = $product->product_multi_media != '' ? unserialize($product->product_multi_media) : array();
                    $product_video = $product->product_video != '' ? unserialize($product->product_video) : array();
                    ?>
                    @if(!empty($product_video))
                        <div class="show-videoIntro-product">
                            <?php
                            $_video_one = '';
                            if ($product_video[0] != '') {
                                $_video_one = str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $product_video[0]);
                            }
                            ?>
                            <div class="embed-responsive embed-responsive-16by9" dataone="{{$_video_one}}"
                                 style="background:url('{{'http://img.youtube.com/vi/'.substr($_video_one,strrpos($_video_one,'/',0)+1).'/hqdefault.jpg'}}') no-repeat 0 0; background-size: cover;">
                                <i class="fa fa-play fa-5x"></i>
                            </div>
                            <section class="regular slider list-video-intro">
                                @foreach($product_video as $video)
                                    <?php
                                    $_video = '';
                                    if ($video != '') {
                                        $_video = str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $video);
                                    }
                                    ?>
                                    <div>
                                        <img class="embed-responsive-item"
                                             src="{{'http://img.youtube.com/vi/'.substr($_video,strrpos($_video,'/',0)+1).'/hqdefault.jpg'}};"
                                             rel="{{$_video}}">
                                    </div>
                                @endforeach
                            </section>
                        </div>
                    @endif
                    @if(!empty($product_multi_media))
                        <div class="show-img-product">
                            <div class="imgbig">
                                <img src="{{ThumbImg::thumbBaseNormal(Product::FOLDER,$product->product_id,$product_multi_media[0],800,600,'',true,true,true)}}"/>
                            </div>
                            <section class="regular slider">
                                @foreach($product_multi_media as $k=>$img)
                                    <div>
                                        <img class="{{$k==0?' imgactive':' '}}"
                                             src="{{ThumbImg::thumbBaseNormal(Product::FOLDER,$product->product_id,$img,800,600,'',true,true,true)}}">
                                    </div>
                                @endforeach
                            </section>
                        </div>
                    @endif
                @endif
                <div class="product">
                    <div class="product-intro">
                        @include('home.partical_product_for_we')
                    </div>
                    @if(isset($product)&&!empty($product))
                        <div class=" product-center product-intro-1">
                            <h4 class="title-para">Vì sao bạn nên mua sản phẩm này ?</h4>
                            <div class="content-product-intro">
                                <p>
                                    @if(isset($product)&&!empty($product)){!! stripcslashes($product->product_why) !!}@endif
                                </p>
                            </div>
                        </div>
                        <div class=" product-center product-intro-2">
                            <h4 class="title-para">Mua đồng hồ này bạn được tặng những gì ?</h4>
                            <div class="content-product-intro">
                                <p>
                                    @if(isset($product)&&!empty($product)) {!! stripcslashes($product->product_intro) !!} @endif
                                </p>
                            </div>
                        </div>
                        <div class=" product-center product-intro-3">
                            <h4 class="title-para">Nội dung chi tiết</h4>
                            <div class="content-product-intro">
                                <p>
                                    @if(isset($product)&&!empty($product)){!! stripcslashes($product->product_details) !!}@endif
                                </p>
                            </div>
                        </div>
                    @endif
                    <div class="cmt-customer">
                        @if(isset($commentVote)&&!empty($commentVote))
                            @include('home.partical_cmt_customer',['cmt'=>$commentVote])
                        @endif
                    </div>
                    @if(isset($product)&&!empty($product))
                        <div class="box-order">
                            <div class="col-md-6 col-sm-12 col-xs-12 col-form details-form-buy">
                                <form class="form-horizontal" method="POST" action="{{route('dathang1')}}"
                                      id="txtFormShopCart">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="txtPid" id="txtPid" value="{{$product->product_id}}">
                                    <div class="title-form">
                                        <h4>MUA NGAY SẢN PHẨM TUYỆT VỜI NÀY</h4>
                                        <h5>Bạn vui lòng điển đầy đủ thông tin vào các ô bên dưới !</h5>
                                    </div>
                                    @if($product->product_gift_code!=''&&$product->product_gift_code!=NULL)
                                        <?php $arGift = unserialize($product->product_gift_code); ?>
                                        @if(!empty($arGift))
                                            <div class="form-group{{isset($errors)&& $errors->has('order_name') ? ' has-error' : '' }}"
                                                 id="listImgGift">
                                                <label for="order_name" class="control-label col-md-3 col-sm-2">Chọn
                                                    quà
                                                    tặng</label>
                                                <div class=" col-md-9 col-sm-10">
                                                    <input type="hidden" name="txGift" id="txtGift" value="">
                                                    <div>
                                                        @foreach($arGift as $k=>$v)
                                                            <img src="{{ThumbImg::thumbBaseNormal(\App\model\Gift::FOLDER,$k,\App\model\Gift::getById($k)['gift_media'],80,50,'',true,true,true)}}"
                                                                 keys="txGift[{{$k}}]" data="{{$v}}" id="chooserGift"/>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="form-group {{isset($errors)&& $errors->has('txtPnum') ? ' has-error' : '' }}">
                                        <label for="txtPnum" class="control-label col-md-3 col-sm-2">Số lượng
                                            <i>*</i></label>
                                        <div class=" col-md-9 col-sm-10" id="Pnum">
                                            <input id="txtPnum" type="number" class="form-control" name="txtPnum"
                                                   min="1" value="1" required>
                                            <div id="txtPriceAll"><b>Giá: </b><span id="txtPrice">{{Funclip::numberFormat($product['product_price'])}}
                                                    đ/sản phẩm</span></div>
                                            @if (isset($errors)&&$errors->has('txtPnum'))
                                                <span class="help-block">
                                <strong>Số lượng ít nhất 1 chiếc</strong>
                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{isset($errors)&& $errors->has('txtName') ? ' has-error' : '' }}">
                                        <label for="txtName" class="control-label col-md-3 col-sm-2 col-xs-5">Thành
                                            tiền </label>
                                        <div class=" col-md-9 col-sm-10 col-xs-7" id="totalPrice">
                                            {{$product->product_price}}đ
                                        </div>
                                    </div>
                                    <div class="form-group {{isset($errors)&& $errors->has('txtName') ? ' has-error' : '' }}">
                                        <label for="txtName" class="control-label col-md-3 col-sm-2">Họ tên
                                            <i>*</i></label>
                                        <div class=" col-md-9 col-sm-10">
                                            <input id="txtName" type="text" class="form-control" name="txtName"
                                                   placeholder="Họ và tên"
                                                   value="{{isset($member['name'])?$member['name']: old('txtName') }}"
                                                   required>
                                            @if (isset($errors)&&$errors->has('txtName'))
                                                <span class="help-block">
                                <strong>Họ tên không được để trống</strong>
                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{isset($errors)&& $errors->has('txtMobile') ? ' has-error' : '' }}">
                                        <label for="txtMobile" class="control-label col-md-3 col-sm-2">ĐT liên hệ
                                            <i>*</i></label>
                                        <div class=" col-md-9 col-sm-10">
                                            <input id="txtMobile" type="text" class="form-control"
                                                   name="txtMobile" placeholder="VD:0986255353"
                                                   value="{{isset($member['phone'])?$member['phone']: old('txtMobile') }}"
                                                   required>
                                            @if (isset($errors)&&$errors->has('txtMobile'))
                                                <span class="help-block">
								<strong>Số điện thoại không đúng</strong>
                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{isset($errors)&& $errors->has('txtEmail') ? ' has-error' : '' }}">
                                        <label for="txtEmail" class="control-label col-md-3 col-sm-2">Địa chỉ
                                            mail</label>
                                        <div class=" col-md-9 col-sm-10">
                                            <input id="txtEmail" type="email" class="form-control " name="txtEmail"
                                                   placeholder="abc@gmail.com"
                                                   value="{{isset($member['email'])?$member['email']: old('txtEmail') }}">
                                            @if (isset($errors)&&$errors->has('txtEmail'))
                                                <span class="help-block">
							<strong>Email không đúng định dạng</strong>
                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{isset($errors)&& $errors->has('txtAddress') ? ' has-error' : '' }}">
                                        <label for="txtAddress" class="control-label col-md-3 col-sm-2">Địa chỉ
                                            <i>*</i></label>
                                        <div class=" col-md-9 col-sm-10">
                                <textarea id="txtAddress" class="form-control " name="txtAddress"
                                          placeholder="Địa chỉ cụ thể thuận tiện nhất bạn có thể nhận hàng"
                                          rows="2">{{isset($member['address'])? $member['address']:old('txtAddress')}}</textarea>
                                            @if (isset($errors)&&$errors->has('txtEmail'))
                                                <span class="help-block">
							<strong>Đia chỉ giao hàng không được để trống</strong>
                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{isset($errors)&& $errors->has('txtMessage') ? ' has-error' : '' }}">
                                        <label for="txtMessage" class="control-label col-md-3 col-sm-2">Ghi chú</label>
                                        <div class=" col-md-9 col-sm-10">
                                <textarea id="txtMessage" class="form-control " name="txtMessage"
                                          placeholder="VD: địa chỉ giao hàng, thời gian nhận hàng, màu dây ..."
                                          rows="3">{{old('txtMessage')}}</textarea>
                                            <button type="submit" name="txtSubmit" id="submitPaymentOrder"
                                                    class="btn">Gửi đơn hàng
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 col-sm-12 col-info">
                                @if(isset($HDMH)){!! $HDMH->static_content !!}@endif
                            </div>
                        </div>
                        @include('home.partical_comment_product',['CMTPRD'=>$CMTPRD,'member'=>$member,'admin'=>$admin,'prdid'=>$product->product_id])
                    @endif
                </div>

            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 product col-right-same-product">
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
                    <h4 class="title-same-product">Sản phẩm liên quan</h4>
                    <div class="show-list-product">
                        @if(isset($sameProduct)&&!empty($sameProduct))
                            @include('home.partical_list_product',['listPD'=>$sameProduct])
                        @endif
                    </div>
                </div>
            </div>
                @if(isset($ProductSeen)&&!empty($ProductSeen))
                    <div class="product">
                        <div class="product-center">
                            <div class="product-seen">
                                <h4 class="title-product-seen">Sản đã phẩm xem</h4>
                                @include('home.partical_list_product',['listPD'=>$ProductSeen])
                            </div>
                        </div>
                    </div>
                @endif
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection