<?php

/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 30/05/2017
 * Time: 14:48 CH
 */
class Pagging
{
    public static function getPager($numPageShow = 2, $page = 1, $total = 1, $limit = 1, $dataSearch,$url,$page_name = 'page'){
        $total_page = ceil($total/$limit);
        if($total_page == 1) return '';
        $next = '';
        $last = '';
        $prev = '';
        $first= '';
        $left_dot  = '';
        $right_dot = '';
        $from_page = $page - $numPageShow;
        $to_page = $page + $numPageShow;

        if(isset($dataSearch['field_get'])){
            unset($dataSearch['field_get']);
        }

        //get prev & first link
        if($page > 1){
            $prev = self::parseLink($page-1, '', "&lt;",$url, $page_name, $dataSearch);
            $first= self::parseLink(1, '', "&laquo;",$url, $page_name, $dataSearch);
        }
        //get next & last link
        if($page < $total_page){
            $next = self::parseLink($page+1, '', "&gt;",$url, $page_name, $dataSearch);
            $last = self::parseLink($total_page, '', "&raquo;",$url, $page_name, $dataSearch);
        }
        //get dots & from_page & to_page
        if($from_page > 0)	{
            $left_dot = ($from_page > 1) ? '<li><span>...</span></li>' : '';
        }else{
            $from_page = 1;
        }

        if($to_page < $total_page)	{
            $right_dot = '<li><span>...</span></li>';
        }else{
            $to_page = $total_page;
        }
        $pagerHtml = '';
        for($i=$from_page;$i<=$to_page;$i++){
            $pagerHtml .= self::parseLink($i, (($page == $i) ? 'active' : ''), $i,$url, $page_name, $dataSearch);
        }
        return '<ul class="pagination">'.$first.$prev.$left_dot.$pagerHtml.$right_dot.$next.$last.'</ul>';
    }

    static function parseLink($page = 1, $class="", $title="",$url, $page_name = 'page', $dataSearch){
        $param = $dataSearch;
        if($class == 'active'){
            return '<li class="'.$class.'"><a href="javascript:void(0)" title="xem trang '.$title.'">'.$title.'</a></li>';
        }
        return '<li class="'.$class.'"><a href="'.self::buildAction($url,$dataSearch,$page_name,$page).'" title="xem trang '.$title.'">'.$title.'</a></li>';
    }
    public static function buildAction($url,$parameter,$page_name,$page){
        $url.='?';
        foreach ($parameter as $k=>$v){
            $url.=$k.'='.$v.'&';
        }
        return $url.=$page_name.'='.$page;
    }
}