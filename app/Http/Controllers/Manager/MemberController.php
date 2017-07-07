<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 01/07/2017
 * Time: 10:28 SA
 */

namespace App\Http\Controllers\Manager;


use App\Http\Controllers\BaseAdminController;
use App\model\Member;
use Illuminate\Http\Request;
use App\model\Trash;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;
use \Upload;

class MemberController extends BaseAdminController
{
    protected $arrStatus = array(-1 => 'chọn trạng thái',  \CGlobal::status_hide => 'Chờ duyệt',\CGlobal::status_show => 'Hiện',\CGlobal::status_die=>'Ẩn');
    public function __construct()
    {
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Thành viên');
        $this->breadcrumb([['title' => 'Thành viên', 'link' => route('admin.member'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['name'] = $request->has('name') ? $request->name : '';
        $search['member_status'] = (int)$request->has('member_status') ? $request->member_status : -1;
        $dataSearch = Member::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['member_status']);
        return view('Manager.member.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }
    public function getItem(Request $request,$id=0){
        $this->menu();
        $this->title($id == 0 ? 'Thêm mới thành viên' : 'Cập nhật thông tin');
        $this->breadcrumb([['title' => 'Thành viên', 'link' => \route('admin.member'), 'active' => ''], ['title' => $id == 0 ? 'thêm mới' : 'cập nhật', 'link' => \route('admin.member_edit', ['id' => $id]), 'active' => 'active']]);
        $data = $id > 0 ? $data = Member::getById($id): array();
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['member_status']) ? $data['member_status'] : -1,[\CGlobal::status_hide]);
        return view('Manager.member.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus]);
    }
    public function postItem(Request $request,$id=0){
        $id = ($id == 0) ? $request->id_hidden : $id;
        $this->validate($request, [
            'member_name' => 'required|string',
            'member_age' => 'required|int|min:10',
            'member_email' => 'required|email',
            'member_phone' => ['required','regex:/^0(1\d{9}|9\d{8})$/'],
            'member_address' => 'required|string|max:255',
            'member_status' => 'required|int|min:0']);
        if ($id > 0) {
            $this->validate($request, ['member_email' => 'required|email']);
        } else {
            $this->validate($request, ['member_email' => 'required|email|unique:member']);
        }
        if ($request->has('member_pass')) {
            $this->validate($request, [
                'member_pass' => 'required|string|min:6|confirmed'
            ]);
        }
        $item = Member::getById($id);
        $fileName = '';
        if ($request->hasFile('member_avt') && $request->file('member_avt')->isValid()) {
            if ($id <= 0) {
                $id = Member::saveItem(['member_status' => \CGlobal::status_img,'member_created'=>time()], $id);
            }
            if ($id > 0) {
                $fileName = \Upload::uploadFile(
                    'member_avt',
                    $_file_ext = 'jpg,jpeg,png',
                    $_max_file_size = 10 * 1024 * 1024,
                    $_folder = Member::FOLDER . '/' . $id,
                    $type_json = 0
                );
            }
        }
        if($request->remove_media==1&&(!empty($item) && $item->member_avt != '')){
            Upload::unlinkFileAndFolder($item->member_avt, $id, 'uploads/' . Member::FOLDER, 'uploads/thumbs/' . Member::FOLDER, 0);
        }
        if($request->remove_media==0&&(!empty($item) && $item->member_avt != '')){
            $fileName = $item->member_avt;
        }
        
        $data = array(
            'member_name' => $request->member_name,
            'member_age' => $request->member_age,
            'member_email' => $request->member_email,
            'member_phone'=>$request->member_phone,
            'member_address'=>$request->member_address,
            'member_status' => $request->member_status,
            'member_avt'=>$fileName,
            'member_created'=>time());
        if ($request->has('member_pass')) {
            $data['member_pass'] = bcrypt($request->member_pass);
        }
        if($id>0){
            unset($data['member_created']);
        }
        Member::saveItem($data, $id);
        return redirect()->route('admin.member');
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\Member', Member::FOLDER, 'member_id', 'member_name', 'member_avt', '');
                    Member::deleteItem($id);
                }
            }
            return redirect()->route('admin.member');
        }
    }
    public function changeStatus(Request $request){

        $c = '';
        if(!empty($_POST)){
            $id = $request->has('id')?$request->id:0;
            $stt = $request->has('stt')?$request->stt:-1;
            if ($id>0&&$stt!=-1){
                if(Member::updateItem(['member_status'=>$stt],$id)){
                    $c = 1;
                }
            }
        }
        echo $c;exit();
    }

}