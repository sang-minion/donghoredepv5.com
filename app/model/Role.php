<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    public $timestamps = false;
    const FOLDER = 'role';
    protected $fillable = array(
        'role_id',
        'role_title',
        'role_permission',
        'role_order_no',
        'role_status',
        'role_created',
        'allow_upload');

    public static function removeCache($id=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_ROLE);
            Cache::forget(\Memcaches::CACHE_ROLE_ID.$id);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('role_id','>',0);
            if(isset($search['role_title'])&&$search['role_title']!=''){
                $query->where('role_title','like','%'.$search['role_title'].'%');
            }
            if(isset($search['role_status'])&&$search['role_status']!=-1){
                $query->where('role_status',$search['role_status']);
            }
            $total = $query->count();
            $query->orderby('role_order_no','asc');
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
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ROLE_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ROLE_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_ROLE):array();
        if (empty($rs)){
            try{
                $query = self::where('role_id','>',0);
                $query->where('role_status',\CGlobal::status_show);
                $query->orderby('role_order_no','asc');
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
                    Cache::put(\Memcaches::CACHE_ALL_ROLE,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    if ($item->role_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->role_id);
                    }
                    DB::commit();
                    return $item->role_id;
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
                if(isset($item->role_id)&&$item->role_id>0){
                    self::removeCache($item->role_id);
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
                    if (isset($item->role_id)&&$item->role_id>0){
                        self::removeCache($item->role_id);
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
