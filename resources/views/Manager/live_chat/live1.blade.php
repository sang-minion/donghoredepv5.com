<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 06/07/2017
 * Time: 16:20 CH
 */
?>
@extends('layouts.admin')
@section('content')
@if(isset($chat)&&!empty($chat))
    <iframe src="{{$chat['static_content']}}" frameborder="0" width="100%" height="550px" allowfullscreen id="livechat1">
    </iframe>
@endif
@endsection
