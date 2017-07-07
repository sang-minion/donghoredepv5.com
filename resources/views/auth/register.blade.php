@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Đăng ký {{isset($name)?$name:''}}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('registers') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('user_full_name') ? ' has-error' : '' }}">
                            <label for="user_full_name" class="col-md-4 control-label">Họ tên</label>

                            <div class="col-md-6">
                                <input id="user_full_name" type="text" class="form-control date" name="user_full_name" value="{{ old('user_full_name') }}" required autofocus>

                                @if ($errors->has('user_full_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_full_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('user_phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="user_phone" type="text" class="form-control date" name="user_phone" value="{{ old('user_phone') }}" required>

                                @if ($errors->has('user_phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('user_address') ? ' has-error' : '' }}">
                            <label for="user_address" class="col-md-4 control-label">Address</label>

                            <div class="col-md-6">
                                <input id="user_address" type="text" class="form-control date" name="user_address" value="{{ old('user_address') }}" required>

                                @if ($errors->has('user_address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('user_email') ? ' has-error' : '' }}">
                            <label for="user_email" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="user_email" type="email" class="form-control date" name="user_email" value="{{ old('user_email') }}" required>

                                @if ($errors->has('user_email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('user_password') ? ' has-error' : '' }}">
                            <label for="user_password" class="col-md-4 control-label">Mật khẩu</label>

                            <div class="col-md-6">
                                <input id="user_password" type="password" class="form-control date" name="user_password" required>

                                @if ($errors->has('user_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="user_password-confirm" class="col-md-4 control-label date">Nhập lại mật khẩu</label>

                            <div class="col-md-6">
                                <input id="user_password-confirm" type="password" class="form-control" name="user_password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Đăng ký
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
