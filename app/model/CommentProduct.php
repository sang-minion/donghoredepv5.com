<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 28/06/2017
 * Time: 9:16 SA
 */

namespace App\model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CommentProduct extends Model
{
    protected $table = 'comment';
    protected $primaryKey = 'comment_id';
    public $timestamps  = false;
    const FOLDER = 'comment';
    protected $fillable = array(
        'comment_id',
        'comment_parent_id',
        'comment_user_id',
        'comment_product_id',
        'comment_user_role',
        'comment_name',
        'comment_phone',
        'comment_content',
		'comment_created',
		'comment_status',
        'comment_ip_address');

    public static function removeCache($id=0,$key=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_COMMENTS);
            Cache::forget(\Memcaches::CACHE_ALL_COMMENT_PRD_IDS.$id);
        }
        if($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_COMMENTS);
            Cache::forget(\Memcaches::CACHE_ALL_COMMENT_KEYS.$key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('comment_id','>',0);
            if(isset($search['name'])&&$search['name']!=''){
                $query->where('comment_name','like','%'.$search['name'].'%');
                $query->orWhere('comment_phone','like','%'.$search['name'].'%');
            }
			if(isset($search['comment_product_id'])&&$search['comment_product_id']!=-1){
				$query->where('comment_product_id',$search['comment_product_id']);
			}
            if(isset($search['comment_start']) && isset($search['comment_end']) && $search['comment_start'] !='' && $search['comment_end'] !=''){
                $order_from = \CDate::convertDate($search['comment_start'].' 00:00:00');
                $order_to = \CDate::convertDate($search['comment_end']. ' 23:59:59');
                if($order_to >= $order_from && $order_to > 0){
                    $query->whereBetween('comment_created', array($order_from, $order_to));
                }
            }
            if(isset($search['comment_status'])&&$search['comment_status']!=-1){
                $query->where('comment_status',$search['comment_status']);
            }
            $total = $query->count();
            $query->orderby('comment_created','desc');
            $fil_get = isset($search['fil_get'])&&$search['fil_get']!=''?explode(',',trim($search['fil_get'])):array();
            if (!empty($fil_get)){
                $rs = $query->take($limit)->skip($offset)->get($fil_get);
            }else{
                $rs = $query->take($limit)->skip($offset)->get();
            }
            return $rs;
        }catch (\PDOException $e){
            throw new \PDOException();
        }
    }
    public static function getById($id=0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_COMMENT_PRD_IDS.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_COMMENT_PRD_IDS.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public  static  function  getCommentByName($name){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_COMMENT_KEYS.$name):array();
        if (empty($rs)){
            try{
                $query = self::where('comment_id','>',0);
                $query->where('comment_name',$name);
                $rs = $query->get();
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_COMMENT_KEYS.$name,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$product_id=0,$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_COMMENT_PRD_IDS.$product_id):array();
        if (empty($rs)){
            try{
                $query = self::where('comment_id','>',0);
                if($product_id!=0){
                    $query->where('comment_product_id',$product_id);
                }
                $query->where('comment_status',\CGlobal::status_show);
                $query->orderby('comment_created','desc');
                $fil_get = isset($dataget['fil_get'])&&$dataget['fil_get']?explode(',',trim($dataget['fil_get'])):array();
                if ($limit>0){
                    $query->take($limit);
                }
                if (!empty($fil_get)){
                    $rs = $query->get($fil_get);
                }else{
                    $rs = $query->get();
                }
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_COMMENT_PRD_IDS.$product_id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
	public static function addItem($data = array()){
        if (is_array($data)&&count($data)>0){
            try{
                DB::beginTransaction();
                $item = new self();
                foreach ($data as $k=>$v){
                    $item->$k=$v;
                }
                if ($item->save()){
                    DB::commit();
                    if ($item->comment_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->comment_product_id,$item->comment_name);
                    }
                    return $item->comment_id;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function updateItem($data = array(),$id=0){
        if(is_array($data)&&count($data)>0&&$id>0){
            try{
                DB::beginTransaction();
                $item = self::find($id);
                $item->update($data);
                if(isset($item->comment_id)&&$item->comment_id>0){
                    self::removeCache($item->comment_product_id,$item->comment_name);
                }
                DB::commit();
                return true;
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function deleteItem($id=0){
        if ($id>0){
            try{
                DB::beginTransaction();
                $item = self::find($id);
                if ($item!=null){
                    $item->delete();
                    DB::commit();
                    if (isset($item->comment_id)&&$item->comment_id>0){
                        self::removeCache($item->comment_product_id,$item->comment_name);
                    }
                    return true;
                }
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function saveItem($data=array(),$id=0){
        if ($id>0){
            return self::updateItem($data,$id);
        }else{
            return self::addItem($data);
        }
    }
}