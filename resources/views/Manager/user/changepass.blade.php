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
        <div class="form-group">
            <div class=" col-md-6 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
    </form>
@endsection
