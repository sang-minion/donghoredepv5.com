<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\News;
use App\model\Trash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NewsController extends BaseAdminController{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'Ẩn');
	protected $arrCate = array(-1 => 'Chọn danh mục', \CGlobal::key_tin_tuc => 'Tin tức', \CGlobal::key_khuyen_mai => 'Khuyến mãi');
    public function __construct()
    {
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Tin tức');
        $this->breadcrumb([['title' => 'Tin tức', 'link' => route('admin.news'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['news_title'] = $request->has('news_title') ? $request->news_title : '';
        $search['news_status'] = (int)$request->has('news_status') ? $request->news_status : -1;
		$search['news_key_parent'] = $request->has('news_key_parent') ? $request->news_key_parent : -1;
        $dataSearch = News::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? \Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search,$request->url()) : '';
        $optionStatus = \Utility::getOption($this->arrStatus, $search['news_status']);
		$optionCate = \Utility::getOption($this->arrCate, $search['news_key_parent']);
        return view('Manager.news.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus,'arCate'=>$this->arrCate,'optionCate'=>$optionCate]);
    }
    public function getItem(Request $request,$id=0){
        $this->menu();
        $this->title($id==0?'Thêm mới News':'Cập nhật News');
        $this->breadcrumb([['title'=>'Tin tức','link'=>\route('admin.news'),'active'=>''],['title'=>$id==0?'thêm mới':'cập nhật','link'=>\route('admin.news_edit',['id'=>$id]),'active'=>'active']]);
        \Loader::loadJS('libs\ckeditor\ckeditor.js',\CGlobal::$postHead);
        $data = $id>0?$data = News::getById($id):array();
        $optionStatus = \Utility::getOption($this->arrStatus,isset($data['news_status'])?$data['news_status']:\CGlobal::status_show);
		$optionCate = \Utility::getOption($this->arrCate, isset($data['news_key_parent'])?$data['news_key_parent']:\CGlobal::key_tin_tuc);
        return view('Manager.news.add',['id'=>$id,'data'=>$data,'optionStatus'=>$optionStatus,'optionCate'=>$optionCate]);
    }
    public function postItem(Request $request,$id=0){
        $this->validate($request,['news_title'=>'required|string',
            'news_status'=>'required|int|min:0']);
        $data = array('news_title'=>$request->news_title,
            'news_alias'=>\Utility::pregReplaceStringAlias($request->news_title),
            'news_intro'=>addslashes($request->news_intro),
            'news_content'=>addslashes($request->news_content),
            'news_status'=>$request->news_status,
			'news_key_parent'=>$request->has('news_key_parent')&&$request->news_key_parent!=-1?$request->news_key_parent:\CGlobal::key_tin_tuc,
            'meta_title'=>$request->meta_title,
            'meta_keywords'=>$request->meta_keywords,
            'meta_description'=>$request->meta_description,
            'news_created'=>time());
        $id = ($id == 0) ? $request->id_hidden : $id;
        $item = News::getById($id);
        $fileName = '';
        if ($request->hasFile('news_media') && $request->file('news_media')->isValid()) {
            if ($id <= 0) {
                $id = News::saveItem(['news_status' => \CGlobal::status_img,'news_created'=>time()], $id);
            }
            if ($id > 0) {
                $fileName = \Upload::uploadFile(
                    'news_media',
                    $_file_ext = 'jpg,jpeg,png',
                    $_max_file_size = 10 * 1024 * 1024,
                    $_folder = News::FOLDER . '/' . $id,
                    $type_json = 0
                );
            }
        }
        if($request->remove_media==1&&(!empty($item) && $item->news_media != '')){
            \Upload::unlinkFileAndFolder($item->news_media, $id, 'uploads/' . News::FOLDER, 'uploads/thumbs/' . News::FOLDER, 0);
        }
        if($request->remove_media==0&&(!empty($item) && $item->news_media != '')){
            $fileName = $item->news_media;
        }
        $data['news_media'] = $fileName;
        if($id>0){
            unset($data['news_created']);
        }
        News::saveItem($data,$id);
        return redirect()->route('admin.news');
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\News', News::FOLDER, 'news_id', 'news_title', 'news_media', '');
                    News::deleteItem($id);
                }
            }
            return redirect()->route('admin.news');
        }
    }
}
