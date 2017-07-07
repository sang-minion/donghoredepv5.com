<?php

/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 30/05/2017
 * Time: 14:35 CH
 */
class CGlobal
{
    static $cssVer = 1.0;
    static $jsVer = 1.0;
    //Position Header, Footer
    public static $postHead = 1;
    public static $postEnd = 2;
    //Add CSS, JS, Meta
    public static $extraHeaderCSS = '';
    public static $extraHeaderJS = '';
    public static $extraFooterCSS = '';
    public static $extraFooterJS = '';
    public static $extraMeta = '';
    public static $textMenu = '';
    public static $breadcrumbtop = '';
    public static $title = '';
    public static $txt_hostline = '';
    public static $txt_zalo = '';
    public static $txt_footer_left = '';

    const domain = 'donghoredep.com';
    const nameSite = 'Đồng hồ giá rẻ';
    const phoneSupport = '097.756.847';
    const emailAdmin = 'tiensang93@gmail.com';

    const nameWebInExcel = 'Donghoredep.com';
    const addressWebInExcel = '15A - Ngõ 178 - Kim Hoa - Đống Đa - Hà Nội';

    const txt403 = 'Không được truy cập...';
    const txt404 = 'Không tìm thấy...';

    const role_admin = 11;
    const role_manager = 2;
    const role_customer = 0;

    const key_nhan_hieu = 'thuong-hieu-dong-ho';
    const key_danh_muc_dh = 'danh-muc-dong-ho';
    const key_chi_nhanh_dai_ly ='chi-nhanh-dai-ly';
    const key_ho_tro_khach_hang='ho-tro-khach-hang';
    const key_chinh_sach_chung = 'chinh-sach-chung';
    const key_tin_tuc = 'tin-tuc';
	const key_khuyen_mai = 'khuyen-mai';
    const key_hostline = 'hostline';
    const key_zalo = 'zalo';
    const key_footer_left = 'footer-left';
    const key_footer_bottom = 'footer-bottom';
	const key_fanpage_fabook = 'fanpage-fabook';
	const key_box_chat_support = 'box-chat-online';
	const key_link_facebook = 'link-facebook';
	const key_link_google = 'link-google';
	const key_kenh_youtube = 'kenh-youtube';
	const key_live_chat = 'live-chat';
	const key_counter = 'flag-counter';

    const num_record_per_page = 30;
    const num_record_per_page_minimum = 12;
    const num_scroll_page = 2;

    const num_record_per_page_product = 25;
    const num_record_per_page_product_index = 10;
    const num_record_per_page_row_product_index = 3;
    const num_record_same_product = 5;
    const num_record_product_hot_random = 5;

    const num_record_per_page_news = 10;
    const num_record_same_news = 10;

    const max_num_record_order = 100;

    const num_record_max_excel = 5000;

    const status_hide = 0;
    const status_show = 1;
    const status_success = 2;
    const status_cancel = 3;
    const status_die = 4;
    const status_img = 1800;

    //Size Img
    public static $arrSizeImg = array(
        '2'=>'200x200',
        '4'=>'400x400',
        '6'=>'600x600',
        '8'=>'800x800',
    );
    //Folder
    const IMAGE_ERROR = 133;
}