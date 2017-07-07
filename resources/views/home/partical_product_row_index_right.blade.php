<?php
use \App\model\Category;
?>
<div class="col-md-12 col-sm-12 xol-xs-12 product-center product-row-index">
                <div class="title-row border-pink1">
                    <a class="title-rows-left" href="{{Funclip::buildLinkCategory($Cate->category_id,$Cate->category_keyword)}}">
                        <img class="pink1" src="{{asset('resources/assets/media/C8.png')}}">
                        <div class="inner-title">
                            <h4>{{$Cate->category_title}}</h4>
                                        <h5>{{$Cate->category_intro}}</h5>
                        </div>
                    </a>
                    <a href="{{Funclip::buildLinkCategory($Cate->category_id,$Cate->category_keyword)}}"
                       class="btn pink1 btn-show-all hidden-xs" title="{{$Cate->category_title}}">Xem tất cả ?</a>
                </div>
                <div class="product-row product-row-right">
				<div class="col-item">
				@include('home.partical_list_product',['listPD'=>$ProductCate[1]['prd']])
				</div>
                    <div class="col-large">
                         <a href="{{Funclip::buildLinkCategory($Cate->category_id,$Cate->category_keyword)}}"
                                       title="{{$Cate->category_title}}">
                                        <img src="{{ThumbImg::thumbBaseNormal(Category::FOLDER,$Cate->category_id,$Cate->category_media,400,400,'',true,true,true)}}">
                                    </a>
                    </div>
                </div>
            </div>