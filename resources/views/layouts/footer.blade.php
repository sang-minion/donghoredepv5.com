<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 19/06/2017
 * Time: 1:01 SA
 */
use \App\Http\Controllers\Home\CartController ;
?>
<section class="maps">
    <div class="container">
        <h3>Hệ thống chi nhánh và đại lý</h3>
        <ul class="nav nav-pills" role="tablist">
            @foreach($maps as $k=>$item)
                @if($k==0)
                    <li role="presentation" class="active"><a href="#Maps{{$k+1}}" aria-controls="home" role="tab"
                                                              data-toggle="tab">{{$item['static_title']}}</a></li>
                @else
                    <li role="presentation"><a href="#Maps{{$k+1}}" aria-controls="profile" role="tab"
                                               data-toggle="tab">{{$item['static_title']}}</a></li>
                @endif
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach($maps as $k=>$item)
                @if($k==0)
                    <div role="tabpanel" class="tab-pane  fade in active" id="Maps{{$k+1}}">
                        <iframe src="{{$item['static_content']}}" frameborder="0" style="border:0"
                                allowfullscreen></iframe>
                    </div>
                @else
                    <div role="tabpanel" class="tab-pane fade in " id="Maps2">
                        <iframe src="{{$item['static_content']}}" frameborder="0" style="border:0"
                                allowfullscreen></iframe>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
<section class="footer-1">
    <div class="col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-2 col-xs-8  box-like-facebook">
		{!!isset($fanpageFB)&&!empty($fanpageFB)&&$fanpageFB->static_status==\CGlobal::status_show? stripslashes($fanpageFB['static_content']):''!!}
    </div>
</section>
<section class="footer-2">
    <div class="container">
        <div class="col-md-6 col-sm-4 col-xs-12">
            {!! $footer_left !!}
        </div>
        <div class="col-md-3 col-sm-4">
            <h4 class="title-footer">Hỗ trợ khách hàng</h4>
            <ul>
                @foreach($hotroKH as $item)
                    <li>
                        <a href="{{Funclip::buildLinkDetailSupport($item['static_id'],$item['static_keyword'])}}"
                           title="{{$item['static_title']}}">{{$item['static_title']}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-3 col-sm-4">
            <h4 class="title-footer">Chính sách chung</h4>
            <ul>
                @foreach($chinhsachChung as $item)
                    <li>
                        <a href="{{Funclip::buildLinkDetailPolicy($item['static_id'],$item['static_keyword'])}}"
                           title="{{$item['static_title']}}">{{$item['static_title']}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
<section class="footer-3">
    <div class="container">
        <div class="col-md-3 col-sm-3 contact-left">
            Copyright © {{date('Y',time())}} by Đồng Hồ Rẻ Đẹp
        </div>
        <div class="col-md-3 col-sm-3 list-contact">
            <div class="item it-facebook"><a href="{{isset($linkFB)&&!empty($linkFB)&&$linkFB->static_status==\CGlobal::status_show?$linkFB['static_content']:'#'}}"><i class="fa fa-facebook"></i></a></div>
            <div class="item it-google"><a href="{{isset($linkGG)&&!empty($linkGG)&&$linkGG->static_status==\CGlobal::status_show?$linkGG['static_content']:'#'}}"><i class="fa fa-google-plus"></i></a></div>
            <div class="item it-youtube"><a href="{{isset($linkYoutube)&&!empty($linkYoutube)&&$linkYoutube->static_status==\CGlobal::status_show?$linkYoutube['static_content']:'#'}}"><i class="fa fa-youtube-play"></i></a></div>
        </div>
        <div class="col-md-6 col-sm-6 contact-right">
            <div>Tổng:
				@if(isset($flagCounter)&&!empty($flagCounter))
				{!! stripslashes($flagCounter['static_content']) !!}
				@endif
			</div>
            <div style="display:none;">Đang online:
				@if(isset($flagCounter)&&!empty($flagCounter))
				{!! stripslashes($flagCounter['static_content']) !!}
				@endif
			</div>
        </div>
    </div>
</section>
@if(isset($idproduct)&&$idproduct>0)
<div class="shopping-cart" title="có {{CartController::sessionCart()}} sản phẩm trong giỏ hàng" >
    <i class="icon-shopping-cart fa fa-plus"></i>
    <span  id="total-product">({{CartController::sessionCart()}})</span>
    <a class="title" href="javascript:void(0)" id="btn-add-cart"  data-id="{{$idproduct}}"  data-num="1" title="Thêm vào giỏ hàng">Thêm vào giỏ</a>
</div>
@endif
@if(CartController::sessionCart()>0&&!isset($showBtnBuy))
	<div id="btn-add-cart" class="shopping-cart-show" title="có {{CartController::sessionCart()}} sản phẩm trong giỏ hàng" >
		<i class="icon-shopping-cart fa fa-shopping-bag"></i>
		<span  id="total-product">({{CartController::sessionCart()}})</span>
		<a href="{{route('giohang')}}" class="titleOrder" title="Đặt hàng và thanh toán">Đặt hàng</a>
	</div>
@endif
<a class="btn-top" href="javascript:void(0);" title="Top" style="display: none"></a>
@if(isset($boxChat[0])&&!empty($boxChat[0])&&$boxChat[0]->static_status==\CGlobal::status_show)
{!! stripslashes($boxChat[0]['static_content'])!!}
@endif
{{--<script lang="javascript">(function() {var pname = ( (document.title !='')? document.title : document.querySelector('h1').innerHTML );var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async=1; ga.src = '//live.vnpgroup.net/js/web_client_box.php?hash=88d951cc87fb951a4ebe7eb8a8406ed8&data=eyJzc29faWQiOjQ5MjAyODgsImhhc2giOiIzN2IyY2M1YzE2OTY2ZTQ1NDhmODNjYzA4MTEwNzM5MiJ9&pname='+pname;var s = document.getElementsByTagName('script');s[0].parentNode.insertBefore(ga, s[0]);})();</script>--}}