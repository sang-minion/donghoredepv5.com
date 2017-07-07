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
    <div class="row">
        <form name="frmSearch" class="frmSearch" id="frmSearch" method="GET">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Từ khóa</label>
                    <div>
                        <input type="text" class="form-control input-sm" name="order_name"
                               @if(isset($search['order_name']) && $search['order_name'] !='')value="{{$search['order_name']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Từ ngày</label>
                    <div>
                        <input type="text" class="form-control input-sm date" name="order_start"
                               @if(isset($search['order_start']) && $search['order_start'] !='')value="{{$search['order_start']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Đến ngày</label>
                    <div>
                        <input type="text" class="form-control input-sm date" name="order_end"
                               @if(isset($search['order_end']) && $search['order_end'] !='')value="{{$search['order_end']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Trạng thái</label>
                    <div>
                        <select name="order_status" class="form-control input-sm">
                            {!! $optionStatus !!}
                        </select>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1">
                    <div>
                        <button class="btn" name="submit" value="s" title="tìm kiếm"><i class="fa fa-search fa-2x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row menu-option">
        <div class="col-lg-8 col-md-8 col-sm-8"><h5>Quản lý: Đơn hàng [tổng số: {{$total}}]
			<i class="fa fa-circle fa-admin red">Đơn mới</i>
			<i class="fa fa-circle fa-admin green">Đơn đã xác nhận</i>
			<i class="fa fa-circle fa-admin green2">Đơn thành công</i>
			<i class="fa fa-circle fa-admin green3">Đơn hoàn</i>
			<i class="fa fa-circle fa-admin black">Đã Hủy</i>
		</h5>
		</div>
        <div class="col-lg-4 col-md-4 col-sm-4" style="text-align: right">
            <a href="javascript:void(0)"> <i class="fa fa-trash" id="deleteMoreItem" title="xóa item"></i> </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{route('admin.order_delete') }}" id="formListItem" name="formListItem" method="POST">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="1%">STT</th>
                            <th width="1%"><input id="checkAll" type="checkbox"></th>
                            <th width="10%">Tên khách hàng</th>
                            <th width="6%">Số điện thoại</th>
                            <th width="20%">Sản phẩm</th>
                            <th width="1%">SL</th>
                            <th width="7%">Tổng</th>
                            <th width="8%">Nguồn đơn</th>
                            <th width="6%">Ngày lập</th>
                            <th width="1%"><i class="fa fa-circle fa-admin"></i></th>
                            <th width="1%"><i class="fa fa-edit fa-admin"></i></th>
                        </tr>
                        @foreach($data as $k=>$item)
                            <?php
                            $arPD = $item->product_infor != NULL && $item->product_infor != '' ? unserialize($item->product_infor) : array();
                            ?>
                            <tr>
                                <td>{{$k+1}}</td>
                                <td><input class="checkItem" name="checkItem[]" type="checkbox"
                                           value="{{$item->order_id}}"></td>
                                <td>{{$item->order_name}}</td>
                                <td>{{$item->order_phone}}</td>
                                <td>
                                    @if(!empty($arPD))
                                        <ul>
                                            @foreach($arPD as $pd)
                                                <li>
                                                    <a href="{{Funclip::buildLinkDetailProduct($pd['id'],$pd['code'])}}"
                                                       target="_blank">{{$pd['title']}}</a>[{{$pd['num']}}]
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>{{$item->order_num}}</td>
                                <td>{{Funclip::numberFormat($item->order_total)}}đ</td>
                                <td>{{$item->order_srouces}}</td>
                                <td>{{date('d/m/Y',$item->order_created)}}</td>
                                <td>
								@if($item->order_status == CGlobal::status_hide)
                                        <i class="fa fa-circle fa-admin red"></i>
									@elseif($item->order_status == CGlobal::status_show)
                                        <i class="fa fa-circle fa-admin green"></i>
                                    @elseif($item->order_status == CGlobal::status_success)
                                        <i class="fa fa-circle fa-admin green2"></i>
									@elseif($item->order_status == CGlobal::status_cancel)
										<i class="fa fa-circle fa-admin green3"></i>
                                    @elseif($item->order_status == CGlobal::status_die)
                                        <i class="fa fa-circle fa-admin black"></i>
                                    @endif									
                                </td>
                                <td><a href="{{route('admin.order_edit',['id'=>$item->order_id])}}"><i
                                                class="fa fa-edit fa-admin"></i></a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </form>
        </div>
        @if(isset($total)&&$total>0)
            <div class="" style="text-align: center;padding: 0px 15px">
                @if(isset($paging))
                    {!! $paging !!}
                @endif
            </div>
        @endif
    </div>
@endsection