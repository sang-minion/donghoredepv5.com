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

        <div class="form-group{{isset($errors)&& $errors->has('role_title') ? ' has-error' : '' }}">
            <label for="role_title" class="col-md-2 col-sm-3 control-label">Tiêu đề</label>
            <div class="col-md-6 col-sm-9">
                <input id="role_title" type="text" class="form-control" name="role_title"
                       value="{{isset($data['role_title'])?$data['role_title']: old('role_title') }}" required
                       autofocus>
                @if (isset($errors)&&$errors->has('role_title'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('role_title') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 col-sm-3 control-label">Phân quyền truy cập</label>
            <div class="col-md-10 col-sm-9">
                <div class="col-lg-12 col-md-12 col-sm-12 head-permission" >
                    <ul class=" col-lg-12 col-md-12 col-sm-12">
                        <li class="col-md-3 col-sm-4 ">Tên module</li>
                        <li class="col-md-9 col-sm-8">Danh sách quyền</li>
                    </ul>
                </div>
                <div class=" col-lg-12 col-md-12 col-sm-12 content-permission" >
                    @foreach($arrModule as $module)
                        <ul class="col-lg-12 col-md-12 col-sm-12" >
                            <li class="col-lg-12 col-md-12 col-sm-12 " >
                                <div class=" col-md-3 col-sm-4 ">
                                    {{$module['module_title']}}-{{$module['module_controller']}}
                                </div>
                                <?php
                                $module_action = array();
                                if($module['module_action'] != '' || $module['module_action'] != null){
                                    $module_action = unserialize($module['module_action']);
                                }
                                $permission = array();
                                if(isset($data['role_permission']) && ($data['role_permission'] != '' || $data['role_permission'] != null)){
                                    $permission = unserialize($data['role_permission']);
                                }
                                $str = '';
                                ?>
                                <div class="col-md-9 col-sm-8">

                                    @foreach($module_action as $key=>$action)
                                        <?php
                                        if(isset($permission[$module->module_controller]) && isset($permission[$module->module_controller][$action]) == 1){
                                            $str = 'value="1" checked="checked"';
                                        }else{
                                            $str ='value="1"';
                                        }
                                        ?>
                                        <div class=" col-md-2 col-sm-6">
                                            <input type="checkbox" name="access[{{$module['module_controller']}}][{{$action}}]" class="item_{{$action}}" id="access[{{$module['module_controller']}}][{{$action}}]" {{$str}}/>
                                            <label for="access[{{$module['module_controller']}}][{{$action}}]" title="{{ucwords($action)}}">@if(isset($arrNamePermission[$action])) {{$arrNamePermission[$action]}} @else {{$action}} @endif</label>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('role_status') ? ' has-error' : '' }}">
            <label for="role_status" class="col-md-2 col-sm-3 control-label">Cho phép upload</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="allow_upload">
                    {!! $optionAllowUL !!}
                </select>
                @if (isset($errors)&&$errors->has('role_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('role_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('role_status') ? ' has-error' : '' }}">
            <label for="role_status" class="col-md-2 col-sm-3 control-label">Trạng thái</label>
            <div class="col-md-6 col-sm-9">
                <select class="form-control" name="role_status">
                    {!! $optionStatus !!}
                </select>
                @if (isset($errors)&&$errors->has('role_status'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('role_status') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{isset($errors)&& $errors->has('role_order_no') ? ' has-error' : '' }}">
            <label for="role_order_no" class="col-md-2 col-sm-3 control-label">Thứ tự</label>
            <div class="col-md-6 col-sm-9">
                <input type="text" class="form-control" name="role_order_no" id="role_order_no" value="{{isset($data['role_order_no'])?$data['role_order_no']:'0'}}">
                @if (isset($errors)&&$errors->has('role_order_no'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('role_order_no') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="form-group" >
            <div class="col-md-6 btn-option">
                <input type="hidden" id="id_hidden" name="id_hidden" value="{{$id}}"/>
                <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary">Lưu lại</button>
                <button type="reset" class="btn" id="goback">Bỏ qua</button>
            </div>
        </div>
    </form>
@endsection
