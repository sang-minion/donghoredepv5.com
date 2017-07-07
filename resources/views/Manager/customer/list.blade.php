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
                        <input type="text" class="form-control input-sm" name="name"
                               @if(isset($search['name']) && $search['name'] !='')value="{{$search['name']}}"@endif>
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
        <div class="col-lg-6 col-md-6 col-sm-6"><h5>Quản lý: Email khách hàng [tổng số: {{$total}}]</h5></div>
        <div class="col-lg-6 col-md-6 col-sm-6" style="text-align: right">
            <a href="javascript:void(0)"> <i class="fa fa-trash" id="deleteMoreItem" title="xóa item"></i> </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{route('admin.customer_delete') }}" id="formListItem" name="formListItem" method="POST">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="1%">STT</th>
                            <th width="1%"><input id="checkAll" type="checkbox"></th>
                            <th width="20%">Họ tên</th>
                            <th width="10">Số điện thoại</th>
                            <th width="20">Địa chỉ</th>
                            <th width="10">Email</th>
                            <th width="10%">Ngày tạo</th>
                        </tr>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td><input class="checkItem" name="checkItem[]"  type="checkbox" value="{{$item->customer_id}}"> </td>
                                <td>{{$item->customer_name}}</td>
                                <td>{{$item->customer_phone}}</td>
                                <td>{{$item->customer_address}}</td>
                                <td>{{$item->customer_mail}}</td>
                                <td>{{date('d/m/Y',strtotime($item->created_at))}}</td>
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