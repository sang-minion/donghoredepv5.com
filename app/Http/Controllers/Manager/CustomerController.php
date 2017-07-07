<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 01/07/2017
 * Time: 10:26 SA
 */

namespace App\Http\Controllers\Manager;


use App\Http\Controllers\BaseAdminController;
use App\model\EmailCustomer;
use Illuminate\Http\Request;
use App\model\Trash;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;
use \Upload;

class CustomerController extends BaseAdminController
{
    protected $arrStatus = array(-1 => 'chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'ẩn');
    public function __construct()
    {
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Email khách hàng');
        $this->breadcrumb([['title' => 'Email khách hàng', 'link' => route('admin.customer'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['name'] = $request->has('name') ? $request->name : '';
        $search['customer_status'] = (int)$request->has('customer_status') ? $request->customer_status : -1;
        $dataSearch = EmailCustomer::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['customer_status']);
        return view('Manager.customer.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);

    }
    public function getItem(Request $request,$id=0){
        die('chức năng không khả dụng');
    }
    public function postItem(Request $request){
        die('chức năng không khả dụng');
    }
    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\EmailCustomer', EmailCustomer::FOLDER, 'customer_id', 'customer_name', '', '');
                    EmailCustomer::deleteItem($id);
                }
            }
            return redirect()->route('admin.customer');
        }
    }


}