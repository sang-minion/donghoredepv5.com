<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 04/07/2017
 * Time: 14:15 CH
 */

namespace App\Http\Controllers\Manager;
use App\model\CommentHome;
use Illuminate\Http\Request;
use App\model\Trash;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;
use \Upload;

use App\Http\Controllers\BaseAdminController;

class CommentHomeController extends BaseAdminController
{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
    public function __construct()
    {
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Phản hồi của khách hàng');
        $this->breadcrumb([['title' => 'Phản hồi của khách hàng', 'link' => route('admin.comment'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['name'] = $request->has('name') ? $request->name : '';
        $search['cmt_status'] = (int)$request->has('cmt_status') ? $request->cmt_status : -1;
        $dataSearch = CommentHome::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['cmt_status']);
        return view('Manager.comment_home.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }
    public function getItem(Request $request,$id=0){
        $this->menu();
        $this->title($id == 0 ? 'Phản hồi của khách hàng' : 'Phản hồi của khách hàng');
        $this->breadcrumb([['title' => 'Phản hồi của khách hàng', 'link' => \route('admin.comment'), 'active' => ''], ['title' => $id == 0 ? 'thêm mới' : 'cập nhật', 'link' => \route('admin.comment_edit', ['id' => $id]), 'active' => 'active']]);
        $data =$id > 0?$data = CommentHome::getById($id):array();
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['cmt_status']) ? $data['cmt_status'] : \CGlobal::status_show);
        return view('Manager.comment_home.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus]);
    }
    public function postItem(Request $request,$id=0){
        $id = $id == 0 ? $request->id_hidden : $id;
        $this->validate($request, [
            'cmt_name' => 'required|string',
            'cmt_status' => 'required|int|min:0']);
        $data = array('cmt_name' => addslashes($request->cmt_name),
            'cmt_link' => $request->cmt_link,
            'cmt_status' => $request->cmt_status,
            'cmt_content'=> addslashes($request->cmt_content),
            'cmt_created' => time());
        $item = CommentHome::getById($id);
        $fileName = '';
        if ($request->hasFile('cmt_avt') && $request->file('cmt_avt')->isValid()) {
            if ($id <= 0) {
                $id = CommentHome::saveItem(['cmt_status' => \CGlobal::status_img, 'cmt_created' => time()], $id);
            }
            if ($id > 0) {
                $fileName = \Upload::uploadFile(
                    'cmt_avt',
                    $_file_ext = 'jpg,jpeg,png',
                    $_max_file_size = 10 * 1024 * 1024,
                    $_folder = CommentHome::FOLDER . '/' . $id,
                    $type_json = 0
                );
            }
        }
        if ($request->remove_media == 1 && (!empty($item) && $item->cmt_avt != '')) {
            Upload::unlinkFileAndFolder($item->cmt_avt, $id, 'uploads/' . CommentHome::FOLDER, 'uploads/thumbs/' . CommentHome::FOLDER, 0);
        }
        if ($request->remove_media == 0 && (!empty($item) && $item->cmt_avt != '')) {
            $fileName = $item->cmt_avt;
        }
        $data['cmt_avt'] = $fileName;
        if ($id > 0) {
            unset($data['cmt_created']);
        }
        CommentHome::saveItem($data, $id);
        return redirect()->route('admin.comment');
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\CommentHome', CommentHome::FOLDER, 'cmt_id', 'cmt_name', 'cmt_avt', '');
                    CommentHome::deleteItem($id);
                }
            }
            return redirect()->route('admin.comment');
        }
    }

}