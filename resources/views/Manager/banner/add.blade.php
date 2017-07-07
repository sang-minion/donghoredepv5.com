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
    <form action="" class="form-horizontal frmAdd" method="POST" id="frmAdd" name="frmAdd" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group{{isset($errors)&& $errors->has('banner_title') ? ' has-error' : '' }}">
            <label for="banner_title" class="col-md-2 col-sm-3 control-label">Tiêu đề</label>
            <div class="col-md-6 col-sm-9">
                <input id="banner_title" type="text" class="form-control" name="banner_title"
                       value="{{isset($data['banner_title'])?$data['banner_title']: old('banner_title') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('banner_title'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('banner_title') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('banner_link') ? ' has-error' : '' }}">
            <label for="banner_link" class="col-md-2 col-sm-3 control-label">Link intro</label>
            <div class="col-md-6 col-sm-9">
                <input id="banner_link" type="text" class="form-control" name="banner_link"
                       value="{{isset($data['banner_link'])?$data['banner_link']: old('banner_link') }}"
                       autofocus>
                @if (isset($errors)&&$errors->has('banner_link'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('banner_link') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="banner_media" class="col-md-2 col-sm-3 control-label">Hình ảnh </label>
            <div class="col-md-6 col-sm-9">
                <label class="btn btn-primary" for="banner_media">Upload hình ảnh</label>  <i id="fileName"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" name="banner_media" id="banner_media" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG" @if(isset($data['banner_media'])&&$data['banner_media']!='') rel="1" @else  rel="0"  @endif>
                    @if(isset($data['banner_media'])&&$data['banner_media']!='')
                        <i class="fa fa-remove red" id="xoa-media" title="xóa"></i>
                        <img src="{{ThumbImg::thumbBaseNormal(\App\model\Banner::FOLDER,$id,$data['banner_media'],400,400,'',true,true,true)}}" width="100%"/>
                    @endif
                </div>
            </div>
        </div>
		<div class="form-group">
            <label for="banner_order_no" class="col-md-2 col-sm-3 control-label">Ghim</label>
            <div class="col-md-6 col-sm-9">
                <div class="checkbox">
                            <label>
                                <input type="checkbox" name="banner_ghim" value="1" @if(isset($data['banner_ghim'])&&$data['banner_ghim']==\CGlobal::status_show) checked @endif />
                                Ghim ưu tiên
                            </label>
                        </div>
            </div>
        </div>
        <div class="form-group">
            <label for="banner_order_no" class="col-md-2 col-sm-3 control-label">Thứ tự</label>
            <div class="col-md-6 col-sm-9">
                <input type="number" class="form-control" name="banner_order_no" id="banner_order_no" min="0"
                       value="{{isset($data['banner_order_no'])?$data['banner_order_no']:old('banner_order_no')}}">
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('banner_status') ? ' has-error' : '' }}">
            <label for="banner_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="banner_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('banner_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('banner_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <input type="hidden" id="remove_media" name="remove_media" value="0"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
    </form>
@endsection
