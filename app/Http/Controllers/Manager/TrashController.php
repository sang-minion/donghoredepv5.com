<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\Trash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \Pagging;

class TrashController extends BaseAdminController{
    public function __construct(){
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Trash');
        $this->breadcrumb([['title' => 'Trash', 'link' => route('admin.trash'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['trash_title'] = $request->has('trash_title') ? addslashes($request->trash_title) : '';
        $dataSearch = Trash::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search,$request->url()) : '';
        return view('Manager.trash.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging]);
    }

    public function getItem(Request $request,$id=0){
        $this->menu();
        $this->title('Trash');
        $this->breadcrumb([['title'=>'Trash','link'=>\route('admin.trash'),'active'=>''],['title'=>'Nội dung tạm xóa','link'=>\route('admin.module_edit',['id'=>$id]),'active'=>'active']]);
        $data = array();
        $data = array();
        $arrField = array();
        if($id > 0) {
            $data = Trash::getById($id);
            $class = $data->trash_class;
            $ObjClass = new $class();
            $arrField = $ObjClass->getFillable();
        }
        return view('Manager.trash.add',['id'=>$id,'data'=>$data,'arrField'=>$arrField]);
    }
    public function restore(Request $request,$id=0){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::restoreItem($id);
                    Trash::deleteItem($id);
                }
            }
            return redirect()->route('admin.trash');
        }
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::deleteItem($id);
                }
            }
            return redirect()->route('admin.trash');
        }
    }
}
