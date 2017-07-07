<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\Trash;
use Illuminate\Http\Request;
use App\model\Module;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;

class ModuleController extends BaseAdminController{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
    public function __construct() {
        parent::__construct();
    }

    public function listView(Request $request){
        $this->menu();
        $this->title('Module');
        $this->breadcrumb([['title' => 'Module', 'link' => route('admin.module'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['module_title'] = $request->has('module_title') ? addslashes($request->module_title) : '';
        $search['module_status'] = (int)$request->has('module_status') ? $request->module_status : -1;
        $dataSearch = Module::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search,$request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['module_status']);
        return view('Manager.module.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }

    public function getItem(Request $request,$id=0){
        $this->menu();
        $this->title($id==0?'Thêm mới Module':'Cập nhật Module');
        $this->breadcrumb([['title'=>'Module','link'=>\route('admin.module'),'active'=>''],['title'=>$id==0?'thêm mới':'cập nhật','link'=>\route('admin.module_edit',['id'=>$id]),'active'=>'active']]);
        $data = $id>0?$data = Module::getById($id):array();
        $optionStatus = Utility::getOption($this->arrStatus,isset($data['module_status'])?$data['module_status']:-1);
        return view('Manager.module.add',['id'=>$id,'data'=>$data,'optionStatus'=>$optionStatus]);
    }
    public function postItem(Request $request,$id=0){
        $this->validate($request,['module_title'=>'required|string',
            'module_controller'=>'required|string',
            'module_status'=>'required|int|min:0',
            'module_order_no'=>'required|int|min:0']);
        $data = array('module_title'=>$request->module_title,
            'module_controller'=>$request->module_controller,
            'module_action'=>serialize($request->module_action),
            'module_status'=>$request->module_status,
            'module_order_no'=>$request->module_order_no,
            'module_created'=>time());
        $id = ($id == 0) ? $request->id_hidden : $id;
        if($id>0){
            unset($data['module_created']);
        }
        Module::saveItem($data,$id);
        return redirect()->route('admin.module');
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                Trash::addItem($id,Session::get('user')['user_id'],'App\model\Module',Module::FOLDER,'module_id','module_title','','');
                    Module::deleteItem($id);
                }
            }
            return redirect()->route('admin.module');
        }
    }
}
