<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Partner extends Model
{
    protected $table = 'partner';
    protected $primaryKey = 'partner_id';
    public $timestamps  = false;
    const FOLDER = 'partner';
    protected $fillable = array(
        'partner_id',
        'partner_title',
        'partner_website',
        'partner_address',
        'partner_logo',
        'partner_intro',
        'partner_status',
        'partner_created');
    public static function removeCache($id=0,$key=''){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_PARTNER);
            Cache::forget(\Memcaches::CACHE_PARTNER_ID.$id);
        }
        if($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_PARTNER);
            Cache::forget(\Memcaches::CACHE_PARTNER_KEY.$key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('partner_id','>',0);
            if(isset($search['partner_title'])&&$search['partner_title']!=''){
                $query->where('partner_title','like','%'.$search['partner_title'].'%');
            }
            if(isset($search['partner_status'])&&$search['partner_status']!=-1){
                $query->where('partner_status',$search['partner_status']);
            }
            $total = $query->count();
            $query->orderby('partner_id','asc');
            $fil_get = isset($search['fil_get'])&&$search['fil_get']!=''?explode(',',trim($search['fil_get'])):array();
            if (!empty($fil_get)){
                $rs = $query->take($limit)->skip($offset)->get($fil_get);
            }else{
                $rs = $query->take($limit)->skip($offset)->get();
            }
            return $rs;
        }catch (\PDOException $e){
            echo  $e;die;
            throw new \PDOException();
        }
    }
    public static function getIdByKeyword($key = ''){
        $id = 0;
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_PARTNER_KEY . $key) : array();
        try {
            if (empty($rs)) {
                $rs = self::where('partner_title', $key)->first();
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_PARTNER_KEY . $key, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
        return !empty($rs)?$rs->news_id:$id;
    }
    public static function getById($id=0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_PARTNER_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_PARTNER_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_PARTNER):array();
        if (empty($rs)){
            try{
                $query = self::where('partner_id','>',0);
                $query->where('partner_status',\CGlobal::status_show);
                $query->orderby('partner_id','asc');
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
                    Cache::put(\Memcaches::CACHE_ALL_PARTNER,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    DB::connection()->getPdo()->commit();
                    if ($item->partner_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->partner_id,$item->partner_title);
                    }
                    return $item->partner_id;
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
                if(isset($item->partner_id)&&$item->partner_id>0){
                    self::removeCache($item->partner_id,$item->partner_title);
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
                    if (isset($item->partner_id)&&$item->partner_id>0){
                        self::removeCache($item->partner_id,$item->partner_title);
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
