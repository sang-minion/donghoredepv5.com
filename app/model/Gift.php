<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class Gift extends Model
{
    protected $table = 'gift';
    protected $primaryKey = 'gift_id';
    public $timestamps  = false;
    const FOLDER = 'gift';
    protected $fillable = array(
        'gift_id',
        'gift_code',
        'gift_alias',
        'gift_title',
        'gift_price_input',
        'gift_price',
        'gift_intro',
        'gift_media',
        'gift_multi_media',
        'gift_status',
		'gift_created');
    public static function removeCache($id=0,$key=''){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_GIFT);
            Cache::forget(\Memcaches::CACHE_GIFT_ID.$id);
        }
        if ($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_GIFT);
            Cache::forget(\Memcaches::CACHE_GIFT_KEY . $key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('gift_id','>',0);
            if(isset($search['gift_title'])&&$search['gift_title']!=''){
                $query->where('gift_title','like','%'.$search['gift_title'].'%');
            }
            if(isset($search['gift_code'])&&$search['gift_code']!=''){
                $query->where('gift_code','like','%'.$search['gift_code'].'%');
            }
            
            if(isset($search['gift_status'])&&$search['gift_status']!=-1){
                $query->where('gift_status',$search['gift_status']);
            }
            $total = $query->count();
            $query->orderby('gift_id','asc');
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
    public static function getIdByKeyword($key = ''){
        $id = 0;
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_GIFT_KEY . $key) : array();
        try {
            if (empty($rs)) {
                $rs = self::where('gift_code', $key)->first();
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_GIFT_KEY . $key, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
        return !empty($rs)?$rs->product_id:$id;
    }
    public static function getById($id=0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_GIFT_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs && \Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_GIFT_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array()){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_GIFT):array();
        if (empty($rs)){
            try{
                $query = self::where('gift_id','>',0);
                $query->where('gift_status',\CGlobal::status_show);
                $query->orderby('gift_id','asc');
                $fil_get = isset($dataget['fil_get'])&&$dataget['fil_get']?explode(',',trim($dataget['fil_get'])):array();
                
                if (!empty($fil_get)){
                    $rs = $query->get($fil_get);
                }else{
                    $rs = $query->get();
                }
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_GIFT,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    if ($item->gift_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->gift_id,$item->gift_code);
                    }
                    return $item->gift_id;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                echo $e;die;
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
                if(isset($item->gift_id)&&$item->gift_id>0){
                    self::removeCache($item->gift_id,$item->gift_code);
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
                    if (isset($item->gift_id)&&$item->gift_id>0){
                        self::removeCache($item->gift_id,$item->gift_code);
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
