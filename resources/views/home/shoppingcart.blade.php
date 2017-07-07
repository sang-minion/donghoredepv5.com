<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 25/06/2017
 * Time: 13:13 CH
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
        <div class="details-shoppingCart">
            <h2 class="title">Sản phẩm trong giỏ hàng</h2>
            <form class="form-horizontal" method="POST" id="txtFormShopCart">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-hover">
                        <tr class="">
                            <th width="1%">STT</th>
                            <th width="10%">Hình ảnh</th>
                            <th width="30%">Tên sản phẩm</th>
                            <th width="10%">Số lượng</th>
                            <th width="15%">Giá</th>
                            <th width="15%">Thành tiền</th>
                            <th> <a class="btn btn-danger" title="xóa tất cả sản phẩm trong giỏ hàng" id="dellAllCart" data="del-all"><i class="fa fa-trash"></i></a></th>
                        </tr>
                        <?php $total = 0; ?>
                        @foreach($dataItem as $k=>$item)
						<?php 
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
                                    <img src="{{ThumbImg::thumbBaseNormal(\App\model\Product::FOLDER,$item->product_id,$item->product_media,140,100,'',true,true,true)}}"/>
                                </td>
                                <td>
                                    <a href="{{Funclip::buildLinkDetailProduct($item->product_id,$item->product_alias)}}"
                                       target="_blank"
                                       title="{{$item->product_title}}">{{$item->product_title}}</a></td>
                                <td><input class="form-control" type="number" name="listCart[{{$item->product_id}}]"
                                           value="{{$dataCart[$item->product_id]}}" id="Pnum"  dataId="{{$item->product_id}}" min="1"></td>
                                <td id="Price{{$item->product_id}}" >{{Funclip::numberFormat($price)}}đ/sản phẩm</td>
                                <td id="TotalPrice{{$item->product_id}}" data="{{$price*$dataCart[$item->product_id]}}">{{Funclip::numberFormat($price*$dataCart[$item->product_id])}}
                                    đ <?php $total += $price * $dataCart[$item->product_id]; ?></td>
                                <td>
                                    <a class="btn btn-danger" title="xóa sản phẩm trong giỏ hàng" id="delOneItemCart"
                                       data="{{$item->product_id}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
							<?php $price=0; ?>
                        @endforeach
                        <tr class="">
                            <td colspan="3">
                                <a href="{{route('index')}}" class="btn btn-warning btn-backIndex">
                                    <i class="fa fa-angle-left"></i> Tiếp tục mua hàng</a>
                            </td>
                            <td>
                                <a class="btn btn-primary" title="Cập nhật giỏ hàng" id="updateCart" style="display:none">Cập nhật</a>
                            <td colspan="2" id="TotalMax" data="{{$total}}">
                                Tổng tiền : {{Funclip::numberFormat($total)}}đ
                            </td>
                            <td>
                                <a href="{{route('dathang')}}" class="btn btn-success btn-senshoppingCart">Thanh toán
                                    <i class="fa fa-angle-right"></i></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <div class="product-category">
            @if(isset($paging))
                <div class="paging-product">
                    {!! $paging !!}
                </div>
            @endif
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection
