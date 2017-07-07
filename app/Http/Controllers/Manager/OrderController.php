<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 17/06/2017
 * Time: 21:01 CH
 */

namespace App\Http\Controllers\Manager;
use App\Http\Controllers\BaseAdminController;
use App\model\Gift;
use App\model\Order;
use App\model\Trash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends BaseAdminController{
    protected $arrStatus = array(-1 => 'Chọn trạng thái', \CGlobal::status_hide => 'Đơn mới', \CGlobal::status_show => 'Đơn đã xác nhận', \CGlobal::status_success => 'Đơn thành công', \CGlobal::status_cancel => 'Đơn hoàn',\CGlobal::status_die=>'Đơn hủy');
    public function __construct(){
        parent::__construct();
    }
    public function listView(Request $request){
        $this->menu();
        $this->title('Đơn đặt hàng');
        $this->breadcrumb([['title' => 'Đơn đặt hàng', 'link' => route('admin.order'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['order_name'] = $request->has('order_name') ? $request->order_name : '';
        $search['order_status'] = (int)$request->has('order_status') ? $request->order_status : -1;
        $search['order_start'] = addslashes($request->has('order_start')?$request->order_start:'');
        $search['order_end'] = addslashes($request->has('order_end')?$request->order_end:'');
        $dataSearch = Order::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? \Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search,$request->url()) : '';
        $optionStatus = \Utility::getOption($this->arrStatus, $search['order_status']);
        return view('Manager.order.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }
    public function getItem(Request $request, $id = 0){
        $this->menu();
        $this->title('Chi tiết đơn hàng');
        $this->breadcrumb([['title' => 'Đơn đặt hàng', 'link' => \route('admin.order'), 'active' => ''], ['title' =>'Chi tiết đơn hàng', 'link' => \route('admin.order_edit', ['id' => $id]), 'active' => 'active']]);
        $data = $id > 0 ? $data = Order::getById($id):array();
        $optionStatus = \Utility::getOption($this->arrStatus, isset($data['order_status']) ? $data['order_status'] : \CGlobal::status_show);
        return view('Manager.order.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus]);
    }

    public function postItem(Request $request, $id = 0){
        $id = $id == 0 ? $request->id_hidden : $id;
        $data = array('order_status' => $request->order_status);
        Order::saveItem($data, $id);
        return redirect()->route('admin.order');
    }

    public function delete(Request $request){
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    Trash::addItem($id, Session::get('user')['user_id'], 'App\model\Order', Order::FOLDER, 'order_id', 'order_name', '', '');
                    Order::deleteItem($id);
                }
            }
            return redirect()->route('admin.order');
        }
    }
}