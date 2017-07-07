<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 28/06/2017
 * Time: 9:43 SA
 */
?>
@extends('layouts.app')
@section('menu')
    @include('layouts.header')
@endsection
@section('content')
    <ul class="breadcrumbs" style="display:none">
        <div class="container">
            <li><a>Trang trủ</a></li>
            <li><a><i class="fa fa-angle-right"></i> Đồng hồ nam</a></li>
            <li><a><i class="fa fa-angle-right"></i> Đồng hồ sk-368</a></li>
        </div>
    </ul>
    <div class="container ">
        <div class="details-carts">
            <div class="col-md-5 col-sm-12 col-xs-12 cart-info">
                <div class="panel panel-default">
                    <div class="panel-heading">Thông tin đơn hàng ({{count($dataItem)}} sản phẩm)</div>
                    <div class="panel-body">
                        <div class="table-responsive ">
                            <table class="table table-bordered table-hover">
                                <tr class="">
                                    <th width="1%">STT</th>
                                    <th width="30%">Tên sản phẩm</th>
                                    <th width="10%">SL</th>
                                    <th width="15%">Giá</th>
                                </tr>
                                <?php $total = 0; $arGift = array();?>
                                @foreach($dataItem as $k=>$item)
                                    <?php
                                    if ($item->product_gift_code != NULL && $item->product_gift_code != '') {
                                        if (!empty(unserialize($item->product_gift_code))) {
                                            foreach (unserialize($item->product_gift_code) as $k2 => $v2) {
                                                if (!isset($arGift[$k2])) {
                                                    $arGift[$k2] = $v2;
                                                }
                                            }
                                        }
                                    }
									$arMTP = $item->product_price_multi != NULL && $item->product_price_multi != '' ? unserialize($item->product_price_multi) : array();
									$price = 0;
									if (!empty($arMTP)) {
										foreach ($arMTP as $k2 => $v2) {
											if ($dataCart[$item->product_id] >= $k2) $price = $v2;
										}
									}
									$price = $price==0?$item->product_price:$price;
                                    ?>
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>
                                            <a href="{{Funclip::buildLinkDetailProduct($item->product_id,$item->product_alias)}}"
                                               target="_blank"
                                               title="{{$item->product_title}}">{{$item->product_title}}</a></td>
                                        <td>{{$dataCart[$item->product_id]}}</td>
                                        <td>{{Funclip::numberFormat($price)}}đ/sản phẩm</td>
                                        <?php $total += $price * $dataCart[$item->product_id]; $price=0; ?>
                                    </tr>
                                @endforeach
                                <tr class="">
                                    <td colspan="6">
                                        Tổng tiền : {{Funclip::numberFormat($total)}}đ
                                    </td>

                                </tr>
                            </table>
                        </div>
                        <div class="product-category">
                            @if(isset($paging))
                                <div class="paging-product">
                                    {!! $paging !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-sm-12 col-xs-12 details-form-buy">
                <div class="panel panel-default">
                    <div class="panel-heading">Địa chỉ giao hàng</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" id="txtFormShopCart">
                            {{ csrf_field() }}
                            @if(!empty($arGift))
                                <div class="form-group{{isset($errors)&& $errors->has('order_name') ? ' has-error' : '' }}"
                                     id="listImgGift">
                                    <label for="order_name" class="control-label col-md-2 col-sm-2">Chọn quà
                                        tặng</label>
                                    <div class=" col-md-10 col-sm-10">
                                        <input type="hidden" name="txGift" id="txtGift" value="">
                                        @if(!empty($arGift))
                                            <div>
                                                @foreach($arGift as $k=>$v)
                                                    <img src="{{ThumbImg::thumbBaseNormal(\App\model\Gift::FOLDER,$k,\App\model\Gift::getById($k)['gift_media'],80,50,'',true,true,true)}}"
                                                         keys="txGift[{{$k}}]" data="{{$v}}" id="chooserGift"/>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="form-group {{isset($errors)&& $errors->has('txtName') ? ' has-error' : '' }}">
                                <label for="txtName" class="control-label col-md-2 col-sm-2">Họ tên <i>*</i></label>
                                <div class=" col-md-10 col-sm-10">
                                    <input id="txtName" type="text" class="form-control" name="txtName" placeholder="Họ và tên"
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
                                <label for="txtMobile" class="control-label col-md-2 col-sm-2">ĐT liên hệ
                                    <i>*</i></label>
                                <div class=" col-md-10 col-sm-10">
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
                                <label for="txtEmail" class="control-label col-md-2 col-sm-2">Địa chỉ mail</label>
                                <div class=" col-md-10 col-sm-10">
                                    <input id="txtEmail" type="email" class="form-control " name="txtEmail" placeholder="abc@gmail.com"
                                           value="{{isset($member['email'])?$member['email']: old('txtEmail') }}">
                                    @if (isset($errors)&&$errors->has('txtEmail'))
                                        <span class="help-block">
							<strong>Email không đúng định dạng</strong>
                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{isset($errors)&& $errors->has('txtAddress') ? ' has-error' : '' }}">
                                <label for="txtAddress" class="control-label col-md-2 col-sm-2">Địa chỉ <i>*</i></label>
                                <div class=" col-md-10 col-sm-10">
                                <textarea id="txtAddress" class="form-control " name="txtAddress" placeholder="Địa chỉ cụ thể thuận tiện nhất bạn có thể nhận hàng"
                                          rows="2">{{isset($member['address'])?$member['address']:old('txtAddress')}}</textarea>
                                    @if (isset($errors)&&$errors->has('txtEmail'))
                                        <span class="help-block">
							<strong>Đia chỉ giao hàng không được để trống</strong>
                            </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{isset($errors)&& $errors->has('txtMessage') ? ' has-error' : '' }}">
                                <label for="txtMessage" class="control-label col-md-2 col-sm-2">Ghi chú</label>
                                <div class=" col-md-10 col-sm-10">
                                <textarea id="txtMessage" class="form-control " name="txtMessage" placeholder="VD: địa chỉ giao hàng, thời gian nhận hàng, màu dây ..."
                                          rows="3">{{old('txtMessage')}}</textarea>
										 <button type="submit" name="txtSubmit" id="submitPaymentOrder" class="btn">Gửi đơn
                                    hàng
                                </button> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection

