<?php

namespace App\Http\Controllers\Manager;


use App\Http\Controllers\BaseAdminController;
use App\model\Role;
use App\model\Trash;
use App\model\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;

class UsersController extends BaseAdminController{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
    protected $arrRole = array(-1 => 'chọn nhóm quyển');
    public function __construct(){
        parent::__construct();
        $role = Role::getAll(array(), 0);
        foreach ($role as $item) {
            $this->arrRole[$item->role_id] = $item->role_title;
        }
    }

    public function listView(Request $request){
        $this->menu();
        $this->title('User');
        $this->breadcrumb([['title' => 'User', 'link' => route('admin.user'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['name'] = $request->has('name') ? addslashes($request->name) : '';
        $search['user_status'] = (int)$request->has('user_status') ? $request->user_status : -1;
        $search['user_role_id'] = (int)$request->has('user_role_id') ? $request->user_role_id : -1;
        $dataSearch = Users::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['user_status']);
        $optionRole = \Utility::getOption($this->arrRole, $search['user_role_id']);
        return view('Manager.user.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus, 'optionRole' => $optionRole, 'arrRole' => $this->arrRole]);
    }

    public function getItem(Request $request, $id = 0){
        $this->menu();
        $this->title($id == 0 ? 'Thêm mới User' : 'Cập nhật User');
        $this->breadcrumb([['title' => 'User', 'link' => \route('admin.user'), 'active' => ''], ['title' => $id == 0 ? 'thêm mới' : 'cập nhật', 'link' => \route('admin.user_edit', ['id' => $id]), 'active' => 'active']]);
        $data = $id > 0 ? $data = Users::getById($id): array();
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['user_status']) ? $data['user_status'] : -1);
        $optionRole = \Utility::getOption($this->arrRole, isset($data['user_role_id']) ? $data['user_role_id'] : -1);
        return view('Manager.user.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus, 'arrRole' => $this->arrRole, 'optionRole' => $optionRole]);
    }

    public function postItem(Request $request, $id = 0){
        $id = ($id == 0) ? $request->id_hidden : $id;
        $this->validate($request, [
            'name' => 'required|string',
			'email' => 'required|email',
            'user_phone' => 'required|string|min:9|max:50',
            'user_address' => 'required|string|max:255',
            'user_status' => 'required|int|min:0',
            'user_role_id' => 'required|int|min:0']);
        if ($id > 0) {
            $this->validate($request, ['email' => 'required|email']);
        } else {
            $this->validate($request, ['email' => 'required|email|unique:users']);
        }
        if ($request->has('password')) {
            $this->validate($request, [
                'password' => 'required|string|min:6|confirmed'
            ]);
        }
        $data = array(
            'name' => $request->name,
            'user_phone' => $request->user_phone,
            'user_address' => $request->user_address,
            'user_status' => $request->user_status,
            'user_role_id' => $request->user_role_id,
            'email' => $request->email);
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }
        Users::saveItem($data, $id);
        return redirect()->route('admin.user');
    }

    public function profileUser(Request $request){
        $this->menu();
        $this->title('Cập nhật User');
        $this->breadcrumb([['title' => 'cập nhật profile', 'link' => '', 'active' => 'active']]);
        $data = array();
        $data = Users::getById(Session::get('user')['user_id']);
        return view('Manager.user.profile', ['id' => Session::get('user')['user_id'], 'data' => $data]);
    }

    public function postprofileUser(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'user_phone' => 'required|string|min:9|max:50',
            'user_address' => 'required|string|max:255',
            'email' => 'required|email'
        ]);
        $data = array(
            'name' => $request->name,
            'user_phone' => $request->user_phone,
            'user_address' => $request->user_address,
            'email' => $request->email);
        Users::saveItem($data, $request->id_hidden);
        return redirect()->route('index');
    }

    public function changeass(Request $request){
        $this->menu();
        $this->title('Change pass');
        $this->breadcrumb([['title' => 'cập nhật password', 'link' => '', 'active' => 'active']]);
        return view('Manager.user.changepass', ['id' => Session::get('user')['user_id']]);
    }

    public function postChangeass(Request $request){
        $this->validate($request, ['password' => 'required|string|min:6|confirmed']);
        Users::saveItem(['password' => bcrypt($request->password)], $request->id_hidden);
        return redirect()->route('index');
    }

    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\Users', Users::FOLDER, 'id', 'email', '', '');
                    Users::deleteItem($id);
                }
            }
            return redirect()->route('admin.user');
        }
    }
}
