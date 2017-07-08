<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\Role;
use App\model\Trash;
use Illuminate\Http\Request;
use App\model\Module;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;

class RoleController extends BaseAdminController
{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
    protected $arrAllowUL = array(-1 => 'Chọn cho phép upload', \CGlobal::status_show => 'Có', \CGlobal::status_hide => 'Không');
    public function __construct()
    {
        parent::__construct();
    }
    public function listView(Request $request)
    {
        $this->menu();
        $this->title('Role');
        $this->breadcrumb([['title' => 'Role', 'link' => route('admin.role'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['role_title'] = $request->has('role_title') ? $request->role_title : '';
        $search['role_status'] = (int)$request->has('role_status') ? $request->role_status : -1;
        $dataSearch = Role::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search,$request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['role_status']);
        return view('Manager.role.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }

    public function getItem(Request $request,$id=0){
        $this->menu();
        $this->title($id==0?'Thêm mới Role':'Cập nhật Role');
        $this->breadcrumb([['title'=>'Role','link'=>\route('admin.role'),'active'=>''],['title'=>$id==0?'thêm mới':'cập nhật','link'=>\route('admin.role_edit',['id'=>$id]),'active'=>'active']]);
        $data = $id>0? $data = Role::getById($id):array();
        $arrModule = Module::getAll(array(), 0);
        $optionStatus = Utility::getOption($this->arrStatus,isset($data['role_status'])?$data['role_status']:\CGlobal::status_show);
        $optionAllowUL = Utility::getOption($this->arrAllowUL,isset($data['allow_upload'])?$data['allow_upload']:\CGlobal::status_show);
        return view('Manager.role.add',['id'=>$id,'data'=>$data,'optionStatus'=>$optionStatus,'arrModule'=>$arrModule,'arrAllowUL'=>$this->arrAllowUL,'optionAllowUL'=>$optionAllowUL,'arrAction'=>$this->arrAction]);
    }
    public function postItem(Request $request,$id=0){
        $this->validate($request,['role_title'=>'required|string',
            'role_status'=>'required|int|min:0',
            'role_order_no'=>'required|int|min:0']);
        $data = array('role_title'=>$request->role_title,
            'role_permission'=>serialize($request->access),
            'role_status'=>$request->role_status,
            'role_order_no'=>$request->role_order_no,
			'allow_upload'=>$request->allow_upload,
            'role_created'=>time());
        $id = ($id == 0) ? $request->id_hidden : $id;
        if($id>0){
            unset($data['role_created']);
        }
        Role::saveItem($data,$id);
        return redirect()->route('admin.role');
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                Trash::addItem($id,Session::get('user')['user_id'],'App\model\Role',Role::FOLDER,'role_id','role_title','','');
                    Role::deleteItem($id);
                }
            }
            return redirect()->route('admin.role');
        }
    }
}
