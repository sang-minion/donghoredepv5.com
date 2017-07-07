<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 28/06/2017
 * Time: 9:42 SA
 */
?>
@extends('layouts.app')
@section('menu')
    @include('layouts.header')
@endsection
@section('content')
    <div class="container thankforbuy">
        <h1>Đặt hàng thành công</h1>
        <h5>Đơn hàng của bạn đã được chúng tôi ghi nhận , chúng tôi sẽ giao hàng cho bạn trong thời gian sớm nhất.</h5>
        <h5>Hãy chú ý điện thoại chúng tôi sẽ gọi điện cho bạn để giao hàng.</h5>
        <h5>Cảm ơn bạn đã đặt hàng tại Donghoredep.com</h5>
        <a class="btn btn-success" href="{{route('index')}}">Về trang chủ</a>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection




