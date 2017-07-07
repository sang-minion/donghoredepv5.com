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
        <div class="form-group{{isset($errors)&& $errors->has('static_title') ? ' has-error' : '' }}">
            <label for="static_title" class="col-md-2 col-sm-3 control-label">Tiêu đề</label>
            <div class="col-md-6 col-sm-9">
                <input id="static_title" type="text" class="form-control" name="static_title"
                       value="{{isset($data['static_title'])?$data['static_title']: old('static_title') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('static_title'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('static_title') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="static_content" class="col-md-2 col-sm-3 control-label">Nội dung</label>
            <div class="col-md-10 col-sm-9">
                <textarea class="form-control ckeditor  "
                          name="static_content">{{isset($data['static_content'])?stripslashes($data['static_content']):old('static_content')}}</textarea>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('static_status') ? ' has-error' : '' }}">
            <label for="static_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="static_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('static_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('static_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-10 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <input type="hidden" id="remove_media" name="remove_media" value="0"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
    </form>
@endsection
