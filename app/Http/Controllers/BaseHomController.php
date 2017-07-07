<?php

namespace App\Http\Controllers;


use App\model\Category;
use App\model\StaticInfor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use function PHPSTORM_META\map;

class BaseHomController extends Controller
{
    public function __construct()
    {
    }

    public function menu($active='')
    {
        $arMenu = array();
        $listMenu = Category::getAllMenu();
        if (!empty($listMenu)) {
            foreach ($listMenu as $item) {
                if ($item->horizontal_menu == \CGlobal::status_show && $item->category_status = \CGlobal::status_show) {
                    $vertical_menu = array();
                    $t = false;
                    foreach ($listMenu as $item2) {
                        if ($item2->vertical_menu == \CGlobal::status_show && $item2->category_parent_id==$item->category_id&&$item2->category_status = \CGlobal::status_show){
                            if($active===$item2->category_keyword)$t=true;
                            $vertical_menu[] = array('title' => $item2->category_title, 'link' => \Funclip::buildLinkCategory($item2->category_id,$item2->category_keyword), 'icon' => '', 'active' => $active===$item2->category_keyword? 'active':'');
                        }
                    }
                    $arMenu[] = array('title' => $item->category_title, 'link' => \Funclip::buildLinkCategory($item->category_id,$item->category_keyword), 'icon' => '', 'active' =>empty($vertical_menu)?$active===$item->category_keyword?'active': '':$t?'active':'','submenu'=>$vertical_menu);
                    $t = false;
                }
            }
        }
        $hostline  = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_hostline));
        $zalo = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_zalo));
        $footer_left = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_footer_left));
        $hotroKH = StaticInfor::getAllByParentKey(\CGlobal::key_ho_tro_khach_hang,100);
        $chinhsachChung = StaticInfor::getAllByParentKey(\CGlobal::key_chinh_sach_chung,100);
        $chinhanhdaily = StaticInfor::getAllByParentKey(\CGlobal::key_chi_nhanh_dai_ly,100);
		$fanpageFB = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_fanpage_fabook));
		$boxChat = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_box_chat_support));
		$linkFB = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_link_facebook));
		$linkGG = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_link_google));
		$linkYoutube = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_kenh_youtube));
		$flagCounter = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_counter));
		
        return ['menu'=>$arMenu,'hostline'=>isset($hostline->static_content)?$hostline->static_content:\CGlobal::$txt_hostline,
            'zalo'=>isset($zalo->static_content)?$zalo->static_content:\CGlobal::$txt_zalo,
            'footer_left'=>isset($footer_left->static_content)?$footer_left->static_content:'',
            'hotroKH'=>$hotroKH,'chinhsachChung'=>$chinhsachChung,'maps'=>$chinhanhdaily,
			'fanpageFB'=>$fanpageFB,'boxChat'=>$boxChat,'linkFB'=>$linkFB,'linkGG'=>$linkGG,'linkYoutube'=>$linkYoutube,
			'flagCounter'=>$flagCounter];
    }

	public function pagenotfound404(){
		$header = $this->menu('');
		return view('error.404',$header);
	}
}
