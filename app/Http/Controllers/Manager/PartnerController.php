<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\Partner;
use App\model\Trash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PartnerController extends BaseAdminController{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
    public function __construct()
    {
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Đối tác');
        $this->breadcrumb([['title' => 'Đối tác', 'link' => route('admin.partner'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['partner_title'] = $request->has('partner_title') ? $request->partner_title : '';
        $search['partner_status'] = (int)$request->has('partner_status') ? $request->partner_status : -1;
        $dataSearch = Partner::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? \Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search,$request->url()) : '';
        $optionStatus = \Utility::getOption($this->arrStatus, $search['partner_status']);
        return view('Manager.partner.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }
    public function getItem(Request $request,$id=0){
        $this->menu();
        $this->title($id==0?'Thêm đối tác':'Cập nhật');
        $this->breadcrumb([['title'=>'Đối tác','link'=>\route('admin.partner'),'active'=>''],['title'=>$id==0?'thêm mới':'cập nhật','link'=>\route('admin.partner_edit',['id'=>$id]),'active'=>'active']]);
        \Loader::loadJS('libs\ckeditor\ckeditor.js',\CGlobal::$postHead);
        $data = $id>0?$data = Partner::getById($id):array();
        $optionStatus = \Utility::getOption($this->arrStatus,isset($data['partner_status'])?$data['partner_status']:\CGlobal::status_show);
        return view('Manager.partner.add',['id'=>$id,'data'=>$data,'optionStatus'=>$optionStatus]);
    }
    public function postItem(Request $request,$id=0){
        $this->validate($request,['partner_title'=>'required|string',
            'partner_status'=>'required|int|min:0']);
        $data = array('partner_title'=>$request->partner_title,
            'partner_website'=>addslashes($request->partner_website),
            'partner_address'=>$request->partner_address,
            'partner_intro'=>$request->partner_intro,
            'partner_status'=>$request->partner_status,
            'partner_created'=>time());
        $id = ($id == 0) ? $request->id_hidden : $id;
        $item = Partner::getById($id);
        $fileName = '';
        if ($request->hasFile('partner_logo') && $request->file('partner_logo')->isValid()) {
            if ($id <= 0) {
                $id = Partner::saveItem(['partner_status' => \CGlobal::status_img,'partner_created'=>time()], $id);
            }
            if ($id > 0) {
                $fileName = \Upload::uploadFile(
                    'partner_logo',
                    $_file_ext = 'jpg,jpeg,png',
                    $_max_file_size = 10 * 1024 * 1024,
                    $_folder = Partner::FOLDER . '/' . $id,
                    $type_json = 0
                );
            }
        }
        if($request->remove_media==1&&(!empty($item) && $item->partner_logo != '')){
            \Upload::unlinkFileAndFolder($item->partner_logo, $id, 'uploads/' . Partner::FOLDER, 'uploads/thumbs/' . Partner::FOLDER, 0);
        }
        if($request->remove_media==0&&(!empty($item) && $item->partner_logo != '')){
            $fileName = $item->partner_logo;
        }
        $data['partner_logo'] = $fileName;
        if($id>0){
            unset($data['partner_created']);
        }
        Partner::saveItem($data,$id);
        return redirect()->route('admin.partner');
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\Partner', Partner::FOLDER, 'partner_id', 'partner_title', 'partner_logo', '');
                    Partner::deleteItem($id);
                }
            }
            return redirect()->route('admin.partner');
        }
    }
}
