<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\BaseHomController;
use App\model\Banner;
use App\model\Category;
use App\model\Partner;
use App\model\CommentHome;
use App\model\CommentProduct;
use App\model\News;
use App\model\Product;
use App\model\Gift;
use App\model\StaticInfor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends BaseHomController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $header = $this->menu('');
        \Loader::loadTitle('Donghoredep.com');
        $meta_title = $meta_keywords = $meta_description = 'đồng hồ giá rẻ , đồng hồ nam giá rẻ , đồng hồ nữ giá rẻ, đồng hồ rẻ đẹp độc';
        \Seo::SEOS('', $meta_title, $meta_keywords, $meta_description);
        $banner_rong = Banner::getAll([], 1, 0);
        $banner_nho = Banner::getAll([], 2, 0);
        $banner_doi = Banner::getAll([], 3, 0);
		$banner_doi2 = Banner::getAll([], 4, 0);
        $total_product_news = 0;
        $total_product_most = 0;
        $total_product_gif = 0;
        $total_product_cheapest = 0;
        $total_product_buy_most = 0;
        $total_product_best = 0;
        $pd_product_news = Product::searchByCondition(['product_news' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_per_page_product_index, 0, $total_product_news);
        $pd_product_most = Product::searchByCondition(['product_most' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_per_page_product_index, 0, $total_product_most);
        $pd_product_gif = Gift::searchByCondition(['gift_status' => \CGlobal::status_show], \CGlobal::num_record_per_page_product, 0, $total_product_gif);
        $pd_product_cheapest = Product::searchByCondition(['product_cheapest' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_per_page_product_index, 0, $total_product_cheapest);
        $pd_product_buy_most = Product::searchByCondition(['product_buy_most' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_per_page_product_index, 0, $total_product_buy_most);
        $pd_product_best = Product::searchByCondition(['product_best' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_per_page_product_index, 0, $total_product_best);

        $cate = Category::getAll(['category_parent_id'=>Category::getIdByKeyword(\CGlobal::key_danh_muc_dh)],100);
        $arProductCate = array();
        foreach ($cate as $item){
            $arp = Product::searchByCondition(['product_cate_id'=>$item->category_id,'product_news'=>\CGlobal::status_show,'product_status'=>\CGlobal::status_show],\CGlobal::num_record_per_page_row_product_index,0,$total);
            $arProductCate[] = ['cate'=>$item,'prd'=>$arp];
        }
        $cateBrand = Category::getAll(['category_parent_id'=>Category::getIdByKeyword(\CGlobal::key_nhan_hieu)],100);
        $commentVote = CommentHome::getAll([],100);
		$partner = Partner::getAll([],100);
        return view('home.index', array_merge($header, ['total_product_news' => $total_product_news, 'pd_product_news' => $pd_product_news,
            'pd_product_most' => $pd_product_most, 'total_product_most' => $total_product_most,
            'pd_product_gif' => $pd_product_gif, 'total_product_gif' => $total_product_gif,
            'pd_product_cheapest' => $pd_product_cheapest, 'total_product_cheapest' => $total_product_cheapest,
            'pd_product_buy_most' => $pd_product_buy_most, 'total_product_buy_most' => $total_product_buy_most,
            'total_product_best' => $total_product_best, 'pd_product_best' => $pd_product_best,
            'banner_rong' => $banner_rong, 'banner_nho' => $banner_nho, 'banner_doi' => $banner_doi, 'banner_doi2' => $banner_doi2,
            'ProductCate'=>$arProductCate,'arBrand'=>$cateBrand,'commentVote'=>$commentVote,'partner'=>$partner]));
    }

    public function details($name = '',$id=0)
    {
        \Loader::loadCSS('libs/slick-slider/slick.css');
        \Loader::loadCSS('libs/slick-slider/slick-theme.css');
        \Loader::loadJS('libs/slick-slider/slick.min.js');
        $banner_right = Banner::getAll([], 2, 0);
        $product = Product::getById($id);
		$arkey = array();
		if(Session::has('pdseen')){
			$arkey = Session::get('pdseen');
			if(!in_array($id,$arkey)){
				$arkey[] = $id;
			}
		}else{
			$arkey[] = $id;
		}
		Session::put('pdseen', $arkey, 60 * 24);
		Session::save();
		$metaimg = !empty($product)?$product->product_media:'';
        $meta_title  =!empty($product)?$product->meta_title:'';$meta_keywords = !empty($product)?$product->meta_keywords:''; $meta_description = !empty($product)?$product->meta_description:'';
        if(!empty($product)){
			\Seo::SEOS($metaimg!=''?\ThumbImg::thumbBaseNormal(Product::FOLDER,$id,$metaimg,800,800,'',true,true,true):'', $meta_title, $meta_keywords, $meta_description);
		}
        $cate = Category::getById(!empty($product) ? $product->product_cate_id : 0);
        $header = $this->menu(!empty($cate) ? $cate->category_keyword : '');
        \Loader::loadTitle(!empty($product) ? $product->product_title : 'Không tìm thấy nội dung bạn yêu cầu');
        $commentVote = CommentHome::getAll([],100);
		$HDMH = StaticInfor::getById(StaticInfor::getIdByKeyword('huong-dan-mua-hang'));
		$CMTPRD = CommentProduct::getAll([],$id,1000);
        $ProductSeen = Product::getOrderCart(['product_id' =>$arkey], \CGlobal::max_num_record_order, 0, $total);
		$sameProduct = Product::searchByCondition(['product_news' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_same_product, 0, $total);
        return view('home.details', array_merge($header, ['banner_right' => $banner_right, 'sameProduct' => $sameProduct,'ProductSeen'=>$ProductSeen, 'product' => $product,'idproduct'=>$id,
		'member' => Session::has('member') ? Session::get('member') : array(),'admin'=>Session::has('user')?Session::get('user'):array(),'cates'=>$cate,'commentVote'=>$commentVote,'HDMH'=>$HDMH,'CMTPRD'=>$CMTPRD]));
    }

    public function category(Request $request,$name = '',$id=0)
    {
        $header = $this->menu($name);
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page_product;
        $offset = ($pageNo - 1) * $limit;
        $cate = Category::getById($id);
        $title = !empty($cate) ? $cate->category_title : 'Không tìm thấy nội dung bạn yêu cầu';
        \Loader::loadTitle($title);
        $metaimg = !empty($cate)?$cate->category_media:'';
        $meta_title  = !empty($cate)?$cate->meta_title:'';$meta_keywords = !empty($cate)?$cate->meta_keywords:''; $meta_description = !empty($cate)?$cate->meta_description:'';
        if(!empty($cate)){
			\Seo::SEOS($metaimg!=''?\ThumbImg::thumbBaseNormal(Category::FOLDER,$id,$metaimg,800,800,'',true,true,true):'', $meta_title, $meta_keywords, $meta_description);
		}
        if ($cate->category_keyword === \CGlobal::key_tin_tuc||$cate->category_keyword === \CGlobal::key_khuyen_mai) {
            $total_product = 0;
            $pd_product = News::searchByCondition(['news_status' => \CGlobal::status_show,'news_key_parent'=>$cate->category_keyword], \CGlobal::num_record_per_page_product, $offset, $total_product);
            $sameProduct = Product::searchByCondition(['product_news' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_same_product, 0, $totalsame);
            $banner_right = Banner::getAll([], 2, 0);
            $paging = \Pagging::getPager($pageScroll,$pageNo,$total_product,$limit,[],$request->url());
            return view('home.pagenews',array_merge($header,['title'=>$title,'pd_product'=>$pd_product,'sameProduct'=>$sameProduct,'banner_right'=>$banner_right,'paging'=>$paging]));
        }

        $banner_nho = Banner::getAll([], 2, 0);
		$banner_giua = Banner::getAll([], 5, 0);
        $total_product_news = 0;
        $total_product_most = 0;
        $total_product_gif = 0;
        $total_product_cheapest = 0;
        $total_product_buy_most = 0;
        $total_product_best = 0;
        $pd_product_news = Product::searchByCondition(['product_cate_id' => $cate->category_id,'product_news' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], $limit, $offset, $total_product_news);
        $pd_product_most = Product::searchByCondition(['product_cate_id' => $cate->category_id,'product_most' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], $limit, $offset, $total_product_most);
        $pd_product_gif = Gift::searchByCondition(['product_cate_id' => $cate->category_id,'gift_status' => \CGlobal::status_show], $limit, $offset, $total_product_gif);
        $pd_product_cheapest = Product::searchByCondition(['product_cate_id' => $cate->category_id,'product_cheapest' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], $limit, $offset, $total_product_cheapest);
        $pd_product_buy_most = Product::searchByCondition(['product_cate_id' => $cate->category_id,'product_buy_most' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], $limit, $offset, $total_product_buy_most);
        $pd_product_best = Product::searchByCondition(['product_cate_id' => $cate->category_id,'product_best' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], $limit, $offset, $total_product_best);

        $total = $total_product_news;
        $total = $total_product_most>$total?$total_product_most:$total;
        $total = $total_product_gif?$total_product_gif:$total;
        $total = $total_product_cheapest?$total_product_cheapest:$total;
        $total = $total_product_buy_most?$total_product_buy_most:$total;
        $total = $total_product_best?$total_product_best:$total;
        $paging = \Pagging::getPager($pageScroll,$pageNo,$total,$limit,[],$request->url());
        $commentVote = CommentHome::getAll([],100);

        return view('home.product',  array_merge($header, ['total_product_news' => $total_product_news, 'pd_product_news' => $pd_product_news,
            'pd_product_most' => $pd_product_most, 'total_product_most' => $total_product_most,
            'pd_product_gif' => $pd_product_gif, 'total_product_gif' => $total_product_gif,
            'pd_product_cheapest' => $pd_product_cheapest, 'total_product_cheapest' => $total_product_cheapest,
            'pd_product_buy_most' => $pd_product_buy_most, 'total_product_buy_most' => $total_product_buy_most,
            'total_product_best' => $total_product_best, 'pd_product_best' => $pd_product_best,
            'banner_nho' => $banner_nho, 'banner_giua' => $banner_giua,'paging'=>$paging,'cate'=>$cate,
            'commentVote'=>$commentVote]));
    }

    public function search(Request $request)
    {
        $header = $this->menu('');
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::num_record_per_page_product;
        $offset = ($pageNo - 1) * $limit;
        $total_product = 0;
        $title = $request->has('key') ? 'sản phẩm : ' . $request->key : 'kết quả tìm kiếm';
        $cate = Category::getById(Category::getIdByKeyword('tim-kiem'));
        \Loader::loadTitle($title);
        $pd_product = Product::searchByCondition(['product_title' => $request->has('key') ? $request->key : '', 'product_status' => \CGlobal::status_show], \CGlobal::num_record_per_page_product, $offset, $total_product);
        $paging = $total_product > 0 ? \Pagging::getPager($pageScroll, $pageNo, $total_product, $limit, [], $request->url()) : '';
        return view('home.search', array_merge($header, ['total_product' => $total_product, 'pd_product' => $pd_product,
            'paging' => $paging, 'title' => $title,'cate'=>$cate,'tk'=>'tk']));
    }

    public function news($name = '',$id=0)
    {
        \Loader::loadCSS('libs/slick-slider/slick.css');
        \Loader::loadCSS('libs/slick-slider/slick-theme.css');
        \Loader::loadJS('libs/slick-slider/slick.min.js');
        $banner_right = Banner::getAll([], 2, 0);
        $product = News::getById($id);
        $header = $this->menu(\CGlobal::key_tin_tuc);
		$header = $this->menu(!empty($product)?$product->news_key_parent:'');
        \Loader::loadTitle(!empty($product) ? $product->news_title : 'Không tìm thấy nội dung bạn yêu cầu');
		$title = !empty($product) ? $product->news_key_parent===\CGlobal::key_tin_tuc?'Tin tức':'Khuyến mãi':'Khuyễn mãi';
        $metaimg = !empty($product)?$product->category_media:'';
        $meta_title  = !empty($product)?$product->meta_title:'';$meta_keywords = !empty($product)?$product->meta_keywords:''; $meta_description = !empty($product)?$product->meta_description:'';
		if(!empty($product)){
			\Seo::SEOS($metaimg!=''?\ThumbImg::thumbBaseNormal(News::FOLDER,$id,$metaimg,800,800,'',true,true,true):'', $meta_title, $meta_keywords, $meta_description);
		}
        $sameProduct = Product::searchByCondition(['product_news' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_same_product, 0, $total);
        return view('home.detailsnews', array_merge($header, ['banner_right' => $banner_right, 'sameProduct' => $sameProduct, 'product' => $product, 'title' => $title]));

    }

    public function support($name = '',$id=0)
    {
        $banner_right = Banner::getAll([], 2, 0);
        $product = StaticInfor::getById(StaticInfor::getIdByKeyword($name));
        $header = $this->menu(\CGlobal::key_tin_tuc);
        \Loader::loadTitle(!empty($product) ? $product->static_title : 'Không tìm thấy nội dung bạn yêu cầu');
        $sameProduct = Product::searchByCondition(['product_news' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_same_product, 0, $total);
        return view('home.detailsnews', array_merge($header, ['banner_right' => $banner_right, 'sameProduct' => $sameProduct, 'product' => $product, 'title' => 'Hỗ trợ khách hàng']));
    }

    public function policy( $name = '',$id=0)
    {
        $banner_right = Banner::getAll([], 2, 0);
        $product = StaticInfor::getById($id);
        $header = $this->menu(\CGlobal::key_tin_tuc);
        \Loader::loadTitle(!empty($product) ? $product->static_title : 'Không tìm thấy nội dung bạn yêu cầu');
        $sameProduct = Product::searchByCondition(['product_news' => \CGlobal::status_show, 'product_status' => \CGlobal::status_show], \CGlobal::num_record_same_product, 0, $total);
        return view('home.detailsnews', array_merge($header, ['banner_right' => $banner_right, 'sameProduct' => $sameProduct, 'product' => $product, 'title' => 'Chính sách chung']));
    }
	
	public function comment(Request $request){
        $c = 0;
		if(!empty($_POST)) {
            $cmt = $request->cmt;
            $pid = $request->pid;
            $rcmtid = $request->idrep;
            $name = $request->name;
            $phone = $request->phone;
            $uid = $request->check;
            $role = $request->check;
            if (Session::token() === $request->_token) {
                if ($uid == 0) {
                    if ($cmt != '' && $pid != 0 && $name != '' && $phone != '' && preg_match("/^0(1\d{9}|9\d{8})$/", $phone)) {
                        $data = ['comment_parent_id' => $rcmtid,
                            'comment_user_id' => 0,
                            'comment_product_id' => $pid,
                            'comment_user_role' => 0,
                            'comment_name' => $name,
                            'comment_phone' => $phone,
                            'comment_content' => addslashes($cmt),
                            'comment_created' => time(),
                            'comment_status' => \CGlobal::status_hide];
                        CommentProduct::saveItem($data, 0);
                        $c = 1;
                    }
                } else {
                    if (Session::has('member') && !empty(Session::get('member'))) {
                        if ($cmt != '' && $pid != 0) {
                            $data = ['comment_parent_id' => $rcmtid,
                                'comment_user_id' => Session::get('member')['member_id'],
                                'comment_product_id' => $pid,
                                'comment_user_role' => 0,
                                'comment_name' => Session::get('member')['member_name'],
                                'comment_phone' => Session::get('member')['member_phone'],
                                'comment_content' => addslashes($cmt),
                                'comment_created' => time(),
                                'comment_status' => \CGlobal::status_show];
                            CommentProduct::saveItem($data, 0);
                            $c = 1;
                        }
                    } else if (Session::has('user') && !empty(Session::get('user'))) {
                        if ($cmt != '' && $pid != 0) {
                            $data = ['comment_parent_id' => $rcmtid,
                                'comment_user_id' => Session::get('user')['user_id'],
                                'comment_product_id' => $pid,
                                'comment_user_role' => Session::get('user')['user_role_id'],
                                'comment_name' => Session::get('user')['name'],
                                'comment_phone' => Session::get('user')['user_phone'],
                                'comment_content' => addslashes($cmt),
                                'comment_created' => time(),
                                'comment_status' => \CGlobal::status_show];
                            CommentProduct::saveItem($data, 0);
                            $c = 1;
                        }
                    }
                }
            }
        }
        echo $c;exit();
	}

}
