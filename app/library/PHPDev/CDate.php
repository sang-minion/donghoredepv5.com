<?php

/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 30/05/2017
 * Time: 14:33 CH
 */
class CDate
{
// Convert Date To Int
    public  static function convertDate($date=''){
        if($date!=''){
            $date = str_replace('/', '-', $date);
            $strtotime = strtotime($date);
            return $strtotime;
        }
        return time();
    }

    // Show Date To String
    public static function showDate($date){
        $_date='';
        if($date){
            $_date = date('d/m/Y', intval($date));
            return $_date;
        }
        return date('d/m/Y', time());
    }

    //Date Vietnamese Convert
    public static function date_vietname($str=''){
        $current_date_str='';
        $arrListTodayVietnamese = array("Mon" => "Thứ hai","Tue" => "Thứ ba","Wed" => "Thứ tư","Thu" => "Thứ năm","Fri" => "Thứ sáu","Sat" => "Thứ bảy","Sun" => "Chủ nhật");
        foreach($arrListTodayVietnamese as $k => $v){
            if(strtolower($str)===strtolower($k)){
                $current_date_str = $v;
            }
        }
        return $current_date_str;
    }
}