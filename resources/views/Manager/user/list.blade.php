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
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Nhóm quyền</label>
                    <div>
                        <select name="user_role_id" class="form-control input-sm">
                            {!! $optionRole !!}
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Trạng thái</label>
                    <div>
                        <select name="user_status" class="form-control input-sm">
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
        <div class="col-lg-6 col-md-6 col-sm-6"><h5>Quản lý: Người dùng [tổng số: {{$total}}]</h5></div>
        <div class="col-lg-6 col-md-6 col-sm-6" style="text-align: right">
            <a href="{{route('admin.user_edit')}}"> <i class="fa fa-plus" title="thêm mới"></i> </a>
            <a href="javascript:void(0)"> <i class="fa fa-trash" id="deleteMoreItem" title="xóa item"></i> </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{route('admin.user_delete') }}" id="formListItem" name="formListItem" method="POST">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="1%">STT</th>
                            <th width="1%"><input id="checkAll" type="checkbox"></th>
                            <th width="10%">Nhóm quyền</th>
                            <th width="15%">Họ Tên</th>
                            <th width="10%">Email</th>
                            <th width="10">SĐT</th>
                            <th width="20%">Địa chỉ</th>
                            <th width="10%">Ngày tạo</th>
                            <th width="1%"><i class="fa fa-circle fa-admin"></i></th>
                            <th width="1%"><i class="fa fa-edit fa-admin"></i></th>
                        </tr>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td><input class="checkItem" name="checkItem[]" type="checkbox"
                                           value="{{$item->id}}"></td>
                                <td>
                                    @foreach($arrRole as $k=>$v)
                                        @if($item->user_role_id==$k)
                                            {{$v}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->user_phone}}</td>
                                <td>{{$item->user_address}}</td>
                                <td>{{ date('d/m/Y', strtotime( $item->created_at))}}</td>
                                <td>@if($item->user_status == CGlobal::status_show)
                                        <i class="fa fa-circle fa-admin green changestt" dataid="{{$item->id}}" datastt="{{$item->user_status}}" datatype="7" datamulti="0" title="click để thay đổi trạng thái"></i>
                                    @else
                                        <i class="fa fa-circle fa-admin red changestt" dataid="{{$item->id}}" datastt="{{$item->user_status}}" datatype="7" datamulti="0" title="click để thay đổi trạng thái"></i>
                                    @endif
                                </td>
                                <td><a href="{{route('admin.user_edit',['id'=>$item->id])}}"><i
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