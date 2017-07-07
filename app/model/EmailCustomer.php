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

class EmailCustomer extends Model
{
    protected $table = 'emailcustomer';
    protected $primaryKey = 'customer_id';
    public $timestamps  = true;
    const FOLDER = 'users';
    protected $fillable = array(
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_mail',
        'customer_status',
        'created_at',
        'updated_at');

    public static function removeCache($id=0,$key=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_CUSTOMEREMAIL);
            Cache::forget(\Memcaches::CACHE_ALL_CUSTOMEREMAIL_ID.$id);
        }
        if($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_CUSTOMEREMAIL);
            Cache::forget(\Memcaches::CACHE_ALL_CUSTOMEREMAIL_KEY.$key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('customer_id','>',0);
            if(isset($search['name'])&&$search['name']!=''){
                $query->where('customer_name','like','%'.$search['name'].'%');
                $query->orWhere('customer_mail','like','%'.$search['name'].'%');
                $query->orWhere('customer_phone','like','%'.$search['name'].'%');
            }
            if(isset($search['customer_status'])&&$search['customer_status']!=-1){
                $query->where('customer_status',$search['customer_status']);
            }
            $total = $query->count();
            $query->orderby('created_at','desc');
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
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_CUSTOMEREMAIL_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_CUSTOMEREMAIL_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public  static  function  getCustomerByEmail($mail){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_CUSTOMEREMAIL_KEY.$mail):array();
        if (empty($rs)){
            try{
                $query = self::where('customer_id','>',0);
                $query->where('customer_mail',$mail);
                $rs = $query->get();
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_CUSTOMEREMAIL_KEY.$mail,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_CUSTOMEREMAIL):array();
        if (empty($rs)){
            try{
                $query = self::where('customer_id','>',0);
                $query->where('customer_status',\CGlobal::status_show);
                $query->orderby('created_at','desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_CUSTOMEREMAIL,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    if ($item->customer_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->customer_id,$item->customer_mail);
                    }
                    return $item->customer_id;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                echo  $e;die;
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
                if(isset($item->customer_id)&&$item->customer_id>0){
                    self::removeCache($item->customer_id,$item->customer_mail);
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
                    if (isset($item->customer_id)&&$item->customer_id>0){
                        self::removeCache($item->customer_id,$item->customer_mail);
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