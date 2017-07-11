<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 05/07/2017
 * Time: 16:45 CH
 */
?>
<div class="col-md-3 col-sm-3 col-xs-12 box-comment">
    <div class="col-md-3 col-sm-3 col-xs-12 col-comment">
        <h4 class="title">Đánh giá & Bình luận</h4>
        <div class="vote-change">
            <h5 class="vote"> Đánh giá *
                <i class="fa fa-star item-vote active" rel="1" title="Vote 1 sao"></i>
                <i class="fa fa-star item-vote active" rel="2" title="Vote 2 sao"></i>
                <i class="fa fa-star item-vote active" rel="3" title="Vote 3 sao"></i>
                <i class="fa fa-star item-vote active" rel="4" title="Vote 4 sao"></i>
                <i class="fa fa-star item-vote active" rel="5" title="Vote 5 sao"></i>
                <span class="total-vote">tổng</span>
            </h5>
            <ul class="show-result-vote">
                <li id="1star">
                    <span>1 sao</span>
                    <span class="show-total-vote">
                        <span class="show-percent"></span>
                        <span class="show-percent2"></span>
                    </span>
                    <span class="count-num-start">0</span>
                </li>
                <li id="2star">
                    <span>2 sao</span>
                    <span class="show-total-vote">
                        <span class="show-percent"></span>
                        <span class="show-percent2"></span>
                    </span>
                    <span class="count-num-start">0</span>
                </li>
                <li id="3star">
                    <span>3 sao</span>
                    <span class="show-total-vote">
                        <span class="show-percent"></span>
                        <span class="show-percent2"></span>
                    </span>
                    <span class="count-num-start">0</span>
                </li>
                <li id="4star">
                    <span>4 sao</span>
                    <span class="show-total-vote">
                        <span class="show-percent"></span>
                        <span class="show-percent2"></span>
                    </span>
                    <span class="count-num-start">0</span>
                </li>
                <li id="5star">
                    <span>5 sao</span>
                    <span class="show-total-vote">
                        <span class="show-percent"></span>
                        <span class="show-percent2"></span>
                    </span>
                    <span class="count-num-start">0</span>
                </li>
            </ul>
        </div>
        <input type="hidden" id="active-vote" value="{{ !empty($avtiveVote)?$avtiveVote:0}}">
        <input type="hidden" id="check" value="{{ !empty($member)?$member['member_id']:!empty($admin)?$admin['user_id']:0}}">
        <input type="hidden" id="roleck" value="{{ !empty($member)?0:!empty($admin)?$admin['user_role_id']:0}}">
        <input type="hidden" id="pidprd" value="{{isset($prdid)?$prdid:0}}">
        <div class="col-md-12 col-sm-12 col-xs-12 rows-comment-s">
            <div class="comments">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <textarea class="form-control textcmt" id="cmt0" rows="3" required placeholder="Bình luận"
                                  dataidrep="0"></textarea>
                    </div>
                    <div class="panel-footer">
                        @if(empty($member) && empty($admin))
                            <div class="col-md-4 col-sm-4 col-xs-5 itemcmt">
                                <input class="form-control" type="text" id="txtname0" placeholder="Họ tên (bắt buộc)"
                                       required/>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-5 itemcmt">
                                <input class="form-control" type="text" id="txtphone0" placeholder="Số điện thoại"
                                       required/>
                            </div>
                        @endif
                        <button type="button" datacmt="cmt0" dataip="0" class="btn btn-primary postcomment">
                            Gửi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($CMTPRD)&&!empty($CMTPRD))
            @foreach($CMTPRD as $k=>$item)
                @if($item->comment_parent_id==0)
                    <div class="col-md-12 col-sm-12 col-xs-12 rows-comment-s" id="{{$item->comment_id}}" datauid="{{$item->comment_user_id}}" dataip="{{$item->comment_ip_address}}">
                        <i class="avt">{{$item->comment_name[0]}}</i>
                        <div class="comments" id="cmtid{{$item->comment_id}}">
                            <h5 class="nickname">{{$item->comment_name}}</h5>
                            <h5 class="vote">
                                <span class="datecmt">{{date('d/m/Y H:i:s',$item->comment_created)}}</span>
                                </br>
                                <b>
                                    <i class="fa fa-star item-vote active"></i>
                                    <i class="fa fa-star item-vote active"></i>
                                    <i class="fa fa-star item-vote active"></i>
                                    <i class="fa fa-star item-vote active"></i>
                                    <i class="fa fa-star item-vote active"></i>
                                </b>
                                <i class="reply-comment" datapanel="panel{{$k+1}}" dataid="{{$item->comment_id}}">trả
                                    lời</i>
                            </h5>
                            <div class="comment-content">{{$item->comment_content}}</div>
                            @foreach($CMTPRD as $item2)
                                @if($item2->comment_parent_id==$item->comment_id)
                                    <div class="col-md-12 col-sm-12 col-xs-12 rows-comment-s"
                                         id="{{$item->comment_id}}">
                                        <i class="avt">{{$item2->comment_name[0]}}</i>
                                        <div class="comments" id="cmtid{{$item2->comment_id}}">
                                            <h5 class="nickname">{{$item2->comment_user_role!=0?'Quản trị viên':$item2->comment_name}}</h5>
                                            <h5 class="vote">
                                                <span class="datecmt">{{date('d/m/Y H:i:s',$item2->comment_created)}}</span>
                                                <b></b>
                                                <i class="reply-comment" datapanel="panel{{$k+1}}"
                                                   dataid="{{$item->comment_id}}">trả lời</i>
                                            </h5>
                                            <div class="comment-content">{{$item2->comment_content}} </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="panel panel-default hiddenrep" id="panel{{$k+1}}">
								<div class="panel-body">
                                <textarea class="form-control textcmt" rows="3"
                                          placeholder="Trả lời bình luận ..." dataidrep="{{$item->comment_id}}" required
                                          id="cmt{{$k+1}}"></textarea>
                                </div>
                                <div class="panel-footer">
                                    @if(empty($member) && empty($admin))
                                        <div class="col-md-4 col-sm-4 col-xs-5 itemcmt">
                                            <input class="form-control" type="text" id="txtname{{$k+1}}"
                                                   placeholder="Họ tên (bắt buộc)" required/>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-5 itemcmt">
                                            <input class="form-control" type="text" id="txtphone{{$k+1}}"
                                                   placeholder="Số điện thoại" required/>
                                        </div>
                                    @endif
                                    <button type="button" datacmt="cmt{{$k+1}}" dataip="{{$k+1}}"
                                            class="btn btn-primary postcomment">
                                        Gửi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

{{--<div class="modal fade" id="modelcomment" tabindex="-1" role="dialog" aria-labelledby="checkIfo">--}}
    {{--<div class="modal-dialog" role="document">--}}
        {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>--}}
                {{--</button>--}}
                {{--<h4 class="modal-title" id="checkIfo">Thông tin người gửi</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
                {{--<form>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="name" class="control-label">Họ tên</label>--}}
                        {{--<input type="text" class="form-control" id="name" required>--}}
                    {{--</div>--}}
                    {{--<div class="form-group ">--}}
                        {{--<label for="phone" class="control-label">Số điện thoại</label>--}}
                        {{--<input type="text" class="form-control" id="phone" required>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="message" class="control-label">Bình luận</label>--}}
                        {{--<textarea class="form-control" id="message" required></textarea>--}}
                    {{--</div>--}}
                    {{--<input type="hidden" id="pidprd" value="{{isset($prdid)?$prdid:0}}">--}}
                    {{--<input type="hidden" id="cmtid" value="0">--}}
                    {{--<input type="hidden" id="check"--}}
                           {{--value="{{ !empty($member)?$member['member_id']:!empty($admin)?$admin['user_id']:0}}">--}}
                    {{--<input type="hidden" id="roleck"--}}
                           {{--value="{{ !empty($member)?0:!empty($admin)?$admin['user_role_id']:0}}">--}}
                {{--</form>--}}
            {{--</div>--}}
            {{--<div class="modal-footer" style="text-align: center">--}}
                {{--<button type="button" class="btn btn-default" data-dismiss="modal">Gửi bình luận</button>--}}
                {{--<button type="button" class="btn btn-primary sendcomment">Gửi bình luận</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}



