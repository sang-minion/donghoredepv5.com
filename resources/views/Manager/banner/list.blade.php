<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 05/06/2017
 * Time: 10:00 SA
 */
 use \App\model\Banner;
?>
@extends('layouts.admin')
@section('content')
    <div class="row">
        <form name="frmSearch" class="frmSearch" id="frmSearch" method="GET">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Từ khóa</label>
                    <div>
                        <input type="text" class="form-control input-sm" name="banner_title"
                               @if(isset($search['banner_title']) && $search['banner_title'] !='')value="{{$search['banner_title']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Trạng thái</label>
                    <div>
                        <select name="banner_status" class="form-control input-sm">
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
        <div class="col-lg-6 col-md-6 col-sm-6"><h5>Quản lý: Banner quảng cáo [tổng số: {{$total}}]</h5></div>
        <div class="col-lg-6 col-md-6 col-sm-6" style="text-align: right">
            <a href="{{route('admin.banner_edit')}}"> <i class="fa fa-plus" title="thêm mới"></i> </a>
            <a href="javascript:void(0)"> <i class="fa fa-trash" id="deleteMoreItem" title="xóa item"></i> </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{route('admin.banner_delete') }}" id="formListItem" name="formListItem" method="POST">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="1%">STT</th>
                            <th width="1%"><input id="checkAll" type="checkbox"></th>
							<th width="5%"><i class="fa fa-admin fa-picture-o"></i></th>
                            <th width="10%">Tiêu đề</th>
                            <th width="2%">Vị trí</th>
                            <th width="20%">Link intro</th>
                            <th width="5%">Ngày tạo</th>
							<th width="1%">Ghim</th>
                            <th width="1%"><i class="fa fa-circle fa-admin"></i></th>
                            <th width="1%"><i class="fa fa-edit fa-admin"></i></th>
                        </tr>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>
									<input class="checkItem" name="checkItem[]" type="checkbox" value="{{$item->banner_id}}">
								</td>
								<td><img src="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$item->banner_id,$item->banner_media,800,600,'',true,true,true)}}"  rel="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$item->banner_id,$item->banner_media,800,600,'',true,true,true)}}" id="showIMG" style="width:100%; min-width:30px"/></td>		  
                                <td>@if($item->banner_media!='') <a href="javascript:void(0)" title="{{$item->banner_title}}" rel="{{ThumbImg::thumbBaseNormal(Banner::FOLDER,$item->banner_id,$item->banner_media,800,600,'',true,true,true)}}" id="showIMG"> {{$item->banner_title}} </a>@else {{$item->banner_title}} @endif</td>
                                <td>{{$item->banner_order_no}}</td>
                                <td>{{$item->banner_link}}</td>
                                <td>{{date('d/m/Y',$item->banner_created)}}</td>
								<td><i class="fa fa-check fa-admin  {{$item->banner_ghim==\CGlobal::status_show?' green':' red'}}"></i></td>
                                <td>@if($item->banner_status == CGlobal::status_show)
                                        <i class="fa fa-circle fa-admin green changestt" dataid="{{$item->banner_id}}" datastt="{{$item->banner_status}}" datatype="2" datamulti="0" title="click để thay đổi trạng thái"></i>
                                    @else
                                        <i class="fa fa-circle fa-admin green changestt" dataid="{{$item->banner_id}}" datastt="{{$item->banner_status}}" datatype="2" datamulti="0" title="click để thay đổi trạng thái"></i>
                                    @endif
                                </td>
                                <td><a href="{{route('admin.banner_edit',['id'=>$item->banner_id])}}"><i
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