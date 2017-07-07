<?php
 use \App\model\Product;
 ?>
<div class=" show-list-product">
                        <ul>
                            @foreach($listPD as $k=>$item)
								@if(isset($min)&&isset($max))
									@if($k>=$min&&$k<$max)
										<li class="col-product">
											<a href="{{Funclip::buildLinkDetailProduct($item->product_id,$item->product_alias)}}"
											   class="box-product" title="{{$item->product_title}}">
												<img class="box-img"
													 src="{{ThumbImg::thumbBaseNormal(Product::FOLDER,$item->product_id,$item->product_media,220,200,'',true,true,true)}}"
													 alt="{{$item->product_title}}">
												<h5 class="title-product">{{$item->product_title}}</h5>
												<h5 class="vote"><i class="fa fa-star item-vote active"></i><i
															class="fa fa-star item-vote active"></i><i
															class="fa fa-star item-vote active"></i><i
															class="fa fa-star item-vote active"></i><i
															class="fa fa-star item-vote active"></i>
												</h5>
												<h5 class="price">{{$item->product_price_saleof>0?Utility::numberFormat($item->product_price_saleof).'đ':Utility::numberFormat($item->product_price).'đ'}}
													<span class="price-odl">{{$item->product_price_saleof>0?Utility::numberFormat($item->product_price).'đ':''}}</span>
												</h5>
											</a>
										</li>
									@endif
								@else
									<li class="col-product">
										<a href="{{Funclip::buildLinkDetailProduct($item->product_id,$item->product_alias)}}"
										   class="box-product" title="{{$item->product_title}}">
											<img class="box-img"
												 src="{{ThumbImg::thumbBaseNormal(Product::FOLDER,$item->product_id,$item->product_media,220,200,'',true,true,true)}}"
												 alt="{{$item->product_title}}">
											<h5 class="title-product">{{$item->product_title}}</h5>
											<h5 class="vote"><i class="fa fa-star item-vote active"></i><i
														class="fa fa-star item-vote active"></i><i
														class="fa fa-star item-vote active"></i><i
														class="fa fa-star item-vote active"></i><i
														class="fa fa-star item-vote active"></i>
											</h5>
											<h5 class="price">{{$item->product_price_saleof>0?Utility::numberFormat($item->product_price_saleof).'đ':Utility::numberFormat($item->product_price).'đ'}}
												<span class="price-odl">{{$item->product_price_saleof>0?Utility::numberFormat($item->product_price).'đ':''}}</span>
											</h5>
										</a>
									</li>
								@endif
                            @endforeach
                        </ul>
						</div>