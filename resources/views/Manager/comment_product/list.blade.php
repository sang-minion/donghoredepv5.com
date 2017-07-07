<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 05/06/2017
 * Time: 10:00 SA
 */
 use \App\model\CommentHome;
?>
@extends('layouts.admin')
@section('content')
    <div class="row">
        <form name="frmSearch" class="frmSearch" id="frmSearch" method="GET">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Tên khách hàng</label>
                    <div>
                        <input type="text" class="form-control input-sm" name="name"
                               @if(isset($search['name']) && $search['name'] !='')value="{{$search['name']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Từ ngày</label>
                    <div>
                        <input type="text" class="form-control input-sm date" name="comment_start"
                               @if(isset($search['comment_start']) && $search['comment_start'] !='')value="{{$search['comment_start']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Đến ngày</label>
                    <div>
                        <input type="text" class="form-control input-sm date" name="comment_end"
                               @if(isset($search['comment_end']) && $search['comment_end'] !='')value="{{$search['comment_end']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Mã sản phẩm</label>
                    <div>
                        <select name="comment_product_id" class="form-control input-sm">
                            {!! $optionPRD !!}
                        </select>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Trạng thái</label>
                    <div>
                        <select name="comment_status" class="form-control input-sm">
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
        <div class="col-lg-6 col-md-6 col-sm-6"><h5>Quản lý: Comment sản phẩm [tổng số: {{$total}}]
                <i class="fa fa-circle fa-admin red">Chờ duyệt </i>
                <i class="fa fa-circle fa-admin green">Hiện</i>
                <i class="fa fa-circle fa-admin black">Đã ẩn </i>
            </h5></div>
        <div class="col-lg-6 col-md-6 col-sm-6" style="text-align: right">
            {{--<a href="{{route('admin.comment_product_eit')}}"> <i class="fa fa-plus" title="thêm mới"></i> </a>--}}
            <a href="javascript:void(0)"> <i class="fa fa-trash" id="deleteMoreItem" title="xóa item"></i> </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{route('admin.comment_product_delete') }}" id="formListItem" name="formListItem" method="POST">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="1%">STT</th>
                            <th width="1%"><input id="checkAll" type="checkbox"></th>
                            <th width="5%">Thành phần</th>
                            <th width="10%">Họ tên </th>
                            <th width="10%">SĐT</th>
                            <th width="50%">Nội dung bình luận</th>
                            <th width="8%">Ngày</th>
                            <th width="1%"><i class="fa fa-mail-forward fa-admin"></i></th>
                            <th width="1%"><i class="fa fa-circle fa-admin"></i></th>
                        </tr>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>
									<input class="checkItem" name="checkItem[]" type="checkbox" value="{{$item->comment_id}}">
								</td>
                                <td>{{$item->comment_user_role==0?'Khách':'Quản trị viên'}}</td>
								<td>{{$item->comment_name}}</td>
                                <td>{{$item->comment_phone}}</td>
                                <td>{{$item->comment_content}}</td>
                                <td>{{date('d/m/Y H:i:s',$item->comment_created)}}</td>
                                <th width="1%"><a href="{{isset($arProduct[$item->comment_product_id])?Funclip::buildLinkDetailProduct($item->comment_product_id,$arProduct[$item->comment_product_id]):0}}" title="xem trang chi tiết" target="_blank"><i class="fa fa-mail-forward fa-admin"></i></a></th>
                                <td>
									@if($item->comment_status == CGlobal::status_show)
                                        <i class="fa fa-circle fa-admin green changestt" dataid="{{$item->comment_id}}" datastt="{{$item->comment_status}}" datatype="1" datamulti="1" title="click để thay đổi trạng thái"></i>
                                    @elseif($item->comment_status == CGlobal::status_hide)
                                        <i class="fa fa-circle fa-admin red changestt" dataid="{{$item->comment_id}}" datastt="{{$item->comment_status}}" datatype="1" datamulti="1" title="click để thay đổi trạng thái"></i>
                                    @elseif($item->comment_status == CGlobal::status_die)
                                        <i class="fa fa-circle fa-admin black changestt" dataid="{{$item->comment_id}}" datastt="{{$item->comment_status}}" datatype="1" datamulti="1" title="click để thay đổi trạng thái"></i>
                                    @endif
                                </td>
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