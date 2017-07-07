<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\Banner;
use Illuminate\Http\Request;
use App\model\Trash;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;
use \Upload;
class BannerController extends BaseAdminController
{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
    public function __construct(){
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Banner');
        $this->breadcrumb([['title' => 'Banner', 'link' => route('admin.banner'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['banner_title'] = $request->has('banner_title') ? $request->banner_title : '';
        $search['banner_status'] = (int)$request->has('banner_status') ? $request->banner_status : -1;
        $dataSearch = Banner::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['banner_status']);
        return view('Manager.banner.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }

    public function getItem(Request $request, $id = 0){
        $this->menu();
        $this->title($id == 0 ? 'Thêm banner mới' : 'Cập nhật banner');
        $this->breadcrumb([['title' => 'Banner', 'link' => \route('admin.banner'), 'active' => ''], ['title' => $id == 0 ? 'thêm mới' : 'cập nhật', 'link' => \route('admin.banner_edit', ['id' => $id]), 'active' => 'active']]);
        $data =$id > 0?$data = Banner::getById($id):array();
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['banner_status']) ? $data['banner_status'] : \CGlobal::status_show);
        return view('Manager.banner.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus]);
    }
    public function postItem(Request $request, $id = 0){
        $id = $id == 0 ? $request->id_hidden : $id;
        $this->validate($request, [
            'banner_title' => 'required|string',
            'banner_status' => 'required|int|min:0']);
        $data = array('banner_title' => addslashes($request->banner_title),
            'banner_link' => $request->banner_link,
            'banner_order_no' => $request->banner_order_no,
            'banner_status' => $request->banner_status,
			'banner_ghim'=> (int) $request->has('banner_ghim')? $request->banner_ghim : \CGlobal::status_hide,
            'banner_created' => time());
        $item = Banner::getById($id);
        $fileName = '';
        if ($request->hasFile('banner_media') && $request->file('banner_media')->isValid()) {
            if ($id <= 0) {
                $id = Banner::saveItem(['banner_status' => \CGlobal::status_img, 'banner_created' => time()], $id);
            }
            if ($id > 0) {
                $fileName = \Upload::uploadFile(
                    'banner_media',
                    $_file_ext = 'jpg,jpeg,png',
                    $_max_file_size = 10 * 1024 * 1024,
                    $_folder = Banner::FOLDER . '/' . $id,
                    $type_json = 0
                );
            }
        }
        if ($request->remove_media == 1 && (!empty($item) && $item->banner_media != '')) {
            Upload::unlinkFileAndFolder($item->banner_media, $id, 'uploads/' . Banner::FOLDER, 'uploads/thumbs/' . Banner::FOLDER, 0);
        }
        if ($request->remove_media == 0 && (!empty($item) && $item->banner_media != '')) {
            $fileName = $item->banner_media;
        }
        $data['banner_media'] = $fileName;
        if ($id > 0) {
            unset($data['banner_created']);
        }
        Banner::saveItem($data, $id);
        return redirect()->route('admin.banner');
    }

    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\Banner', Banner::FOLDER, 'banner_id', 'banner_title', 'banner_media', '');
                    Banner::deleteItem($id);
                }
            }
            return redirect()->route('admin.banner');
        }
    }
}
