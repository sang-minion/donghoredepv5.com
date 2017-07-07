<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 08/2016
* @Version	 : 1.0
*/

namespace App\Http\Controllers\Home;

use App\Http\Controllers\BaseHomController;

use App\model\EmailCustomer;
use App\model\Gift;
use App\model\Order;
use App\model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CartController extends BaseHomController
{
    public function __construct()    {
        \Loader::loadJS('backend/js/cart.js', \CGlobal::$postEnd);
    }

    public function ajaxAddCart(Request $request)   {
        if (empty($_POST)) {
            return redirect()->route('index');
        }
        $pid = (int)$request->pid;
        $pnum = (int)$request->pnum;
        $data = array();
        if ($pid > 0 && $pnum > 0) {
            $result = Product::getById($pid);
            if (sizeof($result) != 0) {
                if (Session::has('cart')) {
                    $data = Session::get('cart');
                    if (isset($data[$pid])) {
                        $data[$pid] += 1;
                    } else {
                        $data[$pid] = 1;
                    }
                } else {
                    $data[$pid] = 1;
                }
                Session::put('cart', $data, 60 * 24);
                echo 1;
            }
        } else {
            if (Session::has('cart')) {
                $data = Session::get('cart');
                if (isset($data[$pid])) {
                    unset($data[$pid]);
                }
                Session::put('cart', $data, 60 * 24);
            }
            echo 'Không tồn tại sản phẩm này';
            exit();
        }
        Session::save();
        exit();
    }

    public function pageOrderCart(Request $request)    {
        $meta_title = $meta_keywords = $meta_description = 'Sản phẩm trong giỏ hàng';
        \Seo::SEOS('', $meta_title, $meta_keywords, $meta_description);
        $dataCart = array();
        //Update Cart
        if (!empty($_POST)) {
            $token = $request->_token;
            if (Session::token() === $token) {
                $updateCart = $request->has('listCart') ? $request->listCart : array();
                $dataCart = Session::get('cart');
                foreach ($updateCart as $pid => $pnum) {
                    if ($pnum <= 0) {
                        if (isset($dataCart[$pid])) {
                            unset($dataCart[$pid]);
                        }
                    } else {
                        if (isset($dataCart[$pid]))
                            $dataCart[$pid] = (int)$pnum;
                    }
                }
                Session::put('cart', $dataCart);
                Session::save();
                unset($_POST);
                return redirect()->route('giohang');
            }
        }
        //End Update Cart
        if (Session::has('cart')) {
            $dataCart = Session::get('cart');
        }
        $header = $this->menu('');
        \Loader::loadTitle('Sản phẩm trong giỏ hàng');
        //Config Page
        $pageNo = (int)$request->has('page') ? $request->page : 1;
        $pageScroll = \CGlobal::num_scroll_page;
        $limit = \CGlobal::max_num_record_order;
        $offset = ($pageNo - 1) * $limit;
        $search = $dataItem = array();
        $total = 0;
        $paging = '';
        if (!empty($dataCart)) {
            $arrId = array_keys($dataCart);
            $paging = '';
            if (!empty($arrId)) {
                $search['product_id'] = $arrId;
                $search['field_get'] = 'product_id,product_alias,product_title,product_price,product_status';
                $dataItem = Product::getOrderCart($search, $limit, $offset, $total);
                $paging = $total > 0 ? \Pagging::getPager($pageScroll, $pageNo, $total, $limit, [], $request->url()) : '';
            }
        }
        return view('home.shoppingcart', array_merge($header, ['dataCart' => $dataCart, 'dataItem' => $dataItem, 'paging' => $paging,'showBtnBuy'=>0]));
    }

    public function deleteOneItemInCart(Request $request)    {

        if (empty($_POST)) {
            return redirect()->route('index');
        }
        $pid = (int)$request->has('pid') ? $request->pid : 0;
        if ($pid > 0) {
            if (Session::has('cart')) {
                $data = Session::get('cart');
                if (isset($data[$pid])) {
                    unset($data[$pid]);
                }
                Session::put('cart', $data, 60 * 24);
                Session::save();
            }
        }
        echo 'ok';
        exit();
    }

    public function deleteAllItemInCart(Request $request)    {
        if (empty($_POST)) {
            return redirect()->route('index');
        }
        $dell = addslashes($request->has('all') ? $request->all : '');
        if ($dell == 'del-all') {
            if (Session::has('cart')) {
                Session::forget('cart');
                Session::save();
            }
        }
        echo 'ok';
        exit();
    }

    public function sendCart(Request $request){
        if (!empty($_POST)) {
            $token = $request->_token;
            if (Session::token() === $token) {
                $this->validate($request, ['txtName' => 'required|string', 'txtMobile' => ['required','regex:/^0(1\d{9}|9\d{8})$/'], 'txtAddress' => 'required|string', 'txtPid' => 'required|numeric|min:1', 'txtPnum' => 'required|numeric|min:1']);
                $txtName = addslashes(trim($request->has('txtName') ? $request->txtName : ''));
                $txtMobile = addslashes(trim($request->has('txtMobile') ? $request->txtMobile : ''));
                $txtAddress = addslashes(trim($request->has('txtAddress') ? $request->txtAddress : ''));
                $txtMessage = addslashes(trim($request->has('txtMessage') ? $request->txtMessage : ''));
                $txtEmail = addslashes(trim($request->has('txtEmail') ? $request->txtEmail : ''));
                $txtPid = $request->has('txtPid') ? (int)$request->txtPid : 0;
                $txtPnum = $request->has('txtPnum') ? (int)$request->txtPnum : 0;
                $txGift = $request->has('txGift') ? $request->txGift : array();
                $txColor = $request->has('$txColor') ? $request->txColor : array();
                if ($txtName != '' && $txtMobile != '' && $txtAddress != '' && $txtPid > 0 && $txtPnum > 0) {
                    $total = 0;
                    $arr_content = array();
                    $gift = array();
                    $dataItem = Product::getById($txtPid);
                    $price = 0;
                    if (sizeof($dataItem) != 0) {
                        $arMTP = $dataItem->product_price_multi != NULL && $dataItem->product_price_multi != '' ? unserialize($dataItem->product_price_multi) : array();
                        if (!empty($arMTP)) {
                            foreach ($arMTP as $k => $v2) {
                                if ($txtPnum >= $k) $price = $v2;
                            }
                        }
                        $price = $price == 0 ? (int)$dataItem->product_price : $price;
                        $item_content = array(
                            'id' => $txtPid,
                            'code' => $dataItem->product_code,
                            'title' => $dataItem->product_title,
                            'num' => $txtPnum,
                            'price' => $price,
                        );
                        $arr_content[] = $item_content;
                        if ($price > 0) {
                            $total += $price * $txtPnum;
                        }
                        if(!empty($txGift)&&$txGift!=NULL){
                            $g = Gift::getById(array_keys($txGift)[0]);
                            if (!empty($g)){
                                $gift['id'] = $g->gift_id;
                                $gift['code'] = $g->gift_code;
                                $gift['title'] = $g->gift_title;
                            }
                        }
                        $data = array(
                            'order_name' => $txtName,
                            'order_total' => $total,
                            'order_phone' => $txtMobile,
                            'order_address' => $txtAddress,
                            'order_mail' => $txtEmail,
                            'product_infor' => serialize($arr_content),
                            'order_note' => $txtMessage,
                            'order_num' => $txtPnum,
                            'order_gift_code' => serialize($gift),
                            'order_resources' => Session::has('resources') ? Session::get('resources') : '',
                            'order_status' => 0,
                            'order_created' => time(),
                        );
                        if (Session::has('member')) {
                            $session_member = Session::get('member');
                            $data['order_cust_id'] = $session_member['member_id'];
                        } else {
                            $data['order_cust_id'] = 0;
                        }
                        //Add Order
                        $query = Order::addItem($data);
                        //Send Mail To Admin And Customer
//                    $this->sendMailOrder($data);
                        //Send Mail To Customer
//                    $this->sendMailOrderToCustomer($txtEmail, $data);

                        //Add Custommer to EmailCustomer
                        $dataCustomer = array(
                            'customer_mail' => $txtEmail,
                            'customer_phone' => $txtMobile,
                            'customer_address' => $txtAddress,
                            'customer_name' => $txtName,
                        );
                        $this->addCustomer($txtEmail, $dataCustomer);
                        return redirect()->route('camondathang');
                    }
                }
			}
		}
		return redirect()->route('index');
	}

	public function pageSendCart(Request $request){
		if (!Session::has('cart')) {
			return redirect()->route('index');
		}

		$meta_title = $meta_keywords = $meta_description = 'Gửi thông tin đơn hàng';
		\Seo::SEOS('', $meta_title, $meta_keywords, $meta_description);

	//		Loader::loadJS('frontend/js/cart.js', CGlobal::$postEnd);
		$header = $this->menu('');
		\Loader::loadTitle('Đặt hàng');
		$dataCart = array();
		if (Session::has('cart')) {
			$dataCart = Session::get('cart');
		}
		//Config Page
		$pageNo = (int)$request->has('page') ? $request->page : 1;
		$pageScroll = \CGlobal::num_scroll_page;
		$limit = \CGlobal::max_num_record_order;
		$offset = ($pageNo - 1) * $limit;
		$search = $dataItem = array();
		$total = 0;
		$paging = '';

		if (!empty($dataCart)) {
			$arrId = array_keys($dataCart);
			if (!empty($arrId)) {
				$search['product_id'] = $arrId;
				$search['field_get'] = 'product_id,product_title,product_content,product_price,product_image,product_status,product_gift_code';
				$dataItem = Product::getOrderCart($search, $limit, $offset, $total);
				$paging = $total > 0 ? \Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search, $request) : '';
			}
		}

		if (!empty($_POST)) {
			$token = $request->_token;
			if (Session::token() === $token) {
				$this->validate($request, ['txtName' => 'required|string', 'txtMobile' => ['required','regex:/^0(1\d{9}|9\d{8})$/'], 'txtAddress' => 'required|string']);
				$txtName = addslashes(trim($request->has('txtName') ? $request->txtName : ''));
				$txtMobile = addslashes(trim($request->has('txtMobile') ? $request->txtMobile : ''));
				$txtAddress = addslashes(trim($request->has('txtAddress') ? $request->txtAddress : ''));
				$txtMessage = addslashes(trim($request->has('txtMessage') ? $request->txtMessage : ''));
				$txtEmail = addslashes(trim($request->has('txtEmail') ? $request->txtEmail : ''));
				$txGift = $request->has('txGift') ? $request->txGift : array();

				$txColor = $request->has('$txColor') ? $request->txColor : array();
				if ($txtName != '' && $txtMobile != '' && $txtAddress != '') {
					$total_num = 0;
					$total = 0;
					$arr_content = array();
					$gift = array();
					foreach ($dataItem as $item) {
						$price = 0;
						foreach ($dataCart as $k => $v) {
							if ($item->product_id == $k) {
								$arMTP = $item->product_price_multi != NULL && $item->product_price_multi != '' ? unserialize($item->product_price_multi) : array();
								if (!empty($arMTP)) {
									foreach ($arMTP as $k => $v2) {
										if ($v >= $k) $price = $v2;
									}
								}
								$price = $price == 0 ? (int)$item->product_price : $price;
								$item_content = array(
									'id' => $item->product_id,
									'code' => $item->product_code,
									'title' => $item->product_title,
									'num' => $v,
									'price' => $price,
								);
								$arr_content[] = $item_content;
								$total_num += $v;
								if ($price > 0) {
									$total += (int)$item->product_price * $v;
								}
								$price = 0;
							}
						}
					}
                    if(!empty($txGift)&&$txGift!=NULL){
                        $g = Gift::getById(array_keys($txGift)[0]);
                        if (!empty($g)){
                            $gift['id'] = $g->gift_id;
                            $gift['code'] = $g->gift_code;
                            $gift['title'] = $g->gift_title;
                        }
                    }
					$data = array(
						'order_name' => $txtName,
						'order_total' => $total,
						'order_phone' => $txtMobile,
						'order_address' => $txtAddress,
						'order_mail' => $txtEmail,
						'product_infor' => serialize($arr_content),
						'order_note' => $txtMessage,
						'order_num' => $total_num,
						'order_gift_code' => serialize($gift),
						'order_resources' => Session::has('resources') ? Session::get('resources') : '',
						'order_status' => 0,
						'order_created' => time(),
					);
					if (Session::has('member')) {
						$session_member = Session::get('member');
						$data['order_cust_id'] = $session_member['member_id'];
					} else {
						$data['order_cust_id'] = 0;
					}
					//Add Order
					$query = Order::addItem($data);

					//Send Mail To Admin And Customer
	//                    $this->sendMailOrder($data);
					//Send Mail To Customer
	//                    $this->sendMailOrderToCustomer($txtEmail, $data);

					//Add Custommer to EmailCustomer
					$dataCustomer = array(
						'customer_mail' => $txtEmail,
						'customer_phone' => $txtMobile,
						'customer_address' => $txtAddress,
						'customer_name' => $txtName,
					);
					$this->addCustomer($txtEmail, $dataCustomer);

					if (Session::has('cart')) {
						Session::forget('cart');
						return redirect()->route('camondathang');
					}
				}
			}
		}
		return view('home.formBuy', array_merge($header, ['dataCart' => $dataCart, 'dataItem' => $dataItem, 'paging' => $paging, 'member' => Session::has('member') ? Session::get('member') : array(),'showBtnBuy'=>0]));
	}

	public function pageThanksBuy(Request $request){
		$meta_title = $meta_keywords = $meta_description = 'Cảm ơn đã mua hàng';
		\Seo::SEOS('', $meta_title, $meta_keywords, $meta_description);
		\Loader::loadTitle('Cảm ơn bạn đã đặt hàng tại Donghoredep.com');
		$header = $this->menu('');
		return view('home.thankforbuy', $header);
	}

	public function sendMailOrder($data){
		if (!empty($data)) {
			$emails = [CGlobal::emailAdmin];
			Mail::send('emails.mailReportOrderToAdmin', array('data' => $data), function ($message) use ($emails) {
				$message->to($emails, 'Order')
					->subject('Đơn hàng từ website ' . date('d/m/Y h:i', time()));
			});
		}
		return true;
	}

	public function sendMailOrderToCustomer($mail = '', $data){
		if ($mail != '' && !empty($data)) {
			$checkRegexEmail = \ValidForm::checkRegexEmail($mail);
			if ($checkRegexEmail) {
				$emails = [$mail];
				Mail::send('emails.mailReportOrderToCustomer', array('data' => $data), function ($message) use ($emails) {
					$message->to($emails, 'Order')
						->subject(ucwords(CGlobal::domain) . ' - Bạn đã đặt mua sản phẩm ' . date('d/m/Y h:i', time()));
				});
			}
		}
		return true;
	}

	public function addCustomer($mail = '', $data = array()){
		if ($mail != '' && !empty($data)) {
			$checkMail = \Validform::checkRegexEmail($mail);
			if ($checkMail) {
				$checkEmailExist = EmailCustomer::getCustomerByEmail($mail);
				if (sizeof($checkEmailExist) == 0) {
					EmailCustomer::addItem($data);
				}
			}
		}
	}

	public static function sessionCart(){
		$num = 0;
		if (Session::has('cart')) {
			foreach (Session::get('cart') as $k => $item) {
				$num += $item;
			}
		}
		return $num;
	}

	public function getPrice(Request $request){
		$txtpNum = $request->has('pnum') ? $request->pnum : 0;
		$txtpId = $request->has('pid') ? $request->pid : 0;
		$price = 0;
		if ($txtpId > 0 && $txtpNum > 0) {
			$p = Product::getById($txtpId);
			if (sizeof($p) != 0) {
				$arMTP = $p->product_price_multi != NULL && $p->product_price_multi != '' ? unserialize($p->product_price_multi) : array();
				if (!empty($arMTP)) {
					foreach ($arMTP as $k => $v) {
						if ($txtpNum >= $k) $price = $v;
					}
				}
				$data = array();
				if (Session::has('cart')) {
					$data = Session::get('cart');
					if (isset($data[$txtpId])) {
						$data[$txtpId] = $txtpNum;
					}
				}
				Session::put('cart', $data, 60 * 24);
				Session::save();
				$price = $price == 0 ? $p->product_price : $price;
			}
		}
		echo $price;
		exit();
	}
}
