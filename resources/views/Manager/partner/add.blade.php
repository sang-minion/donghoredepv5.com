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

        <div class="form-group{{isset($errors)&& $errors->has('partner_title') ? ' has-error' : '' }}">
            <label for="partner_title" class="col-md-2 col-sm-3 control-label">Tiêu đề</label>
            <div class="col-md-6 col-sm-9">
                <input id="partner_title" type="text" class="form-control" name="partner_title"
                       value="{{isset($data['partner_title'])?$data['partner_title']: old('partner_title') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('partner_title'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('partner_title') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
		
        <div class="form-group ">
            <label for="partner_website" class="col-md-2 col-sm-3 control-label">Địa chỉ website</label>
            <div class="col-md-6 col-sm-9">
                <input type="text" class="form-control" name="partner_website" id="partner_website"
                       value="{{isset($data['partner_website'])?$data['partner_website']:old('partner_website')}}">
            </div>
        </div>
        <div class="form-group ">
            <label for="partner_address" class="col-md-2 col-sm-3 control-label">Địa chỉ liên hệ</label>
            <div class="col-md-6 col-sm-9">
                <input type="text" class="form-control" name="partner_address" id="partner_address"
                       value="{{isset($data['partner_address'])?$data['partner_address']:old('partner_address')}}">
            </div>
        </div>
        <div class="form-group">
            <label for="partner_logo" class="col-md-2 col-sm-3 control-label">Hình ảnh logo</label>
            <div class="col-md-6 col-sm-9">
                <label class="btn btn-primary" for="partner_logo">Upload hình ảnh</label> <i id="fileName"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" name="partner_logo"
                       id="partner_logo" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG"
                     @if(isset($data['partner_logo'])&&$data['partner_logo']!='') rel="1" @else  rel="0" @endif>
                    @if(isset($data['news_media'])&&$data['news_media']!='')
                        <i class="fa fa-remove red" id="xoa-media" title="xóa"></i>
                        <img src="{{ThumbImg::thumbBaseNormal(\App\model\Partner::FOLDER,$id,$data['partner_logo'],400,400,'',true,true,true)}}"
                             width="100%"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="partner_intro" class="col-md-2 col-sm-3 control-label">Mô tả giới thiệu</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="partner_intro" class="form-control" name="partner_intro" rows="3"
                          autofocus>{{isset($data['partner_intro'])? stripslashes($data['partner_intro']):old('partner_intro')}}</textarea>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('partner_status') ? ' has-error' : '' }}">
            <label for="partner_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="partner_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('partner_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('partner_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group" >
            <div class="col-md-6 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <input type="hidden" id="remove_media" name="remove_media" value="0"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
		
    </form>
@endsection
