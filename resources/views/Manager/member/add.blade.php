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
        <div class="form-group{{isset($errors)&& $errors->has('member_name') ? ' has-error' : '' }}">
            <label for="member_name" class="col-md-2 col-sm-3 control-label">Họ tên</label>
            <div class="col-md-6 col-sm-9">
                <input id="member_name" type="text" class="form-control" name="member_name"
                       value="{{isset($data['member_name'])?$data['member_name']: old('member_name') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('member_name'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('member_name') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('member_age') ? ' has-error' : '' }}">
            <label for="member_age" class="col-md-2 col-sm-3 control-label">Tuổi</label>
            <div class="col-md-6 col-sm-9">
                <input id="member_age" type="number" min="10" class="form-control" name="member_age"
                       value="{{isset($data['member_age'])?$data['member_age']: old('member_age') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('member_age'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('member_age') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="member_avt" class="col-md-2 col-sm-3 control-label">Hình ảnh avt</label>
            <div class="col-md-6 col-sm-9">
                <label class="btn btn-primary" for="member_avt">Upload hình ảnh</label> <i id="fileName"></i>
                <input type="file" class="" accept="image/jpg,image/jpeg,image/png" name="member_avt"
                       id="member_avt" value="" style="display: none">
                <br>
                <div class="col-md-6" id="privewIMG"
                     @if(isset($data['member_avt'])&&$data['member_avt']!='') rel="1" @else  rel="0" @endif>
                    @if(isset($data['member_avt'])&&$data['member_avt']!='')
                        <i class="fa fa-remove red" id="xoa-media" title="xóa"></i>
                        <img src="{{ThumbImg::thumbBaseNormal(\App\model\Member::FOLDER,$id,$data['member_avt'],400,400,'',true,true,true)}}"
                             width="100%"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('member_phone') ? ' has-error' : '' }}">
            <label for="member_phone" class="col-md-2 col-sm-3 control-label">Số điện thoại</label>
            <div class="col-md-6 col-sm-9">
                <input id="member_phone" type="text" class="form-control" name="member_phone"
                       value="{{isset($data['member_phone'])?$data['member_phone']: old('member_phone') }}"
                       required
                       autofocus>
                @if (isset($errors)&&$errors->has('member_phone'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('member_phone') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('member_address') ? ' has-error' : '' }}">
            <label for="member_address" class="col-md-2 col-sm-3 control-label">Địa chỉ</label>
            <div class="col-md-6 col-sm-9">
                <textarea id="member_address" class="form-control" name="member_address" rows="3"
                          autofocus>{{isset($data['member_address'])? stripslashes($data['member_address']):old('member_address')}}</textarea>
                @if (isset($errors)&&$errors->has('member_address'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('member_address') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('member_email') ? ' has-error' : '' }}">
            <label for="member_email" class="col-md-2 col-sm-3 control-label">Địa chỉ mail</label>
            <div class="col-md-6 col-sm-9">
                <input id="member_email" type="email" class="form-control" name="member_email"
                       value="{{isset($data['member_email'])?$data['member_email']: old('member_email') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('member_email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('member_email') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('member_pass') ? ' has-error' : '' }}">
            <label for="member_pass" class="col-md-2 col-sm-3 control-label">Mật khẩu</label>
            <div class="col-md-6 col-sm-9">
                <input id="member_pass" type="password" class="form-control" name="member_pass">

                @if (isset($errors)&&$errors->has('member_pass'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('member_pass') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for=member_pass-confirm" class="col-md-2 col-sm-3 control-label date">Nhập lại mật khẩu</label>
            <div class="col-md-6 col-sm-9">
                <input id="member_pass-confirm" type="password" class="form-control" name="password_confirmation">
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('member_status') ? ' has-error' : '' }}">
            <label for="member_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="member_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('member_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('member_status') }}</strong>
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
