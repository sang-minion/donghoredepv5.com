<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 06/07/2017
 * Time: 16:12 CH
 */

namespace App\Http\Controllers\Manager;


use App\Http\Controllers\BaseAdminController;
use App\model\StaticInfor;
use Illuminate\Http\Request;

class LiveChatController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function liveChats(Request $request){
        $this->menu();
        $this->title('Live chat chăm sóc khách hàng');
        $this->breadcrumb([['title' => 'Live chat chăm sóc khách hàng', 'link' => route('admin.live_chat'), 'active' => 'active']]);
        $chat = StaticInfor::getById(StaticInfor::getIdByKeyword(\CGlobal::key_live_chat));
        return view('Manager.live_chat.live1',['chat'=>$chat]);
    }

}