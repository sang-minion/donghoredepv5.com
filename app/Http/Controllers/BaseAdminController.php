<?php

namespace App\Http\Controllers;

use App\model\Banner;
use App\model\Category;
use App\model\CommentHome;
use App\model\CommentProduct;
use App\model\Gift;
use App\model\Member;
use App\model\Module;
use App\model\News;
use App\model\Product;
use App\model\Role;
use App\model\StaticInfor;
use App\model\Users;
use App\model\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\AssignOp\Mod;

class BaseAdminController extends Controller
{
    protected $user = array();
    protected $arrAction =['listView'=>'Danh sách','getItem'=>'Chi tiết','postItem'=>'lưu thay đổi','delete'=>'xóa','changeSTT'=>'Ajax cập nhạt trạng thái',
        'changeass'=>'đổi mật khẩu','profileUser'=>'Cá nhân','restore'=>'khôi phục'];

    public function __construct(){
        $this->middleware('admin');
        \Loader::loadCSS('backend/css/sb-admin.css', \CGlobal::$postHead);
        \Loader::loadJS('backend/js/sb-admin.js', \CGlobal::$postHead);
    }

    public function dashBoard(){
        $this->menu();
        $this->title('Dashboard');
        return view('Manager.dashboard.view1');
    }
    public function title($title_name){
        \Loader::loadTitle($title_name);
    }

    public function menu(){
        $rName = Route::current()->getName();
        $arMenu = array();
        $arMenu[] = array('title' => 'Đơn hàng', 'link' => route('admin.order'), 'icon' => 'fa-globe fa-admin', 'active' => $this->checkRouteName($rName, 'admin.order') ? 'active' : '');
        $arMenu[] = array('title' => 'Hỗ trợ online', 'link' => route('admin.live_chat'), 'icon' => 'fa-comments fa-admin', 'active' => $this->checkRouteName($rName, 'admin.live_chat') ? 'active' : '');
        $arMenu[] = array('title' => 'Khách hàng', 'link' => 'javascript:void(0)', 'icon' => 'fa-angle-down',
            'submenu' => array(
                //array('title' => 'Thành viên', 'link' => route('admin.member'), 'icon' => 'fa-user-plus fa-admin', 'active' => $this->checkRouteName($rName, 'admin.member') ? 'active' : ''),
                array('title' => 'Email khách hàng', 'link' => route('admin.customer'), 'icon' => 'fa-user-o fa-admin', 'active' => $this->checkRouteName($rName, 'admin.customer') ? 'active' : ''),
                array('title' => 'Comment Sản phẩm', 'link' => route('admin.comment_product'), 'icon' => 'fa-comment fa-admin', 'active' => $this->checkRouteName($rName, 'admin.comment_product') ? 'active' : ''),
				array('title' => 'Phản hồi khách hàng', 'link' => route('admin.comment'), 'icon' => 'fa-comment fa-admin', 'active' => $this->checkRouteName($rName, 'admin.comment') ? 'active' : '')
			));
        $arMenu[] = array('title' => 'Sản phẩm', 'link' => 'javascript:void(0)', 'icon' => 'fa-angle-down',
            'submenu' => array(
                array('title' => 'Danh mục sản phẩm', 'link' => route('admin.product_category'), 'icon' => 'fa-bars fa-admin', 'active' => $this->checkRouteName($rName, 'admin.product_category') ? 'active' : ''),
                array('title' => 'Nhãn hiệu sản phẩm', 'link' => route('admin.product_brand'), 'icon' => 'fa-star fa-admin', 'active' => $this->checkRouteName($rName, 'admin.product_brand') ? 'active' : ''),
                array('title' => 'Sản phẩm', 'link' => route('admin.product'), 'icon' => 'fa-bookmark fa-admin', 'active' => $this->checkRouteName($rName, 'admin.product') ? 'active' : ''),
                array('title' => 'Quà tặng', 'link' => route('admin.gift'), 'icon' => 'fa-gift fa-admin', 'active' => $this->checkRouteName($rName, 'admin.gift') ? 'active' : '')
            ));
        
        $arMenu[] = array('title' => 'Khác', 'link' => 'javascript:void(0)', 'icon' => 'fa-angle-down',
            'submenu' => array(
                array('title' => 'Bài viết', 'link' => route('admin.news'), 'icon' => 'fa-quote-right fa-admin', 'active' => $this->checkRouteName($rName, 'admin.news') ? 'active' : ''),
				array('title' => 'Banner', 'link' => route('admin.banner'), 'icon' => 'fa-image fa-admin', 'active' => $this->checkRouteName($rName, 'admin.banner') ? 'active' : ''),
				array('title' => 'Hỗ trợ khách hàng', 'link' => route('admin.support'), 'icon' => 'fa-phone fa-admin', 'active' => $this->checkRouteName($rName, 'admin.support') ? 'active' : ''),
				array('title' => 'Chính sách chung', 'link' => route('admin.policy'), 'icon' => 'fa-hashtag fa-admin', 'active' => $this->checkRouteName($rName, 'admin.policy') ? 'active' : ''),
				array('title' => 'Đối tác', 'link' => route('admin.partner'), 'icon' => 'fa-handshake-o fa-admin', 'active' => $this->checkRouteName($rName, 'admin.partner') ? 'active' : ''),
		 ));
        $arMenu[] = array('title' => 'Hệ thống', 'link' => 'javascript:void(0)', 'icon' => 'fa-angle-down',
            'submenu' => array(
				array('title' => 'Danh mục chung', 'link' => route('admin.category'), 'icon' => 'fa-th fa-admin', 'active' => $this->checkRouteName($rName, 'admin.category') ? 'active' : ''),
                array('title' => 'Nỗi dung tĩnh', 'link' => route('admin.static'), 'icon' => 'fa-certificate fa-admin', 'active' => $this->checkRouteName($rName, 'admin.static') ? 'active' : ''),
                array('title' => 'Module', 'link' => route('admin.module'), 'icon' => 'fa-gears fa-admin', 'active' => $this->checkRouteName($rName, 'admin.module') ? 'active' : ''),
                array('title' => 'Phân quyển', 'link' => route('admin.role'), 'icon' => 'fa-key fa-admin', 'active' => $this->checkRouteName($rName, 'admin.role') ? 'active' : ''),
                array('title' => 'Người dùng', 'link' => route('admin.user'), 'icon' => 'fa-group fa-admin', 'active' => $this->checkRouteName($rName, 'admin.user') ? 'active' : ''),
                array('title' => 'Thùng rác', 'link' => route('admin.trash'), 'icon' => 'fa-trash fa-admin', 'active' => $this->checkRouteName($rName, 'admin.trash') ? 'active' : '')
            ));
        \Loader::loadMenu($arMenu);
    }

    public function checkRouteName($routeName = '', $nameCheck = ''){
        $r = false;
        if ($routeName === $nameCheck || strtolower(substr($routeName, 0, strlen($nameCheck))) === strtolower($nameCheck)) {
            $r = true;
        }
        return $r;
    }

    public function breadcrumb($listItem){
        \Loader::loadBreadcrumb($listItem);
    }
    public function changeSTT(Request $request){
        $c = '';
        if(!empty($_POST)){
            $id = $request->has('id')?$request->id:0;
            $stt = $request->has('stt')?$request->stt:-1;
            $type = $request->has('type')?$request->type:0;
            if($type>0&&$id>0&&$stt!=-1){
                if($type==1){
                    if(CommentProduct::updateItem(['comment_status'=>$stt],$id)){
                        CommentProduct::removeCache($id);
                        $c=1;
                    }
                }
                if($type==2){
                    if(Banner::updateItem(['Banner_status'=>$stt],$id)){
                        Banner::removeCache($id);
                        $c=1;
                    }
                }
                if($type==3){
                    if(Product::updateItem(['product_status'=>$stt],$id)){
                        Product::removeCache($id);
                        $c=1;
                    }
                }
                if($type==4){
                    if(Category::updateItem(['category_status'=>$stt],$id)){
                        Category::removeCache($id);
                        $c=1;
                    }
                }
                if($type==5){
                    if(News::updateItem(['news_status'=>$stt],$id)){
                        News::removeCache($id);
                        $c=1;
                    }
                }
                if($type==6){
                    if(StaticInfor::updateItem(['static_status'=>$stt],$id)){
                        StaticInfor::removeCache($id);
                        $c=1;
                    }
                }
                if($type==7){
                    if(Users::updateItem(['user_status'=>$stt],$id)){
                        Users::removeCache($id);
                        $c=1;
                    }
                }
                if($type==8){
                    if(Role::updateItem(['role_status'=>$stt],$id)){
                        Role::removeCache($id);
                        $c=1;
                    }
                }
                if($type==9){
                    if(Module::updateItem(['module_status'=>$stt],$id)){
                        Module::removeCache($id);
                        $c=1;
                    }
                }
                if($type==10){
                    if(CommentHome::updateItem(['cmt_status'=>$stt],$id)){
                        CommentHome::removeCache($id);
                        $c=1;
                    }
                }
                if($type==11){
                    if(Gift::updateItem(['gift_status'=>$stt],$id)){
                        Gift::removeCache($id);
                        $c=1;
                    }
                }
                if($type==12){
                    if(Member::updateItem(['member_status'=>$stt],$id)){
                        Member::removeCache($id);
                        $c=1;
                    }
                }
				if($type==13){
                    if(Partner::updateItem(['partner_status'=>$stt],$id)){
                        Partner::removeCache($id);
                        $c=1;
                    }
                }
            }
        }
        echo $c;exit();
    }
}
