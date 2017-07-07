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
        <div class="form-group{{isset($errors)&& $errors->has('cmt_name') ? ' has-error' : '' }}">
            <label for="cmt_name" class="col-md-2 col-sm-3 control-label">Tên</label>
            <div class="col-md-6 col-sm-9">
                <input id="cmt_name" type="text" class="form-control" name="cmt_name"
                       value="{{isset($data['cmt_name'])?$data['cmt_name']: old('cmt_name') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('cmt_name'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('cmt_name') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('cmt_link') ? ' has-error' : '' }}">
            <label for="cmt_link" class="col-md-2 col-sm-3 control-label">Link intro</label>
            <div class="col-md-6 col-sm-9">
                <input id="cmt_link" type="text" class="form-control" name="cmt_link"
                       value="{{isset($data['cmt_link'])?$data['cmt_link']: old('cmt_link') }}"
                       autofocus>
                @if (isset($errors)&&$errors->has('cmt_link'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('cmt_link') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="cmt_avt" class="col-md-2 col-sm-3 control-label">Hình ảnh </label>
            <div class="col-md-6 col-sm-9">
                <label class="btn btn-primary" for="cmt_avt">Upload hình ảnh</label>  <i id="fileName"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" name="cmt_avt" id="cmt_avt" value="" style="display: none">
                <br>
                <div class="col-md-12" id="privewIMG" @if(isset($data['cmt_avt'])&&$data['cmt_avt']!='') rel="1" @else  rel="0"  @endif>
                    @if(isset($data['cmt_avt'])&&$data['cmt_avt']!='')
                        <i class="fa fa-remove red" id="xoa-media" title="xóa"></i>
                        <img src="{{ThumbImg::thumbBaseNormal(\App\model\CommentHome::FOLDER,$id,$data['cmt_avt'],400,400,'',true,true,true)}}" width="100%"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="cmt_content" class="col-md-2 col-sm-3 control-label">Nội dung cmt</label>
            <div class="col-md-10 col-sm-9">
                <textarea id="cmt_content" class="form-control" name="cmt_content" rows="3" autofocus>{{isset($data['cmt_content'])?$data['cmt_content']:old('cmt_content')}}</textarea>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('cmt_status') ? ' has-error' : '' }}">
            <label for="cmt_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="cmt_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('cmt_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('cmt_status') }}</strong>
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
