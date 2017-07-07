<?php

/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 30/05/2017
 * Time: 14:51 CH
 */
class Seo
{
    public static function SEOS($img='', $meta_title='', $meta_keywords='', $meta_description='', $url=''){
        if($img == ''){
            $img = config('app.base_url').'uploads/default.jpg';
        }
        if($meta_title ==''){
            $meta_title = CGlobal::nameSite;
        }
        if($meta_keywords == ''){
            $meta_keywords = $meta_title;
        }
        if($meta_description == ''){
            $meta_description = $meta_title;
        }

        $str = '';
        $str .= '<title>'.$meta_title.'</title>';
        $str .= '<meta name="robots" content="index,follow">';
        $str .= '<meta http-equiv="REFRESH" content="1800">';
        $str .= '<meta name="revisit-after" content="days">';
        $str .= '<meta http-equiv="content-language" content="vi"/>';
        $str .= '<meta name="copyright" content="'.CGlobal::domain.'">';
        $str .= '<meta name="author" content="'.CGlobal::domain.'">';

        //Google
        $str .= '<meta name="keywords" content="'.$meta_keywords.'">';
        $str .= '<meta name="description" content="'.$meta_description.'">';

        //Facebook
        $str .= '<meta content="article" property="og:type">';
        $str .= '<meta content="'.$meta_title.'" property="og:title">';
        $str .= '<meta content="'.$meta_description.'" property="og:description">';
        $str .= '<meta content="'.CGlobal::nameSite.'" property="og:site_name">';
        $str .= '<meta content="'.$img.'" itemprop="thumbnailUrl" property="og:image">';

        //Twitter
        $str .= '<meta name="twitter:title" content="'.$meta_title.'">';
        $str .= '<meta name="twitter:description" content="'.$meta_description.'">';
        $str .= '<meta name="twitter:image" content="'.$img.'">';

        if($url != ''){
            $str .= '<link rel="canonical" href="'.$url.'">';
            $str .= '<meta property="og:url" itemprop="url" content="'.$url.'">';
            $str .= '<meta name="twitter:url" content="'.$url.'">';
        }

        CGlobal::$extraMeta = $str;
    }
}