<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 05/06/2017
 * Time: 10:00 SA
 */
 use \App\model\Gift;
?>
@extends('layouts.admin')
@section('content')
    <div class="row">
        <form name="frmSearch" class="frmSearch" id="frmSearch" method="GET">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Mã quà tặng</label>
                    <div>
                        <input type="text" class="form-control input-sm" name="gift_code"
                               @if(isset($search['gift_code']) && $search['gift_code'] !='')value="{{$search['gift_code']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Trạng thái</label>
                    <div>
                        <select name="gift_status" class="form-control input-sm">
                            {!! $optionStatus !!}
                        </select>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1">
                    <div>
                        <button class="btn" name="submit" value="s" title="tìm kiếm"><i
                                    class="fa fa-search fa-2x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row menu-option">
        <div class="col-lg-6 col-md-6 col-sm-6"><h5>Quản lý: QUà tặng [tổng số: {{$total}}]</h5></div>
        <div class="col-lg-6 col-md-6 col-sm-6" style="text-align: right">
            <a href="{{route('admin.gift_edit')}}"> <i class="fa fa-plus" title="thêm mới"></i> </a>
            <a href="javascript:void(0)"> <i class="fa fa-trash" id="deleteMoreItem" title="xóa item"></i> </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{route('admin.gift_delete') }}" id="formListItem" name="formListItem" method="POST">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="1%">STT</th>
                            <th width="1%"><input id="checkAll" type="checkbox"></th>
                            <th width="10%">Mã</th>
                            <th width="20%">Tiêu đề</th>
							<th width="5%"><i class="fa fa-admin fa-picture-o"></i></th>
                            <th width="10%">Giá bán</th>
                            <th width="10">Ngày tạo</th>
                            <th width="1%"><i class="fa fa-circle fa-admin"></i></th>
                            <th width="1%"><i class="fa fa-edit fa-admin"></i></th>
                        </tr>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td><input class="checkItem" name="checkItem[]" type="checkbox"
                                           value="{{$item->gift_id}}"></td>
                                <td>{{$item->gift_code}}</td>
                                <td>{{$item->gift_title}}</td>
                                <td>@if($item->gift_media!='')<img src="{{ThumbImg::thumbBaseNormal(Gift::FOLDER,$item->gift_id,$item->gift_media,800,600,'',true,true,true)}}"  rel="{{ThumbImg::thumbBaseNormal(Gift::FOLDER,$item->gift_id,$item->gift_media,800,600,'',true,true,true)}}" id="showIMG" style="width:100%; min-width:80px"/>@endif</td>	  
                                <td>{{Funclip::numberFormat($item->gift_price)}}đ</td>
                                <td>{{date('d/m/Y',$item->gift_created)}}</td>
                                <td>@if($item->gift_status == CGlobal::status_show)
                                        <i class="fa fa-circle fa-admin green changestt" dataid="{{$item->gift_id}}" datastt="{{$item->gift_status}}" datatype="11" datamulti="0" title="click để thay đổi trạng thái"></i>
                                    @else
                                        <i class="fa fa-circle fa-admin red changestt" dataid="{{$item->gift_id}}" datastt="{{$item->gift_status}}" datatype="11"  datamulti="0" title="click để thay đổi trạng thái"></i>
                                    @endif
                                </td>
                                <td><a href="{{route('admin.gift_edit',['id'=>$item->gift_id])}}"><i
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