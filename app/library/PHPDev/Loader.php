<?php

/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 30/05/2017
 * Time: 14:42 CH
 */
class Loader
{
    public static function loadTitle($title){
        CGlobal::$title = $title!=''?$title:config('app.name');
    }
    public static function loadCSS($file_name, $position=1){
        if(is_array($file_name)){
            foreach($file_name as $v){
                Loader::loadCSS($v);
            }
            return;
        }

        if(strpos($file_name, 'http://') !== false){
            $html = '<link rel="stylesheet" href="' . $file_name . ((CGlobal::$cssVer) ? '?ver=' . CGlobal::$cssVer : '') . '" type="text/css">' . "\n";
            if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderCSS, $html) === false)
                CGlobal::$extraHeaderCSS .= $html . "\n";
            elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterCSS, $html) === false)
                CGlobal::$extraFooterCSS .= $html . "\n";
        }else{
            $html = '<link type="text/css" rel="stylesheet" href="' . url('', array(), Config::get('config.SECURE')) . '/resources/assets/' . $file_name . ((CGlobal::$cssVer) ? '?ver=' . CGlobal::$cssVer : '') . '" />' . "\n";
            if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderCSS, $html) === false)
                CGlobal::$extraHeaderCSS .= $html . "\n";
            elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterCSS, $html) === false)
                CGlobal::$extraFooterCSS .= $html . "\n";
        }
    }
    //Load js to header or footer
    public static function loadJS($file_name, $position=1){
        if(is_array($file_name)){
            foreach($file_name as $v){
                Loader::loadJS($v);
            }
            return;
        }

        if(strpos($file_name, 'http://') !== false){
            $html = '<script type="text/javascript" src="' . $file_name . ((CGlobal::$jsVer) ? '?ver=' . CGlobal::$jsVer : '') . '"></script>';
            if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderJS, $html) === false)
                CGlobal::$extraHeaderJS .= $html . "\n";
            elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterJS, $html) === false)
                CGlobal::$extraFooterJS .= $html . "\n";
        }else{
            $html = '<script type="text/javascript" src="' . url('', array(), Config::get('config.SECURE')) . '/resources/assets/' . $file_name . ((CGlobal::$jsVer) ? '?ver=' . CGlobal::$jsVer : '') . '"></script>';
            if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderJS, $html) === false)
                CGlobal::$extraHeaderJS .= $html . "\n";
            elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterJS, $html) === false)
                CGlobal::$extraFooterJS .= $html . "\n";
        }
    }

    public static function loadBreadcrumb($listItem = array()){
        if (!empty($listItem)){
            foreach ($listItem as $item){
                $active = $item['active']!=''?$item['active']:"";
                $item['link'] = $active!=''?'javascript:void(0)':$item['link'];
                CGlobal::$breadcrumbtop.='<li class="'.$active.'"><a href="'.$item['link'].'" title="'.$item['title'].'">'.$item['title'].'</a></li>';
            }
        }
    }

    public static function loadMenu($listItem = array()){
        if (!empty($listItem)){
            foreach ($listItem as $k => $item){
                $active = isset($item['active'])&&$item['active']!=''?$item['active']:'';
                $icon = isset($item['icon'])&&$item['icon']!=''?$item['icon']:'';
                if (isset($item['submenu'])&&is_array($item['submenu'])&&!empty($item['submenu'])){
                    $idmenu = isset($item['idSubMenu'])&&$item['idSubMenu']!=''?$item['idSubMenu']:'subMenu'.$k;
                    CGlobal::$textMenu.='<li class="'.$active.'"><a href="'.$item['link'].'" data-toggle="collapse" data-target="#'.$idmenu.'"> 
                    '.$item['title'].'<i class="fa '.$icon.'"></i></a>
                    <ul id="'.$idmenu.'" class="collapse">';
                    CGlobal::$textMenu.=self::loadMenu($item['submenu']);
                    CGlobal::$textMenu.='</ul></li>';
                }else{
                    CGlobal::$textMenu.='<li class="'.$active.'"><a href="'.$item['link'].'" title="'.$item['title'].'">'.$item['title'].'<i class="fa '.$icon.'"></i></a></li>';
                }
            }
        }
    }


}