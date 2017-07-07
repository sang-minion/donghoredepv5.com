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
    <form action="" class="form-horizontal frmAdd" method="POST" id="frmAdd" name="frmAdd">
        {{ csrf_field() }}

        <div class="form-group{{isset($errors)&& $errors->has('user_role_id') ? ' has-error' : '' }}">
            <label for="user_role_id" class="col-md-2 col-sm-3 control-label">Nhóm quyền</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="user_role_id">
                    {!! $optionRole !!}
                </select>
                @if (isset($errors)&&$errors->has('user_role_id'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('user_role_id') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-2 col-sm-3 control-label">Họ tên</label>
            <div class="col-md-6 col-sm-9">
                <input id="name" type="text" class="form-control" name="name"
                       value="{{isset($data['name'])?$data['name']: old('name') }}"
                       required
                       autofocus>
                @if (isset($errors)&&$errors->has('name'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-2 col-sm-3 control-label">Email</label>
            <div class="col-md-6 col-sm-9">
                <input type="email" class="form-control" name="email" id="email"
                       value="{{isset($data['email'])?$data['email']:old('email')}}">
                @if (isset($errors)&&$errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('user_phone') ? ' has-error' : '' }}">
            <label for="user_phone" class="col-md-2 col-sm-3 control-label">SĐT</label>
            <div class="col-md-6 col-sm-9">
                <input type="text" class="form-control" name="user_phone" id="user_phone"
                       value="{{isset($data['user_phone'])?$data['user_phone']:old('user_phone')}}">
                @if (isset($errors)&&$errors->has('user_phone'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('user_phone') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('user_address') ? ' has-error' : '' }}">
            <label for="user_address" class="col-md-2 col-sm-3 control-label">Địa chỉ</label>
            <div class="col-md-6 col-sm-9">
                <input type="text" class="form-control" name="user_address" id="user_address"
                       value="{{isset($data['user_address'])?$data['user_address']:old('user_address')}}">
                @if (isset($errors)&&$errors->has('user_address'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('user_address') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-2 col-sm-3 control-label">Mật khẩu</label>
            <div class="col-md-6 col-sm-9">
                <input id="password" type="password" class="form-control" name="password">

                @if (isset($errors)&&$errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm" class="col-md-2 col-sm-3 control-label date">Nhập lại mật khẩu</label>
            <div class="col-md-6 col-sm-9">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('user_status') ? ' has-error' : '' }}">
            <label for="user_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="user_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('user_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('user_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
    </form>
@endsection
