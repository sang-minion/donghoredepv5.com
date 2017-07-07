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
		<div class="form-group{{isset($errors)&& $errors->has('news_key_parent') ? ' has-error' : '' }}">
            <label for="news_key_parent" class="col-md-2 col-sm-3 control-label">Danh mục</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="news_key_parent">
                    {!! $optionCate !!}
                </select>
                @if (isset($errors)&&$errors->has('news_key_parent'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('news_key_parent') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('news_title') ? ' has-error' : '' }}">
            <label for="news_title" class="col-md-2 col-sm-3 control-label">Tiêu đề</label>
            <div class="col-md-6 col-sm-9">
                <input id="news_title" type="text" class="form-control" name="news_title"
                       value="{{isset($data['news_title'])?$data['news_title']: old('news_title') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('news_title'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('news_title') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="news_media" class="col-md-2 col-sm-3 control-label">Hình ảnh</label>
            <div class="col-md-6 col-sm-9">
                <label class="btn btn-primary" for="news_media">Upload hình ảnh</label> <i id="fileName"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" name="news_media"
                       id="news_media" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG"
                     @if(isset($data['news_media'])&&$data['news_media']!='') rel="1" @else  rel="0" @endif>
                    @if(isset($data['news_media'])&&$data['news_media']!='')
                        <i class="fa fa-remove red" id="xoa-media" title="xóa"></i>
                        <img src="{{ThumbImg::thumbBaseNormal(\App\model\News::FOLDER,$id,$data['news_media'],400,400,'',true,true,true)}}"
                             width="100%"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="news_intro" class="col-md-2 col-sm-3 control-label">Nội dung rút gọn</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="news_intro" class="form-control" name="news_intro" rows="3"
                          autofocus>{{isset($data['news_intro'])? stripslashes($data['news_intro']):old('news_intro')}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="news_content" class="col-md-2 col-sm-3 control-label">Mô dung chi tiết</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="news_content" class="form-control ckeditor" name="news_content" rows="3"
                          autofocus>{{isset($data['news_content'])? stripslashes($data['news_content']):old('news_content')}}</textarea>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('news_status') ? ' has-error' : '' }}">
            <label for="news_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="news_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('news_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('news_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group ">
            <label for="meta_title" class="col-md-2 col-sm-3 control-label">Meta title</label>
            <div class="col-md-10 col-sm-9">
                <input type="text" class="form-control" name="meta_title" id="meta_title"
                       value="{{isset($data['meta_title'])?$data['meta_title']:old('meta_title')}}">
            </div>
        </div>
        <div class="form-group ">
            <label for="meta_keywords" class="col-md-2 col-sm-3 control-label">Meta keywords</label>
            <div class="col-md-10 col-sm-9">
                <input type="text" class="form-control" name="meta_keywords" id="meta_keywords"
                       value="{{isset($data['meta_keywords'])?$data['meta_keywords']:old('meta_keywords')}}">
            </div>
        </div>
        <div class="form-group ">
            <label for="meta_description" class="col-md-2 col-sm-3 control-label">Meta description</label>
            <div class="col-md-10 col-sm-9">
                <input type="text" class="form-control" name="meta_description" id="meta_description"
                       value="{{isset($data['meta_description'])?$data['meta_description']:old('meta_description')}}">
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
