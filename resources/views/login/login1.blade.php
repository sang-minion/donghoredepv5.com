<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 04/06/2017
 * Time: 9:28 SA
 */
?>
<style>

</style>

@extends('layouts.login')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 box-login">

                <h1>CMS Control Panel</h1>
                <form class="form-horizontal" role="form" method="POST"
                      action="{{ route('login') }}">

                    {{ csrf_field() }}
                    <div class="section title">
                        <h4>Vui lòng nhập thông tin </h4>
                    </div>
                    @if (Session::has('error'))
                        <h5 class="section">
                            {{ Session::get('error') }}
                        </h5>
                    @endif
                    <div class="input-group section">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-user"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                               aria-describedby="basic-addon1"
                               value="{{ old('email') }}" required autofocus>
                        @if (isset($errors)&& $errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="input-group section">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-unlock-alt"></i></span>
                        <input type="password" class="form-control" id="pass" placeholder="password"
                               aria-describedby="basic-addon1" name="password" value="{{ old('password') }}" required
                               autofocus>
                        @if (isset($errors)&&$errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="input-group section">
                        <div class="col-md-6 col-sm-6 col-xs-8" style="padding: 0px">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                           name="remember" {{ old('remember') ? 'checked' : '' }}> Ghi nhớ mật khẩu
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-4" style="padding: 0px">
                            <button type="submit" class="btn">
                                Đăng nhập <i class="fa fa-angle-right "></i>
                            </button>
                        </div>
                    </div>
                    <div class="section-bottom">
                            <span>© {{config('app.name')}}</span>
                            <a class="" href="javascript:void(0)">
                                Quên mật khẩu
                            </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(document).on('mouseover', '.section', function () {
                jQuery(this).addClass('activechange');
            });
            jQuery(document).on('mouseout', '.section', function () {
                jQuery(this).removeClass('activechange');
            });
        })
    </script>
@endsection
