<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 05/06/2017
 * Time: 10:00 SA
 */
?>
@extends('layouts.admin')
@section('content')
    <form action="" class="form-horizontal frmAdd" method="POST" id="frmAdd" name="frmAdd"
          enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 ">
                <div class="panel panel-default">
                    <div class="panel-heading">Thông tin đặt hàng</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th width="30%">Họ tên KH</th>
                                    <td>{{$data->order_name}}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Số ĐT</th>
                                    <td>{{$data->order_phone}}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Email</th>
                                    <td>{{$data->order_mail}}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Địa chỉ</th>
                                    <td>{{$data->order_address}}</td>
                                </tr>
                                <tr>
                                    <th width="30%">Ghi chú</th>
                                    <td>{{$data->order_note}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 ">
                <div class="panel panel-default">
                    <div class="panel-heading">Thông tin về đơn hàng</div>
                    <div class="panel-body">
                        <h4>Nguồn đơn: {{$data->order_resources}}</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th width="30%">Tên Sản phẩm</th>
                                    <th width="5%">Số lượng</th>
                                    <th width="15%">Giá</th>
                                    <th width="15%">Tạm tính</th>
                                </tr>
                                <?php
                                    $total = 0;
                                    $arPD = $data->product_infor != NULL && $data->product_infor != '' ? unserialize($data->product_infor) : array();
                                    $arGift = $data->order_gift_code!=NULL&&$data->order_gift_code!=''?unserialize($data->order_gift_code):array();
                                ?>
                                @if(!empty($arPD))
                                    @foreach($arPD as $item)
                                        <tr>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="{{Funclip::buildLinkDetailProduct($item['id'],$item['code'])}}" target="_blank">{{$item['title']}}</a>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>{{Funclip::numberFormat($item['num'])}}</td>
                                            <td>{{Funclip::numberFormat($item['price'])}}đ</td>
                                            <td>{{Funclip::numberFormat($item['num']*$item['price'])}}đ<?php $total = $item['num']*$item['price']; ?></td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th>Quà tặng</th>
                                    <?php $arg = $data->order_gift_code!=Null&&$data->order_gift_code!=''?unserialize($data->order_gift_code):array(); ?>
                                    <td colspan="3">{{!empty($arg)?$arg['title']:''}}</td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền: </th>
                                    <td colspan="3">{{Funclip::numberFormat($data->order_total)}}đ</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group clear">
                <label for="news_status" class="col-md-2 col-sm-3 control-label">Trạng thái </label>
                <div class="col-md-4 col-sm-9">
                    <select class="form-control" name="order_status">
                        {!! $optionStatus !!}
                    </select>
                </div>
            </div>
            <div class="col-md-6 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
    </form>
@endsection
