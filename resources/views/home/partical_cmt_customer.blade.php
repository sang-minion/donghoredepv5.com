@if(sizeof($cmt)>0)
<div class="col-md-12 product-comment-customer">
    <h4 class="title-para2">Phản hồi khách hàng
        <b>
            <i class="fa fa-angle-left fa-2x" id="previous-comment" title="previous"
               href="#show-commetnt-customer" role="button" data-slide="prev">
            </i><i class="fa fa-angle-right fa-2x" id="next-comment" title="next"
                   href="#show-commetnt-customer"
                   role="button" data-slide="next"></i>
        </b></h4>
    <div class="carousel slide" id="show-commetnt-customer" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">            
                <?php $c = 1;?>
                @foreach($cmt as $k=>$item)
                    @if(!empty($item))
                    @if($k==0&&$c==1)
                        <div class="item active">
                            <ul>
                                @elseif($k!=0&&$c==1)
                                    <div class="item">
                                        <ul>
                                            @endif
                                            <li class="col-comment-customer">
                                                <a href="{{$item->cmt_link}}" target="_blank" title="{{$item->cmt_name}}">
                                                    <img src="{{ThumbImg::thumbBaseNormal(\App\model\CommentHome::FOLDER,$item->cmt_id,$item->cmt_avt,200,200,'',true,true,true)}}">
                                                    <h4>{{$item->cmt_name}}</h4>
                                                    <i class="asdl"></i>
                                                    <div>{{$item->cmt_content}}</div>
                                                    <i class="asdr"></i>
                                                </a>
                                            </li>
                                            <?php $c += 1;?>
                                            @if($c==4)
                                        </ul>
                                    </div>
                                    <?php $c = 1;?>
                                @endif
                                @endif
                                @endforeach
                                @if((count($cmt)>0&&count($cmt)<3)||count($cmt)%3!=0)
                            </ul>
                        </div>
                    @endif
        </div>
    </div>
</div>
@endif