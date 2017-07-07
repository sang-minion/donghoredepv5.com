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
        <div class="form-group{{isset($errors)&& $errors->has('product_cate_id') ? ' has-error' : '' }}">
            <label for="product_cate_id" class="col-md-2 col-sm-3 control-label">Danh mục sản phẩn</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="product_cate_id">
                    {!! $optionCate !!}
                </select>
                @if (isset($errors)&&$errors->has('product_cate_id'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('product_cate_id') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('product_brand_id') ? ' has-error' : '' }}">
            <label for="product_brand_id" class="col-md-2 col-sm-3 control-label">Nhãn hiệu sản phẩn</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="product_brand_id">
                    {!! $optionBrand !!}
                </select>
                @if (isset($errors)&&$errors->has('product_brand_id'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('product_brand_id') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('product_code') ? ' has-error' : '' }}">
            <label for="category_title" class="col-md-2 col-sm-3 control-label">Mã sản phẩm</label>
            <div class="col-md-6 col-sm-9">
                <input id="product_code" type="text" class="form-control" name="product_code"
                       value="{{isset($data['product_code'])?$data['product_code']: old('product_code') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('product_code'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('product_code') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('product_title') ? ' has-error' : '' }}">
            <label for="product_title" class="col-md-2 col-sm-3 control-label">Tiêu đề</label>
            <div class="col-md-6 col-sm-9">
                <input id="product_title" type="text" class="form-control" name="product_title"
                       value="{{isset($data['product_title'])?$data['product_title']: old('product_title') }}"
                       required
                       autofocus>
                @if (isset($errors)&&$errors->has('product_title'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('product_title') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <!--<div class="form-group ">
            <label for="product_price_input" class="col-md-2 col-sm-3 control-label">Giá nhập</label>
            <div class="col-md-6 col-sm-9">
                <input id="product_price_input" type="number" class="form-control" name="product_price_input"
                       value="{{isset($data['product_price_input'])?$data['product_price_input']: old('product_price_input') }}"
                       autofocus>
            </div>
        </div>-->
        <div class="form-group{{isset($errors)&& $errors->has('product_price') ? ' has-error' : '' }}">
            <label for="product_price" class="col-md-2 col-sm-3 control-label">Giá bán</label>
            <div class="col-md-6 col-sm-9">
                <input id="product_price" type="number" class="form-control" name="product_price"
                       value="{{isset($data['product_price'])?$data['product_price']: old('product_price') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('product_price'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('product_price') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group ">
            <label for="product_multi_price" class="col-md-2 col-sm-3 control-label">Giá khác <i
                        class="fa fa-plus green fa-admin" id="themGIa" title="thêm bộ giá"></i></label>
            <div class="col-md-6 col-sm-9">
                <ul id="listGia">
                    <?php
                    $argia = isset($data['product_price_multi'])&& $data['product_price_multi']!=NULL && $data['product_price_multi'] != '' ? unserialize($data['product_price_multi']) : array();
                    ?>
                    @if(!empty($argia))
                        @foreach($argia as $k=>$v)
                            <li>
                                Giá mua : <input class="form-control level-price" type="number" name="level[]" min="1"
                                                 value="{{$k}}" autofocus> :
                                <input class="form-control price" type="number" name="price[]" min="0" value="{{$v}}"
                                       autofocus>
                                <i class="fa fa-remove red fa-admin" id="xoa-gia"></i>
                            </li>
                        @endforeach
                    @else
                        @if( old('level')!=null && count( old('level')>0))
                            @foreach(old('level') as $k=>$v)
                                @if($v!='')
                                    <li>
                                        <input type="number" class="form-control level-price" name="level[]"
                                               value="{{$v}}" autofocus>
                                        <input class="form-control price" type="number" name="price[]" min="0" value="{{isset(old('price')[$k])?old('price')[$k]:1}}"
                                               autofocus>
                                        <i class="fa fa-remove red fa-admin" id="xoa-gia"></i>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <li>
                                Giá mua : <input class="form-control level-price" type="number" name="level[]" min="1"
                                                 value="1" autofocus> :
                                <input class="form-control price" type="number" name="price[]" min="0" autofocus>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
        <div class="form-group ">
            <label for="product_price_saleof" class="col-md-2 col-sm-3 control-label">Giá mới (saleof) </label>
            <div class="col-md-6 col-sm-9">
                <input id="product_price_saleof" type="number" class="form-control" name="product_price_saleof"
                       value="{{isset($data['product_price_saleof'])?$data['product_price_saleof']: old('product_price_saleof') }}"
                       autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="product_gift_code" class="col-md-2 col-sm-3 control-label">Chọn quà tặng</label>
            <div class="col-md-6 col-sm-9">
                <?php
                $arGifcode = isset($data['product_gift_code']) && $data['product_gift_code'] != NULL && $data['product_gift_code'] != '' ? unserialize($data['product_gift_code']) : array();

                ?>
                @if(!empty($Gift))
                    @foreach($Gift as $item)
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="product_gift_code[{{$item->gift_id}}]" value="{{$item->gift_code}}"
                                       @if($arGifcode!=NULL&&(in_array($item->gift_id,array_keys($arGifcode))||in_array($item->gift_code,$arGifcode))) checked @endif />
                                {{$item->gift_title}}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('product_video') ? ' has-error' : '' }}">
            <label for="product_video" class="col-md-2 col-sm-3 control-label">Link video <i
                        class="fa fa-plus green fa-admin" id="themLinkVideo" title="thêm bộ giá"></i></label>
            <div class="col-md-6 col-sm-9">
                <ul id="listLinkVideo">
                    <?php
                    $arlinkVideo = isset($data['product_video']) && $data['product_video'] != '' ? unserialize($data['product_video']) : array();
                    ?>
                    @if(!empty($arlinkVideo))
                        @foreach($arlinkVideo as $k=>$v)
                            <li>
                                <input id="product_video" type="text" class="form-control" name="product_video[]"
                                       value="{{$v}}" autofocus placeholder="link video intro">
                                <i class="fa fa-remove red fa-admin" id="xoa-link"></i>
                            </li>
                        @endforeach
                    @else
                        @if( old('product_video')!=null && count( old('product_video')>0))
                            @foreach(old('product_video') as $v)
                                @if($v!='')
                                    <li>
                                        <input id="product_video" type="url" class="form-control" name="product_video[]"
                                               value="{{$v}}" autofocus placeholder="link video intro">
                                        <i class="fa fa-remove red fa-admin" id="xoa-link"></i>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <li>
                                <input id="product_video" type="url" class="form-control" name="product_video[]"
                                       value="{{ old('product_video[0]')}}" autofocus placeholder="link video intro">
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
        <div class="form-group">
            <label for="product_media" class="col-md-2 col-sm-3 control-label">Hình ảnh sản phẩm</label>
            <div class="col-md-6 col-sm-9">
                <label class="btn btn-primary" for="product_media">Upload hình ảnh</label> <i id="fileName"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" name="product_media"
                       id="product_media" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG"
                     @if(isset($data['product_media'])&&$data['product_media']!='') rel="1" @else  rel="0" @endif>
                    @if(isset($data['product_media'])&&$data['product_media']!='')
                        <i class="fa fa-remove red" id="xoa-media" title="xóa"></i>
                        <img src="{{ThumbImg::thumbBaseNormal(\App\model\Product::FOLDER,$id,$data['product_media'],400,400,'',true,true,true)}}"
                             width="100%"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="product_multi_media" class="col-md-2 col-sm-3 control-label">Album ảnh</label>
            <div class="col-md-10 col-sm-9">
                <label class="btn btn-primary" for="product_multi_media">Upload album ảnh</label> <i id="totalList"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" multiple
                       name="product_multi_media[]"
                       id="product_multi_media" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG"
                     @if(isset($data['product_multi_media'])&&$data['product_multi_media']!='') rel="1"
                     @else  rel="0" @endif>
                    <ul id="showListIMG">
                        @if(isset($data['product_multi_media'])&&$data['product_multi_media']!='')
                            <?php
                            $ar = isset($data['product_multi_media']) && $data['product_multi_media'] != '' ? unserialize($data['product_multi_media']) : array();
                            ?>
                            @foreach($ar as $k=>$v)
                                <li class="col-md-3 col-sm-4" rel="{{$k}}">
                                    <i class="fa fa-remove red" id="xoa-multi-media" title="xóa" rel="{{$k}}"></i>
                                    <img src="{{ThumbImg::thumbBaseNormal(\App\model\Product::FOLDER,$id,$v,400,400,'',true,true,true)}}"
                                         style=""/>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('product_status') ? ' has-error' : '' }}">
            <label for="product_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="product_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('product_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('product_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="product_status" class="col-md-2 col-sm-3 control-label">Tình trạng sản phẩm</label>
            <div class="col-md-6 col-sm-9">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="product_cheapest" value="1"
                               @if((isset($data['product_cheapest'])&&$data['product_cheapest']==1)||old('product_cheapest')==1) checked @endif />
                        Rẻ nhất
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="product_most" value="1"
                               @if((isset($data['product_most'])&&$data['product_most']==1)||old('product_most')==1) checked @endif />
                        Nhiều nhất
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="product_news" value="1"
                               @if((isset($data['product_news'])&&$data['product_news']==1)||old('product_news')==1) checked @endif />
                        Mới nhất
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="product_buy_most" value="1"
                               @if((isset($data['product_buy_most'])&&$data['product_buy_most']==1)||old('product_buy_most')==1) checked @endif />
                        Mua nhiều nhất
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="product_best" value="1"
                               @if((isset($data['product_best'])&&$data['product_best']==1)||old('product_best')==1) checked @endif />
                        Tốt nhất
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="product_why" class="col-md-2 col-sm-3 control-label">Mô tả vì sao nên mua</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="product_why" class="form-control ckeditor" name="product_why" rows="3"
                          autofocus>{{isset($data['product_why'])? stripslashes($data['product_why']):old('product_why')}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="product_intro" class="col-md-2 col-sm-3 control-label">Nội dung quà tặng</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="product_intro" class="form-control ckeditor" name="product_intro" rows="3"
                          autofocus>{{isset($data['product_intro'])? stripslashes($data['product_intro']):old('product_intro')}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="product_details" class="col-md-2 col-sm-3 control-label">Mô tả chi tiết</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="product_details" class="form-control ckeditor" name="product_details" rows="3"
                          autofocus>{{isset($data['product_details'])? stripslashes($data['product_details']):old('product_details')}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="product_order_no" class="col-md-2 col-sm-3 control-label">Thứ tự</label>
            <div class="col-md-6 col-sm-9">
                <input type="number" class="form-control" name="product_order_no" id="product_order_no" min="0"
                       value="{{isset($data['product_order_no'])?$data['product_order_no']:old('product_order_no')}}">
            </div>
        </div>
        <div class="form-group ">
            <label for="meta_title" class="col-md-2 col-sm-3 control-label">Meta title</label>
            <div class="col-md-6 col-sm-9">
                <input type="text" class="form-control" name="meta_title" id="meta_title"
                       value="{{isset($data['meta_title'])?$data['meta_title']:old('meta_title')}}">
            </div>
        </div>
        <div class="form-group ">
            <label for="meta_keywords" class="col-md-2 col-sm-3 control-label">Meta keywords</label>
            <div class="col-md-6 col-sm-9">
                <input type="text" class="form-control" name="meta_keywords" id="meta_keywords"
                       value="{{isset($data['meta_keywords'])?$data['meta_keywords']:old('meta_keywords')}}">
            </div>
        </div>
        <div class="form-group ">
            <label for="meta_keywords" class="col-md-2 col-sm-3 control-label">Meta description</label>
            <div class="col-md-10 col-sm-9">
			<textarea id="meta_description" class="form-control" name="meta_description" rows="3" autofocus>{{isset($data['meta_description'])?$data['meta_description']:old('meta_description')}}</textarea>
                <!--<input type="text" class="form-control" name="meta_description" id="meta_description"
                       value="{{isset($data['meta_description'])?$data['meta_description']:old('meta_description')}}">-->
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <input type="hidden" id="remove_media" name="remove_media" value="0"/>
                <input type="hidden" id="remove_multi_media" name="remove_multi_media[]" value="-1"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
    </form>
@endsection
