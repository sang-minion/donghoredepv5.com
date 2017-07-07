<?php
 use \App\model\Gift;
?>
<div class="show-list-product">
<ul>
                            @foreach($listGF as $k=>$item)
                                <li class="col-product">
                                    <a href="javascript:void(0)"
                                       class="box-product" title="{{$item->gift_title}}">
                                        <img class="box-img"
                                             src="{{ThumbImg::thumbBaseNormal(Gift::FOLDER,$item->gift_id,$item->gift_media,220,200,'',true,true,true)}}"
                                             alt="{{$item->gift_title}}">
                                        <h5 class="title-product">{{$item->gift_title}}</h5>
                                        <h5 class="vote"><i class="fa fa-star item-vote active"></i><i
                                                    class="fa fa-star item-vote active active"></i><i
                                                    class="fa fa-star item-vote active active"></i><i
                                                    class="fa fa-star item-vote active active"></i><i
                                                    class="fa fa-star item-vote active active"></i>
                                        </h5>
                                        <h5 class="price">{{Utility::numberFormat($item->gift_price).'đ'}}</h5>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
						</div>