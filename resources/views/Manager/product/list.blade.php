<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 05/06/2017
 * Time: 10:00 SA
 */
 use \App\model\Product;
  use \App\model\Gift;
?>
@extends('layouts.admin')
@section('content')
    <div class="row">
        <form name="frmSearch" class="frmSearch" id="frmSearch" method="GET">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-3">
                    <label class="control-label">Mã sản phẩm</label>
                    <div>
                        <input type="text" class="form-control input-sm" name="product_code"
                               @if(isset($search['product_code']) && $search['product_code'] !='')value="{{$search['product_code']}}"@endif>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Danh mục sản phẩm</label>
                    <div>
                        <select name="product_cate_id" class="form-control input-sm">
                            {!! $optionCate !!}
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Trạng thái</label>
                    <div>
                        <select name="product_status" class="form-control input-sm">
                            {!! $optionStatus !!}
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label">Nhãn hiệu</label>
                    <div>
                        <select name="product_brand_id" class="form-control input-sm">
                            {!! $optionBrand !!}
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label"></label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="product_cheapest" value="1"
                                   @if(isset($search['product_cheapest'])&&$search['product_cheapest']==1) checked @endif />
                            Rẻ nhất
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label"></label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="product_most" value="1"
                                   @if(isset($search['product_most'])&&$search['product_most']==1) checked @endif />
                            Nhiều nhất
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label"></label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="product_news" value="1"
                                   @if(isset($search['product_news'])&&$search['product_news']==1) checked @endif />
                            Mới nhất
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label"></label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="product_buy_most" value="1"
                                   @if(isset($search['product_buy_most'])&&$search['product_buy_most']==1) checked @endif />
                            Mua nhiều nhất
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3">
                    <label class="control-label"></label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="product_best" value="1"
                                   @if(isset($search['product_best'])&&$search['product_best']==1) checked @endif />
                            Tốt nhất
                        </label>
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
        <div class="col-lg-6 col-md-6 col-sm-6"><h5>Quản lý: Sản phẩm [tổng số: {{$total}}]</h5></div>
        <div class="col-lg-6 col-md-6 col-sm-6" style="text-align: right">
            <a href="{{route('admin.product_edit')}}"> <i class="fa fa-plus" title="thêm mới"></i> </a>
            <a href="javascript:void(0)"> <i class="fa fa-trash" id="deleteMoreItem" title="xóa item"></i> </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form action="{{route('admin.product_delete') }}" id="formListItem" name="formListItem" method="POST">
                {{ csrf_field() }}
                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th width="1%">STT</th>
                            <th width="1%"><input id="checkAll" type="checkbox"></th>
                            <th width="10%">Mã</th>
                            <th width="20%">Tiêu đề</th>
							<th width="5%"><i class="fa fa-admin fa-picture-o"></i></th>
                            <th width="15%">Danh mục</th>
                            <th width="15%">Nhãn hiệu</th>
                            <th width="10%">Giá bán</th>
                            <th width="10">Thứ tự</th>
                            <th width="10">Ngày tạo</th>
                            <th width="1%"><i class="fa fa-circle fa-admin"></i></th>
                            <th width="1%"><i class="fa fa-edit fa-admin"></i></th>
                        </tr>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td><input class="checkItem" name="checkItem[]" type="checkbox"
                                           value="{{$item->product_id}}"></td>
                                <td>{{$item->product_code}}</td>
                                <td><a href="{{Funclip::buildLinkDetailProduct($item->product_id,$item->product_alias)}}" target="_blank" title="{{$item->product_title}}"> {{$item->product_title}} </a></td>
                                <td>@if($item->product_media!='')<img src="{{ThumbImg::thumbBaseNormal(Product::FOLDER,$item->product_id,$item->product_media,800,600,'',true,true,true)}}"  rel="{{ThumbImg::thumbBaseNormal(Gift::FOLDER,$item->product_id,$item->product_media,800,600,'',true,true,true)}}" id="showIMG" style="width:100%; min-width:80px"/>@endif</td>	  
                                
								<td>
                                    @foreach($arCate as $k=>$v)
                                        @if($item->product_cate_id==$k)
                                            {{$v}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($arBrand as $k=>$v)
                                        @if($item->product_brand_id==$k)
                                            {{$v}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{Funclip::numberFormat($item->product_price)}}đ</td>
                                <td>{{$item->product_order_no}}</td>
                                <td>{{date('d/m/Y',$item->product_created)}}</td>
                                <td>@if($item->product_status == CGlobal::status_show)
                                        <i class="fa fa-circle fa-admin green changestt" dataid="{{$item->product_id}}" datastt="{{$item->product_status}}" datatype="3" datamuti="0" title="click để thay đổi trạng thái"></i>
                                    @else
                                        <i class="fa fa-circle fa-admin red changestt" dataid="{{$item->product_id}}" datastt="{{$item->product_status}}" datatype="3" datamuti="0"  title="click để thay đổi trạng thái"></i>
                                    @endif
                                </td>
                                <td><a href="{{route('admin.product_edit',['id'=>$item->product_id])}}"><i
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