<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 01/07/2017
 * Time: 11:01 SA
 */

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\BaseAdminController;
use App\model\Category;
use App\model\Trash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \Pagging;
use \Utility;
use \Upload;

class ProductCategoryController extends BaseAdminController
{
    protected $arrStatus = array(-1 => 'chọn trạng thái', \CGlobal::status_show => 'Hiện', \CGlobal::status_hide => 'ẩn');
    protected $parent = array();

    public function __construct()
    {
        parent::__construct();
        $this->parent = Category::getById(Category::getIdByKeyword(\CGlobal::key_danh_muc_dh));
    }

    public function listView(Request $request)
    {
        $this->menu();
        $this->title('Danh mục sản phẩm');
        $this->breadcrumb([['title' => 'Danh mục sản phẩm', 'link' => route('admin.product_category'), 'active' => 'active']]);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = array();
        $total = 0;
        $search['category_title'] = $request->has('category_title') ? $request->category_title : '';
        $search['category_status'] = (int)$request->has('category_status') ? $request->category_status : -1;
        $search['category_parent_id'] = $this->parent->category_id;
        $dataSearch = Category::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request->url()) : '';
        $optionStatus = Utility::getOption($this->arrStatus, $search['category_status']);
        return view('Manager.product_category.list', ['search' => $search, 'data' => $dataSearch, 'total' => $total, 'paging' => $paging, 'optionStatus' => $optionStatus]);
    }

    public function getItem(Request $request, $id = 0)
    {
        $this->menu();
        $this->title($id == 0 ? 'Thêm danh mục mới' : 'Cập nhật danh mục');
        $this->breadcrumb([['title' => 'Danh mục sản phẩm', 'link' => \route('admin.product_category'), 'active' => ''], ['title' => $id == 0 ? 'thêm mới' : 'cập nhật', 'link' => \route('admin.product_category_edit', ['id' => $id]), 'active' => 'active']]);
        $data = array();
        if ($id > 0) {
            $data = Category::getById($id);
            if (!empty($data)) {
                if ($data->category_parent_id != $this->parent->category_id) {
                    $data = [];
                }
            }
        }
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['category_status']) ? $data['category_status'] : \CGlobal::status_show);
        return view('Manager.product_category.add', ['id' => $id, 'data' => $data, 'optionStatus' => $optionStatus]);
    }

    public function postItem(Request $request, $id = 0)
    {
        $id = $id == 0 ? $request->id_hidden : $id;
        $this->validate($request, [
            'category_title' => 'required|string',
            'category_status' => 'required|int|min:0']);
        $data = array('category_title' => addslashes($request->category_title),
            'category_parent_id' => $this->parent->category_id,
            'category_keyword' => Utility::pregReplaceStringAlias(addslashes($request->category_keyword)),
            'category_intro' => $request->category_intro,
            'horizontal_menu' => $request->horizontal_menu,
            'vertical_menu' => $request->vertical_menu,
            'category_order_no' => $request->category_order_no,
            'category_status' => $request->category_status,
            'meta_title' => addslashes($request->meta_title),
            'meta_keywords' => addslashes($request->meta_keywords),
            'meta_description' => addslashes($request->meta_description),
            'category_created' => time());
        $item = Category::getById($id);
        if (!empty($item)) {
            if ($item->category_parent_id == $this->parent->category_id) {
                $fileName = '';
                $banner = '';
                if ($request->hasFile('category_media') && $request->file('category_media')->isValid()) {
                    if ($id <= 0) {
                        $id = Category::saveItem(['category_status' => \CGlobal::status_img, 'category_created' => time()], $id);
                    }
                    if ($id > 0) {
                        $fileName = \Upload::uploadFile(
                            'category_media',
                            $_file_ext = 'jpg,jpeg,png',
                            $_max_file_size = 10 * 1024 * 1024,
                            $_folder = Category::FOLDER . '/' . $id,
                            $type_json = 0
                        );
                    }
                }
                if ($request->remove_media == 1 && (!empty($item) && $item->category_media != '')) {
                    Upload::unlinkFileAndFolder($item->category_media, $id, 'uploads/' . Category::FOLDER, 'uploads/thumbs/' . Category::FOLDER, 0);
                }
                if ($request->remove_media == 0 && (!empty($item) && $item->category_media != '')) {
                    $fileName = $item->category_media;
                }
                $data['category_media'] = $fileName;
                if ($request->hasFile('category_media_banner') && $request->file('category_media_banner')->isValid()) {
                    if ($id <= 0) {
                        $id = Category::saveItem(['category_status' => \CGlobal::status_img, 'category_created' => time()], $id);
                    }
                    if ($id > 0) {
                        $banner = \Upload::uploadFile(
                            'category_media_banner',
                            $_file_ext = 'jpg,jpeg,png',
                            $_max_file_size = 10 * 1024 * 1024,
                            $_folder = Category::FOLDER . '/' . $id,
                            $type_json = 0
                        );
                    }
                }
                if ($request->remove_media_banner == 1 && (!empty($item) && $item->category_media_banner != '')) {
                    Upload::unlinkFileAndFolder($item->category_media_banner, $id, 'uploads/' . Category::FOLDER, 'uploads/thumbs/' . Category::FOLDER, 0);
                }
                if ($request->remove_media_banner == 0 && (!empty($item) && $item->remove_media_banner != '')) {
                    $banner = $item->category_media_banner;
                }
                $data['category_media_banner'] = $banner;
                if ($id > 0) {
                    unset($data['category_created']);
                }
                Category::saveItem($data, $id);
            }
        }else{
            $fileName = '';
            $banner = '';
            if ($request->hasFile('category_media') && $request->file('category_media')->isValid()) {
                if ($id <= 0) {
                    $id = Category::saveItem(['category_status' => \CGlobal::status_img, 'category_created' => time()], $id);
                }
                if ($id > 0) {
                    $fileName = \Upload::uploadFile(
                        'category_media',
                        $_file_ext = 'jpg,jpeg,png',
                        $_max_file_size = 10 * 1024 * 1024,
                        $_folder = Category::FOLDER . '/' . $id,
                        $type_json = 0
                    );
                }
            }
            if ($request->remove_media == 1 && (!empty($item) && $item->category_media != '')) {
                Upload::unlinkFileAndFolder($item->category_media, $id, 'uploads/' . Category::FOLDER, 'uploads/thumbs/' . Category::FOLDER, 0);
            }
            if ($request->remove_media == 0 && (!empty($item) && $item->category_media != '')) {
                $fileName = $item->category_media;
            }
            $data['category_media'] = $fileName;
            if ($request->hasFile('category_media_banner') && $request->file('category_media_banner')->isValid()) {
                if ($id <= 0) {
                    $id = Category::saveItem(['category_status' => \CGlobal::status_img, 'category_created' => time()], $id);
                }
                if ($id > 0) {
                    $banner = \Upload::uploadFile(
                        'category_media_banner',
                        $_file_ext = 'jpg,jpeg,png',
                        $_max_file_size = 10 * 1024 * 1024,
                        $_folder = Category::FOLDER . '/' . $id,
                        $type_json = 0
                    );
                }
            }
            if ($request->remove_media_banner == 1 && (!empty($item) && $item->category_media_banner != '')) {
                Upload::unlinkFileAndFolder($item->category_media_banner, $id, 'uploads/' . Category::FOLDER, 'uploads/thumbs/' . Category::FOLDER, 0);
            }
            if ($request->remove_media_banner == 0 && (!empty($item) && $item->remove_media_banner != '')) {
                $banner = $item->category_media_banner;
            }
            $data['category_media_banner'] = $banner;
            if ($id > 0) {
                unset($data['category_created']);
            }
            Category::saveItem($data, $id);
        }
        return redirect()->route('admin.product_category');
    }

    public function delete(Request $request)
    {
        $checkID = $request->checkItem;
        $token = $request->_token;
        if (Session::token() === $token) {
            if (!empty($checkID) && is_array($checkID)) {
                foreach ($checkID as $id) {
                    $item = Category::getById($id);
                    if (!empty($item)) {
                        if ($item->category_parent_id == $this->parent->category_id) {
                            Trash::addItem($id, Session::get('user')['user_id'], 'App\model\Category', Category::FOLDER, 'category_id', 'category_title', 'category_media', '');
                            Category::deleteItem($id);
                        }
                    }
                }
            }
        }
        return redirect()->route('admin.product_category');
    }


}