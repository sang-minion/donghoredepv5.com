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
        <div class="form-group{{isset($errors)&& $errors->has('gift_code') ? ' has-error' : '' }}">
            <label for="gift_code" class="col-md-2 col-sm-3 control-label">Mã quà tặng</label>
            <div class="col-md-6 col-sm-9">
                <input id="gift_code" type="text" class="form-control" name="gift_code"
                       value="{{isset($data['gift_code'])?$data['gift_code']: old('gift_code') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('gift_code'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('gift_code') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('gift_title') ? ' has-error' : '' }}">
            <label for="gift_title" class="col-md-2 col-sm-3 control-label">Tiêu đề</label>
            <div class="col-md-6 col-sm-9">
                <input id="gift_title" type="text" class="form-control" name="gift_title"
                       value="{{isset($data['gift_title'])?$data['gift_title']: old('gift_title') }}"
                       required
                       autofocus>
                @if (isset($errors)&&$errors->has('gift_title'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('gift_title') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group ">
            <label for="gift_price_input" class="col-md-2 col-sm-3 control-label">Giá nhập </label>
            <div class="col-md-6 col-sm-9">
                <input id="gift_price_input" type="number" class="form-control" name="gift_price_input"
                       value="{{isset($data['gift_price_input'])?$data['gift_price_input']: old('gift_price_input') }}">
            </div>
        </div>
        <div class="form-group">
            <label for="gift_price" class="col-md-2 col-sm-3 control-label">Gián bán </label>
            <div class="col-md-6 col-sm-9">
                <input id="gift_price" type="number" class="form-control" name="gift_price"
                       value="{{isset($data['gift_price'])?$data['gift_price']: old('gift_price') }}"
                       autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="gift_media" class="col-md-2 col-sm-3 control-label">Hình ảnh quà tặng</label>
            <div class="col-md-6 col-sm-9">
                <label class="btn btn-primary" for="gift_media">Upload hình ảnh</label>  <i id="fileName"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" name="gift_media" id="gift_media" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG" @if(isset($data['gift_media'])&&$data['gift_media']!='') rel="1" @else  rel="0"  @endif>
                    @if(isset($data['gift_media'])&&$data['gift_media']!='')
                        <i class="fa fa-remove red" id="xoa-media" title="xóa"></i>
                        <img src="{{ThumbImg::thumbBaseNormal(\App\model\Gift::FOLDER,$id,$data['gift_media'],400,400,'',true,true,true)}}" width="100%"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="gift_multi_media" class="col-md-2 col-sm-3 control-label">Album ảnh</label>
            <div class="col-md-10 col-sm-9">
                <label class="btn btn-primary" for="gift_multi_media">Upload album ảnh</label> <i id="totalList"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" multiple
                       name="gift_multi_media[]"
                       id="gift_multi_media" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG"
                     @if(isset($data['gift_multi_media'])&&$data['gift_multi_media']!='') rel="1"
                     @else  rel="0" @endif>
                    <ul id="showListIMG">
                        @if(isset($data['gift_multi_media'])&&$data['gift_multi_media']!='')
                            <?php
                            $ar = isset($data['gift_multi_media']) && $data['gift_multi_media'] != '' ? unserialize($data['gift_multi_media']) : array();
                            ?>
                            @foreach($ar as $k=>$v)
                                <li class="col-md-3 col-sm-4" rel="{{$k}}">
                                    <i class="fa fa-remove red" id="xoa-multi-media" title="xóa" rel="{{$k}}"></i>
                                    <img src="{{ThumbImg::thumbBaseNormal(\App\model\Gift::FOLDER,$id,$v,400,400,'',true,true,true)}}"
                                         style=""/>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('gift_status') ? ' has-error' : '' }}">
            <label for="gift_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="gift_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('gift_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('gift_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="gift_intro" class="col-md-2 col-sm-3 control-label">Mô tả</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="gift_intro" class="form-control ckeditor" name="gift_intro" rows="3"
                          autofocus>{{isset($data['gift_intro'])? stripslashes($data['gift_intro']):old('gift_intro')}}</textarea>
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
