<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('page-404', 'BaseHomController@pagenotfound404')->name('page-404');

//home



Route::get('/', 'Home\HomeController@index')->name('index');
Route::get('{name}-{id}.html',array('as' => 'home.details','uses' =>'Home\HomeController@details'))->where('name', '[A-Z0-9a-z_\-]+')->where('id', '[0-9]+');
Route::get('danh-muc/{name}-{id}.html',array('as' => 'home.category','uses' =>'Home\HomeController@category'))->where('name', '[A-Z0-9a-z_\-]+')->where('id', '[0-9]+');
Route::get(CGlobal::key_tin_tuc.'/{name?}-{id?}.html',array('as' => 'home.news','uses' =>'Home\HomeController@news'))->where('name', '[A-Z0-9a-z_\-]+')->where('id', '[0-9]+');
Route::get(CGlobal::key_ho_tro_khach_hang.'/{name?}-{id?}.html',array('as' => 'home.support','uses' =>'Home\HomeController@support'))->where('name', '[A-Z0-9a-z_\-]+')->where('id', '[0-9]+');
Route::get(CGlobal::key_chinh_sach_chung.'/{name?}-{id?}.html',array('as' => 'home.policy','uses' =>'Home\HomeController@policy'))->where('name', '[A-Z0-9a-z_\-]+')->where('id', '[0-9]+');
Route::get('tim-kiem', 'Home\HomeController@search')->name('search');
Route::post('them-vao-gio-hang.html',array('as'=>'addCart','uses'=>'Home\CartController@ajaxAddCart'));
Route::get('gio-hang.html',array('as'=>'giohang','uses'=>'Home\CartController@pageOrderCart'));
Route::post('gio-hang.html',array('as'=>'giohang','uses'=>'Home\CartController@pageOrderCart'));
Route::post('xoa-gio-hang.html',array('as'=>'xoagiohang','uses'=>'Home\CartController@deleteAllItemInCart'));
Route::post('xoa-mot-san-pham-trong-gio-hang.html',array('as'=>'xoa1spgiohang','uses'=>'Home\CartController@deleteOneItemInCart'));
Route::get('dat-hang.html',array('as'=>'dathang','uses'=>'Home\CartController@pageSendCart'));
Route::post('dat-hang.html',array('as'=>'dathang','uses'=>'Home\CartController@pageSendCart'));
Route::post('dat-hang-one.html',array('as'=>'dathang1','uses'=>'Home\CartController@sendCart'));
Route::get('cam-on-da-dat-hang.html',array('as'=>'camondathang','uses'=>'Home\CartController@pageThanksBuy'));
Route::post('gia-san-pham.html',array('as'=>'giasanpham','uses'=>'Home\CartController@getPrice'));
Route::post('post-comment.html',array('as'=>'comment','uses'=>'Home\HomeController@comment'));
Route::post('post-vote.html',array('as'=>'vote','uses'=>'Home\HomeController@vote'));
// admin
Auth::routes();

Route::group(array('prefix' => 'admin', 'before' => '','middleware'=>'web'), function(){

    Route::get('/', 'BaseAdminController@dashBoard')->name('admin.dashboard');
    Route::post('change-status.html', 'BaseAdminController@changeSTT')->name('admin.member_change_status');

//    Route::get('/', 'Manager\OrderController@listView')->name('admin.dashboard');
    Route::get('order', 'Manager\OrderController@listView')->name('admin.order');
    Route::get('order/edit/{id?}', 'Manager\OrderController@getItem')->name('admin.order_edit')->where('id', '[0-9]+');
    Route::post('order/edit/{id?}', 'Manager\OrderController@postItem')->name('admin.order_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'order/delete', 'Manager\OrderController@delete')->name('admin.order_delete');
    /// module
    Route::get('module', 'Manager\ModuleController@listView')->name('admin.module');
    Route::get('module/edit/{id?}', 'Manager\ModuleController@getItem')->name('admin.module_edit')->where('id', '[0-9]+');
    Route::post('module/edit/{id?}', 'Manager\ModuleController@postItem')->name('admin.module_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'module/delete',  'Manager\ModuleController@delete')->name('admin.module_delete');
    /// role
    Route::get('role', 'Manager\RoleController@listView')->name('admin.role');
    Route::get('role/edit/{id?}', 'Manager\RoleController@getItem')->name('admin.role_edit')->where('id', '[0-9]+');
    Route::post('role/edit/{id?}', 'Manager\RoleController@postItem')->name('admin.role_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'role/delete',  'Manager\RoleController@delete')->name('admin.role_delete');
    /// user
    Route::get('user', 'Manager\UsersController@listView')->name('admin.user');
    Route::get('user/edit/{id?}', 'Manager\UsersController@getItem')->name('admin.user_edit')->where('id', '[0-9]+');
    Route::post('user/edit/{id?}', 'Manager\UsersController@postItem')->name('admin.user_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'user/delete',  'Manager\UsersController@delete')->name('admin.user_delete');
    Route::get('user/profile', 'Manager\UsersController@profileUser')->name('admin.user_profile');
    Route::post('user/profile', 'Manager\UsersController@postprofileUser')->name('admin.user_profile');
    Route::get('user/changepass', 'Manager\UsersController@changeass')->name('admin.user_changepass');
    Route::post('user/changepass', 'Manager\UsersController@postChangeass')->name('admin.user_changepass');
    /// category
    Route::get('category', 'Manager\CategoryController@listView')->name('admin.category');
    Route::get('category/edit/{id?}', 'Manager\CategoryController@getItem')->name('admin.category_edit')->where('id', '[0-9]+');
    Route::post('category/edit/{id?}', 'Manager\CategoryController@postItem')->name('admin.category_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'category/delete',  'Manager\CategoryController@delete')->name('admin.category_delete');
        // product category
    Route::get('product-category', 'Manager\ProductCategoryController@listView')->name('admin.product_category');
    Route::get('product-category/edit/{id?}', 'Manager\ProductCategoryController@getItem')->name('admin.product_category_edit')->where('id', '[0-9]+');
    Route::post('product-category/edit/{id?}', 'Manager\ProductCategoryController@postItem')->name('admin.product_category_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'product-category/delete', 'Manager\ProductCategoryController@delete')->name('admin.product_category_delete');
    // product brand
    Route::get('product-brand', 'Manager\ProductBrandController@listView')->name('admin.product_brand');
    Route::get('product-brand/edit/{id?}', 'Manager\ProductBrandController@getItem')->name('admin.product_brand_edit')->where('id', '[0-9]+');
    Route::post('product-brand/edit/{id?}', 'Manager\ProductBrandController@postItem')->name('admin.product_brand_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'product-brand/delete', 'Manager\ProductBrandController@delete')->name('admin.product_brand_delete');
	/// trash
    Route::get('trash', 'Manager\TrashController@listView')->name('admin.trash');
    Route::get('trash/show/{id?}', 'Manager\TrashController@getItem')->name('admin.trash_showrestore')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'trash/restore', 'Manager\TrashController@restore')->name('admin.trash_restore');
    Route::match(['GET','POST'],'trash/delete',  'Manager\TrashController@delete')->name('admin.trash_delete');
    /// product
    Route::get('product', 'Manager\ProductController@listView')->name('admin.product');
    Route::get('product/edit/{id?}', 'Manager\ProductController@getItem')->name('admin.product_edit')->where('id', '[0-9]+');
    Route::post('product/edit/{id?}', 'Manager\ProductController@postItem')->name('admin.product_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'product/delete',  'Manager\ProductController@delete')->name('admin.product_delete');
	/// gift
    Route::get('gift', 'Manager\GiftController@listView')->name('admin.gift');
    Route::get('gift/edit/{id?}', 'Manager\GiftController@getItem')->name('admin.giftt_edit')->where('id', '[0-9]+');
    Route::post('gift/edit/{id?}', 'Manager\GiftController@postItem')->name('admin.gift_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'gift/delete',  'Manager\GiftController@delete')->name('admin.gift_delete');
    /// news
    Route::get('news', 'Manager\NewsController@listView')->name('admin.news');
    Route::get('news/edit/{id?}', 'Manager\NewsController@getItem')->name('admin.news_edit')->where('id', '[0-9]+');
    Route::post('news/edit/{id?}', 'Manager\NewsController@postItem')->name('admin.news_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'news/delete',  'Manager\NewsController@delete')->name('admin.news_delete');
    /// banner
    Route::get('banner', 'Manager\BannerController@listView')->name('admin.banner');
    Route::get('banner/edit/{id?}', 'Manager\BannerController@getItem')->name('admin.banner_edit')->where('id', '[0-9]+');
    Route::post('banner/edit/{id?}', 'Manager\BannerController@postItem')->name('admin.banner_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'banner/delete',  'Manager\BannerController@delete')->name('admin.banner_delete');
    /// static
    Route::get('static', 'Manager\StaticController@listView')->name('admin.static');
    Route::get('static/edit/{id?}', 'Manager\StaticController@getItem')->name('admin.static_edit')->where('id', '[0-9]+');
    Route::post('static/edit/{id?}', 'Manager\StaticController@postItem')->name('admin.static_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'static/delete',  'Manager\StaticController@delete')->name('admin.static_delete');
    /// customer
    Route::get('customer', 'Manager\CustomerController@listView')->name('admin.customer');
    Route::get('customer/edit/{id?}', 'Manager\CustomerController@getItem')->name('admin.customer_edit')->where('id', '[0-9]+');
    Route::post('customer/edit/{id?}', 'Manager\CustomerController@postItem')->name('admin.customer_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'customer/delete',  'Manager\CustomerController@delete')->name('admin.customer_delete');
    /// member
    Route::get('member', 'Manager\MemberController@listView')->name('admin.member');
    Route::get('member/edit/{id?}', 'Manager\MemberController@getItem')->name('admin.member_edit')->where('id', '[0-9]+');
    Route::post('member/edit/{id?}', 'Manager\MemberController@postItem')->name('admin.customer_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'member/delete',  'Manager\MemberController@delete')->name('admin.member_delete');
	/// CommentHome
    Route::get('comment', 'Manager\CommentHomeController@listView')->name('admin.comment');
    Route::get('comment/edit/{id?}', 'Manager\CommentHomeController@getItem')->name('admin.comment_edit')->where('id', '[0-9]+');
    Route::post('comment/edit/{id?}', 'Manager\CommentHomeController@postItem')->name('admin.comment_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'comment/delete',  'Manager\CommentHomeController@delete')->name('admin.comment_delete');
    /// CommentProduct
    Route::get('comment-product', 'Manager\CommentController@listView')->name('admin.comment_product');
//    Route::get('comment-product/edit/{id?}', 'Manager\CommentController@getItem')->name('admin.comment_product_edit')->where('id', '[0-9]+');
//    Route::post('comment-product/edit/{id?}', 'Manager\CommentController@postItem')->name('admin.comment_product_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'comment-product/delete',  'Manager\CommentController@delete')->name('admin.comment_product_delete');
    /// Support
    Route::get('support', 'Manager\SupportController@listView')->name('admin.support');
    Route::get('support/edit/{id?}', 'Manager\SupportController@getItem')->name('admin.support_edit')->where('id', '[0-9]+');
    Route::post('support/edit/{id?}', 'Manager\SupportController@postItem')->name('admin.support_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'support/delete',  'Manager\SupportController@delete')->name('admin.support_delete');
    /// policy
    Route::get('policy', 'Manager\PolicyController@listView')->name('admin.policy');
    Route::get('policy/edit/{id?}', 'Manager\PolicyController@getItem')->name('admin.policy_edit')->where('id', '[0-9]+');
    Route::post('policy/edit/{id?}', 'Manager\PolicyController@postItem')->name('admin.policy_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'policy/delete',  'Manager\PolicyController@delete')->name('admin.policy_delete');
	// live chat support
	Route::get('live-chat','Manager\LiveChatController@liveChats')->name('admin.live_chat');
    /// partner
    Route::get('partner', 'Manager\PartnerController@listView')->name('admin.partner');
    Route::get('partner/edit/{id?}', 'Manager\PartnerController@getItem')->name('admin.partner_edit')->where('id', '[0-9]+');
    Route::post('partner/edit/{id?}', 'Manager\PartnerController@postItem')->name('admin.partner_edit')->where('id', '[0-9]+');
    Route::match(['GET','POST'],'partner/delete',  'Manager\PartnerController@delete')->name('admin.partner_delete');

});