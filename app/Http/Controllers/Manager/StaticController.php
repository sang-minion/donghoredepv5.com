<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 17/06/2017
 * Time: 20:31 CH
 */

namespace App\Http\Controllers\Manager;


use App\Http\Controllers\BaseAdminController;
use App\model\Category;
use App\model\StaticInfor;
use App\model\Trash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaticController extends BaseAdminController{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
    protected $arrKey = array(-1 => 'Chọn danh mục', \CGlobal::key_chinh_sach_chung => 'Chính sách chung', \CGlobal::key_ho_tro_khach_hang => 'Hỗ trợ khách hàng',\CGlobal::key_chi_nhanh_dai_ly=>'Chi nhánh và đại lý');
    public function __construct(){
        parent::__construct();
        //$arSP = Category::getAll(['category_parent_id'=>Category::getIdByKeyword(\CGlobal::key_ho_tro_khach_hang)]);
        //$arPL = Category::getAll(['category_parent_id'=>Category::getIdByKeyword(\CGlobal::key_chinh_sach_chung)]);
        //$arCN = Category::getAll(['category_parent_id'=>Category::getIdByKeyword(\CGlobal::key_chi_nhanh_dai_ly)]);
        //foreach ($arSP as $item){
        //    $i = StaticInfor::getById(StaticInfor::getIdByKeyword($item->category_keyword));
        //    StaticInfor::updateItem(['static_parent_key'=>\CGlobal::key_ho_tro_khach_hang],$i['static_id']);
        //}
        //foreach ($arPL as $item){
        //    $i = StaticInfor::getById(StaticInfor::getIdByKeyword($item->category_keyword));
        //    StaticInfor::updateItem(['static_parent_key'=>\CGlobal::key_chinh_sach_chung],$i['static_id']);
        //}
        //foreach ($arCN as $item){
        //    $i = StaticInfor::getById(StaticInfor::getIdByKeyword($item->category_keyword));
         //   StaticInfor::updateItem(['static_parent_key'=>\CGlobal::key_chi_nhanh_dai_ly],$i['static_id']);
        //}
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Nội dung tĩnh');
        $this->breadcrumb([['title' => 'Nội dung tĩnh', 'link' => route('admin.static'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['static_title'] = $request->has('static_title') ? $request->static_title : '';
        $search['static_status'] = (int)$request->has('static_status') ? $request->static_status : -1;
        $search['static_parent_key'] = $request->has('static_parent_key') ? $request->static_parent_key : '';
        $dataSearch = StaticInfor::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? \Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = \Utility::getOption($this->arrStatus, $search['static_status']);
        $optionKey = \Utility::getOption($this->arrKey, $search['static_parent_key']);
        return view('Manager.static.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging,
            'optionStatus' => $optionStatus,'optionKey'=>$optionKey,'arrKey'=>$this->arrKey]);
    }
    public function getItem(Request $request, $id = 0){
        $this->menu();
        $this->title($id == 0 ? 'Thêm nội dung mới' : 'Cập nhật nội dung');
        $this->breadcrumb([['title' => 'Static', 'link' => \route('admin.static'), 'active' => ''], ['title' => $id == 0 ? 'thêm mới' : 'cập nhật', 'link' => \route('admin.static_edit', ['id' => $id]), 'active' => 'active']]);
        \Loader::loadJS('libs\ckeditor\ckeditor.js',\CGlobal::$postHead);
        $data =$id > 0 ? $data = StaticInfor::getById($id): array();
        $optionStatus = \Utility::getOption($this->arrStatus, isset($data['static_status']) ? $data['static_status'] : \CGlobal::status_show);
        $optionKey = \Utility::getOption($this->arrKey, isset($data['static_parent_key'])? $data['static_parent_key']:'');
        return view('Manager.static.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus,'optionKey'=>$optionKey]);
    }

    public function postItem(Request $request, $id = 0){
        $id = $id == 0 ? $request->id_hidden : $id;
        $this->validate($request, [
            'static_title' => 'required|string',
            'static_parent_key'=>'required|string',
            'static_status' => 'required|int|min:0']);
        $data = array('static_title' => addslashes($request->static_title),
            'static_content' => addslashes($request->static_content),
            'static_keyword' => \Utility::pregReplaceStringAlias(addslashes($request->static_title)),
            'static_parent_key'=>\Utility::pregReplaceStringAlias($request->has('static_parent_key')?$request->static_parent_key:''),
            'static_status' => $request->static_status);
        StaticInfor::saveItem($data, $id);
        return redirect()->route('admin.static');
    }

    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\StaticInfor', StaticInfor::FOLDER, 'static_id', 'static_title', '', '');
                    StaticInfor::deleteItem($id);
                }
            }
            return redirect()->route('admin.static');
        }
    }
}