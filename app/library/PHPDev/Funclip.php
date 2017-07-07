<?php

/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 30/05/2017
 * Time: 14:37 CH
 */
class Funclip
{
    static function post_db_parse_html($t=""){
        if ( $t == "" ){
            return $t;
        }
        $t = str_replace( "&#39;"   , "'", $t );
        $t = str_replace( "&#33;"   , "!", $t );
        $t = str_replace( "&#036;"   , "$", $t );
        $t = str_replace( "&#124;"  , "|", $t );
        $t = str_replace( "&amp;"   , "&", $t );
        $t = str_replace( "&gt;"    , ">", $t );
        $t = str_replace( "&lt;"    , "<", $t );
        $t = str_replace( "&quot;"  , '"', $t );

        //-----------------------------------------
        // Take a crack at parsing some of the nasties
        // NOTE: THIS IS NOT DESIGNED AS A FOOLPROOF METHOD
        // AND SHOULD NOT BE RELIED UPON!
        //-----------------------------------------

        $t = preg_replace( "/javascript/i" , "j&#097;v&#097;script", $t );
        $t = preg_replace( "/alert/i"      , "&#097;lert"          , $t );
        $t = preg_replace( "/about:/i"     , "&#097;bout:"         , $t );
        $t = preg_replace( "/onmouseover/i", "&#111;nmouseover"    , $t );
        $t = preg_replace( "/onmouseout/i", "&#111;nmouseout"    , $t );
        $t = preg_replace( "/onclick/i"    , "&#111;nclick"        , $t );
        $t = preg_replace( "/onload/i"     , "&#111;nload"         , $t );
        $t = preg_replace( "/onsubmit/i"   , "&#111;nsubmit"       , $t );
        $t = preg_replace( "/object/i"   , "&#111;bject"       , $t );
        $t = preg_replace( "/frame/i"   , "fr&#097;me"       , $t );
        $t = preg_replace( "/applet/i"   , "&#097;pplet"       , $t );
        $t = preg_replace( "/meta/i"   , "met&#097;"       , $t );

        return $t;
    }

    static function stripUnicode($str){
        if(!$str) return false;
        $marTViet = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă",
            "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
            "ì", "í", "ị", "ỉ", "ĩ",
            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
            "ỳ", "ý", "ỵ", "ỷ", "ỹ",
            "đ",
            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
            "Ì", "Í", "Ị", "Ỉ", "Ĩ",
            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ"
        , "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
            "Đ");

        $marKoDau=array("a","a","a","a","a","a","a","a","a","a","a"
        ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o"
        ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A"
        ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O"
        ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D");

        $str = str_replace($marTViet,$marKoDau,$str);
        return $str;
    }
    static function _name_cleaner($name,$replace_string="_"){
        return preg_replace( "/[^a-zA-Z0-9\-\_]/", $replace_string , $name );
    }

    //Cac ky sap xep gan nhau
    static function safeTitle($text) {
        $text = FuncLip::post_db_parse_html($text);
        $text = FuncLip::stripUnicode($text);
        $text = self::_name_cleaner($text, "-");
        $text = str_replace("----", "-", $text);
        $text = str_replace("---", "-", $text);
        $text = str_replace("--", "-", $text);
        $text = trim($text, '-');

        if ($text) {
            return strtolower($text);
        } else {
            return ' ';
        }
    }
    //Number Format
    static function numberFormat($number = 0){
        if ($number >= 1000) {
            return number_format($number, 0, '.', '.');
        }
        return $number;
    }
    static function formatPhonenumber($phone=''){
        $rs = '';
        if ($phone!=''&&strlen($phone)>9){
            $rs = substr($phone,0,3).'.'.substr($phone,3,3).'.'.substr($phone,6,strlen($phone));
        }
        return $rs;
    }
    //Buid Link Category
    static function buildLinkCategory($cat_id = 0, $cat_title = 'Danh-mục'){
        if($cat_id > 0){
            return URL::route('home.category', array('name'=>strtolower(FuncLip::safeTitle($cat_title)), 'id'=>$cat_id));
        }
        return '#';
    }
    //Buid Link News
    static function buildLinkDetailNews($id = 0, $news_title = 'Chi-tiet'){
        if($id > 0){
            return URL::route('home.news', array('id'=>$id, 'name'=>strtolower(FuncLip::safeTitle($news_title))));
        }
        return '#';
    }
    //Buid Link policy
    static function buildLinkDetailPolicy($id = 0, $news_title = 'Chinh-sach-chung'){
        if($id > 0){
            return URL::route('home.policy', array('id'=>$id, 'name'=>strtolower(FuncLip::safeTitle($news_title))));
        }
        return '#';
    }
    //Buid Link policy
    static function buildLinkDetailSupport($id = 0, $news_title = 'Ho-tro-khach-hang'){
        if($id > 0){
            return URL::route('home.support', array('id'=>$id, 'name'=>strtolower(FuncLip::safeTitle($news_title))));
        }
        return '#';
    }
    //Buid Link News
    static function buildLinkDetailProduct($id = 0, $news_title = 'Chi-tiet'){
        if($id > 0){
            return URL::route('home.details', array('id'=>$id, 'name'=>strtolower(FuncLip::safeTitle($news_title))));
        }
        return '#';
    }
    static function sortBySubValue($array, $value, $asc = true, $preserveKeys = false){
        if(!empty($array)){
            if ($preserveKeys) {
                $c = array();
                if (is_object(reset($array))) {
                    foreach ($array as $k => $v) {
                        $b[$k] = strtolower($v->$value);
                    }
                } else {
                    foreach ($array as $k => $v) {
                        $b[$k] = strtolower($v[$value]);
                    }
                }
                $asc ? asort($b) : arsort($b);
                foreach ($b as $k => $v) {
                    $c[$k] = $array[$k];
                }
                $array = $c;
            } else {
                if (is_object(reset($array))) {
                    usort($array, function ($a, $b) use ($value, $asc) {
                        return $a->{$value} == $b->{$value} ? 0 : ($a->{$value} - $b->{$value}) * ($asc ? 1 : -1);
                    });
                } else {
                    usort($array, function ($a, $b) use ($value, $asc) {
                        return $a[$value] == $b[$value] ? 0 : ($a[$value] - $b[$value]) * ($asc ? 1 : -1);
                    });
                }
            }
        }
        return $array;
    }
}