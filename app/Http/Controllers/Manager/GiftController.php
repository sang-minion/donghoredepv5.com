<?php

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\BaseAdminController;
use App\model\Gift;
use App\model\Trash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \Upload;
use \Pagging;
use \Utility;

class GiftController extends BaseAdminController{
    protected $arStatus = array(-1 => 'chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'ẩn');

    public function __construct(){
        parent::__construct();
    }

    public function listView(Request $request){
        $this->menu();
        $this->title('Quà tặng');
        $this->breadcrumb([['title' => 'Quà tặng', 'link' => route('admin.gift'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['gift_code'] = $request->has('gift_code') ? $request->gift_code : '';
        $search['gift_status'] = (int)$request->has('gift_status') ? $request->gift_status : -1;
        $dataSearch = Gift::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arStatus, $search['gift_status']);
        return view('Manager.gift.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }

    public function getItem(Request $request, $id = 0)
    {
        \Loader::loadJS('libs/ckeditor/ckeditor.js', \CGlobal::$postHead);
        $this->menu();
        $this->title($id == 0 ? 'Thêm quà tặng mới' : 'Cập nhật quà tặng');
        $this->breadcrumb([['title' => 'Quà tặng', 'link' => \route('admin.gift'), 'active' => ''], ['title' => $id == 0 ? 'thêm mới' : 'cập nhật', 'link' => \route('admin.gift_edit', ['id' => $id]), 'active' => 'active']]);
        $data = $id > 0? $data = Gift::getById($id):array();
        $optionStatus = Utility::getOption($this->arStatus, isset($data['gift_status']) ? $data['gift_status'] : \CGlobal::status_show);
        return view('Manager.gift.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus]);
    }

    public function postItem(Request $request, $id = 0){
        $id = $id == 0 ? $request->id_hidden : $id;
        $this->validate($request, ['gift_code' => 'required|string',
            'gift_title' => 'required|string',]);
        $data = array();
        $data['gift_code'] = Utility::pregReplaceStringAlias($request->gift_code);
        $data['gift_title'] = addslashes($request->gift_title);
		$data['gift_intro'] = addslashes($request->gift_intro);
        $data['gift_alias'] = Utility::pregReplaceStringAlias($request->gift_title);
		$data['gift_price_input']=$request->gift_price_input;
        $data['gift_price'] = $request->gift_price;
        $data['gift_status'] = $request->gift_status;
        $data['gift_created'] = time();
        $item = Gift::getById($id);
        $fileName = '';
        $listfileName = array();
        if ($request->hasFile('gift_media') && $request->file('gift_media')->isValid()) {
            if ($id <= 0) {
                $id = Gift::saveItem(['gift_status' => \CGlobal::status_img, 'gift_created' => time()], $id);
            }
            if ($id > 0) {
                $fileName = \Upload::uploadFile(
                    'gift_media',
                    $_file_ext = 'jpg,jpeg,png',
                    $_max_file_size = 10 * 1024 * 1024,
                    $_folder = Gift::FOLDER . '/' . $id,
                    $type_json = 0
                );
            }
        }
        if ($request->remove_media == 1 && (!empty($item) && $item->gift_media != '')) {
            Upload::unlinkFileAndFolder($item->gift_media, $id, 'uploads/' . Gift::FOLDER, 'uploads/thumbs/' . Gift::FOLDER, 0);
        }
        if ($request->remove_media == 0 && (!empty($item) && $item->gift_media != '')) {
            $fileName = $item->gift_media;
        }
        $data['gift_media'] = $fileName;
        $ar = !empty($item) && $item->gift_multi_media != '' ? unserialize($item->gift_multi_media) : array();
        if (is_array($request->gift_multi_media) && count($request->gift_multi_media) > 0) {
            if ($id <= 0) {
                $id = Gift::saveItem(['gift_status' => \CGlobal::status_img, 'gift_created' => time()], $id);
            }
            if ($id > 0) {
                $listfileName = \Upload::UploadMultiFile(
                    'gift_multi_media',
                    $_file_ext = 'jpg,jpeg,png',
                    $_max_file_size = 10 * 1024 * 1024,
                    $_folder = Gift::FOLDER . '/' . $id,
                    $type_json = 0
                );
            }
        }
        if (($request->has('remove_multi_media') && is_array($request->remove_multi_media) && count($request->remove_multi_media) > 0) && (!empty($ar))) {
            foreach ($request->remove_multi_media as $k => $v) {
                if ($v != -1) {
                    Upload::unlinkFileAndFolder($ar[$v], $id, 'uploads/' . Gift::FOLDER, 'uploads/thumbs/' . Gift::FOLDER, 0);
                    unset($ar[$v]);
                }
            }
            if (!empty($ar)) {
                foreach ($ar as $k => $v) {
                    $listfileName[] = $v;
                }
            }
        }
        $data['gift_multi_media'] = serialize($listfileName);
        Gift::saveItem($data, $id);
        return redirect()->route('admin.gift');
    }

    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\Gift', Gift::FOLDER, 'gift_id', 'gift_title', 'gift_media', 'gift_multi_media');
                    Gift::deleteItem($id);
                }
            }
            return redirect()->route('admin.gift');
        }
    }
}
