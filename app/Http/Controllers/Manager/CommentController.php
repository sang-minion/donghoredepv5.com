<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 01/07/2017
 * Time: 11:17 SA
 */

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\Comment;
use App\model\CommentProduct;
use App\model\Product;
use Illuminate\Http\Request;
use App\model\Trash;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;
use \Upload;

class CommentController extends BaseAdminController
{
    protected $arrStatus = array(-1 => 'chọn trạng thái', \CGlobal::status_hide => 'Chờ duyệt', \CGlobal::status_show => 'Hiện',\CGlobal::status_die=>'Ẩn');
    protected $arPRD = array(-1=>'Chọn sản phẩm');
    public function __construct()
    {
        parent::__construct();
        $arpd = Product::getAll();
        foreach ($arpd as $item){
            $this->arPRD[$item->product_id]=$item->product_title;
        }

    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Comment sản phẩm');
        $this->breadcrumb([['title' => 'Comment sản phẩm', 'link' => route('admin.comment_product'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['name'] = $request->has('name') ? $request->name : '';
        $search['comment_product_id'] = (int)$request->has('comment_product_id') ? $request->comment_product_id : -1;
        $search['comment_start'] = addslashes($request->has('comment_start')?$request->comment_start:'');
        $search['comment_end'] = addslashes($request->has('comment_end')?$request->comment_end:'');
        $search['comment_status'] = (int)$request->has('comment_status') ? $request->comment_status : -1;
        $dataSearch = CommentProduct::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['comment_status']);
        $optionPRD = Utility::getOption($this->arPRD, $search['comment_product_id']);
        return view('Manager.comment_product.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging,
            'optionStatus' => $optionStatus,'arProduct'=>$this->arPRD,'optionPRD'=>$optionPRD]);
    }
    public function getItem(Request $request,$id=0){
        return redirect()->back();
   }
    public function postItem(Request $request,$id=0){
        return redirect()->back();
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\CommentProduct', CommentProduct::FOLDER, 'comment_id', 'comment_name', '', '');
                    CommentProduct::deleteItem($id);
                }
            }
            return redirect()->route('admin.comment_product');
        }
    }
}